<?php 
Class Profile_CCT_Unitassociations extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'unitassociations',
		'label'         => 'unit associations',
		'description'   => '',
		'show'          => array('unit-website'),
		'show_fields'   => array('unit-website'),
		'multiple'      => true,
		'show_multiple' => true,
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	var $shell = array(
		'class' => 'unit-associations',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_text( array(
			'field_id' => 'unit',
			'label'    => 'Name',
			'size'     => 35,
		) );
		$this->input_text( array(
			'field_id' => 'unit-website',
			'label'    => 'Website - http://{value}',
			'size'     => 35
		) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_link( array(
			'field_id'     => 'unit',
			'default_text' => 'Biotechnology',
			'maybe_link'   => true,
			'href'         => ( empty( $this->data['unit-website'] ) ? '' : 'http://'.$this->data['unit-website'] ),
		) );
	}
	
	/**
	 * shell function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $options
	 * @param mixed $data
	 * @return void
	 */
	public static function shell( $options, $data ) {
		new Profile_CCT_Unitassociations( $options, $data ); 
	}
}

/**
 * profile_cct_unitassociations_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_unitassociations_shell( $options, $data ) {
	Profile_CCT_Unitassociations::shell( $options, $data ); 
}

/*
function profile_cct_unitassociations_display_shell( $options, $data ) {
		Profile_CCT_Unitassociations::shell( $options, $data ); 

}

function profile_cct_unitassociations_shellas( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'unitassociations',
		'label' => 'unitassociations',
		'description' => '',
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
	
	$field->input_text( array('field_id' => 'unit', 'label' => 'Name', 'size'=>35, 'value'=>$data['unit'], 'type' => 'text','count'=>$count) );
	$field->input_text( array('field_id' => 'unit-website', 'label' => 'Website - http://', 'size'=>35, 'value'=>$data['unit-website'], 'type' => 'text','count'=>$count, 'show'=>in_array('unit-website', $show)) );
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}




function profile_cct_unitassociations_display_shellasas(  $action, $options, $data=null ) {
	
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
		'before' => '',
		'empty' => '',
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
		echo $options['empty'];
	endif;
	
}
function profile_cct_unitassociations_display( $data, $options ){
	
	extract( $options );
	$field = Profile_CCT::get_object();
	
	
	$field->display_text( array( 'field_type'=>$type, 'class' => 'department', 'type' => 'shell', 'tag' => 'div') );
	if( empty($data['unit-website']) ):
		$field->display_text( array( 'field_type'=>$type, 'default_text' => 'Biotechnology', 'value'=>$data['unit'], 'type' => 'text') );
	else:
		$field->display_text( array( 'field_type'=>$type, 'default_text' => 'Biotechnology', 'value'=>$data['unit'], 'type' => 'text', 'tag'=> 'a', 'href'=> $field->correct_URL($data['unit-website']) ) );
	endif;
	$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'div') );
	
}
*/