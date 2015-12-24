<?php
/*
Plugin Name: 50 Plugins
Plugin URI: http://pootlepress.com/
Description: Lists 50 plugins handpicked by experts to get you your WP website up and running!
Author: pootlepress
Version: 1.0.0
Author URI: http://pootlepress.com/
@developer shramee <shramee.srivastav@gmail.com>
*/

/** Plugin admin class */
require 'inc/class-admin.php';
/** Including Main Plugin class */
require 'class-plugins-50.php';
/** Intantiating main plugin class */
Plugins_50::instance( __FILE__ );

/** Addon update API */
add_action( 'plugins_loaded', 'Plugins_50_api_init' );

/**
 * Instantiates Pootle_Page_Builder_Addon_Manager with current add-on data
 * @action plugins_loaded
 */
function Plugins_50_api_init() {
	//Return if POOTLEPB_DIR not defined
	if ( ! defined( 'POOTLEPB_DIR' ) ) { return; }
	/** Including PootlePress_API_Manager class */
	require_once POOTLEPB_DIR . 'inc/addon-manager/class-manager.php';
	/** Instantiating PootlePress_API_Manager */
	new Pootle_Page_Builder_Addon_Manager(
		Plugins_50::$token,
		'50 Plugins',
		Plugins_50::$version,
		Plugins_50::$file,
		Plugins_50::$token
	);
}
