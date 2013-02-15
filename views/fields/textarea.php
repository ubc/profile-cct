<?php 
/**
 * Profile_CCT_Textarea class.
 * 
 * @extends Profile_CCT_Field
 */
class Profile_CCT_Textarea extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'        => 'textarea',
		'label'       => 'textarea',
		'description' => '',
		'width'       => 'full',
		'before'      => '',
		'empty'       => '',
		'after'       => '',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_textarea( array(
			'name'    => 'profile_cct['.$this->type.'][textarea]',
			'label'   => '',
			'size'    => 25,
			'row'     => 2,
			'cols'    => 20,
			'default' => $this->data['default'],
			'value'   => ( isset( $this->data['textarea'] ) ? $this->data['textarea']  : null ),
		) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_textfield( array(
			'class'        => 'textarea',
			'default_text' => 'lorem ipsum',
			'value'        => ( isset( $this->data['textarea'] ) ? $this->data['textarea']  : null ),
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
		new Profile_CCT_Textarea( $options, $data ); 
	}
}

/**
 * profile_cct_textarea_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_textarea_shell( $options, $data ) {
	Profile_CCT_Textarea::shell( $options, $data );
}