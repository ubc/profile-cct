<?php

Class Profile_CCT_Graduatestudent extends Profile_CCT_Field {
		
		var $default_options = array(
			'type' => 'graduatestudent',
			'label' => 'graduate student',	
			'description' => '',
			
			'show'=>array( 'student-salutations','student-middle','student-credentials','student-website'),
			'show_fields'=>array('student-salutations','student-middle','student-credentials','student-website'),
			
			'multiple'=>true,
			'show_multiple'=>true,
		
			'link_to'=>true,
			'show_link_to' =>true,
			
			'width' => 'full',
			'before' => '',
			'empty' => '',
			'after' =>'',
		);
	
	function field() {
		$this->input_text( array( 'field_id' => 'student-salutations','label' => 'Salutations', 'size'=>2 ) );
		$this->input_text( array( 'field_id' => 'student-first','label' => 'First', 'size'=>14 ) );
		$this->input_text( array( 'field_id' => 'student-middle','label' => 'Middle', 'size'=>3 ) );
		$this->input_text( array( 'field_id' => 'student-last','label' => 'Last', 'size'=>19 ) );
		$this->input_text( array( 'field_id' => 'student-credentials', 'label' => 'Credentials','size'=>7 ) );
		$this->input_text( array( 'field_id' => 'student-website', 'label' => 'Website - http://','size'=>35 ) );

	}
	
	function display() {
		/*
		$field->display_text( array( 'field_type'=>$type, 'class' => 'graduatestudent', 'type' => 'shell', 'tag' => 'div' ) );
		$field->display_text( array( 'field_type'=>$type, 'class' => 'honorific-prefix student-salutations','default_text' => 'Mr', 'value'=>$data['student-salutations'], 'type' => 'text' , 'show' => in_array("student-salutations",$show)) );
		$field->display_text( array( 'field_type'=>$type, 'class' => 'student-given-name','default_text' => 'Richard', 'value'=>$data['student-first'], 'type' => 'text' ));
		$field->display_text( array( 'field_type'=>$type, 'class' => 'additional-name student-middle','default_text' => 'John', 'value'=>$data['student-middle'], 'type' => 'text', 'show' => in_array("student-middle",$show) ));
		$field->display_text( array( 'field_type'=>$type, 'class' => 'student-family-name','default_text' => 'Grayson', 'value'=>$data['student-last'], 'type' => 'text' ));
		$field->display_text( array( 'field_type'=>$type, 'class' => 'honorific-suffix suffix student-credentials','separator' => ',','default_text' => 'B.S.S.', 'value'=>$data['student-credentials'],'type' => 'text', 'show' => in_array("student-credentials",$show)));
		$field->display_text( array( 'field_type'=>$type, 'class' => 'student-website','default_text' => 'http://richardjohngrayson.com/', 'type' => 'text', 'tag' => 'a', 'href'=>$field->correct_URL($data['student-website']), 'show' => in_array("student-website",$show) ));
		$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'div') );
		*/
	}
	
	public static function shell( $options, $data ) {
		new Profile_CCT_Graduatestudent( $options, $data ); 
	}
	
}

function profile_cct_graduatestudent_shell( $options, $data ) {
	Profile_CCT_Graduatestudent::shell( $options, $data ); 
}

function profile_cct_graduatestudent_display_shell( $options, $data ) {
	Profile_CCT_Graduatestudent::shell( $options, $data ); 
}



/**
 * profile_cct_graduatestudent_shell function.
 * 
 * @access public
 * @param mixed $action
 * @param mixed $options. (default: null)
 * @return void
 */
function profile_cct_graduatestudent_shell_old( $action, $options=null ) {
	
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
		
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	$default_options = array(
		'type' => 'graduatestudent',
		'label' => 'graduatestudent',	
		'description' => '',
		'show'=>array('student-salutations','student-middle','student-credentials','student-website'),
		'multiple'=>true,
		'show_multiple'=>true,
		'show_fields'=>array('student-salutations','student-middle','student-credentials','student-website')
		);
	
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			profile_cct_graduatestudent_field($item_data,$options,$count);
			$count++;
		endforeach;
		
	else:
		profile_cct_graduatestudent_field($data,$options);
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
function profile_cct_graduatestudent_field( $data, $options, $count = 0 ){
	
	extract( $options );
	
	$field = Profile_CCT::get_object();
	
	$show = (is_array($show) ? $show : array());
	
	echo '<div data-count="'.$count.'" class="wrap-fields">';
	
	$field->input_field( array( 'field_id' => 'student-salutations','label' => 'Salutations', 'size'=>2, 'value'=>$data['student-salutations'], 'type' => 'text', 'show' => in_array("student-salutations",$show),'count'=>$count) );
	$field->input_field( array( 'field_id' => 'student-first','label' => 'First', 'size'=>14, 'value'=>$data['student-first'], 	'type' => 'text','count'=>$count));
	$field->input_field( array( 'field_id' => 'student-middle','label' => 'Middle', 'size'=>3,'value'=>$data['student-middle'], 'type' => 'text','show'=>in_array("student-middle",$show),'count'=>$count));
	$field->input_field( array( 'field_id' => 'student-last','label' => 'Last', 'size'=>19, 'value'=>$data['student-last'], 	'type' => 'text','count'=>$count));
	$field->input_field( array( 'field_id' => 'student-credentials', 'label' => 'Credentials','size'=>7, 'value'=>$data['student-credentials'],'type' => 'text',  'show' => in_array("student-credentials",$show),'count'=>$count));
	$field->input_field( array( 'field_id' => 'student-website', 'label' => 'Website - http://','size'=>35, 'value'=>$data['student-website'],'type' => 'text',  'show' => in_array("student-website",$show),'count'=>$count));
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	
	echo '</div>';
}
function profile_cct_graduatestudent_display_shell_old( $action, $options=null, $data ) {

	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."

	$default_options = array(
		'type' => 'name',
		'label' => 'name',
		'width' => 'full',
		'link_to'=>true,
		'show_link_to' =>true,
		'hide_label'=>true,
		'before' => '',
		'empty' => '',
		'after' => '',
		'show'=>array('student-salutations','student-middle','student-credentials','student-website'),
		'show_fields'=>array('student-salutations','student-middle','student-credentials','student-website')
		);
		
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	if( !$field->is_array_empty($data) ||  $action == "edit" ):
		$field->start_field($action,$options);
	
		if( $field->is_data_array( $data ) ):
			foreach($data as $item_data):
				if( !$field->is_array_empty($item_data) ||  $action == "edit" ):
					profile_cct_graduatestudent_display($item_data,$options);
				endif;
			endforeach;
			
		else:
			profile_cct_graduatestudent_display($data,$options);
		endif;
		
		$field->end_field( $action, $options );
	
	else:
		echo $options['empty'];
	endif;

}
function profile_cct_graduatestudent_display( $data, $options ){
	
	global $post;
	
	extract( $options );
	
	$field = Profile_CCT::get_object();
	
	$show = (is_array($show) ? $show : array());
	
	$field->display_text( array( 'field_type'=>$type, 'class' => 'graduatestudent', 'type' => 'shell', 'tag' => 'div' ) );
	$field->display_text( array( 'field_type'=>$type, 'class' => 'honorific-prefix student-salutations','default_text' => 'Mr', 'value'=>$data['student-salutations'], 'type' => 'text' , 'show' => in_array("student-salutations",$show)) );
	$field->display_text( array( 'field_type'=>$type, 'class' => 'student-given-name','default_text' => 'Richard', 'value'=>$data['student-first'], 'type' => 'text' ));
	$field->display_text( array( 'field_type'=>$type, 'class' => 'additional-name student-middle','default_text' => 'John', 'value'=>$data['student-middle'], 'type' => 'text', 'show' => in_array("student-middle",$show) ));
	$field->display_text( array( 'field_type'=>$type, 'class' => 'student-family-name','default_text' => 'Grayson', 'value'=>$data['student-last'], 'type' => 'text' ));
	$field->display_text( array( 'field_type'=>$type, 'class' => 'honorific-suffix suffix student-credentials','separator' => ',','default_text' => 'B.S.S.', 'value'=>$data['student-credentials'],'type' => 'text', 'show' => in_array("student-credentials",$show)));
	$field->display_text( array( 'field_type'=>$type, 'class' => 'student-website','default_text' => 'http://richardjohngrayson.com/', 'type' => 'text', 'tag' => 'a', 'href'=>$field->correct_URL($data['student-website']), 'show' => in_array("student-website",$show) ));
	$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'div') );
	
}