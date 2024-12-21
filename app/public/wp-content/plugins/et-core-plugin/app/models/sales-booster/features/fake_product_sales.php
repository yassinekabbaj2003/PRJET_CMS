<?php $global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
    'message',
    esc_html__( 'Message', 'xstore-core' ),
    sprintf( esc_html__( 'Text that will be shown: %s - {fire} will be replaced by emoji; %s - {bag} will be replaced by shopping bag emoji;  %s - {count} will be replaced by calculated count between Min and Max values set below; %s - {timeframe} will be replaced by the timeframe value you set. %s Default text: {fire} {count} items sold in last {timeframe}', 'xstore-core' ), '<br/>', '<br/>', '<br/>', '<br/>', '<br/>' ),
    '{fire} {count} items sold in last {timeframe}' ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'sales_type',
    esc_html__( 'Show Sales Type', 'xstore-core' ),
    false,
    array(
        'fake'   => esc_html__( 'Fake count', 'xstore-core' ),
        'orders' => esc_html__( 'Based on real orders', 'xstore-core' ),
    ),
    'fake' ); ?>

<?php $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'show_on_shop',
    esc_html__( 'Show on Shop/Categories', 'xstore-core' ),
    false,
    false ); ?>

<?php $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'hide_on_outofstock',
    esc_html__( 'Hide for Outofstock products', 'xstore-core' ),
    false,
    false ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'timeframe',
    esc_html__( 'Time Frame', 'xstore-core' ),
    esc_html__( 'Specify custom timeframe value.', 'xstore-core' ),
    1,
    59,
    3,
    1,
    '' ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'timeframe_period',
    esc_html__( 'Time Period', 'xstore-core' ),
    esc_html__( 'Select custom time period', 'xstore-core' ),
    array(
        'minutes' => esc_html__( 'Minutes', 'xstore-core' ),
        'hours'   => esc_html__( 'Hours', 'xstore-core' ),
        'days'    => esc_html__( 'Days', 'xstore-core' ),
        'weeks'   => esc_html__( 'Weeks', 'xstore-core' ),
        'months'  => esc_html__( 'Months', 'xstore-core' ),
    ),
    'hours' ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'min_count',
    esc_html__( 'Min Count', 'xstore-core' ),
    esc_html__( 'Set minimum count of fake sales. In other words: From X sales to y sales.', 'xstore-core' ),
    1,
    30,
    3,
    1,
    'sales',
    array(
        array(
            'name'    => 'sales_type',
            'value'   => 'fake',
            'section' => 'fake_product_sales',
            'default' => 'fake'
        ),
    ) ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'max_count',
    esc_html__( 'Max Count', 'xstore-core' ),
    esc_html__( 'Set maximum count of fake sales. In other words: From x sales to Y sales.', 'xstore-core' ),
    1,
    100,
    12,
    1,
    'sales',
    array(
        array(
            'name'    => 'sales_type',
            'value'   => 'fake',
            'section' => 'fake_product_sales',
            'default' => 'fake'
        ),
    ) ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'shown_after',
    esc_html__( 'Min Sales Count', 'xstore-core' ),
    esc_html__( 'Set minimum count of sales. If sales count of product is less then product sales text will not be shown', 'xstore-core' ),
    0,
    30,
    0,
    1,
    'sales',
    array(
        array(
            'name'    => 'sales_type',
            'value'   => 'orders',
            'section' => 'fake_product_sales',
            'default' => 'fake'
        ),
    ) ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'transient_hours',
    esc_html__( 'Cache Lifespan', 'xstore-core' ),
    esc_html__( 'Specify time after which the product sales cache is cleared.', 'xstore-core' ),
    1,
    72,
    24,
    1,
    'hours' ); ?>