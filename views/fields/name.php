<?php 

/**
 * profile_cct_name_field_shell function.
 * 
 * @access public
 * @param mixed $action
 * @param mixed $options. (default: null)
 * @return void
 */
function profile_cct_name_field_shell( $action, $options=null ) {
	
	
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
		'description'=>'',
		'show'=>array('title','middle','suffix'),
		'show_fields'=>array('title','middle','suffix')
		);
	
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	
	profile_cct_name_field($data,$options);
	
	$field->end_field( $action, $options );
	
}
/**
 * profile_cct_name_field function.
 * 
 * @access public
 * @param mixed $data
 * @param mixed $options
 * @return void
 */
function profile_cct_name_field( $data, $options ){
	
	extract( $options );
	
	$field = Profile_CCT::get_object();
	
	$show = (is_array($show) ? $show : array());
	$field->input_field( array( 'field_type'=>$type,'field_id'=>'title','label'=>'Title', 'size'=>2, 'value'=>$data['title'], 	'type'=>'text', 'show' => in_array("title",$show)) );
	$field->input_field( array( 'field_type'=>$type,'field_id'=>'first','label'=>'First', 'size'=>14, 'value'=>$data['first'], 	'type'=>'text', ));
	$field->input_field( array( 'field_type'=>$type,'field_id'=>'middle','label'=>'Middle', 'size'=>3,'value'=>$data['middle'], 'type'=>'text', 'show' => in_array("middle",$show) ));
	$field->input_field( array( 'field_type'=>$type,'field_id'=>'last','label'=>'Last', 'size'=>19, 'value'=>$data['last'], 	'type'=>'text', ));
	$field->input_field( array( 'field_type'=>$type,'field_id'=>'suffix', 'label'=>'Suffix','size'=>3, 'value'=>$data['suffix'],'type'=>'text',  'show' => in_array("suffix",$show)));
	
}
function profile_cct_name_display_shell( $action, $options=null, $data ) {

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
		'after'=>'',
		'show'=>array('title','middle','suffix'),
		'show_fields'=>array('title','middle','suffix')
		);
		
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	
	profile_cct_name_display($data,$options);
	
	$field->end_field( $action, $options );

}
function profile_cct_name_display( $data, $options ){
	
	global $post;
	
	extract( $options );
	
	$field = Profile_CCT::get_object();
	
	$show = (is_array($show) ? $show : array());
	 
	$href = ( isset($post) ? get_permalink() : "#" );
	
	$field->display_text( array( 'field_type'=>$type, 'class'=>'fn n', 'type'=>'shell', 'tag'=>'h2','link_to'=>$link_to, 'href'=>$href ) );
	$field->display_text( array( 'field_type'=>$type, 'class'=>'honorific-prefix title','default_text'=>'Mr', 'value'=>$data['title'], 'type'=>'text' , 'show' => in_array("title",$show)) );
	$field->display_text( array( 'field_type'=>$type, 'class'=>'given-name','default_text'=>'Bruce', 'value'=>$data['first'], 	'type'=>'text', ));
	$field->display_text( array( 'field_type'=>$type, 'class'=>'additional-name middle','default_text'=>'Anthony', 'value'=>$data['middle'], 'type'=>'text', 'show' => in_array("middle",$show) ));
	$field->display_text( array( 'field_type'=>$type, 'class'=>'family-name','default_text'=>'Wayne', 'value'=>$data['last'], 	'type'=>'text', ));
	$field->display_text( array( 'field_type'=>$type, 'class'=>'honorific-suffix suffix','default_text'=>'BCom', 'value'=>$data['suffix'],'type'=>'text',  'show' => in_array("suffix",$show)));
	$field->display_text( array( 'field_type'=>$type, 'type'=>'end_shell', 'tag'=>'h2','link_to'=>$link_to) );
	
}


