<?php
Class Profile_CCT_Projects extends Profile_CCT_Field {
	var $default_options = array(
		'type'          => 'projects',
		'label'         => 'projects',
		'description'   => '',
		'show'          => array( 'project-website', 'start-date-month', 'start-date-year', 'end-date-month', 'end-date-year', 'project-status' ),
		'show_fields'   => array( 'project-website', 'start-date-month', 'start-date-year', 'end-date-month', 'end-date-year', 'project-status' ),
		'multiple'      => true,
		'show_multiple' => true,
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	var $shell = array(
		'class' => 'projects',
	);
	
	function field() {
		$this->input_text( array(
			'field_id' => 'project-title',
			'label'    => 'Title',
			'size'     => 57,
		) );
		$this->input_textarea( array(
			'field_id' => 'project-description',
			'label'    => 'Description',
			'size'     => 35,
		) );
		$this->input_text( array(
			'field_id' => 'project-website',
			'label'    => 'Website - http://{value}',
			'size'     => 35,
		) );
		$this->input_select( array(
			'field_id'   => 'start-date-month',
			'label'      => 'Start Month',
			'all_fields' => $this->list_of_months()
		) );
		$this->input_select( array(
			'field_id'   => 'start-date-year',
			'label'      => 'Start Year',
			'all_fields' => $this->list_of_years(),
		) );
		$this->input_select( array(
			'field_id'   => 'end-date-month',
			'label'      => 'End Month',
			'all_fields' => $this->list_of_months()
		) );
		$this->input_select( array(
			'field_id'   => 'end-date-year',
			'label'      => 'End Year',
			'all_fields' => $this->list_of_years( 20, -20 ),
		) );
		$this->input_select( array(
			'field_id'   => 'project-status',
			'label'      => 'Status',
			'all_fields' => $this->project_status(),
		) );
	}
	
	function display() {
		$this->display_text( array(
			'field_id'       => 'project-title',
			'class'          => 'project-title',
			'default_text'   => 'Cure for Cancer',
			'post_separator' => ' ',
			'tag'            => 'strong',
		) );
		$this->display_text( array(
			'field_id'     => 'project-status',
			'class'        => 'project-status',
			'default_text' => 'Current',
			'tag'          => 'em',
		) );
		$this->display_shell( array( 'class' => 'project-dates') );
		$this->display_text( array(
			'field_id'       => 'start-date-month',
			'class'          => 'start-date-month',
			'default_text'   => 'January',
			'post_separator' => ', ',
		) );
		$this->display_text( array(
			'field_id'     => 'start-date-year',
			'class'        => 'start-date-year',
			'default_text' => '2006',
		) );
		$this->display_text( array(
			'field_id'       => 'end-date-month',
			'class'          => 'end-date-month',
			'default_text'   => 'December',
			'separator'      => '  -  ',
			'post_separator' => ', ',
		) );
		$this->display_text( array(
			'field_id'     => 'end-date-year',
			'class'        => 'end-date-year',
			'default_text' => '2016',
			'separator'    => ( empty( $this->data['end-date-month'] ) ? '  -  ' : '' ),
		) );
		$this->display_end_shell();
		$this->display_link( array(
			'field_id'     => 'project-website',
			'class'        => 'project-website',
			'default_text' => 'http://wayneenterprises.biz',
			'href'         => ( ! empty( $this->data['project-website'] ) ? $this->data['project-website'] : '' ),
		) );
		$this->display_textfield( array(
			'field_id'     => 'project-description',
			'class'        => 'project-description',
			'default_text' => 'The current research at Wayne Biotech is focused on finding a cure for cancer.',
		) );
	}
	
	public static function shell( $options, $data ) {
		new Profile_CCT_Projects( $options, $data ); 
	}
}

function profile_cct_projects_shell( $options, $data ) {
	Profile_CCT_Projects::shell( $options, $data ); 
}