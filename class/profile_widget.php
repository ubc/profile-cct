<?php
function profile_cct_widget_init(){
register_widget('Profile_CCT_widget');
}
add_action( 'widgets_init', 'profile_cct_widget_init' );

class Profile_CCT_widget extends WP_Widget{
	function profile_cct_widget(){
		parent::__construct(false, 'Profile Navigation');
	}
	
	
	function widget($args, $instance){
		echo do_action('profile_cct_display_archive_controls', array('mode'=>'widget'));
	}
	
	
	function update($new_instance, $old_instance){
	
	}
	
	
	function form($instance){
		echo 'Customize in Profiles->Settings->Settings';
	}
}