<?php
/**
Plugin Name: Profile Custom Content Type
Plugin URI:
Version: 1.2.3
Text Domain: profile_cct
Domain Path: /languages
Description: Allows administrators to manage user profiles better in order to display them on their websites
Author: Enej Bajgoric, Eric Jackish, Aleksandar Arsovski, CTLT, UBC
Licence: GPLv2
Author URI: http://ctlt.ubc.ca
 */
// this file should be renamed to profile-custom-content-type for backward compatibilty



if ( !defined('ABSPATH') )
	die('-1');

define( 'PROFILE_CCT_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'PROFILE_CCT_BASENAME', plugin_basename(__FILE__) );
define( 'PROFILE_CCT_DIR_URL',  plugins_url( ''  , PROFILE_CCT_BASENAME ) );
define( 'PROFILE_CCT_VERSION', 1 );

require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct.php' );
require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct-admin.php' );
require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct-fields.php' );

require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct-widget.php' );
/*

require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct-shortcodes.php' );
require( PROFILE_CCT_DIR_PATH.'lib/class.profile-cct-taxonomies.php' );


*/


