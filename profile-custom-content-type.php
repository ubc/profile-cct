<?php
/**
 * Plugin Name: Profile Custom Content Type
 * Plugin URI:
 * Text Domain: profile_cct
 * Domain Path: /languages
 * Description: Allows administrators to manage user profiles better in order to display them on their websites
 * Author: Enej Bajgoric, Eric Jackish, Aleksandar Arsovski, CTLT, UBC
 * Version: 1.1 
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

define('PROFILE_CCT_DIR', plugin_dir_path(__FILE__));



require(PROFILE_CCT_DIR.'profile-taxonomies.php');
require(PROFILE_CCT_DIR.'profile-manage-table.php');
if(!class_exists('Profile_CCT')):
class Profile_CCT {
	static private $classobj = NULL;

	static public  $textdomain  = NULL;
	static public  $action   = NULL;
	static public  $settings_options = NULL;
	static public  $form_fields = NULL;
	static public  $taxonomies = NULL;
	static public  $form_field_options = NULL;
	static public  $option     = NULL; 
	static public  $current_form_fields = NULL; // stores the current state of the form field... the labels and if it is on the banch... 

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
		
		add_action('the_post', array( $this,'reset_filters' ), 10, 1);

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
					require($dir.$file);
				endwhile;

			closedir($handle);
		endif;
		add_action('profile_cct_before_page', array( $this,'recount_field'),10,1);
		add_action('profile_cct_before_page', array( $this,'display_fields_check'),11,1);
		// function to be executed on form admin page
		add_action('profile_cct_form', array( $this,'form_field_shell'),10,1);
		
		// function to be executed on page and list admin pages
		add_action('profile_cct_page', array( $this,'page_field_shell'),10,3);
		
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
    		
    		 if( !( current_user_can('edit_profile_cct') && (int)$post->post_author != $current_user->ID ) && !current_user_can('edit_others_profile_cct') ):
    			$wp_admin_bar->remove_menu('edit');
    		endif;
    	endif;
    	
    	if(current_user_can('edit_profile_cct')) :
    	
	    	$wp_admin_bar->remove_menu('logout');
	    	
	
	    	$wp_admin_bar->add_menu( array(
				'parent' => 'user-actions',
				'id'     => 'edit-public-profile',
				'title'  => __( 'Edit Public Profile' ),
				'href' => admin_url('users.php?page=public_profile'),
				));
			
			// this shouldn't be messing with the logout 
			$wp_admin_bar->add_menu( array(
				'parent' => 'user-actions',
				'id'     => 'logout',
				'title'  => __( 'Log Out' ),
				'href'   => wp_logout_url(),
				) );
			
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
		return $this->get_plugin_data( 'TextDomain' );
	}
	public function version() {
		return $this->get_plugin_data( 'Version' );
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
		if(!is_admin()):
			return;
		endif;
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
	
	function e($data){
		echo "<pre>";
		var_dump($data);
		echo "</pre>";

	}
	
	function microtime_float() {
    	list($usec, $sec) = explode(" ", microtime());
    	return ((float)$usec + (float)$sec);
	}
	/**
	 * add_menu_page function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_menu_page() {
		
		$public_profile = add_submenu_page(
			'users.php',
			__( 'Public Profile', $this -> get_textdomain() ),
			__( 'Public Profile', $this -> get_textdomain() ),
			'edit_profile_cct', 'public_profile',
			array( $this, 'public_profile' ) );
		
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
		wp_die('redirect didn\'t work');
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
					'page' => 'form'
				));
			break;
		case "page":
			wp_enqueue_script( 'profile-cct-tabs', WP_PLUGIN_URL . '/profile-cct/js/tabs.js',array('jquery','jquery-ui-tabs') );
			wp_enqueue_script( 'profile-cct-form', WP_PLUGIN_URL . '/profile-cct/js/form.js',array('jquery','jquery-ui-sortable') );
			wp_enqueue_script( 'profile-cct-profile', WP_PLUGIN_URL . '/profile-cct/js/profile.js',array('jquery') );
			wp_localize_script( 'profile-cct-form', 'ProfileCCT', array(
					'page' => 'page'
				));
			break;
		case "list":
			wp_enqueue_script( 'profile-cct-form', WP_PLUGIN_URL . '/profile-cct/js/form.js',array('jquery','jquery-ui-sortable') );
			wp_enqueue_script( 'profile-cct-profile', WP_PLUGIN_URL . '/profile-cct/js/profile.js',array('jquery') );
			wp_localize_script( 'profile-cct-form', 'ProfileCCT', array(
					'page' => 'list'
				));
			break;
			
		default:
			// wp_enqueue_script( 'profile-cct-settings', WP_PLUGIN_URL . '/profile-cct/js/settings.js' );
			break;

		}
		
	}
	/**
	 * admin_pages function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_pages() {
		$time_start = $this->microtime_float();
		require(PROFILE_CCT_DIR.'class/admin_pages.php');
		
		$time_end = $this->microtime_float();
		$time = $time_end - $time_start;

		echo "<!-- time to render  $time seconds -->\n";
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
	 * reset_filters function.
	 * 
	 * @access public
	 * @param mixed $post
	 * @return void
	 */
	function reset_filters($post) {
				
		if( $post->post_type == 'profile_cct')
			remove_filter( 'the_content', 'wpautop' );
		else
			add_filter( 'the_content', 'wpautop'); // I hope this doesn't get added twice
	}

	/**
	 * register_cpt_profile_cct function.
	 *
	 * @access public
	 * @return void
	 */
	function register_cpt_profile_cct() {
	
		
		if( empty($this->settings_options['slug']) ) {
			$slug = 'person';
		
		} else {
			$slug = $this->settings_options['slug'];
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
			'menu_icon' => plugins_url( 'icon.png' , __FILE__ ),
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
		global $post, $post_new_file, $pagenow, $current_user, $post_type;
		
		$post_new_file = '#';
		
		if( (int)$post->post_author != $current_user->ID && !current_user_can('edit_others_profile_cct') ):
			wp_die('You are not allow to edit this profile');
		endif;
		
		
		$this->form_fields = get_option('Profile_CCT_form_fields');

		$user_data = get_post_meta($post->ID, 'profile_cct', true );

		$contexts = $this->get_contexts();
		
		
		remove_meta_box( 'submitdiv', 'profile_cct', 'side' );
	
		// remove_meta_box('submitdiv','post', 'normal'); // publish box
		// make sure that the publish box is the stays on the top
		// add_meta_box('submitdiv', __('Publish'), 'post_submit_meta_box', null, 'side', 'high');

		if( is_array( $contexts ) ):

			foreach( $contexts as $context ):

				$fields = $this->get_option('form','fields',$context);
				if($fields):
					foreach($fields as $field):
						
						// add_meta_box( $id, $title, $callback, $page, $context, $priority, $callback_args );
						if(function_exists('profile_cct_'.$field['type'].'_field_shell')):
							
							add_meta_box(
								$field['type']."-".$i.'-'.rand(0,999),
								$field['label'],
								'profile_cct_'.$field['type'].'_field_shell',
								'profile_cct', $context, 'core',
								array(
									'options'=>$field,
									'data'=>$user_data[ $field['type']]
								)
							);
						else:
							do_action("profile_cct_".$field['type']."_add_meta_box", $field, $context, $user_data[ $field['type']], $i);
						endif;
					endforeach;
				endif;
			endforeach;
		endif;
		
		
		remove_meta_box('authordiv', 'post', 'normal');
		remove_meta_box('revisionsdiv', 'post', 'normal');
		
		
		if (  0 < $post->ID && wp_get_post_revisions( $post->ID ) )
			add_meta_box('revisionsdiv', __('Revisions'), 'post_revisions_meta_box', null, 'side', 'low');
		
		if ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) )
			add_meta_box('authordiv', __('Author'), array($this,'post_author_meta_box'), null, 'side', 'low');
		
		add_meta_box('submitdiv', __('Publish'), 'post_submit_meta_box', null, 'side', 'high');
		
	}
	function post_author_meta_box($post) {
	global $user_ID;
?>

Make sure that you select who this is supposed to be.<br />

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
		global $post, $wp_filter;

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
		
		//Ensure there is no slug conflict
		$data['post_name'] = wp_unique_post_slug($data['post_name'], $postarr['ID'], 'publish', 'profile_cct', 0);

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
	 * form_field_shell function.
	 *
	 * @access public
	 * @param mixed $action
	 * @return void
	 */
	function form_field_shell( $action ) {

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
	 * page_field_shell function.
	 *
	 * @access public
	 * @param mixed $action
	 * @param mixed $user_data
	 * @param mixed $where
	 * @return void
	 */
	function page_field_shell( $action, $user_data, $where ) {
		$this->action = $action;
		$contexts = $this->default_shells($where); 
			if($action == 'edit'):
				?><div id="page-shell"><?php
			endif;
		foreach($contexts as $context):
			
			// this is being called for tabs 
			if(function_exists('profile_cct_page_shell_'.$context)):
				call_user_func('profile_cct_page_shell_'.$context,$action,$user_data);
			else:

				?><div id="<?php echo $context; ?>-shell" class="profile-cct-shell"><?php
				
				if($action == 'edit'): ?>
		 			<span class="description-shell"><?php echo $context; ?></span>
		 			<ul class="form-builder sort" id="<?php echo $context; ?>"><?php 
		 		endif;

				$fields = $this->get_option($where,'fields',$context) ;//+ $this->get_option('form','fields',$context);

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
		 <?php endif; 
		 ?></div><?php

		endif;
		endforeach;
		if($action == 'edit'):
				?></div><?php
		endif;

	}
	/**
	 * display_fields_check function.
	 * helps us determin what info is already set in the form on what isn't
	 * @access public
	 * @param mixed $where
	 * @return void
	 */
	function display_fields_check($where){
	
		if( !in_array( $where, array('page','list') ) )
			return true;
		
		$contexts = $this->get_contexts('form');
		
		// CURRENT FIELDS
		// all the fields that are there 
		$current_fields = array();
		foreach($contexts as $context):
			foreach($this->get_option('form','fields',$context) as $field):
			
				$field['is_active'] = true;
				
				$this->current_form_fields[$field['type']] = $field;
			endforeach;
		endforeach;
		
		// don't forget the banch field
		foreach($this->get_option('form','fields','bench') as $field):
			$field['is_active'] = false;
			$this->current_form_fields[$field['type']] = $field;
		endforeach;
		
		//$this->e($this->current_form_fields);
		return true;
	}
	/**
	 * recount_field function.
	 * 
	 * @access public
	 * @param mixed $action
	 * @param mixed $user_data
	 * @param mixed $where
	 * @return void
	 */
	function recount_field( $where ) {
	
	
		if( !in_array( $where, array('form','page','list') ) )
			return true;
	
		// lets see what all the fields are that are suppoed to be there.
		$contexts = $this->get_contexts($where);
		
		// CURRENT FIELDS
		// all the fields that are there 
		$current_fields = array();
		foreach($contexts as $context):
			
			
			foreach( (array)$this->get_option($where,'fields',$context) as $field):
				
				$current_fields[] = $field['type'];
			endforeach;
		endforeach;
		
		// don't forget the banch field
		foreach($this->get_option($where,'fields','bench') as $field):
			$current_fields[] = $field['type'];
		endforeach;
		
		
		
		// DYNAMIC FIELDS
		// all the fields that get included 
		// - taxonomy fields 
		// - db fields (added via the add field function)
		// all the once that are 
		$dynamic_fields = apply_filters("profile_cct_dynamic_fields", array(), $where );
		$all_dynamic_fields = array(); 
		$real_fields = array(); // array of all the default fields containing the field array with the key field['type']
		
		if(is_array($dynamic_fields)):
			foreach($dynamic_fields as $field):
				$all_dynamic_fields[] 		 = $field['type'];
				$real_fields[$field['type']] = $field;
				
				if( !in_array($field['type'], $current_fields) ): // add to the current_fields array
					$current_fields[] = $field['type'];
					$this->option[$where]['fields']['bench'][] = $field;
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
		$default_options =  $this->default_options($where);
		
		foreach($default_options['fields'] as $context =>$fields):
			foreach($fields as $field):
				$default_fields[] 	= $field['type'];
				$real_fields[$field['type']] = $field;
			endforeach;
			unset($field);
		endforeach;
		
		// also don't forget fields that are fields that were added later
		$new_fields = $this->default_options('new_fields');
		foreach($new_fields as $version):
			foreach($version as $field):
			
				if( in_array($where, $field['where']) ): // only add it if it supports the the current where state
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
		if( !empty( $different) ):
			
			// add the fields back to the banch the array... 
			foreach($different as $field)
			$this->option[$where]['fields']['bench'][] = $real_fields[$field];
			
		endif;
		
	return true;
		
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
		
		// check to see what the name of the form is
		$is_in_form = ( (isset($this->current_form_fields) && $this->current_form_fields[$type]['is_active']) ? "is-active" : "");
		
		// the label should always be the same as what it was set in the form
		
		$label 		= ( (isset($this->current_form_fields) && !empty($this->current_form_fields[$type]['label'])) ? $this->current_form_fields[$type]['label'] : $label);		
?>
	 		<<?php echo $shell; ?> class="<?php echo $is_in_form.' shell-'.esc_attr( $type ); ?> field-item <?php echo $class." ".$width; ?>" for="cct-<?php echo esc_attr( $type ); ?>" data-options="<?php echo esc_attr( $this->serialize($options)); ?>" >

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
			$this->input_field( array('size'=>10, 'value'=>stripslashes($description), 'class'=>'field-description','name'=>'description','label'=>'description','type'=>'textarea' , 'before_label'=>true));

		if(isset($width))
			$this->input_field(array('type'=>'select','all_fields'=>array('full','half','one-third','two-third'), 'class'=>'field-width','value'=>$width,'name'=>'width', 'label'=>'select width','before_label'=>true));

		if(isset($text))
			$this->input_field( array('size'=>30, 'value'=>$text, 'class'=>'field-text','name'=>'text','label'=>'text input','type'=>'text' , 'before_label'=>true));

		if(isset($before))
			$this->input_field( array('size'=>10, 'value'=>stripslashes($before), 'class'=>'field-textarea','name'=>'before','label'=>'before html','type'=>'textarea' , 'before_label'=>true));

		if(isset($after))
			$this->input_field( array('size'=>10, 'value'=>stripslashes($after), 'class'=>'field-textarea','name'=>'after','label'=>'after html','type'=>'textarea' , 'before_label'=>true));

		if(isset($empty))
			$this->input_field( array('size'=>10, 'value'=>stripslashes($empty), 'class'=>'field-textarea','name'=>'empty','label'=>'content to be displayed on empty','type'=>'textarea' , 'before_label'=>true));

		if(isset($url_prefix))
			$this->input_field(array('value'=>$url_prefix, 'class'=>'field-text','name'=> 'url_prefix', 'label'=>'url prefix ( http:// )','type'=>'text', 'class'=>'field-url-prefix','before_label'=>true));

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
			if(isset($description)):
				?><pre class="description"><?php echo htmlspecialchars(stripslashes($description)); ?></pre><?php
			endif;
		?>
	 	
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

			if($action!='edit'):
				echo '<a href="#add" '. $style_multiple .' class="button add-multiple">Add another</a>';
			else:
				echo '<a href="#add" '. $style_multiple .' class="button disabled">Add another</a> <em>disabled in preview</em>';
			endif;
	 	
	 
		endif;
?>
	 	</div></<?php echo $shell; ?>><?php
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
		
		require(PROFILE_CCT_DIR.'class/input_field.php');
	}

	/**
	 * w function.
	 *
	 * @access public
	 * @param mixed $options
	 * @return void
	 */
	function display_text($options) {
		require(PROFILE_CCT_DIR.'class/display_text.php');
	}

	/**
	 * add_field function.
	 * function return by ajax to be displayed
	 * @access public
	 * @return void
	 */
	function update_fields() {
		$context = $_POST['context'];

		if(in_array($_POST['where'], array('form','page','list')))
			$where = $_POST['where'];
		else
			$where = 'form';

		if(in_array($_POST['width'], array('full','half','one-third','two-third')))
			$width = $_POST['width'];
		else
			$width = 'full';

		$options = $this->get_option($where,'fields',$context);

		switch( $_POST['method'] ){

		case "update":
			if(is_numeric($_POST['field_index'])):
			switch($where){
				case "form":
					
					$options[$_POST['field_index']]['label']   = $_POST['label'];
					$options[$_POST['field_index']]['description']  = $_POST['description'];
					$options[$_POST['field_index']]['show']   = $_POST['show'];
					$options[$_POST['field_index']]['multiple']  = ( isset($_POST['multiple']) &&  $_POST['multiple'] ? $_POST['multiple'] : 0);
					$options[$_POST['field_index']]['url_prefix']   = $_POST['url_prefix'];
					
					// save the url prefix also in the settings array
					if(!is_array($this->settings_options['data-url'])):
						$this->settings_options['data-url'] = array();
						
						$this->settings_options['data-url'] = array_merge ($this->settings_options['data-url'], array($_POST['type'] => trim($_POST['url_prefix']) ));
						update_option('Profile_CCT_settings', $this->settings_options);
					endif;
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
			$print = "updated";
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
			$print =  "sorted";
			break;
		}
		
		// save the opions
		$this->update_option($where,'fields',$context,$options);
		echo $print;
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

		if(in_array($_POST['where'], array('page','form')))
			$where = $_POST['where'];
		else
			$where = 'form';
		
		$tabs = $this->get_option($where,'tabs');
		

		switch($_POST['method']) {

			case "update":
				$tabs[$_POST['index']] = $_POST['title'];
				$print = "updated";
				break;
	
			case "remove":
	
				// we need to set the proper item to fields to zero as well.
				// and move them to the bench
				$index = $_POST['index'];
				$tabs_count = count($tabs); 
				
				unset( $tabs[ $index ] );
				
				// delete the current field
				$count = $index+1;
				$fields = $this->get_option($where,'fields','tabbed-'.$count);
				$this->delete_option($where,'fields','tabbed-'.$count);
	
	
				if(is_array($fields)): // array was empty so nothing to move
					$bench  = $this->get_option($where,'fields','bench');
					
					// merge but don't duplicate the fields if they are there already
					$bench  = array_merge($bench , $fields);
					
					// save the new banch
					$bench = $this->update_option($where,'fields','bench', $bench);
	
				endif;
				
				
				while($count < $tabs_count):
					
					$count++;
					$fields = $this->get_option($where,'fields','tabbed-'.$count);
	
					$minus = $count - 1;
					
					$this->update_option($where,'fields','tabbed-'.$minus, $fields);
	
					$fields = $this->delete_option($where,'fields','tabbed-'.$count);
		
				endwhile;
				
				$tabs = array_merge($tabs); // reindexes the $tabs array
				$print = "removed";
				break;
	
			case "add":
				$tabs[] = $_POST['title'];
				$tabs_count = count($tabs); 
				$this->update_option($where,'fields','tabbed-'.$tabs_count, array());
				
				$print = "added";
				break;
		}
		
		$this->update_option($where,'tabs','normal', $tabs);
		echo $print;
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
			
			// get the option
			$options = get_option('Profile_CCT_'.$type.'_'.$fields_or_tabs.'_'.$context);
			
			
				
			// if we can't find one in the database
			if(!is_array($options)):
				$default = $this->default_options($type);
			
				if($fields_or_tabs == 'fields')
					$options = $default[$fields_or_tabs][$context];
				else
					$options = $default[$fields_or_tabs];
			endif;
			
				 
			 // lets check if we have the fresh version since we last updated the plugin
			 /* CHECK to see if we need to do the merge */
			$perform_merge = false;
			
			// can we find the version settings
			if(!isset($this->settings_options['version'][$type][$fields_or_tabs][$context])):
				$perform_merge = true;
			// are they less then the current version
			elseif(  $this->version() > $this->settings_options['version'][$type][$fields_or_tabs][$context] ):
				$perform_merge = true;
			endif;
			
			// lets perform the merge 
			if($perform_merge && $context == 'bench'):
				
				$new_fields = $this->default_options('new_fields');
				
				// lets add the new fields in this version to the banch
				if( is_array($new_fields[$this->version()]) ):
					foreach( $new_fields[$this->version()] as $field) :
						
						if( in_array( $type , $field['where'] ) ):
							$options[] = $field['field'];
						endif;
					
					endforeach;
					//  why are we doing this... 
					// $this->update_option($type,$fields_or_tabs,$context,$options);
				endif;
			endif; 
				
		endif;

		$this->option[$type][$fields_or_tabs][$context] = $options;
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
	function update_option($type='form',$fields_or_tabs='fields',$context='normal',$update) {
		
		$this->settings_options['version'][$type][$fields_or_tabs][$context] = $this->version();
		$this->settings_options[$type.'_updated'] = time();
		// saving of the version number
		
		$this->option[$type][$fields_or_tabs][$context] = $update;
		// update the settings
		update_option( 'Profile_CCT_settings', $this->settings_options );

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
	
	function delete_all(){
	
		if(current_user_can('administrator')):
			
			foreach( array("form","page","list") as $where):
				// delete all the fields
				foreach( $this->get_contexts($where) as $context):
					$this->delete_option( $where,'fields',$context);
				endforeach;
				
				// lets not forget the banch 
				$this->delete_option( $where,'fields','bench');
				
				// also delete all the tabs 
				$this->delete_option( $where,'tabs');
				
			endforeach;
		
			// finally delete the settings data 
			delete_option('Profile_CCT_settings');
			
			// also delete all the taxonomies 
			delete_option('Profile_CCT_taxonomy');
			
			// also the global settings 
			if(current_user_can('manage_sites') && $_GET['delete_profile_cct_data'] == "DELETE-GLOBAL" )
				delete_site_option('Profile_CCT_global_settings');
			
			wp_die('all Settings data was deleted');
		endif;
	}
	/**
	 * default_options function.
	 *
	 * @access public
	 * @param string $type. (default: 'form')
	 * @return void
	 */
	function default_options($type = 'form') {
		
		require(PROFILE_CCT_DIR.'class/default_options.php');
		
		return apply_filters( 'profile_cct_default_options', $options, $type);
		
	}
	/**
	 * fields_to_clone function.
	 * fields that we are able to create dulicates out of
	 * @access public
	 * @return void
	 */
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
				array( "type"=> "project" ),
				array( "type"=> "courses" ),
				array( "type"=> "data" )
			));
	}
	
	function permissions_table( $user, $alternate=false) {
		
		if( is_array($this->settings_options['permissions'][$user]) ):
		
			$disabled = ($user == 'administrator'? 'disabled' : '');
			?>
			<tr <?php echo ($alternate ? 'class="alternate"': '' ) ?>>
				<td><?php echo ucwords($user); ?></td>
				<?php foreach($this->settings_options['permissions'][$user] as $action=>$can): ?>
				<td><input type="checkbox" name="options[permissions][<?php echo $user; ?>][<?php echo $action; ?>]" <?php echo $disabled; ?> value="1" <?php checked($can); ?> /></td>	
				<?php endforeach; ?>
			</tr>
			<?php 
		endif;
			
	}
	
	function correct_URL($address) {
	    if (!empty($address) AND $address{0} != '#' AND
	    strpos(strtolower($address), 'mailto:') === FALSE AND
	    strpos(strtolower($address), 'javascript:') === FALSE)
	    {
	        $address = explode('/', $address);
	        $keys = array_keys($address, '..');
	
	        foreach($keys AS $keypos => $key)
	            array_splice($address, $key - ($keypos * 2 + 1), 2);
	
	        $address = implode('/', $address);
	        $address = str_replace('./', '', $address);
	       
	        $scheme = parse_url($address);
	       
	        if (empty($scheme['scheme']))
	            $address = 'http://' . $address;
	
	        $parts = parse_url($address);
	        $address = strtolower($parts['scheme']) . '://';
	
	        if (!empty($parts['user']))
	        {
	            $address .= $parts['user'];
	
	            if (!empty($parts['pass']))
	                $address .= ':' . $parts['pass'];
	
	            $address .= '@';
	        }
	
	        if (!empty($parts['host']))
	        {
	            $host = str_replace(',', '.', strtolower($parts['host']));
	
	            if (strpos(ltrim($host, 'www.'), '.') === FALSE)
	                $host .= '.com';
	
	            $address .= $host;
	        }
	
	        if (!empty($parts['port']))
	            $address .= ':' . $parts['port'];
	
	        $address .= '/';
	
	        if (!empty($parts['path']))
	        {
	            $path = trim($parts['path'], ' /\\');
	
	            if (!empty($path) AND strpos($path, '.') === FALSE)
	                $path .= '/';
	               
	            $address .= $path;
	        }
	
	        if (!empty($parts['query']))
	            $address .= '?' . $parts['query'];
	
	        return $address;
	    }
	
	    else
	        return FALSE;
	}
	
	function install() {
		$field = Profile_CCT::get_object();
		$field->register_cpt_profile_cct();
		flush_rewrite_rules();
		
		// set up the permissions
		if( !is_array($field->settings_options['permissions']) ) {
			$settings_options = $field->default_options( 'settings' );
			$field->settings_options['permissions'] = $settings_options['permissions'];
		}
		
		foreach($field->settings_options['permissions'] as $user=>$permission_array):
			$role = get_role( $user );
			foreach($permission_array as $permission => $can):
			
				// add the new capability
				if( $field->settings_options['permissions'][$user][$permission] ):
					$role->add_cap( $permission );
				else: // or remove it
					$role->remove_cap(  $permission );
				endif;
				
			endforeach;
		endforeach;
		
	}
	function uninstall() {
		
		// remove permissions
		$field = Profile_CCT::get_object();
		$default = $field->default_options( 'settings' );
		foreach($default['permissions'] as $user=>$permission_array):
			$role = get_role( $user );
			foreach($permission_array as $permission => $can):
					$role->remove_cap(  $permission );	
			endforeach;
			
			
		endforeach;
	}
} // end class
endif;
if ( function_exists( 'add_action' ) && class_exists( 'Profile_CCT' ) )
	add_action( 'plugins_loaded', array( 'Profile_CCT', 'get_object' ) );
	

register_activation_hook( __FILE__, array('Profile_CCT', 'install') );
register_deactivation_hook( __FILE__, array('Profile_CCT', 'uninstall') );
