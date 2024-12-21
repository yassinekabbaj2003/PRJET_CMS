<?php

$global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'rating_summary',
    esc_html__('Rating summary', 'xstore-core'),
    esc_html__('Display a summary of all customer ratings in a separate area dedicated to rating summaries.', 'xstore-core'),
    true);

$global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'rating_summary_position',
    esc_html__( 'Rating summary position', 'xstore-core' ),
    (get_option( 'etheme_single_product_builder', false ) ? sprintf(
        esc_html__( 'Choose the position of rating summary. By choosing "%1s" variant make sure you have %2s option active', 'xstore-core' ),
        esc_html__('Above reviews and form', 'xstore-core'),
        '<a href="'.admin_url('/customize.php?autofocus[section]=product_tabs').'" target="_blank" rel="nofollow">'.esc_html__('Separated Reviews', 'xstore-core').'</a>'
    ) : esc_html__( 'Choose the position of rating summary.', 'xstore-core' ) ),
    array(
        'above_all' => esc_html__( 'Above reviews and form', 'xstore-core' ),
        'comments_start'   => esc_html__( 'Above comments', 'xstore-core' ),
        'review_start'   => esc_html__( 'Above review form', 'xstore-core' ),
    ),
    'comments_start',
    array(
        array(
            'name'    => 'rating_summary',
            'value'   => 'on',
            'section' => $tab_content,
            'default' => 'on'
        ),
    ) );

$global_admin_class->xstore_panel_settings_switcher_field($tab_content,
    'pros_cons',
    esc_html__('Pros & Cons', 'xstore-core'),
    esc_html__('Allow customers to add pros and cons of your products in the separate fields.', 'xstore-core'),
    false);

$global_admin_class->xstore_panel_settings_switcher_field($tab_content,
    'likes',
    esc_html__('Likes/dislikes', 'xstore-core'),
    esc_html__('Customers will be allowed to cast their vote for the most helpful review.', 'xstore-core'),
    true);

$global_admin_class->xstore_panel_settings_switcher_field($tab_content,
    'reset_likes',
    esc_html__('Reset likes/dislikes', 'xstore-core'),
    esc_html__('Customers can reset their vote by clicking the icon of their vote again.', 'xstore-core'),
    false,
    array(
        array(
            'name'    => 'likes',
            'value'   => 'on',
            'section' => $tab_content,
            'default' => 'on'
        ),
    ) );

$global_admin_class->xstore_panel_settings_switcher_field($tab_content,
    'verified_owner_badge',
    esc_html__('"Verified owner" badge', 'xstore-core'),
    esc_html__('Mark reviews made by customers who have purchased the current product with a special icon.', 'xstore-core'),
    true);

$global_admin_class->xstore_panel_settings_switcher_field($tab_content,
    'rating_criteria',
    esc_html__('Rating by Criteria', 'xstore-core'),
    esc_html__( 'Customers will be able to review the product according to several criteria. For example: "Value for money", "Durability", "Delivery speed", etc.', 'xstore-core' ),
    false);

$global_admin_class->xstore_panel_settings_switcher_field($tab_content,
    'rating_criteria_required',
    esc_html__('Criteria required', 'xstore-core'),
    esc_html__( 'Enable this option to make criteria required for leaving.', 'xstore-core' ),
    false,
    array(
        array(
            'name'    => 'rating_criteria',
            'value'   => 'on',
            'section' => $tab_content,
            'default' => false
        ),
    ));

$global_admin_class->xstore_panel_settings_repeater_field(
    $tab_content,
    'criteria',
    esc_html__( 'Criteria', 'xstore-core' ),
    false,
    array(
        'criteria_1' => array(
            'callbacks' => array(
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_input_text_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'slug',
                        esc_html__( 'Slug', 'xstore-core' ),
                        esc_html__( 'Please use only Latin characters, numbers, and symbols such as "-" or "_". Unique slug/reference for the criteria.', 'xstore-core' ),
                        'value_of_money',
                        'value_for_money',
                    )
                ),
                array(
                    'callback' => array(
                        $global_admin_class,
                        'xstore_panel_settings_input_text_field'
                    ),
                    'args'     => array(
                        $tab_content,
                        'name',
                        esc_html__( 'Name', 'xstore-core' ),
                        false,
                        esc_html__('Criteria name', 'xstore-core'),
                        esc_html__('Value for money', 'xstore-core'),
                    )
                ),
            )
        ),
    ),
    array(
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_input_text_field'
            ),
            'args'     => array(
                $tab_content,
                'slug',
                esc_html__( 'Slug', 'xstore-core' ),
                esc_html__( 'Please use only Latin characters, numbers, and symbols such as "-" or "_". Unique slug/reference for the criteria.', 'xstore-core' ),
                'value_of_money',
                'delivery',
            )
        ),
        array(
            'callback' => array(
                $global_admin_class,
                'xstore_panel_settings_input_text_field'
            ),
            'args'     => array(
                $tab_content,
                'name',
                esc_html__( 'Name', 'xstore-core' ),
                false,
                esc_html__('Criteria name', 'xstore-core'),
                esc_html__('Delivery speed', 'xstore-core'),
            )
        ),
    ),
    array(
        array(
            'name'    => 'rating_criteria',
            'value'   => 'on',
            'section' => $tab_content,
            'default' => false
        ),
    ),
    esc_html__('Criteria', 'xstore-core')
);

$global_admin_class->xstore_panel_settings_switcher_field($tab_content,
    'circle_avatars',
    esc_html__('Circle avatars', 'xstore-core'),
    esc_html__('Show customers\' avatars in a circle.', 'xstore-core'),
    false);

$global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'date_format',
    esc_html__( 'Date format', 'xstore-core' ),
    false,
    array(
        'default' => sprintf(esc_html__( 'Default %s', 'xstore-core' ), wc_date_format()),
        'ago'   => esc_html__( 'X days ago', 'xstore-core' ),
        'custom'   => esc_html__( 'Custom', 'xstore-core' ),
    ),
    'ago' );

$global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'date_format_custom',
    esc_html__( 'Custom date format', 'xstore-core' ),
    __( 'More examples of formats types <a href="https://www.php.net/manual/en/datetime.format.php" target="_blank">on this page</a>. Default format is inherited from your  <a href="' . admin_url( 'options-general.php' ) . '" target="_blank">WordPress settings</a>', 'xstore-core' ),
    sprintf(
    /* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
        __( 'Use the letters: %s', 'xstore-core' ),
        'l D d j S F m M n Y y'
    ),
    wc_date_format(),
    array(
        array(
            'name'    => 'date_format',
            'value'   => 'custom',
            'section' => $tab_content,
            'default' => 'ago'
        ),
    ) );
?>