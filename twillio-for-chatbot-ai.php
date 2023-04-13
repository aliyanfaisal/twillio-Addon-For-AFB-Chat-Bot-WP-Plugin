<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://moveupmarketinggroup.com/
 * @since             1.0.0
 * @package           Twillio_For_Chatbot_Ai
 *
 * @wordpress-plugin
 * Plugin Name:       Twilio Integration for Maven ChatBot
 * Plugin URI:        https://moveupmarketinggroup.com/
 * Description:       Twilio SMS and WhatsApp Integration for Maven ChatBot. Admin will receive alerts.
 * Version:           1.0.0
 * Author:            MoveUpMarketingGroup
 * Author URI:        https://moveupmarketinggroup.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       twillio-for-chatbot-ai
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define("TwilioChatBotPath", plugin_dir_path( __FILE__ ));
define("TwilioChatBotUrl", plugin_dir_url(__FILE__));

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TWILLIO_FOR_CHATBOT_AI_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-twillio-for-chatbot-ai-activator.php
 */
function activate_twillio_for_chatbot_ai() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-twillio-for-chatbot-ai-activator.php';
	Twillio_For_Chatbot_Ai_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-twillio-for-chatbot-ai-deactivator.php
 */
function deactivate_twillio_for_chatbot_ai() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-twillio-for-chatbot-ai-deactivator.php';
	Twillio_For_Chatbot_Ai_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_twillio_for_chatbot_ai' );
register_deactivation_hook( __FILE__, 'deactivate_twillio_for_chatbot_ai' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-twillio-for-chatbot-ai.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_twillio_for_chatbot_ai() {

	$plugin = new Twillio_For_Chatbot_Ai();
	$plugin->run();

}
run_twillio_for_chatbot_ai();




// REST ROUTES HERE

require_once TwilioChatBotPath."includes/rest-routes/twilio-chat-bot-ai-rest-routes.php";





















add_action("admin_menu", function () {

	if (is_admin() && current_user_can('activate_plugins') &&  !class_exists('Chat_Bot_Ai')) {
		add_action('admin_notices', function () {
			echo '<div class="error"><p>Sorry, but <b>Twilio Integration for Maven Chat Bot</b> Plugin requires the <b>Maven ChatBot </b>from <a href="https://MoveUpMarketingGroup.com"> MoveUpMarketingGroup</a> to be installed and active.</p></div>';
		});
	}
});