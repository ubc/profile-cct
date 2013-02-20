<?php 
/**
 * Profile_CCT_Widget class.
 * 
 * @extends WP_Widget
 */
class Profile_CCT_Widget extends WP_Widget {
	static $action = 'profile_cct_autocomplete'; //Name of the action - should be unique to your plugin.
	
	function init() {
		add_action( 'widgets_init', array( __CLASS__, 'register' ) );
		add_action( 'parse_query', array( __CLASS__, 'search_query' ) );
		add_action( 'query', array( __CLASS__, 'query' ) );
	}
	
	function register() {
		register_widget( "Profile_CCT_Widget" );
	}
	
	function search_query( $query ) {
		if ( isset( $_GET['s'] ) && isset( $query->query['post_type'] ) && $query->query['post_type'] == 'profile_cct' ):
			if ( isset( $_GET['order'] ) ):
				$query->query['order'] = $_GET['order'];
				$query->query_vars['order'] = $_GET['order'];
			endif;
			
			if ( isset( $_GET['orderby'] ) ):
				$query->query['order'] = $_GET['order'];
				$query->query_vars['order'] = $_GET['order'];
			endif;
			
			/*
			if ( isset( $_GET['tax_query'] ) ):
				foreach ( $_GET['tax_query'] as $tax_query ) {
					$query->query['tax_query']['queries'][] = $tax_query;
					$query->query_vars['tax_query']['queries'][] = $tax_query;
				}
				
				$query->query['tax_query']['relation'] = $query->query_vars['tax_query']['relation'];
			endif;
			*/
		endif;
		
		return $query;
	}
	
	function query( $query ) {
		error_log("---Query---");
		error_log(print_r($query, TRUE));
		
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
	function widget( $args, $instance, $title = true ) {
		if ( $title ):
			?>
			<h3 class="widget-title">Profile Navigation</h3>
			<?php
		endif;
		
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
						<select name="orderby">
							<option value="first_name" selected="selected">First Name</option>
							<option value="last_name">Last Name</option>
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
							<input type="hidden" name="tax_query[<?php echo $taxonomy_id; ?>][taxonomy]" value="<?php echo $taxonomy_id; ?>" />
							<select name="tax_query[<?php echo $taxonomy_id; ?>][field]">
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
add_action( 'init', array( 'Profile_CCT_Widget', 'init' ) );