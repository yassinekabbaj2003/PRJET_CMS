<?php
// fix compare
add_action('yith_woocompare_popup_head', function (){
    $filenames = array(
        'parent-style',
        'woocommerce',
        'woocommerce-archive',
        'yith-compare',
    );
    $config = etheme_config_css_files();
    $is_rtl = is_rtl();
    $theme = wp_get_theme();
    foreach ( $filenames as $filename ) {
        if ( !isset($config[$filename])) return;
        if ( $is_rtl ) {
            $rtl_file = get_template_directory() . esc_attr( $config[$filename]['file']) . '-rtl'.ETHEME_MIN_CSS.'.css';
            if (file_exists($rtl_file)) {
                $config[$filename]['file'] .= '-rtl';
            }
        } ?>
        <link rel="stylesheet" id="<?php echo 'etheme-'.esc_attr( $config[$filename]['name'] ); ?>-css" href="<?php echo get_template_directory_uri() . esc_attr( $config[$filename]['file'] ) . ETHEME_MIN_CSS; ?>.css?ver=<?php echo esc_attr( $theme->version ); ?>" type="text/css" media="all" /> <?php // phpcs:ignore ?>
        <?php
    }
    ?>
    <link rel="stylesheet" id="xstore-icons-css" href="<?php get_template_directory_uri() . '/css/xstore-icons.css'; ?>" type="text/css" media="all" /> <?php // phpcs:ignore ?>
    <style>
        @font-face {
            font-family: 'xstore-icons';
            src:
                url('<?php echo get_template_directory_uri(); ?>/fonts/xstore-icons-light.ttf') format('truetype'),
                url('<?php echo get_template_directory_uri(); ?>/fonts/xstore-icons-light.woff2') format('woff2'),
                url('<?php echo get_template_directory_uri(); ?>/fonts/xstore-icons-light.woff') format('woff'),
                url('<?php echo get_template_directory_uri(); ?>/fonts/xstore-icons-light.svg#xstore-icons') format('svg');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
    </style>
    <?php
});