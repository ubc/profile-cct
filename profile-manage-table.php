<?php

function profile_cct_column_register( $columns ) {
	unset($columns);
	$columns["cb"]= '<input type="checkbox" />';
    $columns['thumb'] = __( 'Thumb', 'profile_cct' );
    $columns["title"] = __( "Title" );
    $columns["author"] = __( "Author" );     
    $columns["date"] = __( "Date" );
  
	return $columns;
}
add_filter( 'manage_edit-profile_cct_columns', 'profile_cct_column_register' );


function profile_cct_column_display_thumb( $column_name, $post_id ) {
	

	if ( 'thumb' != $column_name )
		return;
	
	echo get_the_post_thumbnail($post_id, array(50,50) );
}
add_action( 'manage_profile_cct_posts_custom_column', 'profile_cct_column_display_thumb', 10, 2 );