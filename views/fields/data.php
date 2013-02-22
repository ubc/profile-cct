<?php 
Class Profile_CCT_Data extends Profile_CCT_Field {
	var $default_options = array(
		'type'          => 'data',
		'label'         => 'data',
		'description'   => '',
		'multiple'      => false,
		'show_multiple' => false,
		'url_prefix'    => '',
	);
	
	function field() {
		$this->input_text( array(
			'field_id' => 'url',
			'label'    => 'Website - '.$this->options['url_prefix'],
			'size'     => 35,
			'value'    => $this->data['url'],
		) );
	}
	
	function display() {
		require_once( PROFILE_CCT_DIR_PATH.'inc/simple_html_dom.php' );
		
		if ( empty( $this->data['url'] ) ):
			$this->display_text( array(
				'field_id'     => 'external-data',
				'class'        => 'external-data',
				'default_text' => '[External Data Feed]',
				'value'        => '',
			) );
		else:
			$profile = Profile_CCT::get_object();
			$url = 'http://'.$profile->settings['data_url'][$this->type].$this->data['url'];
			
			// Attempt to get page
			if ( $html = file_get_html($url) ):
				$html_body = $html->find( 'body', 0 );
				
				//Don't output undesirable elements
				$bad_elements = $html_body->find( 'script, iframe' );
				foreach ( $bad_elements as $element ):
					$element->outertext = '';
				endforeach;
				
				//should we treat onclick/onmouseover/etc event tags as possibly malicious?
				//$bad_atts = $html_body->find('');
				
				echo $html_body;
				$html->clear();
			else:
				echo "Couldn't access external data";
				error_log( "Couldn't access external data" );
				error_log( print_r( $this->type, TRUE ) );
				error_log( print_r( $profile->settings['data_url'], TRUE ) );
			endif;
		endif;
	}

    public static function shell( $options, $data ) {
		new Profile_CCT_Data( $options, $data );
    }
}

function profile_cct_data_shell( $options, $data ) {
    Profile_CCT_Data::shell( $options, $data );
}