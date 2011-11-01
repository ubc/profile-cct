<?php 

function profile_cct_bio_field_shell( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$options = $options['args']['options'];
		$data = $options['args']['data'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'bio',
		'label'=>'bio',
		'description'=>'',
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field('bio',$action,$options);
	
	profile_cct_bio_field($data,$options);
	
	$field->end_field($options);
}
function profile_cct_bio_field( $data, $options ){

	extract( $options );
	$field = Profile_CCT::get_object();

	$field->input_field( array( 'field_id'=>'bio','label'=>'', 'size'=>25, 'row'=>2, 'cols'=>20, 'value'=>$data['bio'], 'type'=>'textarea') );



}
