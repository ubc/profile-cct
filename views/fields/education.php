<?php 


Class Profile_CCT_Education extends Profile_CCT_Field {
	
	var $default_options = array(
		
		'type' => 'education',
		'label' => 'education',
		'description' => '',
		
		'multiple'=>true,
		'show_multiple' =>true,
		
		'show'=>array('year'),
		'show_fields'=>array('year'),
		
		'width' => 'full',
		'before' => '',
		'empty' => '',
		'after' => ''
		
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_text( array( 'field_id' => 'school', 'label' => 'School name', 'size'=>35 ) );
		$this->input_select( array( 'field_id' => 'year', 'label' => 'Year', 'size'=>25,  'all_fields'=>$this->list_of_years() ));
		$this->input_text( array( 'field_id' => 'degree','label' => 'Degree', 'size'=>5 ) );
		$this->input_text( array( 'field_id' => 'honours','label' => 'Honours', 'size'=>15 ) );

	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
	
		$this->display_shell( array( 'class' => 'educaton' ) );	
		$this->display_text( array( 'field_id' => 'school', 'class' => 'school','default_text' => 'University of Gotham' ) );		
		$this->display_text( array( 'field_id' => 'year', 'class' => 'year','default_text' => '1939', 'separator' => ',' ) );		
		$this->display_text( array( 'field_id' => 'degree', 'class' => 'textarea bio','default_text' => 'Finance', 'separator' => ',') );
		$this->display_text( array( 'field_id' => 'honours', 'class' => 'honors','default_text' => 'BCom', 'separator' => ',' ) );	
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
		new Profile_CCT_Education( $options, $data ); 
	}
}


/**
 * profile_cct_education_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data (default: null)
 * @return void
 */
function  profile_cct_education_shell( $options, $data=null ) {
	
	Profile_CCT_Education::shell( $options, $data );
	
}

/*
function  profile_cct_education_display_shell($options, $data=null ) {
	
	Profile_CCT_Education::shell( $options, $data );
	
	
}


function profile_cct_education_shellasdasdsad(  $action, $options) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'education',
		'label' => 'education',
		'description' => '',
		'show'=>array('year'),
		'multiple'=>true,
		'show_multiple' =>true,
		'show_fields'=>array('year')
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			profile_cct_education_field($item_data,$options,$count);
			$count++;
		endforeach;
		
	else:
		profile_cct_education_field($data,$options);
	endif;
	
	$field->end_field( $action, $options );
}
function profile_cct_education_field( $data, $options, $count = 0 ){

	extract( $options );
	
	$field = Profile_CCT::get_object();
	$year_built_min = date("Y")-50;
    $year_built_max = date("Y")+5;
	$year_array = range($year_built_max, $year_built_min);
	$show = (is_array($show) ? $show : array());
	
	echo "<div class='wrap-fields' data-count='".$count."'>";
	$field->input_field( array( 'field_id' => 'school', 'label' => 'School name', 'size'=>35,  'value'=>$data['school'], 'type' => 'text','count'=>$count) );
	$field->input_field( array( 'field_id' => 'year', 'label' => 'Year', 'size'=>25,  'value'=>$data['year'], 'all_fields'=>$year_array,  'type' => 'select', 'show'=> in_array('year',$show),'count'=>$count));
	$field->input_field( array( 'field_id' => 'degree','label' => 'Degree', 'size'=>5,  'value'=>$data['degree'], 'type' => 'text','count'=>$count) );
	$field->input_field( array( 'field_id' => 'honours','label' => 'Honours', 'size'=>15,  'value'=>$data['honours'], 'type' => 'text','count'=>$count) );
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}unction ' profile_cct_education_display_shell' not found or invalid function n


function profile_cct_education_display_shell( $options, $data=null  ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'education',
		'label' => 'education',
		'hide_label'=>true,
		'before' => '',
		'empty' => '',
		'width' => 'full',
		'after' => '',
		'show'=>array('year'),
		'show_fields'=>array('year')
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	if( !$field->is_array_empty($data , array('year') ) ||  $action == "edit" ):
		$field->start_field($action,$options);
		
		if( $field->is_data_array( $data ) ):
			foreach($data as $item_data):
				
				if( !$field->is_array_empty($item_data , array('year') ) ||  $action == "edit" ):
					profile_cct_education_display($item_data,$options);
					
				endif;
			endforeach;
			
		else:
			
			profile_cct_education_display($data,$options);
		endif;
		$field->end_field( $action, $options );
	else:
		echo $options['empty'];
	endif;
}
function profile_cct_education_display( $data, $options){

	extract( $options );
	
	$field = Profile_CCT::get_object();

	$show = (is_array($show) ? $show : array());
	
	$field->display_text( array(  'class' => 'educaton', 'type' => 'shell', 'tag' => 'div') );
	$field->display_text( array(  'class' => 'school','default_text' => 'University of Gotham', 'value'=>$data['school'], 'type' => 'text') );
	

	$field->display_text( array( 'class' => 'year','default_text' => '1939', 'separator' => ',', 'value'=>$data['year'], 'type' => 'text', 'show'=> in_array('year',$show)));

	
	$field->display_text( array(  'class' => 'textarea bio','default_text' => 'Finance', 'separator' => ',',  'value'=>$data['degree'], 'type' => 'text') );
	
	
	$field->display_text( array(  'class' => 'honors','default_text' => 'BCom', 'separator' => ',',  'value'=>$data['honours'], 'type' => 'text') );
	$field->display_text( array(  'type' => 'end_shell', 'tag' => 'div') );

}
*/