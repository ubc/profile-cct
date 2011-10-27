<?php 

function profile_cct_website_field_shell($action,$options) {
	
	$field = Profile_CCT::set(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'website',
		'label'=>'website',
		'description'=>'',
		);
	$options = (is_array($options) ? array_merge($options,$default_options): $default_options );
	
	
	$field->start_field('email',$action,$options);
	
	profile_cct_website_field($data,$options);
	
	$field->end_field($options);
	
}
function profile_cct_website_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::set();

	
	$field->input_field( array( 'label'=>'http://', 'size'=>70, 'value'=>$data['website'], 'type'=>'text') );



}

