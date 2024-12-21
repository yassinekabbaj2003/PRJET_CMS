<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

use ETC\App\Classes\Elementor;

/**
 * Popup Search widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Popup_Search extends \ETC\App\Controllers\Elementor\General\Search {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'theme-'.parent::get_name().'_popup';
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
		return __( 'Popup Search', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-popup-search et-elementor-header-builder-widget-icon-only';
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
        return array_merge(parent::get_keywords(), array('header', 'full-width', 'popup'));
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
        return ['theme-elements'];
    }

    /**
     * Get widget dependency.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget dependency.
     */

    public function get_script_depends() {
        return array_merge(parent::get_script_depends(), [ 'etheme_modal_popup' ]);
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
        return array_merge(parent::get_style_depends(), [ 'etheme-elementor-modal-popup' ]);
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_popup_button',
            [
                'label' => esc_html__( 'Popup Button', 'xstore-core' ),
                'condition' => [
                    'type' => 'popup'
                ]
            ]
        );

        $this->add_control(
            'popup_button_text',
            [
                'label' => __( 'Button Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'popup_button_selected_icon',
            [
                'label' => esc_html__( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'popup_button_icon',
                'skin' => 'inline',
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-zoom'
                ],
                'label_block' => false,
            ]
        );

        $this->add_control(
            'popup_button_icon_align',
            [
                'label' => __( 'Icon Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'xstore-core' ),
                    'right' => __( 'After', 'xstore-core' ),
                    'above' => __( 'Above', 'xstore-core' ),
                ],
                'condition' => [
                    'popup_button_text!' => '',
                    'popup_button_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'popup_button_icon_indent',
            [
                'label' => __( 'Icon Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 7
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-button .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .etheme-modal-popup-button .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .etheme-modal-popup-button.flex-wrap .button-text:last-child' => 'margin: {{SIZE}}{{UNIT}} 0 0;',
                ],
                'condition' => [
                    'popup_button_text!' => '',
                    'popup_button_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        add_action('etheme_elementor_ajax_search_before_style', [$this, 'register_popup_button_controls']);

        parent::register_controls();

        remove_action('etheme_elementor_ajax_search_before_style', [$this, 'register_popup_button_controls']);

        $this->update_control('section_general', [
            'label' => esc_html__( 'Popup Search', 'xstore-core' ),
        ]);

        $this->update_control('section_style_general', [
            'label' => esc_html__( 'Popup Search', 'xstore-core' ),
        ]);

        $switcher_controls = array(
            'focus_overlay',
            'animated_placeholder'
        );

        foreach ($switcher_controls as $switcher_control) {
            $this->update_control(
                $switcher_control,
                [
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                ]
            );
        }

        $this->update_control(
            'type',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'popup'
            ]
        );

        $this->update_control(
            'product_subcategory_path',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => ''
            ]
        );

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'before',
            'of'   => 'categories',
        ] );

        $this->add_control(
            'hover_overlay',
            [
                'label' => __( 'Overlay on hover', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'type!' => 'popup'
                ]
            ]
        );

        $this->add_control(
            'hover_overlay_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.elementor-location-header:has(.elementor-element.elementor-element-{{ID}} .add-overlay-body-on-hover:hover)' => '--hover-overlay-color: {{VALUE}};',
                ],
                'condition' => [
                    'hover_overlay!' => '',
                    'type!' => 'popup'
                ]
            ]
        );

        $this->end_injection();

        $popup_condition = [
            'type' => 'popup'
        ];
        $this->register_popup_settings($popup_condition);

        $this->register_popup_style_settings($popup_condition);

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'start',
            'of'   => 'section_popup',
        ] );

        $this->add_control(
            'popup_heading',
            [
                'label' => __( 'Heading', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'What Are You Looking For?', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'popup_trending_tags',
            [
                'label' => __( 'Trending Searches', 'xstore-core' ),
                'description' => esc_html__('Write your most popular search terms, separated by commas, to enable customers to search for results with one click.', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Shirt, Shoes, Cap, Skirt',
            ]
        );

        $data_sources = $this->get_additional_content_elements();

        $this->add_control(
            'popup_additional_content',
            [
                'label' 		=>	__( 'Additional Content', 'xstore-core' ),
                'type' 			=>	(count($data_sources) > 1 ? \Elementor\Controls_Manager::SELECT : \Elementor\Controls_Manager::HIDDEN),
                'options' => $data_sources,
                'separator' => 'before',
                'default'	=> 'product_categories'
            ]
        );

        $this->add_control(
            'popup_additional_save_template_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_saved_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'popup_additional_content' => 'saved_template'
                ]
            ]
        );

        $this->add_control(
            'popup_additional_static_block_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_static_block_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'popup_additional_content' => 'static_block'
                ]
            ]
        );

        $saved_templates = Elementor::get_saved_content();

        $this->add_control(
            'popup_additional_saved_template',
            [
                'label' => __( 'Saved Template', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $saved_templates,
                'default' => 'select',
                'condition' => [
                    'popup_additional_content' => 'saved_template'
                ],
            ]
        );

        $static_blocks = Elementor::get_static_blocks();

        $this->add_control(
            'popup_additional_static_block',
            [
                'label' => __( 'Static Block', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $static_blocks,
                'default' => 'select',
                'condition' => [
                    'popup_additional_content' => 'static_block'
                ],
            ]
        );

        $this->end_injection();

        $this->update_control('ajax_search_results_heading_type', [
            'default' => 'headings'
        ]);

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'after',
            'of'   => 'ajax_search_results_heading_type',
        ] );

        $this->add_responsive_control(
            'cols',
            [
                'label' => __( 'Columns', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'separator' => 'after',
                'options' => [
                    '' => __('Default', 'xstore-core'),
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--cols: {{VALUE}};',
                ],
            ]
        );

        $this->end_injection();

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'before',
            'of'   => 'results_button_style_heading',
        ] );

        $this->add_responsive_control(
            'results_popup_items_gap',
            [
                'label' => __( 'Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'type' => 'popup', // for header search
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--items-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_injection();

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'start',
            'of'   => 'section_style_general',
        ] );

        $this->add_control(
            'popup_heading_style_heading',
            [
                'label' => __( 'Popup heading', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'condition' => [
                    'type' => 'popup',
                    'popup_heading!' => ''
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'popup_heading_typography',
                'selector' => '{{WRAPPER}} .etheme-search-form-heading',
                'condition' => [
                    'type' => 'popup'
                ]
            ]
        );

        $this->add_control(
            'popup_heading_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-search-form-heading' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
                'condition' => [
                    'type' => 'popup'
                ]
            ]
        );

        $this->add_control(
            'popup_heading_space',
            [
                'label' => __( 'Margin bottom', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-search-form-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'type' => 'popup'
                ]
            ]
        );

        $this->add_control(
            'popup_trending_tags_style_heading',
            [
                'label' => __( 'Popup trending tags', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'type' => 'popup',
                    'popup_trending_tags!' => ''
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'popup_trending_tags_typography',
                'selector' => '{{WRAPPER}} .etheme-search-tags',
                'condition' => [
                    'type' => 'popup',
                    'popup_trending_tags!' => ''
                ]
            ]
        );

        $this->add_control(
            'popup_search_form_style_heading',
            [
                'label' => __( 'Search form', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'type' => 'popup',
                ]
            ]
        );

        $this->end_injection();
    }

    public function register_popup_button_controls() {
        $popup_button_selector = '{{WRAPPER}} .etheme-modal-popup-button';

        $this->start_controls_section(
            'section_popup_button_style',
            [
                'label' => esc_html__( 'Popup Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'type' => 'popup'
                ]
            ]
        );

        $this->get_button_style_settings('popup_', $popup_button_selector);

        $this->end_controls_section();

        $this->update_control('popup_button_text_color', [
            'default' => '#222'
        ]);

        $this->update_control('popup_button_background_color', [
            'default' => '#fff'
        ]);

        $this->update_control('popup_button_border_width', [
            'default' => [
                'unit' => 'px',
                'top' => 1,
                'left' => 1,
                'right' => 1,
                'bottom' => 1
            ]
        ]);

        $this->update_control('popup_button_min_width', [
            'selectors' => [
                $popup_button_selector => 'min-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->update_control('popup_button_background_hover_color', [
            'default' => ''
        ]);

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'before',
            'of'   => 'popup_button_min_width',
        ] );

        $this->add_responsive_control(
            'popup_button_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    $popup_button_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'popup_button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    $popup_button_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_injection();

    }

    public function register_popup_settings($condition = false) {
        if ( $condition ) {
            $this->start_controls_section(
                'section_popup',
                [
                    'label' => __('Popup Content', 'xstore-core'),
                    'condition' => $condition
                ]
            );
        }
        else {
            $this->start_controls_section(
                'section_popup',
                [
                    'label' => __('Popup Content', 'xstore-core'),
                ]
            );
        }

        $this->add_responsive_control(
            'popup_width',
            [
                'label' => __( 'Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', 'vw' ],
                'default' => [
                    'size' => 100,
                    'unit' => 'vw'
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-content' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'popup_height_type',
            [
                'label' => __( 'Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __( 'Fit To Content', 'xstore-core' ),
                    'fit_to_screen' => __( 'Fit To Screen', 'xstore-core' ),
                    'custom' => __( 'Custom', 'xstore-core' ),
                ],
                'selectors_dictionary' => [
                    'fit_to_screen' => '100vh',
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-content' => 'height: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'popup_height',
            [
                'label' => __( 'Custom Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', 'vh' ],
                'condition' => [
                    'popup_height_type' => 'custom',
                ],
                'default' => [
                    'size' => 380,
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-content' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'popup_popup_content_position',
            [
                'label' => __( 'Content Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => __( 'Top', 'xstore-core' ),
                    'center' => __( 'Center', 'xstore-core' ),
                    'bottom' => __( 'Bottom', 'xstore-core' ),
                ],
                'condition' => [
                    'popup_height_type!' => 'fit_to_screen',
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-content-wrapper' => 'align-items: {{VALUE}};',
                ],
            ]
        );

//        $this->add_control(
//            'position_heading',
//            [
//                'label' => __( 'Position', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::HEADING,
//                'separator' => 'before',
//            ]
//        );

        $this->add_control(
            'popup_horizontal_position',
            [
                'label' => __( 'Horizontal', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'toggle' => false,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'xstore-core' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'xstore-core' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'xstore-core' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-content-wrapper' => 'justify-content: {{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'left' => 'flex-start',
                    'right' => 'flex-end',
                ],
            ]
        );

        $this->add_responsive_control(
            'popup_vertical_position',
            [
                'label' => __( 'Vertical', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'toggle' => false,
                'default' => 'center',
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'xstore-core' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'xstore-core' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'xstore-core' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-content' => 'align-items: {{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'bottom' => 'flex-end',
                ],
            ]
        );

        $this->add_control(
            'popup_overlay',
            [
                'label' => __( 'Popup Overlay', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-overlay' => 'display: block;',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

//		$this->add_control(
//			'popup_close_button',
//			[
//				'label' => __( 'Close Button', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SWITCHER,
//				'default' => 'yes',
//			]
//		);

        $this->add_responsive_control(
            'popup_entrance_animation',
            [
                'label' => __( 'Entrance Animation', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ANIMATION,
                'frontend_available' => true,
                'separator' => 'before',
                'default' => 'fadeInDown'
            ]
        );

        $this->add_responsive_control(
            'popup_exit_animation',
            [
                'label' => __( 'Exit Animation', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::EXIT_ANIMATION,
                'frontend_available' => true,
                'default' => 'fadeInDown'
            ]
        );

        $this->add_control(
            'popup_entrance_animation_duration',
            [
                'label' => __( 'Animation Duration', 'xstore-core' ) . ' (sec)',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'frontend_available' => true,
                'default' => [
                    'size' => .7,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-content' => 'animation-duration: {{SIZE}}s',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'popup_entrance_animation',
                            'operator' => '!==',
                            'value' => '',
                        ],
                        [
                            'name' => 'popup_exit_animation',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function register_popup_style_settings($condition = false) {
        $this->start_controls_section(
            'section_popup_overlay',
            [
                'label' => __('Popup Overlay', 'xstore-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ($condition ? array_merge([
                    'popup_overlay' => 'yes',
                ], $condition) : [
                    'popup_overlay' => 'yes',
                ])
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'popup_overlay_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .etheme-modal-popup-overlay',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => 'rgba(0, 0, 0, 0.3)',
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        if ( $condition ) {
            $this->start_controls_section(
                'section_popup_close_button',
                [
                    'label' => __( 'Close Button', 'xstore-core' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				    'condition' => $condition
                ]
            );
        }
        else {
            $this->start_controls_section(
                'section_popup_close_button',
                [
                    'label' => __('Close Button', 'xstore-core'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
//				'condition' => [
//					'popup_close_button!' => '',
//				],
                ]
            );
        }

        $this->add_control(
            'popup_close_button_position',
            [
                'label' => __( 'Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Inside', 'xstore-core' ),
                    'outside' => __( 'Outside', 'xstore-core' ),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'popup_close_button_vertical',
            [
                'label' => __( 'Vertical Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'max' => 100,
                        'min' => -100,
                        'step' => 1,
                    ],
                    'px' => [
                        'max' => 100,
                        'min' => -100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-close' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'popup_close_button_horizontal',
            [
                'label' => __( 'Horizontal Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'max' => 100,
                        'min' => -100,
                        'step' => 1,
                    ],
                    'px' => [
                        'max' => 100,
                        'min' => -100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .etheme-modal-popup-close' => 'left: auto; right: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}} .etheme-modal-popup-close' => 'right: auto; left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'close_button_style_tabs' );

        $this->start_controls_tab(
            'tab_x_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'popup_close_button_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-close' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'popup_close_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-close' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_x_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'popup_close_button_hover_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-close:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'close_button_hover_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-close:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'close_button_size',
            [
                'label' => __( 'Size', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 22
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-close' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'close_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'close_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-modal-popup-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 5.2
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        if ( $settings['type'] != 'popup' ) {
            if ( !!$settings['hover_overlay'] ) {
                $this->add_render_attribute( 'form'.$this->get_id(), 'class', 'add-overlay-body-on-hover');
            }
            parent::render();
            return;
        }

        $unique_id = $this->get_id();

        $this->add_render_attribute( 'popup_wrapper', 'class', 'elementor-button-wrapper' );

        $this->add_render_attribute( 'popup_button', 'class', ['elementor-button', 'etheme-modal-popup-button'] );
        $this->add_render_attribute( 'popup_button', 'role', 'button' );
        $this->add_render_attribute( 'popup_button', 'aria-label', ($settings['popup_button_text'] ? $settings['popup_button_text'] : esc_html__('Search', 'xstore-core')) );
        $this->add_render_attribute( 'popup_button', 'data-popup-id', $unique_id);

        if ( $settings['popup_button_icon_align'] == 'above' ) {
            $this->add_render_attribute( 'popup_button', [
                'class' => ['flex', 'justify-content-center', 'flex-wrap'],
            ] );
            $this->add_render_attribute( 'popup_button_text', [
                'class' => 'full-width',
            ] );
        }

        ?>
        <div <?php $this->print_render_attribute_string( 'popup_wrapper' ); ?>>
            <a <?php $this->print_render_attribute_string( 'popup_button' ); ?>>
                <?php $this->render_popup_text($settings); ?>
            </a>
        </div>
        <?php
        $this->get_modal_popup_content($settings);
    }

    public function render_popup_text($settings) {
        $this->add_render_attribute( 'popup_button_text',
            [
                'class' => 'button-text',
            ]
        );

        $button_text = $settings['popup_button_text'];

        if ( !$button_text || in_array($settings['popup_button_icon_align'], array('left', 'above') ) )
            $this->render_icon( $settings, 'popup_button' );

        if ($button_text) : ?>
            <span <?php echo $this->get_render_attribute_string( 'popup_button_text' ); ?>><?php echo $settings['popup_button_text']; ?></span>
        <?php endif;

        if ( $button_text && $settings['popup_button_icon_align'] == 'right')
            $this->render_icon( $settings, 'popup_button' );
    }

    protected function get_modal_popup_content($settings) {
        $this->add_render_attribute(
            'modal-wrapper',
            [
                'class' => 'etheme-modal-popup-content-wrapper',
                'data-id' => $this->get_id(),
                'style' => 'display: none;',
//				'data-animation' => $settings['popup_animation']
            ]
        );

        $this->add_render_attribute(
            'modal-close',
            [
                'class' => [
                    'etheme-modal-popup-close',
                    $settings['popup_close_button_position'] != '' ? 'outside' : 'inside'
                ]
            ]
        );

//		if( 'pageload' == $settings['trigger_type'] ){
//			$delay = $settings['modal_box_popup_delay'];
//			$delay = $delay ? ( $delay * 1000 ) : 0;
//
//			$this->add_render_attribute( 'modal-content', 'data-display-delay', $delay );
//		}

        $this->add_render_attribute(
            'modal-content',
            [
                'class' => [
                    'etheme-modal-popup-content',
                    'animated'
                ],
                'data-height' => $settings['popup_height_type']
            ]
        );

        $this->add_render_attribute(
            'modal-content-inner',
            [
                'class' => [
                    'etheme-modal-popup-inner',
                    'container'
                ]
            ]
        );

//		$is_edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        ?>
        <div <?php $this->print_render_attribute_string( 'modal-wrapper' ); ?>>

            <?php if ( !!$settings['popup_overlay'] ) : ?>
                <div class="etheme-modal-popup-overlay"></div>
            <?php endif; ?>

            <div <?php $this->print_render_attribute_string( 'modal-content' ); ?>>
                <?php // if ( $settings['popup_close_button']) : ?>
                <span <?php $this->print_render_attribute_string( 'modal-close' ); ?>>
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                      </svg>
                    </span>
                <?php // endif; ?>
                <div <?php $this->print_render_attribute_string( 'modal-content-inner' ); ?>>
                    <?php
                    add_action('etheme_elementor_search_before_input', [$this, 'render_before_input']);
                    add_action('etheme_elementor_search_after_input', [$this, 'render_after_input']);

                    add_action('etheme_elementor_search_after_input', [$this, 'render_additional_content']);

                        $this->render_inner_content($settings, $this->get_id());

                    remove_action('etheme_elementor_search_before_input', [$this, 'render_before_input']);
                    remove_action('etheme_elementor_search_after_input', [$this, 'render_after_input']);

                    remove_action('etheme_elementor_search_after_input', [$this, 'render_additional_content']);
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function render_before_input() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('popup_heading', 'class', ['etheme-search-form-heading', 'h2', 'products-title']);
        ?>
        <div class="etheme-search-input-form-wrapper-before">
        <?php
        if ( ! \Elementor\Utils::is_empty( $settings['popup_heading'] ) ) { ?>
            <div <?php $this->print_render_attribute_string( 'popup_heading' ); ?>>
                <?php $this->print_unescaped_setting( 'popup_heading' ); ?>
            </div>
            <?php
        }
    }

    public function render_after_input() {
        $settings = $this->get_settings_for_display();
        if ( !empty($settings['popup_trending_tags']) ) {
            $trending_tags = explode(',', $settings['popup_trending_tags']);
            if (count($trending_tags)) {
                echo '<div class="etheme-search-tags flex align-items-center justify-content-center">';
                echo '<span class="etheme-search-tags-title">'.esc_html__('Trending searches: ', 'xstore-core').'</span>';
                echo $this->render_trending_searches_list($trending_tags);
                echo '</div>';
            }
        }
        ?>
        </div>
        <?php
    }

    public function render_additional_content() {
        $settings = $this->get_settings_for_display();
        $prefix = 'popup_additional_';
        $ready_content = '';
        switch ( $settings['popup_additional_content'] ) {
            case 'product_categories':
                $ready_content .= '<br/><div class="full-width align-center products-title h2">'.esc_html__('Popular categories', 'xstore-core').'</div><br/>';
//                $ready_content .= do_shortcode('[product_categories limit=5 columns=5]');
            $ready_content .= \ETC\App\Controllers\Shortcodes\Categories::categories_shortcode(apply_filters('etheme_ajax_search_product_categories_args', array(
                'columns' => 6,
                'number' => 6,
                'style' => 'default',
                'content_position' => 'under',
                'text_color' => 'dark',
            )));
                $ready_content .= '<br/><div class="full-width text-center">';
                    $ready_content .= '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) .'" class="btn black big">';
                        $ready_content .= esc_html__('View all categories', 'xstore-core');
                    $ready_content .= '</a>';
                $ready_content .= '</div><br/><br/>';
                break;
            case 'global_widget':
            case 'saved_template':
                if (!empty($settings[$prefix.$settings['popup_additional_content']]) && $settings[$prefix.$settings['popup_additional_content']] != 'select'):
                    //								echo \Elementor\Plugin::$instance->frontend->get_builder_content( $item[$item['content_type']], true );

                    $posts = get_posts(
                        [
                            'name' => $settings[$prefix.$settings['popup_additional_content']],
                            'post_type' => 'elementor_library',
                            'posts_per_page' => '1',
                            'tax_query' => [
                                [
                                    'taxonomy' => 'elementor_library_type',
                                    'field' => 'slug',
                                    'terms' => str_replace(array('global_widget', 'saved_template'), array('widget', 'section'), $settings[$prefix.$settings['popup_additional_content']]),
                                ],
                            ],
                            'fields' => 'ids'
                        ]
                    );
                    if (!isset($posts[0]) || !$content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($posts[0])) {
                        $ready_content .= esc_html__('We have imported template successfully. To setup it in the correct way please, save this page, refresh and select it in dropdown.', 'xstore-core');
                    } else {
                        $ready_content .= $content;
                    }
                endif;
                break;
            case 'static_block':
                ob_start();
                    Elementor::print_static_block($settings[$prefix.$settings['popup_additional_content']]);
                $ready_content .= ob_get_clean();
                break;
                default;
        }
        if ( !empty($ready_content) ) {
            ?>
            <div class="etheme-search-additional-content-wrapper">
                <?php echo $ready_content; ?>
            </div>
            <?php
        }
    }

    // only if there are Search locations created for Search results page builder then we should redirect the customer
    // to the search results built page
    public function should_redirect_to_archive() {
        $created_templates = get_posts(
            [
                'post_type' => 'elementor_library',
                'post_status' => 'publish',
                'posts_per_page' => '-1',
                'tax_query' => [
                    [
                        'taxonomy' => 'elementor_library_type',
                        'field' => 'slug',
                        'terms' => 'search-results',
                    ],
                ],
                'meta_query'     => array(
                    array(
                        'key'     => '_elementor_conditions',
                        'value'   => 'include/archive/search',
                        'compare' => 'LIKE'
                    )
                ),
                'fields' => 'ids'
            ]
        );

        // originally we should display
        if ( count($created_templates) ) {
            $should_redirect_to_shop = false;
//            foreach ($created_templates as $created_template) {
//                if ( $should_redirect_to_shop ) break;
//                $should_redirect_to_shop = in_array('include/archive', (array)get_post_meta($created_template, '_elementor_conditions', true));
//            }
            return $should_redirect_to_shop;
        }
        return parent::should_redirect_to_archive();
    }

    protected function get_additional_content_elements() {
        return array_merge([
            'none' => esc_html__('Without', 'xstore-core'),
            'product_categories' => esc_html__('Product Categories', 'xstore-core'),
        ], Elementor::get_saved_content_list(array(
            'custom' => false,
            'global_widget' => false
        )));
    }

}
