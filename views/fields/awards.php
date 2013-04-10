<?php
Class Profile_CCT_Awards extends Profile_CCT_Field {
	var $default_options = array(
		'type'          => 'awards',
		'label'         => 'awards',
		'description'   => '',
		'show'          => array( 'award-website', 'receival-date-month' ),
		'show_fields'   => array( 'award-website', 'receival-date-month', 'receival-date-year'),
		'multiple'      => true,
		'show_multiple' => true,
		'width'         => 'full',
		'show_link_to' => false,
		'link_to'      => false,
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	var $shell = array(
		'class' => 'awards',
	);
	
	function field() {
		$this->input_text( array(
			'field_id' => 'award-name',
			'label'    => 'Award Name',
			'size'     => 25,
		)  );
		$this->input_text( array(
			'field_id' => 'award-website',
			'label'    => 'Website - http://{value}',
			'size'     => 35,
		) );
		$this->input_select( array(
			'field_id'   => 'receival-date-month',
			'label'      => 'Month',
			'all_fields' => $this->list_of_months(),
		) );
		$this->input_select( array(
			'field_id'   => 'receival-date-year',
			'label'      => 'Year',
			'all_fields' => $this->list_of_years(),
		) );
	}
	
	function display() {
		
		
		$this->display_link( array(
			'maybe_link'     => true,
			'field_id'       => 'award-name',
			'class'          => 'award-name',
			'default_text'   => 'Gotham Prize for Cancer Research',
			'href'           => $this->data['award-website'],
			'post_separator' => ' ',
		) );
		
		
		$this->display_text( array(
			'field_id'     => 'receival-date-month',
			'class'        => 'receival-date-month',
			'default_text' => 'November',
		) );
		$this->display_text( array(
			'field_id'     => 'receival-date-year',
			'class'        => 'receival-date-year',
			'default_text' => '2011',
			'separator'    => ', ',
		));
	}
	
	public static function shell( $options, $data ) {
		new Profile_CCT_Awards( $options, $data ); 
	}
}

/**
 * profile_cct_awards_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data (default: null)
 * @return void
 */
function profile_cct_awards_shell( $options, $data = null ) {
	Profile_CCT_Awards::shell( $options, $data );
}

/**
 * profile_cct_awards_display_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data (default: null)
 * @return void
 */
function profile_cct_awards_display_shell( $options, $data=null ) {
	Profile_CCT_Awards::shell( $options, $data );
}