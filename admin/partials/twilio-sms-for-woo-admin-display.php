<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://localleadminer.com/
 * @since      1.0.0
 *
 * @package    Twilio_Sms_For_Woo
 * @subpackage Twilio_Sms_For_Woo/admin/partials
 */
class AngellEYE_Twilio_SMS_For_Woo_Admin_Display {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_settings_menu'));
    }

    /**
     * add_settings_menu helper function used for add menu for pluging setting
     * @since    1.0.0
     * @access   public
     */
    public static function add_settings_menu() {
        add_options_page('Twilio SMS For Woo', 'Twilio SMS For Woo', 'manage_options', 'twilio-sms-for-woo-option', array(__CLASS__, 'twilio_sms_for_woo_options'));
    }

    /**
     * twilio_sms_for_woo_options helper will trigger hook and handle all the settings section
     * @since    1.0.0
     * @access   public
     */
    public static function twilio_sms_for_woo_options() {
        do_action('twilio_sms_for_woo_setting_save_field');
        do_action('twilio_sms_for_woo_setting');
    }

}

AngellEYE_Twilio_SMS_For_Woo_Admin_Display::init();
