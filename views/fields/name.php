<?php 


add_action('profile_cct_form','profile_cct_name_field_shell',10,2);

function profile_cct_name_field_shell($action) {
	
	
	//echo $type;
	$field = Profile_CCT::set(); // prints "Creating new instance."
	// substr(basename(__FILE__),0,-4) 
	
	$options = $field->form_fields['name']; // stuff that is comming from the db
	
	$default_options = array(
		'label'=>'name',
		'description'=>'just enter your name',
		'show'=>array('prefix','middle','suffix'),
		'show_fields'=>array('prefix','middle','suffix')
		);
		
	$options = (is_array($options) ? $options: $default_options );
	echo "<ul class='form-builder'>";
	$field->start_field('name',$action,$options);
	
	profile_cct_name_field($data,$options);
	
	$field->end_field($options);
	echo "</ul>";
}
function profile_cct_name_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::set();
	
	if(in_array("prefix",$show))
		$field->label_field( array( 'label'=>'Title', 'size'=>2, 'value'=>$data['title'], 'tabindex'=>1) );
	
	$field->label_field( array( 'label'=>'First', 'size'=>14, 'value'=>$data['title'], 'tabindex'=>2));
	if(in_array("middle",$show))
		$field->label_field( array( 'label'=>'Middle', 'size'=>3,'value'=>$data['middle'], 'tabindex'=>3));
	
	$field->label_field( array( 'label'=>'Last', 'size'=>19, 'value'=>$data['last'], 'tabindex'=>4));
	
	if(in_array("suffix",$show))
		$field->label_field( array( 'label'=>'Sufix','size'=>3, 'value'=>$data['suffix'], 'tabindex'=>5));

}


