<?php

function profile_cct_column_register( $columns ) {
	unset($columns);
	$columns["cb"]= '<input type="checkbox" />';
    
    $columns['title'] = __( "Name" );
    $columns['last_name'] = __("Last Name");
    $columns['thumb'] = __( 'Picture', 'profile_cct' );
    $columns['author'] = __( "Author" );     
    $columns['date'] = __( "Date" );
  
	return $columns;
}
add_filter( 'manage_edit-profile_cct_columns', 'profile_cct_column_register' );


function profile_cct_column_display_thumb( $column_name, $post_id ) {
	
	
	if ( 'thumb' != $column_name )
		return;
	
	echo profile_cct_get_the_post_thumbnail($post_id, array(50,50) );
}
add_action( 'manage_profile_cct_posts_custom_column', 'profile_cct_column_display_thumb', 10, 2 );


function profile_cct_column_display_last_name( $column_name, $post_id ) {
	
	if ( 'last_name' != $column_name )
		return;
	
	echo get_post_meta( $post_id, 'profile_cct_last_name', true);
	
}
add_action( 'manage_profile_cct_posts_custom_column', 'profile_cct_column_display_last_name', 10, 2 );

