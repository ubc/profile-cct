<?php 


class Profile_CCT_Shortcodes {
	
	/**
	 * init function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function init() {
		
		add_shortcode( 'profilelist', 		array( 'Profile_CCT_Shortcodes', 'profile_list_shortcode' ) );
		add_shortcode( 'profile', 			array( 'Profile_CCT_Shortcodes', 'profile_single_shortcode' ) );
		add_shortcode( 'profilesearch',  	array( 'Profile_CCT_Shortcodes', 'profile_search_shortcode' ) );
		add_shortcode( 'profilenavigation', array( 'Profile_CCT_Shortcodes', 'profile_navigation_shortcode' ) );
		
	}
	
	
	/**
	 * profile_list_shortcode function.
	 * 
	 * @access public
	 * @param mixed $atts
	 * @return void
	 */
	public static function profile_list_shortcode( $atts ){
		$tax_query = array();
		$taxonomies = get_taxonomies();
		if($atts):
			foreach($atts as $key=>$att):
				if(in_array("profile_cct_".$key, $taxonomies)):
					
					array_push(
						$tax_query,
						array(
							'taxonomy' => 'profile_cct_'.$key,	////aaghhjjjhg forgot the taxonomies are prefixed
							'field' => 'slug',
							'terms'=>$att,		
							)
						);
				endif;
			endforeach;
		endif;
		
		//Whether to OR or AND the criterias
		if($atts['query']):	
			$tax_query['relation'] = $atts['query'];
		endif;
		
		$query = array(
			'post_type' => 'Profile_CCT',
			'order' => 'ASC',
			'orderby' => 'menu_order',
			'tax_query'=>$tax_query,
			'post__not_in'=>explode(",", $atts['exclude']),
			'posts_per_page'=>-1,
			);
	
	
		if( $atts['order'] ):
			$query['order'] = $atts['order'];
		endif;
	
		//If include is set
		if( $atts['include'] ):
			$query['post__in'] = explode(",", $atts['include']);
		endif;
		
		if($atts['orderby']):
			switch($atts['orderby']){
				case 'first_name':
					$query['orderby'] = 'title';
					break;
				case 'last_name':
					$query['meta_key']='profile_cct_last_name';
					$query['orderby'] = 'meta_value';
					break;
				case 'date':
					$query['orderby'] = 'post_date';
					break;
			}
			
		endif;
		
		$the_query = new WP_Query( $query );
	
		ob_start();	//we want to collect the output and return it instead of displaying it.
		
		if($atts['display'] == 'name'):
			echo '<ul class="profilelist-shortcode">';
		endif;
		
		while($the_query->have_posts()): $the_query->the_post();
			if($atts['display'] == 'name'):
				echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
			elseif($atts['display'] == 'full'):
				the_content();
			else:
				the_excerpt();
			endif;
		endwhile;
		
		if($atts['display'] == 'name'):
			echo '</ul>';
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
	public static function profile_single_shortcode( $atts ){
		if(!isset($atts['person'])):
			return 'You must specify a person';
		endif;
	
		$the_query = new WP_Query('post_type=Profile_CCT&name='.$atts['person']);
		ob_start();	//we want to collect the output and return it instead of displaying it.

		while($the_query->have_posts()): $the_query->the_post();
			if($atts['display'] == 'list'):
				the_excerpt();
			else:
				the_content();
			endif;
		endwhile;
		
		wp_reset_postdata();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
		
	}
	
	/**
	 * get_all_names function.
	 * needed by the profile_search_shrotcode to display the name
	 * @access public
	 * @static
	 * @return void
	 */
	public static function get_all_names(){
		global $wpdb;
		$data = get_transient("profile_cct_name_list");
		if(!$data):
			$query = "SELECT post_title FROM $wpdb->posts WHERE post_type = 'profile_cct' AND post_status = 'publish'";
			$data = $wpdb->get_results( $query );
			set_transient("profile_cct_name_list", $data, 10 * 60);
		endif;
		return $data;
	}
	
	public static function profile_search_shortcode($atts){
		//static $has_search_box = false;
		ob_start();
		?>
		
		<div class="profile-cct-search">
			<form action="<?php echo get_bloginfo('siteurl'); ?>" method="get">
				
				<input type="text" name="s" class="profile-cct-search" />
				<input type="hidden" name="post_type" value="profile_cct" />
				<input type="submit" value="Search People" />
			</form>
			<?php
			$names = array();
			$query_results = Profile_CCT_Shortcodes::get_all_names();
			foreach( $query_results as $result ):
				$names[] = $result->post_title;
			endforeach;
			?>	
			<script>
				jQuery( function() {
					var availableTags = <?php echo json_encode( $names ); ?>;
					jQuery( ".profile-cct-search" ).autocomplete({
						source: availableTags
					});
				});
			</script>
		</div>
		<?php
		return ob_get_clean();
	}
	

}
Profile_CCT_Shortcodes::init();