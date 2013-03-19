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
			add_action( 'profile_cct_shell_'.$id,           array( __CLASS__, 'shell'        ), 10, 2 );
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
		
		$profile = Profile_CCT::get_object();
		foreach ( $profile->taxonomies as $taxonomy ):
			if ( $this->options['type'] == Profile_CCT_Taxonomy::id( $taxonomy['single'] ) ):
				$display = ( isset( $taxonomy['display'] ) ? $taxonomy['display'] : 'default' );
			endif;
		endforeach;
		
		if ( $display == 'dropdown' ):
			?>
			<select>
				<?php
					wp_terms_checklist( $post->ID, array(
						'descendants_and_self' => 0,
						'selected_cats'        => false,
						'popular_cats'         => false,
						'walker'               => new Profile_CCT_Dropdown_Walker(),
						'taxonomy'             => $this->options['type'],
						'checked_ontop'        => false,
					) );
				?>
			</select>
			<?php
		elseif ( is_taxonomy_hierarchical( $this->options['type'] ) ):
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
		
		$taxonomy_id = $field['type'];
		$callback_args = array(
			'taxonomy' => $field['type'],
		);
		
		$profile = Profile_CCT::get_object();
		$taxonomies = $profile->taxonomies;
		
		foreach ( $taxonomies as $taxonomy ):
			if ( Profile_CCT_Taxonomy::id( $taxonomy['single'] ) == $field['type'] ):
				$callback_args['display'] = ( empty( $taxonomy['display'] ) ? 'default' : $taxonomy['display'] );
				break;
			endif;
		endforeach;
		
		if ( $callback_args['display'] == 'default' && $current_user->has_cap( 'manage_categories' ) ):
			if ( is_taxonomy_hierarchical( $field['type'] ) ):
				$callback = 'post_categories_meta_box';
			else:
				$taxonomy_id = 'tagsdiv-'.$taxonomy_id.'div';
				$callback = 'post_tags_meta_box';
			endif;
		else:
			$callback = array( __CLASS__, 'meta_box_content' );
		endif;
		
		add_meta_box( $taxonomy_id, $field['label'], $callback, 'profile_cct', $context, 'core', $callback_args );
	}
	
	public static function meta_box_content( $post, $args ) {
		$taxonomy = $args['args']['taxonomy'];
		$display = $args['args']['display'];
		
		?>
		<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
			<input type="hidden" name="tax_data[<?php echo $taxonomy; ?>][display]" value="<?php echo $display; ?>" />
			<?php
			switch ( $display ):
			case 'dropdown':
				?>
				<select id="<?php echo $taxonomy; ?>dropdown" name="tax_input[<?php echo $taxonomy; ?>][]" class="categorychecklist form-no-clear" data-wp-lists="list:<?php echo $taxonomy; ?>">
					<option value="">None</option>
					<?php
						wp_terms_checklist( $post->ID, array(
							'descendants_and_self' => 0,
							'selected_cats'        => false,
							'popular_cats'         => false,
							'walker'               => new Profile_CCT_Dropdown_Walker(),
							'taxonomy'             => $taxonomy,
							'checked_ontop'        => false,
						) );
					?>
				</select>
				<?php
				break;
			default:
				?>
				<ul id="<?php echo $taxonomy; ?>checklist" class="categorychecklist form-no-clear" data-wp-lists="list:<?php echo $taxonomy; ?>">
					<?php
						wp_terms_checklist( $post->ID, array(
							'descendants_and_self' => 0,
							'selected_cats'        => false,
							'popular_cats'         => false,
							'walker'               => new Profile_CCT_Checkbox_Walker(),
							'taxonomy'             => $taxonomy,
							'checked_ontop'        => false,
						) );
					?>
				</ul>
				<?php
			endswitch;
			?>
		</div>
		<?php
	}
	
	public static function edit_post( $post_id, $post ) {
		global $current_user;
		
		if ( ! empty( $_POST['tax_input'] ) ):
			foreach ( $_POST['tax_input'] as $taxonomy => $terms ):
				$display = ( isset( $_POST['tax_data'][$taxonomy]['display'] ) ? $_POST['tax_data'][$taxonomy]['display'] : 'default' );
				
				if ( $display != 'default' || ! $current_user->has_cap( 'manage_categories' ) ):
					foreach ( $terms as $index => $term_id ):
						$term = get_term( $term_id, $taxonomy );
						
						if ( ! is_taxonomy_hierarchical( $taxonomy ) ):
							$terms[$index] = $term->name;
						endif;
						
						if ( ! term_exists( $term->slug, $taxonomy ) ):
							unset( $terms[$index] );
						endif;
					endforeach;
					
					wp_set_post_terms( $post_id, $terms, $taxonomy );
				endif;
			endforeach;
		endif;
	}

    public static function shell( $options, $data ) {
		global $post;
		
		$options['multiple'] = false;
		
		if ( empty( $data ) ):
			$taxonomy_id = $options['type'];
			
			if ( isset( $post ) ):
				$terms = get_the_terms( $post->ID, $taxonomy_id );
			else:
				$terms = get_terms( $taxonomy_id, array( 'number' => 5, 'hide_empty' => false ) );
			endif;
			
			$data = array();
			if ( is_array( $terms ) && ! empty( $terms ) ):
				foreach ( $terms as $term ):
					$data[] = array(
						'class'        => $term->slug,
						'value'        => $term->name,
						'default_text' => $term->name,
						'href'         => get_term_link( $term, $taxonomy_id ),
					);
				endforeach;
			endif;
		endif;
		
		new Profile_CCT_Taxonomy_Field( $options, $data );
    }
}

class Profile_CCT_Checkbox_Walker extends Walker {
	var $db_fields = array ( 'parent' => 'parent', 'id' => 'term_id' );
	
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		ob_start();
		
		echo $indent;
		?>
		<ul class='children'>
		<?php
		$output .= ob_get_clean();
	}
	
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		ob_start();
		
		echo $indent;
		?>
		</ul>
		<?php
		$output .= ob_get_clean();
	}
	
	function start_el( &$output, $category, $depth, $args, $id = 0 ) {
		extract($args);
		if ( empty($taxonomy) )
		$taxonomy = 'category';
		
		$name = 'tax_input['.$taxonomy.']';
		$class = in_array( $category->term_id, $popular_cats ) ? ' class="popular-category"' : '';
		
		ob_start();
		
		echo $indent;
		?>
		<li id="<?php echo $taxonomy; ?>-<?php echo $category->term_id; ?>" <?php echo $class; ?>>
			<label class="selectit">
				<input value="<?php echo $category->term_id; ?>" type="checkbox" name="<?php echo $name; ?>[]" id="in-<?php echo $taxonomy; ?>-<?php echo $category->term_id; ?>" <?php checked( in_array( $category->term_id, $selected_cats ), true, false ); ?> />
				<?php echo esc_html( apply_filters( 'the_category', $category->name ) ); ?>
			</label>
		<?php
		$output .= ob_get_clean();
	}
	
	function end_el( &$output, $category, $depth = 0, $args = array() ) {
		ob_start();
		?>
		</li>
		<?php
		$output .= ob_get_clean();
	}
}

class Profile_CCT_Dropdown_Walker extends Walker {
	var $db_fields = array ( 'parent' => 'parent', 'id' => 'term_id' );
	
	function start_el( &$output, $category, $depth, $args, $id = 0 ) {
		extract($args);
		
		print_r($args);
		
		ob_start();
		?>
		<option id="<?php echo $taxonomy; ?>-<?php echo $category->term_id; ?>" value="<?php echo $category->term_id; ?>" <?php selected( in_array( $category->term_id, $selected_cats ) ); ?> >
		<?php
		echo str_repeat( "-", $depth )." ";
		echo $category->name;
		
		$output .= ob_get_clean();
	}
	
	function end_el( &$output, $category, $depth = 0, $args = array() ) {
		ob_start();
		?>
		</option>
		<?php
		$output .= ob_get_clean();
	}
}

if ( is_array( Profile_CCT::get_object()->taxonomies ) ):
	Profile_CCT_Taxonomy_Field::init();
endif;