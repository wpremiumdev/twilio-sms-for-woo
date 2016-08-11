<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Twilio_Sms_For_Woo
 * @subpackage Twilio_Sms_For_Woo/includes
 * @author     mbj-webdevelopment <mbjwebdevelopment@gmail.com>
 */
class Twilio_Sms_For_Woo {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Twilio_Sms_For_Woo_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_name = 'twilio-sms-for-woo';
        $this->version = '1.0.6';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_constants();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Twilio_Sms_For_Woo_Loader. Orchestrates the hooks of the plugin.
     * - Twilio_Sms_For_Woo_i18n. Defines internationalization functionality.
     * - Twilio_Sms_For_Woo_Admin. Defines all hooks for the admin area.
     * - Twilio_Sms_For_Woo_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-twilio-sms-for-woo-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-twilio-sms-for-woo-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-twilio-sms-for-woo-admin.php';

        /**
         * The class responsible for defining all actions that occur that occur in the Dashboard.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/twilio-sms-for-woo-admin-display.php';

        /**
         * The class responsible for defining function for display general setting tab
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-twilio-sms-for-woo-setting.php';

        /**
         * The class responsible for defining function for display Html element
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-twilio-sms-for-woo-html-output.php';

        /**
         * The class responsible for defining all function related to log file
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-twilio-sms-for-woo-logger.php';

        $this->loader = new Twilio_Sms_For_Woo_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Twilio_Sms_For_Woo_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Twilio_Sms_For_Woo_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Twilio_Sms_For_Woo_Admin($this->get_plugin_name(), $this->get_version());


        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('woocommerce_order_status_pending', $plugin_admin, 'twilio_send_customer_sms_for_woo_order_status_pending', 10, 1);
        $this->loader->add_action('woocommerce_order_status_failed', $plugin_admin, 'twilio_send_customer_sms_for_woo_order_status_failed', 10, 1);
        $this->loader->add_action('woocommerce_order_status_on-hold', $plugin_admin, 'twilio_send_customer_sms_for_woo_order_status_on_hold', 10, 1);
        $this->loader->add_action('woocommerce_order_status_processing', $plugin_admin, 'twilio_send_customer_sms_for_woo_order_status_processing', 10, 1);
        $this->loader->add_action('woocommerce_order_status_completed', $plugin_admin, 'twilio_send_customer_sms_for_woo_order_status_completed', 10, 1);
        $this->loader->add_action('woocommerce_order_status_refunded', $plugin_admin, 'twilio_send_customer_sms_for_woo_order_status_refunded', 10, 1);
        $this->loader->add_action('woocommerce_order_status_cancelled', $plugin_admin, 'twilio_send_customer_sms_for_woo_order_status_cancelled', 10, 1);
        $this->loader->add_action('woocommerce_order_status_pending_to_on-hold', $plugin_admin, 'twilio_send_admin_order_notification_sms', 10, 1);
        $this->loader->add_action('woocommerce_order_status_pending_to_processing', $plugin_admin, 'twilio_send_admin_order_notification_sms', 10, 1);
        $this->loader->add_action('woocommerce_order_status_pending_to_completed', $plugin_admin, 'twilio_send_admin_order_notification_sms', 10, 1);
        $this->loader->add_action('woocommerce_order_status_failed_to_on-hold', $plugin_admin, 'twilio_send_admin_order_notification_sms', 10, 1);
        $this->loader->add_action('woocommerce_order_status_failed_to_processing', $plugin_admin, 'twilio_send_admin_order_notification_sms', 10, 1);
        $this->loader->add_action('woocommerce_order_status_failed_to_completed', $plugin_admin, 'twilio_send_admin_order_notification_sms', 10, 1);

        $this->loader->add_filter('twilio_sms_for_woo_paypal_args', $plugin_admin, 'twilio_sms_for_woo_paypal_args', 10, 1);
        $this->loader->add_filter('twilio_sms_for_woo_paypal_digital_goods_nvp_args', $plugin_admin, 'twilio_sms_for_woo_paypal_digital_goods_nvp_args', 10, 1);
        $this->loader->add_filter('twilio_sms_for_woo_gateway_paypal_pro_payflow_request', $plugin_admin, 'twilio_sms_for_woo_gateway_paypal_pro_payflow_request', 10, 1);
        $this->loader->add_filter('twilio_sms_for_woo_gateway_paypal_pro_request', $plugin_admin, 'twilio_sms_for_woo_gateway_paypal_pro_request', 10, 1);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Twilio_Sms_For_Woo_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    private function define_constants() {
        if (!defined('TWILIO_SMS_FOR_WOO_LOG_DIR')) {
            define('TWILIO_SMS_FOR_WOO_LOG_DIR', ABSPATH . 'twilio-sms-for-woo-logs/');
        }
    }
}