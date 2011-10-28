<?php 

function profile_cct_phone_field_shell($action,$options) {
	
	$field = Profile_CCT::set(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'phone',
		'label'=>'phone',
		'description'=>'',
		'show'=>array('tel-1'),
		'show_fields'=>array('tel-1','extension'),
		'multiple'=>true
		);
	$options = (is_array($options) ? array_merge($options,$default_options): $default_options );
	
	
	$field->start_field('phone',$action,$options);
	
	profile_cct_phone_field($data,$options);
	
	$field->end_field($options);
	
}
function profile_cct_phone_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::set();

	$field->input_field( array( 'field_id'=>'option','label'=>'option',  'value'=>$data['option'], 'all_fields'=>profile_cct_phone_options(), 'type'=>'select') );
	
	$field->input_field( array( 'field_id'=>'tel-1','label'=>'###', 'size'=>3, 'value'=>$data['tel-1'], 'type'=>'text', 'show' => in_array("tel-1",$show)) );
	$field->input_field( array( 'field_id'=>'tel-2','label'=>'###', 'size'=>3, 'value'=>$data['tel-2'], 'type'=>'text') );
	$field->input_field( array( 'field_id'=>'tel-3','label'=>'####', 'size'=>4, 'value'=>$data['tel-3'], 'type'=>'text') );
	$field->input_field( array( 'field_id'=>'extension','label'=>'extension', 'size'=>4, 'value'=>$data['extension'], 'type'=>'text', 'show' => in_array("extension",$show)) );


}

function profile_cct_phone_options(){

	return array(
			"work",
			"mobile",
			"fax",
			"work fax",
			"pager",
			"other");
}