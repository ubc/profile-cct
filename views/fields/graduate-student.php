<?php
Class Profile_CCT_Graduatestudent extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'graduatestudent',
		'label'         => 'graduate student',	
		'description'   => '',
		'show'          => array( 'student-salutations', 'student-middle', 'student-credentials', 'student-website' ),
		'show_fields'   => array( 'student-salutations', 'student-middle', 'student-credentials', 'student-website','thesis-title' ),
		'multiple'      => true,
		'show_multiple' => true,
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         =>'',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_text( array(
			'field_id'  => 'student-salutations',
			'label'     => 'Salutations',
			'size'      => 2,
			'data'      => array(
				'accepts' => '/[a-zA-Z.]/',
				'limit'   => 5,
			),
		) );
		$this->input_text( array(
			'field_id' => 'student-first',
			'label'    => 'First',
			'size'     => 14,
			'data'      => array(
				'accepts' => '/[a-zA-Z]/',
				'limit'   => 17,
			),
		) );
		$this->input_text( array(
			'field_id' => 'student-middle',
			'label'    => 'Middle',
			'size'     => 3,
			'data'      => array(
				'accepts' => '/[a-zA-Z.]/',
				'limit'   => 6,
			),
		) );
		$this->input_text( array(
			'field_id' => 'student-last',
			'label'    => 'Last',
			'size'     => 19,
			'data'      => array(
				'accepts' => '/[a-zA-Z]/',
				'limit'   => 24,
			),
		) );
		$this->input_text( array(
			'field_id' => 'student-credentials',
			'label'    => 'Credentials',
			'size'     => 7,
			'data'      => array(
				'accepts' => '/[a-zA-Z.]/',
				'limit'   => 10,
			),
		) );
		$this->input_text( array(
			'field_id' => 'student-website',
			'label'    => 'Website - http://{value}',
			'size'     => 35,
		) );
		$this->input_text( array(
			'field_id' => 'thesis-title',
			'label'    => 'Thesis Title',
			'size'     => 70,
		) );
	}
	
	var $shell = array(
		'class' => 'graduatestudent',
	);
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_shell( array( 'class' => 'person-name') );
		$this->display_text( array(
			'field_id'       => 'student-salutations',
			'class'          => 'honorific-prefix student-salutations',
			'default_text'   => 'Mr.',
			'post_separator' => ' ',
		) );
		$this->display_text( array(
			'field_id'       => 'student-first',
			'class'          => 'student-given-name',
			'default_text'   => 'Richard',
			'post_separator' => ' ',
		) );
		$this->display_text( array(
			'field_id'       => 'student-middle',
			'class'          => 'additional-name student-middle',
			'default_text'   => 'John',
			'post_separator' => ' ',
		) );
		$this->display_text( array(
			'field_id'       => 'student-last',
			'class'          => 'student-family-name',
			'default_text'   => 'Grayson',
			'post_separator' => ', ',
		) );
		$this->display_text( array(
			'field_id'       => 'student-credentials',
			'class'          => 'honorific-suffix suffix student-credentials',
			'default_text'   => 'B.S.S.',
			'post_separator' => ' ',
		) );
		$this->display_end_shell();
		$this->display_link( array(
			'field_id'     => 'student-website',
			'class'        => 'student-website',
			'default_text' => 'http://richardjohngrayson.com/',
			'href'         => ( empty( $this->data['student-website'] ) ? '' : $this->data['student-website'] ),
		) );
		$this->display_text( array(
			'field_id'     => 'thesis-title',
			'class'        => 'thesis-title',
			'default_text' => 'Cloaking Device Opacity',
			'tag' => 'div'
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
		new Profile_CCT_Graduatestudent( $options, $data ); 
	}
}

/**
 * profile_cct_graduatestudent_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_graduatestudent_shell( $options, $data ) {
	Profile_CCT_Graduatestudent::shell( $options, $data ); 
}