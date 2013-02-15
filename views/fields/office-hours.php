<?php 
Class Profile_CCT_Officehours extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'officehours',
		'label'         => 'office hours',
		'description'   => '',
		'multiple'      => true,
		'show_multiple' => true,
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         => ''
	);
	
	var $shell = array(
		'class' => 'officehours',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_select( array(
			'field_id'   => 'start-hour',
			'label'      => 'Hour',
			'all_fields' => $this->list_of_hours(),
		) );
		$this->input_select( array(
			'field_id'   => 'start-minute',
			'label'      => 'Minute',
			'separator'  => ':',
			'all_fields' => $this->list_of_minutes(),
		) );
		$this->input_select( array(
			'field_id'   => 'start-period',
			'label'      => 'Period',
			'all_fields' => $this->list_of_periods(),
		) );
		$this->input_select( array(
			'field_id'   => 'end-hour',
			'label'      => 'Hour',
			'separator'  => '  –  ',
			'all_fields' => $this->list_of_hours(),
		) );
		$this->input_select( array(
			'field_id'   => 'end-minute',
			'label'      => 'Minute',
			'separator'  => ':',
			'all_fields' => $this->list_of_minutes(),
		) );
		$this->input_select( array(
			'field_id'   => 'end-period',
			'label'      => 'Period',
			'all_fields' => $this->list_of_periods(),
		) );
		$this->input_multiple( array(
			'field_id'        => 'days',
			'selected_fields' => $this->data['days'],
			'all_fields'      => $this->list_of_days(),
		) );
	}
	
	/**
	 * display function.
	 * todo:implemet the dispay function
	 * @access public
	 * @return void
	 */
	function display() {
		$separator = "";
		if ( isset( $this->data['days'] ) ):
			foreach( $this->data['days'] as $day ):
				$this->display_text( array(
					'class'        => 'days',
					'default_text' => 'Monday',
					'separator'    => $separator,
					'value'        => $day,
				) );
				$separator = ', ';
			endforeach;
		else:
			$this->display_text( array(
				'class'        => 'days',
				'default_text' => 'Monday',
				'type'         => 'text',
			) );
		endif;
		
		$this->display_text( array(
			'field_id'     => 'start-hour',
			'class'        => 'start-hour',
			'default_text' => '11',
			'separator'    => ' ',
		) );
		$this->display_text( array(
			'field_id'     => 'start-minute',
			'class'        => 'start-minute',
			'default_text' => '15',
			'separator'    => ':',
		) );
		$this->display_text( array(
			'field_id'     => 'start-period',
			'class'        => 'start-period',
			'default_text' => 'AM',
		) );
		$this->display_text( array(
			'field_id'     => 'end-hour',
			'class'        => 'end-hour',
			'default_text' => '12',
			'separator'    => ' - ',
		) );
		$this->display_text( array(
			'field_id'     => 'end-minute',
			'class'        => 'end-minute',
			'default_text' => '05',
			'separator'    => ':',
		) );
		$this->display_text( array(
			'field_id'     => 'end-period',
			'class'        => 'end-period',
			'default_text' => 'PM',
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
		new Profile_CCT_Officehours( $options, $data ); 
	}
}

/**
 * profile_cct_officehours_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data (default: null)
 * @return void
 */
function profile_cct_officehours_shell( $options, $data=null) {
	Profile_CCT_Officehours::shell( $options, $data );
}