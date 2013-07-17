<?php

class Profile_CCT_Shortcodes {
	/**
	 * init function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	static function init() {
		add_shortcode( 'profilelist', 		array( __CLASS__, 'profile_list_shortcode' ) );
		add_shortcode( 'profile', 			array( __CLASS__, 'profile_single_shortcode' ) );
		add_shortcode( 'profilesearch',  	array( __CLASS__, 'profile_search_shortcode' ) );
		add_shortcode( 'profilenavigation', array( __CLASS__, 'profile_navigation_shortcode' ) );
		add_shortcode( 'profilefield',      array( __CLASS__, 'profile_field_shortcode' ) );
	}
	
	/**
	 * profile_list_shortcode function.
	 * 
	 * @access public
	 * @param mixed $atts
	 * @return void
	 */
	static function profile_list_shortcode( $atts ) {
		$tax_query = array();
		$taxonomies = get_taxonomies();
		if ( $atts ):
			foreach ( $atts as $key => $att ):
				if ( in_array( "profile_cct_".$key, $taxonomies ) ):
					$array = array(
						'taxonomy' => PROFILE_CCT_TAXONOMY_PREFIX.$key,
						'field'    => 'slug',
						'terms'    => $att,		
					);
					array_push( $tax_query, $array );
				endif;
			endforeach;
		endif;
		
		//Whether to OR or AND the criterias
		if ( $atts['query'] ):	
			$tax_query['relation'] = $atts['query'];
		endif;
		
		$query = array(
			'post_type'      => 'Profile_CCT',
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'tax_query'      => $tax_query,
			'post__not_in'   => explode( ",", $atts['exclude'] ),
			'posts_per_page' => -1,
		);
		
		if ( $atts['order'] ):
			$query['order'] = $atts['order'];
		endif;
	
		//If include is set
		if ( $atts['include'] ):
			$query['post__in'] = explode( ",", $atts['include'] );
		endif;
		
		if ( $atts['limit'] && is_numeric( $atts['limit'] ) ):
			$query['posts_per_page'] = $atts['limit'];
		endif;
		
		if ( $atts['orderby'] ):
			switch ( $atts['orderby'] ):
				case 'first_name':
					$query['orderby'] = 'title';
					break;
				case 'last_name':
					$query['meta_key'] = 'profile_cct_last_name';
					$query['orderby'] = 'meta_value';
					break;
				case 'date':
					$query['orderby'] = 'post_date';
					break;
				case 'random':
					$query['orderby'] = 'rand';
					break;
				default:
					$query['orderby'] = $atts['orderby'];
					break;
			endswitch;
		endif;
		
		$the_query = new WP_Query( $query );
		
		ob_start();	//we want to collect the output and return it instead of displaying it.
		
		if ( $atts['display'] == 'name' ):
			?>
				<ul class="profilelist-shortcode">
			<?php
		else:
			?>
				<div class="profilelist-shortcode">
			<?php
		endif;
		
		while ( $the_query->have_posts() ):
			$the_query->the_post();
			
			if ( $atts['display'] == 'name' ):
				?>
					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php
			elseif ( $atts['display'] == 'full' ):
				?>
					<div id="post-<?php the_ID(); ?>" class="post-<?php the_ID(); ?> profile_cct type-profile_cct">
						<?php the_content(); ?>
					</div>
				<?php
			else:
				?>
					<div id="post-<?php the_ID(); ?>" class="post-<?php the_ID(); ?> profile_cct type-profile_cct ">
						<?php the_excerpt(); ?>
					</div>
				<?php
			endif;
		endwhile;
		
		if ( $atts['display'] == 'name' ):
			?>
				</ul>
			<?php
		else:
			?>
				</div>
			<?php
		endif;
		
		wp_reset_postdata();
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
	}

	/**
	 * profile_single_shortcode function.
	 * 
	 * @access public
	 * @param mixed $atts
	 * @return void
	 */
	static function profile_single_shortcode( $atts ) {
		if ( ! isset( $atts['person'] ) ):
			return 'You must specify a person';
		endif;
		
		$the_query = new WP_Query( 'post_type=Profile_CCT&name='.$atts['person'] );
		
		while ( $the_query->have_posts() ):
			$the_query->the_post();
			
			$return = '<div style="overflow: hidden">';
			if ( $atts['display'] == 'list' ):
				$return .= get_the_excerpt();
			else:
				$return .= get_the_content();
			endif;
			$return .= '</div>';
		endwhile;
		
		wp_reset_postdata();
		return $return;
	}
	
	/**
	 * get_all_names function.
	 * needed by the profile_search_shrotcode to display the name
	 * @access public
	 * @static
	 * @return void
	 */
	static function get_all_names() {
		global $wpdb;
		$data = get_transient("profile_cct_name_list");
		
		if ( ! $data ):
			$query = "SELECT post_title FROM ".$wpdb->posts." WHERE post_type = 'profile_cct' AND post_status = 'publish'";
			$data = $wpdb->get_results( $query );
			set_transient( "profile_cct_name_list", $data, 10 * 60 );
		endif;
		
		return $data;
	}
	
	static function profile_search_shortcode( $atts ) {
		return Profile_CCT_Widget::profile_search( array( 'display_searchbox' => 'true' ) );
	}
	
	static function profile_navigation_shortcode( $atts ) {
		$profile = Profile_CCT::get_object();
		
		if ( is_array( $atts ) ):
			if ( isset( $atts['display_tax'] ) ):
				foreach ( explode( ",", $atts['display_tax'] ) as $taxonomy ):
					$atts['display_tax'][$taxonomy] = 'true';
				endforeach;
			endif;
		else:
			$atts = $profile->settings['archive'];
		endif;
		
		return Profile_CCT_Widget::profile_search( $atts );
	}
	
	static function profile_field_shortcode( $atts ) {
		if ( get_post_type() == 'profile_cct' ):
			// Since these parameters will be passed directly into our field functions, let's filter out any unintended parameters (particularly, url_prefix)
			foreach ( $atts as $key => $att ):
				if ( ! in_array( $key, array( 'type', 'show', 'width', 'html' ) ) ):
					unset( $atts[$key] );
				endif;
			endforeach;
			
			$data = get_post_meta( get_the_ID(), 'profile_cct', true );
			Profile_CCT_Admin::$action = 'display';
			Profile_CCT_Admin::$page   = 'page';
			
			if ( isset( $atts['show'] ) ):
				$atts['show'] = array_map( 'trim', explode( ",", $atts['show'] ) );
			endif;
			
			$options = array();
			foreach ( Profile_CCT_Admin::default_shells() as $context ):
				$fields = Profile_CCT_Admin::get_option( 'page', 'fields', $context );
				if ( is_array( $fields ) ):
					foreach ( $fields as $field ):
						if ( $field['type'] == $atts['type'] ):
							$options = $field;
							break 2;
						endif;
					endforeach;
				endif;
			endforeach;
			
			$options = array_merge( $options, $atts ); 
			
			ob_start();
			
			if ( function_exists( 'profile_cct_'.$atts['type'].'_shell' ) ):
				call_user_func( 'profile_cct_'.$atts['type'].'_shell', $options, $data[ $atts['type'] ] );
			else:
				do_action( 'profile_cct_shell_'.$atts['type'], $options, $data[ $atts['type'] ] );
			endif;
			
			$out = ob_get_contents();
			ob_end_clean();
			
			if ( $atts['html'] == 'false' ):
				$out = strip_tags( $out );
			endif;
			
			return $out;
		endif;
	}
}

Profile_CCT_Shortcodes::init();
