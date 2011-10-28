<?php 

function profile_cct_email_field_shell($action,$options) {
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'email',
		'label'=>'email',
		'description'=>'',
		'multiple'=>true,
		'show_multiple' =>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field('email',$action,$options);
	if( $field->is_data_array( $data ) ):
		
		foreach($data as $item_data):
			profile_cct_email_field($item_data,$options);
		endforeach;
		
	else:
		profile_cct_email_field($item_data,$options);
	endif;
	
	$field->end_field($options);
	
}
function profile_cct_email_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::get_object();
	$field->input_field( array( 'field_id'=>'email', 'label'=>'', 'size'=>35, 'value'=>$data['email'], 'type'=>'text') );



}
