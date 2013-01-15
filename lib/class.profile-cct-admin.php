<?php

define( 'PROFILE_CCT_BASEADMIN', plugin_basename(__FILE__) );

/**
 * Profile_CCT_Admin class.
 */
class Profile_CCT_Admin {
	static public $action = 'display';
	static public $option = NULL;
	static public $page   = NULL;
    
	/**
	 * init function.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_init', array( 'Profile_CCT_Admin', 'admin_init' ) );
		add_action( 'admin_menu', array( 'Profile_CCT_Admin', 'add_menu_page' ) );
        
		// function removed the edit Public profile from everyone but the person who can really edit it
		add_action( 'wp_before_admin_bar_render', array('Profile_CCT_Admin', 'edit_admin_bar_render'), 20 );
	}

	/**
	 * admin_init function.
	 *
	 * @access public
	 * @return void
	 */
	static function admin_init() {
		// Register Settings
		register_setting( 'Profile_CCT_settings',    'Profile_CCT_settings' );
		register_setting( 'Profile_CCT_taxonomy',    'Profile_CCT_taxonomy' );
        
		// These are Options
		register_setting( 'Profile_CCT_form_fields', 'Profile_CCT_form_fields', array( __CLASS__, 'validate_form_fields' ) );
		register_setting( 'Profile_CCT_page_fields', 'Profile_CCT_page_fields', array( __CLASS__, 'validate_page_fields' ) );
		register_setting( 'Profile_CCT_list_page',   'Profile_CCT_list_page',   array( __CLASS__, 'validate_list_fields' ) );
		
		
		register_setting( 'Profile_CCT_list_page',   'Profile_CCT_list_page',   array( __CLASS__, 'validate_list_fields' ) );
		
		// redirect users to their profile page and create one if it doesn't exist
		Profile_CCT_Admin::redirect_to_public_profile();
        
		// function to be executed on form admin page
		add_action( 'profile_cct_form',            array( __CLASS__, 'form_field_shell' ), 10 );
        
		// function to be executed on page and list admin pages
		add_action( 'profile_cct_page',            array( __CLASS__, 'page_field_shell' ), 10, 3 );
		
		add_action( 'wp_ajax_cct_update_fields',   array( __CLASS__, 'update_fields' ) );
		add_action( 'wp_ajax_cct_update_tabs',     array( __CLASS__, 'update_tabs' ) );
		add_action( 'wp_ajax_cct_update_profiles', array( __CLASS__, 'refresh_profiles' ) );
		add_action( 'wp_ajax_cct_needs_refresh',   array( __CLASS__, 'set_profiles_need_refresh' ) );
        
		add_action( 'profile_cct_before_page',     array( __CLASS__, 'recount_field' ), 10, 1 );
		add_action( 'profile_cct_before_page',     array( __CLASS__, 'display_fields_check' ), 11, 1 );
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
			$arguments = array(
                'post_type'      => 'profile_cct',
                'author'         => $current_user->ID,
                'post_status'    => 'any',
                'posts_per_page' => 1,
            );
            
			$the_query = new WP_Query( $arguments );
			while ( $the_query->have_posts() ):
				$the_query->the_post();
				$id = get_the_ID();
			endwhile;
            
			// Reset Post Data
			wp_reset_postdata();
            
			if ( ! $id ):
				// lets create public profile on the fly...
				$post_arg = array(
    				'post_author'  => $current_user->ID,  //The user ID number of the author.
    				'post_content' => 'test', //The full text of the post.
  					'post_excerpt' => 'test2', //For all your post excerpt needs.
  					'post_status'  => 'draft',  //Set the status of the new post.
  					'post_title'   => $current_user->display_name, //The title of your post.
  					'post_type'    => 'profile_cct', //You may want to insert a regular post, page, link, a menu item or
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
    		if ( ! ( current_user_can('edit_profile_cct') && (int) $post->post_author != $current_user->ID ) && ! current_user_can('edit_others_profile_cct') ):
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
            
			// Remove and readd the logout button, in order to ensure, that Log Out appears at the bottom of the list.
			$wp_admin_bar->add_node( array(
				'parent' => 'user-actions',
				'id'     => 'logout',
				'title'  => __( 'Log Out' ),
				'href'   => wp_logout_url(),
			));
            
		endif;
    }


	/**
	 * form_field_shell function.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function form_field_shell() {
		// the default contexts normal, side, and tabs
		$contexts = Profile_CCT_Admin::default_shells();

		foreach ($contexts as $context):
			if ( function_exists( 'profile_cct_form_shell_'.$context ) ):
				call_user_func( 'profile_cct_form_shell_'.$context );
			else:
?>
		 		<div id="<?php echo $context; ?>-shell">
		 			<span class="description-shell"><?php echo $context; ?></span>
		 			<ul class="form-builder sort" id="<?php echo $context; ?>">
		 			<?php
					$fields = Profile_CCT_Admin::get_option( 'form', 'fields', $context );
					
					if ( is_array( $fields ) ):
						foreach ( $fields  as $field ):
							if( function_exists('profile_cct_'.$field['type'].'_field_shell') ):
								call_user_func( 'profile_cct_'.$field['type'].'_field_shell', $field, $data );
							else:
								do_action( 'profile_cct_field_shell_'.$field['type'], $field, $user_data[ $field['type'] ] );
							endif;
						endforeach;
					endif;
					?></ul>
		 		</div>
		 		<?php
			endif;
		endforeach;

	}

	/**
	 * page_field_shell function.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function page_field_shell() {
		self::$action = $action;
		$contexts = Profile_CCT_Admin::default_shells();
        
        if ($action == 'edit'):
            ?><div id="page-shell"><?php
        endif;
		foreach ( $contexts as $context ):
			// this is being called for tabs
			if ( function_exists('profile_cct_page_shell_'.$context) ):
				call_user_func('profile_cct_page_shell_'.$context, $action, $user_data);
			else:
				?>
                    <div id="<?php echo $context; ?>-shell" class="profile-cct-shell">
                    <?php if($action == 'edit'): ?>
		 			<span class="description-shell"><?php echo $context; ?></span>
		 			<ul class="form-builder sort" id="<?php echo $context; ?>">
                    <?php endif; ?>
                <?php
                
				$fields = self::get_option($where, 'fields', $context) ;//+ self::get_option('form','fields',$context);
                
				if ( is_array( $fields ) ):
					foreach( $fields as $field ):
						if ( function_exists('profile_cct_'.$field['type'].'_display_shell') ):
							call_user_func( 'profile_cct_'.$field['type'].'_display_shell', $action, $field, $user_data[ $field['type'] ] );
						else:
							do_action( 'profile_cct_display_shell_'.$field['type'], $action, $field, $user_data[ $field['type'] ] );
						endif;
					endforeach;
				endif;
            if ( $action == 'edit' ):
                ?>
                    </ul>
                <?php endif; ?>
                </div>
                <?php
            endif;
        endforeach;
        
        if ($action == 'edit'):
            ?>
                </div>
            <?php
		endif;
	}

	public static function recount_field( $where ) {
		if ( ! in_array( $where, array('form', 'page', 'list') ) ):
			return true;
		endif;
        
		// lets see what all the fields are that are suppoed to be there.
		$contexts = Profile_CCT_Admin::get_contexts($where);
        
		// CURRENT FIELDS
		// All the fields that are there.
		$current_fields = array();
		foreach ( $contexts as $context ):
			foreach ( (array) Profile_CCT_Admin::get_option($where, 'fields', $context) as $field ):
				$current_fields[] = $field['type'];
			endforeach;
		endforeach;
        
		// don't forget the bench fields.
		foreach ( Profile_CCT_Admin::get_option($where, 'fields', 'bench') as $field ):
			$current_fields[] = $field['type'];
		endforeach;
        
		// DYNAMIC FIELDS
		// all the fields that get included
		// - taxonomy fields
		// - db fields (added via the add field function)
		// all the once that are
		$dynamic_fields = apply_filters( "profile_cct_dynamic_fields", array(), $where );
		$all_dynamic_fields = array();
        $real_fields = array(); // array of all the default fields containing the field array with the key field['type']
        
		if ( is_array($dynamic_fields) ):
			foreach ( $dynamic_fields as $field ):
				$all_dynamic_fields[] 		 = $field['type'];
				$real_fields[$field['type']] = $field;
                
				if ( !in_array($field['type'], $current_fields) ): // add to the current_fields array
					$current_fields[] = $field['type'];
					Profile_CCT_Admin::$option[$where]['fields']['bench'][] = $field;
				endif;
			endforeach;
		endif;
        
		/*
		$this->e("current fields after merge with dynamic fields");
		$this->e($current_fields);
		
		$this->e("dynamic fields");
		$this->e($all_dynamic_fields);
		*/
        
		// DEFAULT FIELDS NOW
		unset($context);
        
		// all the other fields
		$default_fields = array();
        
		// get the default
		$default_options = Profile_CCT_Admin::default_options($where);
        
		foreach ( $default_options['fields'] as $context => $fields ):
			foreach ( $fields as $field ):
				$default_fields[] = $field['type'];
				$real_fields[$field['type']] = $field;
			endforeach;
			unset($field);
		endforeach;
        
		// also don't forget fields that are fields that were added later
		$new_fields = Profile_CCT_Admin::default_options('new_fields');
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
		$default_fields = array_merge($default_fields, $all_dynamic_fields);
        
		/*
		$this->e("default fields");
		$this->e($default_fields);
		// all the default fields should contain the dynamic fields as well
        
		$this->e("default fields after merging with default fields");
		$this->e($default_fields);
		*/
        
		// $this->e("difference between current_fields and default fields");
        
		$different = array_diff($default_fields, $current_fields);
        
		unset($field);
		if ( !empty($different) ):
			// add the fields back to the banch the array...
			foreach ( $different as $field ):
				Profile_CCT_Admin::$option[$where]['fields']['bench'][] = $real_fields[$field];
			endforeach;
		endif;
        
		return true;
	}

	public static function display_fields_check() {
        
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
			array( 'Profile_CCT_Admin', 'public_profile' )
        );
        
		// Order Page
		$order_page = add_submenu_page(
			'edit.php?post_type=profile_cct',
			__( 'Order Profiles', 'profile-cct-td' ),
			__( 'Order Profiles', 'profile-cct-td' ),
			'manage_options', 'order_profiles',
			array( 'Profile_CCT_Admin', 'admin_order_page' )
        );
        
		// Settings page
		$page = add_submenu_page(
			'edit.php?post_type=profile_cct',
			__( 'Settings', 'profile-cct-td' ),
			__( 'Settings', 'profile-cct-td' ),
			'manage_options', __FILE__,
			array( 'Profile_CCT_Admin', 'admin_pages' )
        );
        
		add_action( 'admin_print_styles-' . $order_page, 	array( 'Profile_CCT_Admin', 'order_profiles_admin_styles' ) );
		add_action( 'admin_print_scripts-' . $order_page, 	array( 'Profile_CCT_Admin', 'order_profiles_admin_scripts' ) );
        
		add_action( 'admin_print_styles-' . $page, 			array( 'Profile_CCT_Admin', 'admin_styles' ) );
		add_action( 'admin_print_scripts-' . $page, 		array( 'Profile_CCT_Admin', 'admin_scripts' ) );
        
		add_action( 'admin_print_styles-post-new.php', 		array( 'Profile_CCT_Admin','edit_profile_script' ) );
		add_action( 'admin_print_styles-post.php',			array( 'Profile_CCT_Admin','edit_profile_script' ) );
        
		add_action( 'admin_print_styles-post.php',			array( 'Profile_CCT_Admin','edit_profile_style' ) );
		add_action( 'admin_print_styles-post.php',			array( 'Profile_CCT_Admin','edit_profile_style' ) );
		add_action( 'admin_print_styles-edit.php',			array( 'Profile_CCT_Admin','edit_profile_style' ) );
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
			wp_enqueue_script( 'profile-cct-edit-post', PROFILE_CCT_DIR_URL.'/js/profile-page.js', array( 'jquery-ui-tabs' ) );
			// wp_localize_script( 'profile-cct-edit-post', 'profileCCTSocialArray', profile_cct_social_options());
		endif;
	}

	public static function edit_profile_style() {
		global $current_screen;
        
		if ( 'profile_cct' == $current_screen->id || 'edit-profile_cct' == $current_screen->id ):
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
		Profile_CCT_Admin::$action = 'edit';
		Profile_CCT_Admin::$page = ( in_array( $_GET['view'], array('form', 'page', 'list', 'taxonomy', 'fields', 'settings') ) ? $_GET['view'] : 'about' );
        
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
				<td><input type="checkbox" name="options[permissions][<?php echo esc_attr( $user ); ?>][<?php echo esc_attr( $action ); ?>]" <?php echo $disabled; ?> value="1" <?php checked( $can ); ?> /></td>
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
		wp_enqueue_script( 'profile-cct-order', PROFILE_CCT_DIR_URL.'/js/order-profiles.js', array('jquery', 'jquery-ui-sortable') );
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
			if ( ! is_array($options) ):
				$default = Profile_CCT_Admin::default_options( $type );
                
				if ( $fields_or_tabs == 'fields' ):
					$options = $default[$fields_or_tabs][$context];
				else:
					$options = $default[$fields_or_tabs];
                endif;
			endif;
            
			 // lets check if we have the fresh version since we last updated the plugin
			 /* CHECK to see if we need to do the merge */
			$perform_merge = false;
            
			// can we find the version settings
			if ( ! isset( $profile->settings['version'][$type][$fields_or_tabs][$context] ) ):
				$perform_merge = true;
			// are they less then the current version
			elseif ( PROFILE_CCT_VERSION > $profile->settings['version'][$type][$fields_or_tabs][$context] ):
				$perform_merge = true;
			endif;
            
			// lets perform the merge
			if( $perform_merge && $context == 'bench' ):
				$new_fields = self::default_options( 'new_fields' );
                
				// lets add the new fields in this version to the banch
				if( is_array( $new_fields[PROFILE_CCT_VERSION] ) ):
					foreach( $new_fields[PROFILE_CCT_VERSION] as $field ) :
						if( in_array( $type , $field['where'] ) ):
							$options[] = $field['field'];
						endif;
					endforeach;
					//  why are we doing this...
					// $this->update_option($type,$fields_or_tabs,$context,$options);
				endif;
			endif;
		endif;
        
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
	static function update_option( $type='form', $fields_or_tabs='fields', $context='normal', $update ) {
		$profile = Profile_CCT::get_object();
		$profile->settings['version'][$type][$fields_or_tabs][$context] = PROFILE_CCT_VERSION;
		$profile->settings[$type.'_updated'] = time();
		// saving of the version number
        
		self::$option[$type][$fields_or_tabs][$context] = $update;
		// update the settings
		update_option( 'Profile_CCT_settings', $profile->settings );
        
		return update_option( 'Profile_CCT_'.$type.'_'.$fields_or_tabs.'_'.$context, $update );
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
			//$value = $_POST['where'];
			$expiration = 3*DAY_IN_SECONDS; //Expires in 3 days.
			
			if ( $_POST['needs_refresh'] ):
				$value = get_transient('Profile_CCT_needs_refresh');
				error_log("Initial: ".print_r($value, TRUE));
				$value[$_POST['where']] = 1;
				error_log("After: ".print_r($value, TRUE));
				
				set_transient( $key, $value, $expiration );
			else:
				delete_transient( $key );
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
	 * default_shells function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @return void
	 */
	static function default_shells() {
		switch( Profile_CCT_Admin::$page ) {
			case 'form':
				return array( 'normal', 'side', 'tabs' );
                break;
			case 'page':
				return array( 'header', 'tabs', 'bottom' );
                break;
			case 'list':
				return array( 'normal' );
    			break;
		}
	}

	/**
	 * get_contexts function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @return void
	 */
	static function get_contexts() {
		$contexts = Profile_CCT_Admin::default_shells();
		$id = array_search( 'tabs', $contexts );
        
		if( is_numeric( $id ) ):
			$tabs = Profile_CCT_Admin::get_option( Profile_CCT_Admin::$page, 'tabs' );
            
			if( is_array( $tabs ) ):
				$count = 1;
				foreach($tabs as $tab):
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
	 * generate_profile function.
	 * 
	 * @access public
	 * @param mixed $section
	 * @return void
	 */
	static function generate_profile( $section ) {
		switch ( $section ) {
		case 'bench':
			Profile_CCT_Admin::render_context( $section, false );
			break;
		case 'preview':
			?>
			<div id="<?php echo Profile_CCT_Admin::$page; ?>-shell">
			<?php
			foreach ( Profile_CCT_Admin::default_shells() as $shell ):
				// lets get all the different sections
				Profile_CCT_Admin::render_context( $shell );
			endforeach;
			// Show the preview
			?>
			</div>
			<?php
			break;
		}
	}
	
	/**
	 * render_context function.
	 * 
	 * @access public
	 * @param mixed $context
	 * @param bool $display_context (default: true)
	 * @return void
	 */
	static function render_context( $context, $display_context = true ) {
		$class = ( 'bench' != $context ? 'form-builder' : '' );
        
		if ( function_exists('profile_cct_'.$context.'_shell') ):
			call_user_func('profile_cct_'.$context.'_shell');
		else:
			// class //profile-cct-shell should be added to the profile stuff
			?>
			<div id="<?php echo $context; ?>-shell" >
					<?php if ( $display_context ): ?>
					<span class="description-shell"><?php echo $context; ?></span>
					<?php endif; ?>
					<ul class="sort <?php echo $class; ?>" id="<?php echo $context; ?>">
				<?php
				$fields = Profile_CCT_Admin::get_option( Profile_CCT_Admin::$page, 'fields', $context );
				if ( is_array( $fields ) ):
					foreach ( $fields as $field ):
						if ( function_exists('profile_cct_'.$field['type'].'_shell') ):
							call_user_func( 'profile_cct_'.$field['type'].'_shell', $field, $data );
						else:
							do_action( 'profile_cct_field_shell_'.$field['type'], $field, $user_data[ $field['type'] ] );
						endif;
					endforeach;
				endif;
				?>
					</ul>
			</div>
			<?php
		endif;
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
	 * add_field function.
	 * function return by ajax to be displayed
	 * @access public
	 * @return void
	 */
	static function update_fields() {
		$context = $_POST['context'];
		
		if ( in_array($_POST['where'], array('form', 'page', 'list')) ):
			$where = $_POST['where'];
		else:
			$where = 'form';
		endif;
		
		if ( in_array($_POST['width'], array('full', 'half', 'one-third', 'two-third')) ):
			$width = $_POST['width'];
		else:
			$width = 'full';
		endif;
		
		$options = self::get_option($where, 'fields', $context);
		
		switch ( $_POST['method'] ):
		case "update":
			if ( is_numeric( $_POST['field_index'] ) ):
				switch ($where):
				case "form":
					$options[$_POST['field_index']]['label']       = $_POST['label'];
					$options[$_POST['field_index']]['description'] = $_POST['description'];
					$options[$_POST['field_index']]['show']        = $_POST['show'];
					$options[$_POST['field_index']]['multiple']    = isset($_POST['multiple']) && $_POST['multiple'] ? $_POST['multiple'] : 0;
					//TODO: Figure out what the url_prefix is.
					$options[$_POST['field_index']]['url_prefix']  = $_POST['url_prefix'];
					
					// save the url prefix also in the settings array
					if ( ! is_array( Profile_CCT::$settings['data-url'] ) ):
						Profile_CCT::$settings['data-url'] = array();
						Profile_CCT::$settings['data-url'] = array_merge( Profile_CCT::$settings['data-url'], array( $_POST['type'] => trim($_POST['url_prefix']) ) );
						update_option('Profile_CCT_settings', Profile_CCT::$settings);
					endif;
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
		self::update_option($where, 'fields', $context, $options);
		echo $print;
		error_log("Die");
		die();
	}
	
	/**
	 * An Ajax functon used to save tabs.
	 *
	 * @access public
	 * @return void
	 */
	function update_tabs() {
		$where = in_array( $_POST['where'], array('page', 'form') ) ? $_POST['where'] : 'form';
		$tabs = self::get_option($where, 'tabs');
		
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
			$fields = self::get_option($where, 'fields', 'tabbed-'.$count);
			self::delete_option($where, 'fields', 'tabbed-'.$count);
			
			if ( is_array($fields) ): // Array was empty, so there is nothing to move
				$bench = self::get_option($where, 'fields', 'bench');
				$bench = array_merge($bench, $fields); // Merge but don't duplicate the fields if they are already there.
				$bench = self::update_option($where, 'fields', 'bench', $bench); // Save the new bench.
			endif;
			
			while ($count < $tabs_count):
				$count++;
				$fields = self::get_option($where, 'fields', 'tabbed-'.$count);
				
				$previous = $count - 1;
				self::update_option($where, 'fields', 'tabbed-'.$previous, $fields);
				
				$fields = self::delete_option($where, 'fields', 'tabbed-'.$count);
			endwhile;
			
			$tabs = array_merge($tabs); // Reindex the $tabs array.
			$print = "removed";
			break;
		case "add":
			$tabs[] = $_POST['title'];
			$tabs_count = count($tabs); 
			self::update_option($where, 'fields', 'tabbed-'.$tabs_count, array());
			$print = "added";
			break;
		endswitch;
		
		self::update_option($where, 'tabs', 'normal', $tabs);
		echo $print;
		die();
	}
}

if ( function_exists( 'add_action' ) && class_exists( 'Profile_CCT_Admin' ) ):
	add_action( 'plugins_loaded', array( 'Profile_CCT_Admin', 'init' ) );
endif;