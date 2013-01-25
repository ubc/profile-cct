<?php
Class Profile_CCT_Website extends Profile_CCT_Field {
	/**
	 * default_options
	 * 
	 * @var mixed
	 * @access public
	 */
	var $default_options = array(
		'type'          => 'website',
		'label'         => 'website',
		'description'   => '',
		'multiple'      => true,
		'show_multiple' => true,
		'show'          => array(),
		'show_fields'   => array('site-title'),
		'width'         => 'full',
		'before'        => '',
		'empty'         => '',
		'after'         => '',
	);
	
	/**
	 * field function.
	 * 
	 * @access public
	 * @return void
	 */
	function field() {
		$this->input_text( array(
			'field_id' => 'website',
			'label'    => 'Website - http://{value}',
			'size'     => 35,
		) );
		$this->input_text( array(
			'field_id' => 'site-title',
			'label'    => 'Site title',
			'size'     => 35,
		) );
	}
	
	/**
	 * display function.
	 * 
	 * @access public
	 * @return void
	 */
	function display() {
		$this->display_shell( array( 'class' => 'website' ) );
		$this->display_link( array(
			'field_id'     => 'site-title',
			'default_text' => 'http://wayneenterprises.biz',
			'value'        => $this->data['site-title'],
			'href'         => ( empty( $this->data['website'] ) ? '' : 'http://'.$this->data['website'] ),
			'force_link'   => true,
		) );
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
		new Profile_CCT_Website( $options, $data ); 
	}	
}

/**
 * profile_cct_website_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data
 * @return void
 */
function profile_cct_website_shell( $options, $data ) {
	Profile_CCT_Website::shell( $options, $data ); 
}

/*
function profile_cct_website_display_shell( $options, $data ) {
	Profile_CCT_Website::shell( $options, $data ); 

}

function profile_cct_website_shellasdas($action,$options) {
	
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
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'website', 'label' => 'Website - http://', 'size'=>35, 'value'=>$data['website'], 'type' => 'text','count'=>$count) );
	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id' => 'site-title', 'label' => 'Site title', 'size'=>35, 'value'=>$data['site-title'], 'type' => 'text', 'show'=>in_array('site-title', $show),'count'=>$count) );
	if($count)
	 			echo ' <a class="remove-fields button" href="#">Remove</a>';
	echo "</div>";
}

function profile_cct_website_display_shellasdasd( $action, $options, $data=null ) {
	
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
		'before' => '',
		'empty' => '',
		'width' => 'full',
		'after' => '',
		
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
	
	$field->display_text( array( 'field_type'=>$type, 'class' => 'website', 'type' => 'shell', 'tag' => 'div') );
	$field->display_text( array( 'field_type'=>$type, 'default_text' => 'http://wayneenterprises.biz', 'value'=>$name, 'type' => 'text', 'tag' => 'a', 'href'=>$field->correct_URL( $data['website'] ) ) );
	$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'div') );
	
}
*/