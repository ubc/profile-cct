<?php 

Class Profile_CCT_Email extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'email',
		'label'         => 'email',
		'description'   => '',
		'multiple'      => true,
		'show_multiple' => true,
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	var $shell = array(
		'class' => 'email',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$current_user = wp_get_current_user();
		
		$this->input_text( array(
			'field_id' => 'email',
			'label'    => '',
			'size'     => 35,
			'default'  => $current_user->user_email,
		) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_email( array(
			'field_id'     => 'email',
			'default_text' => 'bruce.wayne@wayneenterprises.com',
		) );
	}
	
	/**
	 * display_email function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function display_email( $attr ) {
		$value = $this->data['email'];
		
		if ( empty( $attr['mailto'] ) ):
			$attr['mailto'] = ( 'edit' == $this->action ? $attr['default_text'] : $value );
        endif;
		
		$attr['value'] = $value;
		$attr['href']  = ( empty( $attr['mailto'] ) ? '' : 'mailto:'.$attr['mailto'] );
		
		$this->display_link( $attr );
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
		new Profile_CCT_Email( $options, $data ); 
	}
}

/**
 * profile_cct_email_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_email_shell( $options, $data ) {
	Profile_CCT_Email::shell( $options, $data );
}

/*
function profile_cct_email_display_shell( $options, $data ) {
	
	Profile_CCT_Email::shell( $options, $data );
	
}



function profile_cct_email_field( $data, $options, $count = 0 ){
	
	extract( $options );
	$field = Profile_CCT::get_object();
	echo "<div class='wrap-fields' data-count='".$count."'>";
	
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'email', 'label' => '', 'size'=>35, 'value'=>$data['email'], 'type' => 'text','count'=>$count) );
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}




function profile_cct_email_display_shell_old(  $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'email',
		'width' => 'full',
		'before' => '',
		'empty' => '',
		'after' =>'',
		'hide_label'=>true
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	

	if( !$field->is_array_empty($data) ||  $action == "edit" ):
		$field->start_field($action,$options);
	
		if( $field->is_data_array( $data ) ):
			foreach($data as $item_data):
				if( !$field->is_array_empty($item_data) ||  $action == "edit" ):
					profile_cct_email_display($item_data,$options);
				endif;
			endforeach;
		
		else:
			profile_cct_email_display($item_data,$options);
		endif;
		
		$field->end_field( $action, $options );
	
	else:
		echo $options['empty'];
	endif;
	
}
function profile_cct_email_display( $data, $options ){
	
	extract( $options );
	$show = (is_array($show) ? $show : array());
	$field = Profile_CCT::get_object();
	

	$field->display_text( array( 'field_type'=>$type, 'class' => 'email', 'type' => 'shell', 'tag' => 'div') );
	$field->display_text( array( 'field_type'=>$type, 'default_text' => 'bruce.wayne@wayneenterprises.com', 'value'=>$data['email'], 'type' => 'text', 'tag' => 'a', 'href' => 'mailto:'.$data['email']) );
	$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'div') );
	
}
*/