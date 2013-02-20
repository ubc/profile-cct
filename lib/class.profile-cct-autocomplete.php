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
        wp_register_style( 'jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
        add_action( 'get_search_form',               array( __CLASS__, 'add_scripts_to_form' ) );
        add_action( 'wp_print_footer_scripts',       array( __CLASS__, 'print_footer_scripts' ), 11 );
        add_action( 'wp_ajax_'.self::$action,        array( __CLASS__, 'autocomplete_suggestions' ) );
        add_action( 'wp_ajax_nopriv_'.self::$action, array( __CLASS__, 'autocomplete_suggestions' ) );
		
		if( !is_admin()):
        	wp_enqueue_script( 'jquery-ui-autocomplete' );
        	wp_enqueue_style( 'jquery-ui-css' );
			wp_enqueue_style( 'profile-cct-autocomplete', PROFILE_CCT_DIR_URL.'/css/autocomplete.css' );
		endif;
    }

    static function add_scripts_to_form( $form ) {
        wp_enqueue_script( 'jquery-ui-autocomplete' );
        wp_enqueue_style( 'my-jquery-ui' );
        return $form;
    }

    static function print_footer_scripts() {
        ?>
		<script type="text/javascript">
			jQuery(document).ready( function($) {
				var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
				var ajaxaction = '<?php echo self::$action ?>';
				$(".profile-cct-search-form input.profile-cct-search").each( function() {
					jQuery(this).autocomplete( {
						delay: 0,
						minLength: 0,
						source: function( req, response ) {  
							$.getJSON( ajaxurl+'?callback=?&action='+ajaxaction, req, response );  
						},
						select: function( event, ui ) {
							window.location.href = ui.item.link;
						},
					} );
					jQuery(this).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
						return $( "<li>" )
						.append( "<a>" + item.img + " " + item.label + "</a>" )
						.appendTo( ul );
					};
				} );
			} );
		</script>
		<?php
    }

    static function autocomplete_suggestions() {
        $posts = get_posts( array(
            's' => trim( esc_attr( strip_tags( $_REQUEST['term'] ) ) ),
			'post_type' => 'profile_cct',
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