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
	}
	
	/**
	 * profile_list_shortcode function.
	 * 
	 * @access public
	 * @param mixed $atts
	 * @return void
	 */
	static function profile_list_shortcode( $atts ){
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
		return Profile_CCT_Widget::profile_search( true, false, false, false );
	}
	
	static function profile_navigation_shortcode( $atts ) {
		
		return Profile_CCT_Widget::profile_search( true, true, true, true );
	}
}

Profile_CCT_Shortcodes::init();