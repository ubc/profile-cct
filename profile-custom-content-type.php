<?php
/**
 * Plugin Name: Profile Custom Content Type
 * Plugin URI: https://github.com/ubc/profile-cct
 * Version: 1.4.1
 * Description: Allows administrators to manage user profiles better in order to display them on their websites
 * Author: Enej Bajgoric, Devindra Payment, Eric Jackish, Aleksandar Arsovski,  CTLT, UBC
 * Author URI: http://ctlt.ubc.ca
 * Text Domain: profile_cct
 * Domain Path: /languages
 * Licence: GPLv2
 */

if ( ! defined( 'ABSPATH' ) )
	die( '-1' );

define( 'PROFILE_CCT_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'PROFILE_CCT_BASENAME', plugin_basename( __FILE__ ) );
define( 'PROFILE_CCT_DIR_URL', plugins_url( '', PROFILE_CCT_BASENAME ) );
define( 'PROFILE_CCT_BASE_FILE', __FILE__ );
define( 'PROFILE_CCT_VERSION', '1.4.1' );

define( 'PROFILE_CCT_SETTINGS', 'Profile_CCT_settings' );
define( 'PROFILE_CCT_SETTING_VERSION', 'Profile_CCT_version' );
define( 'PROFILE_CCT_SETTING_TAXONOMY', 'Profile_CCT_taxonomy' );
define( 'PROFILE_CCT_SETTING_GLOBAL', 'Profile_CCT_global_settings' );

define( 'PROFILE_CCT_TAXONOMY_PREFIX', 'profile_cct_' );
define( 'PROFILE_CCT_TAXONOMY_META', 'profile_cct_taxonomy' );

require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct.php' );
require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct-admin.php' );
require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct-field.php' );
require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct-taxonomies.php' );
require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct-shortcodes.php' );
require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct-manage-table.php' );
require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct-widget.php' );
require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct-autocomplete.php' );

// Dismissable notice for plugin decomissioning.
require PROFILE_CCT_DIR_PATH . 'vendor/persist-admin-notices-dismissal/persist-admin-notices-dismissal.php';
add_action( 'admin_init', array( 'PAnD', 'init' ) );

/**
 * Add the initial plugin deprecation notice. This is only shown to administrators.
 *
 * @return void
 */
function profilecct_plugin_deprecation_admin_notice_one() {

	if ( ! PAnD::is_admin_notice_active( 'notice-one-forever' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Count of Profile CCT Posts.
	$count_posts = wp_count_posts( 'profile_cct' );
	$total_posts = $count_posts->publish;

	?>
	<div data-dismissible="notice-one-forever" class="notice notice-error is-dismissible" style="overflow: hidden; padding: 1.5rem 2rem;">
		<h1 style="margin-bottom: 0.25rem;">
			<svg style="position: relative; top: 3px; margin-right: 0.25rem;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> 
			<?php esc_html_e( 'Profile CCT Plugin Removal. Action Required.', 'profile-cct' ); ?>
		</h1>

		<p style="margin-bottom: 1rem;"><strong><?php esc_html_e( 'This impacts your site and the content on it. Please read, it\'s important!', 'profile-cct' ); ?></strong></p>
		<p><?php echo wp_kses_post( 'On <strong>December 2 2022</strong>, the Profile Custom Content Type plugin &ndash; which this site uses &ndash; will be removed from UBC CMS. This will mean the ' . absint( $total_posts ) . ' profiles published on your site will be unavailable. We have written about this on the CMS blog with <a href="https://cms.ubc.ca/alternatives-to-the-profiles-cct-plugin/">documentation for several different options</a> you have. We are also running a series of <a href="https://cms.ubc.ca/gutenberg-workshop/">half-day workshops</a> specifically designed to help you recreate your profiles on this site in a supported way.' ); ?></p>
		<p><?php echo wp_kses_post( 'If you choose to take no action, then on December 2 2022 the public profiles you have on your site will be unavailable and you will be unable to access the data from the back end of WordPress. If you wish to maintain the profiles on this website, you will need to take action before December 2 2022.' ); ?></p>
		<p><?php echo wp_kses_post( 'This message is shown to all administrators on this site. You are able to permanently dismiss it by pressing the "x" in the top right of this box, but we encourage you to first read more about this and/or sign up for a workshop.' ); ?></p>

		<hr style="margin: 1.5rem 0;" />

		<p><?php echo wp_kses_post( '<a href="https://cms.ubc.ca/alternatives-to-the-profiles-cct-plugin/" class="button button-primary">More Details</a>' ); ?> &nbsp;&mdash;&nbsp; or &nbsp;&mdash;&nbsp; <?php echo wp_kses_post( '<a href="https://cms.ubc.ca/gutenberg-workshop/" class="button button-secondary">Workshop Registration</a>' ); ?></p>
	</div>
	<?php

} // end profilecct_plugin_deprecation_admin_notice_one()

add_action( 'admin_notices', 'profilecct_plugin_deprecation_admin_notice_one' );
