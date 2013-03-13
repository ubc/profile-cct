<?php 
/**
 * Profile_CCT_Widget class.
 * 
 * @extends WP_Widget
 */
class Profile_CCT_Autocomplete {
	static $action = 'profile_cct_autocomplete'; //Name of the action - should be unique to your plugin.

    static function init() {
        //Register style - you can create your own jQuery UI theme and store it in the plug-in folder
        add_action( 'get_search_form',               array( __CLASS__, 'add_scripts_to_form' ) );
        add_action( 'wp_print_footer_scripts',       array( __CLASS__, 'print_footer_scripts' ), 9 );
        add_action( 'wp_ajax_profile_cct_autocomplete',        array( __CLASS__, 'autocomplete_suggestions' ) );
        add_action( 'wp_ajax_nopriv_profile_cct_autocomplete', array( __CLASS__, 'autocomplete_suggestions' ) );
		
		if ( ! is_admin()):
        	wp_register_script( 'profile-cct-autocomplete', PROFILE_CCT_DIR_URL.'/js/autocomplete.js', array( 'jquery-ui-autocomplete' ), PROFILE_CCT_VERSION, true );
		endif;
    }

    static function add_scripts_to_form( $form ) {
        wp_enqueue_script( 'jquery-ui-autocomplete' );
        wp_enqueue_style( 'my-jquery-ui' );
        return $form;
    }

    static function print_footer_scripts() {
		$settings = array( 'admin_url' => admin_url( 'admin-ajax.php' ) );
		wp_localize_script( 'profile-cct-autocomplete', 'profile_cct_autocomple', $settings );
    }

    static function autocomplete_suggestions() {
        $posts = get_posts( array(
            's' => trim( esc_attr( strip_tags( $_REQUEST['term'] ) ) ),
			'post_type' => 'profile_cct',
			'order'=> 'ASC', 'orderby' => 'title',
        ) );
        $suggestions = array();
		
        global $post;
        foreach ($posts as $post): 
            setup_postdata($post);
			
			$img = get_the_post_thumbnail( $post->ID, array( 25, 25 ) );
			if ( $img == '' ) $img = '<img width=25 class="empty-image" />';
			
            $suggestions[] = array(
				'label' => esc_html($post->post_title),
				'img'   => $img,
				'link'  => get_permalink(),
			);
        endforeach;
		
        $response = $_GET["callback"] . "(" . json_encode($suggestions) . ")";  
        echo $response;
        exit;
    }
}

add_action( 'init', array( 'Profile_CCT_Autocomplete', 'init' ) );