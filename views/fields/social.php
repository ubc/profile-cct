<?php 

function profile_cct_social_field_shell( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'social',
		'label'=>'social',
		
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
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'option', 'label'=>'Site',  'value'=>$data['option'], 'all_fields'=>$social_array, 'type'=>'select','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'username', 'label'=>$social_array_details[$data['option']]['user_url'],  'value'=>$data['username'], 'all_fields'=>$social_array, 'type'=>'text','count'=>$count) );
	
	
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";


}

function profile_cct_social_display_shell(  $action, $options, $data=null  ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'social',
		'label'=>'social',
		
		'before'=>'',
		'empty'=>'',
		'after'=>'',
		'width' => 'full',
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	
	if(!isset($data)){
		//default placeholder data for sample page view
		$data = array(
			array('option'=>'Facebook','username'=>'BruceWayne'),
			array('option'=>'Twitter','username'=>'bwayne'),
			array('option'=>'YouTube','username'=>'bruce'),
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
	
	if(empty($data['option']))return;
	
	extract( $options );
	$field = Profile_CCT::get_object();
	
	
	//make an associative array from the social-options
	$social_array_options = profile_cct_social_options();
	foreach($social_array_options as $social_item):
		$social_array[$social_item['label']] =  $social_item;
	endforeach;
	
	
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'social', 'type'=>'shell', 'tag'=>'div') );
	$user_url = $social_array[$data['option']]['user_url'];
	$img_path = plugins_url() . '/profile-cct/img/';
	
	echo '<img src="' . $img_path . $social_array[$data['option']]['type'] . '.png" class="icon" />';
	
	$field->display_text( array( 
		'field_type'=>$type,  
		'class'=>'type', 

		'value'=>'<strong>' . $data['option'] . '</strong>/' . $data['username'], 
		'type'=>'text', 'href'=>str_replace('{value}',$data['username'], $user_url), 
		'tag'=>'a', 
	));
	
	$field->display_text( array( 'field_type'=>$type, 'type'=>'end_shell', 'tag'=>'div') );
	
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
			 				"user_url"=> "http://www.slideshare.net//{value}"),
			 		);
	}
	
