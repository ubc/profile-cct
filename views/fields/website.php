<?php
Class Profile_CCT_Website extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'website',
		'label'         => 'website',
		'description'   => '',
		'multiple'      => true,
		'show_multiple' => true,
		'show'          => array(),
		'show_fields'   => array('site-title'),
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	var $shell = array(
		'class' => 'website',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$current_user = wp_get_current_user();
		
		$this->input_text( array(
			'field_id' => 'website',
			'label'    => 'Website - http://{value}',
			'size'     => 35,
			'default'  => $current_user->user_url,
		) );
		$this->input_text( array(
			'field_id' => 'site-title',
			'label'    => 'Site title',
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
			'field_id'     => 'website',
			'default_text' => 'http://wayneenterprises.biz',
			'value'        => $this->data['site-title'],
			'href'         => ( empty( $this->data['website'] ) ? '' : $this->data['website'] ),
			'force_link'   => true,
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
		new Profile_CCT_Website( $options, $data ); 
	}	
}

/**
 * profile_cct_website_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_website_shell( $options, $data ) {
	Profile_CCT_Website::shell( $options, $data ); 
}