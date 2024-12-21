<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Product Images widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Product_Images extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_product_images';
	}

	/**
	 * Get widget title.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Product Images', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-featured-image et-elementor-product-widget-icon-only';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'image', 'product', 'carousel', 'gallery', 'lightbox', 'zoom' ];
	}

    /**
     * Get widget categories.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
    	return ['woocommerce-elements-single'];
    }
	
	/**
	 * Get widget dependency.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
        return [
            'etheme-single-product-images'
        ];
	}

    /**
     * Get widget dependency.
     *
     * @since 4.1.4
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_script_depends() {
        $scripts = [
            'et_single_product',
            'et_single_product_builder'
        ];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            $scripts[] = 'et_slick_slider';
            $scripts[] = 'et_single_product_vertical_gallery';
            $scripts[] = 'zoom';
        }
        return $scripts;
    }
	
	/**
	 * Help link.
	 *
	 * @since 4.1.5
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('122-elementor-live-copy-option', false);
	}

	/**
	 * Register widget controls.
	 *
	 * @since 5.2
	 * @access protected
	 */
	protected function register_controls() {
        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'type',
            [
                'label'       => esc_html__( 'Type', 'xstore-core' ),
                'description' => esc_html__( 'With this option, you can choose the type of gallery displayed on your individual product pages. Each gallery has its own beauty and unique design, so give each of them a try and see the difference for yourself.', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'basic' => esc_html__('Basic', 'xstore-core'),
                    'thumbnails_bottom' => esc_html__('Thumbnails bottom', 'xstore-core'),
                    'thumbnails_bottom_inside' => esc_html__('Thumbnails bottom inside', 'xstore-core'),
                    'thumbnails_left' => esc_html__('Thumbnails left', 'xstore-core'),
                    'one_image' => esc_html__('One image', 'xstore-core'),
                    'double_image' => esc_html__('Double image', 'xstore-core'),
                    'full_width' => esc_html__('Full width', 'xstore-core'),
                ],
                'default' => 'thumbnails_bottom',
            ]
        );

        $this->add_control(
            'type_mobile',
            [
                'label'       => esc_html__( 'Type on mobile', 'xstore-core' ),
                'description' => sprintf(esc_html__( 'With this option, you can choose the type of gallery displayed on your individual product pages on mobile device. will use the WordPress function "%1s" to identify mobile devices. However, the "wp_is_mobile()" function may conflict with cache plugins.', 'xstore-core' ),
                    '<a href="https://developer.wordpress.org/reference/functions/wp_is_mobile/" target="_blank" rel="nofollow">wp_is_mobile()</a>'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'basic' => esc_html__('Basic', 'xstore-core'),
                    'thumbnails_bottom' => esc_html__('Thumbnails bottom', 'xstore-core'),
                    'thumbnails_bottom_inside' => esc_html__('Thumbnails bottom inside', 'xstore-core'),
//                    'thumbnails_left' => esc_html__('Thumbnails left', 'xstore-core'),
                    'one_image' => esc_html__('One image', 'xstore-core'),
                    'double_image' => esc_html__('Double image', 'xstore-core'),
//                    'full_width' => esc_html__('Full width', 'xstore-core'),
                ],
                'condition' => [
                    'type' => ['thumbnails_left', 'one_image', 'double_image']
                ],
                'default' => 'thumbnails_bottom',
            ]
        );

        $this->add_control(
            'overflow',
            [
                'label' 		=>	__( 'Overflow visible', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'type' => 'full_width',
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-container' => 'overflow: visible;',
                ],
            ]
        );

        $this->add_control(
            'sale_flash',
            [
                'label' => esc_html__( 'Sale Flash', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'sale_flash_position',
            [
                'label'       => esc_html__( 'Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'left'  => esc_html__( 'Left', 'xstore-core' ),
                    'right' => esc_html__( 'Right', 'xstore-core' ),
                ],
                'default' => 'left',
                'condition' => [
                    'sale_flash!' => ''
                ]
            ]
        );

        $this->add_control(
            'sale_flash_position_axis',
            [
                'label' => esc_html__( 'Axis', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0
                    ],
                ],
                'condition' => [
                    'sale_flash!' => ''
                ],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .onsale' => 'top: {{SIZE}}{{UNIT}}',
                    'body:not(.rtl) {{WRAPPER}} .onsale.left' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                    'body.rtl {{WRAPPER}} .onsale.left' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                    'body:not(.rtl) {{WRAPPER}} .onsale.right' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                    'body.rtl {{WRAPPER}} .onsale.right' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ],
            ]
        );

        $this->add_control(
            'zoom',
            [
                'label' => esc_html__( 'Zoom', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'open_lightbox',
            [
                'label' => esc_html__( 'Open Lightbox', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'yes',
                'options' => [
                    'yes' => esc_html__( 'Yes', 'xstore-core' ),
                    'no' => esc_html__( 'No', 'xstore-core' ),
                ],
            ]
        );

        $this->end_controls_section();

        // slider global settings
        Elementor::get_slider_general_settings($this);

        $slider_conditions = [
            'relation' => 'or',
            'terms' => [
                [
                    'name' 		=> 'type',
                    'operator'  => '!in',
                    'value' 	=> ['one_image', 'double_image']
                ],
                [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' 		=> 'type',
                            'operator'  => 'in',
                            'value' 	=> ['one_image', 'double_image']
                        ],
                        [
                            'name' 		=> 'type_mobile',
                            'operator'  => '!in',
                            'value' 	=> ['one_image', 'double_image']
                        ],
                    ],
                ],
            ],
        ];
        $this->update_control( 'section_slider', [
            'conditions' => $slider_conditions,
        ] );
        $this->update_control( 'section_slider_navigation', [
            'conditions' => $slider_conditions,
        ] );

        $items_per_view = range( 1, 5 );
        $items_per_view = array_combine( $items_per_view, $items_per_view );

        $this->start_injection( [
            'type' => 'section',
            'at' => 'start',
            'of' => 'section_slider',
        ] );

        $this->add_control(
            'effect',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => __( 'Effect', 'xstore-core' ),
                'default' => 'coverflow',
                'options' => [
                    'slide'  => __('Slide', 'xstore-core'),
                    'fade' => __('Fade', 'xstore-core'),
//                    'cube' => __('Cube', 'xstore-core'),
                    'coverflow' => __('Coverflow', 'xstore-core'),
                    'flip' => __('Flip', 'xstore-core'),
                ],
                'condition' => [
                    'type!' => 'full_width'
                ]
            ]
        );

        $this->end_injection();

        $this->update_control( 'space_between', [
            'default' => [
                'size' => 10
            ],
        ] );

        $this->update_control( 'slides_per_view', [
            'options' => [ '' => __( 'Default', 'xstore-core' ) ] + $items_per_view,
            'default' 	=>	'1',
//            'condition' => [
//                'effect' => ['slide', 'coverflow'],
//                'type!' => 'full_width'
//            ],
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'name' 		=> 'type',
                        'operator'  => '=',
                        'value' 	=> 'full_width'
                    ],
                    [
                        'name' 		=> 'effect',
                        'operator'  => 'in',
                        'value' 	=> ['slide', 'coverflow']
                    ],
                ],
            ],
        ] );

        $this->update_control('loop', [
            'condition' => [
                'type' => 'basic'
            ],
        ]);

        $this->update_control('autoheight', [
            'default' => 'yes'
        ]);

        $this->update_control('arrows_position_style', [
            'condition' => [
                'navigation' => ['both', 'arrows'],
            ]
        ]);

        $this->update_control('navigation', [
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'name' 		=> 'type',
                        'operator'  => '!in',
                        'value' 	=> ['one_image', 'double_image']
                    ],
                    [
                        'relation' => 'and',
                        'terms' => [
                            [
                                'name' 		=> 'type',
                                'operator'  => 'in',
                                'value' 	=> ['one_image', 'double_image']
                            ],
                            [
                                'name' 		=> 'type_mobile',
                                'operator'  => '!in',
                                'value' 	=> ['one_image', 'double_image']
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $remove_controls_list = array(
            'slides_per_group',
            'space_between',
            'slider_vertical_align',
            'autoplay',
            'autoplay_speed',
            'pause_on_hover',
            'pause_on_interaction',
            'arrows_position'
        );
        foreach ($remove_controls_list as $remove_control) {
            $this->remove_control($remove_control);
        }

        $this->start_injection( [
            'type' => 'control',
            'at' => 'after',
            'of' => 'slides_per_view',
        ] );

        $this->add_control(
            'space_between',
            [
                'label' => esc_html__( 'Space Between', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0
                    ],
                ],
                'condition' => [
                    'effect' => ['slide', 'coverflow']
                ],
            ]
        );

//        $this->add_control(
//            'overflow',
//            [
//                'label' 		=>	__( 'Overflow visible', 'xstore-core' ),
//                'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
//                'condition' => [
//                    'effect' => ['slide']
//                ],
//                'render_type' => 'template', // reinit slider js to create few extra duplicate slides if loop mode
//                'selectors' => [
//                    '{{WRAPPER}} .swiper-control-top' => 'overflow: visible;',
//                ],
//            ]
//        );

        $this->end_injection();

        $this->start_controls_section(
            'section_thumb_slider',
            [
                'label' => esc_html__( 'Thumbnails Slider', 'xstore-core' ),
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' 		=> 'type',
                            'operator'  => 'in',
                            'value' 	=> [
                                'thumbnails_bottom',
                                'thumbnails_bottom_inside',
                                'thumbnails_left',
                            ]
                        ],
                        [
                            'name' 		=> 'type_mobile',
                            'operator'  => 'in',
                            'value' 	=> [
                                'thumbnails_bottom',
                                'thumbnails_bottom_inside',
                                'thumbnails_left',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbs_slides_per_view',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => __( 'Slides Per View', 'xstore-core' ),
                'options' => [ '' => __( 'Default', 'xstore-core' ) ] + $items_per_view,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' 		=> 'type',
                            'operator'  => 'in',
                            'value' 	=> [
                                'thumbnails_bottom',
                                'thumbnails_bottom_inside',
                                'thumbnails_left',
                            ]
                        ],
                        [
                            'name' 		=> 'type_mobile',
                            'operator'  => 'in',
                            'value' 	=> [
                                'thumbnails_bottom',
                                'thumbnails_bottom_inside',
                                'thumbnails_left',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'thumbs_space_between',
            [
                'label' => esc_html__( 'Space Between', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0
                    ],
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' 		=> 'type',
                            'operator'  => 'in',
                            'value' 	=> [
                                'thumbnails_bottom',
                                'thumbnails_bottom_inside',
                                'thumbnails_left',
                            ]
                        ],
                        [
                            'name' 		=> 'type_mobile',
                            'operator'  => 'in',
                            'value' 	=> [
                                'thumbnails_bottom',
                                'thumbnails_bottom_inside',
                                'thumbnails_left',
                            ]
                        ],
                    ],
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .swiper-vertical-images .vertical-thumbnails-wrapper li' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbs_space_before',
            [
                'label' => esc_html__( 'Space Before', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0
                    ],
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' 		=> 'type',
                            'operator'  => '=',
                            'value' 	=> 'thumbnails_bottom',
                        ],
                        [
                            'name' 		=> 'type_mobile',
                            'operator'  => '=',
                            'value' 	=> 'thumbnails_bottom',
                        ],
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-control-bottom' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'thumbs_autoheight',
            [
                'label' => esc_html__( 'Auto Height', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' 		=> 'type',
                            'operator'  => 'in',
                            'value' 	=> [
                                'thumbnails_bottom',
                                'thumbnails_bottom_inside',
                            ]
                        ],
                        [
                            'name' 		=> 'type_mobile',
                            'operator'  => 'in',
                            'value' 	=> [
                                'thumbnails_bottom',
                                'thumbnails_bottom_inside',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'thumbs_slide_opacity',
            [
                'label' => __( 'Slide Opacity', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .thumbnails-list li:not(.active-thumbnail), {{WRAPPER}} .swiper-container.swiper-control-bottom.second-initialized li.thumbnail-item:not(.active-thumbnail)' => 'opacity: {{SIZE}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' 		=> 'type',
                            'operator'  => 'in',
                            'value' 	=> [
                                'thumbnails_bottom',
                                'thumbnails_bottom_inside',
                                'thumbnails_left',
                            ]
                        ],
                        [
                            'name' 		=> 'type_mobile',
                            'operator'  => 'in',
                            'value' 	=> [
                                'thumbnails_bottom',
                                'thumbnails_bottom_inside',
                                'thumbnails_left',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_stretch',
            [
                'label' => __('Stretch Images', 'xstore-core'),
                'type'  => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .woocommerce-product-gallery img' => 'width: 100%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'images_spacing',
            [
                'label' => esc_html__( 'Space between', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px'
                ],
                'condition' => [
                    'type' => [
                        'one_image',
                        'double_image',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .one_image .main-images > div, {{WRAPPER}} .one_image .main-images > img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .one_image .main-images' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .double_image .main-images > div' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}; width: calc(50% - {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .double_image .main-images' => 'margin-right: -{{SIZE}}{{UNIT}}; margin-bottom: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '.woocommerce {{WRAPPER}} .images-wrapper.woocommerce-product-gallery:not(.double_image, .one_image), .woocommerce {{WRAPPER}} .woocommerce-product-gallery:is(.double_image, .one_image) .woocommerce-product-gallery',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .woocommerce-product-gallery' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_control(
            'heading_thumbs_style',
            [
                'label' => esc_html__( 'Thumbnails', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'type!' => [
                        'one_image',
                        'double_image',
                        'basic'
                    ],
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'thumbs_border',
                'selector' => '.woocommerce {{WRAPPER}} .thumbnail-item',
                'condition' => [
                    'type!' => [
                        'one_image',
                        'double_image',
                        'basic'
                    ],
                ],
            ]
        );

        $this->add_control(
            'thumbs_hover_border_color',
            [
                'label' => __( 'Active Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'thumbs_border_border!' => '',
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .thumbnail-item.active-thumbnail' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbs_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .thumbnail-item, .woocommerce {{WRAPPER}} .thumbnail-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
                'condition' => [
                    'type!' => [
                        'one_image',
                        'double_image',
                        'basic'
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        Elementor::get_slider_style_settings($this);

        $this->update_control( 'section_style_slider', [
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'name' 		=> 'type',
                        'operator'  => '!in',
                        'value' 	=> ['one_image', 'double_image']
                    ],
                    [
                        'relation' => 'and',
                        'terms' => [
                            [
                                'name' 		=> 'type',
                                'operator'  => 'in',
                                'value' 	=> ['one_image', 'double_image']
                            ],
                            [
                                'name' 		=> 'type_mobile',
                                'operator'  => '!in',
                                'value' 	=> ['one_image', 'double_image']
                            ],
                        ],
                    ],
                ],
            ],
        ] );

        $remove_controls_list = array(
            'arrows_hide_desktop',
            'arrows_hide_mobile',
            'dots_hide_desktop',
            'dots_hide_mobile'
        );
        foreach ($remove_controls_list as $remove_control) {
            $this->remove_control($remove_control);
        }

        $this->update_responsive_control('arrows_size', [
            'selectors' => [
                '{{WRAPPER}} .swiper-entry .swiper-container' => '--arrow-size: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $remove_responsive_controls_list = array(
            'arrows_space',
            'arrows_top_space',
        );
        foreach ($remove_responsive_controls_list as $remove_control) {
            $this->remove_responsive_control($remove_control);
        }
		
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 5.2
	 * @access protected
	 */
	protected function render() {
        global $product;

        $product = Elementor::get_product();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( !defined('ETHEME_THEME_VERSION') ) {
            echo Elementor::elementor_frontend_alert_message(esc_html__('To use this widget, please, activate XStore Theme', 'xstore-core'));
            return;
        }
        if ( ! $product ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message();
            }
            return;
        }

		$settings = $this->get_settings_for_display();
        if ( in_array($settings['type'], array('thumbnails_left', 'one_image', 'double_image')) && wp_is_mobile() )
            $settings['type'] = $settings['type_mobile'];

        switch ($settings['type']) {
            case 'thumbnails_left':
                wp_enqueue_script( 'et_slick_slider');
                wp_enqueue_script( 'et_single_product_vertical_gallery');
                break;
            case 'basic':
                add_filter('etheme_product_thumbnails', '__return_false');
                break;
            default:
                set_query_var('etheme_single_product_vertical_slider', false);
            break;
        }
        add_filter('single_product_main_gallery_effect', function ($value) use ($settings) {
            return $settings['effect'];
        });

        add_filter('etheme_sale_label_single', '__return_true');

        if ( !!!$settings['sale_flash'] )
            add_filter('etheme_product_gallery_sale_flash', '__return_false');
        else {
            add_filter('etheme_sale_label_position', function ($position) use ($settings) {
                return $settings['sale_flash_position'];
            });
        }
        if ( $settings['type'] == 'full_width' || in_array($settings['effect'], array('slide', 'coverflow'))) {
            add_filter('etheme_product_gallery_slides', function ($value) use ($settings) {
                $responsive_slides = array();
                if ( $settings['slides_per_view'] )
                    $responsive_slides['large'] = $settings['slides_per_view'];
                if ( $settings['slides_per_view'] )
                    $responsive_slides['notebook'] = $settings['slides_per_view'];
                if ( $settings['slides_per_view_tablet'] )
                    $responsive_slides['tablet_land'] = $settings['slides_per_view_tablet'];
                if ( $settings['slides_per_view_mobile'] )
                    $responsive_slides['mobile'] = $settings['slides_per_view_mobile'];
                return wp_parse_args($responsive_slides, $value);
            });
        }

        add_filter('etheme_product_gallery_lightbox', '__return_false');

        add_action('etheme_before_single_product_image', function () {
            add_filter('etheme_single_product_image_link_attributes_rendered', array($this, 'product_link_attributes'), 10, 2);
        }, 1);
//
        add_action('etheme_after_single_product_image', function () {
            remove_filter('etheme_single_product_image_link_attributes_rendered', array($this, 'product_link_attributes'), 10, 2);
        }, 10);

        add_filter('etheme_product_gallery_spacing', function ($value) use ($settings) {
            return isset($settings['space_between']['size']) && $settings['space_between']['size'] ? $settings['space_between']['size'] : 10;
        });
        if ( !!$settings['loop'] )
            add_filter('etheme_product_gallery_loop', '__return_true');

        add_filter('single_product_main_gallery_autoheight', (!!$settings['autoheight'] ? '__return_true' : '__return_false'));

        if (!in_array($settings['navigation'], array('both', 'arrows')))
            add_filter('etheme_product_gallery_arrows', '__return_false');

        add_filter('etheme_product_gallery_pagination', (in_array($settings['navigation'], array('both', 'dots')) ? '__return_true' : '__return_false'));

        if ( $settings['arrows_type'] != 'arrow') {
            add_filter('etheme_product_gallery_arrows_type', function ($value) use ($settings) {
                return $settings['arrows_type'];
            });
        }

        add_filter('etheme_product_gallery_arrows_style', function ($value) use ($settings) {
            return $settings['arrows_style'];
        });

        if ( $settings['arrows_position_style'] == 'arrows-always' )
            add_filter('etheme_product_gallery_arrows_always', '__return_true');

        add_filter('etheme_product_gallery_type', function ($value) use ($settings) {
        	return $settings['type'];
		});

        add_filter('etheme_product_gallery_zoom', (!!$settings['zoom'] ? '__return_true' : '__return_false'));
        if ( !!$settings['zoom'] ) {
            wp_enqueue_script('zoom');
        }

        if ( $settings['thumbs_space_between'] && $settings['thumbs_space_between']['size'] ) {
            add_filter('etheme_product_gallery_thumb_spacing', function ($value) use ($settings) {
                return $settings['thumbs_space_between']['size'];
            });
        }
        add_filter('etheme_product_gallery_thumbnails_slider', '__return_true');
        if ( $settings['thumbs_slides_per_view'] ) {
            add_filter('etheme_product_gallery_thumb_slides', function ($value) use ($settings) {
                $responsive_slides = array();
                if ($settings['thumbs_slides_per_view']) {
                    $responsive_slides['large'] = $settings['thumbs_slides_per_view'];
                    $responsive_slides['notebook'] = $settings['thumbs_slides_per_view'];
                }
                if ( isset($settings['thumbs_slides_per_view_tablet']) && $settings['thumbs_slides_per_view_tablet'] )
                    $responsive_slides['tablet_land'] = $settings['thumbs_slides_per_view_tablet'];
                if ( isset($settings['thumbs_slides_per_view_mobile']) && $settings['thumbs_slides_per_view_mobile'] )
                    $responsive_slides['mobile'] = $settings['thumbs_slides_per_view_mobile'];
                return wp_parse_args($responsive_slides, $value);
            });
        }

		if ( $edit_mode ) {
			add_filter('etheme_should_reinit_swiper_script', '__return_true');
			add_filter('etheme_elementor_edit_mode', '__return_true');
		}

		set_query_var('etheme_single_product_builder', true);

        add_filter('etheme_elementor_theme_builder', '__return_true');

		wc_get_template( 'single-product/product-image-builder.php' );

        remove_filter('etheme_elementor_theme_builder', '__return_true');

        remove_filter('etheme_sale_label_single', '__return_true');

        // On render widget from Editor - trigger the init manually.
        if ( $edit_mode ) {
            ?>
            <script>
                jQuery( '.woocommerce-product-gallery' ).each( function() {
                    jQuery( this ).wc_product_gallery({'zoom_enabled': <?php echo !!$settings['zoom']; ?>});
                } );
                // force init classes in editor mode only
                jQuery('[data-id="<?php echo $this->get_id(); ?>"] .swiper-control-top').addClass('swiper-container-initialized');
            </script>
            <?php
        }

    }

    public function product_link_attributes($attributes, $image_id) {
        $settings = $this->get_settings_for_display();
        $this->add_lightbox_data_attributes( 'single_product_gallery_image_' . $image_id, $image_id, $settings['open_lightbox'], 'all-' . $this->get_id() );
        $new_attributes = $this->get_render_attribute_string('single_product_gallery_image_' . $image_id);
        $attributes = array_unique(array_merge($attributes, explode(' ', $new_attributes)));
        return $attributes;
    }

}