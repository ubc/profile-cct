<?php 

function profile_cct_website_field_shell($action,$options) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'website',
		'label'=> 'website',
		'description'=> '',
		'multiple'=> true,
		'show'=>array(),
		'show_fields'=>array('site-title'),
		'show_multiple' =>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );

	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		foreach($data as $item_data):
			profile_cct_website_field($item_data,$options);
		endforeach;
	else:
		profile_cct_website_field($data,$options);
	endif;
	
	$field->end_field( $action, $options );
	
}
function profile_cct_website_field( $data, $options ){

	extract( $options );
	$field = Profile_CCT::get_object();

	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'website', 'label'=>'url - http://', 'size'=>35, 'value'=>$data['website'], 'type'=>'text') );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'site-title', 'label'=>'site title', 'size'=>35, 'value'=>$data['website'], 'type'=>'text', 'show'=>in_array('site-title', $show)) );
}

function profile_cct_website_display_shell($action,$options) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'website',
		'label'=> 'website',
		'hide_label'=>true,
		'before'=>'',
		'width' => 'full',
		'after'=>'',
		
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		foreach($data as $item_data):
			profile_cct_website_display($item_data,$options);
		endforeach;
	else:
		profile_cct_website_display($data,$options);
	endif;
	
	$field->end_field( $action, $options );
	
}
function profile_cct_website_display( $data, $options ){

	extract( $options );
	$field = Profile_CCT::get_object();
	?>
	<div class=""><a href="">http://google.com</a></div>
	<?php

}

