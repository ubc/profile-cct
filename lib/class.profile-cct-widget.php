<?php 
/**
 * Profile_CCT_Widget class.
 * 
 * @extends WP_Widget
 */
class Profile_CCT_Widget extends WP_Widget {
	function init() {
		add_action( 'widgets_init', array( __CLASS__, 'register' ) );
		//add_action( 'parse_query',  array( __CLASS__, 'print_query' ) );
		//add_action( 'parse_query',  array( __CLASS__, 'search_query' ) );
	}
	
	function register() {
		register_widget( "Profile_CCT_Widget" );
	}
	
	function print_query( $query ) {
		echo '<pre>';
		print_r($query->query);
		echo '</pre>';
	}
	
	function search_query( $query ) {
		echo '===Parse===';
		echo '<pre>';
		print_r($query);
		echo '</pre>';
		
		if ( isset( $_GET['s'] ) && isset( $query->query['post_type'] ) && $query->query['post_type'] == 'profile_cct' ):
			foreach ( $_GET as $key => $param ) {
				if ( ! empty( $param ) ):
					$query->query[$key] = $param;
				endif;
			}
		endif;
		
		echo '<br />';
		echo '===Result===';
		echo '<pre>';
		print_r($query);
		echo '</pre>';
		
		return $query;
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
						<select name="">
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
						<input type="hidden" name="meta_key" value="last_name" />
						<select name="orderby">
							<option value="menu_order" selected="selected">Default</option>
							<option value="title">First Name</option>
							<option value="meta_value">Last Name</option>
							<option value="date">Date Added</option>
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
				<input type="submit" value="Search People" />
			</form>
		</div>
		<?php
		return ob_get_clean();
	}
}

// Lets initate the widget
Profile_CCT_Widget::init();