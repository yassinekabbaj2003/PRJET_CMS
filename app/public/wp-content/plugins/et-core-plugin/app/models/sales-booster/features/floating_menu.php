<?php
$global_admin_class->xstore_panel_settings_repeater_field(
    $tab_content,
    'items',
    esc_html__( 'Items', 'xstore-core' ),
    false,
    array(
        'items_1' => array(
            'callbacks' => array(
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_upload_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'svg_icon',
                        esc_html__( 'SVG Icon', 'xstore-core' ),
                        false,
                        'image/svg+xml',
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
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_input_text_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'link',
                        esc_html__( 'Link', 'xstore-core' ),
                        false,
                        false,
                        '#'
                    )
                ),
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_switcher_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'target_blank',
                        esc_html__( 'Open In New Window', 'xstore-core' ),
                    )
                ),
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_switcher_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'active_dot',
                        esc_html__( 'Enable Dot', 'xstore-core' ),
                        esc_html__( 'Enable pulsing dot for this item', 'xstore-core' )
                    )
                ),
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_switcher_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'is_active',
                        esc_html__( 'Make Item Active', 'xstore-core' ),
                    )
                ),
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_switcher_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'mobile_hidden',
                        esc_html__( 'Hide on Mobile', 'xstore-core' ),
                    )
                ),
            )
        ),
    ),
    array(
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_upload_field'
            ),
            'args'     => array(
                $tab_content,
                'svg_icon',
                esc_html__( 'SVG Icon', 'xstore-core' ),
                false,
                'image/svg+xml',
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
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_input_text_field'
            ),
            'args'     => array(
                $tab_content,
                'link',
                esc_html__( 'Link', 'xstore-core' )
            )
        ),
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_switcher_field'
            ),
            'args'     => array(
                $tab_content,
                'target_blank',
                esc_html__( 'Open In New Window', 'xstore-core' ),
            )
        ),
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_switcher_field'
            ),
            'args'     => array(
                $tab_content,
                'active_dot',
                esc_html__( 'Enable Dot', 'xstore-core' ),
                esc_html__( 'Enable pulsing dot for this item', 'xstore-core' )
            )
        ),
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_switcher_field'
            ),
            'args'     => array(
                $tab_content,
                'is_active',
                esc_html__( 'Make Item Active', 'xstore-core' ),
            )
        ),
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_switcher_field'
            ),
            'args'     => array(
                $tab_content,
                'mobile_hidden',
                esc_html__( 'Hide on Mobile', 'xstore-core' ),
            )
        ),
    )
);

$global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'position',
    esc_html__( 'Position', 'xstore-core' ),
    esc_html__( 'Set default position of floating menu. Auto will make right position for ltr and left for rtl', 'xstore-core' ),
    array(
        'auto'  => esc_html__( 'Auto', 'xstore-core' ),
        'left'  => esc_html__( 'Left', 'xstore-core' ),
        'right' => esc_html__( 'Right', 'xstore-core' ),
    ),
    'auto' );

//                                $global_admin_class->xstore_panel_settings_select_field( $tab_content,
//                                    'tooltip_color_scheme',
//                                    esc_html__( 'Tooltip Color Scheme', 'xstore-core' ),
//	                                esc_html__('Set colorscheme for items tooltips. Set auto to inherit from theme styles.', 'xstore-core'),
//                                    array(
//	                                    'auto' => esc_html__( 'Auto', 'xstore-core' ),
//                                        'dark'   => esc_html__( 'Dark', 'xstore-core' ),
//                                        'light' => esc_html__( 'Light', 'xstore-core' ),
//                                    ),
//                                    'dark' );


$global_admin_class->xstore_panel_settings_tab_field_start(
    esc_html__( 'Global Style Settings', 'xstore-core' )
);

$global_admin_class->xstore_panel_settings_slider_field(
    $tab_content,
    'content_zoom',
    esc_html__( 'Content zoom (%)', 'xstore-core' ),
    false,
    50,
    300,
    100,
    1,
    '%'
);

$global_admin_class->xstore_panel_settings_slider_field(
    $tab_content,
    'items_gap',
    esc_html__( 'Items Gap (px)', 'xstore-core' ),
    false,
    0,
    50,
    7,
    1,
    'px'
);

$global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
    'background_color',
    esc_html__( 'Background Color', 'xstore-core' ),
    esc_html__( 'Choose the background color of the floating menu.', 'xstore-core' ),
    '#444' );

$global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
    'box_shadow_color',
    esc_html__( 'Box Shadow Color', 'xstore-core' ),
    esc_html__( 'Choose the box-shadow color of the floating menu. It will be auto calculated with opacity.', 'xstore-core' ),
    '#fff' );

$global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
    'item_color',
    esc_html__( 'Item Color', 'xstore-core' ),
    esc_html__( 'Choose the color of icons.', 'xstore-core' ),
    '#fff' );

$global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
    'item_color_hover',
    esc_html__( 'Item Color (hover)', 'xstore-core' ),
    esc_html__( 'Choose the color of icons on hover.', 'xstore-core' ),
    get_theme_mod( 'activecol', '#a4004f' ) );

$global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
    'dot_color',
    esc_html__( 'Dot Color', 'xstore-core' ),
    esc_html__( 'Choose the color of pulsing dots.', 'xstore-core' ),
    '#10a45d' );

$global_admin_class->xstore_panel_settings_tab_field_end();

$global_admin_class->xstore_panel_settings_tab_field_start(
    esc_html__( 'Active Colors', 'xstore-core' )
);

$global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
    'active_item_color',
    esc_html__( 'Active Item Color', 'xstore-core' ),
    esc_html__( 'Choose the color of active item.', 'xstore-core' ),
    '#fff' );

$global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
    'active_item_background_color',
    esc_html__( 'Active Item Background Color', 'xstore-core' ),
    esc_html__( 'Choose the background color of the active item.', 'xstore-core' ),
    '#10a45d' );

$global_admin_class->xstore_panel_settings_tab_field_end();
