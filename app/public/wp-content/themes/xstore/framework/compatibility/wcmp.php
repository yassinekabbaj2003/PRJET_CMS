<?php
/**
 * Description
 *
 * @package    wcmp.php
 * @since      8.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

// mvx_before_vendor_dashboard
// **********************************************************************//
// ! WC Marketplace fix
// **********************************************************************//
if ( class_exists( 'WCMp_Ajax' ) ) {
    add_action( 'wp_head', 'single_product_multiple_vendor_class' );
}

if ( ! function_exists( 'single_product_multiple_vendor_class' ) ) :
    function single_product_multiple_vendor_class(){
        ?>
        <script type="text/javascript">
            var themeSingleProductMultivendor = '#content_tab_singleproductmultivendor';
        </script>
        <?php
    }
endif;

add_action( 'wp_enqueue_scripts', function (){
    $plugin_4x_version = class_exists('MVX');
    if ( ($plugin_4x_version && function_exists('mvx_is_store_page') && mvx_is_store_page()) ||
        (!$plugin_4x_version && function_exists('wcmp_is_store_page') && wcmp_is_store_page()) ) {
        etheme_enqueue_style( 'star-rating' );
        etheme_enqueue_style( 'comments' );

        wp_enqueue_script( 'comment-reply' );
    }
}, 35 );

add_action('mvx_frontend_enqueue_scripts', function ($is_vendor_dashboard) {
    if ( $is_vendor_dashboard ) {
        $dir_uri = get_template_directory_uri();
        $icons_type = ( etheme_get_option('bold_icons', 0) ) ? 'bold' : 'light';
        wp_register_style( 'xstore-icons-font', false );
        wp_enqueue_style( 'xstore-icons-font' );
        wp_add_inline_style( 'xstore-icons-font',
            "@font-face {
		  font-family: 'xstore-icons';
		  src:
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".ttf') format('truetype'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".woff2') format('woff2'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".woff') format('woff'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".svg#xstore-icons') format('svg');
		  font-weight: normal;
		  font-style: normal;
		  font-display: swap;
		}"
        );
        wp_enqueue_style( 'xstore-icons-font-style', $dir_uri . '/css/xstore-icons.css' );
        etheme_enqueue_style('wcmp-dashboard-style');
    }
});

add_filter('do_shortcode_tag', function ($content, $shortcode, $atts) {
    if ( in_array($shortcode, array('wcmp_vendor', 'mvx_vendor'))) {
        if ( !get_query_var( 'et_is-loggedin', false) ) {
            etheme_enqueue_style( 'account-page' );
            $content = str_replace(
                array('wcmp-dashboard', 'mvx-dashboard'),
                array('wcmp-dashboard woocommerce-account', 'mvx-dashboard woocommerce-account'),
                $content );
        }
    }
    return $content;
},10,3);

add_action('wp', function () {
    add_action( 'woocommerce_before_shop_loop', function () {
        $plugin_4x_version = class_exists('MVX');
        if ( wc_get_loop_prop( 'is_shortcode' ) || ($plugin_4x_version && function_exists('mvx_is_store_page') && mvx_is_store_page() ||
                (!$plugin_4x_version && function_exists('wcmp_is_store_page') && wcmp_is_store_page())
            ) ) {
            add_filter('theme_mod_ajax_product_filter', '__return_false'); // lock ajax filters on Vendor page
            etheme_enqueue_style('filter-area', true ); ?>
            <div class="filter-wrap">
            <div class="filter-content">
        <?php }
    }, 0 );

    add_action( 'woocommerce_before_shop_loop', function () {
        $plugin_4x_version = class_exists('MVX');
        if ( wc_get_loop_prop( 'is_shortcode' ) || ($plugin_4x_version && function_exists('mvx_is_store_page') && mvx_is_store_page()) ||
            (!$plugin_4x_version && function_exists('wcmp_is_store_page') && wcmp_is_store_page())
        ) { ?>
            </div>
            </div>
        <?php }
    }, 99999 );
}, 60);

add_filter('mvx_store_sidebar_args', function ($args) {
    $args['before_widget'] = '<aside id="%1$s" class="widget sidebar-widget clr %2$s">';
    $args['before_title']  = apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>');
    $args['after_title']  = apply_filters('etheme_sidebar_after_title', '</span></h4>');
   return $args;
});