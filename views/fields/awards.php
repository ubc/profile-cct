<?php 

/**
 * profile_cct_awards_field_shell function.
 * 
 * @access public
 * @param mixed $action
 * @param mixed $options. (default: null)
 * @return void
 */
function profile_cct_awards_field_shell( $action, $options=null ) {
	
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
		
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	$default_options = array(
		'type'=>'awards',
		'label'=>'awards',	
		'description'=>'',
		'show'=>array('award-website','receival-date-month'),
		'multiple'=>true,
		'show_multiple'=>true,
		'show_fields'=>array('award-website','receival-date-month')
		);
	
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			profile_cct_awards_field($item_data,$options,$count);
			$count++;
		endforeach;
		
	else:
		profile_cct_awards_field($data,$options);
	endif;
	$field->end_field( $action, $options );
	
}
/**
 * profile_cct_graduatestudent_field function.
 * 
 * @access public
 * @param mixed $data
 * @param mixed $options
 * @return void
 */
function profile_cct_awards_field( $data, $options, $count = 0 ){
	
	extract( $options );
	
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	$year_built_min = date("Y")-50;
    $year_built_max = date("Y");
    $awarded_year_array = range($year_built_max, $year_built_min);
	
	echo "<div data-count='".$count."'>";
	$field->input_field( array( 'field_type'=>$type,'multiple'=>$multiple,'field_id'=>'award-name','label'=>'Award Name', 'size'=>25, 'value'=>$data['award-name'], 'type'=>'text','count'=>$count));
	$field->input_field( array( 'field_type'=>$type,'multiple'=>$multiple,'field_id'=>'award-website', 'label'=>'Website - http://','size'=>35, 'value'=>$data['award-website'],'type'=>'text', 'show' => in_array("award-website",$show),'count'=>$count));
	$field->input_field( array( 'field_type'=>$type,'multiple'=>$multiple,'field_id'=>'receival-date-month','label'=>'Month', 'size'=>35, 'value'=>$data['receival-date-month'], 'all_fields'=>profile_cct_list_of_months(), 'type'=>'select', 'show' => in_array("receival-date-month",$show),'count'=>$count) );
	$field->input_field( array( 'field_type'=>$type,'multiple'=>$multiple,'field_id'=>'receival-date-year','label'=>'Year', 'size'=>35, 'value'=>$data['receival-date-year'], 'all_fields'=>$awarded_year_array, 'type'=>'select', 'count'=>$count) );
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}
function profile_cct_awards_display_shell( $action, $options=null, $data ) {

	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."

	$default_options = array(
		'type'=>'name',
		'label'=>'name',
		'width' => 'full',
		'link_to'=>true,
		'show_link_to' =>true,
		'hide_label'=>true,
		'before'=>'',
		'empty'=>'',
		'after'=>'',
		'show'=>array('award-website','receival-date-month'),
		'show_fields'=>array('award-website','receival-date-month')
		);
		
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	if( !$field->is_array_empty($data, array('receival-date-month','receival-date-year')) ||  $action == "edit" ):
		$field->start_field($action,$options);
	
		if( $field->is_data_array( $data ) ):
			foreach($data as $item_data):
				if( !$field->is_array_empty($item_data, array('receival-date-month','receival-date-year')) ||  $action == "edit" ):
					profile_cct_awards_display($item_data,$options);
				endif;
			endforeach;
			
		else:
			profile_cct_awards_display($data,$options);
		endif;
		
		$field->end_field( $action, $options );
	
	else:
		echo $empty;
	endif;

}
function profile_cct_awards_display( $data, $options ){
	
	global $post;
	
	extract( $options );
	
	$field = Profile_CCT::get_object();
	
	$show = (is_array($show) ? $show : array());
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'awards', 'type'=>'shell', 'tag'=>'div' ) );
	
	if( empty($data['award-website']) ):
		$field->display_text( array( 'field_type'=>$type, 'class'=>'award-name','default_text'=>'Gotham Prize for Cancer Research', 'value'=>$data['award-name'], 'type'=>'text' ));
	else:
		$field->display_text( array( 'field_type'=>$type, 'class'=>'award-name','default_text'=>'Gotham Prize for Cancer Research', 'value'=>$data['award-name'], 'type'=>'text', 'tag'=> 'a', 'href'=> $data['award-website'] ) );
	endif;
	$field->display_text( array( 'field_type'=>$type, 'class'=>'receival-date-month','default_text'=>'November', 'value'=>$data['receival-date-month'], 'type'=>'text', 'show'=> in_array("receival-date-month",$show)) );
	$field->display_text( array( 'field_type'=>$type, 'class'=>'receival-date-year','default_text'=>'2011', 'separator'=>',', 'value'=>$data['receival-date-year'], 'type'=>'text' ));
	$field->display_text( array( 'field_type'=>$type, 'type'=>'end_shell', 'tag'=>'div') );
	
}