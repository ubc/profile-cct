<?php 
Class Profile_CCT_Permalink extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'        => 'permalink',
		'label'       => 'permalink',
		'description' => '',
		'link_to'     => true, // always link to
		'width'       => 'full',
		'before'      => '',
		'empty'       => '',
		'after'       => '',
		'text'	      => 'more info',
	);
	
	var $shell = array(
		'class' => 'permalink',
	);
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_text( array(
			'value' => $this->text,
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
		new Profile_CCT_Permalink( $options, $data ); 
	}	
}

/**
 * profile_cct_permalink_display_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_permalink_display_shell( $options, $data ) {
	Profile_CCT_Permalink::shell( $options, $data );
}