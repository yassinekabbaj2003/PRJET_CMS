<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @xstore-version 9.4.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

$wrap_fields_wrapper = apply_filters('etheme_checkout_form_shipping_wrapper', false);
$wrap_additional_information_fields_wrapper = apply_filters('etheme_checkout_form_additional_information_wrapper', false);
$wrap_additional_information_separated = apply_filters('etheme_checkout_form_additional_information_separated', false);

?>
<div class="<?php echo implode(' ', apply_filters('etheme_checkout_form_shipping_wrapper_classes', array('woocommerce-shipping-fields'))); ?>">
	<?php if ( apply_filters('etheme_checkout_form_shipping_address', (WC()->cart->needs_shipping_address() === true)) ) : ?>

        <?php if ( apply_filters('etheme_checkout_form_shipping_title', true) ) { ?>
            <<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?> class="<?php echo apply_filters('etheme_woocommerce_checkout_title_class', 'step-title'); ?>">
                <span><?php echo apply_filters('etheme_woocommerce_checkout_shipping_title', esc_html__( 'Shipping address', 'xstore' )); ?></span>
            </<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?>>
        <?php }
        if ( $wrap_fields_wrapper ) : ?>
            <div class="<?php echo implode(' ', apply_filters('etheme_checkout_form_shipping_fields_wrapper_classes', array('woocommerce-shipping-fields-wrapper'))); ?>">
        <?php endif; ?>

        <div class="shipping_address_wrapper">
            <?php do_action( 'etheme_before_checkout_shipping_checkbox' ); ?>
            <div id="ship-to-different-address">

                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                    <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php esc_html_e( 'Ship to a different address?', 'xstore' ); ?></span>
                </label>
            </div>

            <div class="shipping_address">

                    <?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

                    <div class="woocommerce-shipping-fields__field-wrapper">
                        <?php $fields = $checkout->get_checkout_fields( 'shipping' ); ?>
                        <?php foreach ( $fields as $key => $field ) : ?>
                            <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                        <?php endforeach; ?>
                    </div>

                    <?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

                </div>
        </div>

        <?php if ( $wrap_fields_wrapper ) :
            do_action('etheme_after_checkout_shipping_form_fields_wrapper', $checkout ) ?>
            </div>
        <?php endif; ?>
	<?php endif; ?>

    <?php if ( apply_filters('etheme_checkout_form_additional_information', true) ) : ?>

        <?php if ( $wrap_additional_information_separated ) : ?>
            </div><div class="<?php echo implode(' ', apply_filters('etheme_checkout_form_additional_information_wrapper_classes', array('woocommerce-shipping-additional-information'))); ?>">
        <?php endif; ?>

	    <?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

        <?php if ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) ) : ?>

            <?php if ( apply_filters('etheme_checkout_form_additional_information_title_force_display', false) || ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>
                <?php if ( apply_filters('etheme_checkout_form_additional_information_title', true) ) { ?>
                    <<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?> class="<?php echo apply_filters('etheme_woocommerce_checkout_title_class', 'step-title'); ?>">
                        <span><?php echo apply_filters('etheme_woocommerce_checkout_additional_information_title', esc_html__( 'Additional Information', 'xstore' )); ?></span>
                    </<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?>>
                <?php } ?>
            <?php endif;

            if ( $wrap_additional_information_fields_wrapper ) : ?>
                <div class="<?php echo implode(' ', apply_filters('etheme_checkout_form_additional_information_fields_wrapper_classes', array('woocommerce-additional-fields-wrapper'))); ?>">
            <?php endif; ?>

                <div class="woocommerce-additional-fields__field-wrapper">
                    <?php foreach ( $checkout->get_checkout_fields( 'order' )  as $key => $field ) : ?>
                        <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                    <?php endforeach; ?>
                </div>

            <?php if ( $wrap_additional_information_fields_wrapper ) :
                do_action('etheme_after_checkout_additional_information_form_fields_wrapper', $checkout ) ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>

        <?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>

    <?php if ( $wrap_additional_information_separated ) : ?>
        </div>
    <?php endif; ?>

    <?php endif; ?>

<?php if ( !$wrap_additional_information_separated ) : ?>
</div>
<?php endif; ?>