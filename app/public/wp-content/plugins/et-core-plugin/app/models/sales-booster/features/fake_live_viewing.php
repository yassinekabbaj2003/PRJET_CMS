<?php $global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
    'message',
    esc_html__( 'Message', 'xstore-core' ),
    sprintf( esc_html__( 'Text that will be shown: %s - {eye} will be replaced by icon; %s - {count} will be replaced by calculated count between Min and Max values set below; %s Default text: {eye} {count} people are viewing this product right now', 'xstore-core' ), '<br/>', '<br/>', '<br/>' ),
    '{eye} {count} people are viewing this product right now' ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'min_count',
    esc_html__( 'Min Count', 'xstore-core' ),
    esc_html__( 'Set minimum count of fake users are viewing right now. In other words: From X user to y users.', 'xstore-core' ),
    1,
    30,
    8,
    1,
    'users' ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'max_count',
    esc_html__( 'Max Count', 'xstore-core' ),
    esc_html__( 'Set maximum count of fake users are viewing right now. In other words: From x user to Y users.', 'xstore-core' ),
    1,
    100,
    49,
    1,
    'users' ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'minutes',
    esc_html__( 'Minutes', 'xstore-core' ),
    esc_html__( 'Set minutes of recalc count of viewing people for products.', 'xstore-core' ),
    1,
    59,
    2,
    1,
    'min' ); ?>