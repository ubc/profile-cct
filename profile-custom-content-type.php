<?php
/**
  Plugin Name: Profile Custom Content Type
  Plugin URI: https://github.com/ubc/profile-cct
  Version: 1.3.2
  Description: Allows administrators to manage user profiles better in order to display them on their websites
  Author: Enej Bajgoric, Devindra Payment, Eric Jackish, Aleksandar Arsovski,  CTLT, UBC
  Author URI: http://ctlt.ubc.ca
  Text Domain: profile_cct
  Domain Path: /languages
  Licence: GPLv2
 */

if ( ! defined( 'ABSPATH' ) )
    die( '-1' );

define( 'PROFILE_CCT_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'PROFILE_CCT_BASENAME', plugin_basename( __FILE__ ) );
define( 'PROFILE_CCT_DIR_URL', plugins_url( '', PROFILE_CCT_BASENAME ) );
define( 'PROFILE_CCT_BASE_FILE', __FILE__ );
define( 'PROFILE_CCT_VERSION', '1.3.1' );

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
