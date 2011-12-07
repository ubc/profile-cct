<?php 


add_action('profile_cct_admin_pages', 'profile_cct_add_secondary_address_fields_filter', 10, 1);

add_action('profile_cct_form', 'profile_cct_add_secondary_address_fields_filter', 5);
add_action('profile_cct_page', 'profile_cct_add_secondary_address_fields_filter', 5);
add_action('profile_cct_secondary_address_add_meta_box','profile_cct_secondary_address_add_meta_box',10, 4 );

function profile_cct_add_secondary_address_fields_filter($type_of= null){
	
	// for now only on pages and lists
	// if(in_array($type_of, array('page','list')) )
	add_filter( 'profile_cct_dynamic_fields', 'profile_cct_add_secondary_address_fields' );
		
	add_action('profile_cct_display_shell_secondary_address', 'profile_cct_address_display_shell',10, 3);
	add_action('profile_cct_field_shell_secondary_address', 'profile_cct_address_field_shell',10, 3);
	
}

function profile_cct_add_secondary_address_fields( $fields ){
	$fields[] = array( "type"=> 'secondary_address', "label"=> "Secondary Address");
	return $fields;
}

function profile_cct_secondary_address_add_meta_box($field, $context, $data, $i){
	add_meta_box( 
		$field['type']."-".$i.'-'.rand(0,999), 
		$field['label'], 
		'profile_cct_address_field_shell', 
		'profile_cct', $context, 'low', 
		array(
			'options'=>$field,
			'data'=>$data
			)
	);
}
