<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://jamespham.io
 * @since      1.0.0
 *
 * @package    Woocommerce_Custom_Emails
 * @subpackage Woocommerce_Custom_Emails/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Custom_Emails
 * @subpackage Woocommerce_Custom_Emails/includes
 * @author     James Pham <jamespham93@yahoo.com>
 */
class Woocommerce_Custom_Emails_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-custom-emails',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
