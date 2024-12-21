<?php $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'show_all_pages',
    esc_html__( 'Show on all pages', 'xstore-core' ),
    false,
    false ); ?>

<?php $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'show_as_button',
    esc_html__( 'Show as button on Single Product', 'xstore-core' ),
    false,
    false ); ?>

<?php $global_admin_class->xstore_panel_settings_upload_field( $tab_content,
    'icon',
    esc_html__( 'Custom Image/SVG', 'xstore-core' ),
    false ); ?>

<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'label',
    esc_html__( 'Custom label', 'xstore-core' ),
    false,
    false,
    esc_html__( 'Ask an expert', 'xstore-core' ) ); ?>

<?php $global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
    'popup_content',
    esc_html__( 'Popup content', 'xstore-core' ),
    esc_html__( 'Enter static block shortcode or custom html', 'xstore-core' ),
    false ); ?>

<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'popup_dimensions_custom_width',
    esc_html__( 'Custom popup width', 'xstore-core' ),
    false,
    false,
    '' ); ?>

<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'popup_dimensions_custom_height',
    esc_html__( 'Custom popup height', 'xstore-core' ),
    false,
    false,
    '' ); ?>

<?php $global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
    'popup_background_color',
    esc_html__( 'Popup background color', 'xstore-core' ),
    esc_html__( 'Choose the background color of the request a quote popup.', 'xstore-core' ),
    '#fff' ); ?>

<?php $global_admin_class->xstore_panel_settings_upload_field( $tab_content,
    'popup_background_image',
    esc_html__( 'Background image', 'xstore-core' ),
    esc_html__( 'Choose the background image of the request a quote popup.', 'xstore-core' ) ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'popup_background_repeat',
    esc_html__( 'Background repeat', 'xstore-core' ),
    false,
    array(
        'no-repeat' => esc_html__( 'No repeat', 'xstore-core' ),
        'repeat'    => esc_html__( 'Repeat All', 'xstore-core' ),
        'repeat-x'  => esc_html__( 'Repeat-X', 'xstore-core' ),
        'repeat-y'  => esc_html__( 'Repeat-Y', 'xstore-core' ),
    ),
    'no-repeat' ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'popup_background_position',
    esc_html__( 'Background position', 'xstore-core' ),
    false,
    array(
        'left top'      => esc_html__( 'Left top', 'xstore-core' ),
        'left center'   => esc_html__( 'Left center', 'xstore-core' ),
        'left bottom'   => esc_html__( 'Left bottom', 'xstore-core' ),
        'right top'     => esc_html__( 'Right top', 'xstore-core' ),
        'right center'  => esc_html__( 'Right center', 'xstore-core' ),
        'right bottom'  => esc_html__( 'Right bottom', 'xstore-core' ),
        'center top'    => esc_html__( 'Center top', 'xstore-core' ),
        'center center' => esc_html__( 'Center center', 'xstore-core' ),
        'center bottom' => esc_html__( 'Center bottom', 'xstore-core' ),
    ),
    'center center' ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'popup_background_size',
    esc_html__( 'Background size', 'xstore-core' ),
    false,
    array(
        'cover'   => esc_html__( 'Cover', 'xstore-core' ),
        'contain' => esc_html__( 'Contain', 'xstore-core' ),
        'auto'    => esc_html__( 'Auto', 'xstore-core' ),
    ),
    'cover' ); ?>

<?php $global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
    'popup_color',
    esc_html__( 'Popup text color', 'xstore-core' ),
    'Choose the color of the request a quote popup.',
    '#000' ); ?>
