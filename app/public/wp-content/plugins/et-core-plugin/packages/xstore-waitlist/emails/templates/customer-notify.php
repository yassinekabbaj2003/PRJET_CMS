<?php
/**
 * Customer Product in stock Notify email
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

<hr/>
<div style="margin: 0 -10px">
    <table width='100%' valign='top' border='0' cellpadding='0' cellspacing='0'>
    <tr>
        <td style="padding: 10px; width: 35%">
            <a href='<?php echo esc_url( $product_info['permalink'] ); ?>' target="_blank" style='text-decoration: none; display: inline-block; margin-bottom: 10px;'>
                <?php echo wp_kses_post($product_info['image']); ?>
            </a>
        </td>
        <td style="padding: 10px; width: 65%" valign="middle">
            <div style='text-align: start;font-size: 18px;'>
                <a href='<?php echo esc_url( $product_info['permalink'] ); ?>' target="_blank" class="link">
                    <?php echo wp_kses( $product_info['name'], wp_kses_allowed_html() ) ?>
                </a>
            </div>
            <?php if ( $product_info['sku'] ) : ?>
                <div>
                    <?php echo sprintf(__('%sSKU:%s %s', 'xstore-core'),
                        '<strong>',
                        '</strong>',
                        $product_info['sku']); ?>
                </div>
            <?php endif; ?>
            <div>
                <?php echo wp_kses( $product_info['price'], wp_kses_allowed_html() ) ?>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div style="padding: 15px; text-decoration: none; text-transform: uppercase; text-align: center; background: #222; color: #fff;">
                <a href='<?php echo esc_url( $product_info['permalink'] ); ?>' target="_blank" style='color:#fff; text-decoration:none;'>
                      <?php echo wp_kses( esc_html__('Shop now', 'xstore-core'), wp_kses_allowed_html() ) ?>
                </a>
            </div>
        </td>
    </tr>
</table>
</div>
<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
    echo '<br/><br/><div class="text" style="text-align: start">' . wp_kses_post( wpautop( wptexturize( $additional_content ) ) ) . '</div>';
}

do_action( 'woocommerce_email_footer', $email );
