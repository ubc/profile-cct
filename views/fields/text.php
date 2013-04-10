<?php 
Class Profile_CCT_Text extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'text',
		'label'         => 'text',
		'description'   => '',
		'multiple'      => true,
		'show_multiple' => true,
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         =>'',
	);
	
	var $shell = array(
		'class' => 'text',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_text( array(
			'field_id' => 'text',
			'label'    => '',
			'size'     => 25,
			'class'    => "text-shell",
		) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$text = ( empty( $this->data['text']) ? null : $this->data['text'] );

		$this->display_text( array(
			'value'		   => $text,
			'class'        => 'text text',
			'default_text' => 'Lorem ipsum dolor sit amet.',
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
		new Profile_CCT_Text( $options, $data ); 
	}
}

/**
 * profile_cct_text_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_text_shell( $options, $data ) {
	Profile_CCT_Text::shell( $options, $data );
}