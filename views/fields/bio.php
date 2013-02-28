<?php 

/**
 * profile_cct_bio_shell function.
 * 
 * @access public
 * @param mixed $options
 * @param mixed $data (default: null)
 * @return void
 */
function profile_cct_bio_shell( $options_or_post, $data = null ) {
	if ( Profile_CCT_Field::is_post( $options_or_post ) && $options_or_post->post_status == 'auto-draft' ):
		$data['args']['data']['default'] = get_the_author_meta( 'description', wp_get_current_user()->ID );
	endif;
	
	Profile_CCT_Textarea::shell( $options_or_post, $data );
}