<?php 
/**
 * Profile_CCT_Textarea class.
 * 
 * @extends Profile_CCT_Field
 */
class Profile_CCT_Textarea extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'        => 'textarea',
		'label'       => 'textarea',
		'description' => '',
		'width'       => 'full',
		'before'      => '',
		'empty'       => '',
		'after'       => '',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_textarea( array(
			//'field_id' => 'textarea',
			'name'     => 'profile_cct['.$this->type.'][textarea]',
			'label'    => '',
			'size'     => 25,
			'row'      => 2,
			'cols'     => 20,
			'value'    => ( isset( $this->data['textarea'] ) ? $this->data['textarea']  : null ),
		) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_textfield( array(
			//'field_id'     => 'textarea',
			'class'        => 'textarea',
			'default_text' => 'lorem ipsum',
			'value'        => ( isset( $this->data['textarea'] ) ? $this->data['textarea']  : null ),
		) );
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
		new Profile_CCT_Textarea( $options, $data ); 
	}
}

/**
 * profile_cct_textarea_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_textarea_shell( $options, $data ) {
	Profile_CCT_Textarea::shell( $options, $data );
}

/*

function profile_cct_textarea_display_shell( $options, $data ) {
	
	Profile_CCT_Textarea::shell( $options, $data );
	
}
function profile_cct_textarea_shell_old($action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'textarea',
		'label'=> 'textarea',
		'description'=> '',
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	
	$field->start_field($action,$options);
	
	profile_cct_textarea_field($data,$options);
	
	$field->end_field( $action, $options );
	
}
function profile_cct_textarea_field( $data, $options ){

	extract( $options );
	$field = Profile_CCT::get_object();
	
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'textarea', 'label' => '', 'size'=>25, 'row'=>2, 'cols'=>20, 'value'=>$data['textarea'], 'type' => 'textarea') );

}

function profile_cct_textarea_display_shell_old( $action, $options, $data=null  ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'type' => 'textarea',
		'label'=> 'textarea',
		'width' => 'full',
		'hide_label'=>true,
		'before' => '',
		'empty' => '',
		'after' => ''
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	if( !empty($data['textarea']) ||  $action == "edit" ):
		$field->start_field($action,$options);
		
		profile_cct_textarea_display($data,$options);
		
		$field->end_field( $action, $options );
	endif;
	
}
function profile_cct_textarea_display( $data, $options ){

	extract( $options );
	$field = Profile_CCT::get_object();
	
	$field->display_text( array( 'field_type'=>$type, 'class' => 'textarea textarea','default_text' => 'lorem ipsum', 'content_filter' => 'profile_escape_html', 'value'=>$data['textarea'], 'tag' => 'div', 'type' => 'text') );
}

function profile_cct_textarea_filter( $data ) {
	$data = wp_kses_post( $data );
	return apply_filters('the_content', $data);
}

add_filter( 'profile_escape_html', 'profile_cct_textarea_filter');
*/