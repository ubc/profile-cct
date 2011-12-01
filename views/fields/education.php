<?php 

function profile_cct_education_field_shell(  $action, $options) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'education',
		'label'=>'education',
		'description'=>'',
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
		profile_cct_education_field($item_data,$options);
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
	
	echo "<div data-count='".$count."'>";
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'school', 'label'=>'school name', 'size'=>35,  'value'=>$data['school'], 'type'=>'text','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'year', 'label'=>'year', 'size'=>25,  'value'=>$data['year'], 'all_fields'=>$year_array,  'type'=>'select', 'show'=> in_array('year',$show),'count'=>$count));
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'degree','label'=>'degree', 'size'=>5,  'value'=>$data['degree'], 'type'=>'text','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'honours','label'=>'honours', 'size'=>15,  'value'=>$data['honours'], 'type'=>'text','count'=>$count) );
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}


function profile_cct_education_display_shell( $action, $options, $data=null  ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'education',
		'label'=>'education',
		'hide_label'=>true,
		'before'=>'',
		'width' => 'full',
		'after'=>'',
		'show'=>array('year'),
		'show_fields'=>array('year')
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		
		foreach($data as $item_data):
			profile_cct_education_display($item_data,$options);
		endforeach;
		
	else:
		profile_cct_education_display($item_data,$options);
	endif;
	
	$field->end_field( $action, $options );
}
function profile_cct_education_display( $data, $options ){

	extract( $options );
	
	$field = Profile_CCT::get_object();

	$show = (is_array($show) ? $show : array());
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'educaton', 'type'=>'shell', 'tag'=>'div') );
	$field->display_text( array( 'field_type'=>$type, 'class'=>'school','default_text'=>'University of British Columbia', 'value'=>$data['school'], 'type'=>'text','count'=>$count) );
	

	$field->display_text( array( 'class'=>'year','default_text'=>'2011', 'separator'=>',', 'value'=>$data['year'], 'type'=>'text', 'show'=> in_array('year',$show),'count'=>$count));

	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'textarea bio','default_text'=>'Physics', 'separator'=>',',  'value'=>$data['degree'], 'type'=>'text','count'=>$count) );
	
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'honors','default_text'=>'BS', 'separator'=>',',  'value'=>$data['honours'], 'type'=>'text','count'=>$count) );
	$field->display_text( array( 'field_type'=>$type, 'type'=>'end_shell', 'tag'=>'div') );

}
