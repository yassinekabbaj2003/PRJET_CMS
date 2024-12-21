 <?php $global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
    'progress_bar_message_text',
    esc_html__( 'Progress message text', 'xstore-core' ),
    esc_html__( 'Write your text for progress bar using {{et_price}} to replace with scripts', 'xstore-core' ),
    get_theme_mod( 'booster_progress_content_et-desktop', esc_html__( 'Spend {{et_price}} to get free shipping', 'xstore-core' ) ) ); ?>

<?php $global_admin_class->xstore_panel_settings_icons_select( $tab_content,
    'progress_bar_process_icon',
    esc_html__( 'Process icon', 'xstore-core' ),
    false,
    $global_admin_class->xstore_panel_icons_list(),
    get_theme_mod( 'booster_progress_icon_et-desktop', 'et_icon-delivery' ) ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'progress_bar_process_icon_position',
    esc_html__( 'Process icon position', 'xstore-core' ),
    false,
    array(
        'before' => esc_html__( 'Before', 'xstore-core' ),
        'after'  => esc_html__( 'After', 'xstore-core' ),
    ),
    get_theme_mod( 'booster_progress_icon_position_et-desktop', 'before' ) ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'progress_bar_cart_ignore_discount',
    esc_html__( 'Ignore Coupons', 'xstore-core' ),
    esc_html__( 'Ignore coupons while count free shipping.', 'xstore-core' ),
    array(
        'yes' => esc_html__( 'Yes', 'xstore-core' ),
        'no'  => esc_html__( 'No', 'xstore-core' ),
    ),
    get_theme_mod( 'progress_bar_cart_ignore_discount', 'yes' ) ); ?>

<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'progress_bar_price',
    esc_html__( 'Price {{Et_price}} For Count', 'xstore-core' ),
    esc_html__( 'Enter only numbers. Please, don\'t use any currency symbol.', 'xstore-core' ),
    false,
    get_theme_mod( 'booster_progress_price_et-desktop', '350' ) ); ?>

<?php $global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
    'progress_bar_message_success_text',
    esc_html__( 'Success message text', 'xstore-core' ),
    false,
    get_theme_mod( 'booster_progress_content_success_et-desktop', esc_html__( 'Congratulations! You\'ve got free shipping.', 'xstore-core' ) ) ); ?>

<?php $global_admin_class->xstore_panel_settings_icons_select( $tab_content,
    'progress_bar_success_icon',
    esc_html__( 'Success icon', 'xstore-core' ),
    false,
    $global_admin_class->xstore_panel_icons_list(),
    get_theme_mod( 'booster_progress_success_icon_et-desktop', 'et_icon-star' ) ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'progress_bar_success_icon_position',
    esc_html__( 'Success icon position', 'xstore-core' ),
    false,
    array(
        'before' => esc_html__( 'Before', 'xstore-core' ),
        'after'  => esc_html__( 'After', 'xstore-core' ),
    ),
    get_theme_mod( 'booster_progress_success_icon_position_et-desktop', 'before' ) ); ?>
