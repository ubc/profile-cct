<?php 

function profile_cct_education_field_shell( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$options = $options['args']['options'];
		$data = $options['args']['data'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'education',
		'label'=>'education',
		'description'=>'',
		'show'=>array('year'),
		'multiple'=>true,
		'show_multiple' =>true,
		'show_fields'=>array('year')
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field('education',$action,$options);
	if( $field->is_data_array( $data ) ):
		
		foreach($data as $item_data):
			profile_cct_education_field($item_data,$options);
		endforeach;
		
	else:
		profile_cct_education_field($item_data,$options);
	endif;
	
	$field->end_field($options);
}
function profile_cct_education_field( $data, $options ){

	extract( $options );
	
	$field = Profile_CCT::get_object();
	$year_built_min = date("Y")-50;
    $year_built_max = date("Y")+5;
	$year_array = range($year_built_max, $year_built_min);
	$show = (is_array($show) ? $show : array());

	$field->input_field( array( 'field_id'=>'school', 'label'=>'school name', 'size'=>35,  'value'=>$data['school'], 'type'=>'text') );
	$field->input_field( array( 'field_id'=>'year', 'label'=>'year', 'size'=>25,  'value'=>$data['year'], 'all_fields'=>$year_array,  'type'=>'select', 'show'=> in_array('year',$show)) );
	$field->input_field( array( 'field_id'=>'degree','label'=>'degree', 'size'=>5,  'value'=>$data['degree'], 'type'=>'text') );
	$field->input_field( array( 'field_id'=>'honours','label'=>'honours', 'size'=>15,  'value'=>$data['honours'], 'type'=>'text') );

}
