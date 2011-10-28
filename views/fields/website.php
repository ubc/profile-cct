<?php 

function profile_cct_website_field_shell($action,$options) {
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'website',
		'label'=> 'website',
		'description'=> '',
		'multiple'=> true,
		'show_multiple' =>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field('email',$action,$options);
	if( $field->is_data_array( $data ) ):
		foreach($data as $item_data):
			profile_cct_website_field($item_data,$options);
		endforeach;
	else:
		profile_cct_website_field($data,$options);
	endif;
	
	$field->end_field($options);
	
}
function profile_cct_website_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::get_object();

	$field->input_field( array( 'field_id'=>'website', 'label'=>'http://', 'size'=>70, 'value'=>$data['website'], 'type'=>'text') );
}

