<?php 


/**
 * Pulse_CPT class.
 */
class Pulse_CPT_Admin {
	
	/**
	 * init function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function init() {
		
		add_action( 'admin_menu', array( 'Pulse_CPT_Admin', 'add_menu_page' ) );
		
		
		
			
	}
	
	/**
	 * admin_init function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_init() {
		global $plugin_page, $pagenow, $current_user;
		/* Register Settings */
		register_setting( 'Profile_CCT_form_fields', 'Profile_CCT_form_fields',  array($this,'validate_form_fields'));
		register_setting( 'Profile_CCT_page_fields', 'Profile_CCT_page_fields', array($this,'validate_page_fields'));
		register_setting( 'Profile_CCT_list_page', 'Profile_CCT_list_page', array($this,'validate_list_fields')  );
		register_setting( 'Profile_CCT_settings', 'Profile_CCT_settings' );
		register_setting( 'Profile_CCT_taxonomy', 'Profile_CCT_taxonomy' );
		
		
		if( isset( $_GET['delete_profile_cct_data']) )
			$this->delete_all();
		
		// redirect users to their profile page and create one if it doesn't exist
		if($plugin_page == 'public_profile' &&  in_array($pagenow, array('profile.php','users.php'))  ):
		
			
			$arguments = array(
						'post_type' => 'profile_cct',
						'author'	=> $current_user->ID,
						'post_status'=> 'any',
						'posts_per_page'=> 1,
						);
			
			$the_query = new WP_Query( $arguments );
			while ( $the_query->have_posts() ) : $the_query->the_post();
				$id = get_the_ID();
			endwhile;
			// Reset Post Data
			wp_reset_postdata();
			
			if(!$id):
				// lets create public profile on the fly...
				$post_arg = array(
    				'post_author' => $current_user->ID,  //The user ID number of the author.
    				'post_content' => 'test', //The full text of the post.
  					'post_excerpt' => 'test2', //For all your post excerpt needs.
  					'post_status' => 'draft',  //Set the status of the new post. 
  					'post_title' => $current_user->display_name, //The title of your post.
  					'post_type' => 'profile_cct' //You may want to insert a regular post, page, link, a menu item or 
				);  
				
				$id = wp_insert_post( $post_arg );
				
			
			endif;
			
			wp_redirect(  admin_url('post.php?post='.$id.'&action=edit') );
			exit;
			
		endif;

	}
	
	public function add_menu_page() {
		
		// public profile page
		$public_profile = add_submenu_page(
			'users.php',
			__( 'Public Profile', 'profile-cct-td' ),
			__( 'Public Profile', 'profile-cct-td' ),
			'edit_profile_cct', 'public_profile',
			array( 'Pulse_CPT_Admin', 'public_profile' ) );
		
		// Settings page
		$page = add_submenu_page(
			'edit.php?post_type=profile_cct',
			__( 'Settings', 'profile-cct-td' ),
			__( 'Settings', 'profile-cct-td' ),
			'manage_options', __FILE__,
			array( 'Pulse_CPT_Admin', 'admin_pages' ) );
		
		// Order Page
		$order_page = add_submenu_page(
			'edit.php?post_type=profile_cct',
			__( 'Order Profiles', 'profile-cct-td' ),
			__( 'Order Profiles', 'profile-cct-td' ),
			'manage_options', "order_profiles",
			array( 'Pulse_CPT_Admin', 'admin_order_page' ) );
			
		
			
		add_action( 'admin_print_styles-' . $order_page, 	array( 'Pulse_CPT_Admin', 'order_profiles_admin_styles' ) );
		add_action( 'admin_print_scripts-' . $order_page, 	array( 'Pulse_CPT_Admin', 'order_profiles_admin_scripts' ) );
				
		add_action( 'admin_print_styles-' . $page, 			array( 'Pulse_CPT_Admin', 'admin_styles' ) );
		add_action( 'admin_print_scripts-' . $page, 		array( 'Pulse_CPT_Admin', 'admin_scripts' ) );
		

	}
	
	public function public_profile() {
		// a page asking the user to create a public profile 
		wp_die('redirect didn\'t work');
	
	}
	
	public function admin_pages() {
	
	}
	
	public function admin_order_page() {
	
		
	}
	

}
if ( function_exists( 'add_action' ) && class_exists( 'Pulse_CPT_Admin' ) )
	add_action( 'plugins_loaded', array( 'Pulse_CPT_Admin', 'init' ) );
