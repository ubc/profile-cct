<?php 


add_action('profile_cct_form','profile_cct_name_field_shell',10,2);

function profile_cct_name_field_shell($action) {
	
	
	//echo $type;
	$field = Profile_CCT::set(); // prints "Creating new instance."
	// substr(basename(__FILE__),0,-4) 
	
	$options = $field->form_fields['name']; // stuff that is comming from the db
	
	$default_options = array(
		'label'=>'name',
		'description'=>'just enter your name',
		'show'=>array('prefix','middle','sufix'),
		'show_fields'=>array('prefix','middle','sufix')
		);
		
	$options = (is_array($options) ? $options: $default_options );
	echo "<ul class='form-builder'>";
	$field->start_field('name',$action,$options);
	
	profile_cct_name_field();
	
	$field->end_field($options);
	echo "</ul>";
}
function profile_cct_name_field(){
	$field = Profile_CCT::set();
	
	/*
	$filed->field_label();
	$filed->field_label();
	$filed->field_label();
	$filed->field_label();
	$filed->field_label();
	*/

}


