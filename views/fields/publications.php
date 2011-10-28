<?php 

function profile_cct_publications_field_shell($action,$options) {
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'publications',
		'label'=>'publications',
		'description'=>'',
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	
	$field->start_field('publications',$action,$options);
	
	profile_cct_publications_field($data,$options);
	
	
	$field->end_field($options);
	
}
function profile_cct_publications_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::get_object();

	$field->input_field( array( 'label'=>'', 'size'=>25, 'row'=>2, 'cols'=>20, 'value'=>$data['publications'], 'type'=>'textarea') );

}
