<?php 
/**
 * Profile_CCT_Widget class.
 * 
 * @extends WP_Widget
 */
class Profile_CCT_Widget extends WP_Widget {
	function init() {
		add_action( 'widgets_init',  array( __CLASS__, 'register' ) );
		add_action( 'pre_get_posts', array( __CLASS__, 'filter_profile') , 10, 1 );
		add_filter( 'posts_clauses', array( __CLASS__, 'intercept_query_clauses'), 20, 2 );
	}
	
	function register() {
		register_widget( "Profile_CCT_Widget" );
	}
	
	function filter_profile($query){
		if ( !empty( $_GET['alphabet'] ) && !is_admin() && $query->is_main_query() && $query->get('post_type') == 'profile_cct' ):
			$query->set( 'meta_key',   'profile_cct_last_name' );
			$query->set( 'meta_value', $_GET['alphabet'].'%' );
		endif;
	}
	
	function intercept_query_clauses( $pieces, $query ) {
		global $wpdb;
		
		// only apply this to post type = profile on the front end and on the main query
		if ( $query->get('post_type') == 'profile_cct' && !is_admin() && $query->is_main_query() ):
			if ( 'DESC' == $_GET['order'] ):
				$pieces['orderby'] = str_replace( ' ASC', ' DESC', $pieces['orderby'] );
			endif;
			
			if ( !empty( $_GET['alphabet'] ) ):
				$pieces['where'] = str_replace( 'CAST('.$wpdb->postmeta.'.meta_value AS CHAR) =', $wpdb->postmeta.'.meta_value LIKE ', $pieces['where'] );
			endif;
		endif;
		
		return $pieces;
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
		$profile = Profile_CCT::get_object();
		?>
		<h3 class="widget-title"><?php echo $profile->settings['widget_title']; ?></h3>
		<?php
		
		echo self::profile_search( $profile->settings['archive'] );
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
	
	function profile_search( $visible ) {
		$profile = Profile_CCT::get_object();
		
		ob_start();
		?>
		<div class="profile-cct-search-form">
			<form action="<?php echo get_bloginfo( 'siteurl' ); ?>" method="get">
				<?php
					if ( $visible['display_searchbox'] == 'true' ):
						?>
						<div class="profile-cct-searchbox profile-cct-search-input">
							<input type="text" name="s" class="profile-cct-search" placeholder="Search Profiles"/>
						</div>
						<?php
						
						wp_enqueue_script( 'profile-cct-autocomplete' );
					endif;
					
					if ( $visible['display_alphabet'] == 'true' ):
						?>
						<div class="profile-cct-search-alphabet profile-cct-search-input">
							<label for="profile-cct-alphabet">Alphabet</label>
							<select name="alphabet" id="profile-cct-alphabet">
								<option value="" selected="selected">Any</option>
								<?php
								foreach ( range('A', 'Z') as $letter ):
									?>
									<option value="<?php echo $letter; ?>"><?php echo $letter; ?></option>
									<?php
								endforeach;
								?>
							</select>
						</div>
						<?php
					endif;
					
					if ( $visible['display_orderby'] == 'true' ):
						?>
						<div class="profile-cct-search-orderby profile-cct-search-input">
							<label for="profile-cct-orderby">Order by</label>
							<select name="orderby" id="profile-cct-orderby">
								<option value="menu_order" selected="selected">Default Order</option>
								<option value="post_title">First Name</option>
								<option value="meta_value">Last Name</option>
								<option value="post_date">Date Added</option>
							</select>
							
							<label for="profile-cct-order">Order</label>
							<select name="order" id="profile-cct-order">
								<option value="ASC" selected="selected">Ascending A - Z</option>
								<option value="DESC">Descending Z - A</option>
							</select>
						</div>
						<?php
					endif;
					
					if ( ! empty( $visible['display_tax'] ) ):
						foreach ( $visible['display_tax'] as $taxonomy_id => $value ):
							$taxonomy = get_taxonomy($taxonomy_id);
							?>
							<div class="profile-cct-search-<?php echo $taxonomy_id; ?> profile-cct-search-input">
								<label for="profile-cct-<?php echo $taxonomy_id; ?>"><?php echo $taxonomy->label; ?></label>
								<select name="<?php echo $taxonomy_id; ?>" id="profile-cct-<?php echo sanitize_html_class( $taxonomy->label ); ?>">
									<option value="" selected="selected">All <?php echo $taxonomy->label; ?></option>
									<?php
									foreach ( get_terms( $taxonomy_id, array() ) as $term ):
										?>
										<option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
										<?php
									endforeach;
									?>
								</select>
							</div>
							<?php
						endforeach;
					endif;
				?>
				<input type="hidden" name="post_type" value="profile_cct" />
				<input type="submit" value="Search Profiles" class="button btn" />
			</form>
		</div>
		<?php
		return ob_get_clean();
	}
	
}

// Lets initate the widget
Profile_CCT_Widget::init();
