<?php 

function profile_cct_email_field_shell($action,$options) {
	
	$field = Profile_CCT::set(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'email',
		'label'=>'email',
		'description'=>'',
		);
	$options = (is_array($options) ? array_merge($options,$default_options): $default_options );
	
	
	$field->start_field('email',$action,$options);
	
	profile_cct_email_field($data,$options);
	
	$field->end_field($options);
	
}
function profile_cct_email_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::set();

	$field->input_field( array( 'label'=>'option',  'value'=>$data['option'], 'all_fields'=>profile_cct_email_options(), 'type'=>'select') );
	$field->input_field( array( 'label'=>'email', 'size'=>25, 'value'=>$data['email'], 'type'=>'text') );



}

function profile_cct_email_options(){

	return array(
	"work",
	"mobile",
	"fax",
	"work fax",
	"pager",
	"other");
}