<?php
Class Profile_CCT_Education extends Profile_CCT_Field {
	var $default_options = array(
		'type'          => 'education',
		'label'         => 'education',
		'description'   => '',
		'multiple'      => true,
		'show_multiple' => true,
		'show'          => array('year'),
		'show_fields'   => array('year'),
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	var $shell = array(
		'class' => 'educaton',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_text( array(
			'field_id' => 'school',
			'label'    => 'School name',
			'size'     => 35,
		) );
		$this->input_select( array(
			'field_id'   => 'year',
			'label'      => 'Year',
			'all_fields' => $this->list_of_years( 3, -70 ),
		) );
		$this->input_text( array(
			'field_id' => 'degree',
			'label'    => 'Degree',
			'size'     => 5,
		) );
		$this->input_text( array(
			'field_id' => 'honours',
			'label'    => 'Honours',
			'size'     => 15,
		) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_text( array(
			'field_id'     => 'school',
			'class'        => 'school',
			'default_text' => 'University of Gotham'
		) );		
		$this->display_text( array(
			'field_id'     => 'year',
			'class'        => 'year',
			'default_text' => '1939',
			'separator'    => ', ',
		) );		
		$this->display_text( array(
			'field_id'     => 'degree',
			'class'        => 'textarea bio',
			'default_text' => 'Finance',
			'separator'    => ', ',
		) );
		$this->display_text( array(
			'field_id'     => 'honours',
			'class'        => 'honors',
			'default_text' => 'BCom',
			'separator'    => ', ',
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
		new Profile_CCT_Education( $options, $data ); 
	}
}

/**
 * profile_cct_education_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data (default: null)
 * @return void
 */
function  profile_cct_education_shell( $options, $data = null ) {
	Profile_CCT_Education::shell( $options, $data );
}