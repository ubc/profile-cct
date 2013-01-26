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
		'show'          => array('affiliation-website','affiliation-role','active-date-month'),
		'show_fields'   => array('affiliation-website','affiliation-role','active-date-month'),
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
			'field_id'       => 'affiliation',
			'class'          => 'affiliation',
			'default_text'   => 'Wayne Healthcare',
			'maybe_link'     => true,
			'href'         => ( empty( $this->data['affiliation-website'] ) ? '' : 'http://'.$this->data['affiliation-website'] ),
			'post_separator' => ', ',
		) );
		$this->display_text( array(
			'field_id'       => 'affiliation-role',
			'class'          => 'affiliation-role',
			'default_text'   => 'Public Speaker',
			'tag'            => 'strong',
			'post_separator' => ', ',
		));
		$this->display_text( array(
			'field_id'       => 'active-date-month',
			'class'          => 'active-date-month',
			'default_text'   => 'January',
			'post_separator' => ' ',
		) );
		$this->display_text( array(
			'field_id'     => 'active-date-year',
			'field_type'   => $type,
			'class'        => 'active-date-year',
			'default_text' => '1951',
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

/*
function profile_cct_professionalaffiliations_display_shell( $options, $data ) {
	
	Profile_CCT_Professionalaffiliations::shell( $options, $data );
	
}


function profile_cct_professionalaffiliations_shellasas( $action, $options ) {
	
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
		
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	$default_options = array(
		'type' => 'professionalaffiliations',
		'label' => 'professionalaffiliations',
		'description' => '',
		'show'=>array('affiliation-website','affiliation-role','active-date-month'),
		'multiple'=>true,
		'show_multiple'=>true,
		'show_fields'=>array('affiliation-website','affiliation-role','active-date-month')
		);
		
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			profile_cct_professionalaffiliations_field($item_data,$options,$count);
			$count++;
		endforeach;
		
	else:
		profile_cct_professionalaffiliations_field($data,$options);
	endif;
	$field->end_field( $action, $options );
	
	
}
function profile_cct_professionalaffiliations_field( $data, $options, $count = 0 ){
	
	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	$year_min = date("Y")-80;
    $year_max = date("Y");
    $active_year_array = range($year_max, $year_min);
	
	echo "<div class='wrap-fields' data-count='".$count."'>";
	
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple, 'field_id' => 'affiliation', 'label' => 'Affiliation', 'size'=>35, 'value'=>$data['affiliation'], 'type' => 'text','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple, 'field_id' => 'affiliation-website', 'label' => 'Website - http://', 'size'=>35, 'value'=>$data['affiliation-website'], 'type' => 'text','show'=>in_array('affiliation-website', $show), 'count'=>$count ));
	echo '<br class="clear" />';
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple, 'field_id' => 'affiliation-role', 'label' => 'Role', 'size'=>35, 'value'=>$data['affiliation-role'], 'type' => 'text', 'show'=>in_array('affiliation-role', $show), 'count'=>$count ));
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple, 'field_id' => 'active-date-month', 'separator' => 'member since:', 'label' => 'Month', 'size'=>35, 'value'=>$data['active-date-month'], 'all_fields'=>profile_cct_list_of_months(), 'type' => 'select', 'show' => in_array("active-date-month",$show),'count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple, 'field_id' => 'active-date-year', 'label' => 'Year', 'size'=>35, 'value'=>$data['active-date-year'], 'all_fields'=>$active_year_array, 'type' => 'select', 'count'=>$count) );
	
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}


function profile_cct_professionalaffiliations_display_shell_as(  $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'professionalaffiliations',
		'width' => 'full',
		'before' => '',
		'empty' => '',
		'after' =>'',
		'show'=>array('affiliation-website','affiliation-role','active-date-month'),
		'show_fields'=>array('affiliation-website','affiliation-role','active-date-month'),
		'hide_label'=>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	if( !$field->is_array_empty( $data, array('active-date-month','active-date-year') ) ||  $action == "edit" ):
		$field->start_field($action,$options );
		
		if( $field->is_data_array( $data ) ):
			
			foreach($data as $item_data):
				if( !$field->is_array_empty( $item_data, array('active-date-month','active-date-year') ) ||  $action == "edit" ):
					profile_cct_professionalaffiliations_display($item_data,$options);
				endif;
			endforeach;
			
		else:
			profile_cct_professionalaffiliations_display($data,$options);
		endif;
		
		$field->end_field( $action, $options );
	else:
		echo $options['empty'];
	endif;
	
}
function profile_cct_professionalaffiliations_display( $data, $options ){
	
	extract( $options );
	$show = (is_array($show) ? $show : array());
	$field = Profile_CCT::get_object();
	
	$field->display_text( array( 'field_type'=>$type, 'class' => 'professionalaffiliations', 'type' => 'shell', 'tag' => 'div') );
	
	if( empty($data['affiliation-website']) ):
		$field->display_text( array( 'field_type'=>$type, 'class' => 'affiliation', 'default_text' => 'Wayne Healthcare', 'value'=>$data['affiliation'], 'type' => 'text') );
	else:
		$field->display_text( array( 'field_type'=>$type, 'class' => 'affiliation', 'default_text' => 'Wayne Healthcare', 'value'=>$data['affiliation'], 'type' => 'text', 'tag'=> 'a', 'href'=> $field->correct_URL($data['affiliation-website']) ) );
	endif;
	$field->display_text( array( 'field_type'=>$type, 'class' => 'affiliation-role', 'default_text' => 'public speaker', 'separator' => ',', 'value'=>$data['affiliation-role'], 'type' => 'text', 'show'=> in_array("affiliation-role",$show) ));
	$field->display_text( array( 'field_type'=>$type, 'class' => 'active-date-month','default_text' => 'January', 'separator' => 'member since:', 'value'=>$data['active-date-month'], 'type' => 'text', 'show'=> in_array("active-date-month",$show) ));
	$field->display_text( array( 'field_type'=>$type, 'class' => 'active-date-year','default_text' => '1951', 'separator' => ',', 'value'=>$data['active-date-year'], 'type' => 'text' ));

	$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'div') );	
}
*/