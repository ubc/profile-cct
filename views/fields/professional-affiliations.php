<?php 
Class Profile_CCT_Professionalaffiliations extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'professionalaffiliations',
		'label'         => 'professional affiliations',
		'description'   => '',
		'show'          => array( 'affiliation-website', 'affiliation-role', 'active-date-month' ),
		'show_fields'   => array( 'affiliation-website', 'affiliation-role', 'active-date-month' ),
		'multiple'      => true,
		'show_multiple' => true,
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	var $shell = array(
		'class' => 'professionalaffiliations',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_text( array(
			'field_id' => 'affiliation',
			'label'    => 'Affiliation',
			'size'     => 35,
		) );
		$this->input_text( array(
			'field_id' => 'affiliation-website',
			'label'    => 'Website - http://{value}',
			'size'     => 35,
		) );
		?>
		<br class="clear" />
		<?php
		$this->input_text( array(
			'field_id' => 'affiliation-role',
			'label'    => 'Role',
			'size'     => 35,
		) );
		$this->input_select( array(
			'field_id'   => 'active-date-month',
			'separator'  => 'member since:',
			'label'      => 'Month',
			'all_fields' => $this->list_of_months(),
		) );
		$this->input_select( array(
			'field_id'   => 'active-date-year',
			'label'      => 'Year',
			'all_fields' => $this->list_of_years(),
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
			'field_id'     => 'affiliation',
			'class'        => 'affiliation',
			'default_text' => 'Wayne Healthcare',
			'maybe_link'   => true,
			'href'         => ( empty( $this->data['affiliation-website'] ) ? '' : $this->data['affiliation-website'] ),
		) );
		$this->display_text( array(
			'field_id'     => 'affiliation-role',
			'class'        => 'affiliation-role',
			'default_text' => 'Public Speaker',
			'tag'          => 'strong',
			'separator'    => ', ',
		));
		$this->display_text( array(
			'field_id'     => 'active-date-month',
			'class'        => 'active-date-month',
			'default_text' => 'January',
			'separator'    => ', ',
		) );
		$this->display_text( array(
			'field_id'     => 'active-date-year',
			'field_type'   => $type,
			'class'        => 'active-date-year',
			'default_text' => '1951',
			'separator'    => ' ',
		) );
	}
	
	public static function shell( $options, $data ) {
		new Profile_CCT_Professionalaffiliations( $options, $data ); 
	}	
}

/**
 * profile_cct_professionalaffiliations_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_professionalaffiliations_shell( $options, $data ) {
	Profile_CCT_Professionalaffiliations::shell( $options, $data );
}