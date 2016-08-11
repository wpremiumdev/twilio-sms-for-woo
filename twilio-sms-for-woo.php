<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Twilio SMS For Woo
 * Plugin URI:        http://localleadminer.com/
 * Description:       WooCommerce Twilio SMS Notifications.
 * Version:           1.0.6
 * Author:            mbj-webdevelopment
 * Author URI:        http://localleadminer.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       twilio-sms-for-woo
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
if (!defined('TWILIO_SMS_FOR_WOO_LOG_DIR')) {
    $upload_dir = wp_upload_dir();
    define('TWILIO_SMS_FOR_WOO_LOG_DIR', $upload_dir['basedir'] . '/twilio-sms-for-woo-logs/');
}
if (!defined('TWILLIO_PLUGIN_DIR_PATH')) {
    define('TWILLIO_PLUGIN_DIR_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-twilio-sms-for-woo-activator.php
 */
function activate_twilio_sms_for_woo() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-twilio-sms-for-woo-activator.php';
    Twilio_Sms_For_Woo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-twilio-sms-for-woo-deactivator.php
 */
function deactivate_twilio_sms_for_woo() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-twilio-sms-for-woo-deactivator.php';
    Twilio_Sms_For_Woo_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_twilio_sms_for_woo');
register_deactivation_hook(__FILE__, 'deactivate_twilio_sms_for_woo');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-twilio-sms-for-woo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_twilio_sms_for_woo() {

    $plugin = new Twilio_Sms_For_Woo();
    $plugin->run();
}

run_twilio_sms_for_woo();