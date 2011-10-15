<?php
/**
* Plugin Name: Profile Custom Content Type
* Plugin URI: 
* Text Domain: profile_cct
* Domain Path: /languages
* Description: Allows administrators to manage user profiles better in order to display them on their websites
* Author: Enej Bajgoric, CTLT
* Version: 0.0.1
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
if(isset( $_GET['delete_profile'])):
	delete_option('Profile_CCT_form_fields', $fields);
	delete_option('Profile_CCT_page_fields', $fields);
endif;
class Profile_CCT {
	private static $instance;
	static private $classobj = NULL;
	
	static public $textdomain = NULL;
	
	static private $settings_options = NULL;
	static public $form_fields = NULL;
	static public $page_fields = NULL;
	static private $field = NULL;
	static private $form_field_counter = 0;
	
	public $tab_index = 5; // name should always be first
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
		add_action( 'edit_form_advanced', array($this, 'edit_form_advanced'));
		add_action( 'add_meta_boxes_profile_cct', array($this, 'edit_post')); // add meta boxes 
		add_action( 'init',  array( $this,'register_cpt_profile_cct') );
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
		$dir    = plugin_dir_path(__FILE__).'views/fields/';
		
		// include all files in the fields folder
		if ($handle = opendir($dir)) :
   			/* This is the correct way to loop over the directory. */
   			while (false !== ($file = readdir($handle))):
   				if(substr($file,0,1) != ".")
   					require_once($dir.$file);
   				// var_dump($dir.$file);
        		// 
    		endwhile;

    		closedir($handle);
		endif;
		
	}
	
	function set(){
		if (!isset(self::$instance)):
            $className = __CLASS__;
            self::$instance = new $className;
        endif;
        return self::$instance;
	
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
	
	
	public function get_textdomain() {
		return $this -> get_plugin_data( 'TextDomain' );
	}
	
	function add_style_edit() {
		global $current_screen;
		if($current_screen->id == 'profile_cct'):
			wp_enqueue_style( 'profile-cct-edit-post', WP_PLUGIN_URL . '/profile-cct/css/profile-page.css' );
			wp_enqueue_script( 'profile-cct-edit-post', WP_PLUGIN_URL . '/profile-cct/js/profile-page.js',array('jquery-ui-tabs') );
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
				wp_enqueue_style( 'profile-cct-page', WP_PLUGIN_URL . '/profile-cct/css/page-list.css' );
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
				wp_localize_script( 'profile-cct-tabs', 'ProfileCCT', array(
	  				'type' => 'page'
				));
				case "list":
				wp_enqueue_script( 'profile-cct-page', WP_PLUGIN_URL . '/profile-cct/js/page-list.js', array('jquery','jquery-ui-sortable','jquery-ui-tabs','jquery-ui-droppable') );
			
			break;
			/*case "helper":
				wp_register_style( 'profile-cct-helper', WP_PLUGIN_URL . '/profile-cct/stylesheet.css' );
			break;
			*/
			default:
				wp_enqueue_script( 'profile-cct-settings', WP_PLUGIN_URL . '/profile-cct/js/settings.js' );
			break;
			
			
		
		}
		wp_enqueue_style( 'profile-cct-general', WP_PLUGIN_URL . '/profile-cct/js/general.js' );	
	}
	/**
	 * get_style_examples function.
	 * 
	 * @access public
	 * @return void
	 */
	public function admin_pages() {
		$this->form_fields = get_option('Profile_CCT_form_fields');
		$this->page_fields = get_option('Profile_CCT_page_fields');
		screen_icon( 'users' );
		?>
		<div class="wrap">
		<h2><?php echo $this -> get_plugin_data( 'Name' ) ?></h2>
		<h3 class="nav-tab-wrapper">
		
		<a class="nav-tab <?php if( !isset($_GET['view']) ) { echo "nav-tab-active"; } ?>" 
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php">Settings</a>
		<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='form' ) { echo "nav-tab-active"; } ?>" 
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=form">Form Builder</a>
		<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='page' ) { echo "nav-tab-active"; } ?>" 
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=page">Page Builder</a>
		<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='list' ) { echo "nav-tab-active"; } ?>" 
			href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=list">List Builder</a>
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
			default:
				require_once("views/settings.php");
			break;
		
		}	
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
	        
	        'supports' => array('thumbnail', 'revisions' ),
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
	function edit_form_advanced(){
		global $post;
		
		if($post->post_type == "profile_cct"):
		if( !$this->form_fields['tabs'] ) 
			$this->form_fields['tabs'] = $this->default_tabs('form');
	
		?>
		<div id="tabs">
			<ul>
				<?php 
				$count = 0;
				foreach( $this->form_fields['tabs'] as $tab) : ?>
					<li><a href="#tabs-<?php echo $count; ?>" class="tab-link"><?php echo $tab; ?></a></li>
				<?php 
					$count++;
				endforeach; ?>
			</ul>
			<?php 
			$count = 0;
			foreach( $this->form_fields['tabs'] as $tab) :
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
	/**
	 * edit_post function.
	 * 
	 * @access public
	 * @return void
	 */
	function edit_post()
	{
				
		$this->form_fields = get_option('Profile_CCT_form_fields');
		
		$tab_count = 0;
		foreach($this->form_fields['tabs'] as $tab):
			if(is_array($this->form_fields['fields']) && is_array($this->form_fields['fields'][$tab_count])):
				$i = 0;
				foreach( $this->form_fields['fields'][$tab_count] as $field):
					
					// add_meta_box( $id, $title, $callback, $page, $context, $priority, $callback_args );
					add_meta_box( $field['type']."-".$tab_count."-".$i.'-'.rand(), $field['label'], array($this,'show_page_field'), 'profile_cct', 'tabbed-'.$tab_count,'high', $field);
					$i++;
				endforeach;
			endif;
			$tab_count++;
		endforeach;
			
		add_meta_box( "name-0", "Name", array($this,'show_page_field'), 'profile_cct', 'normal','high',array( "type"=>'cct-name',"label"=>"name") ); 
	}
	
	function show_page_field($post,$field) {
		
		$this->show_raw_field("form",$field['args']["type"]);
	}
	
	function save_post_data($data,$postarr)
	{
		global $post;
		//var_dump($data,$postarr,$post);
		// die();
		
		
		if($data['post_type'] != 'profile_cct')
			return $data;
			
			// save the name of the person as the title 
		if( is_array( $_POST["profile_field"]["cct-name"]) ):
			$data['post_title'] = $_POST["profile_field"]["cct-name"]['title']." ".$_POST["profile_field"]["cct-name"]['first']." ".$_POST["profile_field"]["cct-name"]['last']." ".$_POST["profile_field"]["cct-name"]['prefix'];
			else:
				$userdata = get_userdata($data['post_author']);
				$data['post_title'] =$userdata->user_nicename;
			endif;
		
		if(is_array($_POST["profile_field"]))
			$data['post_content_filtered'] =  serialize( $_POST["profile_field"] );
		return $data;
		/*
		die();
		*/
	
	}
	
	 /**
	  * fields function.
	  * 
	  * @access public
	  * @return void
	  */
	 function fields()
	 {
	 	return array("name", "address","phone","fax","email","website","position","bio","education","publications","research","teaching","blog","twitter","facebook","linkedin","delicious","flickr","google-plus","text","textarea","social");
	 }
	 
	 /**
	  * default_fields function.
	  * the fields that are displayed when there is no more 
	  * @access public
	  * @return void
	  */
	 function default_fields($type = 'form')
	 {
	 	if($type == 'page' && isset( $this->form_fields['fields'])):
	 		return $this->form_fields['fields'];
	 	else:
		 	return 	array(
				 		array(
					 		array( "type"=> "cct-address", 		"label"=> "address",),
					 		array( "type"=> "cct-phone",		"label"=> "phone" ), 
					 		array( "type"=> "cct-fax",			"label"=> "fax" ), 
					 		array( "type"=> "cct-email",		"label"=> "email" ),
					 		array( "type"=> "cct-website",		"label"=> "website")),
				 		array(
				 			array( "type"=> "cct-position" ,	"label"=> "position" ), 
					 		array( "type"=> "cct-bio",			"label"=> "bio" ), 
					 		array( "type"=> "cct-education", 	"label"=> "education" ), 
					 		array( "type"=> "cct-teaching",		"label"=> "teaching" ), 
					 		array( "type"=> "cct-publications",	"label"=> "publications" ), 
					 		array( "type"=> "cct-research",		"label"=> "research" )), 
				 		array(
					 		array( "type"=> "cct-twitter",		"label"=> "twitter" ),
					 		array( "type"=> "cct-blog",			"label"=> "blog" ), 
					 		array( "type"=> "cct-facebook",		"label"=> "facebook" ), 
					 		array( "type"=> "cct-linkedin",		"label"=> "linkedin" ), 
					 		array( "type"=> "cct-delicious",	"label"=> "delicious" ), 
					 		array( "type"=> "cct-flickr",		"label"=> "flickr" ), 
					 		array( "type"=> "cct-google-plus",	"label"=> "google-plus" ))
				 	); 
		 endif;

	 }
	 
	 
	 function start_field($field_type,$action, $options ) {
	 	extract( $options );
	 	?>
	 	<li class="<?php echo $field_type; ?> field-item" for="cct-<?php echo $field_type; ?>">
	 	<?php 
	 	if($action == 'edit'): ?>
		 	<a href="#remove-field" class="remove">Remove</a>
			<a href="#edit-field" class="edit">Edit</a>
			<div class="edit-shell" style="display:none;">
				<?php 
					$this->label_field( array('size'=>10, 'value'=>$label, 'name'=>'label','title'=>'label'));
					if(isset($description))
				 	$this->textarea_field();				
					if(isset($show_fields))
						$this->select_field(array('type'=>'multiple','all_fields'=>$show_fields,'selected_fields'=>$show,'name'=>'show[]')); 				
				$this->default_value(); ?>
			</div>
		<?php 	
		endif;
	 	?>
	 	<label for="" id="" class="desc"><?php echo $label; ?></label>
	 	<?php 
	 }
	
	 function end_field()
	 {
	 	echo "</li>";
	 }
	 
	 function label_field( $options ){
	 	
	 	extract( $options );
	 	
	 	
	 	$name = ( isset($name)? ' name="'.$name.'"': '');
	 	$size = ( isset($size)? ' size="'.$size.'"': '');
	 	$class = ( isset($class)? ' class="'.$class.'"': ' class="field text"');
	 	?>
	 	<span>
			<input type="text" <?php echo $size.$class; ?> value="<?php echo esc_attr($value); ?>" id="">
			<label for="" ><?php echo $title; ?></label>
		</span>
	 	<?php 
	 }
	 function input_field()
	 {
	 
	 }
	 function select_field($options)
	 {
	 	$name = ( isset($name)? ' name="'.$name.'"': '');
	 	$title = ( isset($title)? ' <label for="" >'.$name.'</label>': '');
	 	extract( $options );
	 	switch($type){
	 		case "multiple":
	 				?><div class="">
	 				<?php echo $title; ?>
	 				<?php
	 				foreach($all_fields as $field): ?>
	 					<label><input type="checkbox" <?php checked( in_array($field,$selected_fields) ); ?> value="<?php echo $field; ?>" /> <?php echo $field; ?></label>
	 				<?php
	 				endforeach;
	 				?>
	 				</div>
	 				<?php 
	 		break;
	 	
	 	
	 	}
	 
	 }
	 function textarea_field(){
	 
	 }
	 
	 function default_value(){
	 	
	 }
	 
	/**
	 * add_field function.
	 * function return by ajax to be displayed
	 * @access public
	 * @return void
	 */
	function update_fields()
 	{	
 		$this->form_fields = get_option('Profile_CCT_form_fields');
 		
 		switch( $_POST['method'] ){
 			
 			case 'add':
				$this->form_fields['fields'][ $_POST['id'] ][] = 
						array( 
							'type'  => $_POST['type'] , 
							'label' => substr( $_POST['type'], 4)  
							);
				
				$this->show_field('form',$_POST['type'],substr( $_POST['type'], 4), 'edit');
 			break;
 			
 			case 'remove':
 				unset( $this->form_fields['fields'][ $_POST['id'] ][ $_POST['index'] ] );
 				// reorder the items again
 				foreach($this->form_fields['fields'][ $_POST['id'] ] as $item ):
 					$items[] = $item;
 				endforeach;
 				
 				$this->form_fields['fields'][ $_POST['id'] ] = $items;

 				echo "removed";
 			break;
 			
 			case "update":
 				$this->form_fields['fields'][ $_POST['id'] ][$_POST['index']]['label'] = $_POST['label'];
 				echo "updated";
 			break;
 			
 			case "sort":
 				$i = 0;
 				foreach( $_POST['types'] as $type):
 					$items[] =  array( 
							'type'  => $type , 
							'label' => $_POST['labels'][$i]  
							);
					$i++;
 				endforeach;
 				$this->form_fields['fields'][ $_POST['id'] ] = $items;
 				echo "sorted";
 			break;
 		}
 		
 		// save the opions
		update_option('Profile_CCT_form_fields', $this->form_fields);
		
		die();
		
	}
	/**
	 * show_field function.
	 * 
	 * @access public
	 * @param mixed $type
	 * @param mixed $field_type
	 * @param mixed $action. (default: NULL)
	 * @param int $order. (default: 0)
	 * @param mixed $label. (default: NULL)
	 * @return void
	 */
	function show_field($type,$field_type,$label,$options,$action=NULL,$order=0)
	{	
		$raw_field_type = substr($field_type,4);
		
		if(!in_array($raw_field_type,$this->fields())) // for security 
			return false;
		
		?>
		<li class="<?php echo $raw_field_type; ?> field-item" for="<?php echo $field_type; ?>">
		
		<?php
		if($action=="edit"):
			if( $label =="" || $label == NULL)
				$label = $raw_type;
		?>
			
			<a href="#remove-field" class="remove">Remove</a>
			<a href="#edit-field" class="edit">Edit</a>
			<div class="edit-shell" style="display:none;">
			
				
				
				<?php $this->show_edit_field($type,$field_type,$label,$raw_field_type, $options); ?>
			</div>
		<?php 
		endif;
		echo '<label for="" id="" class="desc">'.$label.'</label>';
		
		// big switch statment
		$this->show_raw_field($type,$field_type,$action);
		
		echo "</li>";
		
	}
	/**
	 * show_raw_field function.
	 * 
	 * @access public
	 * @param mixed $type. (default: NULL)
	 * @param mixed $action. (default: NULL)
	 * @return void
	 */
	function show_raw_field($type,$field_type=NULL,$action=NULL)
	{	
		global $post;
		
		$post_form = false;
		if(is_object($field_type)): // we need to do this when we are passing in the value from 
			$type = "cct-".$action["args"];
			$post_form = true;
			
			echo "<div class='{$action["args"]}'>";
		endif;
		switch($type){
			case "form":
				require('views/form-fields.php');
			break;
			case "page":
				require('views/page-fields.php');
			break;
		
		}
		if($post_form)
			echo "</div>";
	}
	function show_name_field($action){
		$type="form";
		$label="name";
		$field_type = "cct-name";
		echo "<ul class='form-builder' id='form-name' >";
			?>
		<li class="<?php echo $raw_field_type; ?> field-item" for="<?php echo $field_type; ?>">
		
		<?php
		if($action=="edit"): 
			if( $label =="" || $label == NULL)
				$label = $raw_type;
		?>
			<a href="#edit-field" class="edit">Edit</a>
			<div class="edit-shell" style="display:none;">
				<?php $this->show_edit_field($type,$field_type,$options); ?>
				
			</div>
		<?php 
		endif;
		echo '<label for="" id="" class="desc">'.$label.'</label>';
		
		// big switch statment
		$this->show_raw_field($type,$field_type,$action);
		
		echo "</li>";
		echo "</ul>";
	}
	
	function social_fields(){
		return array(
					array( 	"type"=> "ubc-blog", 	
							"label"=> "UBC Blog", 
							"service_url" =>"http://blogs.ubc.ca/",	
							"user_url"=> "http://blogs.ubc.ca/{value}"),
					array( 	"type"=> "ubc-wiki", 	
							"label"=> "UBC Wiki",
							"service_url" =>"http://wiki.ubc.ca/",		
							"user_url"=> "http://wiki.ubc.ca/User:{value}"),
			 		array( 	"type"=> "twitter", 		
			 				"label"=> "Twitter",
			 				"service_url" =>"http://twitter.com",			
			 				"user_url"=> "http://twitter.com/#!/{value}"),
			 		array( 	"type"=> "facebook",		
			 				"label"=> "Facebook",
			 				"service_url" =>"http://www.facebook.com/",			
			 				"user_url"=> "http://www.facebook.com/{value}" ),
			 		array( 	"type"=> "google-plus", 	
			 				"label"=> "Google Plus",
			 				"service_url" =>"http://plus.google.com",		
			 				"user_url"=> "http://plus.google.com/{value}"),
			 		array( 	"type"=> "linked-in",	
			 				"label"=> "Linked In",
			 				"service_url" =>"http://www.linkedin.com/",			
			 				"user_url"=> "http://www.linkedin.com/in/{value}" ), 
			 		array( 	"type"=> "delicious",	
			 				"label"=> "Delicious",
			 				"service_url" =>"http://www.delicious.com",			
			 				"user_url"=> "http://www.delicious.com/{value}" ),
			 		array( 	"type"=> "picasa",		
			 				"label"=> "Picasa",
			 				"service_url" =>"http://picasaweb.google.com",
			 				"user_url"=> "http://picasaweb.google.com/{value}"),
			 		array(  "type"=> "flickr",		
			 				"label"=> "Flickr",
			 				"service_url" =>"",				
			 				"user_url"=> "http://www.flickr.com/photos/{value}"),
			 		array( 	"type"=> "tumblr",		
			 				"label"=> "Tumblr",
			 				"service_url" =>"http://tumblr.com",			
			 				"user_url"=> "http://{value}.tumblr.com"), 
			 		array( 	"type"=> "blogger",		
			 				"label"=> "Blogger",
			 				"service_url" =>"http://blogspot.com/",			
			 				"user_url"=> "http://{value}.blogspot.com/"), 
			 		array( 	"type"=> "posterous",	
			 				"label"=> "Posterous",
			 				"service_url" =>"http://posterous.com",	
			 				"user_url"=> "http://{value}.posterous.com"),
			 		array( 	"type"=> "wordpress-com",
			 				"label"=> "WordPress.com",
			 				"service_url" =>"http://wordpress.com",	
			 				"user_url"=> "http://{value}.wordpress.com"),
			 		array( 	"type"=> "youtube",		
			 				"label"=> "YouTube",
			 				"service_url" =>"http://youtube.com/",		
			 				"user_url"=> "http://youtube.com/{value}"),
			 		array( 	"type"=> "vimeo",		
			 				"label"=> "Vimeo",
			 				"service_url" =>"http://vimeo.com",			
			 				"user_url"=> "http://vimeo.com/{value}"),
			 		array( 	"type"=> "slideshare",		
			 				"label"=> "SlideShare",
			 				"service_url" =>"http://www.slideshare.net/",			
			 				"user_url"=> "http://www.slideshare.net//{value}"),
			 		);
	}
	function show_edit_field($type,$field_type,$options){
		switch($type){
			case "form":
				require('views/form-edit-fields.php');
			break;
			case "page":
				require('views/page-edit-fields.php');
			break;
		
		}
		
		
	}
	
	public function field_field_tab_index(){
		$this->tab_index++;
		echo $this->tab_index;
	}
		
	
	
	/* ============== TABS =============================================== */
	/**
	 * default_tabs function.
	 * 
	 * @access public
	 * @param string $type. (default: 'form')
	 * @return void
	 */
	function default_tabs($type = 'form')
	{
	 	if($type == 'page' && isset( $this->form_fields['tabs']))
	 		return $this->form_fields['tabs'];
	 	else
	 		return array( "Basic Info", "Bio","Social" );
	}
	/**
	 * show_tabs function.
	 * 
	 * @access public
	 * @param mixed $type
	 * @param mixed $action
	 * @return void
	 */
	function show_tabs($type,$action) {
		switch($type){
			case 'form':
				$fields = $this->form_fields;
			break;
			case 'page':
				$fields = $this->page_fields;
			break;
		}
		if( !$fields['tabs'] ) 
			$fields['tabs'] = $this->default_tabs($type);
			
		if( !$fields['fields'] ) 
			$fields['fields'] = $this->default_fields($type);
			
		
		?>
		<div id="tabs">
		<ul>
			<?php 
			$count = 1;
			foreach( $fields['tabs'] as $tab) : ?>
				<li><a href="#tabs-<?php echo $count; ?>" class="tab-link"><?php echo $tab; ?></a>  <span class="remove-tab">Remove Tab</span> <span class="edit-tab">Edit</span><input type="text" class="edit-tab-input" value="<?php echo esc_attr($tab); ?>" /></li>
			<?php 
				$count++;
			endforeach; ?>
			<li id="add-tab-shell"><a href="#add-tabshell" id="add-tab" title="Add Tab">Add Tab</a></li>
		</ul>
		<?php 
		$count = 1;
		foreach( $fields['tabs'] as $tab) :
		?>
			<div id="tabs-<?php echo $count?>">
				<input type="hidden" name="form_field[tabs][]" value="<?php echo esc_attr($tab); ?>" />
				<ul class="connectedSortable sortable ui-helper-reset form-builder sort dropzone ">
				<?php 
				$i =0;
				if(is_array($fields['fields']) && is_array($fields['fields'][$count-1])):
					foreach( $fields['fields'][$count-1] as $field):
					 	$this->show_field($type, $field['type'], $field['label'],$field['options'], "edit", $i ); $i++;
					endforeach;
				endif;
				?>
				</ul>
			</div>
			<?php 
			$count++;
		endforeach; ?>
		<div id="add-tabshell"></div>
		</div>
		
		<?php 
	}
	
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
		
		$fields = get_option('Profile_CCT_'.$type.'_fields');
		
		if( empty($fields['tabs']) ){
			$fields['tabs'] = $this->default_tabs($type);
		}
	
		switch($_POST['method']) {
			
			case "update":
				$fields['tabs'][$_POST['i']] = $_POST['title'];
				echo "updated";
			break;
			
			case "remove":
				unset( $fields['tabs'][ $_POST['i'] ] );
				unset( $fields['fields'][ $_POST['i'] ] );
				foreach($fields['tabs'] as $tab):
					$tabs[] = $tab; // reset the pointer
				endforeach;
				
				foreach( $fields['fields'] as $item):
					$fields[] = $item; // reset the pointer
				endforeach;
				$fields['tabs'] = $tabs;
				$fields['fields'] = $fields;
				echo "removed";
			break;
			
			case "add":
				
				$fields['tabs'][] = $_POST['title'];
				echo "added";
			break;
		}
		
		switch($type){
			case 'page': 
				$this->page_fields = $fields;
			break;
			case 'form':
				$this->form_fields = $fields;
			break;
		
		
		}
		
		update_option('Profile_CCT_'.$type.'_fields', $fields);
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
	function validate_form_fields($input)
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
	function validate_page_fields($input)
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
	function stripslashes_deep($value)
	{
	    $value = is_array($value) ?
	                array_map('stripslashes_deep', $value) :
	                stripslashes($value);
	    return $value;
	}
} // end class

if ( function_exists( 'add_action' ) && class_exists( 'Profile_CCT' ) )
add_action( 'plugins_loaded', array( 'Profile_CCT', 'get_object' ) );



