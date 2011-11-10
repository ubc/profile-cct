<?php 

function profile_cct_position_field_shell($action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;

	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'position',
		'label'=>'position',
		'description'=>'',
		'show'=>array(),
		'show_fields'=>array('organization'),
		'multiple'=>true,
		'show_multiple' =>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			profile_cct_position_field($item_data,$options,$count);
			 $count++;
		endforeach;
		
	else:
		profile_cct_position_field($item_data,$options);
	endif;
	
	$field->end_field( $action, $options );
	
}
function profile_cct_position_field( $data, $options, $count = 0 ){
	
	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	
	echo "<div data-count='".$count."'>";
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'position','label'=>'title', 'size'=>35, 'value'=>$data['position'], 'type'=>'text','count'=>$count) ); 
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'organization','label'=>'organization', 'size'=>35, 'value'=>$data['organization'], 'type'=>'text', 'show'=>in_array("organization",$show),'count'=>$count) );
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}


function profile_cct_position_display_shell($action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;

	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'position',
		'label'=>'position',
		'hide_label'=>true,
		'before'=>'',
		'width' => 'full',
		'after'=>'',
		'show'=>array(),
		'show_fields'=>array('organization'),

		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	
	if( $field->is_data_array( $data ) ):
		
		foreach($data as $item_data):
			profile_cct_position_display($item_data,$options);
		endforeach;
		
	else:
		profile_cct_position_display($item_data,$options);
	endif;
	
	$field->end_field( $action, $options );
	
}
function profile_cct_position_display( $data, $options ){

	extract( $options );
	$field = Profile_CCT::get_object();
?>
	<div class="position">Web Developer</div>
<?php 
}

