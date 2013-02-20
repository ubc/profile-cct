<?php 
/**
 * Profile_CCT_Widget class.
 * 
 * @extends WP_Widget
 */
class Profile_CCT_Widget extends WP_Widget {
	function init() {
		add_action( 'widgets_init',  array( __CLASS__, 'register' ) );
		add_filter( 'posts_orderby', array( __CLASS__, 'sort_query' ), 10, 2 );
		add_filter( 'the_posts',     array( __CLASS__, 'sort_posts' ), 10, 2 );
	}
	
	function register() {
		register_widget( "Profile_CCT_Widget" );
	}
	
	/**
	 * Apply the orderby and order parameters that were given into this search.
	 */
	function sort_query( $orderby, $query ) {
		global $wpdb;
		
		if ( ! is_admin() && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'profile_cct' ):
			$order_by = ( ! Profile_CCT_Widget::starts_with( $_GET['orderby'], 'profile_cct_' ) ? $_GET['orderby'] : 'menu_order' );
			$order    = ( $_GET['order'] == 'DESC' ? 'DESC' : 'ASC' );
			$orderby  = $wpdb->prefix."posts.".$order_by." ".$order.", ".$orderby;
		endif;
		
		return $orderby;
	}
	
	/**
	 * Apply the orderby and order parameters that were given into this search.
	 */
	function sort_posts( $posts, $query ) {
		if ( ! is_admin() && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'profile_cct' ):
			if ( ! empty( $_GET['alphabet'] ) ):
				foreach ( $posts as $index => $post ):
					$last_name = get_post_meta( $post->ID, 'profile_cct_last_name', true );
					if ( substr( $last_name, 0, 1 ) != $_GET['alphabet'] ) unset( $posts[$index] );
				endforeach;
			endif;
			
			if ( ! empty( $_GET['orderby'] ) && Profile_CCT_Widget::starts_with( $_GET['orderby'], 'profile_cct_' ) ):
				$posts_copy = $posts;
				unset( $posts );
				$posts = array();
				foreach ( $posts_copy as $index => $post ):
					$last_name = get_post_meta( $post->ID, $_GET['orderby'], true );
					$index = $last_name." ".$post->post_title;
					$posts[$index] = $post;
				endforeach;
				
				if ( $_GET['order'] == 'DESC' ):
					krsort( $posts, SORT_STRING );
				else:
					ksort( $posts, SORT_STRING );
				endif;
			endif;
			
			$posts = array_values( $posts ); // Re-index the array
		endif;
		
		return $posts;
	}

	/**
	 * Register widget with WordPress.
	 * 
	 * @access public
	 * @return void
	 */
	function __construct() {
		parent::__construct( 
	 		'profile_cct_navigation', // Base ID
			'Profile Navigation', // Name
			array( 'description' => __( 'Allows the user to search through the list of public profiles.', 'profile_cct' ), ) // Args
		);
	}
	
	/**
	 * Front-end display of widget.
	 * 
	 * @access public
	 * @param mixed $args
	 * @param mixed $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		?>
		<h3 class="widget-title">Profile Navigation</h3>
		<?php
		
		echo Profile_CCT_Widget::profile_search( true, true, true, true );
	}
	
	/**
	 * Sanitize widget form values as they are saved.
	 * 
	 * @access public
	 * @param mixed $new_instance
	 * @param mixed $old_instance
	 * @return void
	 */
	function update( $new_instance, $old_instance ) {
		// there is nothing to update for now
	}
	
	/**
	 * Back-end widget form.
	 * 
	 * @access public
	 * @param mixed $instance
	 * @return void
	 */
	function form( $instance ) {
		?>
		Customize in <a href="<?php echo admin_url( 'edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASEADMIN.'&view=settings' ); ?>">Profiles Settings</a>
		<?php
	}
	
	function profile_search( $searchbox = true, $alphabet = false, $orderby = false, $taxonomies = false ) {
		$profile = Profile_CCT::get_object();
		
		ob_start();
		?>
		<div class="profile-cct-search-form">
			<form action="<?php echo get_bloginfo('siteurl'); ?>" method="get">
				<?php
					if ( $searchbox == true && $profile->settings['archive']['display_searchbox'] == 'on' ):
						?>
						<input type="text" name="s" class="profile-cct-search" />
						<?php
					endif;
					
					if ( $alphabet == true && $profile->settings['archive']['display_alphabet'] == 'on' ):
						?>
						<select name="alphabet">
							<option value="" selected="selected">Any</option>
							<?php
							foreach ( range('A', 'Z') as $letter ):
								?>
								<option value="<?php echo $letter; ?>"><?php echo $letter; ?></option>
								<?php
							endforeach;
							?>
						</select>
						<?php
					endif;
					
					if ( $orderby == true && $profile->settings['archive']['display_orderby'] == 'on' ):
						?>
						<select name="orderby">
							<option value="menu_order" selected="selected">Default Order</option>
							<option value="post_title">First Name</option>
							<option value="profile_cct_last_name">Last Name</option>
							<option value="post_date">Date Added</option>
						</select>
						<select name="order">
							<option value="ASC" selected="selected">Ascending A - Z</option>
							<option value="DESC">Descending Z - A</option>
						</select>
						<?php
					endif;
					
					if ( $taxonomies == true && ! empty( $profile->settings['archive']['display_tax'] ) ):
						foreach ( $profile->settings['archive']['display_tax'] as $taxonomy_id => $value ):
							$taxonomy = get_taxonomy($taxonomy_id);
							?>
							<select name="<?php echo $taxonomy_id; ?>">
								<option value="" selected="selected">All <?php echo $taxonomy->label; ?></option>
								<?php
								foreach ( get_terms( $taxonomy_id, array() ) as $term ):
									?>
									<option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
									<?php
								endforeach;
								?>
							</select>
							<?php
						endforeach;
					endif;
				?>
				<input type="hidden" name="post_type" value="profile_cct" />
				<input type="submit" value="Search Profiles" />
			</form>
		</div>
		<?php
		return ob_get_clean();
	}
	
	function starts_with( $haystack, $needle ) {
		return ! strncmp( $haystack, $needle, strlen( $needle ) );
	}
}

// Lets initate the widget
Profile_CCT_Widget::init();