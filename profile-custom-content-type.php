<?php
/**
* Plugin Name: Profile Custom Content Type
* Plugin URI: 
* Text Domain: profile_cct
* Domain Path: /languages
* Description: Allows administrators to manage user profiles better in order to display them on their websites
* Author: Enej Bajgoric, CTLT
* Version: 0.0.1alpha
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
if(isset( $_GET['d'])):
	delete_option('Profile_CCT_form_fields_tabbed-1');
	delete_option('Profile_CCT_form_fields_tabbed-2');
	delete_option('Profile_CCT_form_fields_tabbed-3');
	delete_option('Profile_CCT_form_fields_tabbed-4');
	delete_option('Profile_CCT_form_fields_tabbed-5');
	delete_option('Profile_CCT_form_fields_tabbed-6');
	delete_option('Profile_CCT_form_fields_normal');
	delete_option('Profile_CCT_form_fields_side');
	delete_option('Profile_CCT_form_fields_banch');
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
	delete_option('Profile_CCT_page_fields_banch');
	delete_option('Profile_CCT_page_tabs_normal');
	
	delete_option('Profile_CCT_page_fields');
	
endif;

require_once('profile-taxonomies.php');

class Profile_CCT {
	static private $classobj = NULL;
	
	static public  $textdomain = NULL;
	
	static public  $settings_options = NULL;
	static public  $form_fields = NULL;
	static public  $page_fields = NULL;
	static private $field = NULL;
	static private $form_field_counter = 0;
	
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
		
		add_action( 'init',  array( $this,'profiles_cct_init')) ; 
		
		add_action( 'template_redirect',  array( $this,'check_freshness')); 
		add_action( 'wp_insert_post_data', array( $this,'save_post_data'),10,2);
		
		add_action( 'wp_ajax_cct_update_fields', array( $this,'update_fields'));
		add_action( 'wp_ajax_cct_update_tabs', array( $this,'update_tabs'));
		
		add_action( 'admin_print_styles-post-new.php', array( $this,'add_style_edit'));
		add_action( 'admin_print_styles-post.php',array( $this,'add_style_edit'));
		
		/* Register Settings */
		register_setting( 'Profile_CCT_form_fields', 'Profile_CCT_form_fields',  array($this,'validate_form_fields'));
		register_setting( 'Profile_CCT_page_fields', 'Profile_CCT_page_fields', array($this,'validate_page_fields'));
		register_setting( 'Profile_CCT_list_page', 'Profile_CCT_list_page'  );
		register_setting( 'Profile_CCT_settings', 'Profile_CCT_settings' );
		register_setting( 'Profile_CCT_taxonomy', 'Profile_CCT_taxonomy' );
		
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
		
		add_action('profile_cct_form', array( $this,'profile_cct_form_field_shell'),10,1);
		
		add_action('profile_cct_page', array( $this,'profile_cct_page_field_shell'),10,2);
		
		
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
		return $this -> get_plugin_data( 'TextDomain' );
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
			wp_enqueue_style( 'profile-cct-edit-post', WP_PLUGIN_URL . '/profile-cct/css/profile-page.css' );
			wp_enqueue_script("thickbox");
			wp_enqueue_style("thickbox");
			wp_enqueue_script( 'profile-cct-edit-post', WP_PLUGIN_URL . '/profile-cct/js/profile-page.js',array('jquery-ui-tabs' ) );
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
	 * add_menu_page function.
	 * 
	 * @access public
	 * @return void
	 */
	public function add_menu_page () {
	
		$page = add_submenu_page( 
			'edit.php?post_type=profile_cct',
			__( 'Settings', $this -> get_textdomain() ),
			__( 'Settings', $this -> get_textdomain() ),
			'manage_options', __FILE__,
			array( $this, 'admin_pages' ) );
			
			
		add_action( 'admin_print_styles-' . $page, array( $this, 'admin_styles' ) );
		add_action( 'admin_print_scripts-' . $page, array( $this, 'admin_scripts' ) );
		
	}
	
	/**
	 * admin_styles function.
	 * 
	 * @access public
	 * @return void
	 */
	public function admin_styles(){
		wp_enqueue_style( 'profile-cct-admin', WP_PLUGIN_URL . '/profile-cct/css/admin.css' );
		switch( $_GET['view'] ) {
			case "form":
				
				wp_enqueue_style( 'profile-cct-form', WP_PLUGIN_URL . '/profile-cct/css/form.css' );
			break;
			case "page":
			case "list":
				wp_enqueue_style( 'profile-cct-form', WP_PLUGIN_URL . '/profile-cct/css/form.css' );
				// wp_enqueue_style( 'profile-cct-page', WP_PLUGIN_URL . '/profile-cct/css/page-list.css' );
			break;
			/*case "helper":
				wp_register_style( 'profile-cct-helper', WP_PLUGIN_URL . '/profile-cct/stylesheet.css' );
			break;
			*/
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
	public function admin_scripts(){
	
		switch( $_GET['view'] ) {
			case "form":
				wp_enqueue_script('jquery-ui-droppable');
				wp_enqueue_script('jquery-ui-sortable');
				wp_enqueue_script('jquery-ui-tabs');
				wp_enqueue_script( 'profile-cct-form', WP_PLUGIN_URL . '/profile-cct/js/form.js',array('jquery','jquery-ui-sortable') );
				wp_enqueue_script( 'profile-cct-tabs', WP_PLUGIN_URL . '/profile-cct/js/tabs.js',array('jquery','jquery-ui-tabs') );
				wp_localize_script( 'profile-cct-tabs', 'ProfileCCT', array(
	  				'type' => 'form'
				));
			break;
			case "page":
				wp_enqueue_script( 'profile-cct-tabs', WP_PLUGIN_URL . '/profile-cct/js/tabs.js',array('jquery','jquery-ui-tabs') );
				wp_enqueue_script( 'profile-cct-form', WP_PLUGIN_URL . '/profile-cct/js/form.js',array('jquery','jquery-ui-sortable') );
				wp_enqueue_script( 'profile-cct-profile', WP_PLUGIN_URL . '/profile-cct/js/profile.js',array('jquery') );
				wp_localize_script( 'profile-cct-tabs', 'ProfileCCT', array(
	  				'type' => 'page'
				));
			break;
			case "list":
				wp_enqueue_script( 'profile-cct-page', WP_PLUGIN_URL . '/profile-cct/js/page-list.js', array('jquery','jquery-ui-sortable','jquery-ui-tabs','jquery-ui-droppable') );
				wp_localize_script( 'profile-cct-tabs', 'ProfileCCT', array(
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
		screen_icon( 'users' );
		?>
		<div class="wrap">
		<h2><?php echo $this -> get_plugin_data( 'Name' ) ?></h2>
		<h3 class="nav-tab-wrapper">
		
		<a class="nav-tab <?php if( !isset($_GET['view']) ) { echo "nav-tab-active"; } ?>" 
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php">Settings</a>
		<span>Builder:</span>
		<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='form' ) { echo "nav-tab-active"; } ?>" 
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=form">Form</a>
		<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='page' ) { echo "nav-tab-active"; } ?>" 
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=page">Page</a>
		<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='list' ) { echo "nav-tab-active"; } ?>" 
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=list">List</a>
		<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='taxonomy' ) { echo "nav-tab-active"; } ?>" 
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=taxonomy">Taxonomy</a>
		<a class="nav-tab <?php if( isset($_GET['view']) && $_GET['view'] =='helper' ) { echo "nav-tab-active"; } ?>" 
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=helper">HELPER</a>
		</h3>
		
		<?php switch( $_GET['view'] ) {
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
			default:
				require_once("views/settings.php");
			break;
		
		}	
	}
	
	function profiles_cct_init(){
		
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
	        'edit_item' => _x( 'Edit Profile', 'profile_cct' ),
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
	        
	        'supports' => array( 'revisions' ),
	        'taxonomies' => array( 'new tax' ),
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
	            'edit_post' => 'edit_profile_cct',
	            'edit_posts' => 'edit_profiles_cct',
	            'edit_others_posts' => 'edit_all_profile_cct',
	            'publish_posts' => 'publish_profile_cct',
	            'read_post' => 'read_profile_cct',
	            'read_private_posts' => 'read_private_profile_cct',
	            'delete_post' => 'delete_profile_cct'
	        )
	    );
	
	    register_post_type( 'profile_cct', $args );
	    
	}
	
	
	function load_scripts_cpt_profile_cct()
	{
		if(!is_admin()):
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_style( 'profile-cct', WP_PLUGIN_URL . '/profile-cct/css/profile-cct.css' );
		endif;
		//add_filter('template_include', array( $this, 'help' ));
	}
	
	function check_freshness(){
		
		
		if(is_post_type_archive( 'profile_cct' )):
			global $post;
			
			
		endif;
		// 
		if(is_singular( 'profile_cct' )):
			global $post;
			
			if( $this->settings_options["page_updated"] > strtotime($post->post_modified_gmt )):
				
				$data = get_post_meta($post->ID, 'profile_cct');
				ob_start();
				do_action('profile_cct_page','display', $data);
				$content = ob_get_contents();
				ob_end_clean();
				
				
				$post->post_content = $content;
				$post->post_modified = current_time( 'mysql' );
				$post->post_modified_gmt = current_time( 'mysql', 1);
				wp_update_post( $post );
			endif;
		
		endif;
	}
	function help($test){
		// var_dump($test); 
		return $test;
	}
	/**
	 * edit_post function.
	 * 
	 * @access public
	 * @return void
	 */
	function edit_post()
	{
		global $post;
			
		$this->form_fields = get_option('Profile_CCT_form_fields');
		
		$user_data = get_post_meta($post->ID, 'profile_cct', true );
		// $user_data = unserialize( $post->post_content_filtered );
		// var_dump($user_data['name']);
		$contexts = $this->get_contexts();
 		
 		if( is_array( $contexts ) ):
 		
			foreach( $contexts as $context ):
				
				$fields = $this->get_option('form','fields',$context);
				
				foreach($fields as $field):
					// add_meta_box( $id, $title, $callback, $page, $context, $priority, $callback_args );
					
					// var_dump($user_data[ $field['type']]);
					add_meta_box( 
								$field['type']."-".$i.'-'.rand(0,999), 
								$field['label'], 
								'profile_cct_'.$field['type'].'_field_shell', 
								'profile_cct', $context, 'low', 
								array(
									'options'=>$field,
									'data'=>$user_data[ $field['type']]
									)
								);
				endforeach;
			endforeach;
		endif;
	}
	function edit_form_advanced(){
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
					<?php do_meta_boxes('profile_cct', 'tabbed-'.$count, $post);	 ?>			
				</div>
				<?php 
				$count++;
			endforeach; ?>
		</div>
		<?php
			
		endif;
	}
		
	function save_post_data($data,$postarr)
	{
		global $post;
		
		if($data['post_type'] != 'profile_cct')
			return $data;
		
			// save the name of the person as the title 
		if( is_array( $_POST["profile_cct"]["name"]) ):
			$data['post_title'] = $_POST["profile_cct"]["name"]['title']." ".$_POST["profile_cct"]["name"]['first']." ".$_POST["profile_cct"]["name"]['last']." ".$_POST["profile_cct"]["cct-name"]['prefix'];
			else:
				$userdata = get_userdata($data['post_author']);
				$data['post_title'] = $userdata->user_nicename;
			endif;
			if(isset($_POST["profile_cct"])):
			
				ob_start();
				do_action('profile_cct_page','display', $_POST["profile_cct"]);
				$content = ob_get_contents();
				ob_end_clean();
				
				
				$data['post_content'] = $content;
			endif;
		
		
		if(is_array($_POST["profile_cct"]))
			update_post_meta($postarr['ID'], 'profile_cct', $_POST["profile_cct"]);
		return $data;
		/*
		die();
		*/
	
	}
	/* ============== FIELDS =============================================== */
	function profile_cct_page_field_shell($action,$user_data){
		$contexts = $this->default_shells('page'); ?><div id="page-shell"><?php 
	 	foreach($contexts as $context):
	 		
		 	if(function_exists('profile_cct_page_shell_'.$context)):
		 		call_user_func('profile_cct_page_shell_'.$context,$action,$user_data);
		 	else: 
		 		
		 		?><div id="<?php echo $context; ?>-shell" class="shell"><?php 
		 			if($action == 'edit'): ?>
		 			<span class="description-shell"><?php echo $context; ?></span>
		 			<ul class="form-builder sort" id="<?php echo $context; ?>">
		 			<?php endif; 
		 			 
		 			$fields = $this->get_option('page','fields',$context);
		 				
			 		if( is_array( $fields  ) ):
				 		foreach($fields  as $field):
				 			call_user_func('profile_cct_'.$field['type'].'_display_shell',$action,$field,$user_data[ $field['type']]);
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
	 * profile_cct_form_field_shell function.
	 * 
	 * @access public
	 * @param mixed $action
	 * @return void
	 */
	function profile_cct_form_field_shell($action){
	 	
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
				 			call_user_func('profile_cct_'.$field['type'].'_field_shell',$action,$field);
				 		endforeach;
			 		endif;
		 		?></ul>
		 		</div>
		 		<?php 
		 		
		 	endif;
	 	endforeach;
	 }
	 /**
	  * start_field function.
	  * 
	  * @access public
	  * @param mixed $action
	  * @param mixed $options
	  * @return void
	  */
	 function start_field($action, $options ) {
	 	extract( $options );
	 	// be default show the remove button
	 	if( !isset($show_remove))
	 		$show_remove = true;
	 		
	 	$shell = 'div';
	 	if($action == 'edit')
	 		$shell = 'li';
	 	
	
	 	if($action == 'edit'): 
	 	
	 		?>
	 		<<?php echo $shell; ?> class="<?php echo esc_attr( $type); ?> field-item <?php echo $width; ?>" for="cct-<?php echo esc_attr( $field_type); ?>" data-options="<?php echo esc_attr( $this->serialize($options)); ?>" >

			<a href="#edit-field" class="edit">Edit</a>
			<div class="edit-shell" style="display:none;">
					<input type="hidden" name="type" value="<?php echo esc_attr( $type ); ?>" />
				<?php 
				
				if(empty($hide_label) && !$hide_label)
					$this->input_field( array('size'=>20, 'value'=>$label, 'class'=>'field-label', 'name'=>'label','label'=>'label', 'type'=>'text', 'before_label'=>true ));
				else
				?>	<input type="hidden" name="label" value="<?php echo esc_attr( $label ); ?>" /> <?php
					if(isset($description))
				 		$this->input_field( array('size'=>10, 'value'=>$description, 'class'=>'field-description','name'=>'description','label'=>'description','type'=>'textarea' , 'before_label'=>true));
				 	
				 	if(isset($width))
						$this->input_field(array('type'=>'select','all_fields'=>array('full','half','one-third','two-third'), 'class'=>'field-width','selected_fields'=>$width,'name'=>'width', 'label'=>'select width','before_label'=>true));
				 		
				 	if(isset($before))
				 		$this->input_field( array('size'=>10, 'value'=>$before, 'class'=>'field-before','name'=>'before','label'=>'before html','type'=>'textarea' , 'before_label'=>true));
				 		
				 	if(isset($after))
				 		$this->input_field( array('size'=>10, 'value'=>$after, 'class'=>'field-after','name'=>'after','label'=>'after html','type'=>'textarea' , 'before_label'=>true));	
				 					
					if(isset($show_fields))
						$this->input_field(array('type'=>'multiple','all_fields'=>$show_fields, 'class'=>'field-show','selected_fields'=>$show,'name'=>'show', 'label'=>'show / hide input area','before_label'=>true));			
					
					if(isset($show_multiple) && $show_multiple)
						$this->input_field(array('type'=>'checkbox','name'=>'multiple', 'class'=>'field-multiple', 'field'=>'yes allow the user to create multiple', 'value'=>$multiple,'label'=>'allow for multiple entries','before_label'=>true));
					 ?>
					 <input type="button" value="Save" class="button save-field-settings" />
					 <span class="spinner" style="display:none;"><img src="<?php echo admin_url(); ?>/images/wpspin_light.gif" alt="spinner" /> saving...</span>
			</div>
		 	<label for="" id="" class="field-title"><?php echo $label; ?></label>
		 	<?php 
	 	endif;
	 	if($action == 'display'): ?>
	 		<<?php echo $shell; ?> class="<?php echo esc_attr( $type); ?> field-item <?php echo $width; ?>">
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
	 function end_field( $action, $options )
	 {
	 	$shell = 'div';
	 	if($action == 'edit')
	 		$shell = 'li';
	 	extract( $options );
	 
	 	if( isset($show_multiple) && $show_multiple ):
	 		
	 		$style_multiple = ( isset($multiple) && $multiple ? 'style="display: inline;"': 'style="display: none;"');
	 	 ?>
	 	<a href="#add" <?php echo $style_multiple; ?> class="button add-multiple">Add</a>
	 	<?php 
	 	endif; 
	 	?>
	 	</div>
	 	<?php 
	 	if(isset($description)):
	 	?><pre class="description"><?php echo $description; ?></pre><?php 
	 	endif;
	 	?></<?php echo $shell; ?>><?php 
	 }
	 /**
	  * input_field function.
	  * 
	  * @access public
	  * @param mixed $options
	  * @return void
	  */
	 function input_field( $options )
	 {
	 	
	 	extract( $options );
	 	
	 	$before_label = ( isset($before_label) && $before_label ? true:false);
	 	$field_id_class = ( isset($field_id)? ' class="'.$field_id.' '.$type.'-shell"': '');
	 	
	 	
	 	$size = ( isset($size)? ' size="'.$size.'"': '');
	 	$row = ( isset($row)? ' row="'.$row.'"': '');
	 	$cols = ( isset($cols)? ' cols="'.$cols.'"': '');
	 	$class = ( isset($class)? ' class="'.$class.'"': ' class="field text"');
	 	$id = ( isset($id)? ' id="'.$id.'"': ' ');
	 	
	 	
	 	if($type =='multiple')
	 		$name = ( isset($name)? ' name="'.$name.'[]"':  ' name="profile_cct['.$field_type.']['.$field_id.'][]"');
	 	elseif($multiple)
	 		$name = ( isset($name)? ' name="'.$name.'[]"':  ' name="profile_cct['.$field_type.']['.$count.']['.$field_id.']"');
	 	else
	 		$name = ( isset($name)? ' name="'.$name.'"': ' name="profile_cct['.$field_type.']['.$field_id.']"');
	 		
	 	$show = ( isset($show) && !$show ? ' style="display:none;"': '');
	 	switch($type) {
	 		case 'text':
			 	?>
			 	<span <?php echo $field_id_class.$show; ?>>
			 		<?php if($before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
					<input type="text" <?php echo $size.$class.$name; ?> value="<?php echo esc_attr($value); ?>" id="">
					<?php if(!$before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
				</span>
				<?php 
			break;
			
			case 'multiple':
	 				?><div <?php echo $field_id_class.$show;  ?>>
	 				<?php 
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
	 				?><div <?php echo $field_id_class.$show;  ?>>
	 				<?php if($before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
	 					<label><input type="checkbox" <?php checked( $value ); ?> value="1" <?php echo $class.$name; ?> /> <?php echo $field; ?></label>
	 				<?php if(!$before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
	 				</div>
	 				<?php 
	 		break;
	 		
	 		case 'select':
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
	 				?>
	 				<span <?php echo $field_id_class; ?>>
	 				<?php if($before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
					<textarea <?php echo $size.$class.$name.$row.$cols; ?> id=""><?php echo esc_html($value); ?></textarea>
					<?php if(!$before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
					</span>
	 				<?php
	 		break;
	 		
	 		
		}
		
	 }
	 
	function display_text($options)
	{
		extract( $options );
	 	
	 	$class = ( isset($class)? ' class="'.$class.'"': ' class=""');
	 	$id = ( isset($id)? ' id="'.$id.'"': ' ');
	 	
	 	$href = ( isset($href)? ' href="'.$href.'"': ' ');
	 	
	 		
	 	$show = ( isset($show) && !$show ? ' style="display:none;"': '');
	 	
	 	$tag = (isset($tag) ? $tag :"span");
	 	
	 	switch( $default_text ){
	 		case 'lorem ipsum':
	 			
	 		$default_text = "<p><strong>".$field_type."</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in velit ac sem dapibus cursus. Donec faucibus adipiscing ipsum ut auctor. Integer quis metus iaculis lacus vulputate facilisis. Fusce malesuada volutpat sapien eu commodo. Integer sed magna orci, quis commodo elit. In convallis fringilla mollis. Pellentesque dapibus mi quis nunc pulvinar lobortis. Sed ut purus auctor ligula aliquam egestas eu at sem. Sed eget nisl urna. Etiam vitae leo id erat porttitor iaculis et et lorem. Curabitur condimentum libero eget sapien dictum congue. In hac habitasse platea dictumst. In in nulla et elit vehicula tempor. Donec sem arcu, viverra quis dignissim ac, adipiscing sed nunc.</p>

<p>Quisque malesuada tellus vitae massa semper non faucibus leo sollicitudin. In sit amet feugiat ligula. Ut id ultrices magna. Proin ut imperdiet tellus. Nulla interdum eleifend massa egestas malesuada. Suspendisse potenti. Nulla suscipit imperdiet velit sit amet pretium. In sit amet lectus felis, commodo varius eros. Duis sapien diam, sagittis faucibus elementum vulputate, faucibus a mi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec viverra, quam in pretium volutpat, elit sapien tempor neque, quis adipiscing magna quam vitae velit.</p>";
			break;
	 	}
	 	
	 	$display = ( $value ? $value :$default_text);
	 	
	 	
	 	switch($type) {
	 		case 'text':
			 	echo "<".$tag." ".$id.$class.$href.">";
			 	echo $display; 
				echo " </".$tag.">";
			break;
			
			case 'shell':
				echo "<".$tag." ".$id.$class.">";
			break;
			
			case 'end_shell';
				echo "</".$tag.">";
			break;
	 		
		}
	}
	 
	/**
	 * add_field function.
	 * function return by ajax to be displayed
	 * @access public
	 * @return void
	 */
	function update_fields()
 	{	
 		
 		$context = $_POST['context'];
		
		
		if(in_array($_POST['type'], array('page','form','list')))
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
					$options[$_POST['field_index']]['label'] 		= $_POST['label'];
	 				$options[$_POST['field_index']]['description'] 	= $_POST['description'];
	 				$options[$_POST['field_index']]['before'] 	= $_POST['before'];
	 				$options[$_POST['field_index']]['after'] 	= $_POST['after'];
	 				$options[$_POST['field_index']]['show'] 		= $_POST['show'];
	 				
	 				$options[$_POST['field_index']]['width'] 		= $width;
	 				
	 				$options[$_POST['field_index']]['multiple']		= ( isset($_POST['multiple']) &&  $_POST['multiple'] ? $_POST['multiple'] : 0); 
	 				
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
	public function field_field_tab_index(){
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
				// and move them to the banch
				$index = $_POST['index'];
				
				$tabs_count = count($tabs);
				
				unset( $tabs[ $index ] );
				
			
				$count = $index+1;
				$fields = $this->get_option($type,'fields','tabbed-'.$count);
				$this->delete_option($type,'fields','tabbed-'.$count);
				//var_dump($index, $count, $fields, $tabs_count, $count < $tabs_count);
				if(is_array($fields)): // array was empty so nothing to move
					$banch  = $this->get_option($type,'fields','banch');
					// and move them to the banch
					$banch  = array_merge($banch , $fields);
					
					$banch = $this->update_option($type,'fields','banch', $banch);
					
				endif;
				
				//var_dump("deleted - tabbed-".$count);
				
				while($count < $tabs_count):
					$count++;
					$fields = $this->get_option($type,'fields','tabbed-'.$count);
					
					
					
					$minus = $count - 1;
					
					//var_dump("moved - tabbed-".$count. " to tabbed-".$minus);
					$this->update_option($type,'fields','tabbed-'.$minus, $fields);
					
					//var_dump("delete - tabbed-".$count);
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
	function validate_form_fields( $input )
	{
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
	function validate_page_fields( $input )
	{
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
	function stripslashes_deep( $value )
	{
	    $value = is_array($value) ?
	                array_map('stripslashes_deep', $value) :
	                stripslashes($value);
	    return $value;
	}
	
	
	function is_data_array( $data )
	{
		// var_dump(is_array($data), is_array($data[0]), $data);
		if(!is_array($data) || !is_array($data[0]))
			return false;
		
		return true;
	}
	
	function serialize( $data )
	{
		
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
	function default_shells($type = 'form')
	{
		if($type == 'page')
			return array( 'header', 'tabs', 'bottom');
		else
			return array( 'normal','side','tabs');
	 
	}
	 
	function get_contexts($type = 'form'){
		
		$contexts = $this->default_shells();
		$id = array_search('tabs',$contexts);
		
		if(is_numeric($id)):
	 		$tabs = $this->get_option($type,'tabs');
	 	
	 		if(is_array($tabs)):
		 		$count = 1;
		 		foreach($tabs as $tab):
		 			$contexts[] = "tabbed-".$count;
		 			$count++;
		 		endforeach;
	 		endif;
	 		
	 		unset($contexts[$id]);
	 		$contexts = array_values($contexts);
	 	endif;
	 	return $contexts;
	}
	
	function get_option($type='form',$fields_or_tabs='fields',$context='normal'){
		$option = get_option('Profile_CCT_'.$type.'_'.$fields_or_tabs.'_'.$context);
		
		if(!is_array($option)):
			$default = $this->default_options($type);
	
			if($fields_or_tabs == 'fields')
				return $default[$fields_or_tabs][$context];
			else
				return $default[$fields_or_tabs]; 
			
		else:
			
			return $option;
		endif; 
	}
	
	function update_option($type='form',$fields_or_tabs='fields',$context='normal',$update){
		$settings = get_option('Profile_CCT_settings');
		if(!is_array($settings))
			$settings = array();
		
		$settings[$type.'_updated'] = time();
		
		// update the settings
		update_option( 'Profile_CCT_settings', $settings );
		
		return update_option( 'Profile_CCT_'.$type.'_'.$fields_or_tabs.'_'.$context, $update);
	}
	function delete_option($type='form',$fields_or_tabs='fields',$context='normal'){
		
		return delete_option('Profile_CCT_'.$type.'_'.$fields_or_tabs.'_'.$context);
	}
	
	function default_options($type = 'form')
	 {
	 		if($type == 'page'):
	 			return apply_filters('profile_cct_default_options', array(
		 				'fields'=> array(
				 				'tabbed-1' => array(
								 		array( "type"=> "address", 		"label"=> "address",),
								 		array( "type"=> "phone",		"label"=> "phone" ), 
								 		array( "type"=> "email",		"label"=> "email" ),
								 		array( "type"=> "website",		"label"=> "website"),
								 		array( "type"=> "social",		"label"=> "social")
							 		),
						 		 'tabbed-2' =>array(
							 			array( "type"=> "position" ,	"label"=> "position" ), 
								 		array( "type"=> "bio",			"label"=> "bio" )
								 		
							 		),
							 	 'header'=> array( 
							 	 		array( "type"=>"picture", "label"=>"picture" ),
							 			array("type"=> "name" ,	"label"=> "name" )
							 			),
								 'bottom'=> array( 
													 	
							 			),
							 	'banch' =>array(
							 			array( "type"=> "education", 	"label"=> "education" ), 
								 		array( "type"=> "teaching",		"label"=> "teaching" ), 
								 		array( "type"=> "publications",	"label"=> "publications" ), 
								 		array( "type"=> "research",		"label"=> "research" )
							 	)),
					   'tabs' => array("Basic Info", "Bio")
				 	));
	 		elseif($type == 'form'):
	 			
				return apply_filters('profile_cct_default_options', array(
		 				'fields'=> array(
				 				'tabbed-1' => array(
								 		array( "type"=> "address", 		"label"=> "address",),
								 		array( "type"=> "phone",		"label"=> "phone" ), 
								 		array( "type"=> "email",		"label"=> "email" ),
								 		array( "type"=> "website",		"label"=> "website"),
								 		array( "type"=> "social",		"label"=> "social")
							 		),
						 		 'tabbed-2' =>array(
							 			array( "type"=> "position" ,	"label"=> "position" ), 
								 		array( "type"=> "bio",			"label"=> "bio" )
								 		
							 		),
							 	 'normal'=> array( 
							 			array("type"=> "name" ,	"label"=> "name" )
							 			),
								 'side'=> array( 
										array( "type"=>"picture", "label"=>"picture" )			 	
							 			),
							 	'banch' =>array(
							 			array( "type"=> "education", 	"label"=> "education" ), 
								 		array( "type"=> "teaching",		"label"=> "teaching" ), 
								 		array( "type"=> "publications",	"label"=> "publications" ), 
								 		array( "type"=> "research",		"label"=> "research" )
							 	)),
					   'tabs' => array("Basic Info", "Bio")
				 	));
			endif;
		

	 }
} // end class

if ( function_exists( 'add_action' ) && class_exists( 'Profile_CCT' ) )
add_action( 'plugins_loaded', array( 'Profile_CCT', 'get_object' ) );

