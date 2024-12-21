<?php
/**
 * Product quantity select
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-select.php.
 *
 * @xstore-version 9.4.0
 *
 */

defined( 'ABSPATH' ) || exit;

if ( $max_value && $min_value === $max_value ) {
    ?>
    <div class="quantity hidden">
        <input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty"
               name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>"/>
    </div>
    <?php
} else {
    /* translators: %s: Quantity. */
    $label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'xstore' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'xstore' );
    ?>
    <div class="quantity quantity-select">
        <label class="screen-reader-text"
               for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_attr( $label ); ?></label>
        <select name="<?php echo esc_attr( $input_name ); ?>" id="<?php echo esc_attr( $input_id ); ?>" class="qty">
            <?php foreach ( $quantity_values as $option ): ?>
                <option
                    value="<?php echo esc_attr( $option ) ?>" <?php selected( $input_value, $option ); ?>><?php echo esc_html( $option ); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php
}
