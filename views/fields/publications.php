<?php 

function profile_cct_publications_field_shell($action,$options) {
	
	$field = Profile_CCT::set(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'publications',
		'label'=>'publications',
		'description'=>'',
		);
	$options = (is_array($options) ? array_merge($options,$default_options): $default_options );
	
	
	$field->start_field('publications',$action,$options);
	
	profile_cct_publications_field($data,$options);
	
	$field->end_field($options);
	
}
function profile_cct_publications_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::set();

	$field->input_field( array( 'label'=>'', 'size'=>25, 'row'=>2, 'cols'=>20, 'value'=>$data['publications'], 'type'=>'textarea') );

}
