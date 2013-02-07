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
			$id = Profile_CCT_Taxonomy::id($taxonomy['single']);
			
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
		
		if ( is_file('includes/meta-boxes.php') ):
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
	
	function add_taxonomy_fields( $fields ){
		$profile = Profile_CCT::get_object();
		
		foreach ( $profile->taxonomies as $taxonomy ):
			// Add it to the fields
			$sanitized_single = str_replace( '-', '_', sanitize_title($taxonomy['single']) );
			$fields[] = array(
				"type"  => 'profile_cct_'.$sanitized_single,
				"label" => $taxonomy['plural'],
			);
		endforeach;
		
		return $fields;
	}
	
	public static function add_meta_box( $field, $context, $data, $i ) {
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
			foreach ( $terms as $term ):
				$data[] = array(
					'class'        => $term->slug,
					'value'        => $term->name,
					'default_text' => $term->name,
					'href'         => get_term_link( $term, $taxonomy ),
				);
			endforeach;
		endif;
		
		new Profile_CCT_Taxonomy_Field( $options, $data );
    }
}

if ( is_array( Profile_CCT::get_object()->taxonomies ) ):
	Profile_CCT_Taxonomy_Field::init();
endif;

/*
	function profile_cct_taxonomy_display_shell( $action, $options, $data = null ) {
		if ( is_object($action) ):
			$post = $action;
			$action = "display";
			$data = $options['args']['data'];
			$options = $options['args']['options'];
		endif;
		
		$field = Profile_CCT::get_object();
		
		$default_options = array(
			'width' => 'full',
			'before' => '',
			'empty' => '',
			'after' =>'',
			'text'	=>$options['label'].":",
			'hide_label'=>true,
			'class' => 'is-active', // highlights the taxonomies to display differently
		);
		
		$options = ( is_array($options) ? array_merge( $default_options, $options ) : $default_options );
		
		$field->start_field($action, $options);
		if ( $field->is_data_array( $data ) ):
			
			foreach ( $data as $item_data ):
				profile_cct_taxonomy_display($item_data, $options);
			endforeach;
			
		else:
			profile_cct_taxonomy_display($item_data, $options);
		endif;
		
		$field->end_field( $action, $options );
		
	}
	
	function profile_cct_taxonomy_display( $data, $options ) {
		global $post;
		extract( $options );
		$show = ( is_array($show) ? $show : array() );
		$field = Profile_CCT::get_object();
		
		$taxonomy = $type;
		$field->display_text( array( 'field_type'=>$taxonomy, 'class' => 'taxonomy', 'type' => 'shell', 'tag' => 'p') );
		$field->display_text( array( 'field_type'=>$type, 'default_text'=>$label, 'value'=>$text." ", 'type' => 'text', 'tag' => 'span', 'class' => 'text-input', 'title'=>$label.":") );
		if( is_object( $post ) ):
			
			$terms =  get_the_terms( $post->ID, $taxonomy );
			
			if(is_array($terms)):
				foreach ( $terms as $term ):
					$link = get_term_link( $term, $taxonomy );
					if ( is_wp_error( $link ) )
						return $link;
					$term_links[] = '<a href="' . $link . '" rel="tag">' . $term->name . '</a>';
				endforeach;
		
				$term_links = apply_filters( "term_links-$taxonomy", $term_links );
			
				echo join( ", ", $term_links );	
			endif;
		else:
			$single = str_replace("profile_cct_","",$type);	
			
			$field->display_text( array( 'field_type'=>$type,  'class' => 'tag',  'href' => '#', 'default_text'=>$single.' 1', 'value' => '', 'type' => 'text', 'tag' => 'a') );
			$field->display_text( array( 'field_type'=>$type,  'class' => 'tag', 'separator' => ', ', 'href' => '#', 'default_text'=>$single.' 2', 'value' => '', 'type' => 'text', 'tag' => 'a') );
			$field->display_text( array( 'field_type'=>$type,  'class' => 'tag', 'separator' => ', ', 'href' => '#', 'default_text'=>$single.' 3', 'value' => '', 'type' => 'text', 'tag' => 'a') );
			
		endif;
		$field->display_text( array( 'field_type'=>$type, 'type' => 'end_shell', 'tag' => 'p') );
	}
*/