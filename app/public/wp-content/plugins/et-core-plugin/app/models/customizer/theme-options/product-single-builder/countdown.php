<?php
/**
 * The template created for displaying single product layout options
 *
 * @version 0.0.2
 * @since   6.0.0
 * @log     0.0.2
 * ADDED: buy_now_btn
 * ADDED: show single stock
 */

add_filter('et/customizer/add/sections', function ($sections) {

    $args = array(
        'single-product-page-countdown' => array(
            'name' => 'single-product-page-countdown',
            'title' => esc_html__('Countdown', 'xstore-core'),
            'panel' => 'single_product_builder',
            'icon' => 'dashicons-hourglass',
            'type' => 'kirki-lazy',
            'dependency' => array()
        )
    );

    return array_merge($sections, $args);
});

add_filter('et/customizer/add/fields/single-product-page-countdown', function ($fields) use ($brand_label) {
    $args = array();

    // Array of fields
    $args = array(

        'single_countdown_type' => array(
            'name' => 'single_countdown_type',
            'type'            => 'radio-image',
            'settings' => 'single_countdown_type',
            'label' => esc_html__('Type', 'xstore-core'),
            'tooltip' => esc_html__('Choose the design type of the countdown timer to be displayed on the single product page.', 'xstore-core') . '<br/>' .
                sprintf(esc_html__('Note: the countdown timer will be shown on the single product page if the product has a sale price and the "Sale Countdown" option in the "%1s Options" section is configured correctly.', 'xstore-core'), $brand_label) . '<br/>' .
                sprintf(esc_html__('Tip: The "%1s Options" can be found by going to Dashboard -> Products -> Edit Product and scrolling down.', 'xstore-core'), $brand_label),
            'section' => 'single-product-page-countdown',
            'default' => 'type2',
            'choices' => array(
                'type1' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/countdown/countdown-1.svg',
                'type2' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/countdown/countdown-2.svg',
                'type3' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/countdown/countdown-3.svg',
            ),
        ),

        'single_countdown_title' => array(
            'name' => 'single_countdown_title',
            'type' => 'etheme-text',
            'settings' => 'single_countdown_title',
            'label' => esc_html__('Title text', 'xstore-core'),
            'tooltip' => esc_html__('Customize the title text displayed before the countdown timer.', 'xstore-core'),
            'section' => 'single-product-page-countdown',
            'default' => esc_html__('{fire} Hurry up! Sale ends in:', 'xstore-core'),
            'active_callback' => array(
                array(
                    'setting' => 'single_countdown_type',
                    'operator' => '==',
                    'value' => 'type3',
                ),
            ),
        ),

    );

    return array_merge($fields, $args);

} );