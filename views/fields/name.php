<?php 


add_action('profile_cct_form','profile_cct_name_field_shell',10,2);

function profile_cct_name_field_shell($action) {
	
	$field = Profile_CCT::set(); // prints "Creating new instance."
	
	$options = $field->form_fields['name']; // stuff that is comming from the db
	
	$default_options = array(
		'label'=>'name',
		'description'=>'just enter your name',
		'show'=>array('prefix','middle','suffix'),
		'show_fields'=>array('prefix','middle','suffix')
		);
		
	$options = (is_array($options) ? array_merge($options,$default_options): $default_options );
	echo "<ul class='form-builder'>";
	$field->start_field('name',$action,$options);
	
	profile_cct_name_field($data,$options);
	
	$field->end_field($options);
	echo "</ul>";
}
function profile_cct_name_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::set();
	
	if(in_array("prefix",$show))
		$field->input_field( array( 'label'=>'Title', 'size'=>2, 'value'=>$data['title'], 'type'=>'text', 'tabindex'=>1) );
	
		$field->input_field( array( 'label'=>'First', 'size'=>14, 'value'=>$data['title'], 'type'=>'text', 'tabindex'=>2));
	if(in_array("middle",$show))
		$field->input_field( array( 'label'=>'Middle', 'size'=>3,'value'=>$data['middle'], 'type'=>'text', 'tabindex'=>3));
	
		$field->input_field( array( 'label'=>'Last', 'size'=>19, 'value'=>$data['last'], 'type'=>'text', 'tabindex'=>4));
	
	if(in_array("suffix",$show))
		$field->input_field( array( 'label'=>'Sufix','size'=>3, 'value'=>$data['suffix'], 'type'=>'text', 'tabindex'=>5));
	
}


