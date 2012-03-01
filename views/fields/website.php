<?php 

function profile_cct_website_field_shell($action,$options) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'website',
		'label'=> 'website',
		'description'=> '',
		'multiple'=> true,
		'show'=>array(),
		'show_fields'=>array('site-title'),
		'show_multiple' =>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );

	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			profile_cct_website_field($item_data,$options,$count);
			$count++;
		endforeach;
	else:
		profile_cct_website_field($data,$options);
	endif;
	
	$field->end_field( $action, $options );
	
}
function profile_cct_website_field( $data, $options, $count = 0 ){

	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	
	echo "<div class='wrap-fields' data-count='".$count."'>";
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'website', 'label'=>'Website - http://', 'size'=>35, 'value'=>$data['website'], 'type'=>'text','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'site-title', 'label'=>'Site title', 'size'=>35, 'value'=>$data['site-title'], 'type'=>'text', 'show'=>in_array('site-title', $show),'count'=>$count) );
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}

function profile_cct_website_display_shell( $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'website',
		'label'=> 'website',
		'hide_label'=>true,
		'before'=>'',
		'empty'=>'',
		'width' => 'full',
		'after'=>'',
		
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );

	if( !$field->is_array_empty( $data ) ||  $action == "edit" ):
		$field->start_field( $action, $options );
		if( $field->is_data_array( $data ) ):
			foreach($data as $item_data):
				if( !$field->is_array_empty( $item_data ) ||  $action == "edit" ):
					profile_cct_website_display( $item_data, $options );
				endif;		
			endforeach;
		else:
			profile_cct_website_display( $data , $options );
		endif;
		
		$field->end_field( $action, $options );
	else:
		echo $options['empty'];
	endif;
	
}
function profile_cct_website_display( $data, $options ){

	extract( $options );
	$field = Profile_CCT::get_object();
	$name = (!empty($data['site-title']) ? $data['site-title'] : $data['website'] );
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'website', 'type'=>'shell', 'tag'=>'div') );
	$field->display_text( array( 'field_type'=>$type, 'default_text'=>'http://wayneenterprises.biz', 'value'=>$name, 'type'=>'text', 'tag'=>'a', 'href'=>$data['website']) );
	$field->display_text( array( 'field_type'=>$type, 'type'=>'end_shell', 'tag'=>'div') );
	
}