<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'text_before',
    esc_html__( 'Text before', 'xstore-core' ),
    esc_html__( 'Write title for estimated delivery output', 'xstore-core' ),
    false,
    esc_html__( 'Estimated delivery:', 'xstore-core' ) ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'date_type',
    esc_html__( 'Date type', 'xstore-core' ),
    false,
    array(
        'range' => esc_html__( 'Days Range', 'xstore-core' ),
        'days'  => esc_html__( 'Days', 'xstore-core' ),
    ),
    'days' ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'min_days',
    esc_html__( 'Min days', 'xstore-core' ),
    esc_html__( 'Set minimum count of days. In other words: From X days to y days.', 'xstore-core' ),
    1,
    100,
    3,
    1,
    'days',
    array(
        array(
            'name'    => 'date_type',
            'value'   => 'range',
            'section' => $tab_content,
            'default' => 'days'
        ),
    ) ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'max_days',
    esc_html__( 'Max days', 'xstore-core' ),
    esc_html__( 'Set max count of days. In other words: From x days to Y days.', 'xstore-core' ),
    1,
    100,
    5,
    1,
    'days',
    array(
        array(
            'name'    => 'date_type',
            'value'   => 'range',
            'section' => $tab_content,
            'default' => 'days'
        ),
    ) ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'days',
    esc_html__( 'Days', 'xstore-core' ),
    esc_html__( 'Set count of days.', 'xstore-core' ),
    1,
    100,
    3,
    1,
    'days',
    array(
        array(
            'name'    => 'date_type',
            'value'   => 'days',
            'section' => $tab_content,
            'default' => 'days'
        ),
    ) ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'days_type',
    esc_html__( 'Days type', 'xstore-core' ),
    false,
    array(
        'number' => esc_html__( 'Number of days', 'xstore-core' ),
        'date'   => esc_html__( 'Exact date', 'xstore-core' ),
    ),
    'number' ); ?>

<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'date_format',
    esc_html__( 'Date format', 'xstore-core' ),
    __( 'More examples of formats types <a href="https://www.php.net/manual/en/datetime.format.php" target="_blank">on this page</a>. Default format is inherited from your  <a href="' . admin_url( 'options-general.php' ) . '" target="_blank">WordPress settings</a>', 'xstore-core' ),
    sprintf(
    /* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
        __( 'Use the letters: %s', 'xstore-core' ),
        'l D d j S F m M n Y y'
    ),
    get_option( 'date_format' ),
    array(
        array(
            'name'    => 'days_type',
            'value'   => 'date',
            'section' => $tab_content,
            'default' => 'date'
        ),
    ) ); ?>

<?php $global_admin_class->xstore_panel_settings_multicheckbox_field( $tab_content,
    'non_working_days',
    esc_html__( 'Not working days', 'xstore-core' ),
    esc_html__( 'Exclude certain non-working days from date estimation count.', 'xstore-core' ),
    array(
        'day_off_monday'    => esc_html__( 'Monday', 'xstore-core' ),
        'day_off_tuesday'   => esc_html__( 'Tuesday', 'xstore-core' ),
        'day_off_wednesday' => esc_html__( 'Wednesday', 'xstore-core' ),
        'day_off_thursday'  => esc_html__( 'Thursday', 'xstore-core' ),
        'day_off_friday'    => esc_html__( 'Friday', 'xstore-core' ),
        'day_off_saturday'  => esc_html__( 'Saturday', 'xstore-core' ),
        'day_off_sunday'    => esc_html__( 'Sunday', 'xstore-core' ),
    ),
    array(
        'day_off_saturday',
        'day_off_sunday',
    )
); ?>

<?php
$estimated_delivery_only_for          = function_exists( 'wc_get_product_stock_status_options' ) ? wc_get_product_stock_status_options() : array(
    'instock'     => esc_html__( 'In Stock', 'xstore-core' ),
    'outofstock'  => esc_html__( 'Out of stock', 'xstore-core' ),
    'onbackorder' => esc_html__( 'Available on backorder', 'xstore-core' ),
);
$estimated_delivery_only_for_rendered = array();
foreach ( $estimated_delivery_only_for as $key => $value ) {
    $estimated_delivery_only_for_rendered[ 'only_for_' . $key ] = $value;
}
$global_admin_class->xstore_panel_settings_multicheckbox_field( $tab_content,
    'only_for',
    esc_html__( 'Show only for', 'xstore-core' ),
    esc_html__( 'Select product statuses if you need to show only for specific ones or deselect all to show on all products.', 'xstore-core' ),
    $estimated_delivery_only_for_rendered,
    array()
); ?>
<!--                            -->

<?php
    $shipping_classes = WC()->shipping()->get_shipping_classes();
    $shipping_classes_rendered = array();
    if ( count($shipping_classes) ) {
        $shipping_classes_url = admin_url('admin.php?page=wc-settings&tab=shipping&section=classes');
        foreach ($shipping_classes as $shipping_class) {
            // save by term_id because WC make is the same way
            $shipping_classes_rendered['shipping_class_id_'.$shipping_class->term_id] = '<a href="'.$shipping_classes_url.'" target="_blank" style="color: currentColor">'.$shipping_class->name.'</a>';
        }
    }
?>

<?php foreach (array_merge($estimated_delivery_only_for, $shipping_classes_rendered) as $key => $value) :
    $is_shipping_class = in_array($key, array_keys($shipping_classes_rendered));
    $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
        'separated_values_for_'.$key,
        $is_shipping_class ? sprintf(__( 'Custom values for "%s" [Shipping class]', 'xstore-core' ), $value) :
            sprintf(__( 'Custom values for "%s" status', 'xstore-core' ), $value),
        false ); ?>

    <?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
        'min_days_'.$key,
        esc_html__( 'Min days', 'xstore-core' ),
        esc_html__( 'Set minimum count of days. In other words: From X days to y days.', 'xstore-core' ),
        1,
        100,
        3,
        1,
        'days',
        array(
            array(
                'name'    => 'date_type',
                'value'   => 'range',
                'section' => $tab_content,
                'default' => 'days'
            ),
            array(
                'name'    => 'separated_values_for_'.$key,
                'value'   => 'on',
                'section' => $tab_content,
                'default' => false
            ),
        ) ); ?>

    <?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
        'max_days_'.$key,
        esc_html__( 'Max days', 'xstore-core' ),
        esc_html__( 'Set max count of days. In other words: From x days to Y days.', 'xstore-core' ),
        1,
        100,
        5,
        1,
        'days',
        array(
            array(
                'name'    => 'date_type',
                'value'   => 'range',
                'section' => $tab_content,
                'default' => 'days'
            ),
            array(
                'name'    => 'separated_values_for_'.$key,
                'value'   => 'on',
                'section' => $tab_content,
                'default' => false
            ),
        ) ); ?>

    <?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
        'days_'.$key,
        esc_html__( 'Days', 'xstore-core' ),
        esc_html__( 'Set count of days.', 'xstore-core' ),
        1,
        100,
        3,
        1,
        'days',
        array(
            array(
                'name'    => 'date_type',
                'value'   => 'days',
                'section' => $tab_content,
                'default' => 'days'
            ),
            array(
                'name'    => 'separated_values_for_'.$key,
                'value'   => 'on',
                'section' => $tab_content,
                'default' => false
            ),
        ) ); ?>

<?php endforeach; ?>

<!--                            -->

<?php $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'locale',
    esc_html__( 'Use Locale', 'xstore-core' ),
    false ); ?>

<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'locale_format',
    esc_html__( 'Locale format', 'xstore-core' ),
    __( 'More examples of formats types <a href="https://www.php.net/manual/en/function.strftime.php" target="_blank">on this page</a>.', 'xstore-core' ),
    esc_html__( 'eg: %A, %b %d', 'xstore-core' ),
    '%A, %b %d',
    array(
        array(
            'name'    => 'locale',
            'value'   => 'on',
            'section' => $tab_content,
            'default' => false
        ),
    ) ); ?>

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
