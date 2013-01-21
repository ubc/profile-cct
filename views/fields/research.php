<?php 
/**
 * profile_cct_research_shell function.
 * 
 * @access public
 * @param array $options (default: array())
 * @param mixed $data (default: null)
 * @return void
 */
function profile_cct_research_shell( $options = array(), $data = null ) {
	$options[ "type" ]  = 'research'; // make sure that you can't over write this
	$options[ "label" ] = ( !empty( $options[ "label" ] ) ? $options[ "label" ] : 'Research' );
	
	Profile_CCT_Textarea::shell( $options, $data );
}

/*


add_action('profile_cct_admin_pages', 'profile_cct_add_research_fields_filter', 10, 1);

add_action('profile_cct_form', 'profile_cct_add_research_fields_filter', 5);
add_action('profile_cct_page', 'profile_cct_add_research_fields_filter', 5);
add_action('profile_cct_research_add_meta_box','profile_cct_research_add_meta_box',10, 4 );

function profile_cct_add_research_fields_filter($type_of= null){
	
	// for now only on pages and lists
	// if(in_array($type_of, array('page','list')) )
	add_filter( 'profile_cct_dynamic_fields', 'profile_cct_add_research_fields' );
		
	add_action('profile_cct_display_shell_research', 'profile_cct_textarea_display_shell',10, 3);
	add_action('profile_cct_shell_research', 'profile_cct_textarea_shell',10, 3);
	
}

function profile_cct_add_research_fields( $fields ){
	$fields[] = array( "type"=> 'research', "label"=> "Research");
	return $fields;
}

function profile_cct_research_add_meta_box($field, $context, $data, $i){
	add_meta_box( 
		$field['type']."-".$i.'-'.rand(0,999), 
		$field['label'], 
		'profile_cct_textarea_shell', 
		'profile_cct', $context, 'high', 
		array(
			'options'=>$field,
			'data'=>$data
			)
	);
}

*/
