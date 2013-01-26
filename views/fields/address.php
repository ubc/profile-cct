<?php 
Class Profile_CCT_Address extends Profile_CCT_Field {
	var $default_options = array(
		'type'        => 'address',
		'label'       => 'address',
		'description' => '',
		'show'        => array( 'building-name', 'room-number', 'street-1', 'street-2', 'city', 'postal', 'province', 'country' ),
		'show_fields' => array( 'building-name', 'room-number', 'street-1', 'street-2', 'city', 'postal', 'province', 'country' ),
		'width'       => 'full',
		'before'      => '',
		'empty'       => '',
		'after'       => '',
	);
	
	var $shell = array(
		'class' => 'address adr',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_text( array(
			'field_id' => 'building-name',
			'label'    => 'Building Name',
			'size'     => 35,
		) );
		$this->input_text( array(
			'field_id' => 'room-number',
			'label'    => 'Room Number',
			'size'     => 35,
		) );
		$this->input_text( array(
			'field_id' => 'street-1',
			'label'    => 'Street Address',
			'size'     => 74,
		) );
		$this->input_text( array(
			'field_id' => 'street-2',
			'label'    => 'Address Line 2',
			'size'     => 74,
		) );
		$this->input_text( array(
			'field_id' => 'city',
			'label'    => 'City',
			'size'     => 35,
		) );
		$this->input_text( array(
			'field_id' => 'province',
			'label'    => 'Province / State /  Region',
			'size'     => 35,
		) );
		$this->input_select( array(
			'field_id'   => 'country',
			'label'      => 'Country',
			'all_fields' => $this->list_of_countries(),
		) );
		$this->input_text( array(
			'field_id' => 'postal',
			'label'    => 'Postal / Zip Code',
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
		$this->display_text( array(
			'field_id'       => 'building-name',
			'class'          => 'building-name',
			'default_text'   => 'Wayne Manor',
			'post_separator' => ' ',
		) );
		$this->display_text( array(
			'field_id'     => 'room-number',
			'class'        => 'room-number',
			'default_text' => '101',
		) );
		$this->display_text( array(
			'field_id'     => 'street-1',
			'class'        => 'street-address street-1',
			'default_text' => '1007 Mountain Drive',
			'tag'          => 'div',
		) );
		$this->display_text( array(
			'field_id'     => 'street-2',
			'class'        => 'extended-address street-2',
			'default_text' => '',
			'tag'          => 'div',
		) );
		$this->display_text( array(
			'field_id'       => 'city',
			'class'          => 'locality city',
			'default_text'   => 'Gotham',
			'post_separator' => ', ',
		) );
		$this->display_text( array(
			'field_id'     => 'province',
			'class'        => 'region province',
			'default_text' => 'Connecticut',
			'post_separator' => ' ',
		) );
		$this->display_text( array(
			'field_id'     => 'postal',
			'class'        => 'postal',
			'default_text' => 'V1Z 2X0',
		) );
		$this->display_text( array(
			'field_id'     => 'country',
			'class'        => 'country-name country',
			'default_text' => 'United States',
			'tag'          => 'div',
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
		new Profile_CCT_Address( $options, $data ); 
	}
}

/**
 * profile_cct_address_shell function.
 * 
 * @access public
 * @param mixed $action
 * @param mixed $options
 * @return void
 */
function profile_cct_address_shell( $options, $data = null ) {
	Profile_CCT_Address::shell( $options, $data );
}

/**
 * profile_cct_address_display_shell function.
 * 
 * @access public
 * @param mixed $action
 * @param mixed $options
 * @param mixed $data (default: null)
 * @return void
 */
function profile_cct_address_display_shell( $options, $data=null ) {
	Profile_CCT_Address::shell( $options, $data );
}