<?php 

function profile_cct_position_field_shell($action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$options = $options['args']['options'];
		$data = $options['args']['data'];
	endif;

	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'position',
		'label'=>'position',
		'description'=>'',
		'multiple'=>true,
		'show_multiple' =>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field('position',$action,$options);
	
	if( $field->is_data_array( $data ) ):
		
		foreach($data as $item_data):
			profile_cct_position_field($item_data,$options);
		endforeach;
		
	else:
		profile_cct_position_field($item_data,$options);
	endif;
	
	$field->end_field($options);
	
}
function profile_cct_position_field( $data, $options ){

	extract( $options );
	$field = Profile_CCT::get_object();
	
	$field->input_field( array( 'field_id'=>'position','label'=>'', 'size'=>35, 'value'=>$data['position'], 'type'=>'text') );
}
