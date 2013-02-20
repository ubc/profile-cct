<?php 
/**
 * Profile_CCT_Widget class.
 * 
 * @extends WP_Widget
 */
class Profile_CCT_Widget extends WP_Widget {
	static $action = 'profile_cct_autocomplete'; //Name of the action - should be unique to your plugin.
	
	function init() {
		register_widget( "Profile_CCT_Widget" );
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
		$profile = Profile_CCT::get_object();
		
		if ( $title ):
			?>
			<h3 class="widget-title">Profile Navigation</h3>
			<?php
		endif;
		
		if ( $profile->settings['archive']['display_searchbox'] == 'on' ):
			echo Profile_CCT_Shortcodes::profile_search_shortcode();
		endif;
		
		if ( $profile->settings['archive']['display_alphabet'] == 'on' ):
			echo Profile_CCT_Shortcodes::profile_search_shortcode();
		endif;
		
		if ( $profile->settings['archive']['display_orderby'] == 'on' ):
			?>
			<select name="sort_order_by" id="sort_order_by">
				<option value="first_name" selected="selected">First Name</option>
				<option value="last_name">Last Name</option>
				<option value="date">Date Added</option>
			</select>
			<select name="sort_order" id="sort_order">
				<option value="ASC" selected="selected">Ascending A - Z</option>
				<option value="DESC">Descending Z - A</option>
			</select>
			<?php
		endif;
		
		if ( ! empty( $profile->settings['archive']['display_tax'] ) ):
			foreach ( $profile->settings['archive']['display_tax'] as $taxonomy_id => $value ):
				$taxonomy = get_taxonomy($taxonomy_id);
				?>
				<select name="<?php echo $taxonomy_id; ?>">
					<option value="all" selected="selected">All <?php echo $taxonomy->label; ?></option>
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
}

// Lets initate the widget
add_action( 'widgets_init', array( 'Profile_CCT_Widget', 'init' ) );