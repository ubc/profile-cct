<?php 

// lets you add fields that are dynamically created by adding to the clone_fields in the settings option
if( is_array($this->settings_options['clone_fields']) ): 
	// add the fields that need to be applied to the form page
	add_action('profile_cct_form', 			'profile_cct_add_db_fields_filter', 5);
	
	add_action('profile_cct_admin_pages', 	'profile_cct_add_db_fields_filter', 10, 1);
	add_action('profile_cct_form', 			'profile_cct_add_db_fields_filter', 5);
	add_action('profile_cct_page', 			'profile_cct_add_db_fields_filter', 5);
	
	foreach($this->settings_options['clone_fields'] as $field):
		add_action('profile_cct_'.$field['type'].'_add_meta_box','profile_cct_db_add_meta_box',10, 4 );
	endforeach;
endif;

/**
 * profile_cct_add_db_fields_filter function.
 * 
 * @access public
 * @param mixed $type_of (default: null)
 * @return void
 */
function profile_cct_add_db_fields_filter($type_of= null){
	
	
	$profile_cct = Profile_CCT::get_object();
	
	add_filter( 'profile_cct_dynamic_fields', 'profile_cct_add_db_fields' );
	
	foreach($profile_cct->settings_options['clone_fields'] as $field):
		
		add_action('profile_cct_display_shell_'.$field['type'], 'profile_cct_'.$field['field_clone'].'_display_shell',10, 3);	
		add_action('profile_cct_shell_'.$field['type'], 'profile_cct_'.$field['field_clone'].'_shell',10, 3);
	endforeach;
	
}

/**
 * profile_cct_add_db_fields function.
 * 
 * @access public
 * @param mixed $fields
 * @return void
 */
function profile_cct_add_db_fields( $fields ){
	
	$profile_cct = Profile_CCT::get_object();
	
	foreach($profile_cct->settings_options['clone_fields'] as $field):
		
		$fields[] = array( "type"=> $field['type'], "label"=> $field['label']);
	endforeach;
	
	return $fields;
}


/**
 * profile_cct_db_add_meta_box function.
 * 
 * @access public
 * @param mixed $field
 * @param mixed $context
 * @param mixed $data
 * @param mixed $i
 * @return void
 */
function profile_cct_db_add_meta_box($field, $context, $data, $i){
	$profile_cct = Profile_CCT::get_object();
	foreach($profile_cct->settings_options['clone_fields'] as $db_field):
		
		if($db_field['type'] == $field['type']):
	
		add_meta_box( 
			$field['type']."-".$i.'-'.rand(0,999), 
			$field['label'], 
			'profile_cct_'.$db_field['field_clone'].'_shell', 
			'profile_cct', $context, 'core', 
			array(
				'options'=>$field,
				'data'=>$data
				)
		);
		break;
		endif;
	endforeach;
	
}
