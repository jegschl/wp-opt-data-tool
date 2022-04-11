<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://empdigital.cl
 * @since      1.0.0
 *
 * @package    Wp_Opt_Data_Tool
 * @subpackage Wp_Opt_Data_Tool/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Opt_Data_Tool
 * @subpackage Wp_Opt_Data_Tool/includes
 * @author     Jorge Garrido <jegschl@gmail.com>
 */
class Wp_Opt_Data_Tool_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-opt-data-tool',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
