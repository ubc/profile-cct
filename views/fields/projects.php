<?php

function profile_cct_projects_field_shell( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'projects',
		'label'=>'projects',
		'description'=>'',
		'show'=>array('project-website','start-date-month','start-date-year','end-date-month','end-date-year','project-status'),
		'multiple'=>true,
		'show_multiple'=>true,
		'show_fields'=>array('project-website','start-date-month','start-date-year','end-date-month','end-date-year','project-status'),
		'class'=>'projects'
		);
	
	$options = (is_array($options) ? array_merge($default_options,$options): $default_options );
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			profile_cct_projects_field($item_data,$options,$count);
			$count++;
		endforeach;
		
	else:
		profile_cct_projects_field($data,$options);
	endif;
	
	$field->end_field( $action, $options );

}
function profile_cct_projects_field( $data, $options, $count = 0){

	extract( $options );
	$show = (is_array($show) ? $show : array());
	$field = Profile_CCT::get_object();
	$year_built_min = date("Y")-50;
    $year_built_max = date("Y")+10;
	$year_array = range($year_built_max, $year_built_min);
	$completion_year_array = range($year_built_max, date("Y"));
	$project_status_array = array("Current", "Completed");
	
	echo '<div data-count="'.$count.'" class="wrap-fields">';
	
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'project-title', 'label'=>'Project Title', 'size'=>35, 'value'=>$data['project-title'], 'type'=>'text','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'project-description','label'=>'Project Description', 'size'=>35, 'value'=>$data['project-description'], 'type'=>'textarea','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'project-website', 'label'=>'Project Website', 'size'=>35, 'value'=>$data['project-website'], 'type'=>'text', 'show' => in_array("project-website",$show), 'count'=>$count ) );
	
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'start-date-month','label'=>'Month', 'size'=>35, 'value'=>$data['start-date-month'], 'all_fields'=>profile_cct_list_of_months(), 'type'=>'select', 'show' => in_array("start-date-month",$show),'count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'start-date-year','label'=>'Year', 'size'=>35, 'value'=>$data['start-date-year'], 'all_fields'=>$year_array, 'type'=>'select', 'show' => in_array("start-date-year",$show),'count'=>$count) );
	
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'end-date-month','label'=>'Month', 'size'=>35, 'value'=>$data['end-date-month'], 'all_fields'=>profile_cct_list_of_months(), 'type'=>'select', 'show' => in_array("end-date-month",$show),'count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'end-date-year','label'=>'Year', 'size'=>35, 'value'=>$data['end-date-year'], 'all_fields'=>$completion_year_array, 'type'=>'select', 'show' => in_array("end-date-year",$show),'count'=>$count) );
	
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'project-status','label'=>'Status', 'size'=>35, 'value'=>$data['project-status'], 'all_fields'=>$project_status_array, 'type'=>'select', 'show' => in_array("project-status",$show),'count'=>$count) );
	
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}


function profile_cct_projects_display_shell( $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'projects',
		'width' => 'full',
		'hide_label'=>true,
		'before'=>'',
		'after' =>'',
		'show'=>array('project-website','start-date-month','start-date-year','end-date-month','end-date-year','project-status'),
		'show_fields'=>array('project-website','start-date-month','start-date-year','end-date-month','end-date-year','project-status')
		);
	
	$options = (is_array($options) ? array_merge($default_options,$options): $default_options );
	
	if( !$field->is_array_empty($data , array('start-date-month','start-date-year','end-date-month','end-date-year','project-status') ) ||  $action == "edit" ):
		$field->start_field($action,$options);
		
		if( $field->is_data_array( $data ) ):
			foreach($data as $item_data):
				
				if( !$field->is_array_empty($item_data , array('start-date-month','start-date-year','end-date-month','end-date-year','project-status') ) ||  $action == "edit" ):
					profile_cct_projects_display($item_data,$options);
					
				endif;
			endforeach;
			
		else:
			
			profile_cct_projects_display($data,$options);
		endif;
		$field->end_field( $action, $options );
	else:
		echo $empty;
	endif;

}
function profile_cct_projects_display( $data, $options ){

	extract( $options );
	$show = (is_array($show) ? $show : array());
	

	$field = Profile_CCT::get_object();
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'projects', 'type'=>'shell','tag'=>'div') );
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'project-title','default_text'=>'Cure for Cancer', 'value'=>$data['project-title'], 'type'=>'text' ) );
	$field->display_text( array( 'field_type'=>$type, 'class'=>'project-description','default_text'=>'The current research at Wayne Biotech is focused on finding a cure for cancer.', 'value'=>$data['project-description'], 'type'=>'text') );
	$field->display_text( array( 'field_type'=>$type, 'class'=>'project-website','default_text'=>'http://wayneenterprises.biz', 'separator'=>'<br />'/*needs changing?*/, 'value'=>$data['project-website'], 'type'=>'text', 'show' => in_array("project-website",$show) ) );
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'start-date-month','default_text'=>'January', 'value'=>$data['start-date-month'], 'type'=>'text', 'show'=> in_array("start-date-month",$show)) );
	$field->display_text( array( 'field_type'=>$type, 'class'=>'start-date-year','default_text'=>'2006', 'separator'=>',', 'value'=>$data['start-date-year'], 'type'=>'text', 'show'=> in_array("start-date-year",$show)) );
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'end-date-month','default_text'=>'December', 'value'=>$data['end-date-month'], 'type'=>'text', 'show'=> in_array("end-date-month",$show)) );
	$field->display_text( array( 'field_type'=>$type, 'class'=>'end-date-year','default_text'=>'2016', 'separator'=>',', 'value'=>$data['end-date-year'], 'type'=>'text', 'show'=> in_array("end-date-year",$show)) );
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'project-status','default_text'=>'Current', 'value'=>$data['project-status'], 'type'=>'text', 'show' => in_array("project-status",$show) ) );
	
	$field->display_text( array( 'field_type'=>$type, 'type'=>'end_shell','tag'=>'div') );

}

function profile_cct_list_of_months() {
	return array(
		"January",
		"February",
		"March",
		"April",
		"May",
		"June",
		"July",
		"August",
		"September",
		"October",
		"November",
		"December"		
	);
}