<?php
class Profile_CCT_Taxonomy_Field extends Profile_CCT_Field {
    /**
     * default_options
     * 
     * @var mixed
     * @access public
     */
    var $default_options = array(
		'type'         => 'taxonomy',
		'label'        => 'taxonomy',
		'description'  => '',
		'width'        => 'full',
		'before'       => '',
		'empty'        => '',
		'after'        => '',
		'multiple'     => false,
    );
	
	public static function init() {
		add_filter( 'profile_cct_dynamic_fields', array( __CLASS__, 'add_taxonomy_fields' ) );
		
		$profile = Profile_CCT::get_object();
		
		foreach ( $profile->taxonomies as $taxonomy ):
			$id = Profile_CCT_Taxonomy::id( $taxonomy['single'] );
			
			add_action( 'profile_cct_'.$id.'_add_meta_box', array( __CLASS__, 'add_meta_box' ), 10, 3 );
			add_action( 'profile_cct_shell_'.$id,           array( __CLASS__, 'shell'        ), 10, 3 );
		endforeach;
	}
	
    /**
     * display function.
     * 
     * @access public
     * @return void
     */
    function field() {
		global $post;
		
		if ( is_file( 'includes/meta-boxes.php' ) ):
			require_once('includes/meta-boxes.php');
		endif;
		
		$data = array(
			'args' => array(
				'taxonomy' => $this->options['type'],
			),
		);
		
		if ( is_taxonomy_hierarchical( $this->options['type'] ) ):
			call_user_func( 'post_categories_meta_box', $post, $data );
		else:
			call_user_func( 'post_tags_meta_box', $post, $data );
		endif;
	}
	
    /**
     * display function.
     * 
     * @access public
     * @return void
     */
    function display() {
		$first = true;
		foreach ( $this->data as $term ):
			if ( $first ):
				$first = false;
			else:
				$term['separator'] = ", ";
			endif;
			
			$this->display_link( $term );
		endforeach;
	}
	
	function add_taxonomy_fields( $fields ) {
		$profile = Profile_CCT::get_object();
		
		foreach ( $profile->taxonomies as $taxonomy ):
			// Add it to the fields
			$sanitized_single = str_replace( '-', '_', sanitize_title( $taxonomy['single'] ) );
			$fields[] = array(
				"type"  => PROFILE_CCT_TAXONOMY_PREFIX.$sanitized_single,
				"label" => $taxonomy['plural'],
			);
		endforeach;
		
		return $fields;
	}
	
	public static function add_meta_box( $field, $context, $data ) {
		$callback_args = array(
			'taxonomy' => $field['type'],
		);
		
		if ( is_taxonomy_hierarchical( $field['type'] ) ):
			add_meta_box( $field['type'], $field['label'], 'post_categories_meta_box', 'profile_cct', $context, 'core', $callback_args );
		else:
			add_meta_box( 'tagsdiv-'.$field['type'].'div', $field['label'], 'post_tags_meta_box', 'profile_cct', $context, 'core', $callback_args );
		endif;
	}

    public static function shell( $options, $data ) {
		global $post;
		
		$options['multiple'] = false;
		
		if ( empty( $data ) ):
			$taxonomy = $options['type'];
			
			if ( isset( $post ) ):
				$terms = get_the_terms( $post->ID, $taxonomy );
			else:
				$terms = get_terms( $taxonomy, array( 'number' => 5, 'hide_empty' => false ) );
			endif;
			
			$data = array();
			if ( is_array($terms) ):
				foreach ( $terms as $term ):
					$data[] = array(
						'class'        => $term->slug,
						'value'        => $term->name,
						'default_text' => $term->name,
						'href'         => get_term_link( $term, $taxonomy ),
					);
				endforeach;
			endif;
		endif;
		
		new Profile_CCT_Taxonomy_Field( $options, $data );
    }
}

if ( is_array( Profile_CCT::get_object()->taxonomies ) ):
	Profile_CCT_Taxonomy_Field::init();
endif;