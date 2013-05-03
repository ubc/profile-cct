<?php 

Class Profile_CCT_Email extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'email',
		'label'         => 'email',
		'description'   => '',
		'multiple'      => true,
		'show_multiple' => true,
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	var $shell = array(
		'class' => 'email',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$current_user = wp_get_current_user();
		
		$default = '';
		if ( Profile_CCT_Field::is_post( $options_or_post ) && $options_or_post->post_status == 'auto-draft' ):
			$default = $current_user->user_email;
		endif;
		
		$this->input_text( array(
			'field_id' => 'email',
			'label'    => '',
			'size'     => 35,
			'default'  => $default,
		) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_email( array(
			'field_id'     => 'email',
			'default_text' => 'bruce.wayne@wayneenterprises.com',
		) );
	}
	
	/**
	 * display_email function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function display_email( $attr ) {
		$value = $this->data['email'];
		
		if ( empty( $attr['mailto'] ) ):
			$attr['mailto'] = ( 'edit' == $this->action ? $attr['default_text'] : $value );
        endif;
		
		$attr['value'] = $value;
		$attr['href']  = ( empty( $attr['mailto'] ) ? '' : 'mailto:'.antispambot(sanitize_email($attr['mailto']),1) );
		
		$this->display_link( $attr );
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
		new Profile_CCT_Email( $options, $data ); 
	}
}

/**
 * profile_cct_email_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_email_shell( $options, $data ) {
	Profile_CCT_Email::shell( $options, $data );
}