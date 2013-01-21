<?php 
Class Profile_CCT_Specialization extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'specialization',
		'label'         => 'specialization',
		'description'   => '',
		'multiple'      => true,
		'show_multiple' => true,
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_text( array(
			'field_id' => 'specialization',
			'label'    => '',
			'size'     => 35,
		) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_shell( array( 'class' => 'specialization' ) );
		$this->display_text( array(
			'field_id' => 'specialization',
			'default_text' => 'Philanthropy',
		) );
		$this->display_end_shell();
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
		new Profile_CCT_Specialization( $options, $data ); 
	}
}

/**
 * profile_cct_specialization_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_specialization_shell( $options, $data ) {
	Profile_CCT_Specialization::shell( $options, $data ); 
}

/*
function profile_cct_specialization_display_shell( $options, $data ) {
		Profile_CCT_Specialization::shell( $options, $data ); 

}


function profile_cct_specialization_shellsdsdsd( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'specialization',
		'label' => 'specialization',
		'description' => '',
		'multiple'=>true,
		'show_multiple'=>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			profile_cct_specialization_field($item_data,$options,$count);
			$count++;
		endforeach;
		
	else:
		profile_cct_specialization_field($item_data,$options);
	endif;
	$field->end_field( $action, $options );
	
	
}
function profile_cct_specialization_field( $data, $options, $count = 0 ){
	
	extract( $options );
	$field = Profile_CCT::get_object();
	echo "<div class='wrap-fields' data-count='".$count."'>";
	
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'specialization', 'label' => '', 'size'=>35, 'value'=>$data['specialization'], 'type' => 'text','count'=>$count) );
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}




function profile_cct_specialization_display_shellsddsds(  $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'specialization',
		'width' => 'full',
		'before' => '',
		'empty' => '',
		'after' =>'',
		'hide_label'=>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	if( !$field->is_array_empty($data) ||  $action == "edit" ):
		$field->start_field($action,$options);
		
		if( $field->is_data_array( $data ) ):
		
			foreach($data as $item_data):
				if( !$field->is_array_empty($item_data) ||  $action == "edit" ):
					profile_cct_specialization_display($item_data,$options);
				endif;
			endforeach;
		
		else:
			profile_cct_specialization_display($data,$options);
		endif;
	
		$field->end_field( $action, $options );
	
	else:
		echo $options['empty'];
	endif;
	
}
function profile_cct_specialization_display( $data, $options ){
	
	extract( $options );
	$show = (is_array($show) ? $show : array());
	$field = Profile_CCT::get_object();
	

	$field->display_text( array( 'field_type'=>$type, 'class' => 'specialization', 'type' => 'shell', 'tag' => 'div') );
	$field->display_text( array( 'field_type'=>$type, 'default_text' => 'Philanthropy', 'value'=>$data['specialization'], 'type' => 'text') );
	$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'div') );
	
}
*/