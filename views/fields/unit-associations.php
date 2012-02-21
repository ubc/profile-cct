<?php 

function profile_cct_unitassociations_field_shell( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'unitassociations',
		'label'=>'unitassociations',
		'description'=>'',
		'multiple'=>true,
		'show_multiple'=>true,
		'show'=>array('unit-website'),
		'show_fields'=>array('unit-website'),
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			profile_cct_unitassociations_field($item_data,$options,$count);
			$count++;
		endforeach;
		
	else:
		profile_cct_unitassociations_field($data,$options);
	endif;
	$field->end_field( $action, $options );
	
	
}
function profile_cct_unitassociations_field( $data, $options, $count = 0 ){
	
	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	echo "<div class='wrap-fields' data-count='".$count."'>";
	
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'unit', 'label'=>'Name', 'size'=>35, 'value'=>$data['unit'], 'type'=>'text','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'unit-website', 'label'=>'Website - http://', 'size'=>35, 'value'=>$data['unit-website'], 'type'=>'text','count'=>$count, 'show'=>in_array('unit-website', $show)) );
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}




function profile_cct_unitassociations_display_shell(  $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'unitassociations',
		'width' => 'full',
		'before'=>'',
		'empty'=>'',
		'after' =>'',
		'hide_label'=>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	if( !$field->is_array_empty($data) ||  $action == "edit" ):
		$field->start_field($action,$options );
		
		if( $field->is_data_array( $data ) ):
			
			foreach($data as $item_data):
				if( !$field->is_array_empty($item_data) ||  $action == "edit" ):
					profile_cct_unitassociations_display($item_data,$options);
				endif;
			endforeach;
			
		else:
			profile_cct_unitassociations_display($data,$options);
		endif;
		
		$field->end_field( $action, $options );
	else:
		echo $empty;
	endif;
	
}
function profile_cct_unitassociations_display( $data, $options ){
	
	extract( $options );
	$field = Profile_CCT::get_object();
	
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'department', 'type'=>'shell', 'tag'=>'div') );
	if( empty($data['unit-website']) ):
		$field->display_text( array( 'field_type'=>$type, 'default_text'=>'Biotechnology', 'value'=>$data['unit'], 'type'=>'text') );
	else:
		$field->display_text( array( 'field_type'=>$type, 'default_text'=>'Biotechnology', 'value'=>$data['unit'], 'type'=>'text', 'tag'=> 'a', 'href'=> $data['unit-website'] ) );
	endif;
	$field->display_text( array( 'field_type'=>$type, 'type'=>'end_shell', 'tag'=>'div') );
	
}