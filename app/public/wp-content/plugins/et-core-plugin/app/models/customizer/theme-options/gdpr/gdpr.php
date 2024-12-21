<?php
/**
 * The template created for displaying general optimization options
 *
 * @version 0.0.1
 * @since 9.0.3
 */
add_filter( 'et/customizer/add/sections', function($sections) {
	
	$args = array(
		'general-gdpr-cookies'	 => array(
			'name'      => 'general-gdpr-cookies',
			'title'      => esc_html__( 'GDPR & Cookies', 'xstore-core' ),
			'description' => esc_html__('Our GDPR & cookies option is the perfect solution for website owners who want to ensure they are operating in accordance with EU privacy laws.', 'xstore-core'),
			'icon' => 'dashicons-privacy',
			'priority' => 14,
			'type'		=> 'kirki-lazy',
			'dependency'    => array()
		)
	);
	return array_merge( $sections, $args );
});

add_filter( 'et/customizer/add/fields/general-gdpr-cookies', function ( $fields ) use ( $separators, $strings, $choices, $sep_style ) {

    $pages = et_b_get_posts(
        array(
            'post_per_page' => -1,
            'nopaging'      => true,
            'post_type'     => 'page',
            'with_select_page' => true
        )
    );

    $sections = et_b_get_posts(
        array(
            'post_per_page' => -1,
            'nopaging'      => true,
            'post_type'     => 'staticblocks',
            'with_none' => true
        )
    );

	$args = array();
	
	// Array of fields
	$args = array(
		'et_cookies_notice_switcher'	=> array(
			'name'		  => 'et_cookies_notice_switcher',
			'type'        => 'toggle',
			'settings'    => 'et_cookies_notice_switcher',
			'label'       => esc_html__( 'Cookies notice', 'xstore-core' ),
			'tooltip'     => esc_html__('This option allows you to quickly and easily add a notification to your website letting visitors know that your website uses cookies. Customize the look and feel of your cookie notice to fit your brand, and choose from various display options to ensure your visitors receive the message in the most effective way possible.', 'xstore-core'),
			'section'     => 'general-gdpr-cookies',
			'default'     => 0,
		),

        'et_cookies_notice_visibility_et-desktop'	=> array(
            'name'		  => 'et_cookies_notice_visibility_et-desktop',
            'type'        => 'toggle',
            'settings'    => 'et_cookies_notice_visibility_et-desktop',
            'label'       => esc_html__( 'Show on desktop', 'xstore-core' ),
            'tooltip' => esc_html__( 'Keep this option enabled if you want to display the cookie notification on the relevant device.', 'xstore-core' ),
            'section'     => 'general-gdpr-cookies',
            'default'     => 1,
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '==',
                    'value'    => '1',
                ),
            ),
        ),

        'et_cookies_notice_visibility_et-mobile'	=> array(
            'name'		  => 'et_cookies_notice_visibility_et-mobile',
            'type'        => 'toggle',
            'settings'    => 'et_cookies_notice_visibility_et-mobile',
            'label'       => esc_html__( 'Show on mobile', 'xstore-core' ),
            'tooltip' => esc_html__( 'Keep this option enabled if you want to display the cookie notification on the relevant device.', 'xstore-core' ),
            'section'     => 'general-gdpr-cookies',
            'default'     => 1,
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '==',
                    'value'    => '1',
                ),
            ),
        ),

        'et_cookies_notice_position'	=> array(
            'name'		  => 'et_cookies_notice_position',
            'type'        => 'select',
            'settings'    => 'et_cookies_notice_position',
            'label'       => esc_html__( 'Position', 'xstore-core' ),
            'tooltip'  => esc_html__('Choose the position of cookie notification.', 'xstore-core'),
            'section'     => 'general-gdpr-cookies',
            'default'     => 'left_bottom',
            'choices'     => array(
                'left_bottom' => esc_html__( 'Left bottom', 'xstore-core' ),
                'right_bottom' => esc_html__( 'Right bottom', 'xstore-core' ),
                'full_bottom' => esc_html__( 'Stretch bottom', 'xstore-core' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '==',
                    'value'    => '1',
                ),
            ),
        ),

        'et_cookies_notice_animation'	=> array(
            'name'		  => 'et_cookies_notice_animation',
            'type'        => 'select',
            'settings'    => 'et_cookies_notice_animation',
            'label'       => esc_html__( 'Animation type', 'xstore-core' ),
            'tooltip' => esc_html__('You can select the animation from a variety of customizable animations to create a cookie notification that is consistent with your brand and website design. Our animations are smooth and seamless, making notification easy for your visitors.', 'xstore-core'),
            'section'     => 'general-gdpr-cookies',
            'default'     => 'fadeInUp',
            'choices'     => array(
                'fadeInUp' => esc_html__( 'Fade In Up', 'xstore-core' ),
                'fadeIn' => esc_html__( 'Fade In', 'xstore-core' ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'element'  => '.et-cookies-popup-wrapper',
                    'property' => 'animation-name',
                    'context'  => array( 'editor', 'front' )
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '==',
                    'value'    => '1',
                ),
            ),
        ),

        'et_cookies_notice_cache'	=> array(
            'name'		  => 'et_cookies_notice_cache',
            'type'        => 'slider',
            'settings'    => 'et_cookies_notice_cache',
            'label'           => esc_html__( 'Cache lifespan', 'xstore-core' ),
            'tooltip'     => esc_html__( 'The customer\'s agreement choice will be stored for a days limit you set in this option. Note: the value will be kept in the cache for the time you set in this option or until the browser cookies are cleared. This will add an additional cookie to the customer\'s browser with the following parameters: name: "etheme_cookies", purpose: "Keep customer agreement value", expiry: "3 days by default".', 'xstore-core' ) . '<br/>' .
                esc_html__('Note: Please remember to include this in the security policy (GDPR).', 'xstore-core'),
            'section'     => 'general-gdpr-cookies',
            'default'     => 3,
            'choices'     => array(
                'min'  => '1',
                'max'  => '31',
                'step' => '1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '==',
                    'value'    => '1',
                ),
            ),
        ),

        // et_cookies_notice_content
        'et_cookies_notice_content'                   => array(
            'name'            => 'et_cookies_notice_content',
            'type'            => 'editor',
            'settings'        => 'et_cookies_notice_content',
            'label'           => esc_html__( 'Content', 'xstore-core' ),
            'tooltip'     => $strings['description']['editor_control'],
            'section'         => 'general-gdpr-cookies',
            'default'         => sprintf(esc_html__('This website uses cookies to improve your experience. %s By using this website you agree to our %s.', 'xstore-core'), '<br/>', '<a href="#">'.esc_html__('Privacy Policy', 'xstore-core').'</a>'),
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '==',
                    'value'    => '1',
                ),
                array(
                    'setting'  => 'et_cookies_notice_content_sections',
                    'operator' => '!=',
                    'value'    => '1',
                ),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'et_cookies_notice_content' => array(
                    'selector'        => '.et-cookies-popup-wrapper .cookies-content',
                    'render_callback' => function () {
                        if ( function_exists('html_blocks_callback') ) {
                            return html_blocks_callback( array(
                                'html_backup' => 'et_cookies_notice_content',
                                'html_backup_default' => sprintf(esc_html__('This website uses cookies to improve your experience. %s By using this website you agree to our %s.', 'xstore-core'), '<br/>', '<a href="#">'.esc_html__('Privacy Policy', 'xstore-core').'</a>')
                            ) );
                        }
                        else
                            return do_shortcode(get_theme_mod('et_cookies_notice_content', ''));
                    },
                ),
            ),
        ),

        // et_cookies_notice_details_page
        'et_cookies_notice_details_page'                    => array(
            'name'            => 'et_cookies_notice_details_page',
            'type'            => 'select',
            'settings'        => 'et_cookies_notice_details_page',
            'label'           => esc_html__('Page details', 'xstore-core'),
            'tooltip'         => esc_html__( 'With this option, you can select a page link to redirect your customers to your GDPR policy on your website.', 'xstore-core'),
            'section'         => 'general-gdpr-cookies',
            'multiple'        => 1,
            'choices'         => $pages,
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '==',
                    'value'    => '1',
                ),
            ),
        ),

        // et_cookies_notice_content_sections
        'et_cookies_notice_content_sections'          => array(
            'name'            => 'et_cookies_notice_content_sections',
            'type'            => 'toggle',
            'settings'        => 'et_cookies_notice_content_sections',
            'label'           => $strings['label']['use_static_block'],
            'tooltip'         => $strings['description']['use_static_block'],
            'section'         => 'general-gdpr-cookies',
            'default'         => 0,
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '==',
                    'value'    => '1',
                ),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'et_cookies_notice_content_sections' => array(
                    'selector'        => '.et-cookies-popup-wrapper .cookies-content',
                    'render_callback' => function () {
                        if ( function_exists('html_blocks_callback') ) {
                            return html_blocks_callback(array(
                                'section' => 'et_cookies_notice_content_section',
                                'sections' => 'et_cookies_notice_content_sections',
                                'html_backup' => 'et_cookies_notice_content',
                                'html_backup_default' => sprintf(esc_html__('This website uses cookies to improve your experience. %s By using this website you agree to our %s.', 'xstore-core'), '<br/>', '<a href="#">'.esc_html__('Privacy Policy', 'xstore-core').'</a>'),
                                'section_content' => true
                            ));
                        }
                        else {
                            ob_start();
                            $content = get_theme_mod( 'et_cookies_notice_content_section' );
                            $section_css = get_post_meta( $content, '_wpb_shortcodes_custom_css', true );
                            if ( ! empty( $section_css ) ) {
                                echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
                                echo strip_tags( $section_css );
                                echo '</style>';
                            }

                            etheme_static_block( $content, true );
                            return ob_get_clean();
                        }
                    },
                ),
            ),
        ),

        // et_cookies_notice_content_section
        'et_cookies_notice_content_section'           => array(
            'name'            => 'et_cookies_notice_content_section',
            'type'            => 'select',
            'settings'        => 'et_cookies_notice_content_section',
//            'label'           => sprintf( esc_html__( 'Choose %1s for Cookies content', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: var(--customizer-dark-color, #555)">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
            'label'           => $strings['label']['choose_static_block'],
            'tooltip'         => $strings['description']['choose_static_block'],
            'section'         => 'general-gdpr-cookies',
            'default'         => '',
            'priority'        => 10,
            'choices'         => $sections,
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '==',
                    'value'    => '1',
                ),
                array(
                    'setting'  => 'et_cookies_notice_content_sections',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'et_cookies_notice_content_section' => array(
                    'selector'        => '.et-cookies-popup-wrapper .cookies-content',
                    'render_callback' => function () {
                        if ( function_exists('html_blocks_callback') ) {
                            return html_blocks_callback(array(
                                'section' => 'et_cookies_notice_content_section',
                                'sections' => 'et_cookies_notice_content_sections',
                                'html_backup' => 'et_cookies_notice_content',
                                'html_backup_default' => sprintf(esc_html__('This website uses cookies to improve your experience. %s By using this website you agree to our %s.', 'xstore-core'), '<br/>', '<a href="#">'.esc_html__('Privacy Policy', 'xstore-core').'</a>'),
                                'section_content' => true
                            ));
                        }
                        else {
                            ob_start();
                            $content = get_theme_mod( 'et_cookies_notice_content_section' );
                            $section_css = get_post_meta( $content, '_wpb_shortcodes_custom_css', true );
                            if ( ! empty( $section_css ) ) {
                                echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
                                echo strip_tags( $section_css );
                                echo '</style>';
                            }

                            etheme_static_block( $content, true );
                            return ob_get_clean();
                        }
                    },
                ),
            ),
        ),

        // et_cookies_notice_content_button_text
        'et_cookies_notice_content_button_text'                  => array(
            'name'      => 'et_cookies_notice_content_button_text',
            'type'      => 'etheme-text',
            'settings'  => 'et_cookies_notice_content_button_text',
            'label'     => $strings['label']['button_text'],
            'tooltip'   => $strings['description']['button_text'],
            'section'         => 'general-gdpr-cookies',
            'default'   => esc_html__( 'Ok, I am ready', 'xstore-core' ),
            'transport' => 'postMessage',
            'js_vars'   => array(
                array(
                    'element'  => '.et-cookies-popup-wrapper .cookies-button',
                    'function' => 'html',
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '==',
                    'value'    => '1',
                ),
            )
        ),


        // style separator
        'et_cookies_notice_content_style_separator'             => array(
            'name'            => 'et_cookies_notice_content_style_separator',
            'type'            => 'custom',
            'settings'        => 'et_cookies_notice_content_style_separator',
            'section'         => 'general-gdpr-cookies',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-external"></span> <span style="padding-inline-start: 5px;">' . esc_html__( 'Popup style', 'xstore-core' ) . '</span></div>',
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '!=',
                    'value'    => 0,
                ),
            ),
        ),

        // et_cookies_notice_content_content_alignment
        'et_cookies_notice_content_content_alignment'           => array(
            'name'            => 'et_cookies_notice_content_content_alignment',
            'type'            => 'radio-buttonset',
            'settings'        => 'et_cookies_notice_content_content_alignment',
            'label'           => $strings['label']['alignment'],
            'tooltip'         => $strings['description']['alignment'],
            'section'         => 'general-gdpr-cookies',
            'default'         => 'center',
            'choices'         => $choices['alignment'],
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '!=',
                    'value'    => 0,
                ),
                array(
                    'setting'  => 'et_cookies_notice_position',
                    'operator' => '!=',
                    'value'    => 'full_bottom',
                ),
            ),
        ),

        // et_cookies_notice_content_content_width_height
        'et_cookies_notice_content_content_width_height'        => array(
            'name'            => 'et_cookies_notice_content_content_width_height',
            'type'            => 'radio-buttonset',
            'settings'        => 'et_cookies_notice_content_content_width_height',
            'label'     => esc_html__( 'Dimensions', 'xstore-core' ),
            'tooltip'   => esc_html__( 'Choose which dimensions should be applied for the "Cookies notice" popup. Select "Custom" to configure the custom dimensions in the form below.', 'xstore-core' ),
            'section'         => 'general-gdpr-cookies',
            'default'         => 'auto',
            'multiple'        => 1,
            'choices'         => array(
                'auto'   => esc_html__( 'Auto', 'xstore-core' ),
                'custom' => esc_html__( 'Custom', 'xstore-core' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '!=',
                    'value'    => 0,
                ),
                array(
                    'setting'  => 'et_cookies_notice_position',
                    'operator' => '!=',
                    'value'    => 'full_bottom',
                ),
            ),
        ),

        // et_cookies_notice_content_content_width_height_custom
        'et_cookies_notice_content_content_width_height_custom' => array(
            'name'            => 'et_cookies_notice_content_content_width_height_custom',
            'type'            => 'dimensions',
            'settings'        => 'et_cookies_notice_content_content_width_height_custom',
            'label'     => esc_html__( 'Custom dimensions', 'xstore-core' ),
            'tooltip'   => esc_html__( 'Configure the dimensions for the "Cookies notice" popup.', 'xstore-core' ),
            'section'         => 'general-gdpr-cookies',
            'default'         => array(
                'width'  => '550px',
                'height' => '250px',
            ),
			'choices'   => array(
                'labels' => $strings['label']['popup_dimensions'],
                'descriptions' => $strings['description']['popup_dimensions'],
			),
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '!=',
                    'value'    => 0,
                ),
                array(
                    'setting'  => 'et_cookies_notice_position',
                    'operator' => '!=',
                    'value'    => 'full_bottom',
                ),
                array(
                    'setting'  => 'et_cookies_notice_content_content_width_height',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
            'output'          => array(
                array(
                    'choice'   => 'width',
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et-cookies-popup-wrapper .cookies-content-custom-dimenstions',
                    'property' => 'width',
                ),
                array(
                    'choice'   => 'height',
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et-cookies-popup-wrapper .cookies-content-custom-dimensions',
                    'property' => 'height',
                ),
            ),
            'transport'       => 'postMessage',
            'js_vars'         => array(
                array(
                    'choice'   => 'width',
                    'type'     => 'css',
                    'element'  => '.et-cookies-popup-wrapper .cookies-content-custom-dimensions',
                    'property' => 'width',
                ),
                array(
                    'choice'   => 'height',
                    'type'     => 'css',
                    'element'  => '.et-cookies-popup-wrapper .cookies-content-custom-dimensions',
                    'property' => 'height',
                ),
            )
        ),

        // et_cookies_notice_content_background
        'et_cookies_notice_content_background'                  => array(
            'name'            => 'et_cookies_notice_content_background',
            'type'            => 'background',
            'settings'        => 'et_cookies_notice_content_background',
            'label'           => $strings['label']['wcag_bg_color'],
            'tooltip'     => $strings['description']['wcag_bg_color'],
            'section'         => 'general-gdpr-cookies',
            'default'         => array(
                'background-color'      => '#ffffff',
                'background-image'      => '',
                'background-repeat'     => 'no-repeat',
                'background-position'   => 'center center',
                'background-size'       => '',
                'background-attachment' => '',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '!=',
                    'value'    => 0,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context' => array( 'editor', 'front' ),
                    'element' => '.et-cookies-popup-wrapper',
                ),
            ),
        ),

        'et_cookies_notice_content_color' => array(
            'name'            => 'et_cookies_notice_content_color',
            'settings'        => 'et_cookies_notice_content_color',
            'label'           => $strings['label']['wcag_color'],
            'tooltip'     => $strings['description']['wcag_color'],
            'type'            => 'kirki-wcag-tc',
            'section'         => 'general-gdpr-cookies',
            'default'         => '#000000',
            'choices'         => array(
                'setting' => 'setting(general-gdpr-cookies)(et_cookies_notice_content_background)[background-color]',
                // 'maxHueDiff'          => 60,   // Optional.
                // 'stepHue'             => 15,   // Optional.
                // 'maxSaturation'       => 0.5,  // Optional.
                // 'stepSaturation'      => 0.1,  // Optional.
                // 'stepLightness'       => 0.05, // Optional.
                // 'precissionThreshold' => 6,    // Optional.
                // 'contrastThreshold'   => 4.5   // Optional.
                'show'    => array(
                    // 'auto'        => false,
                    // 'custom'      => false,
                    'recommended' => false,
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '!=',
                    'value'    => 0,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et-cookies-popup-wrapper, .et-cookies-popup-wrapper span.close',
                    'property' => 'color'
                )
            )
        ),

        'et_cookies_notice_content_box_model_et-desktop' => array(
            'name'            => 'et_cookies_notice_content_box_model_et-desktop',
            'settings'        => 'et_cookies_notice_content_box_model_et-desktop',
            'label'           => $strings['label']['computed_box'],
            'tooltip'     => $strings['description']['computed_box'],
            'type'            => 'kirki-box-model',
            'section' => 'general-gdpr-cookies',
            'default'         => array(
                'margin-top'          => '15px',
                'margin-right'        => '15px',
                'margin-bottom'       => '15px',
                'margin-left'         => '15px',
                'border-top-width'    => '1px',
                'border-right-width'  => '1px',
                'border-bottom-width' => '1px',
                'border-left-width'   => '1px',
                'padding-top'         => '30px',
                'padding-right'       => '30px',
                'padding-bottom'      => '30px',
                'padding-left'        => '30px',
            ),
            'output'          => array(
                array(
                    'context' => array( 'editor', 'front' ),
                    'element' => '.et-cookies-popup-wrapper',
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '!=',
                    'value'    => 0,
                ),
            ),
            'transport'       => 'postMessage',
            'js_vars'         => box_model_output( '.et-cookies-popup-wrapper' )
        ),

        // et_cookies_notice_content_border
        'et_cookies_notice_content_border'               => array(
            'name'            => 'et_cookies_notice_content_border',
            'type'            => 'select',
            'settings'        => 'et_cookies_notice_content_border',
            'label'           => $strings['label']['border_style'],
            'tooltip'     => $strings['description']['border_style'],
            'section' => 'general-gdpr-cookies',
            'default'         => 'solid',
            'choices'         => $choices['border_style'],
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et-cookies-popup-wrapper',
                    'property' => 'border-style'
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '!=',
                    'value'    => 0,
                ),
            ),
        ),

        // et_cookies_notice_content_border_color_custom
        'et_cookies_notice_content_border_color_custom'  => array(
            'name'            => 'et_cookies_notice_content_border_color_custom',
            'type'            => 'color',
            'settings'        => 'et_cookies_notice_content_border_color_custom',
            'label'           => $strings['label']['border_color'],
            'tooltip'     => $strings['description']['border_color'],
            'section' => 'general-gdpr-cookies',
            'default'         => '#e1e1e1',
            'choices'         => array(
                'alpha' => true
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'et_cookies_notice_switcher',
                    'operator' => '!=',
                    'value'    => 0,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et-cookies-popup-wrapper',
                    'property' => 'border-color',
                ),
            ),
        ),

	);

    unset($pages);

	return array_merge( $fields, $args );
	
});