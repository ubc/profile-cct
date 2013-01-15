<?php 

class Profile_CCT_Phone extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type' => 'phone',
		'label' => 'phone',
		'description' => '',
		
		'show'=> array( 'tel-1' ),
		'show_fields'=> array( 'tel-1', 'extension' ),
		
		'multiple' => true,
		'show_multiple' => true,
		
		'width' => 'full',
		'before' => '',
		'empty' => '',
		'after' => '',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_select( array( 'field_id' => 'option','label' => 'Option', 'all_fields' => $this->phone_options() ) );
		$this->input_text( array( 'field_id' => 'tel-1','label' => '###', 'size' => 3 ) );
		$this->input_text( array( 'field_id' => 'tel-2','label' => '###', 'size' => 3 ) );
		$this->input_text( array( 'field_id' => 'tel-3','label' => '####', 'size' => 4 ) );
		$this->input_text( array( 'field_id' => 'extension','label' => 'extension', 'size' => 4 ) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_shell( array( 'class' => 'telephone tel') );
		$this->display_text( array( 'field_id' => 'option', 'class' => 'type', 'default_text' => 'Work', 'post_separator' => ':' ) );
		$this->display_text( array( 'field_id' => 'tel-1', 'class' => 'tel-1', 'default_text' => '735', 'post_separator' => '-' ) );
		$this->display_text( array( 'field_id' => 'tel-2', 'class' => 'tel-2', 'default_text' => '279', 'post_separator' => '-' ) );
		$this->display_text( array( 'field_id' => 'tel-3', 'class' => 'tel-3', 'default_text' => '2963' ) );
		$this->display_text( array( 'field_id' => 'extension', 'class' => 'extension', 'default_text' => '2', 'separator' => ' ext:' ) );
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
		new Profile_CCT_Phone( $options, $data ); 
	}
	
}

/**
 * profile_cct_phone_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_phone_shell( $options, $data ) {
	Profile_CCT_Phone::shell( $options, $data ); 
}

/*
function profile_cct_phone_display_shell( $options, $data ) {
		Profile_CCT_Phone::shell( $options, $data ); 

}


function profile_cct_phone_shell_old( $action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'phone',
		'label' => 'phone',
		'description' => '',
		'show'=>array('tel-1'),
		'multiple'=>true,
		'show_multiple' =>true,
		'show_fields'=>array('tel-1','extension')
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	
	if( $field->is_data_array( $data ) ):
		$count = 0;
		foreach($data as $item_data):
			
			profile_cct_phone_field($item_data,$options, $count);
			$count++;
			
		endforeach;
		
	else:
		// this should only occure if the there is no data
		profile_cct_phone_field($data,$options);
	endif;
	
	$field->end_field( $action, $options );
	
	
}
function profile_cct_phone_field( $data, $options, $count = 0 ){

	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	
	echo "<div class='wrap-fields' data-count='".$count."'>";
	$field->input_field( array( ''field_id' => 'option','label' => 'Option',  'value'=>$data['option'], 'all_fields'=>profile_cct_phone_options(), 'type' => 'select') );
	
	$field->input_field( array( ''field_id' => 'tel-1','label' => '###', 'size'=>3, 'value'=>$data['tel-1'], 'type' => 'text', 'show' => in_array("tel-1",$show),'count'=>$count) );
	$field->input_field( array( ''field_id' => 'tel-2','label' => '###', 'size'=>3, 'value'=>$data['tel-2'], 'type' => 'text','count'=>$count) );
	$field->input_field( array( ''field_id' => 'tel-3','label' => '####', 'size'=>4, 'value'=>$data['tel-3'], 'type' => 'text','count'=>$count) );
	$field->input_field( array( ''field_id' => 'extension','label' => 'extension', 'size'=>4, 'value'=>$data['extension'], 'type' => 'text', 'show' => in_array("extension",$show),'count'=>$count) );
	
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";


}



function profile_cct_phone_display_shell_old(  $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'phone',
		'label_hide'=>true,
		'before' => '',
		'empty' => '',
		'after' => '',
		'width' => 'full',
		'show'=>array('type','tel-1'),
		'show_fields'=>array('type','tel-1','extension')
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	if( !$field->is_array_empty($data , array('option') ) ||  $action == "edit" ):
	
		$field->start_field($action,$options);
		
		if( $field->is_data_array( $data ) ):
			
			foreach($data as $item_data):
				if( !$field->is_array_empty( $item_data, array('option') ) ||  $action == "edit" ):
					profile_cct_phone_display($item_data,$options);
				endif;
			endforeach;
			
		else:
			// this shouldn't be happening unless its displaying stuff for the 
			profile_cct_phone_display($data,$options);
		endif;
		
		$field->end_field( $action, $options );
		
	else:
		echo $options['empty'];
	endif;
	
}
function profile_cct_phone_display( $data, $options ){

	extract( $options );
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	
	
	$field->display_text( array( 'field_type'=>$type, 'class' => 'telephone tel', 'type' => 'shell', 'tag' => 'div') );
	$field->display_text( array( 'field_type'=>$type,  'class' => 'type', 'default_text' => 'Work', 'value'=>$data['option'], 'type' => 'text', 'tag' => 'span') );
	
	$seperator=':';
	if(empty($data['option']))
		$seperator = '';
	$field->display_text( array( 'field_type'=>$type,  'class' => 'tel-1', 'default_text' => '735', 'separator'=>$seperator,'value'=>$data['tel-1'], 'type' => 'text', 'tag' => 'span') );
	
	$seperator = ' -';
	if(empty($data['tel-1'])):
		if(empty($data['option']))
			$seperator = '';
		else
			$seperator = ':';
	endif;
	
	
	$field->display_text( array( 'field_type'=>$type,  'class' => 'tel-2', 'default_text' => '279', 'separator'=>$seperator, 'value'=>$data['tel-2'], 'type' => 'text', 'tag' => 'span') );
	$field->display_text( array( 'field_type'=>$type,  'class' => 'tel-3', 'default_text' => '2963',  'separator' => ' -','value'=>$data['tel-3'], 'type' => 'text', 'tag' => 'span') );
	$field->display_text( array( 'field_type'=>$type,  'class' => 'extension', 'default_text' => '2', 'separator' => ' ext:','value'=>$data['extension'], 'type' => 'text', 'tag' => 'span') );
	
	$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'div') );
}



function profile_cct_phone_options(){

	return array(
			"phone",
			"work phone",
			"mobile",
			"fax",
			"work fax",
			"pager",
			"other");
}
*/