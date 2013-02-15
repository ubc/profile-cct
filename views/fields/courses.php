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