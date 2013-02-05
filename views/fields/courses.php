<?php 
Class Profile_CCT_Courses extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'courses',
		'label'         => 'courses',
		'description'   => '',
		'multiple'      => true,
		'show_multiple' => true,
		'show'          => array(),
		'show_fields'   => array( 'section-number', 'course-date-month', 'course-date-year' ,'course-summary' ),
		'before'        => '',
		'empty'         => '',
		'after'         => '',
		'width'         => 'full',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_text( array(
			'field_id' => 'course-name',
			'label'    => 'Course Name',
			'size'     => 35,
		) );
		$this->input_text( array(
			'field_id' => 'teaching-unit-prefix',
			'label'    => 'Subject Code',
			'size'     => 4,
			'data'     => array(
				'accepts' => '/[a-zA-Z]/',
				'limit'   => 5,
			),
		) );
		$this->input_text( array(
			'field_id' => 'course-number',
			'label'    => 'Course #',
			'size'     => 3,
			'data'     => array(
				'accepts' => '/[0-9]/',
				'limit'   => 6,
			),
		) );
		$this->input_text( array(
			'field_id' => 'section-number',
			'label'    => 'Section #',
			'size'     => 3,
			'data'     => array(
				'accepts' => '/[0-9]/',
				'limit'   => 6,
			),
		) );
		$this->input_select( array(
			'field_id'   => 'course-date-month',
			'label'      => 'Month',
			'all_fields' => $this->list_of_months()
		) );
		$this->input_select( array(
			'field_id'   => 'course-date-year',
			'label'      => 'Year',
			'all_fields' => $this->list_of_years(),
		) );
		$this->input_textarea( array(
			'field_id' => 'course-summary',
			'label'    => 'Course Summary',
			'class'	   => 'field textarea',
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
		$this->display_shell( array( 'class' => 'courses') );
		$this->display_text( array(
			'field_id'       => 'teaching-unit-prefix',
			'class'          => 'teaching-unit-prefix',
			'default_text'   => 'COMM',
			'post_separator' => ' ',
		) );
		$this->display_text( array(
			'field_id'       => 'course-number',
			'class'          => 'course-number',
			'default_text'   => '450',
			'post_separator' => ' ',
		) );
		$this->display_text( array(
			'field_id'       => 'section-number',
			'class'          => 'section-number',
			'default_text'   => '101',
			'post_separator' => ' ',
		) );
		$this->display_text( array(
			'field_id'       => 'course-name',
			'class'          => 'course-name',
			'default_text'   => 'Financial Accounting',
			'tag'            => 'strong',
		) );
		$this->display_text( array(
			'field_id'       => 'course-date-month',
			'class'          => 'course-date-month',
			'default_text'   => 'May',
			'separator'      => ', ',
			'post_separator' => ' ',
		) );
		$this->display_text( array(
			'field_id'     => 'course-date-year',
			'class'        => 'course-date-year',
			'default_text' => '2012',
		) );
		$this->display_textfield( array(
			'field_id'     => 'course-summary',
			'class'        => 'course-summary',
			'default_text' => 'Continuation of the examination of accounting as a means of measurement and as an information system for external reporting purposes.',
		) );
		
		$this->display_end_shell( );
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
		new Profile_CCT_Courses( $options, $data ); 
	}
}

/**
 * profile_cct_courses_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_courses_shell( $options, $data  ) {
	Profile_CCT_Courses::shell( $options, $data ); 
}

/*
function profile_cct_courses_field( $data, $options, $count = 0 ){

	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	
	$year_built_min = date("Y")-10;
    $year_built_max = date("Y")+3;
	$year_array = range($year_built_max, $year_built_min);
	
	echo "<div class='wrap-fields' data-count='".$count."'>";
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'course-name','label' => 'Course Name', 'size'=>35, 'value'=>$data['course-name'], 'type' => 'text', 'count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'teaching-unit-prefix','label' => 'Subject Code', 'size'=>4, 'value'=>$data['teaching-unit-prefix'], 'type' => 'text','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'course-number','label' => 'Course #', 'size'=>3, 'value'=>$data['course-number'], 'type' => 'text','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'section-number','label' => 'Section #', 'size'=>3, 'value'=>$data['section-number'], 'type' => 'text','count'=>$count, 'show' => in_array("section-number",$show),) );
	
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'course-date-month','label' => 'Month', 'size'=>35, 'value'=>$data['course-date-month'], 'all_fields'=>profile_cct_list_of_months(), 'type' => 'select', 'show' => in_array("course-date-month",$show),'count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'course-date-year','label' => 'Year', 'size'=>35, 'value'=>$data['course-date-year'], 'all_fields'=>$year_array, 'type' => 'select', 'show' => in_array("course-date-year",$show),'count'=>$count) );

	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'course-summary','label' => 'Course Summary', 'size'=>35, 'value'=>$data['extension'], 'type' => 'textarea', 'show' => in_array("course-summary",$show),'count'=>$count) );
	
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";


}



function profile_cct_courses_display_shell(  $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'courses',
		'label_hide'=>true,
		'before' => '',
		'empty' => '',
		'after' => '',
		'width' => 'full',
		'show'=>array('course-summary', 'course-date-month', 'course-date-year'),
		'show_fields'=>array('course-summary','course-date-month','course-date-year'),
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	if( !$field->is_array_empty($data) ||  $action == "edit" ):
	
		$field->start_field($action,$options);
		
		if( $field->is_data_array( $data ) ):
			
			foreach($data as $item_data):
				if( !$field->is_array_empty( $item_data ) ||  $action == "edit" ):
					profile_cct_courses_display($item_data,$options);
				endif;
			endforeach;
			
		else:
			// this shouldn't be happening unless its displaying stuff for the 
			profile_cct_courses_display($data,$options);
		endif;
		
		$field->end_field( $action, $options );
		
	else:
		echo $options['empty'];
	endif;
	
}
function profile_cct_courses_display( $data, $options ){

	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	
	
	$field->display_text( array( 'field_type'=>$type, 'class' => 'courses', 'type' => 'shell', 'tag' => 'div') );
	
	$field->display_text( array( 'field_type'=>$type,  'class' => 'course-name', 'default_text' => 'Financial Accounting', 'value'=>$data['course-name'], 'type' => 'text') );
	$field->display_text( array( 'field_type'=>$type,  'class' => 'teaching-unit-prefix', 'default_text' => 'COMM', 'separator' => ',', 'value'=>$data['teaching-unit-prefix'], 'type' => 'text') );
	$field->display_text( array( 'field_type'=>$type,  'class' => 'course-number', 'default_text' => '450','value'=>$data['course-number'], 'type' => 'text' ) );
	$field->display_text( array( 'field_type'=>$type,  'class' => 'section-number', 'default_text' => '101','value'=>$data['section-number'], 'type' => 'text' ) );
	$field->display_text( array( 'field_type'=>$type,  'class' => 'course-date-month', 'default_text' => 'May','value'=>$data['course-date-month'], 'type' => 'text' ) );
	$field->display_text( array( 'field_type'=>$type,  'class' => 'course-date-year', 'default_text' => '2012','value'=>$data['course-date-year'], 'type' => 'text' ) );
	$field->display_text( array( 'field_type'=>$type,  'class' => 'course-summary', 'content_filter' => 'profile_escape_html', 'default_text' => 'Continuation of the examination of accounting as a means of measurement and as an information system for external reporting purposes.','value'=>$data['course-summary'], 'type' => 'text', 'tag' => 'span') );
	$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'div') );
}
*/