<?php
Class Profile_CCT_Position extends Profile_CCT_Field {
	var $default_options = array(
		'type'          => 'position',
		'label'         => 'position',
		'description'   => '',
		'show'          => array(),
		'show_fields'   => array( 'organization', 'url' ),
		'multiple'      => true,
		'show_multiple' => true,
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	var $shell = array(
		'class' => 'position',
	);
	
	function field() {
		$this->input_text( array(
			'field_id' => 'position',
			'label'    => 'Title',
			'size'     => 35,
		)); 
		$this->input_text( array(
			'field_id' => 'organization',
			'label'    => 'Organization',
			'size'     => 35,
		) );
		$this->input_text( array(
			'field_id' => 'url',
			'label'    => 'Website - http://{value}',
			'size'     => 35,
		) );
	}
	
	function display() {
		$this->display_text( array(
			'field_id'     => 'position',
			'class'        => 'role',
			'default_text' => 'CEO'
		) );
		$this->display_link( array(
			'field_id'     => 'organization',
			'class'        => 'org organization',
			'separator'    => ', ',
			'default_text' => 'Wayne Enterprises',
			'maybe_link'   => true,
			'href'         => ( empty( $this->data['url'] ) ? '' : $this->data['url'] ),
		) );
	}
	
	public static function shell( $options, $data ) {
		new Profile_CCT_Position( $options, $data ); 
	}
}

function profile_cct_position_shell( $options, $data ) {
	Profile_CCT_Position::shell( $options, $data );
}