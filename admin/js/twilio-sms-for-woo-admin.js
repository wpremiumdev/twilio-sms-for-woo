jQuery(function ($) {
    $("#twilio_sms_test_sms_button").live("click", function () {
        var twilio_sms_woo_test_phone = $("#twilio_sms_woo_test_mobile_number").val();
        var twilio_sms_woo_test_message = $("#twilio_sms_woo_test_message").val();
        var data = {
            action: 'twilio_sms_send_test_sms',
            security: twilio_sms_test_sms_button_params.twilio_sms_woo_test_sms,
            twilio_sms_woo_test_phone: twilio_sms_woo_test_phone,
            twilio_sms_woo_test_message: twilio_sms_woo_test_message
        };
        $.post(twilio_sms_test_sms_button_params.ajax_url, data, function (response) {
            response = JSON.parse(response);
            if (typeof (response.success) !== 'undefined') {
                if (response.success.length > 0) {
                    alert(response.success);
                } else {
                    alert(response.error);
                }
            } else {
                alert(response.error);
            }
        });
    });
});