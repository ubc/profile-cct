<?php 

function profile_cct_phone_field_shell( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$options = $options['args']['options'];
		$data = $options['args']['data'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'phone',
		'label'=>'phone',
		'description'=>'',
		'show'=>array('tel-1'),
		'multiple'=>true,
		'show_multiple' =>true,
		'show_fields'=>array('tel-1','extension')
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	
	$field->start_field('phone',$action,$options);
	
	if( $field->is_data_array( $data ) ):
		
		foreach($data as $item_data):
			profile_cct_phone_field($item_data,$options);
		endforeach;
		
	else:
		profile_cct_phone_field($item_data,$options);
	endif;
	
	$field->end_field($options);
	
}
function profile_cct_phone_field( $data, $options ){

	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	
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