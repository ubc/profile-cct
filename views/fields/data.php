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
			'name'     => 'url',
			'label'    => 'Website - '.$this->options['url_prefix'],
			'size'     => 35,
			'value'    => $this->data['url'],
		) );
		//$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'url_prefix', 'label' => '', 'value'=>$url_prefix, 'type' => 'hidden','count'=>$count, ) );
		//if($count) echo ' <a class="remove-fields button" href="#">Remove</a>';
	}
	
	function display() {
		require_once( PROFILE_CCT_DIR_PATH.'inc/simple_html_dom.php' );
		
		extract( $options );
			
		if ( empty( $this->data['url'] ) ):
			$this->display_text( array(
				'field_id'     => 'external-data',
				'class'        => 'external-data',
				'default_text' => '[External Data Feed]',
				'value'        => '',
			) );
		else:
			$url_prefix = $this->options['url_prefix'];
			$url = $url_prefix.$data['url'];
			
			//attempt to get page
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
			endif;
		endif;
		
		$field->display_text( array(
			'field_type' => $type,
			'type'       => 'end_shell',
			'tag'        => 'div',
		) );
	}

    public static function shell( $options, $data ) {
		new Profile_CCT_Data( $options, $data );
    }
}

function profile_cct_data_shell( $options, $data ) {
    Profile_CCT_Data::shell( $options, $data );
}

/*
function profile_cct_data_display_shell( $action, $options, $data = null ) {
	if ( is_object($action) ):
		$post    = $action;
		$action  = "display";
		$data    = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object();
	
	$default_options = array(
		'type'       => 'data',
		'width'      => 'full',
		'before'     => '',
		'empty'      => '',
		'after'      => '',
		'hide_label' => true,
	);
	$options = ( is_array( $options ) ? array_merge( $default_options, $options ) : $default_options );
	
	if ( ! $field->is_array_empty( $data ) || $action == "edit" ):
		$field->start_field( $action, $options );
		
		if ( $field->is_data_array( $data ) ):
			
			foreach( $data as $item_data ):
				if ( ! $field->is_array_empty( $item_data ) || $action == "edit" ):
					profile_cct_data_display( $item_data, $options );
				endif;
			endforeach;
			
		else:
			profile_cct_data_display( $data, $options );
		endif;
		
		$field->end_field( $action, $options );
	else:
		echo $options['empty'];
	endif;
}
*/