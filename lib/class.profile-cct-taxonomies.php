<?php 
/**
 * Profile_CCT class.
 */
class Profile_CCT_Taxonomy {
	/**
	 * init function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function init() {
		$profile = Profile_CCT::get_object();
		
		// remove some taxonomies
		$profile->taxonomies = Profile_CCT_Taxonomy::remove( $profile->taxonomies );
		
		if ( is_array( $profile->taxonomies ) ):
			foreach ( $profile->taxonomies as $taxonomy ):
				Profile_CCT_Taxonomy::register( $taxonomy );
			endforeach;
		endif;
		
		add_action( 'add_meta_boxes', array( __CLASS__, 'remove_meta_boxes' ) );
	}
	
	public static function remove_meta_boxes() {
		$profile = Profile_CCT::get_object();
		
		if ( is_array( $profile->taxonomies ) ):
			foreach ( $profile->taxonomies as $taxonomy ):
				$id = Profile_CCT_Taxonomy::id($taxonomy['single']);
				//$title = __('Test '.$taxonomy['plural']);
				//$args = array( 'taxonomy' => $id );
				
				if ( $taxonomy['hierarchical'] == true ):
					remove_meta_box( $id.'div', 'profile_cct', 'side' );
					//add_meta_box( $id, $title, 'post_categories_meta_box', 'profile_cct', 'side', 'core', $args );
				else:
					remove_meta_box( 'tagsdiv-'.$id, 'profile_cct', 'side' );
					//add_meta_box( $id, $title, 'post_tags_meta_box', 'profile_cct', 'side', 'core', $args );
				endif;
			endforeach;
		endif;
	}
	
	/**
	 * register function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $taxonomy
	 * @return void
	 */
	public static function register( $taxonomy ) {
		$labels = array(
			'name'              => $taxonomy['plural'] ,
			'singular_name'     => $taxonomy['single'],
			'search_items'      => __( 'Search '.$taxonomy['plural'] ),
			'all_items'         => __( 'All '.$taxonomy['plural'] ),
			'parent_item'       => __( 'Parent '.$taxonomy['single'] ),
			'parent_item_colon' => __( 'Parent '.$taxonomy['single'].":" ),
			'edit_item'         => __( 'Edit '.$taxonomy['single'] ), 
			'update_item'       => __( 'Update '.$taxonomy['single'] ),
			'add_new_item'      => __( 'Add New '.$taxonomy['single'] ),
			'new_item_name'     => __( 'New '.$taxonomy['single'].' Name' ),
			'menu_name'         => __( $taxonomy['plural'] ),
		);
		
		// finally register the taxonomy
		register_taxonomy( Profile_CCT_Taxonomy::id( $taxonomy['single'] ), array( 'profile_cct' ), array(
			'hierarchical' => $taxonomy['hierarchical'] ? true : false,
			'labels'       => $labels,
			'show_ui'      => true,
			'query_var'    => true,
			'rewrite'      => array( 'slug' => sanitize_title( $taxonomy['single'] ) ),
		));
	}
	
	/**
	 * remove function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $taxonomies
	 * @return void
	 */
	public static function remove($taxonomies) {
		// Try to remove taxonomies.
		if ( is_admin() && wp_verify_nonce( $_GET['_wpnonce'], 'profile_cct_remove_taxonomy'.$_GET['remove'] ) ): 
			if ( isset( $taxonomies[$_GET['remove']] ) ):
				unset( $taxonomies[$_GET['remove']] );
			endif;
			
			update_option( 'Profile_CCT_taxonomy', $field->taxonomies );
			flush_rewrite_rules();
		endif;
		
		return $taxonomies;
	}
	
	/**
	 * add function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $new_taxonomy
	 * @param mixed $taxonomies
	 * @return void
	 */
	public static function add( $new_taxonomy, $taxonomies ) {
		$taxonomies[] = $new_taxonomy;
   		update_option( 'Profile_CCT_taxonomy', $taxonomies );
   		
		Profile_CCT_Taxonomy::register( $new_taxonomy );
   		flush_rewrite_rules();
		return $taxonomies;
	}
	
	/**
	 * id function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $single_taxonomy
	 * @return void
	 */
	public static function id( $single_taxonomy ) {
		return 'profile_cct_'.str_replace( '-', '_', sanitize_title( $single_taxonomy ) );
	}
}

if ( function_exists( 'add_action' ) && class_exists( 'Profile_CCT_Taxonomy' ) ):	
	add_action( 'init', array( 'Profile_CCT_Taxonomy', 'init' ) );
endif;
