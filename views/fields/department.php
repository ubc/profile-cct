<?php 


Class Profile_CCT_Department extends Profile_CCT_Field {
	
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type' => 'department',
		'label' => 'department',
		'description' => '',
		
		'multiple'=>true,
		'show_multiple'=>true,
		
		'show'=>array('url'),
		'show_fields'=>array('url'),
		
		'before' => '',
		'empty' => '',
		'after' =>'',
		'width' => 'full'
		);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
	
		$this->input_text( array(  'multiple'=>$multiple,'field_id' => 'department', 'label' => 'Name', 'size'=>35, 'type' => 'text' ) );
		
		$this->input_text( array(  'multiple'=>$multiple,'field_id' => 'url', 'label' => 'Website - http://', 'size'=>35 ) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_shell( array( 'class' => 'department') );
		$this->display_link( array(  'field_id' => 'department', 'default_text' => 'Finance and Technology', 'maybe_link' => true, 'href'=> $this->data['url'] ) );
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
	public static function shell($options, $data) {
		new Profile_CCT_Department( $options, $data ); 
	}
}

/**
 * profile_cct_department_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data (default: null)
 * @return void
 */
function profile_cct_department_shell( $options, $data = null ) {

	Profile_CCT_Department::shell( $options, $data );

}

/*	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'department',
		'label' => 'department',
		'description' => '',
		'multiple'=>true,
		'show_multiple'=>true,
		'show'=>array('url'),
		'show_fields'=>array('url'),
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			profile_cct_department_field($item_data,$options,$count);
			$count++;
		endforeach;
		
	else:
		profile_cct_department_field($data,$options);
	endif;
	$field->end_field( $action, $options );
	
	
}
function profile_cct_department_field( $data, $options, $count = 0 ){
	
	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	echo "<div class='wrap-fields' data-count='".$count."'>";
	
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'department', 'label' => 'Name', 'size'=>35, 'value'=>$data['department'], 'type' => 'text','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'url', 'label' => 'Website - http://', 'size'=>35, 'value'=>$data['url'], 'type' => 'text','count'=>$count, 'show'=>in_array('url', $show)) );
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}




function profile_cct_department_display_shell(  $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'department',
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
					profile_cct_department_display($item_data,$options);
				endif;
			endforeach;
			
		else:
			profile_cct_department_display($data,$options);
		endif;
		
		$field->end_field( $action, $options );
	else:
		echo $options['empty'];
	endif;
	
}
function profile_cct_department_display( $data, $options ){
	
	extract( $options );
	$field = Profile_CCT::get_object();
	
	
	$field->display_text( array( 'field_type'=>$type, 'class' => 'department', 'type' => 'shell', 'tag' => 'div') );
	if( empty($data['url']) ):
		$field->display_text( array( 'field_type'=>$type, 'default_text' => 'Finance and Technology', 'value'=>$data['department'], 'type' => 'text') );
	else:
		$field->display_text( array( 'field_type'=>$type, 'default_text' => 'Finance and Technology', 'value'=>$data['department'], 'type' => 'text', 'tag'=> 'a', 'href'=> $field->correct_URL($data['url']) ) );
	endif;
	$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'div') );
	
}