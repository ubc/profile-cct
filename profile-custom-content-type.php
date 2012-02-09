<?php
/**
 * Plugin Name: Profile Custom Content Type
 * Plugin URI:
 * Text Domain: profile_cct
 * Domain Path: /languages
 * Description: Allows administrators to manage user profiles better in order to display them on their websites
 * Author: Enej Bajgoric, CTLT
 * Version: 1.0beta
 * Licence: GPLv2
 * Author URI: http://ctlt.ubc.ca
 */


/**
 License:
 ==============================================================================
 Copyright CTLT (email : support.cms@ubc.ca)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

 Requirement
 ==============================================================================
 This plugin requires WordPress >= 3.2 and tested with PHP Interpreter >= 5.2
 */

// don't load directly
if ( !defined('ABSPATH') )
	die('-1');


if(isset( $_GET['d'])):
	delete_option('Profile_CCT_form_fields_tabbed-1');
delete_option('Profile_CCT_form_fields_tabbed-2');
delete_option('Profile_CCT_form_fields_tabbed-3');
delete_option('Profile_CCT_form_fields_tabbed-4');
delete_option('Profile_CCT_form_fields_tabbed-5');
delete_option('Profile_CCT_form_fields_tabbed-6');
delete_option('Profile_CCT_form_fields_normal');
delete_option('Profile_CCT_form_fields_side');
delete_option('Profile_CCT_form_fields_bench');
delete_option('Profile_CCT_form_tabs_normal');


delete_option('Profile_CCT_page_fields_tabbed-1');
delete_option('Profile_CCT_page_fields_tabbed-2');
delete_option('Profile_CCT_page_fields_tabbed-3');
delete_option('Profile_CCT_page_fields_tabbed-4');
delete_option('Profile_CCT_page_fields_tabbed-5');
delete_option('Profile_CCT_page_fields_tabbed-6');
delete_option('Profile_CCT_page_fields_header');
delete_option('Profile_CCT_page_fields_side');
delete_option('Profile_CCT_page_fields_bottom');
delete_option('Profile_CCT_page_fields_bench');
delete_option('Profile_CCT_page_tabs_normal');
delete_option('Profile_CCT_page_fields');

delete_option('Profile_CCT_list_fields_normal');
delete_option('Profile_CCT_list_fields_bench');

delete_option('Profile_CCT_page_fields');

endif;

require_once('profile-taxonomies.php');
require_once('profile-manage-table.php');

class Profile_CCT {
	static private $classobj = NULL;

	static public  $textdomain  = NULL;
	static public  $action   = NULL;
	static public  $settings_options = NULL;
	static public  $form_fields = NULL;
	static public  $taxonomies = NULL;
	static public  $field_options = NULL;
	static public  $option     = NULL;
	static public  $field_options_type = NULL;

	/**
	 * construct
	 *
	 * @uses
	 * @access public
	 * @since 0.0.1
	 * @return void
	 */
	public function __construct () {

		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		/* saving the post meta info */
		add_action( 'edit_form_advanced', array($this, 'edit_form_advanced'));
		add_action( 'add_meta_boxes_profile_cct', array($this, 'edit_post')); // add meta boxes

		add_action( 'init',  array( $this,'profiles_cct_init'),0) ;

		add_action( 'template_redirect',  array( $this,'check_freshness'));
		add_action( 'wp_insert_post_data', array( $this,'save_post_data'),10,2);

		add_action( 'wp_ajax_cct_update_fields', array( $this,'update_fields'));
		add_action( 'wp_ajax_cct_update_tabs', array( $this,'update_tabs'));

		add_action( 'admin_print_styles-post-new.php', array( $this,'add_style_edit'));
		add_action( 'admin_print_styles-post.php',array( $this,'add_style_edit'));

		add_action( 'admin_init',array($this,'admin_init'));

		$this->settings_options = get_option('Profile_CCT_settings');

		$dir    = plugin_dir_path(__FILE__).'views/fields/';

		// include all files in the fields folder
		if ($handle = opendir($dir)) :
			/* This is the correct way to loop over the directory. */
			while (false !== ($file = readdir($handle))):
				if(substr($file,0,1) != ".")
					require_once($dir.$file);

				endwhile;

			closedir($handle);
		endif;
		// function to be executed on form admin page
		add_action('profile_cct_form', array( $this,'profile_cct_form_field_shell'),10,1);

		// function to be executed on page and list admin pages
		add_action('profile_cct_page', array( $this,'profile_cct_page_field_shell'),10,3);
		
		// function removed the edit Public profile from everyone but the person who can really edit it
		add_action( 'wp_before_admin_bar_render', array($this, 'edit_admin_bar_render'),20 );

	}
	/**
	 * edit_admin_bar_render function.
	 * 
	 * @access public
	 * @return void
	 */
	function edit_admin_bar_render() {
    	global $wp_admin_bar, $post, $current_user;
    	
    	if ( 'profile_cct' == get_post_type() ):
    		
    		 if( (int)$post->post_author != $current_user->ID && !current_user_can('edit_others_profile_cct') ):
    			$wp_admin_bar->remove_menu('edit');
    		endif;
    	endif;	
    		
    }

	/**
	 * points the class
	 *
	 * @access public
	 * @since 0.0.1
	 * @return object
	 */
	public function get_object () {

		if ( NULL === self :: $classobj )
			self :: $classobj = new self;

		return self :: $classobj;
	}

	/**
	 * get_textdomain function.
	 *
	 * @access public
	 * @return void
	 */
	public function get_textdomain() {
		return $this ->get_plugin_data( 'TextDomain' );
	}
	/**
	 * add_style_edit function.
	 *
	 * @access public
	 * @return void
	 */
	function add_style_edit() {
		global $current_screen;

		if($current_screen->id == 'profile_cct'):
			wp_enqueue_style("thickbox");
			wp_enqueue_script("thickbox");
	
			wp_enqueue_style( 'profile-cct-edit-post', WP_PLUGIN_URL . '/profile-cct/css/profile-page.css' );
			wp_enqueue_script( 'profile-cct-edit-post', WP_PLUGIN_URL . '/profile-cct/js/profile-page.js',array('jquery-ui-tabs' ) );
			wp_localize_script( 'profile-cct-edit-post', 'profileCCTSocialArray', profile_cct_social_options());

		endif;

	}
	/**
	 * return plugin comment data
	 *
	 * @uses get_plugin_data
	 * @access public
	 * @since 0.0.1
	 * @param $value string, default = 'Version'
	 * Name, PluginURI, Version, Description, Author, AuthorURI, TextDomain, DomainPath, Network, Title
	 * @return string
	 */
	private function get_plugin_data ( $value = 'Version' ) {

		$plugin_data = get_plugin_data ( __FILE__ );
		$plugin_value = $plugin_data[$value];

		return $plugin_value;
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


		// $this->e($role);
			/*
					'edit_post' => 'edit_profile_cct',
		            'edit_posts' => 'edit_profiles_cct',
		            'edit_others_posts' => 'edit_others_profile_cct',
		            'publish_posts' => 'publish_profile_cct',
		            'read_post' => 'read_profile_cct',
		            'read_private_posts' => 'read_private_profile_cct',
		            'delete_post' => 'delete_profile_cct'
		            */
		$roles = array('author','contributor');
		foreach( $roles as $role_name):
		
			$role = get_role( $role_name ); // gets the author role
			$role->add_cap( 'edit_profiles_cct', false );
			$role->add_cap( 'edit_profile_cct', true ); //
			$role->add_cap( 'publish_profile_cct', false );
			$role->add_cap( 'edit_others_profile_cct',false);
		endforeach;

		$role = get_role( 'subscriber' ); // gets the author role
		$role->add_cap( 'public_profile_profiles_cct', false );

		// redirect users to their profile page
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
				// var_dump($current_user);
				
				
				
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
		
		
		//if($plugin_page)

	}
	function e($data){
		echo "<pre>";
		var_dump($data);
		echo "</pre>";

	}
	/**
	 * add_menu_page function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_menu_page() {
		$current_user = wp_get_current_user();
		
		
		$public_profile = add_submenu_page(
			'users.php',
			__( 'Public Profile', $this -> get_textdomain() ),
			__( 'Public Profile', $this -> get_textdomain() ),
			'read', 'public_profile',
			array( $this, 'public_profile' ) );
		if( !$current_user->has_cap('edit_others_profile_cct') ):
		endif;
		$page = add_submenu_page(
			'edit.php?post_type=profile_cct',
			__( 'Settings', $this -> get_textdomain() ),
			__( 'Settings', $this -> get_textdomain() ),
			'manage_options', __FILE__,
			array( $this, 'admin_pages' ) );

		add_action( 'admin_print_styles-' . $page, array( $this, 'admin_styles' ) );
		add_action( 'admin_print_scripts-' . $page, array( $this, 'admin_scripts' ) );

	}

	function public_profile(){
	
	
		// a page asking the user to create a public profile 
		echo ('redirect didn\'t work');
	}

	/**
	 * admin_styles function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_styles() {

		// todo: this could be done with one css file
		wp_enqueue_style( 'profile-cct-admin', WP_PLUGIN_URL . '/profile-cct/css/admin.css' );
		switch( $_GET['view'] ) {
		case "form":
		case "page":
		case "list":
			wp_enqueue_style( 'profile-cct-form', WP_PLUGIN_URL . '/profile-cct/css/form.css' );
			break;
		default:
			wp_enqueue_style( 'profile-cct-settings', WP_PLUGIN_URL . '/profile-cct/css/settings.css' );
			break;

		}
		wp_enqueue_style( 'profile-cct-general', WP_PLUGIN_URL . '/profile-cct/css/general.css' );
	}
	/**
	 * admin_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_scripts() {

		switch( $_GET['view'] ) {
		case "form":
			wp_enqueue_script( 'profile-cct-form', WP_PLUGIN_URL . '/profile-cct/js/form.js',array('jquery','jquery-ui-sortable') );
			wp_enqueue_script( 'profile-cct-tabs', WP_PLUGIN_URL . '/profile-cct/js/tabs.js',array('jquery','jquery-ui-tabs') );
			wp_localize_script( 'profile-cct-form', 'ProfileCCT', array(
					'type' => 'form'
				));
			break;
		case "page":
			wp_enqueue_script( 'profile-cct-tabs', WP_PLUGIN_URL . '/profile-cct/js/tabs.js',array('jquery','jquery-ui-tabs') );
			wp_enqueue_script( 'profile-cct-form', WP_PLUGIN_URL . '/profile-cct/js/form.js',array('jquery','jquery-ui-sortable') );
			wp_enqueue_script( 'profile-cct-profile', WP_PLUGIN_URL . '/profile-cct/js/profile.js',array('jquery') );
			wp_localize_script( 'profile-cct-form', 'ProfileCCT', array(
					'type' => 'page'
				));
			break;
		case "list":
			wp_enqueue_script( 'profile-cct-form', WP_PLUGIN_URL . '/profile-cct/js/form.js',array('jquery','jquery-ui-sortable') );
			wp_enqueue_script( 'profile-cct-profile', WP_PLUGIN_URL . '/profile-cct/js/profile.js',array('jquery') );
			wp_localize_script( 'profile-cct-form', 'ProfileCCT', array(
					'type' => 'list'
				));
			break;
			/*case "helper":
				wp_register_style( 'profile-cct-helper', WP_PLUGIN_URL . '/profile-cct/stylesheet.css' );
			break;
			*/
		default:
			// wp_enqueue_script( 'profile-cct-settings', WP_PLUGIN_URL . '/profile-cct/js/settings.js' );
			break;



		}
		wp_enqueue_style( 'profile-cct-general', WP_PLUGIN_URL . '/profile-cct/js/general.js' );
	}
	/**
	 * admin_pages function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_pages() {
		$type_of = (in_array($_GET['view'], array('form','page','list'))? $_GET['view']: NULL );

		do_action('profile_cct_admin_pages', $type_of);
		if($type_of):
			if(!is_array($this->field_options[$type_of]))
				$this->field_options[$type_of] = array();

			foreach ($this->get_contexts($type_of) as $context):

				$fields = $this->get_option($type_of, 'fields',$context);
			if( is_array($fields) ):
				foreach( $fields as $field ):
					$this->field_options[$type_of][] = $field;
				$this->field_options_type[$type_of][] = $field['type'];
			endforeach;
		endif;
		unset($fields, $field);
		endforeach;

		// lets not forget the bench
		$fields = $this->get_option($type_of, 'fields','bench');
		if( is_array($fields) ):
			foreach( $fields as $field ):
				$this->field_options[$type_of][] = $field;
			$this->field_options_type[$type_of][] = $field['type'];
		endforeach;
		endif;

		unset($fields, $field);

		// ability to add new field such as dynamic once though this
		// each type has to be unique
		$dynamic_fields = apply_filters("profile_cct_dynamic_fields", array(),$type_of );

		if(is_array($dynamic_fields)):
			foreach($dynamic_fields as $field):
				// if we can't find the field lets add it to the other things
				if( !in_array($field['type'], $this->field_options_type[$type_of]) ):

					$this->field_options[$type_of][] = $field;
				$this->field_options_type[$type_of][] = $field['type'];
			$this->option[$type_of]['fields']['bench'][] = $field;

		endif;
		endforeach;
		endif;

		endif;


		screen_icon( 'users' );
?>
		<div class="wrap">
		<h2>Profile Settings</h2>
		<h3 class="nav-tab-wrapper">

		<a class="nav-tab <?php if( !isset($_GET['view']) ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php">About</a>
		<span>Builder:</span>
		<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='taxonomy' ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=taxonomy">Taxonomy</a>
		<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='form' ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=form">Form</a>
		<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='page' ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=page">Person View</a>
		<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='list' ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=list">List View</a>
		
		<!-- 
		<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='fields' ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=fields">Fields</a>
		
		<a class="nav-tab <?php if( isset($_GET['view']) && $_GET['view'] =='helper' ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=helper">HELPER</a>
			-->
		</h3>
		
		<?php
		$this->action = 'edit';
		switch( $_GET['view'] ) {
		case "form":
			require_once("views/form.php");
			break;
		case "page":
			require_once("views/page.php");
			break;
		case "list":
			require_once("views/list.php");
			break;
		case "helper":
			require_once("views/helper.php");
			break;
		case "taxonomy":
			require_once("views/taxonomy.php");
			break;
		case "fields":
			require_once("views/fields.php");
			break;
		default:
			require_once("views/about.php");
			break;

		}
	}
	/**
	 * profiles_cct_init function.
	 *
	 * @access public
	 * @return void
	 */
	function profiles_cct_init() {
		$this->taxonomies = get_option( 'Profile_CCT_taxonomy');

		$this->register_cpt_profile_cct();
		$this->load_scripts_cpt_profile_cct();
	}

	/**
	 * register_cpt_profile_cct function.
	 *
	 * @access public
	 * @return void
	 */
	function register_cpt_profile_cct() {
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

			'supports' => array( 'revisions','author'  ),
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
				'slug' => 'person',
				'with_front' => true,
				'feeds' => true,
				'pages' => true
			),
			'capabilities' => array(
				'edit_post' => 			'edit_profile_cct',
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

		$roles = apply_filters('profile_cct_admin_roles', array('administrator','editor'));
		
		foreach($roles as $role_name):
			$role = get_role($role_name);

			$role->add_cap( 'edit_profile_cct' );
			$role->add_cap( 'edit_profiles_cct' );
			$role->add_cap( 'edit_all_profile_cct' );
			$role->add_cap( 'publish_profile_cct' );
			$role->add_cap( 'read_private_profile_cct' );
			$role->add_cap( 'delete_profile_cct' );

		endforeach;

	}

	/**
	 * load_scripts_cpt_profile_cct function.
	 *
	 * @access public
	 * @return void
	 */
	function load_scripts_cpt_profile_cct() {
		if(!is_admin()):
			wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_style( 'profile-cct', WP_PLUGIN_URL . '/profile-cct/css/profile-cct.css' );
		endif;
		//add_filter('template_include', array( $this, 'help' ));
	}
	/**
	 * check_freshness function.
	 *
	 * @access public
	 * @return void
	 */
	function check_freshness() {
		$tax = array();

		if( is_array($this->taxonomies) ):
			foreach($this->taxonomies as $taxonomy):
				$tax[] = 'profile_cct_'.str_replace( '-','_',sanitize_title($taxonomy['single']));
			endforeach;
		endif;

		if(is_post_type_archive( 'profile_cct' ) || is_tax($tax)):

			if ( have_posts() ) : while ( have_posts() ) : the_post();

				global $post;

			if( $this->settings_options["list_updated"] > strtotime($post->post_modified_gmt )):

				$data = get_post_meta($post->ID, 'profile_cct', true);
			ob_start();
		do_action('profile_cct_page','display', $data, 'page');
		$content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action('profile_cct_page','display', $data,'list');
		$excerpt = ob_get_contents();
		ob_end_clean();

		$post->post_excerpt = $excerpt;
		$post->post_content = $content;

		$this->update_profile( $post );
		endif;
		endwhile;

		endif;

		rewind_posts();

		endif;
		//
		if(is_singular( 'profile_cct' )):
			global $post;



		if ( have_posts() ) : while ( have_posts() ) : the_post();
			global $post;

		if( $this->settings_options["page_updated"] > strtotime($post->post_modified_gmt )):

			$data = get_post_meta($post->ID, 'profile_cct', true);
		ob_start();
		do_action('profile_cct_page','display', $data, 'page');
		$content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action('profile_cct_page','display', $data,'list');
		$excerpt = ob_get_contents();
		ob_end_clean();


		$post->post_excerpt = $excerpt;
		$post->post_content = $content;

		$this->update_profile( $post );
		endif;

		endwhile;

		endif;
		rewind_posts();





		endif;
	}

	function update_profile( $post ) {

		$mypost['ID'] = $post->ID;

		$mypost['post_content'] = $post->post_content;
		$mypost['post_excerpt'] = $post->post_excerpt;

		wp_update_post( $mypost );

	}
	/**
	 * edit_post function.
	 *
	 * @access public
	 * @return void
	 */
	function edit_post() {
		global $post, $post_new_file, $pagenow, $current_user;
			$post_new_file = '#';
		
		
		// who can edit this
		
		
		if( (int)$post->post_author != $current_user->ID && !current_user_can('edit_others_profile_cct') ):
			
			wp_die('You are not allow to edit this profile');
			
		endif;
		
		
		$this->form_fields = get_option('Profile_CCT_form_fields');

		$user_data = get_post_meta($post->ID, 'profile_cct', true );

		$contexts = $this->get_contexts();

		if( is_array( $contexts ) ):

			foreach( $contexts as $context ):

				$fields = $this->get_option('form','fields',$context);

			foreach($fields as $field):
				// add_meta_box( $id, $title, $callback, $page, $context, $priority, $callback_args );
				if(function_exists('profile_cct_'.$field['type'].'_field_shell')):
					add_meta_box(
						$field['type']."-".$i.'-'.rand(0,999),
						$field['label'],
						'profile_cct_'.$field['type'].'_field_shell',
						'profile_cct', $context, 'high',
						array(
							'options'=>$field,
							'data'=>$user_data[ $field['type']]
						)
					);
				else:
					do_action("profile_cct_".$field['type']."_add_meta_box", $field, $context, $user_data[ $field['type']], $i);
				endif;
			endforeach;
		endforeach;
		endif;
		
		// var_dump('removing the author div');
		remove_meta_box('authordiv', 'post', 'normal');
		remove_meta_box('revisionsdiv', 'post', 'normal');
		
		if ( post_type_supports($post_type, 'revisions') && 0 < $post_ID && wp_get_post_revisions( $post_ID ) )
			add_meta_box('revisionsdiv', __('Revisions'), 'post_revisions_meta_box', null, 'side', 'low');
		
		if ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) )
			add_meta_box('authordiv', __('Author'), array($this,'post_author_meta_box'), null, 'side', 'low');
		
	}
	function post_author_meta_box($post) {
	global $user_ID;
?>
Make sure that select who this use is suppoed to be.
<label class="screen-reader-text" for="post_author_override"><?php _e('Author'); ?></label>
<?php
	wp_dropdown_users( array(
		'who' => null,
		'name' => 'post_author_override',
		'selected' => empty($post->ID) ? $user_ID : $post->post_author,
		'include_selected' => true
	) );
	}
	/**
	 * edit_form_advanced function.
	 *
	 * @access public
	 * @return void
	 */
	function edit_form_advanced() {
		global $post;

		if($post->post_type == "profile_cct"):
			$tabs = $this->get_option('form','tabs');
		// here we need to find if there are even any fields in the tabs
?>
			<div id="tabs">
				<ul>
					<?php
		$count = 1;
		foreach( $tabs as $tab) : ?>
						<li><a href="#tabs-<?php echo $count; ?>" class="tab-link"><?php echo $tab; ?></a></li>
					<?php
		$count++;
		endforeach; ?>
				</ul>
				<?php
		$count = 1;
		foreach(  $tabs  as $tab) :
?>
					<div id="tabs-<?php echo $count?>">
						<?php do_meta_boxes('profile_cct', 'tabbed-'.$count, $post);  ?>
					</div>
					<?php
		$count++;
		endforeach; ?>
			</div>
			<?php

		endif;
	}
	/**
	 * save_post_data function.
	 *
	 * @access public
	 * @param mixed $data
	 * @param mixed $postarr
	 * @return void
	 */
	function save_post_data( $data, $postarr ) {
		global $post;

		if(!isset( $_POST["profile_cct"] ))
			return $data;

		$profile_cct_data_previous =  get_post_meta($postarr['ID'], 'profile_cct', true);

		if(!is_array($profile_cct_data_previous))
			$profile_cct_data_previous = array();

		$profile_cct_data = (is_array($_POST["profile_cct"]) ?
			array_merge( $profile_cct_data_previous, $_POST["profile_cct"] ):
			$profile_cct_data_previous );


		// save the name of the person as the title
		if( is_array( $profile_cct_data["name"]) || !empty($profile_cct_data["name"])):
			$data['post_title'] = $profile_cct_data["name"]['first']." ".$profile_cct_data["name"]['last'];
		$data['post_name'] = sanitize_title($profile_cct_data["name"]['first']." ".$profile_cct_data["name"]['last']);
		else:
			$userdata = get_userdata($data['post_author']);
		$data['post_title'] = $userdata->user_nicename;
		$data['post_name'] = sanitize_title($userdata->user_nicename);
		endif;


		if( is_array( $profile_cct_data["name"]) || !empty($profile_cct_data["name"])):
			$data['post_title'] = $profile_cct_data["name"]['first']." ".$profile_cct_data["name"]['last'];
		else:
			$userdata = get_userdata($data['post_author']);
		$data['post_title'] = $userdata->user_nicename;
		endif;

		ob_start();
		do_action('profile_cct_page','display', $profile_cct_data, 'page');
		$content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action('profile_cct_page','display', $profile_cct_data, 'list');
		$excerpt = ob_get_contents();
		ob_end_clean();

		$data['post_excerpt'] = $excerpt;
		$data['post_content'] = $content;

		if(is_array($_POST["profile_cct"]))
			update_post_meta($postarr['ID'], 'profile_cct', $profile_cct_data);


		return $data;

	}
	/* ============== FIELDS =============================================== */


	/**
	 * profile_cct_form_field_shell function.
	 *
	 * @access public
	 * @param mixed $action
	 * @return void
	 */
	function profile_cct_form_field_shell( $action ) {

		// the default contexts normal, side, and tabs
		$contexts = $this->default_shells();

		foreach($contexts as $context):
			if(function_exists('profile_cct_form_shell_'.$context)):
				call_user_func('profile_cct_form_shell_'.$context,$action,$user_data);
			else:

?>
		 		<div id="<?php echo $context; ?>-shell">
		 			<span class="description-shell"><?php echo $context; ?></span>
		 			<ul class="form-builder sort" id="<?php echo $context; ?>">
		 		<?php
		$fields = $this->get_option('form','fields',$context);

		if( is_array( $fields  ) ):
			foreach($fields  as $field):
				if( function_exists('profile_cct_'.$field['type'].'_field_shell') ):
					call_user_func('profile_cct_'.$field['type'].'_field_shell',$action,$field);
				else:
					do_action( 'profile_cct_field_shell_'.$field['type'], $action, $field, $user_data[ $field['type'] ] );
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
	 * profile_cct_page_field_shell function.
	 *
	 * @access public
	 * @param mixed $action
	 * @param mixed $user_data
	 * @param mixed $where
	 * @return void
	 */
	function profile_cct_page_field_shell( $action, $user_data, $where ) {
		$this->action = $action;
		$contexts = $this->default_shells($where); ?><div id="page-shell"><?php
		foreach($contexts as $context):

			if(function_exists('profile_cct_page_shell_'.$context)):
				call_user_func('profile_cct_page_shell_'.$context,$action,$user_data);
			else:

				?><div id="<?php echo $context; ?>-shell" class="shell"><?php
			if($action == 'edit'): ?>
		 			<span class="description-shell"><?php echo $context; ?></span>
		 			<ul class="form-builder sort" id="<?php echo $context; ?>">
		 			<?php endif;

		$fields = $this->get_option($where,'fields',$context);

		if( is_array( $fields  ) ):
			foreach($fields  as $field):
				if( function_exists('profile_cct_'.$field['type'].'_display_shell') ):
					call_user_func('profile_cct_'.$field['type'].'_display_shell',$action,$field,$user_data[ $field['type']]);
				else:
					do_action( 'profile_cct_display_shell_'.$field['type'], $action, $field, $user_data[ $field['type'] ] );
				endif;
			endforeach;
		endif;

		if($action == 'edit'): ?>
		 			</ul>
		 			<?php endif; ?></div><?php

		endif;
		endforeach;

		?></div> <!-- end of page shell --><?php
	}


	/**
	 * start_field function.
	 *
	 * @access public
	 * @param mixed $action
	 * @param mixed $options
	 * @return void
	 */
	function start_field( $action, $options ) {
		extract( $options );
		// be default show the remove button
		if( !isset($show_remove))
			$show_remove = true;

		if( !isset($class) )
			$class= "";

		$shell = 'div';
		if($action == 'edit')
			$shell = 'li';

		if($action == 'edit'):

?>
	 		<<?php echo $shell; ?> class="<?php echo esc_attr( $type ); ?> field-item <?php echo $class." ".$width; ?>" for="cct-<?php echo esc_attr( $type); ?>" data-options="<?php echo esc_attr( $this->serialize($options)); ?>" >

			<a href="#edit-field" class="edit">Edit</a>
			<div class="edit-shell" style="display:none;">
					<input type="hidden" name="type" value="<?php echo esc_attr( $type ); ?>" />
				<?php

		if(empty($hide_label) && !$hide_label):
			$this->input_field( array('size'=>30, 'value'=>$label, 'class'=>'field-label', 'name'=>'label','label'=>'label', 'type'=>'text', 'before_label'=>true ));
		else:
			?>	<input type="hidden" name="label" value="<?php echo esc_attr( $label ); ?>" /> <?php
		endif;


		if(isset($description))
			$this->input_field( array('size'=>10, 'value'=>$description, 'class'=>'field-description','name'=>'description','label'=>'description','type'=>'textarea' , 'before_label'=>true));

		if(isset($width))
			$this->input_field(array('type'=>'select','all_fields'=>array('full','half','one-third','two-third'), 'class'=>'field-width','value'=>$width,'name'=>'width', 'label'=>'select width','before_label'=>true));

		if(isset($text))
			$this->input_field( array('size'=>30, 'value'=>$text, 'class'=>'field-text','name'=>'text','label'=>'text input','type'=>'text' , 'before_label'=>true));

		if(isset($before))
			$this->input_field( array('size'=>10, 'value'=>$before, 'class'=>'field-textarea','name'=>'before','label'=>'before html','type'=>'textarea' , 'before_label'=>true));

		if(isset($after))
			$this->input_field( array('size'=>10, 'value'=>$after, 'class'=>'field-textarea','name'=>'after','label'=>'after html','type'=>'textarea' , 'before_label'=>true));

		if(isset($empty))
			$this->input_field( array('size'=>10, 'value'=>$empty, 'class'=>'field-textarea','name'=>'empty','label'=>'content to be displayed on empty','type'=>'textarea' , 'before_label'=>true));

		if(isset($show_fields))
			$this->input_field(array('type'=>'multiple','all_fields'=>$show_fields, 'class'=>'field-show','selected_fields'=>$show,'name'=>'show', 'label'=>'show / hide input area','before_label'=>true));

		if(isset($show_multiple) && $show_multiple)
			$this->input_field(array('type'=>'checkbox','name'=>'multiple', 'class'=>'field-multiple', 'field'=>'yes, allow the user to create multiple fields', 'value'=>$multiple,'label'=>'multiple','before_label'=>true));

		if(isset($show_link_to))
			$this->input_field(array('type'=>'checkbox','name'=>'link_to', 'class'=>'field-multiple', 'field'=>'wrap the field with a link to the profile page', 'value'=>$link_to,'label'=>'link to profile','before_label'=>true));
?>
				<input type="button" value="Save" class="button save-field-settings" />
				<span class="spinner" style="display:none;"><img src="<?php echo admin_url(); ?>/images/wpspin_light.gif" alt="spinner" /> saving...</span>
			</div>
		 	<label for="" id="" class="field-title"><?php echo $label; ?></label>
		 	<?php
		endif;
		if($action == 'display'):
			echo $before;
		?><<?php echo $shell; ?> class="<?php echo esc_attr( $type ); ?> field-item <?php echo $class." ".$width; ?>">
	 	<?php
		endif;
?>
	 	<div class="field-shell">
	 	<?php
		if( isset($show_multiple) && $show_multiple ): ?>

	 	<?php
			endif;
	}
	/**
	 * end_field function.
	 *
	 * @access public
	 * @param mixed $action
	 * @param mixed $options
	 * @return void
	 */
	function end_field( $action, $options ) {
		$shell = 'div';
		if($action == 'edit')
			$shell = 'li';
		extract( $options );

		if( isset($show_multiple) && $show_multiple ):

			$style_multiple = ( isset($multiple) && $multiple ? 'style="display: inline;"': 'style="display: none;"');
?>
	 	<a href="#add" <?php echo $style_multiple; ?> class="button add-multiple">Add another</a>
	 	<?php
		endif;
?>
	 	</div><?php
		if(isset($description)):
			?><pre class="description"><?php echo $description; ?></pre><?php
		endif;
		?></<?php echo $shell; ?>><?php
		if($action == "display")
			echo $after;
	}
	/**
	 * input_field function.
	 *
	 * @access public
	 * @param mixed $options
	 * @return void
	 */
	function input_field( $options ) {

		extract( $options );

		$before_label = ( isset($before_label) && $before_label ? true:false);
		$field_id_class = ( isset($field_id)? ' class="'.$field_id.' '.$type.'-shell"': '');


		$size = ( isset($size)? ' size="'.$size.'"': '');
		$row = ( isset($row)? ' row="'.$row.'"': '');
		$cols = ( isset($cols)? ' cols="'.$cols.'"': '');
		$class = ( isset($class)? ' class="'.$class.'"': ' class="field text"');
		$id = ( isset($id)? ' id="'.$id.'"': ' ');
		$separator = (isset($separator) ? '<span class="separator">'.$separator.'</span>': "");

		if($type =='multiple'):
			$name = ( isset($name)? ' name="'.$name.'[]"':  ' name="profile_cct['.$field_type.']['.$count.']['.$field_id.'][]"');
		$textarea_id = 'profile_cct-'.$field_type.'-'.$count.'-'.$field_id;
		$textarea_name = 'profile_cct['.$field_type.']['.$count.']['.$field_id.'][]';
		elseif($multiple):
			$name = ( isset($name)? ' name="'.$name.'[]"':  ' name="profile_cct['.$field_type.']['.$count.']['.$field_id.']"');
		$textarea_id = 'profile_cct-'.$field_type.'-'.$count.'-'.$field_id;
		$textarea_name = 'profile_cct['.$field_type.']['.$count.']['.$field_id.']';
		else:
			$name = ( isset($name)? ' name="'.$name.'"': ' name="profile_cct['.$field_type.']['.$field_id.']"');
		$textarea_id = 'profile_cct-'.$field_type.'-'.$field_id;
		$textarea_name = 'profile_cct['.$field_type.']['.$field_id.']';
		endif;
		$show = ( isset($show) && !$show ? ' style="display:none;"': '');
		switch($type) {
		case 'text':

			if ($separator)
				echo $separator;
?>
			 	<span <?php echo $field_id_class.$show; ?>>
			 		<?php if($before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
					<input type="text" <?php echo $size.$class.$name; ?> value="<?php echo esc_attr($value); ?>" id="">
					<?php if(!$before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
				</span>
				<?php
			break;

		case 'multiple':
			?><div <?php echo $field_id_class.$show;  ?>><?php
			if ($separator)
				echo $separator;

			if($before_label){ ?><label for="" ><?php echo $label; ?></label> <?php }
			// need to change the name in this case
			$selected_fields = (is_array($selected_fields) ? $selected_fields : array());

			foreach($all_fields as $field):

?>
	 					<label><input type="checkbox" <?php checked( in_array($field,$selected_fields) ); ?> value="<?php echo $field; ?>" <?php echo $class.$name; ?> /> <?php echo $field; ?></label>
	 					<?php
			endforeach;

			if(!$before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>

	 				</div>
	 				<?php
			break;
		case 'checkbox':
			if ($separator)
				echo $separator;

			?><div <?php echo $field_id_class.$show;  ?>>
	 				<?php if($before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
	 					<label><input type="checkbox" <?php checked( $value ); ?> value="1" <?php echo $class.$name; ?> /> <?php echo $field; ?></label>
	 				<?php if(!$before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
	 				</div>
	 				<?php
			break;

		case 'select':

			if ($separator)
				echo $separator;

			?><span <?php echo $field_id_class.$show;  ?>>
	 				<?php
			if($before_label){ ?><label for="" ><?php echo $label; ?></label> <?php }
?>
	 				<select <?php echo $name; ?> >
	 				<?php
			foreach($all_fields as $field): ?>
	 					<option  value="<?php echo $field; ?>" <?php selected($value,$field); ?> > <?php echo $field; ?></option>
	 					<?php
			endforeach;
?>
	 				</select>
	 				<?php
			if(!$before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>

	 				</span>
	 				<?php
			break;

		case 'textarea':
			if ($separator)
				echo $separator;
?>
	 				<span <?php echo $field_id_class; ?>>
	 				<?php if($before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>

	 				<?php
			// only dispaly the editor on the Profile edit side
			if( $this->action == 'edit' ): ?>
	 					<textarea <?php echo $size.$class.$name.$row.$cols; ?> id=""><?php echo esc_html($value); ?></textarea>
	 				<?php
			else:
				wp_editor( $value, $textarea_id, array('textarea_name'=>$textarea_name,'teeny'=>true, 'media_buttons'=>false) );
			endif;
			if(!$before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
					</span>
	 				<?php
			break;


		}

	}

	/**
	 * w function.
	 *
	 * @access public
	 * @param mixed $options
	 * @return void
	 */
	function display_text($options) {
		extract( $options );

		$hide = ( isset($show) && !$show ? ' style="display:none;"': '');
		if($this->action == 'display' && empty($value) && !in_array($type, array('end_shell','shell') ) && empty($hide) ):
			echo "";
		return true;
		endif;

		$prepend_class = $class;
		$class = ( isset($class)? ' class="'.$class.'"': ' class=""');
		$id = ( isset($id)? ' id="'.$id.'"': ' ');

		$href = ( isset($href)? ' href="'.$href.'"': ' ');


		$show = ( isset($show) && !$show ? false: true);

		$tag = (isset($tag) ? $tag :"span");

		switch( $default_text ){
		case 'lorem ipsum':

			$default_text = "<p><strong>".$field_type."</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in velit ac sem dapibus cursus. Donec faucibus adipiscing ipsum ut auctor. Integer quis metus iaculis lacus vulputate facilisis. Fusce malesuada volutpat sapien eu commodo. Integer sed magna orci, quis commodo elit. In convallis fringilla mollis. Pellentesque dapibus mi quis nunc pulvinar lobortis. Sed ut purus auctor ligula aliquam egestas eu at sem. Sed eget nisl urna. Etiam vitae leo id erat porttitor iaculis et et lorem. Curabitur condimentum libero eget sapien dictum congue. In hac habitasse platea dictumst. In in nulla et elit vehicula tempor. Donec sem arcu, viverra quis dignissim ac, adipiscing sed nunc.</p>

<p>Quisque malesuada tellus vitae massa semper non faucibus leo sollicitudin. In sit amet feugiat ligula. Ut id ultrices magna. Proin ut imperdiet tellus. Nulla interdum eleifend massa egestas malesuada. Suspendisse potenti. Nulla suscipit imperdiet velit sit amet pretium. In sit amet lectus felis, commodo varius eros. Duis sapien diam, sagittis faucibus elementum vulputate, faucibus a mi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec viverra, quam in pretium volutpat, elit sapien tempor neque, quis adipiscing magna quam vitae velit.</p>";
			break;

		case 'bruce bio':

			$default_text = "<p><strong>".$field_type."</strong> A wealthy businessman who lives in Gotham City, born to Dr. Thomas Wayne and his wife Martha, two very wealthy and charitable Gotham City socialites.</p>

<p>Known for his contributions to charity, notably through his Wayne Foundation, a charity devoted to helping the victims of crime and preventing people from becoming criminals.</p>";
			break;
		}

		$display = ( $value ? $value :$default_text);

		$separator = (isset($separator) ? '<span class="'.$prepend_class.'-separator">'.$separator.'</span>': "");


		switch($type) {
		case 'text':
			echo $separator.' <'.$tag.' '.$id.$class.$href.$hide.'>';
			echo $display;
			echo "</".$tag.">";
			break;

		case 'shell':
			if($tag == 'a'):
				if($link_to):
					echo '<'.$tag.' '.$id.$class.$href.'>';
				else:
					echo '<span '.$id.$class.'>';
				endif;
			else:
				echo '<'.$tag.' '.$id.$class.'>';
			if($link_to):
				echo '<a '.$href.'>';
			endif;
			endif;

			break;

		case 'end_shell';
			if($tag == 'a'):
				if($link_to):
					echo '</'.$tag.'>';
				else:
					echo '</span>';
				endif;
			else:
				if($link_to):
					echo '</a>';
				endif;
			echo '</'.$tag.'>';
			endif;
			break;

		}
	}

	/**
	 * add_field function.
	 * function return by ajax to be displayed
	 * @access public
	 * @return void
	 */
	function update_fields() {
		$context = $_POST['context'];

		if(in_array($_POST['type'], array('form','page','list')))
			$type = $_POST['type'];
		else
			$type = 'form';

		if(in_array($_POST['width'], array('full','half','one-third','two-third')))
			$width = $_POST['width'];
		else
			$width = 'full';

		$options = $this->get_option($type,'fields',$context);

		switch( $_POST['method'] ){

		case "update":
			if(is_numeric($_POST['field_index'])):
				switch($type){
				case "form":
					$options[$_POST['field_index']]['label']   = $_POST['label'];
					$options[$_POST['field_index']]['description']  = $_POST['description'];
					$options[$_POST['field_index']]['show']   = $_POST['show'];
					$options[$_POST['field_index']]['multiple']  = ( isset($_POST['multiple']) &&  $_POST['multiple'] ? $_POST['multiple'] : 0);
					break;
				case "page":
				case "list":
					$options[$_POST['field_index']]['width']   = $width;
					$options[$_POST['field_index']]['before']   = $_POST['before'];
					$options[$_POST['field_index']]['after']   = $_POST['after'];
					$options[$_POST['field_index']]['show']   = $_POST['show'];
					$options[$_POST['field_index']]['link_to']   = $_POST['link_to'];
					$options[$_POST['field_index']]['clear']   = $_POST['clear'];
					$options[$_POST['field_index']]['text']   = $_POST['text'];
					$options[$_POST['field_index']]['empty']   = $_POST['empty'];
					$options[$_POST['field_index']]['seperator']  = $_POST['seperator'];
					break;
				}
			echo "updated";
			endif;
			break;

		case "sort":

			if(!empty($_POST['data'])):
				unset($options);
			foreach($_POST['data'] as $data):
				$options[] = wp_parse_args($data);
			endforeach;
			else:
				$options = array();
			endif;
			echo "sorted";
			break;
		}

		// save the opions
		$this->update_option($type,'fields',$context,$options);

		die();

	}
	/**
	 * field_field_tab_index function.
	 *
	 * @access public
	 * @return void
	 */
	public function field_field_tab_index() {
		$this->tab_index++;
		echo $this->tab_index;
	}



	/* ============== TABS =============================================== */

	/**
	 * update_tabs function.
	 *
	 * @access public
	 * @return void
	 */
	function update_tabs() {

		if(in_array($_POST['type'], array('page','form')))
			$type = $_POST['type'];
		else
			$type = 'form';

		$tabs = $this->get_option($type,'tabs');

		switch($_POST['method']) {

		case "update":
			$tabs[$_POST['index']] = $_POST['title'];
			echo "updated";
			break;

		case "remove":

			// we need to set the proper item to fields to zero as well.
			// and move them to the bench
			$index = $_POST['index'];

			$tabs_count = count($tabs);

			unset( $tabs[ $index ] );


			$count = $index+1;
			$fields = $this->get_option($type,'fields','tabbed-'.$count);
			$this->delete_option($type,'fields','tabbed-'.$count);


			if(is_array($fields)): // array was empty so nothing to move
				$bench  = $this->get_option($type,'fields','bench');
			// and move them to the bench
			$bench  = array_merge($bench , $fields);

			$bench = $this->update_option($type,'fields','bench', $bench);

			endif;



			while($count < $tabs_count):
				$count++;
			$fields = $this->get_option($type,'fields','tabbed-'.$count);

			$minus = $count - 1;

			$this->update_option($type,'fields','tabbed-'.$minus, $fields);

			$fields = $this->delete_option($type,'fields','tabbed-'.$count);

			endwhile;

			echo "removed";
			break;

		case "add":
			$tabs[] = $_POST['title'];
			echo "added";
			break;
		}

		$this->update_option($type,'tabs','normal', $tabs);
		die();
	}

	/* ============== Validation =============================================== */
	/**
	 * validate_form_fields function.
	 *
	 * @access public
	 * @param mixed $input
	 * @return void
	 */
	function validate_form_fields( $input ) {
		// last check before saving to the db
		return $input;
	}

	/**
	 * validate_form_fields function.
	 *
	 * @access public
	 * @param mixed $input
	 * @return void
	 */
	function validate_page_fields( $input ) {
		// last check before saving to the db
		return $input;
	}

	/**
	 * stripslashes_deep function.
	 * utility function
	 * @access public
	 * @param mixed $value
	 * @return void
	 */
	function stripslashes_deep( $value ) {
		$value = is_array($value) ?
			array_map('stripslashes_deep', $value) :
			stripslashes($value);
		return $value;
	}

	/**
	 * is_data_array function.
	 *
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	function is_data_array( $data ) {

		if(!is_array($data) || !is_array($data[0]))
			return false;

		return true;
	}

	function is_array_empty($data, $excepton = array()){
		// assume that the array is empty until proven otherwise.
		$data_empty = true;

		// this is a multi
		if( is_array($data[0]) ):
			foreach($data as $items):
				foreach($items as $item => $value):
					if( !empty($value) &&  !in_array($item,  $excepton) )  // prove me wrong -> the value is there and the item is not a country field
						$data_empty = false;

					endforeach;
				endforeach;
			elseif( is_array($data) ):
				foreach($data as $item => $value):
					if( !empty($value) &&  !in_array($item,  $excepton) )  // prove me wrong -> the value is there and the item is not a country field
						$data_empty = false;

					endforeach;
				endif;


			return $data_empty;


	}
	/**
	 * serialize function.
	 *
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	function serialize( $data ) {

		foreach($data as $key => $value):
			if( in_array($key,array("show_fields","show_multiple")))
				continue;
			if(is_array($value)):
				foreach($value as $value_data):
					$str[] = urlencode($key."[]")."=".urlencode($value_data);
				endforeach;

			else:
				$str[] = urlencode($key)."=".urlencode($value);
			endif;
		endforeach;

		return implode("&",$str);
	}

	/**
	 * default_shells function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @return void
	 */
	function default_shells( $type = 'form' ) {
		switch( $type ){
		case 'form':
			return array( 'normal','side','tabs');
			break;

		case 'page':
			return array( 'header', 'tabs', 'bottom');
			break;

		case 'list':
			return array( 'normal');
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
	function get_contexts( $type = 'form' ) {

		$contexts = $this->default_shells( $type );
		$id = array_search('tabs',$contexts);

		if( is_numeric($id) ):
			$tabs = $this->get_option($type,'tabs');

		if(is_array($tabs)):
			$count = 1;
		foreach($tabs as $tab):
			$contexts[] = "tabbed-".$count;
		$count++;
		endforeach;
		endif;

		unset( $contexts[$id] );
		$contexts = array_values($contexts);
		endif;
		return $contexts;
	}
	/**
	 * get_option function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @param string $fields_or_tabs. (default: 'fields')
	 * @param string $context. (default: 'normal')
	 * @return void
	 */
	function get_option($type='form',$fields_or_tabs='fields',$context='normal'){
		// return the options from the array stored
		if(is_array($this->option[$type][$fields_or_tabs][$context])):
			return $this->option[$type][$fields_or_tabs][$context];
		else:
			// get the
			$option = get_option('Profile_CCT_'.$type.'_'.$fields_or_tabs.'_'.$context);

		if(!is_array($option)):
			$default = $this->default_options($type);

		if($fields_or_tabs == 'fields')
			$option = $default[$fields_or_tabs][$context];
		else
			$option = $default[$fields_or_tabs];
		endif;
		endif;

		$this->option[$type][$fields_or_tabs][$context] = $option;
		return $option;

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
	function update_option($type='form',$fields_or_tabs='fields',$context='normal',$update) {
		$settings = get_option('Profile_CCT_settings');
		if(!is_array($settings))
			$settings = array();

		$settings[$type.'_updated'] = time();

		$this->option[$type][$fields_or_tabs][$context] = $update;
		// update the settings
		update_option( 'Profile_CCT_settings', $settings );

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
	function delete_option($type='form',$fields_or_tabs='fields',$context='normal') {
		unset( $this->option[$type][$fields_or_tabs][$context] );
		return delete_option( 'Profile_CCT_'.$type.'_'.$fields_or_tabs.'_'.$context );
	}
	/**
	 * default_options function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @return void
	 */
	function default_options($type = 'form') {
		switch($type) {
		case 'form':
			return apply_filters( 'profile_cct_default_options', array(
					'fields'=> array(
						'tabbed-1' => array(
							array( "type"=> "address",   "label"=> "address",),
							array( "type"=> "phone",  "label"=> "phone" ),
							array( "type"=> "email",  "label"=> "email" ),
							array( "type"=> "website",  "label"=> "website"),
							array( "type"=> "social",  "label"=> "social")
						),
						'tabbed-2' =>array(
							array( "type"=> "position" , "label"=> "position" ),
							array( "type"=> "bio",   "label"=> "bio" )

						),
						'normal'=> array(
							array("type"=> "name" , "label"=> "name" )
						),
						'side'=> array(
							array( "type"=>"picture", "label"=>"picture" )
						),
						'bench' =>array(

							array( "type"=> "department",    "label"=> "department"),
							array( "type"=> "courses",     "label"=> "courses" ),
							array( "type"=> "officehours",    "label"=> "office hours" ),
							array( "type"=> "education",     "label"=> "education" ),
							array( "type"=> "awards",     "label"=> "awards" ),
							array( "type"=> "specialization",   "label"=> "specialization" ),
							array( "type"=> "teaching",     "label"=> "teaching" ),
							array( "type"=> "publications",    "label"=> "publications" ),
							array( "type"=> "research",     "label"=> "research" ),
							array( "type"=> "projects",   "label"=> "projects" ),
							array( "type"=> "unitassociations",   "label"=> "unit associations"),
							array( "type"=> "professionalaffiliations", "label"=> "professional affiliations"),
							array( "type"=> "graduatestudent",   "label"=> "graduate student" )

						)),
					'tabs' => array("Basic Info", "Bio")
				), $type );
			break;

		case 'page':
			return apply_filters( 'profile_cct_default_options', array(
					'fields'=> array(
						'tabbed-1' => array(
							array( "type"=> "address",  "label"=> "address",),
							array( "type"=> "phone", "label"=> "phone" ),
							array( "type"=> "email", "label"=> "email" ),
							array( "type"=> "website", "label"=> "website"),
							array( "type"=> "social", "label"=> "social")
						),
						'tabbed-2' =>array(
							array( "type"=> "position" , "label"=> "position" ),
							array( "type"=> "bio",   "label"=> "biography" )

						),
						'header'=> array(
							array( "type"=>"picture", "label"=>"picture" ),
							array("type"=> "name" ,  "label"=> "name" )
						),
						'bottom'=> array(

						),
						'bench' =>array(
							array( "type"=> "department",    "label"=> "department"),
							array( "type"=> "education",     "label"=> "education" ),
							array( "type"=> "awards",     "label"=> "awards" ),
							array( "type"=> "specialization",   "label"=> "specialization" ),
							array( "type"=> "projects",   "label"=> "projects" ),
							array( "type"=> "graduatestudent",   "label"=> "graduate student" ),
							array( "type"=> "permalink",     "label"=> "permalink"),
							array( "type"=> "unitassociations",   "label"=> "unit associations"),
							array( "type"=> "professionalaffiliations", "label"=> "professional affiliations"),
							array( "type"=> "courses",     "label"=> "courses" ),
							array( "type"=> "officehours",    "label"=> "office hours" )

						)),
					'tabs' => array("Basic Info", "Bio")
				) , $type );
			break;

		case 'list':
			return apply_filters( 'profile_cct_default_options', array(
					'fields'=> array(
						'normal'=> array(
							array( "type"=>"picture",  "label"=>"picture" ),
							array( "type"=> "name" , "label"=> "name" ),
							array( "type"=> "phone", "label"=> "phone" ),
							array( "type"=> "email", "label"=> "email" )
						),
						'bench' =>array(
							array( "type"=> "address",    "label"=> "address"),
							array( "type"=> "website",   "label"=> "website"),
							array( "type"=> "social",   "label"=> "social"),
							array( "type"=> "position" ,  "label"=> "position" ),
							array( "type"=> "department",  "label"=> "department" ),
							array( "type"=> "courses",   "label"=> "courses" ),
							array( "type"=> "officehours",  "label"=> "office hours" ),
							array( "type"=> "education",   "label"=> "education" ),
							array( "type"=> "awards",   "label"=> "awards" ),
							array( "type"=> "specialization", "label"=> "specialization" ),
							array( "type"=> "projects", "label"=> "projects" ),
							array( "type"=> "graduatestudent", "label"=> "graduate student" ),
							array( "type"=> "permalink",   "label"=> "permalink"),
							array( "type"=> "unitassociations", "label"=> "unit associations"),
							array( "type"=> "professionalaffiliations", "label"=> "professional affiliations")
						)
					)
				) , $type );
			break;


		}
	}

	function fields_to_clone() {
		return apply_filters( 'profile_cct_fields_to_clone', array(
				array( "type"=> "phone" ),
				array( "type"=> "email" ),
				array( "type"=> "address" ),
				array( "type"=> "website" ),
				array( "type"=> "position" ),
				array( "type"=> "education" ),
				array( "type"=> "textarea"  ),
				array( "type"=> "text" ),
				array( "type"=> "specialization" )
			));
	}
} // end class

if ( function_exists( 'add_action' ) && class_exists( 'Profile_CCT' ) )
	add_action( 'plugins_loaded', array( 'Profile_CCT', 'get_object' ) );
