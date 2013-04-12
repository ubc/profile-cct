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
	
	var $shell = array(
		'class' => 'social-link',
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
			'value'      => $this->data['option'],
			'all_fields' => $this->social_options('label'),
			'type'       => 'select',
			'count'      => $count,
		) );
		$this->input_text( array(
			'field_id'   => 'username',
			'label'      => ( isset( $this->data['option'] ) ? $this->social_options( 'user_url', $this->data['option'] ) : "" ),
			'value'      => $this->data['username'],
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
		$this->display_social_link( array(
			'field_id' => 'username',
		) );
	}
	
	function display_social_link( $attr ) {
		
		if ( isset( $this->data ) ):
			$service = $this->social_options( 'all',  $this->data['option'] );
			
			if ( ! empty($this->data['username']) ):
				$attr['href'] = str_replace( '{value}', $this->data['username'], $service['user_url'] );
				$attr['value'] = '<img src="'.PROFILE_CCT_DIR_URL.'/img/'.$service['type'].'.png" /><strong>'.$this->data['option'].'</strong>/ '.$this->data['username'];
			endif;
			$this->display_link( $attr );
		else:
			// only display this when editing
			if( 'edit' == $this->action ):
				$defaults = array(
					array(
						'field_id'     => $attr['field_id'],
						'href'         => "http://www.youtube.com/",
						'default_text' => '<img src="'.PROFILE_CCT_DIR_URL.'/img/youtube.png" /> <strong>YouTube</strong>/ wayneenterprise<br />',
					),
					array(
						'field_id'     => $attr['field_id'],
						'href'         => "http://www.facebook.com/",
						'default_text' => '<img src="'.PROFILE_CCT_DIR_URL.'/img/facebook.png" /> <strong>Facebook</strong>/ waynebiz<br />',
					),
					array(
						'field_id'     => $attr['field_id'],
						'href'         => "http://www.twitter.com/",
						'default_text' => '<img src="'.PROFILE_CCT_DIR_URL.'/img/twitter.png" /> <strong>Twitter</strong>/ waynepr<br />',
					),
				);
				
				foreach ( $defaults as $default ):
					$this->display_link( $default );
				endforeach;
			endif;
		endif;
	}
	
	/**
	 * social_options function.
	 * 
	 * @access public
	 * @return void
	 */
	function social_options( $what = 'all', $type = null ) {
		$all = array(
			array( 	"type"        => "ubc-blog", 	
					"label"       => "UBC Blog", 
					"service_url" => "http://blogs.ubc.ca/",	
					"user_url"    => "http://blogs.ubc.ca/{value}" ),
			array( 	"type"        => "ubc-wiki", 	
					"label"       => "UBC Wiki",
					"service_url" => "http://wiki.ubc.ca/",		
					"user_url"    => "http://wiki.ubc.ca/User:{value}" ),
			array( 	"type"        => "twitter", 		
					"label"       => "Twitter",
					"service_url" => "http://twitter.com/",			
					"user_url"    => "http://twitter.com/{value}" ),
			array( 	"type"        => "facebook",		
					"label"       => "Facebook",
					"service_url" => "http://www.facebook.com/",			
					"user_url"    => "http://www.facebook.com/{value}" ),
			array( 	"type"        => "google-plus", 	
					"label"       => "Google+",
					"service_url" => "http://plus.google.com/",		
					"user_url"    => "http://plus.google.com/{value}" ),
			array( 	"type"        => "linked-in",	
					"label"       => "LinkedIn",
					"service_url" => "http://www.linkedin.com/",			
					"user_url"    => "http://www.linkedin.com/in/{value}" ), 
			array( 	"type"        => "delicious",	
					"label"       => "Delicious",
					"service_url" => "http://www.delicious.com/",			
					"user_url"    => "http://www.delicious.com/{value}" ),
			array( 	"type"        => "picasa",		
					"label"       => "Picasa",
					"service_url" => "http://picasaweb.google.com/",
					"user_url"    => "http://picasaweb.google.com/{value}" ),
			array(  "type"        => "flickr",		
					"label"       => "Flickr",
					"service_url" => "http://www.flickr.com/",				
					"user_url"    => "http://www.flickr.com/photos/{value}" ),
			array( 	"type"        => "tumblr",		
					"label"       => "Tumblr",
					"service_url" => "http://tumblr.com/",			
					"user_url"    => "http://{value}.tumblr.com" ), 
			array( 	"type"        => "blogger",		
					"label"       => "Blogger",
					"service_url" => "http://blogspot.com/",			
					"user_url"    => "http://{value}.blogspot.com/" ), 
			array( 	"type"        => "posterous",	
					"label"       => "Posterous",
					"service_url" => "http://posterous.com/",	
					"user_url"    => "http://{value}.posterous.com" ),
			array( 	"type"        => "wordpress-com",
					"label"       => "WordPress.com",
					"service_url" => "http://wordpress.com/",	
					"user_url"    => "http://{value}.wordpress.com" ),
			array( 	"type"        => "youtube",		
					"label"       => "YouTube",
					"service_url" => "http://youtube.com/",		
					"user_url"    => "http://youtube.com/{value}" ),
			array( 	"type"        => "vimeo",		
					"label"       => "Vimeo",
					"service_url" => "http://vimeo.com/",			
					"user_url"    => "http://vimeo.com/{value}" ),
			array( 	"type"        => "wikipedia",		
					"label"       => "Wikipedia",
					"service_url" => "http://wikipedia.org/",			
					"user_url"    => "http://wikipedia.org/wiki/User:{value}" ),
			array( 	"type"        => "slideshare",		
					"label"       => "SlideShare",
					"service_url" => "http://www.slideshare.net/",			
					"user_url"    => "http://www.slideshare.net/{value}" ),
		);
		
		if ( $what == 'all' && $type == null ):
			return $all;
		else:
			$return = array();
			
			$what_is_valid = in_array( $what, array( 'type', 'label', 'service_url', 'user_url' ) );
			foreach ( $all as $service ):
				if ( $type == null ):
					if ( $what_is_valid ):
						$return[] = $service[$what];
					else:
						$return[] = $service;
					endif;
				elseif ( $service['label'] == $type ):
					if ( $what_is_valid ):
						return $service[$what];
					else:
						return $service;
					endif;
				endif;
			endforeach;
			
			return $return;
		endif;
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