<?php 
/**
 * Profile_CCT class.
 */
class Profile_CCT {
	static private $classobj            = NULL; // refence for itself
	static public  $textdomain          = NULL;
	static public  $action              = NULL;
	static public  $settings            = NULL; // renamed from  $settings_options
	static public  $form_fields         = NULL;
	static public  $taxonomies          = NULL;
	static public  $is_main_query       = false;
	static public  $form_field_options  = NULL;
	static public  $option              = NULL;
	static public  $current_form_fields = NULL; // stores the current state of the form field... the labels and if it is on the banch... 
    
	static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'get_object' ) );
		register_activation_hook(   PROFILE_CCT_BASE_FILE, array( __CLASS__, 'install'    ) );
		register_deactivation_hook( PROFILE_CCT_BASE_FILE, array( __CLASS__, 'deactivate' ) );
		register_uninstall_hook(    PROFILE_CCT_BASE_FILE, array( __CLASS__, 'uninstall'  ) );
	}
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	function __construct () {
		add_action( 'init', array( $this, 'load' ) );
		$this->settings   = $this->get_settings( 'settings' );
		$this->taxonomies = $this->get_settings( 'taxonomy' );
		
	}
	
	/**
	 * get_object function.
	 * 
	 * @access public
	 * @return void
	 */
	function get_object() {
		if ( self::$classobj === NULL ):
			self::$classobj = new self;
        endif;
        
		return self::$classobj;
	}
	
	/**
	 * init function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public function load() {
		if ( is_admin() ):
			add_action( 'wp_dashboard_setup',         array( $this, 'add_dashboard_widgets' ) );
			add_action( 'edit_form_advanced',         array( $this, 'edit_post_advanced' ) );
			add_action( 'add_meta_boxes_profile_cct', array( $this, 'edit_post' ) );
			add_action( 'edit_form_after_title', array( $this, 'edit_form_after_title' ), 10, 1 );
			add_filter( 'post_row_actions',           array( __CLASS__, 'modify_row_actions' ), 10, 2);
			
			
			//add_filter( 'role_has_cap', array( $this, 'has_cap' ), 10, 3 ); //$this->capabilities, $cap, $this->name )
			add_filter( 'user_has_cap', array( $this, 'has_cap' ), 0, 3 );
			
		else:
			add_filter('the_content', array( $this,'control_filter'), 1);
			add_filter('the_excerpt', array( $this,'control_filter'), 1);
			
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_style( 'profile-cct', PROFILE_CCT_DIR_URL.'/css/profile-cct.css' );
		endif;
		
		add_action( 'pre_get_posts', array( $this, 'sort_posts' ) );
		add_action('wp_ajax_profile-cct-export', array( $this, 'export_settings') );
		add_action('wp_ajax_nopriv_profile-cct-export', array( $this, 'export_settings') );
		
		add_filter( 'wp_import_post_meta', array( $this, 'wp_import_post_meta'), 10, 3);
				
		$this->register_profiles();
		$this->update();
		$this->load_fields();
		
		if ( function_exists( 'add_image_size' ) ) { 
			add_image_size( 'profile-image', $this->settings['picture']['width'], $this->settings['picture']['height'] ); //300 pixels wide (and unlimited height)
		}
	}
	

	
	/**
	 * wp_import_post_meta function.
	 * 
	 * @access public
	 * @param mixed $postmeta
	 * @param mixed $post_id
	 * @param mixed $post
	 * @return void
	 */
	function wp_import_post_meta( $postmeta, $post_id, $post) {
		
		if( 'profile_cct' == $post['post_type']):
			$count = 0;
			foreach( $postmeta as $meta ):
				
				if( 'profile_cct' == $meta['key'] )
					$counter = $count;
					
				$count++;
			endforeach;
			
			// solution found via this ticket - https://core.trac.wordpress.org/ticket/23275
			$value = maybe_unserialize( str_replace( array("\r\n", "\r", "\n"), "\r\n", $postmeta[$counter]['value'] ) ); 
			
			if( empty( $value ) ):
				$value = maybe_unserialize( 
 					preg_replace( // e flag deprecated in PHP 5.5.0 I think 
 					'!s:(\d+):"(.*?)";!se', 
 					"'s:'.strlen('$2').':\"$2\";'", 
 					$postmeta[$counter]['value']  
 					));
			endif;
				
			$postmeta[$counter]['value'] = $value;
			
		endif;
		return $postmeta;
	}
	
	/**
	 * control_filter function.
	 * 
	 * @access public
	 * @param mixed $content
	 * @return void
	 */
	function control_filter( $content ) {
		global $post;
		
		if ( 'profile_cct' == $post->post_type ):
			remove_filter( 'the_content', 'wpautop' );
			remove_filter( 'the_excerpt', 'wpautop' );
		else:
			add_filter( 'the_content', 'wpautop' );
			add_filter( 'the_excerpt', 'wpautop' );
		endif;
		
		return $content;
	
	}
	
	/*
	 * This function should modify old data to accomodate any changes to how it is stored.
	 */
	function update() {
		$global_settings = get_site_option( PROFILE_CCT_SETTING_GLOBAL, array() );
		$bench_fields = get_option( 'Profile_CCT_form_fields_bench', array() );
		$side_fields = get_option( 'Profile_CCT_form_fields_side', array() );
		
		if ( ! isset( $this->settings['version']['general'] ) || version_compare( '1.3.1', $this->settings['version']['general'], '>' ) ):
			if ( defined('WP_DEBUG_LOG') && WP_DEBUG_LOG):
				error_log("Profile CCT: Reregistered custom content type. To fix a bug for 1.3.1");
			endif;
			
			$this->register_profiles();
			flush_rewrite_rules();
		endif;
		
		if ( ! isset( $this->settings['version']['clone_fields'] ) || version_compare( '1.3', $this->settings['version']['clone_fields'], '>' ) ):
			if ( defined('WP_DEBUG_LOG') && WP_DEBUG_LOG):
				error_log("Profile CCT: Updated cloned fields to 1.3 standards");
			endif;
			
			if ( isset( $this->settings['clone_fields'] ) ):
				foreach ( $this->settings['clone_fields'] as $key => $field ):
					if ( is_numeric( $key ) ):
						unset( $this->settings['clone_fields'][$key] );
					endif;
				endforeach;
			endif;
			
			if ( isset( $global_settings['clone_fields'] ) ):
				foreach ( $global_settings['clone_fields'] as $key => $field ):
					if ( ! is_array( $field['blogs'] ) ):
						$blogs = array();
						
						$blog_ids = explode( ',', $field['blogs'] );
						foreach ( $blog_ids as $id ):
							if ( $id != '' ) $blogs[$id] = true;
						endforeach;
						
						unset( $field['blogs'] );
						$this->settings['clone_fields'][$field['type']] = $field;
						
						$global_settings['clone_fields'][$key]['blogs'] = $blogs;
					endif;
				endforeach;
			endif;
		endif;
		
		if ( ! isset( $this->settings['version']['taxonomy'] ) || version_compare( '1.3', $this->settings['version']['taxonomy'], '>' ) ):
			foreach ( $bench_fields as $key => $field ):
				if ( Profile_CCT::string_starts_with( $field['type'], PROFILE_CCT_TAXONOMY_PREFIX ) ):
					$side_fields[] = $field;
					unset( $bench_fields[$key] );
				endif;
			endforeach;
		endif;
		
		$this->settings['version']['general'] = PROFILE_CCT_VERSION;
		$this->settings['version']['clone_fields'] = PROFILE_CCT_VERSION;
		$this->settings['version']['taxonomy'] = PROFILE_CCT_VERSION;
		update_option( 'Profile_CCT_form_fields_bench', $bench_fields );
		update_option( 'Profile_CCT_form_fields_side', $side_fields );
		update_option( PROFILE_CCT_SETTINGS, $this->settings );
		update_site_option( PROFILE_CCT_SETTING_GLOBAL, $global_settings );
	}
	
	/**
	 * sort_posts function.
	 * 
	 * @access public
	 * @param mixed $query
	 * @return void
	 */
	function sort_posts( $query ) {
		$is_taxonomy = false;
		foreach ( $this->taxonomies as $taxonomy ):
			if ( is_tax( Profile_CCT_Taxonomy::id( $taxonomy['single'] ) ) ):
				$is_taxonomy = true;
				break;
			endif;
		endforeach;
		
		if ( $query->is_main_query() && ( $is_taxonomy || is_post_type_archive( 'profile_cct' ) ) && ! is_admin() ):
			$order = in_array( $this->settings['sort_order'], array( 'ASC', 'DESC' ) ) ? $this->settings['sort_order'] : null;
			
			switch ( $this->settings['sort_order_by'] ):
				case "manual":
					$query->set( 'orderby', 'menu_order' );
					if ( is_null( $order ) ) $order = 'ASC';
					break;
				case "first_name":
					$query->set( 'orderby', 'title' );
					if ( is_null( $order ) ) $order = 'ASC';
					break;
				case "last_name":
					$query->set( 'orderby', 'meta_value' );
					$query->set( 'meta_key', 'profile_cct_last_name' );
					if ( is_null( $order ) ) $order = 'ASC';
					break;
				case "date":
					$query->set( 'orderby', 'date' );
					if ( is_null( $order ) ) $order = 'DESC';
					break;
				default:
					$order = 'DESC';
			endswitch;
			
			$query->set( 'order', $order );
		endif;
	}
	
	/**
	 * export_settings function.
	 * 
	 * @access public
	 * @return void
	 */
	function export_settings(){
		global $blog_id;
		$md5 =  md5( $blog_id );
		if( $md5 =! $_GET['s'] )
		return ;
		
		$settings = array( 'taxonomy'=> $this->taxonomies, 'settings' => $this->settings, 'version' =>  $this->version());
	
		
		foreach ( array( "form", "page", "list" ) as $where ):
			// delete all the fields
			foreach ( self::get_contexts( $where ) as $context ):
				$con_settings = self::get_option( $where, 'fields', $context );
				if( !empty( $con_settings ) ) {
					$settings[$where."_fields_".$context] = $con_settings;
				}
				
			endforeach;
			
			// bench
			$bench = self::get_option( $where, 'fields', 'bench' );
			if(  !empty($bench) ) {
				$settings[ $where. '_fields_bench' ] = $bench;
			}
			
			// tabs
			$tabs = self::get_option( $where, 'tabs' );
			if( !empty( $tabs ) ) {			
				$settings[ $where. '_tabs_normal' ] = $tabs;
			}

		endforeach;
				
		echo json_encode( $settings );
		die();
	}
	/**
	 * register_profiles function.
	 * 
	 * @access public
	 * @return void
	 */
	function register_profiles() {
		if ( empty( $this->settings['slug'] ) ):
			$slug = 'person';
		else:
			$slug = $this->settings['slug'];
		endif;
		
		$labels = array(
			'name'               => _x( 'Profiles', 'profile_cct' ),
			'singular_name'      => _x( 'Profile', 'profile_cct' ),
			'add_new'            => _x( 'Add New', 'profile_cct' ),
			'add_new_item'       => _x( 'Add New Profile', 'profile_cct' ),
			'edit_item'          => _x( 'Edit Public Profile', 'profile_cct' ),
			'new_item'           => _x( 'New Profile', 'profile_cct' ),
			'view_item'          => _x( 'View Profile', 'profile_cct' ),
			'search_items'       => _x( 'Search Profiles', 'profile_cct' ),
			'not_found'          => _x( 'No profiles found', 'profile_cct' ),
			'not_found_in_trash' => _x( 'No profiles found in Trash', 'profile_cct' ),
			'parent_item_colon'  => _x( 'Parent Profile:', 'profile_cct' ),
			'menu_name'          => _x( 'Profiles', 'profile_cct' ),
		);
		
		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'menu_icon'           => PROFILE_CCT_DIR_URL.'/icon.png',
			'supports'            => array( 'author' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite' => array(
				'slug'       => $slug,
				'with_front' => true,
				'feeds'      => true,
				'pages'      => true,
			),
			'capabilities' => array(
				'edit_post'           => 'edit_profile_cct', // used for has public profile
				'edit_posts'          => 'edit_profiles_cct',
				'edit_others_posts'   => 'edit_others_profile_cct',
				'publish_posts'       => 'publish_profile_cct',
				'read_post'           => 'read_profile_cct',
				'read_private_posts'  => 'read_private_profile_cct',
				'delete_post'         => 'delete_profile_cct',
				'delete_others_posts' => 'delete_others_profile_cct',
			),
		);
        
		register_post_type( 'profile_cct', $args );
	}
	
	
	/**
	 * has_cap function.
	 * 
	 * @access public
	 * @param mixed $caps
	 * @param mixed $cap
	 * @param mixed $name
	 * @return void
	 */
	function has_cap( $caps, $cap, $name ){
		if( $caps['publish_profile_cct'] && in_array( 'upload_files', $cap) )
			$caps['upload_files'] = true;
		
		return $caps;
	}
	
	/**
	 * load_fields function.
	 * 
	 * @access public
	 * @return void
	 */
	function load_fields() {
		// include all files in the fields folder
		if ( $handle = opendir( PROFILE_CCT_DIR_PATH . 'views/fields/' ) ) :
			// This is the correct way to loop over the directory.
			while ( false !== ( $file = readdir( $handle ) ) ):
				if ( substr($file,0,1) != "." ):
					if ( ! is_dir( PROFILE_CCT_DIR_PATH . 'views/fields/' . $file ) ):
						require( PROFILE_CCT_DIR_PATH . 'views/fields/' . $file );
					endif;
				endif;
			endwhile;
			closedir( $handle );
		endif;
	}
	
	/**
	 * get_settings function.
	 * 
	 * @access public
	 * @param string $type (default: 'settings')
	 * @return void
	 */
	function get_settings( $type = 'settings' ) {
		// if non exist get the default settings 
		$settings = get_option( 'Profile_CCT_'.$type );
		if ( $settings = get_option( 'Profile_CCT_'.$type ) ):
			// make sure that we always retun 
			return wp_parse_args( $settings, $this->get_default_settings($type) );
        else:
            return $this->get_default_settings($type);
        endif;
	}
	
	/**
	 * get_default_settings function.
	 * 
	 * @access public
	 * @param string $type (default: 'settings')
	 * @return void
	 */
	function get_default_settings( $type = 'settings' ) {
        // load the default options array 
        require( PROFILE_CCT_DIR_PATH.'default-options.php' );
        
        return $option[$type];
	}
	
	/**
	 * delete_all_settings function.
	 * delets every settings 
	 * @access public
	 * @return void
	 */
	function delete_all_settings(){
		// only administator can do this… 
		if ( current_user_can('administrator') ):
			foreach ( array( "form", "page", "list" ) as $where ):
				// delete all the fields
				foreach ( self::get_contexts( $where ) as $context ):
					self::delete_option( $where, 'fields', $context );
				endforeach;
				
				// lets not forget the banch 
				self::delete_option( $where, 'fields', 'bench' );
				
				// also delete all the tabs 
				self::delete_option( $where, 'tabs' );
			endforeach;
            
			// finally delete the settings data 
			delete_option( PROFILE_CCT_SETTINGS );
			
			// also delete all the taxonomies 
			delete_option( PROFILE_CCT_SETTING_TAXONOMY );
			
			// also the global settings only super admin can do this
			if (current_user_can( 'manage_sites' ) && $_GET['delete_profile_cct_data'] == "DELETE-GLOBAL" ):
				delete_site_option( PROFILE_CCT_SETTING_GLOBAL );
            endif;
		endif;
	}
	
	/**
	 * delete_option function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @param string $fields_or_tabs. (default: 'fields')
	 * @param string $context. (default: 'normal')
	 * @return void
	 */
	function delete_option( $type = 'form', $fields_or_tabs = 'fields', $context = 'normal' ) {
		unset( $this->option[$type][$fields_or_tabs][$context] );
		return delete_option( 'Profile_CCT_'.$type.'_'.$fields_or_tabs.'_'.$context );
	}
    
	/**
	 * install function.
	 * gets run on plugin install
	 * 
	 * @access public
	 * @return void
	 */
    static function install() {
		$field = self::get_object();
		$field->register_profiles();
		flush_rewrite_rules();
		
		// set up the permissions
		if ( ! is_array( $field->settings['permissions'] ) ):
			$settings = $field->get_default_settings( 'settings' );
			$field->settings['permissions'] = $settings['permissions'];
		endif;
		
		foreach ( $field->settings['permissions'] as $user => $permission_array ):
			$role = get_role( $user );
			
			foreach ($permission_array as $permission => $can):
				// add the new capability
				if ( $field->settings['permissions'][$user][$permission] ):
					$role->add_cap( $permission );
				else: // or remove it
					$role->remove_cap( $permission );
				endif;
			endforeach;
		endforeach;
		
        update_option( PROFILE_CCT_SETTING_GLOBAL, $field->settings );
	}
    
	/**
	 * deactivate function.
	 * 
	 * @access public
	 * @return void
	 */
	static function deactivate() {
		// remove permissions
		$profile = self::get_object();
		$default = $profile->get_default_settings( 'settings' );
		
		foreach ( $default['permissions'] as $user => $permission_array ):
			$role = get_role( $user );
			
			foreach ( $permission_array as $permission => $can ):
				$role->remove_cap( $permission );	
			endforeach;
		endforeach;
	}
	
	/**
	 * uninstall function.
	 * 
	 * @access public
	 * @return void
	 */
	static function uninstall() {
		// remove permissions
		$profile = self::get_object();
		$profile->deactivate();
		$profile->delete_all_settings();
	}
	
	public static function version() {
		return PROFILE_CCT_VERSION;
	}
	
	function add_dashboard_widgets() {
		wp_add_dashboard_widget('profile_cct', 'Your Public Profile', array( $this, 'get_dashboard_widget_content' ) );
		
		// Try to reorganize the meta boxes to move ours to the top.
		global $wp_meta_boxes;
		
		$profile_cct_widget = $wp_meta_boxes['dashboard']['normal']['core']['profile_cct'];
		unset( $wp_meta_boxes['dashboard']['normal']['core']['profile_cct'] );
		$wp_meta_boxes['dashboard']['side']['core'] = array_merge( array('profile_cct' => $profile_cct_widget), $wp_meta_boxes['dashboard']['side']['core'] );
	}
	
	/**
	 * get_dashboard_widget_content function.
	 * 
	 * @access public
	 * @return void
	 */
	function get_dashboard_widget_content() {
		$profile = self::get_user_profile();
		
		if ( empty( $profile ) ):
			?>
			<div style="color: darkred;">
				You do not have a profile.
				<br /><br />
			</div>
			<div>
				<a class="button-primary" href="<?php echo admin_url('profile.php?page=public_profile'); ?>">Create New</a>
			</div>
			<?php
		else:
			?>
			<div style="overflow: hidden">
				<?php
				echo $profile->post_excerpt;
				?>
				<div class="actions">
					<a class="button" href="<?php echo admin_url('users.php?page=public_profile'); ?>">Edit</a>
					<a class="button-primary" href="<?php echo get_permalink( $profile->ID ); ?>">View</a>
				</div>
			</div>
			<?php
		endif;
	}
	
	/**
	 * get_user_profile function.
	 * 
	 * @access public
	 * @return void
	 */
	function get_user_profile() {
		$current_user = wp_get_current_user();
		if ( ! ( $current_user instanceof WP_User ) ):
			if ( defined('WP_DEBUG_LOG') && WP_DEBUG_LOG):
				error_log("Profile CCT Dashboard Widget: Could not retrieve current user.");
			endif;
			return;
		endif;
		
		$arguments = array(
			'post_type'      => 'profile_cct',
			'author'         => $current_user->ID,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		);
        
		$query = new WP_Query( $arguments );
		$results = $query->get_posts();
		$profile = $results[0];
		return $profile;
	}
    
	/**
	 * get_contexts function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @return void
	 */
	function get_contexts( $type = 'form' ) {
		$contexts = $this->default_shells( $type );
		$id = array_search( 'tabs', $contexts );
        
		if ( is_numeric( $id ) ):
			$tabs = $this->get_option( $type, 'tabs' );
            
            if ( is_array( $tabs ) ):
                $count = 1;
                foreach ( $tabs as $tab ):
                    $contexts[] = "tabbed-".$count;
                    $count++;
                endforeach;
            endif;
            unset( $contexts[$id] );
            $contexts = array_values( $contexts );
		endif;
        
		return $contexts;
	}

	/**
	 * default_shells function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @return array
	 */
	function default_shells( $type = 'form' ) {
		switch ( $type ) {
		case 'form':
			return array( 'normal', 'side', 'tabs' );
		case 'page':
			return array( 'header', 'tabs', 'bottom' );
		case 'list':
			return array( 'normal' );
		}
	}
	
	/**
	 * get_option function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @param string $subtype. (default: 'fields'), can be 'fields' or 'tabs'
	 * @param string $context. (default: 'normal')
	 * @return void
	 */
	function get_option( $type = 'form', $subtype = 'fields', $context = 'normal' ) {
		if ( is_array( $this->option[$type][$subtype][$context] ) ):
			return $this->option[$type][$subtype][$context]; // return the value from the stored options array.
		else:
			// Get the option using Wordpress' built-in function.
			
			$options = get_option( 'Profile_CCT_'.$type.'_'.$subtype.'_'.$context );
			
			// Check for success. If this if statement fails, it indicates that the option is not present in the database.
			if ( ! is_array( $options ) ):
                //Get the default values for this option type.
				$default = $this->default_options( $type );
                
				if ( $subtype == 'fields' ):
					$options = $default[$type][$subtype][$context];
				else:
					$options = $default[$type][$subtype];
                endif;
  			endif;
            
            if ( $context == 'bench' ):
                // Check to see if the plugin has been updated since this code last ran. And if so, merge the settings.
                $perform_merge = false;
                if ( ! isset( $this->settings['version'][$type][$subtype][$context] ) ): // Is there no version setting?
                    $perform_merge = true;
                elseif ( $this->version() > $this->settings['version'][$type][$subtype][$context] ): // Is the stored version less than the current version?
                    $perform_merge = true;
                endif;
                
                // Merge the stored settings with any new settings introduced by the new version.
                if ( $perform_merge ):
                    $new_fields = $this->default_options('new_fields');
                    
                    // Lets add the new fields from this version to the bench.
                    if ( is_array($new_fields[$this->version()]) ):
                        foreach ( $new_fields[$this->version()] as $field ):
                            if ( in_array( $type, $field['where'] ) ):
                                $options[] = $field['field'];
                            endif;
                        endforeach;
                    endif;
                endif;
			endif;
		endif;
        
		$this->option[$type][$subtype][$context] = $options;
		return $options;
	}
    
	/**
	 * default_options function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @return void
	 */
	function default_options( $type = 'form' ) {
		require( PROFILE_CCT_DIR_PATH.'default-options.php' ); // $options is defined in this file.
		return apply_filters( 'profile_cct_default_options', $option, $type );
	}
	
	
	function edit_form_after_title( $post ) {
		
		if( 'profile_cct' == $post->post_type ): ?>
		<a class="button" style="position:absolute; top:45px; left:235px;" href="<?php echo get_permalink($post->ID ); ?>">View Profile</a> 
		<?php
		endif;
	}
	/**
	 * edit_post function.
	 *
	 * @access public
	 * @return void
	 */
	function edit_post() {
		
        Profile_CCT_Admin::$page = 'form';
		Profile_CCT_Admin::recount_field( 'form' );
		
		global $post, $post_new_file, $pagenow, $current_user, $post_type_object;
		$post_new_file = '#';
		
		if ( (int) $post->post_author != $current_user->ID && ! current_user_can( 'edit_others_profile_cct' ) ):
			wp_die( 'You are not allow to edit this profile.' );
		endif;
		
		$user_data = get_post_meta( $post->ID, 'profile_cct', true );
		
		remove_meta_box( 'submitdiv', 'profile_cct', 'side' );
		
		$contexts = $this->get_contexts();
		
		if ( is_array( $contexts ) ):
			foreach ( $contexts as $context ):
				$fields = Profile_CCT_Admin::get_option( 'form', 'fields', $context );
				
				if ( $fields ):
					foreach ( $fields as $field ):
						$data = ( isset( $user_data[$field['type']] ) ? $user_data[$field['type']] : null );
						
                        $callback = 'profile_cct_'.$field['type'].'_shell';
                        
						if ( function_exists( $callback ) ):
                            $id = $field['type']."-".$i.'-'.rand(0, 999);
                            $title = $field['label'];
                            $post_type = 'profile_cct';
                            $priority = 'core';
                            $callback_args = array(
                                'options' => $field,
                                'data' => $data,
                            );
							
							add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
						else:
							do_action( "profile_cct_".$field['type']."_add_meta_box", $field, $context, $data, $i );
						endif;
					endforeach;
				endif;
			endforeach;
		endif;
		
		remove_meta_box( 'authordiv', 'post', 'normal' );
		
		if ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) || current_user_can( 'administrator' ) ):
			add_meta_box( 'authordiv', __('Author'), array( $this, 'post_author_meta_box' ), null, 'side', 'low' );
        endif;
		
		add_meta_box( 'submitdiv', __('Publish'), 'post_submit_meta_box', null, 'side', 'high' );
	}
    
	/**
	 * edit_form_advanced function.
	 *
	 * @access public
	 * @return void
	 */
	function edit_post_advanced() {
		global $post;
		
		if ( $post->post_type == "profile_cct" ):
			$tabs = $this->get_option( 'form', 'tabs' );
            // Here we should be finding if there are even any fields in the tabs
            ?>
			<div id="tabs">
				<ul>
					<?php
                    $count = 1;
                    foreach ( $tabs as $tab ):
                        ?>
                            <li>
								<a href="#tabs-<?php echo $count; ?>" class="tab-link"><?php echo $tab; ?></a>
							</li>
                        <?php
                        $count++;
                    endforeach;
                    ?>
				</ul>
				<?php
                $count = 1;
                foreach ( $tabs  as $tab ) :
                    ?>
					<div id="tabs-<?php echo $count?>">
						<?php do_meta_boxes( 'profile_cct', 'tabbed-'.$count, $post );  ?>
					</div>
					<?php
                    $count++;
                endforeach;
                ?>
			</div>
			<?php
		endif;
	}
	
	function modify_row_actions( $actions, $post ) {
		global $current_user;
		
		if ( $post->post_type == "profile_cct" ) {
			if ( ! $current_user->has_cap('edit_others_profile_cct') && $post->post_author != $current_user->ID ) {
				unset($actions['edit']);
				unset($actions['inline hide-if-no-js']);
			}
			
			if ( defined('WP_DEBUG_LOG') && WP_DEBUG_LOG ) {
				error_log( print_r($post, TRUE));
				error_log( print_r($current_user, TRUE));
				error_log( print_r($actions, TRUE));
			}
		}
		return $actions; 
	}
    
    /**
     * post_author_meta_box function.
     * 
     * @access public
     * @param mixed $post
     * @return void
     */
    function post_author_meta_box($post) {
        global $user_ID;
		
        ?>
        Make sure that you select who this is supposed to be.
		<br />
		<label class="screen-reader-text" for="post_author_override"><?php _e('Author'); ?></label>
        <?php
		
        wp_dropdown_users( array(
            'who'      => null,
            'name'     => 'post_author_override',
            'selected' => empty($post->ID) ? $user_ID : $post->post_author,
            'include_selected' => true
        ) );
	}
	
	/**
	 * string_starts_with function.
	 * 
	 * @access public
	 * @param mixed $haystack
	 * @param mixed $needle
	 * @return void
	 */
	function string_starts_with( $haystack, $needle ) {
    	return ! strncmp( $haystack, $needle, strlen($needle) );
	}
	
	
	/**
	 * add_global_field function.
	 * 
	 *	$field = array(
	 *			'type'        => $field_type,
	 *			'label'       => $field_label,
	 *			'field_clone' => $field_clone,
	 *			'description' => $field_description,
	 *			'blogs'       => array(),
	 *		);
	 *  $field_index should be an integer
	 * @access public
	 * @param mixed $field
	 * @return void
	 */
	function add_global_field( $field, $field_index = NULL, $skip_local ) {
		global $blog_id;
		$global_settings = get_site_option( PROFILE_CCT_SETTING_GLOBAL, array() );
		
		if(  'skip' == $skip_local && $field_index == NULL )
			$field_index = $this->find_global_field_index( $field['type'],$global_settings  );
			
		if(  $field_index === NULL ):
			$field['blogs'][$blog_id] = true;
			$global_settings['clone_fields'][] = $field;
			
		else:
			
			$blogs = $this->convert_blog_list_into_blog_array( $field_index, $global_settings );
			$blogs[$blog_id] = true;
			$global_settings['clone_fields'][$field_index]['blogs'] = $blogs;
			
		endif;
		
		unset($field['blogs']);
		if( 'skip' != $skip_local ):
			$this->settings['clone_fields'][$field['type']] = $field;			
			update_option( PROFILE_CCT_SETTINGS, $this->settings );
			
		endif;
		
		update_site_option( PROFILE_CCT_SETTING_GLOBAL, $global_settings );
		return  $global_settings;
	}
		
	/**
	 * find_global_field_index function.
	 * 
	 * @access public
	 * @param mixed $field_type
	 * @param mixed $global_settings
	 * @return void
	 */
	function find_global_field_index( $field_type, $global_settings ){
		
		foreach($global_settings['clone_fields'] as $field_index => $field):
			if( $field['type'] == $field_type )
				return $field_index;
		endforeach;
		return NULL;
	}
	
	/**
	 * remove_global_field function.
	 * 
	 * @access public
	 * @param mixed $field
	 * @param mixed $field_index
	 * @return void
	 */
	function remove_global_field( $field, $field_index, $skip_local ) {
		
		$global_settings = get_site_option( PROFILE_CCT_SETTING_GLOBAL, array() );
		global $blog_id;
		
		$blogs = $this->convert_blog_list_into_blog_array( $field_index, $global_settings );
		
		if( $blogs[$blog_id] ): // we accually have the blog set up as true
			unset($blogs[$blog_id]);
			unset( $this->settings['clone_fields'][$field['type']]);
			
			if ( empty($blogs) ):
				unset($global_settings['clone_fields'][$field_index]);
				$global_settings['clone_fields'] = array_values( array_filter( $global_settings['clone_fields'] ) ); // Reindex the array.
			else:
				$global_settings['clone_fields'][$field_index]['blogs'] = $blogs;
			endif;
			
			if( 'skip' == $skip_local)
				update_option( PROFILE_CCT_SETTINGS, $this->settings );
			
			update_site_option( PROFILE_CCT_SETTING_GLOBAL, $global_settings );
		endif;
		return  $global_settings;
	}
	
	/**
	 * remove_all_global_fields function.
	 * 
	 * @access public
	 * @return void
	 */
	function remove_all_global_fields(){
		global $blog_id;
		
		$global_settings = get_site_option( PROFILE_CCT_SETTING_GLOBAL, array() );
		
		foreach( $global_settings['clone_fields'] as $field_index => $field):
			$this->remove_global_field( $field, $field_index, 'skip' );
		endforeach; 
		
		$this->settings['clone_fields'] = array();
		update_option( PROFILE_CCT_SETTINGS, $this->settings );
		

	}
	
	/**
	 * convert_blog_list_into_blog_array function.
	 * 
	 * @access public
	 * @param mixed $field_index
	 * @return void
	 */
	function convert_blog_list_into_blog_array( $field_index, $global_settings ) {
		global $blog_id;
		// convert global 
		$blogs = array();
		// convert global 
		if ( is_array( $global_settings['clone_fields'][$field_index]['blogs'] ) ):
			$blogs = $global_settings['clone_fields'][$field_index]['blogs'];
		elseif ( ! is_array( $global_settings['clone_fields'][$field_index]['blogs'] ) ):
			$blogs_ids = explode( ',', $global_settings['clone_fields'][$field_index]['blogs'] );
			
			foreach ( $blogs_ids as $id ):
				$id = trim($id);
				if ( ! empty($id) ):
					$blogs[$id] = true;
				endif;
			endforeach;
		endif;
		return $blogs; 
	}
}

Profile_CCT::init();
