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
		'class'        => 'taxonomy',
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
			add_action( 'edit_post',                        array( __CLASS__, 'edit_post'    ), 10, 2 );
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
		$terms = get_terms( $this->options['type'] );
		if ( empty( $terms ) ):
			$this->display_text( array(
				'default_text' => 'No '.$this->options['label'].' have been created.',
				'value'        => '',
			) );
		else:
			$first = true;
			foreach ( $this->data as $term ):
				if ( $first ):
					$first = false;
				else:
					$term['separator'] = ", ";
				endif;
				
				$this->display_link( $term );
			endforeach;
		endif;
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
		global $current_user;
		
		$callback_args = array(
			'taxonomy' => $field['type'],
		);
		
		if ( $current_user->has_cap( 'manage_categories' ) ):
			if ( is_taxonomy_hierarchical( $field['type'] ) ):
				add_meta_box( $field['type'], $field['label'], 'post_categories_meta_box', 'profile_cct', $context, 'core', $callback_args );
			else:
				add_meta_box( 'tagsdiv-'.$field['type'].'div', $field['label'], 'post_tags_meta_box', 'profile_cct', $context, 'core', $callback_args );
			endif;
		else:
			add_meta_box( $field['type'], $field['label'], array( __CLASS__, 'meta_box_content' ), 'profile_cct', $context, 'core', $callback_args );
		endif;		
	}
	
	public static function meta_box_content( $post, $args ) {
		$taxonomy = $args['args']['taxonomy'];
		
		?>
		<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
			<ul id="<?php echo $taxonomy; ?>checklist" class="categorychecklist form-no-clear" data-wp-lists="list:<?php echo $taxonomy; ?>">
				<?php
				wp_terms_checklist( $post->ID, array(
					'descendants_and_self'  => 0,
					'selected_cats'         => false,
					'popular_cats'          => false,
					'walker'                => new Profile_CCT_Walker(),
					'taxonomy'              => $taxonomy,
					'checked_ontop'         => false,
				) );
				?>
			</ul>
		</div>
		<?php
	}
	
	public static function edit_post( $post_id, $post ) {
		global $current_user;
		if ( ! $current_user->has_cap( 'manage_categories' ) ) {
			foreach ( $_POST['tax_input'] as $taxonomy => $terms ):
				if ( ! is_taxonomy_hierarchical( $taxonomy ) ):
					foreach ( $terms as $index => $term_id ):
						$term = get_term( $term_id, $taxonomy );
						$terms[$index] = $term->name;
					endforeach;
				endif;
				
				wp_set_post_terms( $post_id, $terms, $taxonomy );
			endforeach;
		}
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
			if ( is_array( $terms ) && ! empty( $terms ) ):
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

if ( is_admin() ) require_once( 'includes/template.php' );
	
class Profile_CCT_Walker extends Walker_Category_Checklist {
	function start_el( &$output, $category, $depth, $args, $id = 0 ) {
		unset($args['disabled']);
		parent::start_el( $output, $category, $depth, $args, $id = 0 );
	}
}

if ( is_array( Profile_CCT::get_object()->taxonomies ) ):
	Profile_CCT_Taxonomy_Field::init();
endif;