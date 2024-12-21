<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'background_color',
    esc_html__( 'Background color', 'xstore-core' ),
    sprintf(esc_html__( 'Choose the background color of your slide. %s your color and paste the value here.', 'xstore-core' ),
        '<a href="https://color.adobe.com/create/color-wheel" class="elementor-clickable" rel="nofollow" target="_blank"><strong>'.esc_html__('Generate', 'xstore-core').'</strong></a>'),
    '#f7f7f7',
    $default_settings['background_color'] ); ?>

<?php //$global_admin_class->xstore_panel_settings_upload_field( $tab_content,
//    'background_image',
//    esc_html__( 'Background image', 'xstore-core' ),
//    esc_html__( 'Choose the background image of the request a quote popup.', 'xstore-core' ) ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'background_position',
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
    $default_settings['background_position'] ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'background_repeat',
    esc_html__( 'Background repeat', 'xstore-core' ),
    false,
    array(
        'no-repeat' => esc_html__( 'No repeat', 'xstore-core' ),
        'repeat'    => esc_html__( 'Repeat All', 'xstore-core' ),
        'repeat-x'  => esc_html__( 'Repeat-X', 'xstore-core' ),
        'repeat-y'  => esc_html__( 'Repeat-Y', 'xstore-core' ),
    ),
    $default_settings['background_repeat'] ) ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'background_size',
    esc_html__( 'Display size', 'xstore-core' ),
    false,
    array(
        'auto'    => esc_html__( 'Auto', 'xstore-core' ),
        'cover'   => esc_html__( 'Cover', 'xstore-core' ),
        'contain' => esc_html__( 'Contain', 'xstore-core' ),
    ),
    $default_settings['background_size'] ); ?>