<?php

define( 'PROFILE_CCT_BASEADMIN', plugin_basename(__FILE__) );

/**
 * Profile_CCT_Admin class.
 */
class Profile_CCT_Admin {
	static public $action = 'display';
	static public $option = NULL;
	static public $page   = NULL;
    
    static public $current_form_fields = NULL; // Stores the current state of the form field... the labels and if it is on the banch...

	/**
	 * init function.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_menu_page' ) );
        
		// function removed the edit Public profile from everyone but the person who can really edit it
		add_action( 'wp_before_admin_bar_render', array( __CLASS__, 'edit_admin_bar_render' ), 20 );
	}

	/**
	 * admin_init function.
	 *
	 * @access public
	 * @return void
	 */
	static function admin_init() {
		// Register Settings
		register_setting( PROFILE_CCT_SETTINGS,         'Profile_CCT_settings' );
		register_setting( PROFILE_CCT_SETTING_TAXONOMY, 'Profile_CCT_taxonomy' );
        
		// These are Options
		register_setting( 'Profile_CCT_form_fields', 'Profile_CCT_form_fields', array( __CLASS__, 'validate_form_fields' ) );
		register_setting( 'Profile_CCT_page_fields', 'Profile_CCT_page_fields', array( __CLASS__, 'validate_page_fields' ) );
		register_setting( 'Profile_CCT_list_page',   'Profile_CCT_list_page',   array( __CLASS__, 'validate_list_fields' ) );
		
		// redirect users to their profile page and create one if it doesn't exist
		self::redirect_to_public_profile();
		
		add_action( 'wp_ajax_cct_update_fields',   array( __CLASS__, 'update_fields' ) );
		add_action( 'wp_ajax_cct_update_tabs',     array( __CLASS__, 'update_tabs' ) );
		add_action( 'wp_ajax_cct_reset_fields',    array( __CLASS__, 'reset_fields' ) );
		add_action( 'wp_ajax_cct_update_profiles', array( __CLASS__, 'refresh_profiles' ) );
		add_action( 'wp_ajax_cct_needs_refresh',   array( __CLASS__, 'set_profiles_need_refresh' ) );
        
        // double check that the fields don't display twice
        
        add_action( 'profile_cct_before_page',     array( __CLASS__, 'update_clone_fields' ), 10, 1 );
		add_action( 'profile_cct_before_page',     array( __CLASS__, 'recount_field' ), 10, 1 );
		add_action( 'profile_cct_before_page',     array( __CLASS__, 'display_fields_check' ), 11, 1 );
		
		// save the date 
		// add_action( 'wp_insert_post_data',         array( __CLASS__, 'save_post_data'), 10, 2 );
		// save the date
		add_action( 'wp_insert_post',         array( __CLASS__, 'insert_post'), 10, 2 );
	}

	/**
	 * redirect_to_public_profile function.
	 *
	 * @access public
	 * @return void
	 */
	static function redirect_to_public_profile() {
		global $plugin_page, $pagenow, $current_user;
        
		if ( $plugin_page == 'public_profile' && in_array($pagenow, array('profile.php', 'users.php')) ):
			$id = Profile_CCT::get_user_profile()->ID;
            
			// Reset Post Data
			wp_reset_postdata();
            
			if ( ! $id ):
				// lets create public profile on the fly...
				$post_arg = array(
    				'post_author'  => $current_user->ID,           //The user ID number of the author.
    				'post_content' => 'Empty Profile',             //The full text of the post.
  					'post_excerpt' => 'Empty Profile',             //For all your post excerpt needs.
  					'post_status'  => 'draft',                     //Set the status of the new post.
  					'post_title'   => $current_user->display_name, //The title of your post.
  					'post_type'    => 'profile_cct',               //You may want to insert a regular post, page, link, a menu item or
				);
                
				$id = wp_insert_post( $post_arg );
			endif;
            
			wp_redirect( admin_url('post.php?post='.$id.'&action=edit') );
			exit;
		endif;
	}

	/**
	 * edit_admin_bar_render function.
	 * Change the Admin Bar for the better
	 * @access public
	 * @return void
	 */
	static function edit_admin_bar_render() {
    	global $wp_admin_bar, $post, $current_user;
        
    	if ( 'profile_cct' == get_post_type() ):
    		if ( ! ( current_user_can( 'edit_profile_cct' ) && (int) $post->post_author != $current_user->ID ) && ! current_user_can( 'edit_others_profile_cct' ) ):
    			$wp_admin_bar->remove_node('edit');
    		endif;
    	endif;
        
    	if ( current_user_can( 'edit_profile_cct' ) ):
	    	$wp_admin_bar->remove_node('logout');
            
	    	$wp_admin_bar->add_node( array(
				'parent' => 'user-actions',
				'id'     => 'edit-public-profile',
				'title'  => __( 'Edit Public Profile' ),
				'href'   => admin_url('users.php?page=public_profile'),
			));
            
			// Remove and re-add the logout button, in order to ensure, that Log Out appears at the bottom of the list.
			$wp_admin_bar->add_node( array(
				'parent' => 'user-actions',
				'id'     => 'logout',
				'title'  => __( 'Log Out' ),
				'href'   => wp_logout_url(),
			));
		endif;
    }
	
	/**
	 * update_clone_fields function.
	 * This funtion was needed when you are updating from version 1.1 or 1.2 to 1.3
	 * @access public
	 * @return void
	 */
	function update_clone_fields(){
		global $blog_id;
		$profile = Profile_CCT::get_object();
		
		if ( isset( $profile->settings['updated_clone_fields'] ) ):
			return;
		endif;
		
		$global_settings = get_site_option( PROFILE_CCT_SETTING_GLOBAL, array() );
		
		if ( is_array( $global_settings['clone_fields'] ) && ! empty( $global_settings['clone_fields'] ) ):
			foreach ( $global_settings['clone_fields'] as $field ):
				$enabled = ( isset( $field['blogs'][$blog_id] ) && $field['blogs'][$blog_id] == true );
				
				if( $enabled ):
					unset($field["blogs"]);
					$current_fields[$field['type']] = $field;
					
				endif;	
				
			endforeach;
			
			if ( is_array( $current_fields ) ):
				foreach ( $current_fields as $current_field_key => $current_field_data ):
					if ( isset( $profile->settings['clone_fields'][$current_field_key] ) && is_array( $profile->settings['clone_fields'][$current_field_key] )):
						$merge_current_fields[$current_field_key] = shortcode_atts($profile->settings['clone_fields'][$current_field_key], $current_field_data );
					else:
						$merge_current_fields[$current_field_key] = $current_field_data;
					endif;
				endforeach;
			endif;
			
			$profile->settings['clone_fields'] = $merge_current_fields;
			$profile->settings['updated_clone_fields'] = PROFILE_CCT_VERSION;
			
			self::ask_user_to_update_profiles();
			update_option( PROFILE_CCT_SETTINGS, $profile->settings );
		endif;
	}
	
	/**
	 * recount_field function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $where
	 * @return void
	 */
	public static function recount_field( $where ) {
		if ( ! in_array( $where, array( 'form', 'page', 'list' ) ) ):
			return true;
		endif;
       
		// lets see what all the fields are that are suppoed to be there.
		$contexts = self::get_contexts();
        
		// CURRENT FIELDS
		// All the fields that are there.
		$current_fields = array();
		foreach ( $contexts as $context ):
			foreach ( (array) self::get_option( $where, 'fields', $context ) as $field ):
				$current_fields[] = $field['type'];
			endforeach;
		endforeach;
		
        // check to see if this field is alr
		// don't forget the bench fields.
		foreach ( self::get_option( $where, 'fields', 'bench' ) as $field ):
			// lets make sure that for no reason duplicate fields end up in the bench 
			if ( ! in_array( $field['type'], $current_fields ) ):
				$current_fields[] = $field['type'];
				$bench_fields[] = $field;
			endif;
			
		endforeach;
		
		// correct the bench fields 
		self::$option[$where]['fields']['bench'] = $bench_fields;
        
		// DYNAMIC FIELDS
		// all the fields that get included
		// - taxonomy fields
		// - db fields (added via the add field function)
		$dynamic_fields = apply_filters( "profile_cct_dynamic_fields", array(), $where );
		
		$all_dynamic_fields = array();
        $real_fields = array(); // array of all the default fields containing the field array with the key field['type']
        
		if ( is_array( $dynamic_fields ) ):
			foreach ( $dynamic_fields as $field ):
				$all_dynamic_fields[] 		 = $field['type'];
				$real_fields[$field['type']] = $field;
                
				if ( ! in_array( $field['type'], $current_fields ) ): // add to the current_fields array
					$current_fields[] = $field['type'];
					if ( $where == 'form' && Profile_CCT::string_starts_with( $field['type'], PROFILE_CCT_TAXONOMY_PREFIX ) ):
						self::$option[$where]['fields']['side'][] = $field;
					else:
						self::$option[$where]['fields']['bench'][] = $field;
					endif;
				endif;
			endforeach;
		endif;
        
		// DEFAULT FIELDS NOW
		unset($context);
        
		// all the other fields
		$default_fields = array();
        
		// get the default
		$default_options = self::default_options( $where );
        
		foreach ( $default_options['fields'] as $context => $fields ):
			foreach ( $fields as $field ):
				$default_fields[] = $field['type'];
				$real_fields[$field['type']] = $field;
			endforeach;
			unset($field);
		endforeach;
        
		// also don't forget fields that are fields that were added later
		$new_fields = self::default_options( 'new_fields' );
		foreach ( $new_fields as $version ):
			foreach ( $version as $field ):
				if ( in_array($where, $field['where']) ): // only add it if it supports the the current where state
					$default_fields[] = $field['field']['type'];
					$real_fields[$field['field']['type']] = $field['field'];
				endif;
			endforeach;
			unset($field);
		endforeach;
        
		unset($version);
        
		// merging the default array with the dynamic one
		$default_fields = array_merge( $default_fields, $all_dynamic_fields );
        
		$different = array_diff($default_fields, $current_fields);
        
		unset($field);
		if ( ! empty($different) ):
			// add the fields back to the banch the array...
			foreach ( $different as $field ):
				self::$option[$where]['fields']['bench'][] = $real_fields[$field];
			endforeach;
		endif;
        
		return true;
	}
	
	/**
	 * display_fields_check function.
	 * 
	 * Helps us determine which of these fields is already in the form. 
	 * So we can indicate to the user what tthe fileds
	 *
	 * @access public
	 * @static
	 * @param mixed $where
	 * @return void
	 */
	public static function display_fields_check( $where ) {
        if ( ! in_array( $where, array( 'page', 'list' ) ) ):
			return true;
		endif;
		
		$contexts = self::get_contexts( 'form' );
		
		// CURRENT FIELDS
		// All the fields that are there.
		$current_fields = array();
		foreach( $contexts as $context ):
			$fields = self::get_option( 'form', 'fields', $context );
			
			if ( is_array( $fields ) ):
				foreach ( $fields as $field ):
					$field['is_active'] = 1;
					self::$current_form_fields[$field['type']] = $field;
				endforeach;
			endif;
		endforeach;
		
		// Don't forget the bench field.
		foreach ( self::get_option( 'form', 'fields', 'bench' ) as $field ):
			$field['is_active'] = 0;
			self::$current_form_fields[$field['type']] = $field;
		endforeach;
		
		return true;
	}

	/**
	 * add_menu_page function.
	 *
	 * @access public
	 * @return void
	 */
	public static function add_menu_page() {
		// Public profile page
		$public_profile = add_submenu_page(
			'users.php',
			__( 'Public Profile', 'profile-cct-td' ),
			__( 'Public Profile', 'profile-cct-td' ),
			'edit_profile_cct', 'public_profile',
			array( __CLASS__, 'public_profile' )
        );
        
		// Order Page
		$order_page = add_submenu_page(
			'edit.php?post_type=profile_cct',
			__( 'Order Profiles', 'profile-cct-td' ),
			__( 'Order Profiles', 'profile-cct-td' ),
			'manage_options', 'order_profiles',
			array( __CLASS__, 'admin_order_page' )
        );
        
		// Settings page
		$page = add_submenu_page(
			'edit.php?post_type=profile_cct',
			__( 'Settings', 'profile-cct-td' ),
			__( 'Settings', 'profile-cct-td' ),
			'manage_options', __FILE__,
			array( __CLASS__, 'admin_pages' )
        );
        
		add_action( 'admin_print_styles-' . $order_page,  array( __CLASS__, 'order_profiles_admin_styles'  ) );
		add_action( 'admin_print_scripts-' . $order_page, array( __CLASS__, 'order_profiles_admin_scripts' ) );
        
		add_action( 'admin_print_styles-' . $page,        array( __CLASS__, 'admin_styles'  ) );
		add_action( 'admin_print_scripts-' . $page,       array( __CLASS__, 'admin_scripts' ) );
        
		add_action( 'admin_print_styles-post-new.php', 	  array( __CLASS__, 'edit_profile_script' ) );
		add_action( 'admin_print_styles-post.php',        array( __CLASS__, 'edit_profile_script' ) );
        
		add_action( 'admin_print_styles-post.php',        array( __CLASS__, 'edit_profile_style' ) );
		add_action( 'admin_print_styles-post.php',        array( __CLASS__, 'edit_profile_style' ) );
		add_action( 'admin_print_styles-edit.php',        array( __CLASS__, 'edit_profile_style' ) );
		
		add_action( 'admin_print_styles-index.php',       array( __CLASS__, 'widget_style' ) );
	}
	
	############################################################################################################
	/* DASHBOARD PAGE  */

	public static function widget_style() {
		wp_enqueue_style( 'profile-cct', PROFILE_CCT_DIR_URL.'/css/profile-cct.css' );
	}
	
	############################################################################################################
	/* PUBLIC PROFILE PAGE  */

	/**
	 * public_profile function.
	 *
	 * @access public
	 * @return void
	 */
	public static function edit_profile_script() {
		global $current_screen;
        
		if ( 'profile_cct' == $current_screen->id ):
			wp_enqueue_style("thickbox");
			wp_enqueue_script("thickbox");
            
			wp_enqueue_style( 'profile-cct-edit-post', PROFILE_CCT_DIR_URL.'/css/profile-page.css' );
			wp_enqueue_script( 'profile-cct-edit-post-validation', PROFILE_CCT_DIR_URL.'/js/profile-page-validation.js' );
			wp_enqueue_script( 'profile-cct-edit-post', PROFILE_CCT_DIR_URL.'/js/profile-page.js', array( 'jquery-ui-tabs' ) );
			wp_localize_script( 'profile-cct-edit-post', 'profileCCTSocialArray', Profile_CCT_Social::social_options() );
		endif;
	}

	public static function edit_profile_style() {
		global $current_screen;
		
		if ( in_array( $current_screen->id, array( 'profile_cct', 'edit-profile_cct' ) ) ):
			wp_enqueue_style( 'profile-cct-edit-post', PROFILE_CCT_DIR_URL.'/css/profile-page.css' );
		endif;
	}

	############################################################################################################
	/* SETTINGS PAGE */
	/**
	 * admin_pages function.
	 *
	 * @access public
	 * @return void
	 */
	public static function admin_pages() {
		self::$action = 'edit';
		self::$page = ( in_array( $_GET['view'], array( 'form', 'page', 'list', 'taxonomy', 'fields', 'settings' ) ) ? $_GET['view'] : 'about' );
        
		// the header file determins what other files should be loaded here
		require( PROFILE_CCT_DIR_PATH.'views/header.php' );
	}

	/**
	 * admin_styles function.
	 *
	 * @access public
	 * @return void
	 */
	public static function admin_styles() {
		// todo: this could be done with one css file
		wp_enqueue_style( 'profile-cct-admin', PROFILE_CCT_DIR_URL.'/css/admin.css' );
		switch( $_GET['view'] ):
		case "form":
		case "page":
		case "list":
			wp_enqueue_style( 'profile-cct-form', PROFILE_CCT_DIR_URL.'/css/form.css' );
			break;
		default:
			// wp_enqueue_style( 'profile-cct-settings', PROFILE_CCT_DIR_URL.'/css/settings.css' );
            break;
		endswitch;
	}
    
	/**
	 * admin_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	public static function admin_scripts() {
		switch( $_GET['view'] ):
		case "form":
			wp_enqueue_script( 'profile-cct-form', PROFILE_CCT_DIR_URL.'/js/form.js',array('jquery', 'jquery-ui-sortable') );
			wp_enqueue_script( 'profile-cct-tabs', PROFILE_CCT_DIR_URL.'/js/tabs.js',array('jquery', 'jquery-ui-tabs') );
			wp_localize_script( 'profile-cct-form', 'ProfileCCT', array( 'page' => 'form' ) );
			break;
		case "page":
			wp_enqueue_script( 'profile-cct-tabs', PROFILE_CCT_DIR_URL.'/js/tabs.js', array('jquery', 'jquery-ui-tabs') );
			wp_enqueue_script( 'profile-cct-form', PROFILE_CCT_DIR_URL.'/js/form.js', array('jquery', 'jquery-ui-sortable') );
			wp_enqueue_script( 'profile-cct-profile', PROFILE_CCT_DIR_URL.'/js/profile.js', array('jquery') );
			wp_localize_script( 'profile-cct-form', 'ProfileCCT', array( 'page' => 'page' ) );
			break;
		case "list":
			wp_enqueue_script( 'profile-cct-form', PROFILE_CCT_DIR_URL.'/js/form.js', array('jquery', 'jquery-ui-sortable') );
			wp_enqueue_script( 'profile-cct-profile', PROFILE_CCT_DIR_URL.'/js/profile.js', array('jquery') );
			wp_localize_script( 'profile-cct-form', 'ProfileCCT', array( 'page' => 'list' ) );
			break;
		endswitch;
        
		wp_enqueue_script( 'profile-cct-settings', PROFILE_CCT_DIR_URL.'/js/admin.js' );
	}

	/**
	 * permissions_table function.
	 * this function is used on the settings page
	 * @access public
	 * @param mixed $user
	 * @param bool $alternate (default: false)
	 * @return void
	 */
	static function permissions_table( $user, $alternate = false, $settings ) {
		if( is_array( $settings['permissions'][$user] ) ):
			$disabled = ( $user == 'administrator' ? 'disabled' : '' );
			?>
			<tr <?php echo ( $alternate ? 'class="alternate"' : '' ) ?>>
				<td><?php echo ucwords( $user ); ?></td>
				<?php foreach ( $settings['permissions'][$user] as $action => $can ): ?>
					<td>
						<input type="checkbox" name="options[permissions][<?php echo esc_attr( $user ); ?>][<?php echo esc_attr( $action ); ?>]" <?php echo $disabled; ?> value="1" <?php checked( $can ); ?> />
					</td>
				<?php endforeach; ?>
			</tr>
			<?php
		endif;
	}

	############################################################################################################
	/* MANUALLY ORDER PROFILES PAGE */
	/**
	 * admin_order_page function.
	 * Page lets you reorder people
	 * @access public
	 * @return void
	 */
	public static function admin_order_page() {
		require( PROFILE_CCT_DIR_PATH.'views/order-profiles.php' );
	}
    
	/**
	 * order_profiles_admin_styles function.
	 * styles for the order people page
	 * @access public
	 * @return void
	 */
	static function order_profiles_admin_styles() {
		wp_enqueue_style( 'profile-cct-order', PROFILE_CCT_DIR_URL.'/css/order-profiles.css' );
	}
    
	/**
	 * order_profiles_admin_scripts function.
	 * scripts for the order people page
	 * @access public
	 * @return void
	 */
	static function order_profiles_admin_scripts() {
		wp_enqueue_script( 'profile-cct-order', PROFILE_CCT_DIR_URL.'/js/order-profiles.js', array( 'jquery', 'jquery-ui-sortable' ) );
	}

	############################################################################################################
	/* Options Functions  */
	/**
	 * get_option function.
	 * 
	 * @access public
	 * @param string $type (default: 'form')
	 * @param string $fields_or_tabs (default: 'fields')
	 * @param string $context (default: 'normal')
	 * @return void
	 */
	static function get_option( $type = 'form', $fields_or_tabs = 'fields', $context = 'normal' ){
		$profile = Profile_CCT::get_object();
		// return the options from the array stored
		if ( is_array( self::$option[$type][$fields_or_tabs][$context] ) ):
			return self::$option[$type][$fields_or_tabs][$context];
		else:
			// get the option
			$options = get_option( 'Profile_CCT_'.$type.'_'.$fields_or_tabs.'_'.$context );
            
			// if we can't find one in the database
			if ( ! is_array( $options ) ):
				$default = self::default_options( $type );
                
				if ( $fields_or_tabs == 'fields' ):
					$options = $default[$fields_or_tabs][$context];
				else:
					$options = $default[$fields_or_tabs];
                endif;
			endif;
            
			// lets check if we have the fresh version since we last updated the plugin
			/* CHECK to see if we need to do the merge */
			$perform_merge = false;
            
			if ( ! isset( $profile->settings['version'][$type][$fields_or_tabs][$context] ) ): // can we find the version settings
				$perform_merge = true;
			elseif ( PROFILE_CCT_VERSION > $profile->settings['version'][$type][$fields_or_tabs][$context] ): // are they less then the current version
				$perform_merge = true;
			endif;
            
			// lets perform the merge
			if ( $perform_merge && $context == 'bench' ):
				$new_fields = self::default_options( 'new_fields' );
                
				// lets add the new fields in this version to the banch
				if ( is_array( $new_fields[PROFILE_CCT_VERSION] ) ):
					foreach ( $new_fields[PROFILE_CCT_VERSION] as $field ) :
						if ( in_array( $type , $field['where'] ) ):
							$options[] = $field['field'];
						endif;
					endforeach;
				endif;
			endif;
		endif;
        // todo: make sure that we don't duplicate fields… :(
        
		self::$option[$type][$fields_or_tabs][$context] = $options;
		
		return $options;
	}
    
	/**
	 * update_option function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @param string $fields_or_tabs. (default: 'fields')
	 * @param string $context. (default: 'normal')
	 * @param mixed $update
	 * @return void
	 */
	static function update_option( $type = 'form', $fields_or_tabs = 'fields', $context = 'normal', $update ) {
		$profile = Profile_CCT::get_object();
		$profile->settings['version'][$type][$fields_or_tabs][$context] = PROFILE_CCT_VERSION;
		$profile->settings[$type.'_updated'] = time();
		// Saving of the version number
		self::$option[$type][$fields_or_tabs][$context] = $update;
		
		// Update the settings
		update_option( PROFILE_CCT_SETTINGS, $profile->settings );
        
		return update_option( 'Profile_CCT_'.$type.'_'.$fields_or_tabs.'_'.$context, $update );
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
	static function delete_option( $type = 'form', $fields_or_tabs = 'fields', $context = 'normal' ) {
		unset( self::$option[$type][$fields_or_tabs][$context] );
		return delete_option( 'Profile_CCT_'.$type.'_'.$fields_or_tabs.'_'.$context );
	}

	/**
	 * default_options function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @return void
	 */
	static function default_options( $type = 'form' ) {
		// load the $option array - the default option
		require( PROFILE_CCT_DIR_PATH.'default-options.php' );
		$setting = $option[$type];
		return apply_filters( 'profile_cct_default_options', $setting, $type );
	}
	
	/**
	 * icon function.
	 *
	 * @access public
	 * @return void
	 */
	function icon() {
		printf( '<img src="%s/icon-64.png" class="icon32" width="32" height="32" />', PROFILE_CCT_DIR_URL );
	}
	
	/**
	 * Sets whether the plugin's profiles need to be updated or not.
	 *
	 * @access public
	 * @return void
	 */
	static function set_profiles_need_refresh() {
		if ( isset($_POST['needs_refresh']) ):
			$key = 'Profile_CCT_needs_refresh';
			$expiration = 3*86400; //Expires in 3 days.
			
			if ( $_POST['needs_refresh'] ):
				$value = get_transient( $key );
				$value[$_POST['where']] = 1;
				set_transient( $key, $value, $expiration );
			else:
				delete_transient( $key );
			endif;
		endif;
	}
	
	static function ask_user_to_update_profiles() {
		$key = 'Profile_CCT_needs_refresh';
		$expiration = 3*86400; //Expires in 3 days.
		$value = 'update-all';
		set_transient( $key, $value, $expiration );
	
	}

	
	/**
	 * default_shells function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $page (default: null)
	 * @return void
	 */
	static function default_shells( $page = null ) {
		$page = ( is_null( $page ) ? self::$page : $page );
		
		switch ( $page ):
			case 'form':
				return array( 'normal', 'side', 'tabs' );
                break;
			case 'page':
				return array( 'header', 'tabs', 'bottom' );
                break;
			case 'list':
				return array( 'normal' );
    			break;
		endswitch;
	}

	
	/**
	 * get_contexts function.
	 * This functio
	 * @access public
	 * @static
	 * @param mixed $page (default: null)
	 * @return void
	 */
	static function get_contexts( $page = null ) {
		$page = ( is_null( $page ) ? self::$page : $page );
		
		$contexts = self::default_shells( $page );
		
		$index = array_search( 'tabs', $contexts );
		
		if ( is_numeric( $index ) ):
			$tabs = self::get_option( $page, 'tabs' );
            
			$tab_contexts = array();
			if ( is_array( $tabs ) ):
				$count = 1;
				foreach ( $tabs as $tab ):
					$tab_contexts[] = "tabbed-".$count;
					$count++;
				endforeach;
				
				array_splice( $contexts, $index + 1, 0, $tab_contexts );
			else:
				unset( $contexts[$index] );
			endif;
            
			$contexts = array_values( $contexts );
		endif;
		
		return $contexts;
	}

	/**
	 * generate_profile function.
	 * 
	 * @access public
	 * @param mixed $section
	 * @return void
	 */
	static function generate_profile( $section, $data = null ) {
		switch ( $section ):
		case 'bench':
			self::render_context( $section, false );
			break;
		case 'preview':
			?>
			<div id="<?php echo self::$page; ?>-shell" class="preview-shell">
				<?php
					foreach ( self::default_shells() as $context ):
						self::render_context( $context );
					endforeach;
				?>
			</div>
			<?php
			break;
		case 'page':
			self::$page = 'page';
			?>
			<div style="overflow: hidden">
				<?php
					foreach ( self::default_shells() as $context ):
						self::render_context( $context, $data );
					endforeach;
				?>
			</div>
			<?php
			break;
		case 'list':
			self::$page = 'list';
			?>
			<div style="overflow: hidden">
				<?php
				foreach ( self::default_shells() as $context ):
					self::render_context( $context, $data );
				endforeach;
				?>
			</div>
			<?php
			break;
		endswitch;
	}
	
	/**
	 * render_context function.
	 * 
	 * @access public
	 * @param mixed $context
	 * @param bool $display_context (default: true)
	 * @return void
	 */
	static function render_context( $context, $data = null ) {
		$class = ( 'bench' != $context ? 'form-builder' : '' );
        
		if ( function_exists('profile_cct_'.$context.'_shell') ):
			call_user_func( 'profile_cct_'.$context.'_shell', $data );
		else:
			$fields = self::get_option( self::$page, 'fields', $context );
			
			if ( 'display' == self::$action ):
				if ( empty( $fields ) ) return; // Don't dispay the shell if it is empty. There's no need.
				$shell = '';
				$end_shell = '';
				$shell_class = 'class="profile-cct-shell"';
			else:
				$shell = '<ul class="sort '.$class.'" id="'.$context.'">';
				$end_shell = '</ul>';
				$shell_class = 'class="context-shell"';
			endif;
			
			?>
			<div id="<?php echo $context; ?>-shell" <?php echo $shell_class; ?>>
				<?php if ( self::$page == 'form' ): ?>
				<!--<span class="description-shell"><?php echo $context; ?></span>-->
				<?php endif; ?>
				
				<?php
				echo $shell;
				if ( is_array( $fields ) ):
					foreach ( $fields as $field ):
						if ( function_exists( 'profile_cct_'.$field['type'].'_shell' ) ):
							call_user_func( 'profile_cct_'.$field['type'].'_shell', $field, $data[ $field['type'] ] );
						else:
							do_action( 'profile_cct_shell_'.$field['type'], $field, $data[ $field['type'] ] );
						endif;
					endforeach;
				endif;
				echo $end_shell;
				?>
			</div>
			<?php
		endif;
	}
	
	/**
	 * fields_to_clone function.
	 * what fields are allowed to be cloned
	 * @access public
	 * @return void
	 */
	static function fields_to_clone() {
		return apply_filters( 'profile_cct_fields_to_clone', 
			array(
				array( "type" => "phone"     ),
				array( "type" => "email"     ),
				array( "type" => "address"   ),
				array( "type" => "website"   ),
				array( "type" => "position"  ),
				array( "type" => "education" ),
				array( "type" => "textarea"  ),
				array( "type" => "text"      ),
				array( "type" => "projects"  ),
				array( "type" => "courses"   ),
				array( "type" => "data"      ),
			) 
		);
	}
	
	/**
	 * save_post_data function.
	 * 
	 * @access public
	 * @param mixed $data
	 * @param mixed $postarr
	 * @return void
	 */
	function save_post_data( $post_data, $postarr ) {
		global $post, $wp_filter;
		
		if ( isset( $_POST["profile_cct"] )):
			kses_remove_filters();
			
			$profile_cct_data = self::overwrite_previous_post_data($postarr['ID'], $_POST["profile_cct"]);
			
			// save the name of the person as the title
			if ( is_array( $profile_cct_data["name"]) || !empty($profile_cct_data["name"]) ):
				$post_data['post_title'] = $profile_cct_data["name"]['first']." ".$profile_cct_data["name"]['last'];
				$post_data['post_name'] = sanitize_title($profile_cct_data["name"]['first']." ".$profile_cct_data["name"]['last']);
			else:
				$userdata = get_userdata( $post_data['post_author'] );
				$post_data['post_title'] = $userdata->user_nicename;
				$post_data['post_name'] = sanitize_title( $userdata->user_nicename );
			endif;
			
			//Ensure there is no slug conflict
			$post_data['post_name'] = wp_unique_post_slug( $post_data['post_name'], $postarr['ID'], 'publish', 'profile_cct', 0 );
			$post_data['post_content'] = self::generate_content( $profile_cct_data, 'page' );
			$post_data['post_excerpt'] = self::generate_content( $profile_cct_data, 'list' );
			
			self::store_post_data( $postarr['ID'], $profile_cct_data );
			
			kses_init_filters();
		endif;
		
		return $post_data;
	}
	
	/**
	 * insert_post function.
	 * update the post once it is created again
	 * @access public
	 * @param mixed $post_id
	 * @param mixed $post_data
	 * @return void
	 */
	function insert_post( $post_id, $post_data ) {
	
		global $wpdb;
		
		
		if ( isset( $_POST["profile_cct"] )):
			kses_remove_filters();
			
			$profile_cct_data = self::overwrite_previous_post_data( $post_id, $_POST["profile_cct"] );
			
			// save the name of the person as the title
			if ( is_array( $profile_cct_data["name"]) || !empty( $profile_cct_data["name"] ) ):
				$data['post_title'] = $profile_cct_data["name"]['first']." ".$profile_cct_data["name"]['last'];
				$data['post_name'] = sanitize_title( $profile_cct_data["name"]['first']." ".$profile_cct_data["name"]['last'] );
			else:
				$userdata = get_userdata( $post_data->post_author );
				$data['post_title'] = $userdata->user_nicename;
				$data['post_name'] = sanitize_title( $userdata->user_nicename );
			endif;
			
			//Ensure there is no slug conflict
			$data['post_name'] = wp_unique_post_slug( $data['post_name'], $post_id, 'publish', 'profile_cct', 0 );
			$data['post_content'] = self::generate_content( $profile_cct_data, 'page' );
			$data['post_excerpt'] = self::generate_content( $profile_cct_data, 'list' );
			
			
			
			self::store_post_data( $post_id, $profile_cct_data );
			
			$data = sanitize_post( $data, 'db');
			$data = stripslashes_deep( $data );
			
			$where = array( 'ID' =>  $post_id );
						
			$wpdb->update( $wpdb->posts, array( 
								'post_name' => $data['post_name'],
								'post_content' => $data ['post_content'],
								'post_excerpt' => $data ['post_excerpt'],
								'post_title'   => $data['post_title']
								 ), $where );
			
			kses_init_filters();
		endif;		
	
	
	}
	
	/**
	 * generate_content function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $profile_cct_data
	 * @param mixed $where
	 * @return void
	 */
	static function generate_content( $profile_cct_data, $where ) {
		ob_start();
		self::generate_profile( $where, $profile_cct_data );
		$contents = ob_get_contents();
		ob_end_clean();
		
		return $contents;
	}
	
	/**
	 * overwrite_previous_post_data function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $post_id
	 * @param mixed $profile_cct
	 * @return void
	 */
	static function overwrite_previous_post_data( $post_id, $profile_cct ) {
		$profile_cct_data_previous = get_post_meta( $post_id, 'profile_cct', true );
		
		if ( ! is_array($profile_cct_data_previous)):
			$profile_cct_data_previous = array();
		endif;
		
		return ( is_array($profile_cct) ? array_merge( $profile_cct_data_previous, $profile_cct ) : $profile_cct_data_previous );
	}
	
	/**
	 * Saves data associated with a single post to the database.
	 *
	 * @param @profile_cct_data the data to save
	 * @param $post_id the identifier of the post to save data for.
	 * @return void
	 */
	static function store_post_data( $post_id, $profile_cct_data ) {
		update_post_meta( $post_id, 'profile_cct', $profile_cct_data );
		update_post_meta( $post_id, 'profile_cct_last_name', $profile_cct_data["name"]['last'] );
		
		$first_letter = strtolower(substr($profile_cct_data["name"]['last'], 0, 1));
		wp_set_post_terms( $post_id, $first_letter, 'profile_cct_letter', false );
	}
	
	static function refresh_profiles() {
		$page = ( isset( $_POST['page'] ) ? (int) $_POST['page'] : 0 );
		
		$args = array(
			'post_type'		=> 'profile_cct',
			'post_status'	=> 'any',
			'posts_per_page' => 20,
			'orderby'		=> 'title',
			'paged'			=> $page,
		);
		
		$the_query = new WP_Query( $args );
		$previous_version = get_option( PROFILE_CCT_SETTING_VERSION, PROFILE_CCT_VERSION );
		$version_bump = version_compare( PROFILE_CCT_VERSION, $previous_version, '>' );
		
		while ( $the_query->have_posts() ):
			$the_query->the_post();
			global $post;
			self::update_profile( $post, $version_bump );
		endwhile;
		
		wp_reset_postdata();
		
		if ( $page == $the_query->max_num_pages ):
			if ( $version_bump ):
				update_option( PROFILE_CCT_SETTING_VERSION, PROFILE_CCT_VERSION );
			endif;
		endif;
		
		echo json_encode( array( 'max' => $the_query->max_num_pages, 'page' => $page ) );
		die();
	}

	static function update_profile( $post, $version_bump = false ) {
		if ( is_object($post) ):
			$post_id = $post->ID;
		else:
			$post_id = $post;
		endif;
		
		$data = get_post_meta( $post_id, 'profile_cct', true );
		
		$mypost = array(
			'ID'           => $post_id,
			'post_content' => self::generate_content( $data, 'page' ),
			'post_excerpt' => self::generate_content( $data, 'list' ),
		);
		
		kses_remove_filters();
		wp_update_post( $mypost );
		kses_init_filters();
		
		$last_name = ( isset( $data["name"]['last'] ) ? $data["name"]['last'] : '0' );
		update_post_meta( $post_id, 'profile_cct_last_name', $last_name );
		
		$first_letter = strtolower( substr( $last_name, 0, 1 ) );
		$first_letter = ( empty( $first_letter ) ? '0' : $first_letter );
		wp_set_post_terms( $post_id, $first_letter, 'profile_cct_letter', false );
	}
	
	/**
	 * add_field function.
	 * function return by ajax to be displayed
	 * @access public
	 * @return void
	 */
	static function update_fields() {
		$profile = Profile_CCT::get_object();
		$context = $_POST['context'];
		
		if ( in_array( $_POST['where'], array( 'form', 'page', 'list' ) ) ):
			$where = $_POST['where'];
		else:
			$where = 'form';
		endif;
		
		if ( in_array( $_POST['width'], array( 'full', 'half', 'one-third', 'two-third' ) ) ):
			$width = $_POST['width'];
		else:
			$width = 'full';
		endif;
		
		$options = self::get_option( $where, 'fields', $context );
		
		switch ( $_POST['method'] ):
		case "update":
			if ( is_numeric( $_POST['field_index'] ) ):
				switch ($where):
				case "form":
					$url_prefix = trim( $_POST['url_prefix'] );
					
					$options[$_POST['field_index']]['label']       = $_POST['label'];
					$options[$_POST['field_index']]['description'] = $_POST['description'];
					$options[$_POST['field_index']]['show']        = $_POST['show'];
					$options[$_POST['field_index']]['multiple']    = isset( $_POST['multiple'] ) && $_POST['multiple'] ? $_POST['multiple'] : 0;
					$options[$_POST['field_index']]['url_prefix']  = $url_prefix;
					
					// Save the url prefix also in the settings array.
					if ( ! is_array( $profile->settings['data_url'] ) ):
						$profile->settings['data_url'] = array();
					endif;
					
					$profile->settings['data_url'][$_POST['type']] = $url_prefix;
					update_option( PROFILE_CCT_SETTINGS, $profile->settings );
					break;
				case "page":
				case "list":
					$options[$_POST['field_index']]['width']     = $width;
					$options[$_POST['field_index']]['before']    = $_POST['before'];
					$options[$_POST['field_index']]['after']     = $_POST['after'];
					$options[$_POST['field_index']]['show']      = $_POST['show'];
					$options[$_POST['field_index']]['link_to']   = $_POST['link_to'];
					$options[$_POST['field_index']]['clear']     = $_POST['clear'];
					$options[$_POST['field_index']]['text']      = $_POST['text'];
					$options[$_POST['field_index']]['empty']     = $_POST['empty'];
					$options[$_POST['field_index']]['seperator'] = $_POST['seperator'];
					break;
				endswitch;
				$print = "updated";
			endif;
			break;
		case "sort":
			if ( ! empty($_POST['data']) ):
				unset($options);
				foreach($_POST['data'] as $data):
					$options[] = wp_parse_args($data);
				endforeach;
			else:
				$options = array();
			endif;
			$print =  "sorted";
			break;
		endswitch;
		
		// Save the options
		self::update_option( $where, 'fields', $context, $options );
		echo $print;
		die();
	}
	
	/**
	 * An Ajax functon used to save tabs.
	 *
	 * @access public
	 * @return void
	 */
	function update_tabs() {
		$where = in_array( $_POST['where'], array( 'page', 'form' ) ) ? $_POST['where'] : 'form';
		$tabs = self::get_option( $where, 'tabs' );
		
		switch ( $_POST['method'] ):
		case "update":
			$tabs[$_POST['index']] = $_POST['title'];
			$print = "updated";
			break;
		case "remove":
			// We need to set the proper item's fields to zero as well, and move them to the bench.
			$index = $_POST['index'];
			$tabs_count = count($tabs);
			
			unset( $tabs[ $index ] );
			
			// Delete the current field
			$count = $index + 1;
			$fields = self::get_option( $where, 'fields', 'tabbed-'.$count );
			self::delete_option( $where, 'fields', 'tabbed-'.$count );
			
			if ( is_array($fields) ): // Array was empty, so there is nothing to move
				$bench = self::get_option( $where, 'fields', 'bench' );
				$bench = array_merge( $bench, $fields ); // Merge but don't duplicate the fields if they are already there.
				$bench = self::update_option( $where, 'fields', 'bench', $bench ); // Save the new bench.
			endif;
			
			while ($count < $tabs_count):
				$count++;
				$fields = self::get_option( $where, 'fields', 'tabbed-'.$count );
				
				$previous = $count - 1;
				self::update_option( $where, 'fields', 'tabbed-'.$previous, $fields );
				$fields = self::delete_option( $where, 'fields', 'tabbed-'.$count );
			endwhile;
			
			$tabs = array_merge($tabs); // Reindex the $tabs array.
			$print = "removed";
			break;
		case "add":
			$tabs[] = $_POST['title'];
			$tabs_count = count($tabs); 
			self::update_option( $where, 'fields', 'tabbed-'.$tabs_count, array() );
			$print = "added";
			break;
		endswitch;
		
		self::update_option( $where, 'tabs', 'normal', $tabs );
		echo $print;
		die();
	}
}

if ( function_exists( 'add_action' ) && class_exists( 'Profile_CCT_Admin' ) ):
	add_action( 'plugins_loaded', array( 'Profile_CCT_Admin', 'init' ) );
endif;


