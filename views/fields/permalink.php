<?php 



function profile_cct_permalink_display_shell(  $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'permalink',
		'width' => 'full',
		'before'=>'',
		'after' =>'',
		'text'	=>'more info',
		'hide_label'=>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		
		foreach($data as $item_data):
			profile_cct_permalink_display($item_data,$options);
		endforeach;
		
	else:
		profile_cct_permalink_display($item_data,$options);
	endif;
	
	$field->end_field( $action, $options );
	
}
function profile_cct_permalink_display( $data, $options ){
	global $post;
	extract( $options );
	$show = (is_array($show) ? $show : array());
	$field = Profile_CCT::get_object();
	
	$href = ( isset($post) ? get_permalink() : "#" );

	$field->display_text( array( 'field_type'=>$type, 'class'=>'permalink', 'type'=>'shell', 'tag'=>'div') );
	$field->display_text( array( 'field_type'=>$type, 'default_text'=>'jo@ubc.ca', 'value'=>$text, $href, 'type'=>'text', 'tag'=>'a', 'href'=>$href, 'class'=>'text-input') );
	$field->display_text( array( 'field_type'=>$type, 'type'=>'end_shell', 'tag'=>'div') );
	
}
