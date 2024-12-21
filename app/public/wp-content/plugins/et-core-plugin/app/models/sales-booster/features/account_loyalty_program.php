<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'title',
    esc_html__( 'Title', 'xstore-core' ),
    esc_html__( 'Write your title. This text will be shown before benefit items.', 'xstore-core' ),
    false,
    esc_html__( 'Sign up today and you will be able to:', 'xstore-core' ) );
?>
<?php $global_admin_class->xstore_panel_settings_repeater_field(
    $tab_content,
    'benefits',
    esc_html__( 'Benefits', 'xstore-core' ),
    false,
    array(
        'benefits_1' => array(
            'callbacks' => array(
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_icons_select'
                    ),
                    'args'     => array(
                        $tab_content,
                        'icon',
                        esc_html__( 'Icon', 'xstore-core' ),
                        false,
                        $global_admin_class->xstore_panel_icons_list(),
                        'et_icon-tick',
                    )
                ),
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_input_text_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'title',
                        esc_html__( 'Title', 'xstore-core' ),
                        false,
                        '',
                        esc_html__('Quick checkout for a seamless shopping experience', 'xstore-core'),
                    )
                ),
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_textarea_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'description',
                        esc_html__( 'Description', 'xstore-core' ),
                        false,
                        esc_html__('Register on our e-commerce website for fast and easy checkout. You\'ll be able to save your shipping and payment details, so you can breeze through the checkout process with just a few clicks.', 'xstore-core'),
                    )
                ),
            )
        ),
    ),
    array(
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_icons_select'
            ),
            'args'     => array(
                $tab_content,
                'icon',
                esc_html__( 'Icon', 'xstore-core' ),
                false,
                $global_admin_class->xstore_panel_icons_list(),
                'et_icon-tick',
            )
        ),
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_input_text_field'
            ),
            'args'     => array(
                $tab_content,
                'title',
                esc_html__( 'Title', 'xstore-core' ),
                false,
                '',
                esc_html__('Stay up-to-date with order tracking', 'xstore-core'),
            )
        ),
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_textarea_field'
            ),
            'args'     => array(
                $tab_content,
                'description',
                esc_html__( 'Description', 'xstore-core' ),
                false,
                esc_html__('When you register on our e-commerce website, you\'ll be able to track your orders effortlessly. Keep an eye on the status of your deliveries, get alerts when they\'re on their way, and never miss a package again.', 'xstore-core'),
            )
        ),
    ),
    array(
    ),
    esc_html__('Benefits', 'xstore-core')
);