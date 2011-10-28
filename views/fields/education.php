<?php 

function profile_cct_education_field_shell($action,$options) {
	
	$field = Profile_CCT::set(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'education',
		'label'=>'education',
		'description'=>'',
		'show'=>array('year'),
		'show_fields'=>array('year'),
		'multiple'=>true
		);
	$options = (is_array($options) ? array_merge($options,$default_options): $default_options );
	
	
	$field->start_field('education',$action,$options);
	
	profile_cct_education_field($data,$options);
	
	$field->end_field($options);
	
}
function profile_cct_education_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::set();
	$year_built_min = date("Y")-50;
    $year_built_max = date("Y")+5;
	$year_array = range($year_built_max, $year_built_min);

	$field->input_field( array( 'field_id'=>'school', 'label'=>'school name', 'size'=>35,  'value'=>$data['school'], 'type'=>'text') );
	$field->input_field( array( 'field_id'=>'year', 'label'=>'year', 'size'=>25,  'value'=>$data['year'], 'all_fields'=>$year_array,  'type'=>'select', 'show'=> in_array('year',$show)) );
	$field->input_field( array( 'field_id'=>'degree','label'=>'degree', 'size'=>5,  'value'=>$data['degree'], 'type'=>'text') );
	$field->input_field( array( 'field_id'=>'honours','label'=>'honours', 'size'=>15,  'value'=>$data['honours'], 'type'=>'text') );

}
