<?php

namespace XStoreCore\Modules\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( '\WC_Email' ) ) {
    return;
}

/**
 * Class XStore_Waitlist_Customer_Notify
 */
class XStore_Waitlist_Customer_Notify extends \WC_Email {

    /**
     * User email.
     *
     * @var string
     */

    public $introduction;

    public $product_info = [];

    /**
     * Create an instance of the class.
     *
     * @access public
     * @return void
     */
    function __construct() {

        // Email slug we can use to filter other data.
        $this->id          = 'xstore_waitlist_customer_notify';
        $this->title       = __( 'Back in stock notify', 'xstore-core' );
        $this->description = __( 'Exciting update: "{product_name}" is now in stock and ready to be purchased.', 'xstore-core' );
        // For admin area to let the user know we are sending this email to customers.
        $this->customer_email = true;
        $this->heading     = __( 'Rejoice! It\'s back in stock!', 'xstore-core' );
        // translators: placeholder is {blogname}, a variable that will be substituted when email is sent out
        $this->subject     = __( 'Exciting News: Product is Back in Stock!', 'xstore-core' );

        // Template paths.
        $this->template_html  = 'customer-notify.php';
        $this->template_plain = 'plain/customer-notify.php';
        $this->template_base  = XSTORE_WAITLIST_WC_EMAIL_PATH . 'templates/';

        if ( get_theme_mod('xstore_waitlist_product_in_stock_customer_email', true) ) {
            // Action to which we hook onto to send the email.
            add_action('woocommerce_product_set_stock_status_notification', array($this, 'trigger'), 10, 3);
            add_action('woocommerce_variation_set_stock_status_notification', array($this, 'trigger'), 10, 3);
        }

        parent::__construct();
    }

    /**
     * Trigger Function that will send this email to the customer.
     *
     * @access public
     * @return void
     */
    function trigger( $product_id, $stockstatus, $product ) {
        global $wpdb;
        $instance = XStore_Waitlist::get_instance();
        $this->setup_locale();

        if ( 'instock' === $stockstatus ) {
            $table_name = str_replace('-', '_', $instance::$key);
            $this->product_info = array(
                'permalink' => $product->get_permalink(),
                'name' => $product->get_name(),
                'sku' => $product->get_sku(),
                'price' => $product->get_price_html(),
                'image' => $product->get_image()
            );
            // send only for first X amount of subscribers X = $product->get_stock_quantity() count
            if ( 0 < (int) $product->get_stock_quantity() ) {
                $limit = (int) $product->get_stock_quantity();
                $rows    = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT email, product_id FROM ' . $wpdb->prefix . $table_name . ' WHERE product_id = %d AND mail_sent = %d ORDER BY created ASC LIMIT %d, %d', $product_id, 0, 0, $limit ), ARRAY_A ); // db call ok; no-cache ok.
            }
            else {
                $rows    = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT email, product_id FROM ' . $wpdb->prefix . $table_name . ' WHERE product_id = %d AND mail_sent = %d ORDER BY created ASC', $product_id, 0 ), ARRAY_A ); // db call ok; no-cache ok.
            }
            if ( is_array( $rows ) && count( $rows ) ) {
                foreach ($rows as $row) {
                    $this->introduction = str_replace('{product_name}', $instance->create_link_tag($this->product_info['permalink'], $this->product_info['name']), $this->description);
                    $this->recipient  = $row['email'];
                    $this->object = $product;

                    $data    = array(
                        'mail_sent' => 1,
                    );
                    $where   = array(
                        'email'      => $row['email'],
                        'product_id' => $row['product_id'],
                    );

                    $data_format  = array( '%d' );
                    $where_format = array( '%s', '%d' );

                    if ( $this->is_enabled() && $this->get_recipient() ) {
                        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
                        $wpdb->update( $wpdb->prefix . $table_name, $data, $where, $data_format, $where_format ); // db call ok; no-cache ok.
                    }
                }
            }
        }

        $this->restore_locale();
    }

    /**
     * Trigger Function that will send this email to the customer.
     *
     * @access public
     * @return void
     */
    function unique_customer_trigger( $email, $product_id, $product ) {
        global $wpdb;
        $instance = \XStoreCore\Modules\WooCommerce\XStore_Waitlist::get_instance();
        $this->setup_locale();

//        if ( 'instock' === $stockstatus ) {
            $table_name = str_replace('-', '_', $instance::$key);

            $this->product_info = array(
                'permalink' => $product->get_permalink(),
                'name' => $product->get_name(),
                'sku' => $product->get_sku(),
                'price' => $product->get_price_html(),
                'image' => $product->get_image()
            );

            $this->introduction = str_replace('{product_name}', $instance->create_link_tag($this->product_info['permalink'], $this->product_info['name']), $this->description);
            $this->recipient  = $email;
            $this->object = $product;

            if ( $this->is_enabled() && $this->get_recipient() ) {
                $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
            }

//        }

        $this->restore_locale();
    }

    /**
     * Get content html.
     *
     * @access public
     * @return string
     */
    public function get_content_html() {
        return wc_get_template_html(
            $this->template_html,
            array(
                'email_heading'      => $this->get_heading(),
                'product_info' => $this->product_info,
                'introduction' => $this->introduction,
//                'user_id'            => $this->user_id,
//                'user_login'         => $this->user_login,
//                'reset_key'          => $this->reset_key,
                'blogname'           => $this->get_blogname(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin'      => false,
                'plain_text'         => false,
                'email'              => $this,
            ), '', $this->template_base
        );
    }

    /**
     * Get content plain.
     *
     * @return string
     */
    public function get_content_plain() {
        return wc_get_template_html(
            $this->template_plain,
            array(
                'email_heading'      => $this->get_heading(),
                'product_info' => $this->product_info,
                'introduction' => $this->introduction,
//                'user_id'            => $this->user_id,
//                'user_login'         => $this->user_login,
//                'reset_key'          => $this->reset_key,
                'blogname'           => $this->get_blogname(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin'      => false,
                'plain_text'         => true,
                'email'              => $this,
            ), '', $this->template_base
        );
    }

    /**
     * Default content to show below main email content.
     *
     * @since 3.7.0
     * @return string
     */
    public function get_default_additional_content() {
        return __( 'We are reaching out to you because your email address was used to subscribe to stock notifications on our store. <br/><br/> We appreciate your time and thank you for shopping with us.', 'xstore-core' );
    }
}