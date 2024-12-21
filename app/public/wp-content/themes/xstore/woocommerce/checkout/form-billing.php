<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
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

$wrap_fields_wrapper = apply_filters('etheme_checkout_form_billing_wrapper', false);
$wrap_account_fields_wrapper = apply_filters('etheme_checkout_form_account_wrapper', false);

?>
<div class="<?php echo implode(' ', apply_filters('etheme_checkout_form_billing_wrapper_classes', array('woocommerce-billing-fields'))); ?>">
<?php
if ( apply_filters('etheme_checkout_form_billing_title', true) ) {
    if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

        <<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?> class="<?php echo apply_filters('etheme_woocommerce_checkout_title_class', 'step-title'); ?>">
        <span><?php echo apply_filters('etheme_woocommerce_checkout_billing_title', esc_html__( 'Billing &amp; Shipping', 'xstore' )); ?></span>
        </<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?>>

    <?php else : ?>

        <<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?> class="<?php echo apply_filters('etheme_woocommerce_checkout_title_class', 'step-title'); ?>">
        <span><?php echo apply_filters('etheme_woocommerce_checkout_billing_title', esc_html__( 'Billing Details', 'xstore' )); ?></span>
        </<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?>>

    <?php endif;
}
if ( $wrap_fields_wrapper ) : ?>
    <div class="<?php echo implode(' ', apply_filters('etheme_checkout_form_billing_fields_wrapper_classes', array('woocommerce-billing-fields-wrapper'))); ?>">
<?php endif;
do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

    <div class="woocommerce-billing-fields__field-wrapper">

        <?php $fields = $checkout->get_checkout_fields( 'billing' ); ?>
        <?php foreach ( $fields as $key => $field ) :

            woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );

        endforeach; ?>

    </div>

<?php do_action('woocommerce_after_checkout_billing_form', $checkout );

if ( $wrap_fields_wrapper ) :
    do_action('etheme_after_checkout_billing_form_fields_wrapper', $checkout ) ?>
    </div>
<?php endif; ?>

    </div>

<?php if ( ! get_query_var( 'et_is-loggedin', false) && apply_filters('etheme_checkout_form_account_registration', $checkout->is_registration_enabled()) ) : ?>

<div class="<?php echo implode(' ', apply_filters('etheme_checkout_form_account_wrapper_classes', array('woocommerce-account-fields'))); ?>">

    <?php if ( apply_filters('etheme_checkout_form_new_account_title', false) ) { ?>
        <<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?> class="<?php echo apply_filters('etheme_woocommerce_checkout_title_class', 'step-title'); ?>">
        <span><?php echo apply_filters('etheme_woocommerce_checkout_new_account_title', esc_html__( 'New Customer', 'xstore' )); ?></span>
        </<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?>>
    <?php }

    if ( $wrap_account_fields_wrapper ) : ?>
        <div class="<?php echo implode(' ', apply_filters('etheme_checkout_form_account_fields_wrapper_classes', array('woocommerce-account-fields-wrapper'))); ?>">
    <?php endif; ?>

    <?php do_action( 'etheme_before_checkout_createaccount_checkbox' ); ?>

    <?php if ( ! $checkout->is_registration_required() ) : ?>

        <p class="form-row form-row-wide create-account">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ) ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'xstore' ); ?></span>
            </label>
        </p>

    <?php endif; ?>

    <?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

    <?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

        <div class="create-account">
            <?php foreach ( $checkout->get_checkout_fields( 'account' )  as $key => $field ) : ?>
                <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
            <?php endforeach; ?>
            <div class="clear"></div>
        </div>

    <?php endif; ?>

    <?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>

    <?php if ( $wrap_account_fields_wrapper ) :
        do_action('etheme_after_checkout_account_form_fields_wrapper', $checkout ) ?>
        </div>
    <?php endif; ?>

    </div>
<?php endif; ?>