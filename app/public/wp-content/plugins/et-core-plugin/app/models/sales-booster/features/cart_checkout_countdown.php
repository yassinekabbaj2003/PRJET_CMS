
<?php $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'countdown_loop',
    esc_html__( 'Countdown loop', 'xstore-core' ),
    false,
    false ); ?>

<?php $global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
    'countdown_message',
    esc_html__( 'Countdown Message', 'xstore-core' ),
    esc_html__( 'Text that will be shown while timer is live. {fire} will be replaced by emoji, {timer} will be replaced by countdown timer', 'xstore-core' ),
    '{fire} Hurry up, these products are limited, checkout within {timer}' ); ?>

<?php $global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
    'countdown_expired_message',
    esc_html__( 'Countdown Expired Message', 'xstore-core' ),
    esc_html__( 'Text that will be shown when timer ends', 'xstore-core' ),
    'You are out of time! Checkout now to avoid losing your order!' ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'countdown_minutes',
    esc_html__( 'Minutes', 'xstore-core' ),
    false,
    1,
    59,
    5,
    1,
    'min' ); ?>
