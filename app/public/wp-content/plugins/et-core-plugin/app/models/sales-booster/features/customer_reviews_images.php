<?php
$global_admin_class->xstore_panel_settings_slider_field(
    $tab_content,
    'image_size',
    esc_html__( 'Max image size (MB)', 'xstore-core' ),
    false,
    1,
    5,
    1,
    1,
    'MB'
);

$global_admin_class->xstore_panel_settings_slider_field(
    $tab_content,
    'images_count',
    esc_html__( 'Max images count', 'xstore-core' ),
    false,
    1,
    10,
    3,
    1
);

$global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'images_required',
    esc_html__( 'Required Images', 'xstore-core' ),
    esc_html__( 'Turn on if it is required to upload images on new review', 'xstore-core' ),
    false );

//							$global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
//								'images_lightbox',
//								esc_html__( 'Images Lightbox', 'xstore-core' ),
//								esc_html__( 'Turn on to open lightbox on image click in comments', 'xstore-core' ),
//								true );

$global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'images_preview',
    esc_html__( 'Images Preview', 'xstore-core' ),
    esc_html__( 'Turn on to show customer preview images before submitting form', 'xstore-core' ),
    true );
