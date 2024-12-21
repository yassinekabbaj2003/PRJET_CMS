<?php
/**
 * The template created for displaying header waitlist options when woocommerce plugin is installed
 *
 * @version 1.0.9
 * @since   1.4.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {

    $args = array(
        'waitlist' => array(
            'name'       => 'waitlist',
            'title'      => esc_html__( 'Waitlist', 'xstore-core' ),
            'panel'      => 'header-builder',
            'icon'       => 'dashicons-bell',
            'type'       => 'kirki-lazy',
            'dependency' => array()
        )
    );

    return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/waitlist', function ( $fields ) use ( $separators, $strings, $choices, $sep_style, $box_models ) {
    $args = array();

    // Array of fields
    $args = array(
        // content separator
        'waitlist_content_separator'                            => array(
            'name'     => 'waitlist_content_separator',
            'type'     => 'custom',
            'settings' => 'waitlist_content_separator',
            'section'  => 'waitlist',
            'default'  => $separators['content'],
            'priority' => 10,
        ),

        // waitlist_style
        'waitlist_style_et-desktop'                             => array(
            'name'            => 'waitlist_style_et-desktop',
            'type'            => 'radio-image',
            'settings'        => 'waitlist_style_et-desktop',
            'label'           => $strings['label']['style'],
            'tooltip'  => $strings['description']['style'],
            'section'         => 'waitlist',
            'default'         => 'type1',
            'choices'         => et_b_element_styles( 'waitlist' ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'waitlist_style_et-desktop' => array(
                    'selector'        => '.et_b_header-waitlist.et_element-top-level',
                    'render_callback' => 'header_waitlist_callback'
                ),
            ),
            'js_vars'         => array(
                array(
                    'element'  => '.et_b_header-waitlist.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'waitlist-type1',
                    'value'    => 'type1'
                ),
                array(
                    'element'  => '.et_b_header-waitlist.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'waitlist-type2',
                    'value'    => 'type2'
                ),
                array(
                    'element'  => '.et_b_header-waitlist.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'waitlist-type3',
                    'value'    => 'type3'
                ),
            ),
        ),

        // waitlist_icon
        'waitlist_icon_et-desktop'                              => array(
            'name'            => 'waitlist_icon_et-desktop',
            'type'            => 'radio-image',
            'settings'        => 'waitlist_icon_et-desktop',
            'label'           => $strings['label']['icon'],
            'tooltip'     => $strings['description']['icon'],
            'section'         => 'waitlist',
            'default'         => 'type1',
            'choices'         => array(
                'type1'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/waitlist/Waitlist-1.svg',
//                'type2'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/waitlist/Waitlist-2.svg',
                'custom' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
                'none'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg'
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'waitlist_icon' => array(
                    'selector'        => '.et_b_header-waitlist > a .et_b-icon .et-svg',
                    'render_callback' => function () {
                        global $et_waitlist_icons;
                        $type = get_theme_mod( 'waitlist_icon_et-desktop', 'type1' );
                        if ( $type == 'custom' && get_theme_mod( 'waitlist_icon_custom_svg_et-desktop', '' ) != '' ) {
                            return get_post_meta( get_theme_mod( 'waitlist_icon_custom_svg_et-desktop', '' ), '_xstore_inline_svg', true );
                        }

                        return $et_waitlist_icons['light'][ $type ];
                    },
                ),
            ),
        ),

        // waitlist_icon_custom_svg
        'waitlist_icon_custom_svg_et-desktop'                   => array(
            'name'            => 'waitlist_icon_custom_svg_et-desktop',
            'type'            => 'image',
            'settings'        => 'waitlist_icon_custom_svg_et-desktop',
            'label'           => $strings['label']['custom_image_svg'],
            'tooltip'     => $strings['description']['custom_image_svg'],
            'section'         => 'waitlist',
            'default'         => '',
            'choices'         => array(
                'save_as' => 'array',
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'waitlist_icon_custom_svg_et-desktop' => array(
                    'selector'        => '.header-wrapper .et_b_header-waitlist.et_element-top-level',
                    'render_callback' => 'header_waitlist_callback'
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'waitlist_icon_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),

        // waitlist_icon_custom
        // 'waitlist_icon_custom_et-desktop'	=>	 array (
        // 	'name'	   => 'waitlist_icon_custom_et-desktop',
        // 	'type'     => 'code',
        // 	'settings' => 'waitlist_icon_custom_et-desktop',
        // 	'label'    => $strings['label']['custom_icon_svg'],
        // 	'section'  => 'waitlist',
        // 	'default'  => '',
        // 	'choices'  => array(
        // 		'language' => 'html'
        // 	),
        // 	'active_callback' => array(
        // 		array(
        // 			'setting'  => 'waitlist_icon_et-desktop',
        // 			'operator' => '==',
        // 			'value'    => 'custom',
        // 		),
        // 	),
        // 	'transport' => 'postMessage',
        // 	'js_vars' => array(
        // 		array(
        // 			'element'  => '.et_b_header-waitlist > a .et_b-icon .et-svg',
        // 			'function' => 'html',
        // 		),
        // 	),
        // ),

        // waitlist_icon_zoom
        'waitlist_icon_zoom_et-desktop'                         => array(
            'name'            => 'waitlist_icon_zoom_et-desktop',
            'type'            => 'slider',
            'settings'        => 'waitlist_icon_zoom_et-desktop',
            'label'           => $strings['label']['icon_size_proportion'],
            'tooltip'     => $strings['description']['icon_size_proportion'],
            'section'         => 'waitlist',
            'default'         => 1.3,
            'choices'         => array(
                'min'  => '.7',
                'max'  => '3',
                'step' => '.1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'waitlist_icon_et-desktop',
                    'operator' => '!=',
                    'value'    => 'none',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a svg',
                    'property' => 'width',
                    'units'    => 'em'
                ),
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a svg',
                    'property' => 'height',
                    'units'    => 'em'
                )
            )
        ),

        // waitlist_icon_zoom
        'waitlist_icon_zoom_et-mobile'                          => array(
            'name'            => 'waitlist_icon_zoom_et-mobile',
            'type'            => 'slider',
            'settings'        => 'waitlist_icon_zoom_et-mobile',
            'label'           => $strings['label']['icon_size_proportion'],
            'tooltip'     => $strings['description']['icon_size_proportion'],
            'section'         => 'waitlist',
            'default'         => 1.4,
            'choices'         => array(
                'min'  => '.7',
                'max'  => '3',
                'step' => '.1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'waitlist_icon_et-mobile',
                    'operator' => '!=',
                    'value'    => 'none',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level > a svg',
                    'property' => 'width',
                    'units'    => 'em'
                ),
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level > a svg',
                    'property' => 'height',
                    'units'    => 'em'
                )
            )
        ),

        // waitlist_label
        'waitlist_label_et-desktop'                             => array(
            'name'      => 'waitlist_label_et-desktop',
            'type'      => 'toggle',
            'settings'  => 'waitlist_label_et-desktop',
            'label'     => $strings['label']['show_title'],
            'tooltip'   => $strings['description']['show_title'],
            'section'   => 'waitlist',
            'default'   => '1',
            'transport' => 'postMessage',
//			'js_vars'   => array(
//				array(
//					'element'  => '.et_b_header-waitlist.et_element-top-level > a .et-element-label',
//					'function' => 'toggleClass',
//					'class'    => 'dt-hide',
//					'value'    => false
//				),
//			),
            'partial_refresh' => array(
                'waitlist_label_et-desktop' => array(
                    'selector'        => '.et_b_header-waitlist.et_element-top-level',
                    'render_callback' => 'header_waitlist_callback'
                ),
            ),
        ),

        // waitlist_label
        'waitlist_label_et-mobile'                              => array(
            'name'      => 'waitlist_label_et-mobile',
            'type'      => 'toggle',
            'settings'  => 'waitlist_label_et-mobile',
            'label'     => $strings['label']['show_title'],
            'tooltip'   => $strings['description']['show_title'],
            'section'   => 'waitlist',
            'default'   => '0',
            'transport' => 'postMessage',
//			'js_vars'   => array(
//				array(
//					'element'  => '.et_b_header-waitlist.et_element-top-level > a .et-element-label',
//					'function' => 'toggleClass',
//					'class'    => 'mob-hide',
//					'value'    => false
//				),
//			),
            'partial_refresh' => array(
                'waitlist_label_et-mobile' => array(
                    'selector'        => '.et_b_header-waitlist.et_element-top-level',
                    'render_callback' => 'header_waitlist_callback'
                ),
            ),
        ),

        // waitlist_label_custom
        'waitlist_label_custom_et-desktop'                      => array(
            'name'      => 'waitlist_label_custom_et-desktop',
            'type'      => 'etheme-text',
            'settings'  => 'waitlist_label_custom_et-desktop',
            'section'   => 'waitlist',
            'label'     => esc_html__('Title text', 'xstore-core'),
            'tooltip'   => esc_html__('Customize the text on your title.', 'xstore-core'),
            'default'   => esc_html__( 'Waitlist', 'xstore-core' ),
            'transport' => 'postMessage',
            'js_vars'   => array(
                array(
                    'element'  => '.et_b_header-waitlist > a .et-element-label',
                    'function' => 'html',
                ),
            ),
        ),

        // waitlist_content_type
        'waitlist_content_type_et-desktop'                      => array(
            'name'            => 'waitlist_content_type_et-desktop',
            'type'            => 'radio-buttonset',
            'settings'        => 'waitlist_content_type_et-desktop',
            'label'           => esc_html__( 'Content type', 'xstore-core' ),
            'tooltip'  => esc_html__( 'Choose the type of content for the mini-waitlist content. There are three options available for you: Dropdown, Off-Canvas and None (if you don\'t want to have mini-content shown for this element).', 'xstore-core' ),
            'section'         => 'waitlist',
            'default'         => 'dropdown',
            'multiple'        => 1,
            'choices'         => array(
                'none'       => esc_html__( 'None', 'xstore-core' ),
                'dropdown'   => esc_html__( 'Dropdown', 'xstore-core' ),
                'off_canvas' => esc_html__( 'Off-Canvas', 'xstore-core' ),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'waitlist_content_type_et-desktop' => array(
                    'selector'        => '.header-wrapper .et_b_header-waitlist.et_element-top-level',
                    'render_callback' => 'header_waitlist_callback'
                ),
            ),
            'js_vars'         => array(
                array(
                    'element'  => '.header-wrapper .et_b_header-waitlist.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-toTop',
                    'value'    => 'dropdown'
                ),
                array(
                    'element'  => '.header-wrapper .et_b_header-waitlist.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-content_toggle',
                    'value'    => 'off_canvas'
                ),
                array(
                    'element'  => '.header-wrapper .et_b_header-waitlist.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-off-canvas',
                    'value'    => 'off_canvas'
                ),
            ),
        ),

        // waitlist_content_type
        'waitlist_content_type_et-mobile'                       => array(
            'name'            => 'waitlist_content_type_et-mobile',
            'type'            => 'radio-buttonset',
            'settings'        => 'waitlist_content_type_et-mobile',
            'label'           => esc_html__( 'Content type', 'xstore-core' ),
            'tooltip'  => esc_html__( 'Choose the type of content for the mini-waitlist content. There are few options available for you: Off-Canvas and None (if you don\'t want to have mini-content shown for this element).', 'xstore-core' ),
            'section'         => 'waitlist',
            'default'         => 'off_canvas',
            'multiple'        => 1,
            'choices'         => array(
                'none'       => esc_html__( 'None', 'xstore-core' ),
                'off_canvas' => esc_html__( 'Off-Canvas', 'xstore-core' ),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'waitlist_content_type_et-mobile' => array(
                    'selector'        => '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level',
                    'render_callback' => 'header_waitlist_callback'
                ),
            ),
            'js_vars'         => array(
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-toTop',
                    'value'    => 'dropdown'
                ),
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-content_toggle',
                    'value'    => 'off_canvas'
                ),
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-off-canvas',
                    'value'    => 'off_canvas'
                ),
            ),
        ),

        // mini-waitlist-items-count
        'mini-waitlist-items-count'                             => array(
            'name'            => 'mini-waitlist-items-count',
            'type'            => 'slider',
            'settings'        => 'mini-waitlist-items-count',
            'label'           => esc_html__( 'Mini-waitlist products amount', 'xstore-core' ),
            'tooltip'         => esc_html__( 'Set the maximum number of products to be displayed in the mini-content of this element.', 'xstore-core' ),
            'section'         => 'waitlist',
            'default'         => get_theme_mod( 'mini-cart-items-count', '3' ),
            'choices'         => array(
                'min'  => 1,
                'max'  => 30,
                'step' => 1,
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'mini-waitlist-items-count' => array(
                    'selector'        => '.et_b_header-waitlist.et_element-top-level',
                    'render_callback' => 'header_waitlist_callback'
                ),
            ),
        ),

        // waitlist_link_to
        'waitlist_link_to'                                      => array(
            'name'            => 'waitlist_link_to',
            'type'            => 'select',
            'settings'        => 'waitlist_link_to',
            'label'           => esc_html__( 'Link to', 'xstore-core' ),
            'tooltip'         => esc_html__( 'With this option, you can select the page link to which the customer will be redirected, or enter your own link for this purpose.', 'xstore-core' ),
            'section'         => 'waitlist',
            'default'         => 'waitlist_url',
            'priority'        => 10,
            'choices'         => array(
                'waitlist_url' => esc_html__( 'Waitlist page', 'xstore-core' ),
                'custom_url'   => $strings['label']['custom_link'],
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'waitlist_link_to' => array(
                    'selector'        => '.et_b_header-waitlist.et_element-top-level',
                    'render_callback' => 'header_waitlist_callback'
                ),
            ),
            'active_callback' => function () {
                if ( get_theme_mod( 'waitlist_content_type_et-desktop', 'dropdown' ) != 'off_canvas' || get_theme_mod( 'waitlist_content_type_et-mobile', 'dropdown' ) != 'off_canvas' ) {
                    return true;
                }

                return false;
            }
        ),

        // waitlist_custom_url
        'waitlist_custom_url'                                   => array(
            'name'            => 'waitlist_custom_url',
            'type'            => 'etheme-link',
            'settings'        => 'waitlist_custom_url',
            'label'           => $strings['label']['custom_link'],
            'tooltip'         => $strings['description']['custom_link'],
            'section'         => 'waitlist',
            'default'         => '#',
            'active_callback' => array(
                array(
                    'setting'  => 'waitlist_link_to',
                    'operator' => '==',
                    'value'    => 'custom_url',
                ),
            ),
            'transport'       => 'postMessage',
            'js_vars'         => array(
                array(
                    'element'  => '.et_b_header-waitlist > a',
                    'attr'     => 'href',
                    'function' => 'html',
                ),
            ),
        ),

        // content separator
        'waitlist_quantity_separator'                           => array(
            'name'     => 'waitlist_quantity_separator',
            'type'     => 'custom',
            'settings' => 'waitlist_quantity_separator',
            'section'  => 'waitlist',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-image-filter"></span> <span style="padding-inline-start: 5px;">' . esc_html__( 'Quantity options', 'xstore-core' ) . '</span></div>',
            'priority' => 10,
        ),

        // waitlist_quantity
        'waitlist_quantity_et-desktop'                          => array(
            'name'            => 'waitlist_quantity_et-desktop',
            'type'            => 'toggle',
            'settings'        => 'waitlist_quantity_et-desktop',
//			'label'           => esc_html__( 'Show waitlist quantity', 'xstore-core' ),
            'label'           => esc_html__( 'Quantity', 'xstore-core' ),
            'tooltip'   => esc_html__( 'Turn on/off to show/hide the quantity label for this element', 'xstore-core' ),
            'section'         => 'waitlist',
            'default'         => '1',
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'waitlist_quantity_et-desktop' => array(
                    'selector'        => '.et_b_header-waitlist.et_element-top-level',
                    'render_callback' => 'header_waitlist_callback'
                ),
            ),
        ),

        // waitlist_quantity_position
        'waitlist_quantity_position_et-desktop'                 => array(
            'name'            => 'waitlist_quantity_position_et-desktop',
            'type'            => 'radio-buttonset',
            'settings'        => 'waitlist_quantity_position_et-desktop',
            'label'           => esc_html__( 'Position', 'xstore-core' ),
            'tooltip'  => esc_html__('Choose the position of quantity label for this element.', 'xstore-core'),
            'section'         => 'waitlist',
            'default'         => 'right',
            'multiple'        => 1,
            'choices'         => array(
                'top'   => esc_html__( 'Top', 'xstore-core' ),
                'right' => esc_html__( 'Right', 'xstore-core' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'waitlist_quantity_et-desktop',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'postMessage',
            'js_vars'         => array(
                array(
                    'element'  => '.et_b_header-waitlist.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-quantity-right',
                    'value'    => 'right'
                ),
                array(
                    'element'  => '.et_b_header-waitlist.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-quantity-top',
                    'value'    => 'top'
                ),
            ),
        ),

        // waitlist_quantity_size
        'waitlist_quantity_size_et-desktop'                     => array(
            'name'            => 'waitlist_quantity_size_et-desktop',
            'type'            => 'slider',
            'settings'        => 'waitlist_quantity_size_et-desktop',
            'label'           => esc_html__( 'Quantity font size (em)', 'xstore-core' ),
            'tooltip'         => esc_html__( 'This option allows you to increase or decrease the size proportion of the quantity.', 'xstore-core' ),
            'section'         => 'waitlist',
            'default'         => 0.75,
            'choices'         => array(
                'min'  => '.3',
                'max'  => '3',
                'step' => '.01',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'waitlist_quantity_et-desktop',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level .et-quantity',
                    'property' => 'font-size',
                    'units'    => 'em'
                ),
            ),
        ),

        // waitlist_quantity_proportions
        'waitlist_quantity_proportions_et-desktop'              => array(
            'name'            => 'waitlist_quantity_proportions_et-desktop',
            'type'            => 'slider',
            'settings'        => 'waitlist_quantity_proportions_et-desktop',
            'label'           => esc_html__( 'Quantity background size (em)', 'xstore-core' ),
            'tooltip'         => esc_html__( 'This option allows you to increase or decrease the background size proportion of the quantity.', 'xstore-core' ),
            'section'         => 'waitlist',
            'default'         => 1.5,
            'choices'         => array(
                'min'  => '0.1',
                'max'  => '5',
                'step' => '0.1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'waitlist_quantity_et-desktop',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level .et-quantity',
                    'property' => '--et-quantity-proportion',
                    'units'    => 'em'
                ),
            ),
        ),

        // waitlist_quantity_active_background_custom
        'waitlist_quantity_active_background_custom_et-desktop' => array(
            'name'            => 'waitlist_quantity_active_background_custom_et-desktop',
            'type'            => 'color',
            'settings'        => 'waitlist_quantity_active_background_custom_et-desktop',
//			'label'           => esc_html__( 'Waitlist quantity Background (active)', 'xstore-core' ),
            'label'           => esc_html__( 'Quantity Background (active)', 'xstore-core' ),
            'tooltip'         => esc_html__( 'Choose the background color for the quantity label.', 'xstore-core' ),
            'section'         => 'waitlist',
            'choices'         => array(
                'alpha' => true
            ),
            'default'         => '#ffffff',
            'active_callback' => array(
                array(
                    'setting'  => 'waitlist_quantity_et-desktop',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level .et-quantity',
                    'property' => 'background-color',
                ),
            ),
        ),

        'waitlist_quantity_active_color_et-desktop' => array(
            'name'            => 'waitlist_quantity_active_color_et-desktop',
            'settings'        => 'waitlist_quantity_active_color_et-desktop',
            'label'           => esc_html__( 'WCAG waitlist quantity Color (active)', 'xstore-core' ),
            'label'           => $strings['label']['wcag_color'],
            'tooltip'     => $strings['description']['wcag_color'],
            'type'            => 'kirki-wcag-tc',
            'section'         => 'waitlist',
            'default'         => '#000000',
            'choices'         => array(
                'setting' => 'setting(waitlist)(waitlist_quantity_active_background_custom_et-desktop)',
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
                    'setting'  => 'waitlist_quantity_et-desktop',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level .et-quantity',
                    'property' => 'color'
                )
            )
        ),

        // style separator
        'waitlist_style_separator'                  => array(
            'name'     => 'waitlist_style_separator',
            'type'     => 'custom',
            'settings' => 'waitlist_style_separator',
            'section'  => 'waitlist',
            'default'  => $separators['style'],
            'priority' => 10,
        ),

        // waitlist_content_alignment
        'waitlist_content_alignment_et-desktop'     => array(
            'name'        => 'waitlist_content_alignment_et-desktop',
            'type'        => 'radio-buttonset',
            'settings'    => 'waitlist_content_alignment_et-desktop',
            'label'       => $strings['label']['alignment'],
            'label'       => $strings['label']['alignment'],
            'tooltip'     => $strings['description']['alignment'] . '<br/>'. $strings['description']['size_bigger_attention'],
            'section'     => 'waitlist',
            'default'     => 'start',
            'choices'     => $choices['alignment'],
            'transport'   => 'postMessage',
            'js_vars'     => array(
                array(
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a',
                    'function' => 'toggleClass',
                    'class'    => 'justify-content-start',
                    'value'    => 'start'
                ),
                array(
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a',
                    'function' => 'toggleClass',
                    'class'    => 'justify-content-center',
                    'value'    => 'center'
                ),
                array(
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a',
                    'function' => 'toggleClass',
                    'class'    => 'justify-content-end',
                    'value'    => 'end'
                ),
            ),
        ),

        // waitlist_content_alignment
        'waitlist_content_alignment_et-mobile'      => array(
            'name'        => 'waitlist_content_alignment_et-mobile',
            'type'        => 'radio-buttonset',
            'settings'    => 'waitlist_content_alignment_et-mobile',
            'label'       => $strings['label']['alignment'],
            'tooltip'     => $strings['description']['alignment'] . '<br/>'. $strings['description']['size_bigger_attention'],
            'section'     => 'waitlist',
            'default'     => 'start',
            'choices'     => $choices['alignment'],
            'transport'   => 'postMessage',
            'js_vars'     => array(
                array(
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a',
                    'function' => 'toggleClass',
                    'class'    => 'mob-justify-content-start',
                    'value'    => 'start'
                ),
                array(
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a',
                    'function' => 'toggleClass',
                    'class'    => 'mob-justify-content-center',
                    'value'    => 'center'
                ),
                array(
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a',
                    'function' => 'toggleClass',
                    'class'    => 'mob-justify-content-end',
                    'value'    => 'end'
                ),
            ),
        ),

        // waitlist_background
        'waitlist_background_et-desktop'            => array(
            'name'     => 'waitlist_background_et-desktop',
            'type'     => 'select',
            'settings' => 'waitlist_background_et-desktop',
            'label'    => $strings['label']['colors'],
            'tooltip'  => $strings['description']['colors'],
            'section'  => 'waitlist',
            'default'  => 'current',
            'choices'  => $choices['colors'],
            'output'   => array(
                array(
                    'context'       => array( 'editor', 'front' ),
                    'element'       => '.et_b_header-waitlist.et_element-top-level > a',
                    'property'      => 'color',
                    'value_pattern' => 'var(--$-color)'
                ),
            ),
        ),

        // waitlist_background_custom
        'waitlist_background_custom_et-desktop'     => array(
            'name'            => 'waitlist_background_custom_et-desktop',
            'type'            => 'color',
            'settings'        => 'waitlist_background_custom_et-desktop',
            'label'           => $strings['label']['bg_color'],
            'tooltip'         => $strings['description']['bg_color'],
            'section'         => 'waitlist',
            'choices'         => array(
                'alpha' => true
            ),
            'default'         => '#ffffff',
            'active_callback' => array(
                array(
                    'setting'  => 'waitlist_background_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a',
                    'property' => 'background-color',
                ),
            ),
        ),

        'waitlist_color_et-desktop'                     => array(
            'name'            => 'waitlist_color_et-desktop',
            'settings'        => 'waitlist_color_et-desktop',
            'label'           => $strings['label']['wcag_color'],
            'tooltip'         => $strings['description']['wcag_color'],
            'type'            => 'kirki-wcag-tc',
            'section'         => 'waitlist',
            'default'         => '#000000',
            'choices'         => array(
                'setting' => 'setting(waitlist)(waitlist_background_custom_et-desktop)',
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
                    'setting'  => 'waitlist_background_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a',
                    'property' => 'color'
                )
            ),
        ),

        // waitlist_overlay_background_custom
        'waitlist_overlay_background_custom_et-desktop' => array(
            'name'            => 'waitlist_overlay_background_custom_et-desktop',
            'type'            => 'color',
            'settings'        => 'waitlist_overlay_background_custom_et-desktop',
            'label'           => esc_html__( 'Item Background (hover)', 'xstore-core' ),
            'tooltip'         => $strings['description']['bg_color'],
            'section'         => 'waitlist',
            'choices'         => array(
                'alpha' => true
            ),
            'default'         => '',
            'active_callback' => array(
                array(
                    'setting'  => 'waitlist_content_type_et-desktop',
                    'operator' => '==',
                    'value'    => 'off_canvas',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.header-wrapper .et_b_header-waitlist.et-off-canvas .cart_list.product_list_widget li:hover',
                    'property' => 'background-color',
                ),
            ),
        ),

        // waitlist_border_radius
        'waitlist_border_radius_et-desktop'             => array(
            'name'      => 'waitlist_border_radius_et-desktop',
            'type'      => 'slider',
            'settings'  => 'waitlist_border_radius_et-desktop',
            'label'     => $strings['label']['border_radius'],
            'tooltip'   => $strings['description']['border_radius'],
            'section'   => 'waitlist',
            'default'   => 0,
            'choices'   => array(
                'min'  => '0',
                'max'  => '100',
                'step' => '1',
            ),
            'transport' => 'auto',
            'output'    => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a',
                    'property' => 'border-radius',
                    'units'    => 'px'
                )
            )
        ),

        'waitlist_box_model_et-desktop' => array(
            'name'        => 'waitlist_box_model_et-desktop',
            'settings'    => 'waitlist_box_model_et-desktop',
            'label'       => $strings['label']['computed_box'],
            'tooltip' => $strings['description']['computed_box'],
            'type'        => 'kirki-box-model',
            'section'     => 'waitlist',
            'default'     => array(
                'margin-top'          => '0px',
                'margin-right'        => '0px',
                'margin-bottom'       => '0px',
                'margin-left'         => '0px',
                'border-top-width'    => '0px',
                'border-right-width'  => '0px',
                'border-bottom-width' => '0px',
                'border-left-width'   => '0px',
                'padding-top'         => '5px',
                'padding-right'       => '0px',
                'padding-bottom'      => '5px',
                'padding-left'        => '0px',
            ),
            'output'      => array(
                array(
                    'context' => array( 'editor', 'front' ),
                    'element' => '.et_b_header-waitlist.et_element-top-level > a'
                ),
            ),
            'transport'   => 'postMessage',
            'js_vars'     => box_model_output( '.et_b_header-waitlist.et_element-top-level > a' )
        ),

        'waitlist_box_model_et-mobile'                   => array(
            'name'        => 'waitlist_box_model_et-mobile',
            'settings'    => 'waitlist_box_model_et-mobile',
            'label'       => $strings['label']['computed_box'],
            'tooltip' => $strings['description']['computed_box'],
            'type'        => 'kirki-box-model',
            'section'     => 'waitlist',
            'default'     => $box_models['empty'],
            'output'      => array(
                array(
                    'context' => array( 'editor', 'front' ),
                    'element' => '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level > a'
                ),
            ),
            'transport'   => 'postMessage',
            'js_vars'     => box_model_output( '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level > a' )
        ),

        // waitlist_border
        'waitlist_border_et-desktop'                     => array(
            'name'      => 'waitlist_border_et-desktop',
            'type'      => 'select',
            'settings'  => 'waitlist_border_et-desktop',
            'label'     => $strings['label']['border_style'],
            'tooltip'   => $strings['description']['border_style'],
            'section'   => 'waitlist',
            'default'   => 'solid',
            'choices'   => $choices['border_style'],
            'transport' => 'auto',
            'output'    => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a',
                    'property' => 'border-style',
                ),
            ),
        ),

        // waitlist_border_color_custom
        'waitlist_border_color_custom_et-desktop'        => array(
            'name'        => 'waitlist_border_color_custom_et-desktop',
            'type'        => 'color',
            'settings'    => 'waitlist_border_color_custom_et-desktop',
            'label'       => $strings['label']['border_color'],
            'tooltip' => $strings['description']['border_color'],
            'section'     => 'waitlist',
            'default'     => '#e1e1e1',
            'choices'     => array(
                'alpha' => true
            ),
            'transport'   => 'auto',
            'output'      => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level > a',
                    'property' => 'border-color',
                ),
            ),
        ),

        // content separator
        'waitlist_content_dropdown_separator'            => array(
            'name'     => 'waitlist_content_dropdown_separator',
            'type'     => 'custom',
            'settings' => 'waitlist_content_dropdown_separator',
            'section'  => 'waitlist',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-editor-outdent"></span> <span style="padding-inline-start: 5px;">' . esc_html__( 'Mini-waitlist Dropdown', 'xstore-core' ) . '</span></div>',
            'priority' => 10,
        ),

        // waitlist_zoom
        'waitlist_zoom_et-desktop'                       => array(
            'name'      => 'waitlist_zoom_et-desktop',
            'type'      => 'slider',
            'settings'  => 'waitlist_zoom_et-desktop',
//			'label'     => esc_html__( 'Mini-waitlist Content size (%)', 'xstore-core' ),
            'label'     => $strings['label']['content_size'],
            'tooltip'   => $strings['description']['content_zoom'],
            'section'   => 'waitlist',
            'default'   => 100,
            'choices'   => array(
                'min'  => '10',
                'max'  => '200',
                'step' => '1',
            ),
            'transport' => 'auto',
            'output'    => array(
                array(
                    'context'       => array( 'editor', 'front' ),
                    'element'       => '.et_b_header-waitlist.et_element-top-level .et-mini-content',
                    'property'      => '--content-zoom',
                    'value_pattern' => 'calc($em * .01)'
                ),
            ),
        ),

        // waitlist_zoom
        'waitlist_zoom_et-mobile'                        => array(
            'name'      => 'waitlist_zoom_et-mobile',
            'type'      => 'slider',
            'settings'  => 'waitlist_zoom_et-mobile',
//			'label'     => esc_html__( 'Mini-waitlist Content size (%)', 'xstore-core' ),
            'label'     => $strings['label']['content_size'],
            'tooltip'   => $strings['description']['content_zoom'],
            'section'   => 'waitlist',
            'default'   => 100,
            'choices'   => array(
                'min'  => '10',
                'max'  => '200',
                'step' => '1',
            ),
            'transport' => 'auto',
            'output'    => array(
                array(
                    'element'       => '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level .et-mini-content',
                    'property'      => '--content-zoom',
                    'value_pattern' => 'calc($em * .01)'
                ),
            ),
        ),

        // waitlist_dropdown_position
        'waitlist_dropdown_position_et-desktop'          => array(
            'name'            => 'waitlist_dropdown_position_et-desktop',
            'type'            => 'radio-buttonset',
            'settings'        => 'waitlist_dropdown_position_et-desktop',
//            'label'           => esc_html__( 'Mini-waitlist Dropdown position', 'xstore-core' ),
            'label'           => esc_html__( 'Dropdown position', 'xstore-core' ),
            'tooltip'         => esc_html__( 'Choose the position for dropdown content.', 'xstore-core' ),
            'section'         => 'waitlist',
            'default'         => 'right',
            'multiple'        => 1,
            'choices'         => $choices['dropdown_position'],
            'active_callback' => array(
                array(
                    'setting'  => 'waitlist_content_type_et-desktop',
                    'operator' => '==',
                    'value'    => 'dropdown',
                ),
            ),
            'transport'       => 'postMessage',
            'js_vars'         => array(
                array(
                    'element'  => '.et_b_header-waitlist',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-right',
                    'value'    => 'right'
                ),
                array(
                    'element'  => '.et_b_header-waitlist',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-left',
                    'value'    => 'left'
                ),
            ),
        ),

        // waitlist_dropdown_position_custom
        'waitlist_dropdown_position_custom_et-desktop'   => array(
            'name'            => 'waitlist_dropdown_position_custom_et-desktop',
            'type'            => 'slider',
            'settings'        => 'waitlist_dropdown_position_custom_et-desktop',
//			'label'           => esc_html__( 'Mini-waitlist Dropdown offset', 'xstore-core' ),
            'label'           => esc_html__( 'Dropdown offset', 'xstore-core' ),
            'tooltip'         => esc_html__( 'Set the offset position for dropdown content.', 'xstore-core' ),
            'section'         => 'waitlist',
            'default'         => 0,
            'choices'         => array(
                'min'  => '-300',
                'max'  => '300',
                'step' => '1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'waitlist_content_type_et-desktop',
                    'operator' => '==',
                    'value'    => 'dropdown',
                ),
                array(
                    'setting'  => 'waitlist_dropdown_position_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level.et-content-toTop .et-mini-content',
                    'property' => 'right',
                    'units'    => 'px'
                ),
            ),
        ),

        // waitlist_dropdown_background_custom
        'waitlist_dropdown_background_custom_et-desktop' => array(
            'name'      => 'waitlist_dropdown_background_custom_et-desktop',
            'type'      => 'color',
            'settings'  => 'waitlist_dropdown_background_custom_et-desktop',
            'label'   => $strings['label']['bg_color'],
            'tooltip'   => $strings['description']['bg_color'],
            'section'   => 'waitlist',
            'choices'   => array(
                'alpha' => true
            ),
            'default'   => '#ffffff',
            'transport' => 'auto',
            'output'    => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level .et-mini-content, .et_b_mobile-panel-waitlist .et-mini-content',
                    'property' => 'background-color',
                ),
            ),
        ),

        'waitlist_dropdown_color_et-desktop'              => array(
            'name'        => 'waitlist_dropdown_color_et-desktop',
            'settings'    => 'waitlist_dropdown_color_et-desktop',
//			'label'       => esc_html__( 'Mini-waitlist WCAG Color', 'xstore-core' ),
            'label'           => $strings['label']['wcag_color'],
            'tooltip'         => $strings['description']['wcag_color'],
            'type'        => 'kirki-wcag-tc',
            'section'     => 'waitlist',
            'default'     => '#000000',
            'choices'     => array(
                'setting' => 'setting(waitlist)(waitlist_dropdown_background_custom_et-desktop)',
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
            'transport'   => 'auto',
            'output'      => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist.et_element-top-level .et-mini-content, .et_b_mobile-panel-waitlist .et-mini-content',
                    'property' => 'color'
                )
            ),
        ),

        // canvas type

        // waitlist_content_position
        'waitlist_content_position_et-desktop'            => array(
            'name'        => 'waitlist_content_position_et-desktop',
            'type'        => 'radio-buttonset',
            'settings'    => 'waitlist_content_position_et-desktop',
            'label'       => esc_html__( 'Position', 'xstore-core' ),
            'tooltip' => esc_html__( 'Choose the position for off-canvas content.', 'xstore-core' ) . '<br/>' .
                esc_html__( 'Note: this option will work only if content type is set to Off-Canvas.', 'xstore-core' ),
            'section'     => 'waitlist',
            'default'     => 'right',
            'multiple'    => 1,
            'choices'     => array(
                'left'  => esc_html__( 'Left side', 'xstore-core' ),
                'right' => esc_html__( 'Right side', 'xstore-core' ),
            ),
            'transport'   => 'postMessage',
            'js_vars'     => array(
                array(
                    'element'  => '.header-wrapper .et_b_header-waitlist.et_element-top-level.et-off-canvas .et-close',
                    'function' => 'toggleClass',
                    'class'    => 'full-right',
                    'value'    => 'right'
                ),
                array(
                    'element'  => '.header-wrapper .et_b_header-waitlist.et_element-top-level.et-off-canvas .et-close',
                    'function' => 'toggleClass',
                    'class'    => 'full-left',
                    'value'    => 'left'
                ),
                array(
                    'element'  => '.header-wrapper .et_b_header-waitlist.et_element-top-level.et-off-canvas',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-right',
                    'value'    => 'right'
                ),
                array(
                    'element'  => '.header-wrapper .et_b_header-waitlist.et_element-top-level.et-off-canvas',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-left',
                    'value'    => 'left'
                ),
            ),
        ),

        // waitlist_content_position
        'waitlist_content_position_et-mobile'             => array(
            'name'        => 'waitlist_content_position_et-mobile',
            'type'        => 'radio-buttonset',
            'settings'    => 'waitlist_content_position_et-mobile',
            'label'       => esc_html__( 'Position', 'xstore-core' ),
            'tooltip' => esc_html__( 'Choose the position for off-canvas content.', 'xstore-core' ) . '<br/>' .
                esc_html__( 'Note: this option will work only if content type is set to Off-Canvas.', 'xstore-core' ),
            'section'     => 'waitlist',
            'default'     => 'right',
            'multiple'    => 1,
            'choices'     => array(
                'left'  => esc_html__( 'Left side', 'xstore-core' ),
                'right' => esc_html__( 'Right side', 'xstore-core' ),
            ),
            'transport'   => 'postMessage',
            'js_vars'     => array(
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level.et-off-canvas .et-close, .et-mobile-panel .et_b_mobile-panel-waitlist.et-off-canvas .et-close',
                    'function' => 'toggleClass',
                    'class'    => 'full-right',
                    'value'    => 'right'
                ),
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level.et-off-canvas .et-close, .et-mobile-panel .et_b_mobile-panel-waitlist.et-off-canvas .et-close',
                    'function' => 'toggleClass',
                    'class'    => 'full-left',
                    'value'    => 'left'
                ),
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level.et-off-canvas, .et-mobile-panel .et_b_mobile-panel-waitlist.et-off-canvas',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-right',
                    'value'    => 'right'
                ),
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-waitlist.et_element-top-level.et-off-canvas, .et-mobile-panel .et_b_mobile-panel-waitlist.et-off-canvas',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-left',
                    'value'    => 'left'
                ),
            ),
        ),
        'waitlist_content_box_model_et-desktop'           => array(
            'name'        => 'waitlist_content_box_model_et-desktop',
            'settings'    => 'waitlist_content_box_model_et-desktop',
//			'label'       => esc_html__( 'Mini-waitlist Computed box', 'xstore-core' ),
            'label' => $strings['label']['computed_box'],
            'tooltip' => $strings['description']['computed_box'],
            'type'        => 'kirki-box-model',
            'section'     => 'waitlist',
            'default'     => array(
                'margin-top'          => '0px',
                'margin-right'        => '0px',
                'margin-bottom'       => '0px',
                'margin-left'         => '0px',
                'border-top-width'    => '0px',
                'border-right-width'  => '0px',
                'border-bottom-width' => '0px',
                'border-left-width'   => '0px',
                'padding-top'         => '30px',
                'padding-right'       => '30px',
                'padding-bottom'      => '30px',
                'padding-left'        => '30px',
            ),
            'output'      => array(
                array(
                    'context' => array( 'editor', 'front' ),
                    'element' => '.et_b_header-waitlist.et_element-top-level .et-mini-content, .et-mobile-panel .et_b_mobile-panel-waitlist .et-mini-content',
                ),
            ),
            'transport'   => 'postMessage',
            'js_vars'     => box_model_output( '.et_b_header-waitlist.et_element-top-level .et-mini-content, .et-mobile-panel .et_b_mobile-panel-waitlist .et-mini-content' )
        ),

        // waitlist_content_border
        'waitlist_content_border_et-desktop'              => array(
            'name'      => 'waitlist_content_border_et-desktop',
            'type'      => 'select',
            'settings'  => 'waitlist_content_border_et-desktop',
            'label' => $strings['label']['border_style'],
            'tooltip' => $strings['description']['border_style'],
            'section'   => 'waitlist',
            'default'   => 'solid',
            'choices'   => $choices['border_style'],
            'transport' => 'auto',
            'output'    => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist .et-mini-content, .et-mobile-panel .et_b_mobile-panel-waitlist .et-mini-content',
                    'property' => 'border-style',
                ),
            ),
        ),

        // waitlist_content_border_color_custom
        'waitlist_content_border_color_custom_et-desktop' => array(
            'name'        => 'waitlist_content_border_color_custom_et-desktop',
            'type'        => 'color',
            'settings'    => 'waitlist_content_border_color_custom_et-desktop',
//			'label'       => esc_html__( 'Mini-waitlist Border color', 'xstore-core' ),
            'label' => $strings['label']['border_color'],
            'tooltip' => $strings['description']['border_color'],
            'section'     => 'waitlist',
            'default'     => '#e1e1e1',
            'choices'     => array(
                'alpha' => true
            ),
            'transport'   => 'auto',
            'output'      => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-waitlist .et-mini-content, .et_b_header-waitlist .cart-widget-products, .et_b_header-waitlist.et-off-canvas .product_list_widget li:not(:last-child), .et_b_mobile-panel-waitlist .et-mini-content, .et_b_mobile-panel-waitlist .cart-widget-products, .et_b_mobile-panel-waitlist.et-off-canvas .product_list_widget li:not(:last-child)',
                    'property' => 'border-color',
                ),
            ),
        )

    );

    return array_merge( $fields, $args );

} );
