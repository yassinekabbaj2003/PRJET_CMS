<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Customize Dark/Light Switcher.
 *
 * Add color user-preferences (dark/light) for WordPress customizer.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
class Etheme_Customize_User_Preferences {

    // ! Main construct/ just leave it empty
    function __construct(){}


    /**
     * Add actions.
     *
     * Actions to add template and scripts
     *
     * @since   9.0.5
     * @version 1.0.0
     */
    public function actions(){
        // // ! Add scripts and templates
        add_action( 'customize_controls_enqueue_scripts', array( $this, 'scripts' ) );
        add_action( 'customize_controls_print_footer_scripts', array( $this, 'template' ) );
    }

    /**
     * Add script and styles to WordPress Customizer
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    public function scripts() {

        wp_enqueue_script( 'et_customize-user-preferences', get_template_directory_uri() . '/framework/customizer/user-preferences/js/user-preferences.js' );

        wp_localize_script( 'et_customize-user-preferences', 'CustomizeUserPreferences', array(
            'empty_value'   => esc_html__('Please, enter the value between {{min}} and {{max}}', 'xstore'),
        ) );

        wp_register_style( 'et_customize-user-preferences', false );
        wp_enqueue_style( 'et_customize-user-preferences' );
        wp_add_inline_style('et_customize-user-preferences', "
            body {--customizer-ui-width: ".get_theme_mod('customizer_ui_width', 21)."%;
            --customizer-ui-content-zoom: ".get_theme_mod('customizer_ui_zoom', 1).";}
            /*@media only screen and (min-width: 1667px) {*/
            @media only screen and (min-width: 1200px) {
                .wp-full-overlay.expanded {
                    margin-left: 0;
                    margin-right: 0;
                    margin-inline-start: var(--customizer-ui-width, 21%);
                }
                .wp-full-overlay.collapsed .wp-full-overlay-sidebar {
                    margin-left: 0;
                    margin-right: 0;
                    margin-inline-start: calc( -1 * var(--customizer-ui-width, 21%));
                }
                body .wp-full-overlay-sidebar,
                body #customize-footer-actions .devices-wrapper,
                body #customize-controls #customize-notifications-area {
                    width: var(--customizer-ui-width, 21%);
                    min-width: 200px;
                    max-width: 100%;
                    transition: all 0.3s linear;
                }
            }
        ", 'after');

        // if the device user comes from does not support hover so it will be forcefully
        // shown as visible descriptions on options
        wp_add_inline_script('et_customize-user-preferences', "
            document.addEventListener('DOMContentLoaded', function(){
                if ( window.matchMedia && window.matchMedia('(hover: none)').matches )
                    document.getElementsByClassName('wp-customizer')[0].setAttribute('data-options-description', 'yes'); 
            });", 'after');
        $default_mode = get_theme_mod('customizer_ui_theme', 'auto');
        if ( $default_mode == 'auto') {
            wp_add_inline_script('et_customize-user-preferences', "
            document.addEventListener('DOMContentLoaded', function(){
                if ( window.matchMedia && window.matchMedia( `(prefers-color-scheme: dark)` ).matches )
                    document.getElementsByClassName('wp-customizer')[0].setAttribute('data-mode', 'dark');
                else
                    document.getElementsByClassName('wp-customizer')[0].setAttribute('data-mode', 'light'); 
            });", 'after');
        }
        elseif ( $default_mode == 'dark' ) {
            wp_add_inline_script('et_customize-user-preferences', "
            document.addEventListener('DOMContentLoaded', function(){
                document.getElementsByClassName('wp-customizer')[0].setAttribute('data-mode', 'dark'); 
            });", 'after');
        }

        $description_type = get_theme_mod('customizer_options_descriptions', 'tooltip');
        if ( $description_type == 'description' ) {
            wp_add_inline_script('et_customize-user-preferences', "
            document.addEventListener('DOMContentLoaded', function(){
                document.getElementsByClassName('wp-customizer')[0].setAttribute('data-options-description', 'yes'); 
            });", 'after');
        }

        $columns = get_theme_mod('customizer_options_columns', 'two');
        if ( $columns == 'one' ) {
            wp_add_inline_script('et_customize-user-preferences', "
            document.addEventListener('DOMContentLoaded', function(){
                document.getElementsByClassName('wp-customizer')[0].setAttribute('data-options-column', 'yes'); 
            });", 'after');
        }

    }

    /**
     * Get user-preferences template.
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    public function template(){ 
        get_template_part( 'framework/customizer/user-preferences/template-parts/user-preferences' );
    }
}

$Etheme_Customize_User_Preferences = new Etheme_Customize_User_Preferences();
$Etheme_Customize_User_Preferences -> actions();