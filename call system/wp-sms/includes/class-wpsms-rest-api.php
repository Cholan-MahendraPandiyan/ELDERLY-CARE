<?php

namespace WP_SMS;

use WP_SMS\Newsletter;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class RestApi
{
    protected $sms;
    protected $option;
    protected $db;
    protected $tb_prefix;
    protected $namespace;
    protected $options;

    public function __construct()
    {
        global $sms, $wpdb;

        $this->sms       = $sms;
        $this->options   = Option::getOptions();
        $this->db        = $wpdb;
        $this->tb_prefix = $wpdb->prefix;
        $this->namespace = 'wpsms';
    }

    /**
     * Handle Response
     *
     * @param $message
     * @param int $status
     * @param array $data
     * @return \WP_REST_Response
     */
    public static function response($message, $status = 200, $data = [])
    {
        if ($status == 200) {
            $output = array(
                'message' => $message,
                'error'   => array(),
                'data'    => $data
            );
        } else {
            $output = array(
                'error' => array(
                    'code'    => $status,
                    'message' => $message
                ),
            );
        }

        return new \WP_REST_Response($output, $status);
    }

    /**
     * Convert persian/hindi/arabic numbers to english
     *
     * @param $number
     *
     * @return string
     */
    public static function convertNumber($number)
    {
        return strtr($number, array('۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
    }

    /**
     * Subscribe User
     *
     * @param $name
     * @param $mobile
     * @param bool $group
     * @param array $customFields
     * @return array|string
     */
    public static function subscribe($name, $mobile, $group = false, $customFields = array())
    {
        if (empty($name) or empty($mobile)) {
            return new \WP_Error('subscribe', __('Name and Mobile Number are required!', 'wp-sms'));
        }

        // Delete inactive subscribes with this number
        Newsletter::deleteInactiveSubscribersByMobile($mobile);

        $groupIds = is_array($group) ? $group : array($group);

        $gateway_name = Option::getOption('gateway_name');
        if (Option::getOption('newsletter_form_verify') and $gateway_name) {
            // Check gateway setting
            if (!$gateway_name) {
                // Return response
                return new \WP_Error('subscribe', __('Service provider is not available for send activate key to your mobile. Please contact with site.', 'wp-sms'));
            }

            $key = rand(1000, 9999);

            foreach ($groupIds as $groupId) {
                // Add subscribe to database
                $result = Newsletter::addSubscriber($name, $mobile, $groupId, '0', $key, $customFields);
                if ($result['result'] == 'error') {
                    // Return response
                    return new \WP_Error('subscribe', $result['message']);
                }
            }

            wp_sms_send($mobile, sprintf(__('Your activation code: %s', 'wp-sms'), $key));

            // Return response
            return __('To activate your subscription, the activation has been sent to your number.', 'wp-sms');
        } else {
            foreach ($groupIds as $groupId) {
                // Add subscribe to database
                $result = Newsletter::addSubscriber($name, $mobile, $groupId, '1', null, $customFields);
                if ($result['result'] == 'error') {
                    // Return response
                    return new \WP_Error('subscribe', $result['message']);
                }
            }

            // Return response
            return __('Your mobile number has been successfully subscribed.', 'wp-sms');
        }
    }

    /**
     * Unsubscribe user
     *
     * @param $name
     * @param $mobile
     * @param null $group
     *
     * @return array|string
     */
    public static function unSubscribe($name, $mobile, $group = false)
    {
        if (empty($name) or empty($mobile)) {
            return new \WP_Error('unsubscribe', __('Name and Mobile Number are required!', 'wp-sms'));
        }

        // Delete subscriber
        $result = Newsletter::deleteSubscriberByNumber($mobile, $group);

        // Check result
        if ($result['result'] == 'error') {
            // Return response
            return new \WP_Error('unsubscribe', $result['message']);
        }

        return $result['message'];
    }

    /**
     * Verify Subscriber
     *
     * @param $name
     * @param $mobile
     * @param $activation
     * @param $groupId
     * @return array|string
     */
    public static function verifySubscriber($name, $mobile, $activation, $groupId = 0)
    {
        global $wpdb;

        if (empty($name) or empty($mobile) or empty($activation)) {
            return new \WP_Error('unsubscribe', __('The required parameters must be valued!', 'wp-sms'));
        }

        // Check the mobile number is string or integer
        if (strpos($mobile, '+') !== false) {
            $db_prepare = $wpdb->prepare("SELECT * FROM `{$wpdb->prefix}sms_subscribes` WHERE `mobile` = %s AND `status` = %d", $mobile, 0);
        } else {
            $db_prepare = $wpdb->prepare("SELECT * FROM `{$wpdb->prefix}sms_subscribes` WHERE `mobile` = %d AND `status` = %d", $mobile, 0);
        }

        if (is_array($groupId)) {
            $groupId = $groupId[0];
        }

        $updateCondition = array('mobile' => $mobile);
        if ($groupId and $groupId !== 0) {
            $db_prepare                  .= $wpdb->prepare(" AND group_ID = %d", $groupId);
            $updateCondition['group_ID'] = $groupId;
        }

        $check_mobile = $wpdb->get_row($db_prepare);

        if ($check_mobile) {

            if ($activation != $check_mobile->activate_key) {
                // Return response
                return new \WP_Error('verify_subscriber', __('Activation code is wrong!', 'wp-sms'));
            }

            // Check the mobile number is string or integer
            if (strpos($mobile, '+') !== false) {
                $result = $wpdb->update("{$wpdb->prefix}sms_subscribes", array('status' => '1'), $updateCondition, array('%d', '%d'), array('%s'));
            } else {
                $result = $wpdb->update("{$wpdb->prefix}sms_subscribes", array('status' => '1'), $updateCondition, array('%d', '%d'), array('%d'));
            }

            if ($result) {
                do_action('wp_sms_verify_subscriber', $name, $mobile, 1, $check_mobile->ID);

                // Return response
                return __('Your subscription done successfully!', 'wp-sms');
            }
        }

        return new \WP_Error('verify_subscriber', __('Not found the number!', 'wp-sms'));
    }
}

new RestApi();
