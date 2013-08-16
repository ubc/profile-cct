<?php
Class Profile_CCT_Name extends Profile_CCT_Field {
    /**
     * default_options
     * 
     * @var mixed
     * @access public
     */
    var $default_options = array(
		'type'         => 'name',
		'label'        => 'name',
		'description'  => '',
		'show'         => array( 'salutations', 'middle', 'credentials' ),
		'show_fields'  => array( 'salutations', 'middle', 'credentials' ),
		'width'        => 'two-third',
		'link_to'      => true,
		'show_link_to' => true,
		'before'       => '',
		'empty'        => '',
		'after'        => '',
    );
	
	var $shell = array(
		'class' => 'fn n',
		'tag'   => 'h2',
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
			'field_id'  => 'salutations',
			'label'     => 'Salutations',
			'size'      => 2,
			'data'      => array(
				'accepts' => '/[a-zA-Z.]/',
				'limit'   => 5,
			),
		) );
		$this->input_text( array(
			'field_id' => 'first',
			'label'    => 'First',
			'size'     => 13,
			'default'  => $current_user->user_firstname,
			'data'      => array(
				'accepts' => '/[a-zA-Z]/',
				'limit'   => 16,
			),
		) );
		$this->input_text( array(
			'field_id' => 'middle',
			'label'    => 'Middle',
			'size'     => 3,
			'data'      => array(
				'accepts' => '/[a-zA-Z.]/',
				'limit'   => 6,
			),
		) );
		$this->input_text( array(
			'field_id' => 'last',
			'label'    => 'Last',
			'size'     => 17,
			'default'  => $current_user->user_lastname,
			'data'      => array(
				'accepts' => "/[a-zA-Z- ']/",
				'limit'   => 22,
			),
		) );
		$this->input_text( array(
			'field_id' => 'credentials',
			'label'    => 'Credentials',
			'size'     => 6,
			'data'      => array(
				'accepts' => '/[a-zA-Z.]/',
				'limit'   => 10,
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
		$this->display_text( array(
			'field_id'     => 'salutations',
			'class'        => 'honorific-prefix salutations',
			'default_text' => 'Mr',
		) );
		$this->display_text( array(
			'field_id'     => 'first',
			'class'        => 'given-name',
			'separator'    => ' ',
			'default_text' => 'Bruce',
		) );
		$this->display_text( array(
			'field_id'     => 'middle',
			'class'        => 'additional-name middle',
			'separator'    => ' ',
			'default_text' => 'Anthony',
		) );
		$this->display_text( array(
			'field_id'     => 'last',
			'class'        => 'family-name',
			'separator'    => ' ',
			'default_text' => 'Wayne',
		) );
		$this->display_text( array(
			'field_id'     => 'credentials',
			'class'        => 'honorific-suffix suffix credentials',
			'separator'    => ', ',
			'default_text' => 'BCom',
		) );
    }

    public static function shell( $options, $data ) {
		new Profile_CCT_Name( $options, $data );
    }
}

function profile_cct_name_shell( $options, $data ) {
    Profile_CCT_Name::shell( $options, $data );
}