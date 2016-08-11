<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Twilio_Sms_For_Woo
 * @subpackage Twilio_Sms_For_Woo/includes
 * @author     mbj-webdevelopment <mbjwebdevelopment@gmail.com>
 */
class Twilio_Sms_For_Woo_Activator {

    /**
     * @since    1.0.0
     */
    public static function activate() {
        self::create_files();
    }

    private function create_files() {
        $files = array(
            array(
                'base' => TWILIO_SMS_FOR_WOO_LOG_DIR,
                'file' => '.htaccess',
                'content' => 'deny from all'
            ),
            array(
                'base' => TWILIO_SMS_FOR_WOO_LOG_DIR,
                'file' => 'index.html',
                'content' => ''
            )
        );
        foreach ($files as $file) {
            if (wp_mkdir_p($file['base']) && !file_exists(trailingslashit($file['base']) . $file['file'])) {
                if ($file_handle = @fopen(trailingslashit($file['base']) . $file['file'], 'w')) {
                    fwrite($file_handle, $file['content']);
                    fclose($file_handle);
                }
            }
        }
    }

}