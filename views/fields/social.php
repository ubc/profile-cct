<?php 

function profile_cct_social_field_shell($action,$options) {
	
	$field = Profile_CCT::set(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'social',
		'label'=>'social',
		'description'=>'',
		'multiple'=>true
		);
	$options = (is_array($options) ? array_merge($options,$default_options): $default_options );
	
	
	$field->start_field('social',$action,$options);
	
	profile_cct_social_field($data,$options);
	
	$field->end_field($options);
	
}
function profile_cct_social_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::set();
	$social_array_options = profile_cct_social_options();
	foreach($social_array_options as $social_item):
		$social_array[] =  $social_item['label'];
	endforeach;
	$field->input_field( array( 'field_id'=>'option', 'label'=>'option',  'value'=>$data['option'], 'all_fields'=>$social_array, 'type'=>'select') );



}

function profile_cct_social_options(){
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