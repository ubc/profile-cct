<?php
class Profile_CCT_Table {
	function init() {
		add_filter( 'manage_edit-profile_cct_columns',        array( __CLASS__, 'register' ) );
		add_action( 'manage_profile_cct_posts_custom_column', array( __CLASS__, 'display_thumb' ), 10, 2 );
		//add_action( 'manage_profile_cct_posts_custom_column', array( __CLASS__, 'display_last_name' ), 10, 2 );
	}
	
	function register( $columns ) {
		unset($columns);
		
		$columns["cb"]        = '<input type="checkbox" />';
		$columns['thumb']     = __( 'Picture', 'profile_cct' );
		$columns['title']     = __( "Name" );
		//$columns['last_name'] = __( "Last Name" );
		
		$columns['author']    = __( "Author" );     
		$columns['date']      = __( "Date" );
	  
		return $columns;
	}
	
	function display_thumb( $column_name, $post_id ) {
		if ( 'thumb' != $column_name ) return;
		
		echo get_the_post_thumbnail( $post_id, array( 50 , 50 ) );
	}
	
	function display_last_name( $column_name, $post_id ) {
		if ( 'last_name' != $column_name ) return;
		
		echo get_post_meta( $post_id, 'profile_cct_last_name', true);
	}
}

if ( function_exists( 'add_action' ) && class_exists( 'Profile_CCT_Table' ) ):
	add_action( 'plugins_loaded', array( 'Profile_CCT_Table', 'init' ) );
endif;
