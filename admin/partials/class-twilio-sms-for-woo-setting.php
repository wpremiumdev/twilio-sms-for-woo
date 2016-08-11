<?php

/**
 * @class       Twilio_SMS_For_Woo_Setting
 * @since      1.0.0
 * @package    Twilio_Sms_For_Woo
 * @subpackage Twilio_Sms_For_Woo/admin
 * @author     mbj-webdevelopment <mbjwebdevelopment@gmail.com>
 */
class Twilio_SMS_For_Woo_Setting {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {
        add_action('twilio_sms_for_woo_setting', array(__CLASS__, 'twilio_sms_for_woo_setting_field'));
        add_action('twilio_sms_for_woo_setting_save_field', array(__CLASS__, 'twilio_sms_for_woo_setting_save_field'));
        add_action('wp_ajax_twilio_sms_send_test_sms', array(__CLASS__, 'twilio_sms_send_test_sms'));
    }

    public static function twilio_sms_send_test_sms() {
        check_ajax_referer('twilio_sms_woo_test_sms', 'security');
        $log = new Twilio_SMS_For_Woo_Logger();
        require plugin_dir_path(dirname(__FILE__)) . 'partials/lib/Twilio.php';
        $AccountSid = get_option("twilio_sms_woo_account_sid");
        $AuthToken = get_option("twilio_sms_woo_auth_token");
        $from_number = get_option("twilio_sms_woo_from_number");
        $response = '';

        $test_mobile_number = $_POST['twilio_sms_woo_test_phone'];
        $Test_Mobile_Array = '';
        $Test_Mobile_Array = explode(',', $test_mobile_number);

        $test_message = sanitize_text_field($_POST['twilio_sms_woo_test_message']);
        $http = new Services_Twilio_TinyHttp(
                'https://api.twilio.com', array('curlopts' => array(
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 2,
            ))
        );

        if (is_array($Test_Mobile_Array) && count($Test_Mobile_Array) > 0) {

            foreach ($Test_Mobile_Array as $key => $value) {
                try {
                    $client = new Services_Twilio($AccountSid, $AuthToken, "2010-04-01", $http);
                    $message = $client->account->messages->create(array(
                        "From" => $from_number,
                        "To" => trim($value),
                        "Body" => $test_message,
                    ));
                    $response['success'] = "Successfully Sent message {$message->sid}";
                    if ('yes' == get_option('twilio_sms_woo_log_errors')) {
                        $log->add('Twilio SMS', 'TEST SMS Sent message ' . $message->sid);
                    }
                } catch (Exception $e) {
                    $response['error'] = $e->getMessage();
                    if ('yes' == get_option('twilio_sms_woo_log_errors')) {
                        $log->add('Twilio SMS', 'TEST SMS Error message ' . $e->getMessage());
                    }
                }
            }
        }
        echo json_encode($response);
        die();
    }

    public static function twilio_sms_for_woo_general_setting_save_field() {
        $fields[] = array('title' => __('Admin Notifications', 'twilio-sms-for-woo'), 'type' => 'title', 'desc' => '', 'id' => 'admin_notifications_options');
        $fields[] = array(
            'title' => __('Enable new order SMS admin notifications.', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_enable_admin_sms',
            'default' => 'no',
            'type' => 'checkbox'
        );
        $fields[] = array(
            'title' => __('Admin Mobile Number', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_admin_sms_recipients',
            'desc' => __('Enter the mobile number (starting with the country code) where the New Order SMS should be sent. Send to multiple recipients by separating numbers with commas.', 'twilio-sms-for-woo'),
            'default' => '15451225415',
            'type' => 'text'
        );
        $fields[] = array(
            'title' => __('Admin SMS Message', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_admin_sms_template',
            'desc' => __('Use these tags to customize your message: %shop_name%, %order_id%, %order_amount%. Remember that SMS messages are limited to 160 characters.', 'twilio-sms-for-woo'),
            'css' => 'min-width:500px;',
            'default' => __('%shop_name% : You have a new order (%order_id%) for %order_amount%!', 'twilio-sms-for-woo'),
            'type' => 'textarea'
        );
        $fields[] = array('type' => 'sectionend', 'id' => 'general_options');
        $fields[] = array('title' => __('Customer Notifications', 'twilio-sms-for-woo'), 'type' => 'title', 'desc' => '', 'id' => 'customer_notification_options');
        $fields[] = array(
            'title' => __('Send SMS Notifications for these statuses', 'twilio-sms-for-woo'),
            'desc' => __('Pending', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_send_sms_pending',
            'default' => 'yes',
            'type' => 'checkbox',
            'checkboxgroup' => 'start'
        );
        $fields[] = array(
            'desc' => __('On-Hold', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_send_sms_on-hold',
            'default' => 'yes',
            'type' => 'checkbox',
            'checkboxgroup' => '',
            'autoload' => false
        );
        $fields[] = array(
            'desc' => __('Processing', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_send_sms_processing',
            'default' => 'yes',
            'type' => 'checkbox',
            'checkboxgroup' => '',
            'autoload' => false
        );
        $fields[] = array(
            'desc' => __('Completed', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_send_sms_completed',
            'default' => 'yes',
            'type' => 'checkbox',
            'checkboxgroup' => '',
            'autoload' => false
        );
        $fields[] = array(
            'desc' => __('Cancelled', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_send_sms_cancelled',
            'default' => 'yes',
            'type' => 'checkbox',
            'checkboxgroup' => '',
            'autoload' => false
        );
        $fields[] = array(
            'desc' => __('Refunded', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_send_sms_refunded',
            'default' => 'yes',
            'type' => 'checkbox',
            'checkboxgroup' => '',
            'autoload' => false
        );
        $fields[] = array(
            'desc' => __('Failed', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_send_sms_failed',
            'default' => 'yes',
            'type' => 'checkbox',
            'checkboxgroup' => 'end',
            'autoload' => false
        );
        $fields[] = array(
            'title' => __('Default Customer SMS Message', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_default_sms_template',
            'default' => __('%shop_name% : Your order (%order_id%) is now %order_status%.', 'twilio-sms-for-woo'),
            'type' => 'textarea',
            'css' => 'min-width:500px;'
        );
        $fields[] = array(
            'title' => __('Pending SMS Message', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_pending_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
        $fields[] = array(
            'title' => __('On-Hold SMS Message', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_on-hold_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
        $fields[] = array(
            'title' => __('Processing SMS Message', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_processing_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
        $fields[] = array(
            'title' => __('Completed SMS Message', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_completed_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
        $fields[] = array(
            'title' => __('Cancelled SMS Message', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_cancelled_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
        $fields[] = array(
            'title' => __('Refunded SMS Message', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_refunded_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
        $fields[] = array(
            'title' => __('Failed SMS Message', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_failed_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
        $fields[] = array('type' => 'sectionend', 'id' => 'general_options');
        $fields[] = array('title' => __('Twilio Settings', 'twilio-sms-for-woo'), 'type' => 'title', 'desc' => '', 'id' => 'twilio_settings_options');
        $fields[] = array(
            'title' => __('Account SID', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_account_sid',
            'desc' => __('Log into your Twilio Account to find your Account SID.', 'twilio-sms-for-woo'),
            'type' => 'text',
            'css' => 'min-width:300px;',
        );
        $fields[] = array(
            'title' => __('Auth Token', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_auth_token',
            'desc' => __('Log into your Twilio Account to find your Auth Token.', 'twilio-sms-for-woo'),
            'type' => 'text',
            'css' => 'min-width:300px;',
        );
        $fields[] = array(
            'title' => __('From Number', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_from_number',
            'desc' => __('Enter the number to send SMS messages from. This must be a purchased number from Twilio.', 'twilio-sms-for-woo'),
            'type' => 'text',
            'css' => 'min-width:300px;',
        );
        $fields[] = array(
            'desc' => __('Enable this to log Twilio API errors to the log. Use this if you are having issues sending SMS.', 'twilio-sms-for-woo'),
            'title' => __('Log Errors', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_log_errors',
            'default' => 'no',
            'type' => 'checkbox'
        );
        $fields[] = array('type' => 'sectionend', 'id' => 'general_options');
        $fields[] = array('title' => __('Send Test SMS', 'twilio-sms-for-woo'), 'type' => 'title', 'desc' => '', 'id' => 'send_test_sms_options');
        $fields[] = array(
            'title' => __('Mobile Number', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_test_mobile_number',
            'name' => 'twilio_sms_woo_test_mobile_number',
            'desc' => __('Enter the mobile number (starting with the country code) where the test SMS should be send. Note that if you are using a trial Twilio account, this number must be verified first.', 'twilio-sms-for-woo'),
            'type' => 'text',
            'css' => 'min-width:300px;'
        );
        $fields[] = array(
            'title' => __('Message', 'twilio-sms-for-woo'),
            'id' => 'twilio_sms_woo_test_message',
            'name' => 'twilio_sms_woo_test_message',
            'desc' => __('Remember that SMS messages are limited to 160 characters.', 'twilio-sms-for-woo'),
            'type' => 'textarea',
            'css' => 'min-width: 500px;'
        );
        $fields[] = array('type' => 'sectionend', 'id' => 'general_options');
        return $fields;
    }

    public static function twilio_sms_for_woo_setting_field() {
        $sms_setting_fields = self::twilio_sms_for_woo_general_setting_save_field();
        $Html_output = new Twilio_SMS_For_Woo_Html_output();
        ?>
        <form id="twilio_sms_form" enctype="multipart/form-data" action="" method="post">
            <?php $Html_output->init($sms_setting_fields); ?>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc"></th>
                        <td class="forminp">
                            <input type="button" class="button" id="twilio_sms_test_sms_button" name="twilio_sms_test_sms_button" value="<?php esc_attr_e('Send', 'Option'); ?>"/>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" name="twilio_sms" class="button-primary" value="<?php esc_attr_e('Save changes', 'Option'); ?>" />
            </p>
        </form>
        <?php
    }

    public static function twilio_sms_for_woo_setting_save_field() {
        $twilio_sms_setting_fields = self::twilio_sms_for_woo_general_setting_save_field();
        $Html_output = new Twilio_SMS_For_Woo_Html_output();
        $Html_output->save_fields($twilio_sms_setting_fields);
    }

}

Twilio_SMS_For_Woo_Setting::init();