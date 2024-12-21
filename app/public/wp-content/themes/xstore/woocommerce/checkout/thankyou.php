<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 * @xstore-version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( get_query_var('et_is-cart-checkout-advanced', false ) ) {
	wc_get_template(
		'checkout/thankyou-advanced.php',
		array( 'order' => $order )
	);
	return;
}

?>

<div class="woocommerce-order">
	
	<?php
	if ( $order ) :
		
		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>
		
		<?php if ( $order->has_status( 'failed' ) ) : ?>

        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'xstore' ); ?></p>

        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
            <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'xstore' ); ?></a>
			<?php if ( get_query_var( 'et_is-loggedin', false) ) : ?>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'xstore' ); ?></a>
			<?php endif; ?>
        </p>
	
	<?php else : ?>

        <?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>

        <div class="woocommerce-order-overview-wrapper">
            
            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
    
                <li class="woocommerce-order-overview__order order">
                    <?php esc_html_e( 'Order number:', 'xstore' ); ?>
                    <?php echo '<strong>'. $order->get_order_number() . '</strong>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </li>
    
                <li class="woocommerce-order-overview__date date">
                    <?php esc_html_e( 'Date:', 'xstore' ); ?>
                    <?php echo '<strong>' . wc_format_datetime( $order->get_date_created() ) . '</strong>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </li>
                
                <?php if ( get_query_var( 'et_is-loggedin', false) && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                    <li class="woocommerce-order-overview__email email">
                        <?php esc_html_e( 'Email:', 'xstore' ); ?>
                        <?php echo '<strong>' . $order->get_billing_email() . '</strong>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </li>
                <?php endif; ?>
    
                <li class="woocommerce-order-overview__total total">
                    <?php esc_html_e( 'Total:', 'xstore' ); ?>
                    <?php echo '<strong>' . $order->get_formatted_order_total() . '</strong>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </li>
                
                <?php if ( $order->get_payment_method_title() ) : ?>
                    <li class="woocommerce-order-overview__payment-method method">
                        <?php esc_html_e( 'Payment method:', 'xstore' ); ?>
                        <?php echo '<strong>' . wp_kses_post( $order->get_payment_method_title() ) . '</strong>'; ?>
                    </li>
                <?php endif; ?>
    
            </ul>
            
        </div>
	
	<?php endif; ?>
		
		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
	
	<?php else : ?>

        <?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>
	
	<?php endif; ?>

</div>