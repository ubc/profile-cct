<?php 





/**
 * Profile_CCT_Widget class.
 * 
 * @extends WP_Widget
 */
class Profile_CCT_Widget extends WP_Widget {
		
	/**
	 * profile_cct_widget function.
	 * 
	 * @access public
	 * @return void
	 */
	function profile_cct_widget(){
		parent::__construct( false, 'Profile Navigation' );
	}
	
	
	/**
	 * widget function.
	 * 
	 * @access public
	 * @param mixed $args
	 * @param mixed $instance
	 * @return void
	 */
	function widget( $args, $instance ){
		echo do_action( 'profile_cct_display_archive_controls', array('mode' => 'widget'));
	}
	
	
	/**
	 * update function.
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
	 * form function.
	 * 
	 * @access public
	 * @param mixed $instance
	 * @return void
	 */
	function form( $instance ) {
		echo 'Customize in <a href="'. admin_url('edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASENAME.'&view=settings').'" title="Profiles Settings">Profiles Settings</a>';
	}
}
// Lets initate the widget
add_action( 'widgets_init', create_function( '', 'register_widget( "Profile_CCT_Widget" );' ) );