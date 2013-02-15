<?php 
class Profile_CCT_Phone extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'phone',
		'label'         => 'phone',
		'description'   => '',
		'show'          => array( 'tel-1' ),
		'show_fields'   => array( 'tel-1', 'extension' ),
		'multiple'      => true,
		'show_multiple' => true,
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_select( array(
			'field_id'   => 'option',
			'label'      => 'Option',
			'all_fields' => $this->phone_options(),
		) );
		$this->input_text( array(
			'field_id' => 'tel-1',
			'label'    => '###',
			'size'     => 2,
			'data'      => array(
				'accepts' => '/[0-9]/',
				'limit'   => 3,
				'jumps'   => true,
			),
		) );
		$this->input_text( array(
			'field_id' => 'tel-2',
			'label'    => '###',
			'size'     => 2,
			'data'      => array(
				'accepts' => '/[0-9]/',
				'limit'   => 3,
				'jumps'   => true,
			),
		) );
		$this->input_text( array(
			'field_id' => 'tel-3',
			'label'    => '####',
			'size'     => 3,
			'data'      => array(
				'accepts' => '/[0-9]/',
				'limit'   => 4,
				'jumps'   => true,
			),
		) );
		$this->input_text( array(
			'field_id' => 'extension',
			'label'    => 'extension',
			'size'     => 3,
			'data'      => array(
				'accepts' => '/[0-9]/',
				'limit'   => 5,
			),
		) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_shell( array( 'class' => 'telephone tel') );
		
		$this->display_text( array(
			'field_id'       => 'option',
			'class'          => 'type',
			'default_text'   => 'Work',
			'post_separator' => ': ',
		) );
		$this->display_text( array(
			'field_id'       => 'tel-1',
			'class'          => 'tel-1',
			'default_text'   => '735',
			'post_separator' => '-',
		) );
		$this->display_text( array(
			'field_id'       => 'tel-2',
			'class'          => 'tel-2',
			'default_text'   => '279',
			'post_separator' => '-',
		) );
		$this->display_text( array(
			'field_id'     => 'tel-3',
			'class'        => 'tel-3',
			'default_text' => '2963',
		) );
		$this->display_text( array(
			'field_id'     => 'extension',
			'class'        => 'extension',
			'default_text' => '2',
			'separator'    => ' ext:',
		) );
		
		$this->display_end_shell();
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
		new Profile_CCT_Phone( $options, $data ); 
	}
}

/**
 * profile_cct_phone_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_phone_shell( $options, $data ) {
	Profile_CCT_Phone::shell( $options, $data ); 
}