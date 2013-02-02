<?php 

/**
 * profile_cct_bio_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data (default: null)
 * @return void
 */
function profile_cct_bio_shell( $options, $data = null ) {
	// This if/then/else block doesn't currently work correctly.
	if ( empty( $data ) ):
		$data['value'] = get_the_author_meta('description', wp_get_current_user()->ID);
	elseif ( empty( $data['args']['data'] ) ):
		$data['args']['data']['bio'] = get_the_author_meta('description', wp_get_current_user()->ID);
	endif;
	
	Profile_CCT_Textarea::shell( $options, $data );
}