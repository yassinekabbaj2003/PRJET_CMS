<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'text_before',
    esc_html__( 'Text before', 'xstore-core' ),
    esc_html__( 'Write your title. The word inside curly brackets {{word}} will be highlighted', 'xstore-core' ),
    false,
    esc_html__( 'Guaranteed {{safe}} checkout', 'xstore-core' ) );

$global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
    'text_before_highlight_color',
    esc_html__( 'Highlight text color', 'xstore-core' ),
    esc_html__( 'Choose the color of highlighted text.', 'xstore-core' ),
    '#2e7d32' );

$safe_payments_methods = array(
    'visa'  => esc_html__( 'Visa', 'xstore-core' ),
    'master-card'  => esc_html__( 'Master Card', 'xstore-core' ),
    'paypal' => esc_html__( 'PayPal', 'xstore-core' ),
    'american-express' => esc_html__( 'American Express', 'xstore-core' ),
    'maestro' => esc_html__( 'Maestro', 'xstore-core' ),
    'bitcoin' => esc_html__( 'Bitcoin', 'xstore-core' ),
);

$default_payment_items = array();
$default_payment_methods = $safe_payments_methods;

foreach ($default_payment_methods as $safe_payments_method_key => $safe_payments_method_name) {
    $default_payment_items['items_'.array_search($safe_payments_method_key,array_keys($default_payment_methods))] =
        array(
            'callbacks' => array(
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_select_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'payment_method',
                        esc_html__( 'Payment method', 'xstore-core' ),
                        false,
                        array_merge($safe_payments_methods, array(
                            'custom' => esc_html__( 'Custom', 'xstore-core' ),
                        )),
                        $safe_payments_method_key
                    )
                ),
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_upload_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'custom_image',
                        esc_html__( 'Custom Image', 'xstore-core' ),
                        esc_html__( 'Recommended sizes are 90x60', 'xstore-core' ),
//                                                    'image/svg+xml',
                    )
                ),
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_input_text_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'tooltip',
                        esc_html__( 'Tooltip', 'xstore-core' ),
                        false,
                        false,
                        sprintf(esc_html__('Pay safely with %s', 'xstore-core'), $safe_payments_method_name)
                    )
                ),
            )
        );
}

$global_admin_class->xstore_panel_settings_repeater_field(
    $tab_content,
    'items',
    esc_html__( 'Items', 'xstore-core' ),
    false,
    $default_payment_items,
    array(
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_select_field'
            ),
            'args'     => array(
                $tab_content,
                'payment_method',
                esc_html__( 'Payment method', 'xstore-core' ),
                false,
                array_merge($safe_payments_methods, array(
                    'custom' => esc_html__( 'Custom', 'xstore-core' ),
                )),
            )
        ),
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_upload_field'
            ),
            'args'     => array(
                $tab_content,
                'custom_image',
                esc_html__( 'Custom Image', 'xstore-core' ),
                false,
//                                            'image/svg+xml',
            )
        ),
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_input_text_field'
            ),
            'args'     => array(
                $tab_content,
                'tooltip',
                esc_html__( 'Tooltip', 'xstore-core' ),
                false,
                false,
                esc_html__( 'Tooltip text', 'xstore-core' )
            )
        ),
    )
);

$global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'text_after',
    esc_html__( 'Text after', 'xstore-core' ),
    esc_html__( 'Write your title. The word inside curly brackets {{word}} will be highlighted', 'xstore-core' ),
    false,
    esc_html__( 'Your Payment is {{100% Secure}}', 'xstore-core' ) );

$global_admin_class->xstore_panel_settings_input_text_field($tab_content,
    'text_after_url',
    esc_html__('Text after link', 'xstore-core'),
    esc_html__('Write your link. The word inside curly brackets {{word}} set in option above will be wrapped in this link', 'xstore-core'),
    false,
    '');

$global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
    'text_after_highlight_color',
    esc_html__( 'Highlight text color', 'xstore-core' ),
    esc_html__( 'Choose the color of highlighted text.', 'xstore-core' ),
    get_theme_mod( 'dark_styles', false ) ? '#fff' : '#222' );

$global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'tooltips',
    esc_html__('Add tooltips for payments', 'xstore-core'),
    false,
    false );

foreach (
    array('single_product' => esc_html__('Show on Single product', 'xstore-core'),
        'quick_view' => esc_html__('Show in Quick View', 'xstore-core'),
        'cart' => esc_html__('Show on Cart', 'xstore-core'),
        'checkout' => esc_html__('Show on Checkout', 'xstore-core')) as $safe_checkout_pages_key => $safe_checkout_pages_title) {
    $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
        'shown_on_'.$safe_checkout_pages_key,
        $safe_checkout_pages_title,
        false,
        !in_array($safe_checkout_pages_key, array('quick_view')) );
}

?>

<p class="et-message et-info">
    <?php echo __( 'Also you may use next shortcode and put <code>[etheme_sales_booster_safe_checkout]</code> shortcode anywhere you want. It will output all same content which you set up in the settings above.', 'xstore-core' ); ?>
</p>
