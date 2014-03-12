<?php
class Profile_CCT_Table {
	function init() {
		add_filter( 'manage_edit-profile_cct_columns',        array( __CLASS__, 'register' ) );
		add_action( 'manage_profile_cct_posts_custom_column', array( __CLASS__, 'display_thumb' ), 10, 2 );
		//add_action( 'manage_profile_cct_posts_custom_column', array( __CLASS__, 'display_last_name' ), 10, 2 );
		
		global $coauthors_plus;
		if ( class_exists('coauthors_plus') && isset($coauthors_plus) ) {
			// Show multiple authors in dashboard profile listing
			add_filter ('manage_edit-profile_cct_columns',	array( $coauthors_plus, '_filter_manage_posts_columns') );
			
			// Hide default author box
			add_filter ('add_meta_boxes_profile_cct', 		array( __CLASS__, 'remove_authors_box'), 11 );
			// Add co-author box to users who can create multiple profiles or manage all profiles
			add_action ( 'coauthors_plus_edit_authors', 	array( __CLASS__, 'coauthors_plus_edit_authors') );
		}
	}
	
	function coauthors_plus_edit_authors ($post_types) {
		// Added co-authors box to users who can "Create multiple profiles" (OR "Manage all profiles")
		//return current_user_can('edit_profiles_cct') || current_user_can('edit_others_profile_cct');
		return current_user_can('edit_profiles_cct');
	}
	
	function remove_authors_box() {
		remove_meta_box ( 'authordiv', 'profile_cct', 'side' );
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
