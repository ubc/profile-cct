<?php 

function profile_cct_courses_field_shell( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'courses',
		'label'=>'courses',
		'description'=>'',
		'show'=>array(),
		'multiple'=>true,
		'show_multiple' =>true,
		'show_fields'=>array('section-number','course-date-month','course-date-year','course-summary'),
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			
			profile_cct_courses_field($item_data,$options, $count);
			$count++;
			
		endforeach;
		
	else:
		// this should only occure if the there is no data
		profile_cct_courses_field($data,$options);
	endif;
	
	$field->end_field( $action, $options );
	
	
}
function profile_cct_courses_field( $data, $options, $count = 0 ){

	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	
	$year_built_min = date("Y")-10;
    $year_built_max = date("Y")+3;
	$year_array = range($year_built_max, $year_built_min);
	
	echo "<div class='wrap-fields' data-count='".$count."'>";
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'course-name','label'=>'Course Name', 'size'=>35, 'value'=>$data['course-name'], 'type'=>'text', 'count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'teaching-unit-prefix','label'=>'Subject Code', 'size'=>4, 'value'=>$data['teaching-unit-prefix'], 'type'=>'text','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'course-number','label'=>'Course #', 'size'=>3, 'value'=>$data['course-number'], 'type'=>'text','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'section-number','label'=>'Section #', 'size'=>3, 'value'=>$data['section-number'], 'type'=>'text','count'=>$count, 'show' => in_array("section-number",$show),) );
	
	//just added these 2, now need to make sure they actually work properly.
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'course-date-month','label'=>'Month', 'size'=>35, 'value'=>$data['course-date-month'], 'all_fields'=>profile_cct_list_of_months(), 'type'=>'select', 'show' => in_array("course-date-month",$show),'count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'course-date-year','label'=>'Year', 'size'=>35, 'value'=>$data['course-date-year'], 'all_fields'=>$year_array, 'type'=>'select', 'show' => in_array("course-date-year",$show),'count'=>$count) );

	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'course-summary','label'=>'Course Summary', 'size'=>35, 'value'=>$data['extension'], 'type'=>'textarea', 'show' => in_array("course-summary",$show),'count'=>$count) );
	
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";


}



function profile_cct_courses_display_shell(  $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'courses',
		'label_hide'=>true,
		'before'=>'',
		'empty'=>'',
		'after'=>'',
		'width' => 'full',
		'show'=>array('course-summary'),
		'show_fields'=>array('course-summary')
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	if( !$field->is_array_empty($data) ||  $action == "edit" ):
	
		$field->start_field($action,$options);
		
		if( $field->is_data_array( $data ) ):
			
			foreach($data as $item_data):
				if( !$field->is_array_empty( $item_data ) ||  $action == "edit" ):
					profile_cct_courses_display($item_data,$options);
				endif;
			endforeach;
			
		else:
			// this shouldn't be happening unless its displaying stuff for the 
			profile_cct_courses_display($data,$options);
		endif;
		
		$field->end_field( $action, $options );
		
	else:
		echo $options['empty'];
	endif;
	
}
function profile_cct_courses_display( $data, $options ){

	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'courses', 'type'=>'shell', 'tag'=>'div') );
	
	$field->display_text( array( 'field_type'=>$type,  'class'=>'course-name', 'default_text'=>'Financial Accounting', 'value'=>$data['course-name'], 'type'=>'text') );
	$field->display_text( array( 'field_type'=>$type,  'class'=>'teaching-unit-prefix', 'default_text'=>'COMM', 'separator'=>',', 'value'=>$data['teaching-unit-prefix'], 'type'=>'text') );
	$field->display_text( array( 'field_type'=>$type,  'class'=>'course-number', 'default_text'=>'450','value'=>$data['course-number'], 'type'=>'text' ) );
	$field->display_text( array( 'field_type'=>$type,  'class'=>'section-number', 'default_text'=>'101','value'=>$data['section-number'], 'type'=>'text' ) );
	$field->display_text( array( 'field_type'=>$type,  'class'=>'course-start', 'default_text'=>'May 2012','value'=>$data['course-start'], 'type'=>'text' ) );
	$field->display_text( array( 'field_type'=>$type,  'class'=>'course-summary', 'default_text'=>'Continuation of the examination of accounting as a means of measurement and as an information system for external reporting purposes.','value'=>$data['course-summary'], 'type'=>'text', 'tag'=>'span') );
	$field->display_text( array( 'field_type'=>$type, 'type'=>'end_shell', 'tag'=>'div') );
}