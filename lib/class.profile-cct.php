<?php 


/**
 * Profile_CPT class.
 */
class Profile_CCT {
	static private $classobj = NULL; // refence for itself
	static public  $textdomain  = NULL;
	static public  $action   = NULL;
	static public  $settings = NULL; // renamed from  $settings_options
	static public  $form_fields = NULL;
	static public  $taxonomies = NULL; // 
	static public  $is_main_query = false;
	static public  $form_field_options = NULL; 
	static public  $option     = NULL; 
	static public  $current_form_fields = NULL; // stores the current state of the form field... the labels and if it is on the banch... 
	static public  $version;
    
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	function __construct () {
		
		add_action('init', array( $this, 'init' ) );
		$this->settings 	= $this->get_settings( 'settings' );
		$this->taxonomies 	= $this->get_settings( 'taxonomy' );
		
	}
	
	/**
	 * get_object function.
	 * 
	 * @access public
	 * @return void
	 */
	function get_object() {
		
		if ( NULL === self :: $classobj )
			self :: $classobj = new self;
			
		return self :: $classobj;
	}
	
	/**
	 * init function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public function init() {
		
		$this->register_profiles();
		$this->load_fields();
		
	}
	
	/**
	 * register_profiles function.
	 * 
	 * @access public
	 * @return void
	 */
	function register_profiles() {
		
		if( empty( $this->settings['slug'] ) ) {
			$slug = 'person';
		
		} else {
			$slug = $this->settings['slug'];
		}
		
		$labels = array(
			'name' => _x( 'Profiles', 'profile_cct' ),
			'singular_name' => _x( 'Profile', 'profile_cct' ),
			'add_new' => _x( 'Add New', 'profile_cct' ),
			'add_new_item' => _x( 'Add New Profile', 'profile_cct' ),
			'edit_item' => _x( 'Edit Public Profile', 'profile_cct' ),
			'new_item' => _x( 'New Profile', 'profile_cct' ),
			'view_item' => _x( 'View Profile', 'profile_cct' ),
			'search_items' => _x( 'Search Profiles', 'profile_cct' ),
			'not_found' => _x( 'No profiles found', 'profile_cct' ),
			'not_found_in_trash' => _x( 'No profiles found in Trash', 'profile_cct' ),
			'parent_item_colon' => _x( 'Parent Profile:', 'profile_cct' ),
			'menu_name' => _x( 'Profiles', 'profile_cct' ),
		);

		$args = array(
			'labels' => $labels,
			'hierarchical' => false,
			'menu_icon' => PROFILE_CCT_DIR_URL.'/icon.png',
			'supports' => array( 'revisions','author','page-attributes'),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 20,
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => array(
				'slug' => $slug,
				'with_front' => true,
				'feeds' => true,
				'pages' => true
			),
			'capabilities' => array(
				'edit_post' => 			'edit_profile_cct', // used for has public profile
				'edit_posts' => 		'edit_profiles_cct',
				'edit_others_posts' => 	'edit_others_profile_cct',
				'publish_posts' => 		'publish_profile_cct',
				'read_post' => 			'read_profile_cct',
				'read_private_posts' => 'read_private_profile_cct',
				'delete_post' => 		'delete_profile_cct',
				'delete_others_posts' =>'delete_others_profile_cct'
			)
		);
        
		register_post_type( 'profile_cct', $args );
	}
	
	function load_fields() {
		// include all files in the fields folder
		if ( $handle = opendir( PROFILE_CCT_DIR_PATH . 'views/fields/' ) ) :
			// This is the correct way to loop over the directory.
			while ( false !== ( $file = readdir( $handle ) ) ):
				if( substr($file,0,1) != "." )
					if( !is_dir( PROFILE_CCT_DIR_PATH . 'views/fields/' . $file ) )
						require( PROFILE_CCT_DIR_PATH . 'views/fields/' . $file );
				endwhile;
			closedir( $handle );
		endif;
        
	}
	
	function get_settings( $type='settings' ) {
		
		// if non exist get the default settings 
		if( $settings = get_option( 'Profile_CCT_'.$type ) ):
			return $settings;
        else:
            return get_default_settings($type);
        endif;
        
	}
	
	function get_default_settings( $type = 'settings' ) {
        // load the default options array 
        require( PROFILE_CCT_DIR_PATH.'default-options.php' );
        return  $option[$type];
	}
	
	/**
	 * delete_all_settings function.
	 * delets every settings 
	 * @access public
	 * @return void
	 */
	function delete_all_settings(){
		// only administator can do this… 
		if( current_user_can('administrator') ):
			
			foreach( array("form","page","list") as $where):
				// delete all the fields
				foreach( self::get_contexts( $where ) as $context ):
					self::delete_option( $where,'fields',$context);
				endforeach;
				
				// lets not forget the banch 
				self::delete_option( $where,'fields','bench');
				
				// also delete all the tabs 
				self::delete_option( $where,'tabs');
				
			endforeach;
            
			// finally delete the settings data 
			delete_option( 'Profile_CCT_settings' );
			
			delete_option( 'profile_cct_version' );
			
			// also delete all the taxonomies 
			delete_option( 'Profile_CCT_taxonomy' );
			
			// also the global settings only super admin can do this
			if (current_user_can( 'manage_sites' ) && $_GET['delete_profile_cct_data'] == "DELETE-GLOBAL" ):
				delete_site_option('Profile_CCT_global_settings');
            endif;
		endif;
	}
    
	/**
	 * install function.
	 * gets run on plugin install
	 * 
	 * @access public
	 * @return void
	 */
    static function install() {
        error_log('Install');
		$field = Profile_CCT::get_object();
		$field->register_profiles();
		flush_rewrite_rules();
		
		// set up the permissions
		if( !is_array( $field->settings['permissions'] ) ) {
			$settings = $field->get_default_settings( 'settings' );
			$field->settings['permissions'] = $settings['permissions'];
		}
		
		foreach($field->settings['permissions'] as $user=>$permission_array):
			$role = get_role( $user );
			
			foreach($permission_array as $permission => $can):
                
				// add the new capability
				if( $field->settings['permissions'][$user][$permission] ):
					$role->add_cap( $permission );
				else: // or remove it
					$role->remove_cap(  $permission );
				endif;
				
			endforeach;
			
		endforeach;
		
	}
    
	/**
	 * deactivate( function.
	 * 
	 * @access public
	 * @return void
	 */
	static function deactivate() {
		error_log('Deactive');
		// remove permissions
		$profile = Profile_CCT::get_object();
		$default = $profile->get_default_settings( 'settings' );
		
		foreach($default['permissions'] as $user=>$permission_array):
			$role = get_role( $user );
			
			foreach($permission_array as $permission => $can):
					$role->remove_cap(  $permission );	
			endforeach;
			
		endforeach;
		//$profile->delete_all_settings();
	}
	
	/**
	 * deactivate( function.
	 * 
	 * @access public
	 * @return void
	 */
	static function uninstall() {
		
		// remove permissions
		$profile = Profile_CCT::get_object();
		$profile->deactivate();
		//$profile->delete_all_settings();
		
		// also delete all the users info
	}

	
	public static function version() {
		return get_option( 'profile_cct_version', '1.1.8' );
		return $this->version;
	}
	
}

if ( function_exists( 'add_action' ) && class_exists( 'Profile_CCT' ) ):
	
	add_action( 'plugins_loaded', array( 'Profile_CCT', 'get_object' ) );
	
endif;

