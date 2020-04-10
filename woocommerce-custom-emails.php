<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://jamespham.io
 * @since             1.0.0
 * @package           Woocommerce_Custom_Emails
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Custom Emails
 * Plugin URI:        https://github.com/jpham/wc-custom-emails
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            James Pham
 * Author URI:        https://jamespham.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-custom-emails
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOOCOMMERCE_CUSTOM_EMAILS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-custom-emails-activator.php
 */
function activate_woocommerce_custom_emails() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-custom-emails-activator.php';
	Woocommerce_Custom_Emails_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-custom-emails-deactivator.php
 */
function deactivate_woocommerce_custom_emails() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-custom-emails-deactivator.php';
	Woocommerce_Custom_Emails_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_custom_emails' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_custom_emails' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-custom-emails.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_custom_emails() {

	$plugin = new Woocommerce_Custom_Emails();
	$plugin->run();

}
run_woocommerce_custom_emails();
