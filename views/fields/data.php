<?php 

function profile_cct_data_field_shell( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'data',
		'label'=>'data',
		'description'=>'',
		'multiple'=>false,
		'show_multiple'=>false,
		'url_prefix'=>'',

		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			profile_cct_data_field($item_data,$options,$count);
			$count++;
		endforeach;
		
	else:
		profile_cct_data_field($data,$options);
	endif;
	$field->end_field( $action, $options );
	
	
}
function profile_cct_data_field( $data, $options, $count = 0 ){
	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());

	$settings = get_option('Profile_CCT_settings');
	$url_prefix = $settings['data-url'][$type];
	
	echo "<div class='wrap-fields' data-count='".$count."'>";
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'url', 'label'=>'Website - '.$url_prefix, 'size'=>35, 'value'=>$data['url'], 'type'=>'text','count'=>$count, ) );
	//$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'url_prefix', 'label'=>'', 'value'=>$url_prefix, 'type'=>'hidden','count'=>$count, ) );
	//if($count)
	// 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}




function profile_cct_data_display_shell(  $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'data',
		'width' => 'full',
		'before'=>'',
		'empty'=>'',
		'after' =>'',
		'hide_label'=>true,
		//'url_prefix'=>'dsfsdf',
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	if( !$field->is_array_empty($data) ||  $action == "edit" ):
		$field->start_field($action,$options );
		
		if( $field->is_data_array( $data ) ):
			
			foreach($data as $item_data):
				if( !$field->is_array_empty($item_data) ||  $action == "edit" ):
					profile_cct_data_display($item_data,$options);
				endif;
			endforeach;
			
		else:
			profile_cct_data_display($data,$options);
		endif;
		
		$field->end_field( $action, $options );
	else:
		echo $empty;
	endif;
	
}
function profile_cct_data_display( $data, $options ){
	require_once(WP_PLUGIN_DIR.'/profile-cct/inc/simple_html_dom.php');
	
	extract( $options );
	$field = Profile_CCT::get_object();
	$field->display_text( array( 'field_type'=>$type, 'class'=>'data', 'type'=>'shell', 'tag'=>'div') );
		
		$settings = get_option('Profile_CCT_settings');
		$url_prefix = $settings['data-url'][$type];
		$url = $url_prefix . $data['url'];

		//attempt to get page
		if($html = file_get_html($url)):
			$html_body= $html->find('body', 0);
			
			//Don't output undesirable elements
			$bad_elements = $html_body->find('script, iframe');
			foreach ($bad_elements as $e):
				$e->outertext = '';
			endforeach;
			
			//should we treat onclick/onmouseover/etc event tags as possibly malicious?
			//$bad_atts = $html_body->find('');
			
			echo $html_body;
			$html->clear();
		else:
			echo "Couldn't access external data";
		endif;
	
	$field->display_text( array( 'field_type'=>$type, 'type'=>'end_shell', 'tag'=>'div') );
}

function profile_cct_data_get_url_prefix(){
	$options = get_option('Profile_CCT_settings');
	//print_r($options);
	return $options['data']['url_prefix'];
}