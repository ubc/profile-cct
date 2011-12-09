<?php
/*
add_action('profile_cct_admin_pages', 'profile_cct_add_webscraper_fields_filter', 10, 1);

add_action('profile_cct_page', 'profile_cct_add_webscraper_fields_filter', 5);
add_action('profile_cct_display_shell_profile_cct_webscraper', 'profile_cct_webscraper_display_shell',10, 3);
function profile_cct_add_webscraper_fields_filter($type_of= null){
	
	// for now only on pages and lists
	if(in_array($type_of == 'page')) )
		add_filter( 'profile_cct_dynamic_fields', 'profile_cct_add_webscraper_fields' );

}	
function profile_cct_add_webscraper_fields( $fields ){

	$fields[] = array( "type"=> 'webscraper', "label"=> 'Web Scraper');
	return $fields;

}

function profile_cct_webscraper_display_shell($action, $options, $data=null) {
	
	// 
	if(!class_exists('simple_html_dom'))
		require_once(WP_PLUGIN_DIR.'/profile-cct/inc/simple_html_dom.php');
	
	
}

