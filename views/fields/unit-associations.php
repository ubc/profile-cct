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
			'href'         => ( empty( $this->data['unit-website'] ) ? '' : $this->data['unit-website'] ),
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