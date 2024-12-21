<?php
/**
 * Admin waitlist request email
 *
 * @package XStoreCore\Modules\WooCommerce
 * @version 1.0.0
 * @since 5.1.9
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p class="text" style="text-align: start;"><?php echo $introduction; ?></p>

<br/>

    <table width='100%' valign='top' border='0' cellpadding='0' cellspacing='0'>
        <tr>
            <td style="padding: 0; text-align: start">
                <?php echo sprintf(__('%sCustomer email:%s %s', 'xstore-core'),
                    '<strong>',
                    '</strong>',
                    $customer_email_address); ?>
            </td>
        </tr>
        <tr>
            <td style="padding: 0; text-align: start;">
                <?php echo sprintf(__('%sProduct name:%s %s', 'xstore-core'),
                    '<strong>',
                    '</strong>',
                '<a href="'. esc_url( $product_info['permalink'] ).'" target="_blank" class="link">'.
                    wp_kses( $product_info['name'], wp_kses_allowed_html() ).
                '</a>'); ?>
            </td>
        </tr>
        <?php if ( $product_info['sku'] ) : ?>
        <tr>
            <td style="padding: 0; text-align: start">
                <?php echo sprintf(__('%sSKU:%s %s', 'xstore-core'),
                    '<strong>',
                    '</strong>',
                    $product_info['sku']); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td style="padding: 0; text-align: start">
                <?php echo sprintf(__('%sProduct link:%s %s', 'xstore-core'),
                    '<strong>',
                    '</strong>',
                    '<a href="'. esc_url( $product_info['permalink'] ).'" target="_blank" class="link">'.
                    $product_info['permalink'].
                    '</a>'); ?>
            </td>
        </tr>
    </table>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
    echo '<br/><br/><div class="text" style="text-align: start">' . wp_kses_post( wpautop( wptexturize( $additional_content ) ) ) . '</div>';
}

do_action( 'woocommerce_email_footer', $email );
