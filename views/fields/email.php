<?php 

function profile_cct_email_field_shell($action,$options) {
	
	$field = Profile_CCT::set(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'email',
		'label'=>'email',
		'description'=>'',
		'multiple'=>true
		);
	$options = (is_array($options) ? array_merge($options,$default_options): $default_options );
	
	
	$field->start_field('email',$action,$options);
	
	profile_cct_email_field($data,$options);
	
	$field->end_field($options);
	
}
function profile_cct_email_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::set();
	$field->input_field( array( 'field_id'=>'email', 'label'=>'', 'size'=>35, 'value'=>$data['email'], 'type'=>'text') );



}
