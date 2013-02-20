<?php 
Class Profile_CCT_Department extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'department',
		'label'         => 'department',
		'description'   => '',
		'multiple'      => true,
		'show_multiple' => true,
		'show'          => array('url'),
		'show_fields'   => array('url'),
		'before'        => '',
		'empty'         => '',
		'after'         => '',
		'width'         => 'full',
	);
	
	var $shell = array(
		'class' => 'department',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_text( array(
			'multiple' => $multiple,
			'field_id' => 'department',
			'label'    => 'Name',
			'size'     => 35,
			'type'     => 'text',
		) );
		$this->input_text( array(
			'multiple' => $multiple,
			'field_id' => 'url',
			'label'    => 'Website - http://{value}',
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
		$this->display_link( array(
			'field_id'     => 'department',
			'default_text' => 'Finance and Technology',
			'maybe_link'   => true,
			'href'         => ( empty( $this->data['url'] ) ? '' : $this->data['url'] ),
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