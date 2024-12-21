<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Search widget.
 *
 * @since      4.0.10
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Search extends \Elementor\Widget_Base {

    public static $instance = null;

	/**
	 * Get widget name.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_ajax_search';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Ajax Search', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-search';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'search', 'form', 'typing', 'ajax', 'products', 'posts', 'query', 'results' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['eight_theme_general'];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
        return ['etheme_ajax_search'];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return [ 'etheme-elementor-search' ];
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
	 * @since 4.0.10
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
				'label'   => esc_html__( 'Type', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'separated' => esc_html__('Separated', 'xstore-core'),
					'inline' => esc_html__('Inline', 'xstore-core')
				),
				'default' => 'inline',
			]
		);

        $this->add_control(
            'results_new_tab',
            [
                'label' => __( 'Show results in new tab', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HIDDEN,
            ]
        );

        // used in header search widgets
        $this->add_control(
            'focus_overlay',
            [
                'label' => __( 'Overlay on input focus', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'condition' => [
                    'type!' => 'popup'
                ],
                'frontend_available' => true
            ]
        );

        $focus_overlay_selector = ['.elementor-location-header:has(.elementor-element.elementor-element-{{ID}} .add-overlay-body-on-focus:focus)',
            '.elementor-location-header:has(.elementor-element.elementor-element-{{ID}} .add-overlay-body-on-focus.focused)',
        '.elementor-location-header:has(.elementor-element.elementor-element-{{ID}} .add-overlay-body-on-focus:focus-within)'];

        $this->add_control(
            'focus_overlay_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    implode(',', $focus_overlay_selector) => '--hover-overlay-color: {{VALUE}};',
                ],
                'condition' => [
                    'focus_overlay!' => '',
                    'type!' => 'popup'
                ]
            ]
        );
		
		$this->add_control(
			'categories',
			[
				'label'     => esc_html__( 'Show Categories', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true
			]
		);

        if ( \Elementor\Plugin::$instance->breakpoints && method_exists( \Elementor\Plugin::$instance->breakpoints, 'get_active_breakpoints')) {
            $active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
            $breakpoints_list   = array();

            foreach ($active_breakpoints as $key => $value) {
                $breakpoints_list[$key] = $value->get_label();
            }

            $breakpoints_list['desktop'] = 'Desktop';
            $breakpoints_list            = array_reverse($breakpoints_list);
        } else {
            $breakpoints_list = array(
                'desktop' => 'Desktop',
                'tablet'  => 'Tablet',
                'mobile'  => 'Mobile'
            );
        }

        $this->add_control(
            'categories_hidden',
            array(
                'label'    => __( 'Categories Hidden On', 'xstore-core' ),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => 'true',
                'default' => array(),
                'options' => $breakpoints_list,
                'condition' => array(
                    'categories!' => '',
                ),
            )
        );

        $this->add_control(
            'categories_dynamic_width',
            [
                'label'     => esc_html__( 'Dynamic Select Width', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'categories!' => ''
                ]
            ]
        );

        $this->add_control(
            'categories_width',
            [
                'label' => __( 'Select Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'separator' => 'after',
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 350,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-search-form-select' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'categories!' => '',
                    'categories_dynamic_width' => ''
                ]
            ]
        );

        $this->add_control(
            'ajax_search',
            [
                'label'     => esc_html__( 'Ajax Search', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'min_chars',
            [
                'label' => __( 'Search After x Symbols', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 20,
                        'step' => 1
                    ],
                ],
                'condition' => [
                    'ajax_search!' => ''
                ],
                'frontend_available' => true
            ]
        );
		
		$search_types = $this->search_post_types();
		$default_post_types = [];
		if ( array_key_exists('product', $search_types) ) {
		    $default_post_types[] = 'product';
		}
		if ( array_key_exists('post', $search_types) ) {
		    $default_post_types[] = 'post';
		}
		
		$this->add_control(
			'post_types',
			[
				'label'   => esc_html__( 'Post Types', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $search_types,
				'default' => $default_post_types,
                'frontend_available' => true
			]
		);
		
		$this->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Search for products', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Search', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_selected_icon',
			[
				'label' => esc_html__( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'button_icon',
                'default' => [
                    'value' => 'et-icon et-zoom',
                    'library' => 'xstore-icons',
                ],
				'skin' => 'inline',
				'label_block' => false,
			]
		);
		
		$this->add_control(
			'button_icon_align',
			[
				'label' => __( 'Icon Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'xstore-core' ),
					'right' => __( 'After', 'xstore-core' ),
				],
				'condition' => [
					'button_selected_icon[value]!' => '',
				],
			]
		);
		
		$this->add_control(
			'button_icon_indent',
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
					'{{WRAPPER}} .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    'button_text!' => '',
					'button_selected_icon[value]!' => '',
				],
			]
		);
		
		$this->end_controls_section();

        // used in header search
        $this->render_animated_placeholder_options();
		
		$this->start_controls_section(
			'section_search_ajax_settings',
			[
				'label' => esc_html__( 'Ajax Search Results', 'xstore-core' ),
				'condition' => [
                    'ajax_search' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'ajax_search_results_heading_type',
			[
				'label'   => esc_html__( 'Heading Type', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'tabs' => esc_html__('Tabs', 'xstore-core'),
					'headings' => esc_html__('Headings', 'xstore-core'),
					'none' => esc_html__('None', 'xstore-core')
				),
				'default' => 'tabs',
				'frontend_available' => true
			]
		);
		
		// make query for x count posts
		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Limit Results', 'xstore-core' ),
				'description' => __( 'Limit results for each post types', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min'  => 1,
						'max'  => 300,
						'step' => 1
					],
				],
				'frontend_available' => true
			]
		);
		
		// output in search results
		$this->add_control(
			'post_limit',
			[
				'label' => __( 'Posts Count For View', 'xstore-core' ),
				'description' => __( 'Display View All Results button after this number of posts', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min'  => -1,
						'max'  => 30,
						'step' => 1
					],
				],
				'default' => [
                    'size' => 5
                ],
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'product_content_heading',
			[
				'label' => __( 'Product', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'post_types' => 'product',
				],
			]
		);
		
		$this->add_control(
			'product_stock',
			[
				'label'     => esc_html__( 'Show Stock Status', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'post_types' => 'product',
				],
			]
		);
		
		$this->add_control(
			'product_sku',
			[
				'label'     => esc_html__( 'Show Sku', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'condition' => [
					'post_types' => 'product',
				],
			]
		);

        $this->add_control(
            'product_category',
            [
                'label'     => esc_html__( 'Show Category', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'post_types' => 'product',
                ],
            ]
        );

        $this->add_control(
            'product_subcategory_path',
            [
                'label'     => esc_html__( 'Show SubCategory Path', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'frontend_available' => true,
                'condition' => [
                    'post_types' => 'product',
                    'product_category!' => ''
                ],
            ]
        );
		
		$this->add_control(
			'product_price',
			[
				'label'     => esc_html__( 'Show Price', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'post_types' => 'product',
				],
			]
		);
		
		$this->add_control(
			'global_post_type_content_heading',
			[
				'label' => __( 'Global Post Types', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'post_types!' => '',
				],
			]
		);
		
		$this->add_control(
			'global_post_type_date',
			[
				'label'     => esc_html__( 'Show Date', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'post_types!' => '',
				],
			]
		);

        $all_post_type_content_list = [
                'image' => esc_html__('Image', 'xstore-core'),
                'title' => esc_html__('Title', 'xstore-core'),
                'category' => esc_html__('Category', 'xstore-core'),
                'date' => esc_html__('Date', 'xstore-core')
        ];

        $content_mobile_elements = ['image', 'title'];
        if ( array_key_exists('product', $search_types) ) {
            $all_post_type_content_list = array_merge($all_post_type_content_list, array(
                'product_stock' => esc_html__('Product stock', 'xstore-core'),
                'product_sku' => esc_html__('Product sku', 'xstore-core'),
                'product_price' => esc_html__('Product price', 'xstore-core'),
            ));
            $content_mobile_elements[] = 'product_price';
        }


        $this->add_control(
            'post_type_content_mobile',
            [
                'label'   => esc_html__( 'Content on Mobile', 'xstore-core' ),
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $all_post_type_content_list,
                'default' => $content_mobile_elements,
                'frontend_available' => true,
                'condition' => [
                    'post_types!' => '',
                ],
            ]
        );
		
		$this->end_controls_section();
        
        // used in header search
        $this->render_trending_searches_options();

        do_action('etheme_elementor_ajax_search_before_style');
		
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'border',
				'label'     => esc_html__( 'Border', 'xstore-core' ),
				'selector'  => '{{WRAPPER}}',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
						'selectors' => [
                            '{{SELECTOR}}' => '--s-border-style: {{VALUE}};',
                        ],
					],
					'width' => [
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'selectors' => [
                            '{{SELECTOR}}' => '--s-border-width: {{SIZE}}{{UNIT}};',
                        ],
                    ],
					'color' => [
						'default' => '#e1e1e1',
						'selectors' => [
                            '{{SELECTOR}}' => '--s-border-color: {{VALUE}};',
                        ],
					]
				],
			]
		);
		
		$this->add_responsive_control(
			'min_height',
			[
				'label' => __( 'Min Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min'  => 30,
						'max'  => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--s-min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}' => '--s-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'focus_overlay_style_heading',
            [
                'label' => __( 'Focus Overlay', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'focus_overlay!' => '',
                ]
            ]
        );

        $focus_overlay_selectors = implode(',', array(
            '{{WRAPPER}}:has(.add-overlay-body-on-focus:focus, .add-overlay-body-on-focus.focused, .add-overlay-body-on-focus:focus-within) .etheme-search-form-select',
          '{{WRAPPER}}:has(.add-overlay-body-on-focus:focus, .add-overlay-body-on-focus.focused, .add-overlay-body-on-focus:focus-within)  .etheme-search-form-input'
        ));

        $this->add_control(
            'focus_overlay_input_bg_color',
            [
                'label' => esc_html__( 'Input/Categories Background Color', 'xstore-core' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'condition' => [
                    'focus_overlay!' => '',
                ],
                'selectors' => [
                    $focus_overlay_selectors => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}' => '--hover-overlay-items-shadow-color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'focus_overlay_input_color',
            [
                'label' => esc_html__( 'Input/Categories Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000',
                'condition' => [
                    'focus_overlay!' => '',
                ],
                'selectors' => [
                    $focus_overlay_selectors => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );
		
		$this->add_responsive_control(
			'space',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'condition' => [
				    'type' => 'separated'
                ],
				'selectors' => [
					'{{WRAPPER}}' => '--s-form-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_categories',
			[
				'label' => esc_html__( 'Categories', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'categories!' => ''
                ]
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'categories_typography',
                'selector' => '{{WRAPPER}} .etheme-search-form-select',
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'categories_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-search-form-select',
			]
		);
		
		$this->add_control(
			'categories_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-select' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_input',
			[
				'label' => esc_html__( 'Input', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} .etheme-search-form-input',
            ]
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'input_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-search-form-input',
			]
		);
		
		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-input, {{WRAPPER}} .etheme-search-form-clear' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-input::-webkit-input-placeholder' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .etheme-search-form-input::-moz-placeholder' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .etheme-search-form-input:-ms-input-placeholder' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .etheme-search-form-input:-moz-placeholder' => 'fill: {{VALUE}}; color: {{VALUE}};',
                    '{{WRAPPER}} .etheme-search-input-placeholder' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->get_button_style_settings();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_results',
			[
				'label' => esc_html__( 'Results Dropdown', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' 	=> [
                    'relation' => 'or',
                    'terms' 	=> [
                        [
                            'name' 		=> 'ajax_search',
                            'operator'  => '!=',
                            'value' 	=> ''
                        ],
                        [
                            'name' 		=> 'trending_searches',
                            'operator'  => '!=',
                            'value' 	=> ''
                        ]
                    ]
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'results_border',
				'selector' => '{{WRAPPER}} .etheme-search-ajax-results',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => 1,
							'left' => 1,
							'right' => 1,
							'bottom' => 1
						],
					],
					'color' => [
						'default' => '#e1e1e1',
					]
				],
                'condition' => [
                    'type!' => 'popup' // for header search
                ]
			]
		);
		
		$this->add_control(
			'results_offset',
			[
				'label' => __( 'Top offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--s-results-offset: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'type!' => 'popup' // for header search
                ]
			]
		);
		
		$this->add_control(
			'results_max_height',
			[
				'label' => __( 'Max Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 550,
						'step' => 1,
					],
				],
                'condition' => [
                    'type!' => 'popup' // for header search
                ],
                'frontend_available' => true
			]
		);
		
		// make it for wrapper because scrollbar will be ok then
		$this->add_control(
			'results_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-search-ajax-results' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'type!' => 'popup' // for header search
                ]
			]
		);
		
		$is_rtl = is_rtl();
		$this->add_control(
			'results_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .autocomplete-suggestions' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
                    'left' => $is_rtl ? 1 : 0,
                    'right' => $is_rtl ? 0 : 1,
                    'isLinked' => false
                ],
                'condition' => [
                    'type!' => 'popup' // for header search
                ]
              
			]
		);
		
		$this->add_control(
			'results_items_style_heading',
			[
				'label' => __( 'Items', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
                'condition' => [
                    'type!' => 'popup', // for header search
                ]
			]
		);
		
		$this->add_control(
			'results_title_v_space',
			[
				'label' => __( 'Title Vertical Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--v-title-space: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    'type!' => 'popup', // for header search
                    'ajax_search_results_heading_type' => 'headings'
                ]
			]
		);
		
		$this->add_control(
			'results_item_v_space',
			[
				'label' => __( 'Item Vertical Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
                'condition' => [
                    'type!' => 'popup', // for header search
                ],
				'selectors' => [
					'{{WRAPPER}}' => '--v-item-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'results_item_h_space',
			[
				'label' => __( 'Item Horizontal Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
                'condition' => [
                    'type!' => 'popup', // for header search
                ],
				'selectors' => [
					'{{WRAPPER}}' => '--h-item-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'results_item_min_height',
			[
				'label' => __( 'Item Min Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
                'condition' => [
                    'type!' => 'popup', // for header search
                ],
				'selectors' => [
					'{{WRAPPER}}' => '--item-min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'results_button_style_heading',
			[
				'label' => __( 'View All Results Button', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'results_button_typography',
				'selector' => '{{WRAPPER}} .etheme-search-form-more button',
			]
		);
		
		$this->start_controls_tabs( 'tabs_results_button_style' );
		
		$this->start_controls_tab(
			'tab_results_button_normal',
			[
				'label' => esc_html__( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'results_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
//				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-more button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'results_button_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-search-form-more button',
//				'fields_options' => [
//					'background' => [
//						'default' => 'classic',
//					],
//					'color' => [
//                        'default' => '#000000',
//					],
//				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_results_button_hover',
			[
				'label' => esc_html__( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'results_button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-more button:hover, {{WRAPPER}} .etheme-search-form-more button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-search-form-more button:hover svg, {{WRAPPER}} .etheme-search-form-more button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'results_button_background_hover',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-search-form-more button:hover, {{WRAPPER}} .etheme-search-form-more button:focus',
//				'fields_options' => [
//					'background' => [
//						'default' => 'classic',
//					],
//                    'color' => [
//                        'default' => '#3f3f3f'
//                    ]
//				],
			]
		);
		
		$this->add_control(
			'results_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'results_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-more button:hover, {{WRAPPER}} .etheme-search-form-more button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'results_button_border',
				'selector' => '{{WRAPPER}} .etheme-search-form-more button',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'results_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-more button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'results_button_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-more button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->end_controls_section();
		
    }

    function get_button_style_settings($prefix = '', $selector = false) {
        if ( !$selector )
            $selector = '{{WRAPPER}} .etheme-search-form-submit';
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => $prefix . 'button_typography',
                'selector' => $selector,
            ]
        );

        $this->start_controls_tabs( 'tabs_'.$prefix.'button_style' );

        $this->start_controls_tab(
            'tab_'.$prefix.'button_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            $prefix . 'button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    $selector => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => $prefix . 'button_background',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => $selector,
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#000000',
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: {{VALUE}}; --s-form-shadow-color: {{VALUE}}',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_'.$prefix.'button_hover',
            [
                'label' => esc_html__( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            $prefix . 'button_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selector.':hover, '.$selector.':focus' => 'color: {{VALUE}};',
                    $selector.':hover svg, '.$selector.':focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => $prefix . 'button_background_hover',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => $selector.':hover, '.$selector.':focus',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#3f3f3f',
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: {{VALUE}}; --s-form-shadow-color: {{VALUE}}',
                        ],
                    ]
                ],
            ]
        );

        $this->add_control(
            $prefix . 'button_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    $prefix . 'button_border_border!' => '',
                ],
                'selectors' => [
                    $selector.':hover, '.$selector.':focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => $prefix . 'button_border',
                'selector' => $selector,
                'separator' => 'before',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => 0,
                            'left' => 0,
                            'right' => 0,
                            'bottom' => 0
                        ]
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix . 'button_min_width',
            [
                'label' => __( 'Min Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    $selector => '--s-button-min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    }

    public function render_animated_placeholder_options() {
        
    }
    public function render_trending_searches_options() {

    }

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.10
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();

        $unique_id = $this->get_id();

        $this->render_inner_content($settings, $unique_id);
    }

    // this function also is used in mobile-menu widget for Search element
    public function render_inner_content($settings, $unique_id, $extra_settings = array()) {
        $is_woocommerce = class_exists('WooCommerce');

        $this->add_render_attribute(
            'form'.$unique_id, [
                'class' => 'etheme-search-form',
                'role' => 'search',
                'action' => home_url(),
                'method' => 'get',
                'type' => $settings['type']
            ]
        );

        if ( isset($settings['results_new_tab']) && !!$settings['results_new_tab'] ) {
            $this->add_render_attribute(
                'form'.$unique_id, [
                    'target' => '_blank'
                ]
            );
        }

        if ( isset($extra_settings['form_attributes']) ) {
            $this->add_render_attribute(
                'form'.$unique_id, $extra_settings['form_attributes']
            );
        }

        $this->add_render_attribute('ajax-results-wrapper'.$unique_id, 'class', 'etheme-search-ajax-results');

        $this->add_render_attribute(
            'input'.$unique_id, [
                'placeholder' => $settings['placeholder'],
                'class' => 'etheme-search-form-input',
                'type' => 'search',
                'name' => 's',
                'title' => __( 'Search', 'xstore-core' ),
            ]
        );

        if ( apply_filters( 'etheme_search_input_keep_last_value', true ) ) {
            $this->add_render_attribute(
                'input' . $unique_id, [
                    'value' => get_search_query()
                ]
            );
        }

         $this->add_render_attribute('clear'.$unique_id, 'class', 'etheme-search-form-clear');

        $focus_class = '';
        if ( !!$settings['focus_overlay'] ) {
            $this->add_render_attribute( 'input'.$unique_id, 'class', 'add-overlay-body-on-focus');
        }

        $this->add_render_attribute(
            'button'.$unique_id, [
                'class' => 'etheme-search-form-submit',
                'type' => 'submit',
                'title' => __( 'Search', 'xstore-core' ),
                'aria-label' => __( 'Search', 'xstore-core' ),
            ]
        );

        $categories = '';

        if ( $settings['categories'] ) { // show categories

            $taxonomy = ( $is_woocommerce ) ? 'product_cat' : 'category';

            $categories_class = array(
                'etheme-search-form-select'
            );

            if ( !!$focus_class )
                $categories_class[] = $focus_class;

            foreach ($settings['categories_hidden'] as $hidden_on_device) {
                $categories_class[] = 'elementor-hidden-' . $hidden_on_device;
            }

            $categories = wp_dropdown_categories(
                apply_filters('etheme_elementor_ajax_search_categories_args', array(
                    'show_option_all' => esc_html__('All categories', 'xstore-core'),
                    'taxonomy'        => $taxonomy,
                    'hierarchical'    => true,
                    'echo'            => false,
                    'id'              => null,
                    'class' => implode(' ', $categories_class),
                    'name'            => $taxonomy,
                    'orderby'         => 'name',
                    'value_field'     => 'slug',
                    'hide_if_empty'   => true
                ))
            );

        }

        $should_redirect_to_archive = $is_woocommerce && apply_filters('etheme_elementor_search_should_redirect_to_archive', $this->should_redirect_to_archive());

        ?>

        <form <?php echo $this->get_render_attribute_string( 'form'.$unique_id ); ?>>
            <?php do_action('etheme_elementor_search_before_input'); ?>
            <div class="etheme-search-input-form-wrapper">

                <?php
                    if ( !isset($settings['categories_dynamic_width']) || !!$settings['categories_dynamic_width'] )
                        echo str_replace( '<select', '<select style="width: 100%; max-width: calc(122px + 1.4em)"', $categories );
                    else
                        echo $categories;
                ?>

                <div class="etheme-search-input-wrapper">

                    <?php $this->render_animated_placehoder($settings, $unique_id); ?>

                    <input <?php echo $this->get_render_attribute_string( 'input'.$unique_id ); ?>>

                    <?php if ( $should_redirect_to_archive ) : ?>
                        <input type="hidden" name="et_search" value="true">
                    <?php endif; ?>

                    <?php if ( defined( 'ICL_LANGUAGE_CODE' ) && ! defined( 'LOCO_LANG_DIR' ) ) : ?>
                        <input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE; ?>"/>
                    <?php elseif (isset($_GET['lang'])) : ?>
                        <input type="hidden" name="lang" value="<?php echo $_GET['lang']; ?>"/>
                    <?php endif ?>

                    <?php
                    if ( $is_woocommerce && $should_redirect_to_archive ): ?>
                        <input type="hidden" name="post_type" value="product">
                    <?php endif ?>

                    <?php if ( !!$settings['ajax_search'] ) : ?>
                        <span <?php echo $this->get_render_attribute_string( 'clear'.$unique_id ); ?>>
                            <svg xmlns="http://www.w3.org/2000/svg" width=".7em" height=".7em" viewBox="0 0 24 24" fill="currentColor"><path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path></svg>
                        </span>
                    <?php endif; ?>

                </div>

                <div class="etheme-search-form-button-wrapper">

                    <button <?php echo $this->get_render_attribute_string( 'button'.$unique_id ); ?>>

                        <?php
                        if ( $settings['button_icon_align'] == 'left')
                            $this->render_icon( $settings );

                        if ( ! empty( $settings['button_text'] ) )
                            echo '<span class="button-text">'.$settings['button_text'].'</span>';
                        else
                            echo '<span class="elementor-screen-only">' . esc_html__( 'Search', 'xstore-core' ). '</span>';

                        if ( $settings['button_icon_align'] == 'right')
                            $this->render_icon( $settings );
                        ?>

                    </button>
                </div>

            </div>
            <?php do_action('etheme_elementor_search_after_input'); ?>
            <div <?php echo $this->get_render_attribute_string( 'ajax-results-wrapper'.$unique_id ); ?>><?php $this->render_trending_searches($settings, $unique_id) ?></div>
        </form>
        <?php
    }

    public function render_trending_searches($settings, $unique_id) {
        if ( !isset($settings['trending_searches']) || !!!$settings['trending_searches'] ) return;
        $trending_searches = array_map(function ($item) {
            return trim($item);
        }, explode(',', $settings['trending_searches_list']));

        $trending_searches_limit = $settings['trending_searches_limit']['size'];
        $refresh_icon = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">
                        <path d="M29.76 8.16c0.288-1.248 0.896-3.744 1.056-4.992 0.064-0.512-0.064-0.864-0.544-0.992-0.512-0.16-0.832 0.096-1.056 0.672-0.288 0.768-0.768 3.296-0.768 3.296-2.080-2.688-5.248-4.736-8.704-5.6l-0.128-0.032c-4.128-0.992-8.32-0.256-11.904 1.888-7.136 4.352-9.088 12.672-6.752 19.008 0.096 0.224 0.352 0.416 0.64 0.48 0.224 0.064 0.448-0.032 0.608-0.096l0.032-0.032c0.416-0.192 0.608-0.672 0.384-1.184-2.048-5.856 0-12.992 6.208-16.672 3.136-1.856 6.72-2.432 10.432-1.536 2.784 0.672 5.728 2.368 7.552 4.8 0 0-2.56 0-3.424 0.032-0.64 0-0.96 0.16-1.056 0.608-0.128 0.48 0.128 0.992 0.608 1.024 1.568 0.096 5.664 0.128 5.664 0.128 0.16 0 0.192 0 0.384-0.032s0.416-0.192 0.544-0.352c0.096-0.16 0.224-0.416 0.224-0.416zM2.144 23.936c-0.288 1.248-0.864 3.744-1.056 4.992-0.064 0.512 0.064 0.864 0.544 0.992 0.512 0.128 0.832-0.128 1.056-0.704 0.288-0.768 0.768-3.296 0.768-3.296 2.080 2.688 5.28 4.736 8.736 5.6l0.128 0.032c4.128 0.992 8.32 0.256 11.904-1.888 7.136-4.32 9.088-12.672 6.752-19.008-0.096-0.224-0.352-0.416-0.64-0.48-0.224-0.064-0.448 0.032-0.608 0.096l-0.032 0.032c-0.416 0.192-0.608 0.672-0.384 1.184 2.048 5.856 0 12.992-6.208 16.672-3.136 1.856-6.72 2.432-10.432 1.536-2.784-0.672-5.728-2.368-7.552-4.8 0 0 2.56 0 3.424-0.032 0.64 0 0.96-0.16 1.056-0.608 0.128-0.48-0.128-0.992-0.608-1.024-1.568-0.096-5.664-0.128-5.664-0.128-0.16 0-0.192 0-0.384 0.032s-0.416 0.192-0.544 0.352c-0.096 0.16-0.256 0.448-0.256 0.448z"></path>
                    </svg>';

        $refresh_button = '';
        if ( $trending_searches_limit && count($trending_searches) > $trending_searches_limit )
            $refresh_button = '<span class="et_b-icon">' . $refresh_icon . '</span>' . '<span>'.esc_html__('Refresh', 'xstore-core').'</span>';

        $this->add_render_attribute( 'trending-searches-wrapper'.$unique_id, 'class', ['etheme-search-trending-searches-wrapper', 'hidden']);

        $this->add_render_attribute( 'trending-searches'.$unique_id, 'class', ['etheme-search-trending-searches']);

        $this->add_render_attribute( 'trending-searches-heading'.$unique_id, 'class', ['etheme-search-trending-searches-heading', 'etheme-search-form-title']);

        $this->add_render_attribute( 'trending-searches-refresh'.$unique_id, 'class', ['etheme-search-trending-searches-refresh']);

        ?>
        <div <?php echo $this->get_render_attribute_string( 'trending-searches-wrapper'.$unique_id ); ?>>
            <h3 <?php echo $this->get_render_attribute_string( 'trending-searches-heading'.$unique_id ); ?>>
                <?php echo esc_html__('Recommended for you', 'xstore-core'); ?>
                <span <?php echo $this->get_render_attribute_string( 'trending-searches-refresh'.$unique_id ); ?>><?php echo $refresh_button; ?></span>
            </h3>

            <div <?php echo $this->get_render_attribute_string( 'trending-searches'.$unique_id ); ?>></div>
        </div>
        <?php
    }
	
	/**
	 * Render Icon HTML.
	 *
	 * @param $settings
	 * @return void
	 *
	 * @since 4.0.10
	 *
	 */
    public function render_icon($settings, $prefix = 'button') {
	    $migrated = isset( $settings['__fa4_migrated'][$prefix.'_selected_icon'] );
	    $is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();

		if ( ! empty( $settings[$prefix.'_icon'] ) || ! empty( $settings[$prefix.'_selected_icon']['value'] ) ) : ?>
			<?php if ( $is_new || $migrated ) :
				\Elementor\Icons_Manager::render_icon( $settings[$prefix.'_selected_icon'], [ 'aria-hidden' => 'true' ] );
			else : ?>
				<i class="<?php echo esc_attr( $settings[$prefix.'_icon'] ); ?>" aria-hidden="true"></i>
			<?php endif;
		endif;

    }
	
	/**
	 * Return available filtered post types.
	 *
	 * @since 4.0.10
	 *
	 * @return mixed
	 */
    public function search_post_types() {
	    $post_types = array();
	    if ( class_exists('WooCommerce') ) {
	        $post_types['product'] = __('Product', 'xstore-core');
		    $post_types['product_variation'] = __('Product Variation', 'xstore-core');
	    }
	    
	    $post_types['post'] = __('Post', 'xstore-core');
	    $post_types['page'] = __('Page', 'xstore-core');
	    
	    return apply_filters('etheme_elementor_search_post_types', $post_types);
	    
    }

    public function render_animated_placehoder($settings, $unique_id) {
        if ( !isset($settings['animated_placeholder']) || !!!$settings['animated_placeholder'] || !$settings['animated_placeholder_text'] ) return;
        $this->add_render_attribute( 'animated-placeholder'.$unique_id, 'class', ['etheme-search-form-input','etheme-search-input-placeholder']);
        // reset input base placeholder if we use dynamic one 
        $this->remove_render_attribute( 'input'.$unique_id, 'placeholder' );
        $this->add_render_attribute( 'input'.$unique_id, 'placeholder', '');
        ?>

        <span <?php echo $this->get_render_attribute_string( 'animated-placeholder'.$unique_id ); ?>>
            <span class="etheme-search-placeholder">
                <?php echo $settings['animated_placeholder_heading']; ?>
            </span>
            <?php
            $rotating_text = explode( "\n", $settings['animated_placeholder_text'] ); ?>

            <?php foreach ( $rotating_text as $key => $text ) :
                $status_class = 1 > $key ? 'etheme-search-placeholder-text-active' : '';
                echo '<span class="etheme-search-placeholder-text ' . $status_class . '">'.
                    str_replace( ' ', '&nbsp;', trim($text) ) .
                    '</span>';
            endforeach;
            ?>
        </span>
        <?php
    }

    public function render_trending_searches_list($tags) {
        $render_html = '';
        $is_woocommerce = class_exists('WooCommerce');
        $post_type = $is_woocommerce ? 'product' : 'post';
        $url = $is_woocommerce ? wc_get_page_permalink( 'shop' ) : home_url();
        $query_args = $is_woocommerce && $this->should_redirect_to_archive() ? array(
            'post_type' => $post_type,
            'et_search' => 'true'
        ) : array();
        foreach ($tags as $tag) {
            $render_html .= '<a href="' . esc_url( add_query_arg(array_merge($query_args, array('s' => $tag)), $url )) . '">'.esc_html($tag) .'</a>';
        }
        return $render_html;
    }

    public function should_redirect_to_archive() {
        return true;
    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  4.1
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }
}
