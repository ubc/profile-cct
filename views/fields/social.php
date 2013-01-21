<?php 
Class Profile_CCT_Social extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'social',
		'label'         => 'social',	
		'description'   => '',
		'multiple'      => true,
		'show_multiple' => true,
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_select( array(
			'field_id'   => 'option',
			'label'      => 'Site',
			'value'      => $data['option'],
			'all_fields' => $this->social_options( 'label' ),
			'type'       => 'select',
			'count'      => $count,
		) );
		$this->input_text( array(
			'field_id'   => 'usersocial',
			'label'      => $social_array_details[$data['option']]['user_url'],
			'value'      => $data['usersocial'],
			'all_fields' => $social_array,
		) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_shell( array( 'class' => 'social-link' ) );
		$this->display_social_link( array(
			'field_id' => 'usersocial',
		) );
		$this->display_end_shell();
	}
	
	/**
	 * shell function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $options
	 * @param mixed $data
	 * @return void
	 */
	public static function shell( $options, $data ) {
		new Profile_CCT_Social( $options, $data ); 
	}
	
	/**
	 * social_options function.
	 * 
	 * @access public
	 * @return void
	 */
	function social_options( $what = 'all' ) {
		$all = array(
			array( 	"type"        => "ubc-blog", 	
					"label"       => "UBC Blog", 
					"service_url" => "http://blogs.ubc.ca/",	
					"user_url"    => "http://blogs.ubc.ca/{value}"),
			array( 	"type"        => "ubc-wiki", 	
					"label"       => "UBC Wiki",
					"service_url" => "http://wiki.ubc.ca/",		
					"user_url"    => "http://wiki.ubc.ca/User:{value}"),
			array( 	"type"        => "twitter", 		
					"label"       => "Twitter",
					"service_url" => "http://twitter.com",			
					"user_url"    => "http://twitter.com/#!/{value}"),
			array( 	"type"        => "facebook",		
					"label"       => "Facebook",
					"service_url" => "http://www.facebook.com/",			
					"user_url"    => "http://www.facebook.com/{value}" ),
			array( 	"type"        => "google-plus", 	
					"label"       => "Google Plus",
					"service_url" => "http://plus.google.com/",		
					"user_url"    => "http://plus.google.com/{value}"),
			array( 	"type"        => "linked-in",	
					"label"       => "Linked In",
					"service_url" => "http://www.linkedin.com/",			
					"user_url"    => "http://www.linkedin.com/in/{value}" ), 
			array( 	"type"        => "delicious",	
					"label"       => "Delicious",
					"service_url" => "http://www.delicious.com",			
					"user_url"    => "http://www.delicious.com/{value}" ),
			array( 	"type"        => "picasa",		
					"label"       => "Picasa",
					"service_url" => "http://picasaweb.google.com",
					"user_url"    => "http://picasaweb.google.com/{value}"),
			array(  "type"        => "flickr",		
					"label"       => "Flickr",
					"service_url" => "http://www.flickr.com/",				
					"user_url"    => "http://www.flickr.com/photos/{value}"),
			array( 	"type"        => "tumblr",		
					"label"       => "Tumblr",
					"service_url" => "http://tumblr.com",			
					"user_url"    => "http://{value}.tumblr.com"), 
			array( 	"type"        => "blogger",		
					"label"       => "Blogger",
					"service_url" => "http://blogspot.com/",			
					"user_url"    => "http://{value}.blogspot.com/"), 
			array( 	"type"        => "posterous",	
					"label"       => "Posterous",
					"service_url" => "http://posterous.com",	
					"user_url"    => "http://{value}.posterous.com"),
			array( 	"type"        => "wordpress-com",
					"label"       => "WordPress.com",
					"service_url" => "http://wordpress.com",	
					"user_url"    => "http://{value}.wordpress.com"),
			array( 	"type"        => "youtube",		
					"label"       => "YouTube",
					"service_url" => "http://youtube.com/",		
					"user_url"    => "http://youtube.com/{value}"),
			array( 	"type"        => "vimeo",		
					"label"       => "Vimeo",
					"service_url" => "http://vimeo.com",			
					"user_url"    => "http://vimeo.com/{value}"),
			array( 	"type"        => "slideshare",		
					"label"       => "SlideShare",
					"service_url" => "http://www.slideshare.net/",			
					"user_url"    => "http://www.slideshare.net/{value}"),
		);
		
		switch ( $what ):
			case 'all':
				return $all;
				break;
			default:
				if ( in_array( $what, array( 'type', 'label', 'service_url', 'user_url' ) ) ):
					foreach ( $all as $service ):
						$build_array[] = $service[$what];
					endforeach;
					
					return $build_array;
				endif;
				return array();
				break;
		endswitch;
	}
	
}

/**
 * profile_cct_social_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_social_shell( $options, $data ) {
	Profile_CCT_Social::shell( $options, $data );
}

/*
function profile_cct_social_display_shell( $options, $data ) {
	
	Profile_CCT_Social::shell( $options, $data );
	
}

function profile_cct_social_shell_old( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'social',
		'label' => 'social',
		'description' => '',
		'multiple'=>true,
		'show_multiple' =>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			
			profile_cct_social_field($item_data,$options,$count);
			$count++;
		endforeach;
	else:
		profile_cct_social_field($data,$options);
	endif;
	
	$field->end_field( $action, $options );
}
function profile_cct_social_field( $data, $options, $count = 0 ){

	extract( $options );	
	$field = Profile_CCT::get_object();
	$social_array_options = profile_cct_social_options();
	$social_array_details = array();
	foreach($social_array_options as $social_item):
		$social_array[] =  $social_item['label'];
		$social_array_details[$social_item['label']] =  $social_item;	
	endforeach;

	
	echo "<div class='wrap-fields wrap-social-fields' data-count='".$count."'>";
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'option', 'label' => 'Site',  'value'=>$data['option'], 'all_fields'=>$social_array, 'type' => 'select','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'usersocial', 'label'=>$social_array_details[$data['option']]['user_url'],  'value'=>$data['usersocial'], 'all_fields'=>$social_array, 'type' => 'text','count'=>$count) );
	
	
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";


}

function profile_cct_social_display_shell_old(  $action, $options, $data=null  ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'social',
		'label' => 'social',
		
		'before' => '',
		'empty' => '',
		'after' => '',
		'width' => 'full',
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);

	if($action=="edit"){
		//default placeholder data for sample page view
		$data = array(
			array('option' => 'Facebook','usersocial' => 'BruceWayne'),
			array('option' => 'Twitter','usersocial' => 'bwayne'),
			array('option' => 'YouTube','usersocial' => 'bruce'),
		);
	}
	
	if( $field->is_data_array( $data ) ):
		foreach($data as $item_data):
			profile_cct_social_display($item_data,$options);
		endforeach;
	else:
		profile_cct_social_display($data,$options);
	endif;
	
	$field->end_field( $action, $options );
}


function profile_cct_social_display( $data, $options ){
	
	if(empty($data['option'])):
		echo $options['empty'];
		return;
	endif;
	
	extract( $options );
	$field = Profile_CCT::get_object();
	
	
	//make an associative array from the social-options
	$social_array_options = profile_cct_social_options();
	foreach($social_array_options as $social_item):
		$social_array[$social_item['label']] =  $social_item;
	endforeach;
	
	
	
	$field->display_text( array( 'field_type'=>$type, 'class' => 'social', 'type' => 'shell', 'tag' => 'div') );
	$user_url = $social_array[$data['option']]['user_url'];
	$img_path = PROFILE_CCT_DIR_URL . '/img/';
	
	echo '<img src="' . $img_path . $social_array[$data['option']]['type'] . '.png" class="icon" />';
	
	$field->display_text( array( 'field_type'=>$type, 'class' => 'social-link', 'type' => 'shell', 'link_to'=>true,'tag' => 'a', 'href'=>str_replace('{value}',$data['usersocial'], $user_url)) );
	
	$field->display_text( array( 'field_type'=>$type, 'type' => 'text', 'tag' => 'strong', 'value'=>$data['option'])  );
	
	$field->display_text( array( 'field_type'=>$type, 'type' => 'text', 'tag' => 'span', 'separator' => '/', 'value'=>$data['usersocial']) );
	$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'a','link_to'=>true) );
	$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'div') );
	
}


function profile_cct_social_options(){
		return array(
					array( 	"type"=> "", 	
							"label"=> "", 
							"service_url" =>"",	
							"user_url"=> ""),
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
			 				"user_url"=> "http://www.slideshare.net/{value}"),
			 		);
	}
	
*/