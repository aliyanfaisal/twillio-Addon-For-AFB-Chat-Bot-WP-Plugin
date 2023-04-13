<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://moveupmarketinggroup.com/
 * @since      1.0.0
 *
 * @package    Twillio_For_Chatbot_Ai
 * @subpackage Twillio_For_Chatbot_Ai/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Twillio_For_Chatbot_Ai
 * @subpackage Twillio_For_Chatbot_Ai/includes
 * @author     MoveUpMarketingGroup <https://moveupmarketinggroup.com/>
 */
class Twillio_For_Chatbot_Ai_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'twillio-for-chatbot-ai',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
