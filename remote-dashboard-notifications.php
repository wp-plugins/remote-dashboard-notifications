<?php
/**
 * Remote Dashobard Notifications.
 *
 * This plugin allows to push any notification to the WordPress dashboard.
 * This allows easy communication with the clients.
 *
 * @package   Remote Dashboard Notifications
 * @author    ThemeAvenue <web@themeavenue.net>
 * @license   GPL-2.0+
 * @link      http://themeavenue.net
 * @copyright 2013 ThemeAvenue
 *
 * @wordpress-plugin
 * Plugin Name:       Remote Dashboard Notifications
 * Plugin URI:        https://github.com/ThemeAvenue/Remote-Dashboard-Notifications
 * Description:       Remote Dashboard Notifications is made for themes and plugins developers who want to send short notifications to their users.
 * Version:           1.0.0
 * Author:            ThemeAvenue
 * Author URI:        http://themeavenue.net
 * Text Domain:       remote-notifications
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'RDN_URL', plugin_dir_url( __FILE__ ) );
define( 'RDN_PATH', plugin_dir_path( __FILE__ ) );

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-remote-notifications.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Remote_Notifications', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Remote_Notifications', 'deactivate' ) );

/**
 * Instanciate public class
 */
add_action( 'plugins_loaded', array( 'Remote_Notifications', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/**
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() ) {

	require_once( RDN_PATH . 'admin/class-remote-notifications-admin.php' );
	add_action( 'plugins_loaded', array( 'Remote_Notifications_Admin', 'get_instance' ) );

	/**
	 * Instanciate the client class
	 */
	require_once( RDN_PATH . 'includes/class-remote-notification-client.php' );

	if( class_exists( 'TAV_Remote_Notification_Client' ) )
		$rdn = new TAV_Remote_Notification_Client( 116, '88d07387308e6b7f', 'http://support.themeavenue.net?post_type=notification' );

}