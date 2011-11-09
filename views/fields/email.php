<?php 

function profile_cct_email_field_shell( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'email',
		'label'=>'email',
		'description'=>'',
		'multiple'=>true,
		'show_multiple' =>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	
	
	var_dump($field->is_data_array( $data ));
	if( $field->is_data_array( $data ) ):
		
		foreach($data as $item_data):
			$field->start_field($action,$options);
			profile_cct_email_field($item_data,$options);
			$field->end_field( $action, $options );
		endforeach;
		
	else:
		
		$field->start_field($action,$options);
		profile_cct_email_field($item_data,$options);
		$field->end_field( $action, $options );
	endif;
	
	
	
}
function profile_cct_email_field( $data, $options ){
	
	extract( $options );
	$field = Profile_CCT::get_object();
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'email', 'label'=>'', 'size'=>35, 'value'=>$data['email'], 'type'=>'text',) );
}




function profile_cct_email_display_shell( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'email',
		'width' =>'full',
		'before'=>'',
		'after' =>'',
		'hide_label'=>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		
		foreach($data as $item_data):
			profile_cct_email_display($item_data,$options);
		endforeach;
		
	else:
		profile_cct_email_display($item_data,$options);
	endif;
	
	$field->end_field( $action, $options );
	
}
function profile_cct_email_display( $data, $options ){
	
	extract( $options );
	$field = Profile_CCT::get_object();
	?>
	<a href="mailto:jo@ubc.ca">jo@ubc.ca</a>
	<?php
	
}
