<?php 

Class Profile_CCT_Awards extends Profile_CCT_Field {
		
		var $default_options = array(
			'type' => 'awards',
			'label' => 'awards',
			'description' => '',
			
			'show'=>array('award-website','receival-date-month'),
			'show_fields'=>array('award-website','receival-date-month', 'receival-date-year'),
			
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
		$this->input_text( array( 'field_id' => 'award-name','label' => 'Award Name', 'size'=>25 )  );
		$this->input_text( array( 'field_id' => 'award-website', 'label' => 'Website - http://','size'=>35 ) );
		$this->input_select( array( 'field_id' => 'receival-date-month','label' => 'Month', 'size'=>35, 'all_fields'=>$this->list_of_months() ) );
		$this->input_select( array( 'field_id' => 'receival-date-year','label' => 'Year', 'size'=>35, 'all_fields'=>$this->list_of_years() ) );

	}
	
	function display() {
		
		$this->display_text( array( 'field_type'=>$type, 'class' => 'awards', 'type' => 'shell', 'tag' => 'div' ) );
		
		if( empty($data['award-website']) ):
			$this->display_text( array( 'field_type'=>$type, 'class' => 'award-name','default_text' => 'Gotham Prize for Cancer Research', 'value'=>$data['award-name'], 'type' => 'text' ));
		else:
			$this->display_text( array( 'field_type'=>$type, 'class' => 'award-name','default_text' => 'Gotham Prize for Cancer Research', 'value'=>$data['award-name'], 'type' => 'text', 'tag'=> 'a', 'href'=> $field->correct_URL($data['award-website']) ) );
		endif;
		$this->display_text( array( 'field_type'=>$type, 'class' => 'receival-date-month','default_text' => 'November', 'value'=>$data['receival-date-month'], 'type' => 'text', 'show'=> in_array("receival-date-month",$show)) );
		$this->display_text( array( 'field_type'=>$type, 'class' => 'receival-date-year','default_text' => '2011', 'separator' => ',', 'value'=>$data['receival-date-year'], 'type' => 'text' ));
		$this->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'div') );

	}
	
	public static function shell( $options, $data ) {
		new Profile_CCT_Awards( $options, $data ); 
	}
	
}



/**
 * profile_cct_awards_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data (default: null)
 * @return void
 */
function profile_cct_awards_shell( $options, $data = null ) {

	Profile_CCT_Awards::shell( $options, $data );
}




/**
 * profile_cct_awards_display_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data (default: null)
 * @return void
 */
function profile_cct_awards_display_shell( $options, $data=null ) {
			
	Profile_CCT_Awards::shell( $options, $data );
	
}

