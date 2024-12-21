<?php
$currency_symbol = get_woocommerce_currency_symbol( get_woocommerce_currency() );

if ( 'yes' !== get_option( 'woocommerce_enable_ajax_add_to_cart', 'yes' ) ) {
    ?>
    <p class="et-message et-warning">
        <?php
            echo sprintf(esc_html__('To properly use this feature, please activate %s option in your WooCommerce settings.', 'xstore-core'), '<a href="'.admin_url('admin.php?page=wc-settings&tab=products').'" target="_blank" rel="nofollow">'. esc_html__('Enable AJAX add to cart buttons on archives', 'xstore-core') . '</a>')
        ?></p>
    <?php
}

$global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'type',
    esc_html__( 'Discount Type', 'xstore-core' ),
    false,
    array(
        'percentage' => sprintf(__('By percentage (example: 10%1s OFF)', 'xstore-core'), '%'),
        'fixed' => sprintf(__('By fixed price (example: 10%1s OFF)', 'xstore-core'), $currency_symbol),
    ),
    'percentage' ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'rules',
    esc_html__( 'Discount Rules', 'xstore-core' ),
    false,
    array(
        'intervals' => __('Intervals', 'xstore-core'),
        'steps' => __('Steps', 'xstore-core'),
    ),
    'intervals' ); ?>

<?php $global_admin_class->xstore_panel_settings_repeater_field(
    $tab_content,
    'intervals',
    esc_html__( 'Intervals', 'xstore-core' ),
    false,
    array(
        'intervals_1' => array(
            'callbacks' => array(
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_input_number_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'min',
                        esc_html__( 'Min quantity', 'xstore-core' ),
                        false,
                        0,
                        '',
                    )
                ),
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_input_number_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'max',
                        esc_html__( 'Max quantity', 'xstore-core' ),
                        false,
                        0,
                        '',
                    )
                ),
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_input_number_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'percentage',
                        sprintf(esc_html__( 'By percentage (example: 10%1s OFF) / By fixed price (example: 10%1s OFF)', 'xstore-core' ), '%', $currency_symbol),
                        false,
                        0,
                    )
                ),
            )
        ),
    ),
    array(
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_input_number_field'
            ),
            'args'     => array(
                $tab_content,
                'min',
                esc_html__( 'Min quantity', 'xstore-core' ),
                false,
                0,
                '',
            )
        ),
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_input_number_field'
            ),
            'args'     => array(
                $tab_content,
                'max',
                esc_html__( 'Max quantity', 'xstore-core' ),
                false,
                0,
                '',
            )
        ),
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_input_number_field'
            ),
            'args'     => array(
                $tab_content,
                'percentage',
                sprintf(esc_html__( 'By percentage (example: 10%1s OFF) / By fixed price (example: 10%1s OFF)', 'xstore-core' ), '%', $currency_symbol),
                false,
                0,
            )
        ),
    ),
    array(
        array(
            'name'    => 'rules',
            'value'   => 'intervals',
            'section' => $tab_content,
            'default' => 'intervals'
        ),
    ),
    esc_html__('Interval', 'xstore-core')
);
?>

<?php $global_admin_class->xstore_panel_settings_repeater_field(
    $tab_content,
    'steps',
    esc_html__( 'Steps', 'xstore-core' ),
    false,
    array(
        'steps_1' => array(
            'callbacks' => array(
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_input_number_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'every',
                        esc_html__( 'Every X items', 'xstore-core' ),
                        false,
                        0,
                        '',
                    )
                ),
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_input_number_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'percentage',
                        sprintf(esc_html__( 'By percentage (example: 10%1s OFF) / By fixed price (example: 10%1s OFF)', 'xstore-core' ), '%', $currency_symbol),
                        false,
                        0,
                    )
                ),
            )
        ),
    ),
    array(
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_input_number_field'
            ),
            'args'     => array(
                $tab_content,
                'every',
                esc_html__( 'Every X items', 'xstore-core' ),
                false,
                0,
                '',
            )
        ),
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_input_number_field'
            ),
            'args'     => array(
                $tab_content,
                'percentage',
                sprintf(esc_html__( 'By percentage (example: 10%1s OFF) / By fixed price (example: 10%1s OFF)', 'xstore-core' ), '%', $currency_symbol),
                false,
                0,
            )
        ),
    ),
    array(
        array(
            'name'    => 'rules',
            'value'   => 'steps',
            'section' => $tab_content,
            'default' => 'intervals'
        ),
    ),
    esc_html__('Step', 'xstore-core')
);
?>

<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'title',
    esc_html__( 'Title', 'xstore-core' ),
    false,
    false,
    esc_html__('Buy more save more!', 'xstore-core') ); ?>

<?php $global_admin_class->xstore_panel_settings_icons_select( $tab_content,
    'button_icon',
    esc_html__( 'Button icon', 'xstore-core' ),
    false,
    $global_admin_class->xstore_panel_icons_list(),
    'et_icon-shopping-bag' ); ?>

<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'button_text',
    esc_html__( 'Button text', 'xstore-core' ),
    false,
    false,
    esc_html__('Add', 'xstore-core') ); ?>

<?php $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'add_quantity',
    esc_html__('Add quantity inputs', 'xstore-core'),
    false,
    false,
    array(
        array(
            'name'    => 'rules',
            'value'   => 'intervals',
            'section' => $tab_content,
            'default' => 'intervals'
        ),
    ));
?>

<?php $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'shown_on_quick_view',
    esc_html__('Show in Quick View', 'xstore-core'),
    false,
    false);
?>

<?php
$single_product_builder = ! ! get_option( 'etheme_single_product_builder', false );
$global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'position',
    esc_html__( 'Position', 'xstore-core' ),
    sprintf(__( 'Select position of estimated delivery or choose Use shortcode choice and put %s shortcode anywhere you want.', 'xstore-core' ), '<code>[etheme_sales_booster_'.$tab_content.']</code>'),
    $single_product_builder ?
        array(
            'before_atc'               => esc_html__( 'Before Add to cart button', 'xstore-core' ),
            'after_atc'                => esc_html__( 'After Add to cart button', 'xstore-core' ),
            'before_cart_form'         => esc_html__( 'Before Cart form', 'xstore-core' ),
            'after_cart_form'          => esc_html__( 'After Cart form', 'xstore-core' ),
            'before_excerpt'           => esc_html__( 'Before product excerpt', 'xstore-core' ),
            'after_excerpt'            => esc_html__( 'After product excerpt', 'xstore-core' ),
            'before_product_meta'      => esc_html__( 'Before product meta', 'xstore-core' ),
            'after_product_meta'       => esc_html__( 'After product meta', 'xstore-core' ),
            'before_woocommerce_share' => esc_html__( 'Before share', 'xstore-core' ),
            'after_woocommerce_share'  => esc_html__( 'After share', 'xstore-core' ),
            'shortcode'                => esc_html__( 'Use shortcode', 'xstore-core' )
        ) : array(
        'before_cart_form'         => esc_html__( 'Before Cart form', 'xstore-core' ),
        'after_cart_form'          => esc_html__( 'After Cart form', 'xstore-core' ),
        'before_product_meta'      => esc_html__( 'Before product meta', 'xstore-core' ),
        'after_product_meta'       => esc_html__( 'After product meta', 'xstore-core' ),
        'before_woocommerce_share' => esc_html__( 'Before share', 'xstore-core' ),
        'after_woocommerce_share'  => esc_html__( 'After share', 'xstore-core' ),
        'shortcode'                => esc_html__( 'Use shortcode', 'xstore-core' )
    ),
    $single_product_builder ? 'after_excerpt' : 'after_product_meta' ); ?>
