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
		
		//add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
	}
	
	public static function add_meta_boxes() {
		$profile = Profile_CCT::get_object();
		
		if ( is_array( $profile->taxonomies ) ):
			foreach ( $profile->taxonomies as $taxonomy ):
				$id = Profile_CCT_Taxonomy::id($taxonomy['single']);
				$title = __('Test '.$taxonomy['plural']);
				//$callback = array( 'Profile_CCT_Taxonomy', 'render_meta_box' );
				$args = array( 'taxonomy' => $id );
				
				//add_meta_box( $id, $title, $callback, 'profile_cct', 'side', 'low', $taxonomy );
				add_meta_box( $id.'div', $title, 'post_categories_meta_box', 'profile_cct', 'side', 'core', $args );
			endforeach;
		endif;
	}
	
	/*public static function render_meta_box( $post, $args ) {
		$taxonomy = $args['args'];
		$taxonomy_id = Profile_CCT_Taxonomy::id( $taxonomy['single'] );
		
		if ( true == $taxonomy['hierarchical'] ):
			Profile_CCT_Taxonomy::render_category_taxonomy( $id, $term, $taxonomy_id );
		else:
			Profile_CCT_Taxonomy::render_tags_taxonomy( $id, $term, $taxonomy_id );
		endif;
	}
	
	private static function render_tags_taxonomy( $id, $term, $taxonomy_id ) {
		?>
		<div id="<?php echo $taxonomy_id; ?>" class="tagsdiv">
			<div class="jaxtag">
				<div class="nojs-tags hide-if-js">
					<p>Add or remove tags</p>
					<textarea name="tax_input[profile_cct_tik_tok]" rows="3" cols="20" class="the-tags" id="tax-input-profile_cct_tik_tok"></textarea>
				</div>
				<div class="ajaxtag hide-if-no-js">
					<label class="screen-reader-text" for="new-tag-profile_cct_tik_tok">Tik-Toks</label>
					<div class="taghint" style="">Add New Tik-Tok</div>
						<p>
							<input type="text" id="new-tag-profile_cct_tik_tok" name="newtag[profile_cct_tik_tok]" class="newtag form-input-tip" size="16" autocomplete="off" value="">
							<input type="button" class="button tagadd" value="Add">
						</p>
					</div>
					<p class="howto">Separate tags with commas</p>
				</div>
			<div class="tagchecklist"></div>
		</div>
		<?php
	}
	
	private static function render_category_taxonomy( $id, $term, $taxonomy_id ) {
		$terms = Profile_CCT_Taxonomy::get_terms( $taxonomy_id );
		
		?>
		<ul id="<?php echo $taxonomy_id."checklist"; ?>" class="categorychecklist form-no-clear" data-wp-lists="list:<?php echo $taxonomy_id; ?>">
			<?php
				foreach ( $terms as $id => $term ):
					Profile_CCT_Taxonomy::render_term( $id, $term, $taxonomy_id );
				endforeach;
			?>
		</ul>
		<?php
	}
	
	private static function render_term( $id, $term, $taxonomy_id ) {
		?>
		<li id="<? echo $taxonomy_id."-".$id; ?>">
			<label class="selectit" title="<?php echo $term['description']; ?>">
				<input id="in-<? echo $taxonomy_id."-".$id; ?>" type="checkbox" value="<?php echo $id; ?>" name="tax_input[<?php echo $taxonomy_id; ?>][]" <?php if ( $term['checked'] == true ) echo 'checked="checked"'; ?> />
				<?php echo " ".$term['name']; ?>
			</label>
			<?php
				if ( ! empty( $term['children'] ) ):
					?>
					<ul class="children" style="margin-left: 15px;">
					<?php
						foreach ( $term['children'] as $child_id => $child ):
							Profile_CCT_Taxonomy::render_term( $child_id, $child, $taxonomy_id );
						endforeach;
					?>
					</ul>
					<?php
				endif;
			?>
		</li>
		<?php
	}
	
	private static function get_terms( $taxonomy_id ) {
		global $post;
		$tagged_terms = wp_get_post_terms( $post->ID, $taxonomy_id, array() );
		
		$terms_args = array(
			'hide_empty'   => false,
			'hierarchical' => $taxonomy['hierarchical'],
		);
		
		$raw_terms = get_terms( $taxonomy_id, $terms_args );
		$terms = array();
		foreach ( $raw_terms as $term ):
			$terms[$term->term_id] = array(
				'name'        => $term->name,
				'description' => $term->description,
				'parent'      => $term->parent,
				'checked'     => false,
				'children'    => array(),
			);
		endforeach;
		
		foreach ( $tagged_terms as $term ):
			$terms[$term->term_id]['checked'] = true;
		endforeach;
		
		// This entire block will reorganize the $terms array so that parent->children relations are reflected in the term hierarchy.
		$raw_terms = $terms;
		foreach ( $raw_terms as $id => $term ):
			if ( $term['parent'] == 0 ):
				unset( $terms[$id]['parent'] );
			else:
				$parent_ids = array( $term['parent'] );
				$index = 0;
				while (true): // Loop forever, until we call break;
					if ( isset( $terms[$parent_ids[$index]] ) ):
						$parent = & $terms[$parent_ids[$index]];
						
						while ($index > 0):
							$index--;
							$parent = & $parent['children'][$parent_ids[$index]];
						endwhile;
						
						$parent['children'][$id] = $terms[$id];
						unset($terms[$id]);
						break;
					else:
						$parent_ids[] = $raw_terms[$parent_ids[$index]]['parent'];
						$index++;
					endif;
					
					if ($index > 10) break; // Safeguard against infinite loops.
				endwhile;
			endif;
		endforeach;
		
		return $terms;
	}*/
	
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
