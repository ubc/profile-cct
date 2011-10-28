<?php 

function profile_cct_position_field_shell($action,$options) {
	
	$field = Profile_CCT::set(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'position',
		'label'=>'position',
		'description'=>'',
		'multiple'=>true
		);
	$options = (is_array($options) ? array_merge($options,$default_options): $default_options );
	
	
	$field->start_field('position',$action,$options);
	
	profile_cct_position_field($data,$options);
	
	$field->end_field($options);
	
}
function profile_cct_position_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::set();

	$field->input_field( array( 'field_id'=>'position','label'=>'', 'size'=>35, 'value'=>$data['position'], 'type'=>'text') );



}
