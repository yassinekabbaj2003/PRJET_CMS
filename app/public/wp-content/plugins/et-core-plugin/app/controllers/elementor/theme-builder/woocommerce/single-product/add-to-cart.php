<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Add To Cart widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Add_To_Cart extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_add_to_cart';
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
		return __( 'Add to Cart / Buy Now Button', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-add-to-cart-button et-elementor-product-widget-icon-only';
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
        return [ 'woocommerce', 'shop', 'store', 'sale', 'price', 'swatches', 'cart', 'product' ];
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
            'etheme-quantity-types-style',
            'etheme-single-product-elements'
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
//    public function get_script_depends() {
//        return [ 'etheme_et_wishlist' ];
//    }
	
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

        $activated_swatch_option = get_theme_mod('enable_swatch', 1);
        $activated_buy_now_option = true;

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'variations_vertical',
            [
                'label'         =>  esc_html__( 'Vertical design', 'xstore-core' ),
                'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'variations',
                'prefix_class' => 'vertical-',
            ]
        );

        $this->add_control(
            'quantity_style',
            [
                'label' => __( 'Quantity Style', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'square',
                'options' => [
                    'simple' => esc_html__('Simple', 'xstore-core'),
                    'circle' => esc_html__('Circle', 'xstore-core'),
                    'square' => esc_html__('Square', 'xstore-core'),
                    'select' => esc_html__('Select', 'xstore-core'),
                    'none' => esc_html__('None', 'xstore-core'),
                ],
            ]
        );

        $this->end_controls_section();

        if ( $activated_swatch_option ) {
            $this->start_controls_section(
                'section_swatches',
                [
                    'label' => esc_html__( 'Swatches', 'xstore-core' ),
                ]
            );
            
            $this->add_control(
                'swatch_size',
                [
                    'label' => __( 'Size', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'inherit',
                    'options' => [
                        'inherit'   => esc_html__( 'Inherit', 'xstore-core' ),
                        'normal' => esc_html__( 'Normal', 'xstore-core' ),
                        'large'  => esc_html__( 'Large', 'xstore-core' ),
                    ],
                ]
            );
            $this->add_control(
                'swatch_design',
                [
                    'label' => __( 'Design', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'inherit',
                    'options' => [
                        'inherit'   => esc_html__( 'Inherit', 'xstore-core' ),
                        'default'   => esc_html__( 'Default', 'xstore-core' ),
                        'underline' => esc_html__( 'Underline', 'xstore-core' ),
                    ],
                ]
            );
            $this->add_control(
                'swatch_disabled_design',
                [
                    'label' => __( '"Out of Stock" design', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'inherit',
                    'options' => [
                        'inherit'   => esc_html__( 'Inherit', 'xstore-core' ),
                        'default'      => esc_html__( 'Default', 'xstore-core' ),
                        'line-thought' => esc_html__( 'Line-thought', 'xstore-core' ),
                        'cross-line'   => esc_html__( 'Cross line', 'xstore-core' ),
                    ],
                ]
            );
            $this->add_control(
                'swatch_multicolor_design',
                [
                    'label' => __( 'Multicolor design', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'inherit',
                    'options' => [
                        'inherit'   => esc_html__( 'Inherit', 'xstore-core' ),
                        'right' => esc_html__( 'Vertical', 'xstore-core' ),
                        'bottom'   => esc_html__( 'Horizontal', 'xstore-core' ),
                        'diagonal_1'   => esc_html__( 'Diagonal 1', 'xstore-core' ),
//                        'diagonal_2'   => esc_html__( 'Diagonal 2', 'xstore-core' ),
                    ],
                ]
            );
            $this->add_control(
                'swatch_shape',
                [
                    'label' => __( 'Shape', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'inherit',
                    'options' => [
                        'inherit'   => esc_html__( 'Inherit', 'xstore-core' ),
                        'default' => esc_html__( 'Default', 'xstore-core' ),
                        'square'  => esc_html__( 'Square', 'xstore-core' ),
                        'circle'  => esc_html__( 'Circle', 'xstore-core' ),
                    ],
                ]
            );

            $this->end_controls_section();

        }

        $this->start_controls_section(
            'section_button',
            [
                'label' => __( 'Button', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_stretch',
            [
                'label'         =>  esc_html__( 'Stretched button', 'xstore-core' ),
                'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                'default' => 'stretched',
                'return_value' => 'stretched',
                'prefix_class' => 'add-to-cart-button-',
            ]
        );

        $this->add_responsive_control(
            'button_min_width',
            [
                'label' => __( 'Min Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 1
                    ],
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:not(.et-single-buy-now)' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button_stretch' => ''
                ]
            ]
        );

        switch (get_theme_mod('cart_icon_et-desktop', 'type1')) {
            case 'type1':
                $default_icon = 'et-icon et-shopping-bag';
                break;
            case 'type2':
                $default_icon = 'et-icon et-shopping-basket';
                break;
            case 'type4':
                $default_icon = 'et-icon et-shopping-cart-2';
                break;
            default:
                $default_icon = 'et-icon et-shopping-cart';
                break;
        }

        $this->add_control(
            'selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'icon',
                'label_block' => false,
                'default' => [
                    'value' => $default_icon,
                    'library' => 'xstore-icons',
                ],
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label' => __( 'Icon Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'xstore-core' ),
                    'right' => __( 'After', 'xstore-core' ),
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'icon_indent',
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
                    '{{WRAPPER}} .single_add_to_cart_button .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .single_add_to_cart_button .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        if ( $activated_buy_now_option ) {

            $this->start_controls_section(
                'section_buy_now',
                [
                    'label' => __( 'Buy now button', 'xstore-core' ),
                ]
            );

            $this->add_control(
                'buy_now_button_position',
                [
                    'label' => __( 'Position', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'under',
                    'options' => [
                        'without' => __( 'Without', 'xstore-core' ),
                        'next' => __( 'Next to "Add to cart"', 'xstore-core' ),
                        'under' => __( 'Under "Add to cart"', 'xstore-core' ),
                    ],
                ]
            );

            $this->add_control(
                'buy_now_button_separator',
                [
                    'label'         =>  esc_html__( 'Hide separator', 'xstore-core' ),
                    'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} form.cart .et-or-wrapper' => 'margin: 0; height: 0; opacity: 0; visibility: hidden;',
                    ],
                    'condition' => [
                        'buy_now_button_position' => 'under'
                    ]
                ]
            );

            $this->add_control(
                'buy_now_button_stretch',
                [
                    'label'         =>  esc_html__( 'Stretched button', 'xstore-core' ),
                    'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                    'return_value' => 'stretched',
                    'default' => 'stretched',
                    'prefix_class' => 'buy-now-button-',
                    'condition' => [
                        'buy_now_button_position!' => 'without'
                    ]
                ]
            );

            $this->add_responsive_control(
                'buy_now_button_min_width',
                [
                    'label' => __( 'Min Width', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 500,
                            'step' => 1
                        ],
                        '%' => [
                            'min'  => 0,
                            'max'  => 100,
                            'step' => 1
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .et-single-buy-now' => 'min-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'buy_now_button_position!' => 'without',
                        'buy_now_button_stretch' => ''
                    ]
                ]
            );

            $this->add_control(
                'buy_now_selected_icon',
                [
                    'label' => __( 'Icon', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'skin' => 'inline',
                    'fa4compatibility' => 'buy_now_icon',
                    'label_block' => false,
                    'default' => [
                        'value' => $default_icon,
                        'library' => 'xstore-icons',
                    ],
                    'condition' => [
                        'buy_now_button_position!' => 'without'
                    ]
                ]
            );

            $this->add_control(
                'buy_now_icon_align',
                [
                    'label' => __( 'Icon Position', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left' => __( 'Before', 'xstore-core' ),
                        'right' => __( 'After', 'xstore-core' ),
                    ],
                    'condition' => [
                        'selected_icon[value]!' => '',
                        'buy_now_button_position!' => 'without'
                    ],
                ]
            );

            $this->add_control(
                'buy_now_icon_indent',
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
                        '{{WRAPPER}} .et-single-buy-now .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .et-single-buy-now .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'selected_icon[value]!' => '',
                        'buy_now_button_position!' => 'without'
                    ],
                ]
            );

            $this->end_controls_section();
        }

            $this->start_controls_section(
                'section_style_variations',
                [
                    'label' => __( 'Variations', 'xstore-core' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

//            $this->add_responsive_control(
//                'variations_cols_gap',
//                [
//                    'label' => __( 'Columns Gap', 'xstore-core' ),
//                    'type' => \Elementor\Controls_Manager::SLIDER,
//                    'size_units' => [ 'px' ],
//                    'range' => [
//                        'px' => [
//                            'min' => 0,
//                            'max' => 100,
//                            'step' => 1,
//                        ],
//                    ],
//                    'default' => [
//                        'size' => 30
//                    ],
//                    'selectors' => [
//                        '{{WRAPPER}}' => '--et_table-space-h: {{SIZE}}{{UNIT}};',
//                    ],
//                    'condition' => [
//                        'variations_vertical' => ''
//                    ]
//                ]
//            );
//
//            $this->add_responsive_control(
//                'variations_rows_gap',
//                [
//                    'label' => __( 'Rows Gap', 'xstore-core' ),
//                    'type' => \Elementor\Controls_Manager::SLIDER,
//                    'size_units' => [ 'px' ],
//                    'range' => [
//                        'px' => [
//                            'min' => 0,
//                            'max' => 100,
//                            'step' => 1,
//                        ],
//                    ],
//                    'default' => [
//                        'size' => 30
//                    ],
//                    'selectors' => [
//                        '{{WRAPPER}}' => '--et_table-space-v: {{SIZE}}{{UNIT}};',
//                    ],
//                ]
//            );

            $this->add_control(
                'heading_variations_label_style',
                [
                    'label' => esc_html__( 'Label', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'variation_label_typography',
                    'selector' => '.woocommerce {{WRAPPER}} form.cart table.variations label',
                ]
            );

            $this->add_control(
                'variation_label_color',
                [
                    'label' => __( 'Label Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} form.cart table.variations label' => 'color: {{VALUE}};',
                    ],
                ]
            );

        $this->add_control(
            'label_spacing',
            [
                'label'     => esc_html__('Spacing', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} form.cart table.variations label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_variations_select_style',
            [
                'label' => esc_html__( 'Select field', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'variations_select_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} form.cart table.variations td.value select' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'variations_select_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} form.cart table.variations td.value select, .woocommerce {{WRAPPER}} form.cart table.variations td.value:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'variations_select_border_color',
            [
                'label' => esc_html__( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} form.cart table.variations td.value select, .woocommerce {{WRAPPER}} form.cart table.variations td.value:before' => 'border: 1px solid {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'variations_select_typography',
                'selector' => '.woocommerce {{WRAPPER}} form.cart table.variations td.value select, .woocommerce div.product.elementor{{WRAPPER}} form.cart table.variations td.value:before',
                'fields_options' => [
                    'font_size' => [
                        'selector_value' => 'font-size: {{SIZE}}{{UNIT}}; height: auto',
                    ]
                ]
            ]
        );

        $this->add_control(
            'variations_select_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} form.cart table.variations td.value select, .woocommerce {{WRAPPER}} form.cart table.variations td.value:before' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_quantity',
            [
                'label' => __( 'Quantity', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'quantity_full_width',
            [
                'label'         =>  esc_html__( 'Full width', 'xstore-core' ),
                'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .quantity' => 'width: 100%;margin-right: 0;margin-left: 0;',
                    '.woocommerce {{WRAPPER}} .quantity select' => 'margin: 0 auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'quantity_size',
            [
                'label' => __( 'Font Size', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 0.1,
                        'max'  => 10,
                        'step' => 0.1
                    ],
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .quantity-wrapper' => 'font-size: {{SIZE}}{{UNIT}}; max-width: unset;',
                    '.woocommerce {{WRAPPER}} .quantity-wrapper input' => 'font-size: {{SIZE}}{{UNIT}};',
                    '.woocommerce {{WRAPPER}} .quantity-wrapper select' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quantity_height',
            [
                'label' => __( 'Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 0.1,
                        'max'  => 10,
                        'step' => 0.1
                    ],
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .quantity' => 'height: {{SIZE}}{{UNIT}};',
                    '.woocommerce {{WRAPPER}} .quantity-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                    '.woocommerce {{WRAPPER}} .quantity-wrapper input' => 'height: {{SIZE}}{{UNIT}};',
                    '.woocommerce {{WRAPPER}} .quantity-select select' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'quantity_input_width',
            [
                'label' => __( 'Input Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 0.1,
                        'max'  => 10,
                        'step' => 0.1
                    ],
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .quantity-wrapper input' => 'width: {{SIZE}}{{UNIT}};',
                    '.woocommerce {{WRAPPER}} .quantity-select select' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'quantity_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .quantity-select select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'quantity_style' => 'select' // @todo make for inputs too
                ]
            ]
        );

        $this->end_controls_section();

        if ( $activated_swatch_option ) {

            $this->start_controls_section(
                'section_style_swatch',
                [
                    'label' => __( 'Swatches', 'xstore-core' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            $this->start_controls_tabs('swatch_border_style_tabs');

            $this->start_controls_tab( 'swatch_border_style_normal',
                [
                    'label' => esc_html__('Normal', 'xstore-core')
                ]
            );

            $this->add_control(
                'swatch_color',
                [
                    'label' => __( 'Border Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} form.cart table.variations ul.st-swatch-preview li' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab( 'swatch_border_style_active',
                [
                    'label' => esc_html__('Hover/Active', 'xstore-core')
                ]
            );

            $this->add_control(
                'swatch_active_color',
                [
                    'label' => __( 'Border Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} form.cart table.variations .st-swatch-preview li.selected' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();
            $this->end_controls_tabs();

            $this->add_control(
                'heading_reset_variations_button_style',
                [
                    'label' => esc_html__( 'Clear variations button', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'reset_variations_button_typography',
                    'selector' => '{{WRAPPER}} .reset_variations',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'reset_variations_button_text_shadow',
                    'selector' => '{{WRAPPER}} .reset_variations',
                ]
            );

            $this->start_controls_tabs( 'tabs_reset_variations_button_style' );

            $this->start_controls_tab(
                'tab_reset_variations_button_normal',
                [
                    'label' => __( 'Normal', 'xstore-core' ),
                ]
            );

            $this->add_control(
                'reset_variations_button_color',
                [
                    'label' => __( 'Text Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'reset_variations_button_background_color',
                [
                    'label' => __( 'Background Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'tab_reset_variations_button_hover',
                [
                    'label' => __( 'Hover', 'xstore-core' ),
                ]
            );

            $this->add_control(
                'reset_variations_button_hover_color',
                [
                    'label' => __( 'Text Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations:hover, {{WRAPPER}} .reset_variations:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                        '{{WRAPPER}} .reset_variations:hover svg, {{WRAPPER}} .reset_variations:focus svg' => 'fill: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'reset_variations_button_background_hover_color',
                [
                    'label' => __( 'Background Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations:hover, {{WRAPPER}} .reset_variations:focus' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'reset_variations_button_hover_border_color',
                [
                    'label' => __( 'Border Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'condition' => [
                        'reset_variations_button_border_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations:hover, {{WRAPPER}} .reset_variations:focus' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'reset_variations_button_border',
                    'selector' => '{{WRAPPER}} .reset_variations',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'reset_variations_button_border_radius',
                [
                    'label' => __( 'Border Radius', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'reset_variations_button_box_shadow',
                    'selector' => '{{WRAPPER}} .reset_variations',
                ]
            );

            $this->add_responsive_control(
                'reset_variations_button_padding',
                [
                    'label' => __( 'Padding', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'reset_variations_button_spacing',
                [
                    'label'     => esc_html__('Spacing', 'xstore-core'),
                    'type'      => \Elementor\Controls_Manager::SLIDER,
                    'range'     => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations' => 'margin-top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();

        }

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => __( 'Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .single_add_to_cart_button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'button_text_shadow',
                'selector' => '{{WRAPPER}} .single_add_to_cart_button',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:hover, {{WRAPPER}} .single_add_to_cart_button:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .single_add_to_cart_button:hover svg, {{WRAPPER}} .single_add_to_cart_button:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:hover, {{WRAPPER}} .single_add_to_cart_button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:hover, {{WRAPPER}} .single_add_to_cart_button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .single_add_to_cart_button, {{WRAPPER}} .single_add_to_cart_button.button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .single_add_to_cart_button',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'selected_icon_proportion',
            [
                'label' => esc_html__( 'Icon Proportion', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'separator' => 'before',
                'size_units' => [ 'em', 'px', '%', 'custom' ],
                'range' => [
                    'em' => [
                        'max' => 5,
                        'min' => 0,
                        'step' => .1
                    ],
                    'px' => [
                        'max' => 100,
                        'min' => 0
                    ],
                    '%' => [
                        'max' => 70,
                        'min' => 0
                    ],
                ],
                'default' => [
                    'unit' => 'em',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button > i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .single_add_to_cart_button > svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        if ( $activated_buy_now_option ) {
            $this->start_controls_section(
                'section_buy_now_button_style',
                [
                    'label' => __( 'Buy now button', 'xstore-core' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'buy_now_button_position!' => 'without'
                    ]
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'buy_now_button_typography',
                    'selector' => '{{WRAPPER}} .et-single-buy-now',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'buy_now_button_text_shadow',
                    'selector' => '{{WRAPPER}} .et-single-buy-now',
                ]
            );

            $this->start_controls_tabs( 'tabs_buy_now_button_style' );

            $this->start_controls_tab(
                'tab_buy_now_button_normal',
                [
                    'label' => __( 'Normal', 'xstore-core' ),
                ]
            );

            $this->add_control(
                'buy_now_button_color',
                [
                    'label' => __( 'Text Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .et-single-buy-now' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                        '{{WRAPPER}}' => '--single-buy-now-button-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'buy_now_button_background_color',
                [
                    'label' => __( 'Background Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .et-single-buy-now' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}}' => '--single-buy-now-button-background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'tab_buy_now_button_hover',
                [
                    'label' => __( 'Hover', 'xstore-core' ),
                ]
            );

            $this->add_control(
                'buy_now_button_hover_color',
                [
                    'label' => __( 'Text Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .et-single-buy-now:hover, {{WRAPPER}} .et-single-buy-now:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                        '{{WRAPPER}} .et-single-buy-now:hover svg, {{WRAPPER}} .et-single-buy-now:focus svg' => 'fill: {{VALUE}};',
                        '{{WRAPPER}}' => '--single-buy-now-button-color-hover: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'buy_now_button_background_hover_color',
                [
                    'label' => __( 'Background Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .et-single-buy-now:hover, {{WRAPPER}} .et-single-buy-now:focus' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}}' => '--single-buy-now-button-background-color-hover: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'buy_now_button_hover_border_color',
                [
                    'label' => __( 'Border Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'condition' => [
                        'buy_now_button_border_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .et-single-buy-now:hover, {{WRAPPER}} .et-single-buy-now:focus' => 'border-color: {{VALUE}}; --single-buy-now-button-border-color-hover: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'buy_now_button_border',
                    'selector' => '{{WRAPPER}} .et-single-buy-now, {{WRAPPER}} .et-single-buy-now.button',
                    'separator' => 'before',
                    'fields_options' => [
                        'border' => [
                            'selectors' => [
                                '{{SELECTOR}}' => 'border-style: {{VALUE}}; --single-buy-now-button-border-style: {{VALUE}};',
                            ],
                        ],
                        'color' => [
                            'selectors' => [
                                '{{SELECTOR}}' => 'border-color: {{VALUE}}; --single-buy-now-button-border-color: {{VALUE}};',
                            ],
                        ]
                    ]
                ]
            );

            $this->add_control(
                'buy_now_button_border_radius',
                [
                    'label' => __( 'Border Radius', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .et-single-buy-now' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'buy_now_button_box_shadow',
                    'selector' => '{{WRAPPER}} .et-single-buy-now',
                ]
            );

            $this->add_responsive_control(
                'buy_now_button_padding',
                [
                    'label' => __( 'Padding', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .et-single-buy-now' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'buy_now_selected_icon_proportion',
                [
                    'label' => esc_html__( 'Icon Proportion', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'separator' => 'before',
                    'size_units' => [ 'em', 'px', '%', 'custom' ],
                    'range' => [
                        'em' => [
                            'max' => 5,
                            'min' => 0,
                            'step' => .1
                        ],
                        'px' => [
                            'max' => 100,
                            'min' => 0
                        ],
                        '%' => [
                            'max' => 70,
                            'min' => 0
                        ],
                    ],
                    'default' => [
                        'size' => 1,
                        'unit' => 'em',
                    ],
                    'condition' => [
                        'buy_now_selected_icon[value]!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .et-single-buy-now > i' => 'font-size: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .et-single-buy-now > svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                    ],
                ]
            );

            $this->end_controls_section();
        }
		
	}

	/**
	 * Render countdown widget output on the frontend.
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
        $product_type = $product->get_type();
        $quantity_type_o = get_theme_mod('shop_quantity_type', 'input');
        $quantity_type = $settings['quantity_style'];

        add_filter('etheme_elementor_theme_builder', '__return_true');
        add_filter('etheme_show_single_stock', '__return_false');
        add_filter('xstore_wishlist_print_single_product_button', '__return_false');
        add_filter('xstore_waitlist_print_single_product_button', '__return_false');
        add_filter('xstore_compare_print_single_product_button', '__return_false');
        if ( $product_type != 'variable' )
            add_filter('woocommerce_get_stock_html', '__return_empty_string');

        remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
        remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
        add_action( 'woocommerce_before_quantity_input_field', array($this, 'before_add_to_cart_quantity'), 10 );
        add_action( 'woocommerce_after_quantity_input_field', array($this, 'after_add_to_cart_quantity'), 10 );
        add_filter( 'woocommerce_cart_item_quantity', array($this, 'cart_item_quantity'), 3, 20 );
        if ( in_array('select', array($quantity_type, $quantity_type_o) ) ) {
            $select_type_ranges_o = get_theme_mod('shop_quantity_select_ranges', '1-5');
            $select_type_ranges = get_theme_mod('product_quantity_select_ranges', $select_type_ranges_o);
            add_filter('theme_mod_shop_quantity_type', function ($value) use ($quantity_type) {
                return $quantity_type == 'select' ? 'select' : 'input';
            });
            add_filter('theme_mod_shop_quantity_select_ranges', function ($value) use ($select_type_ranges) {
                return $select_type_ranges;
            });
        }

        $swatch_options_2_filter = array(
            'swatch_size',
            'swatch_design',
            'swatch_disabled_design',
            'swatch_multicolor_design',
            'swatch_shape'
        );
        foreach ($swatch_options_2_filter as $swatch_option_2_filter) {
            add_filter('sten_wc_single_'.$swatch_option_2_filter, array($this, 'filter_'.$swatch_option_2_filter));
        }

        $this->add_render_attribute( 'wrapper', [
            'class' => 'etheme-add-to-cart-form',
        ]);

        if ( $settings['buy_now_button_position'] != 'without' ) {
            // reset and force activate if not activated before
            add_filter('etheme_buy_now_enabled', '__return_true');
            remove_action( 'woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn', 10 );
            add_action( 'woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn', 10 );
        }
        switch ($settings['buy_now_button_position']) {
            case 'without':
                remove_action( 'woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn', 10 );
                break;
            case 'next':
                add_filter('etheme_buy_now_or_separator', '__return_false');
                break;

        }

        add_filter( 'esc_html', array($this, 'unescape_html' ), 10, 2 );

        add_filter('woocommerce_product_single_add_to_cart_text', array($this, 'add_to_cart_button_text'), 10, 2);

        add_filter('etheme_buy_now_button_text', array($this, 'buy_now_button_text'), 10, 1);

        if ( $edit_mode ) {
            // fix for hidden quantity
            add_filter('woocommerce_quantity_input_type', function($type) {
                return 'number';
            });
        }

        ?>

        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <?php
                woocommerce_template_single_add_to_cart();

                // ADDED: actions for thirdparty plugins
                if (! $product->is_purchasable()){
                    do_action('etheme_single_product_unpurchasable_after_add_to_cart');
                } elseif (!$product->is_in_stock()) {
                    do_action('etheme_single_product_out_of_stock_after_add_to_cart');
                }{
                    do_action('etheme_single_product_after_add_to_cart');
                }
            ?>
        </div>

        <?php

        remove_filter('etheme_buy_now_button_text', array($this, 'buy_now_button_text'), 10, 1);

        remove_filter('woocommerce_product_single_add_to_cart_text', array($this, 'add_to_cart_button_text'), 10, 2);

        remove_filter( 'esc_html', array($this, 'unescape_html' ), 10, 2 );

        foreach ($swatch_options_2_filter as $swatch_option_2_filter) {
            remove_filter('sten_wc_single_'.$swatch_option_2_filter, array($this, 'filter_'.$swatch_option_2_filter));
        }

        if ( in_array('select', array($quantity_type, $quantity_type_o) ) ) {
            add_filter('theme_mod_shop_quantity_type', function ($value) use ($quantity_type_o) {
                return $quantity_type_o;
            });
            add_filter('theme_mod_shop_quantity_select_ranges', function ($value) use ($select_type_ranges_o) {
                return $select_type_ranges_o;
            });
        }

        remove_filter( 'woocommerce_cart_item_quantity', array($this, 'cart_item_quantity'), 3, 20 );
        remove_action( 'woocommerce_before_quantity_input_field', array($this, 'before_add_to_cart_quantity'), 10 );
        remove_action( 'woocommerce_after_quantity_input_field', array($this, 'after_add_to_cart_quantity'), 10 );

        switch ($settings['buy_now_button_position']) {
            case 'without':
                add_action( 'woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn', 10 );
                break;
            case 'next':
                remove_filter('etheme_buy_now_or_separator', '__return_false');
                break;
        }
        add_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
        add_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );

        if ( $product_type != 'variable' )
            remove_filter('woocommerce_get_stock_html', '__return_empty_string');
        remove_filter('etheme_show_single_stock', '__return_false');
        remove_filter('xstore_wishlist_print_single_product_button', '__return_false');
        remove_filter('xstore_waitlist_print_single_product_button', '__return_false');
        remove_filter('xstore_compare_print_single_product_button', '__return_false');
        remove_filter('etheme_elementor_theme_builder', '__return_true');

        // On render widget from Editor - trigger the init manually.
        if ( $edit_mode ) {
            ?>
            <script>
                jQuery(document).ready(function () {
                    if ( etTheme.reinitSwatches !== undefined )
                        etTheme.reinitSwatches();
                });
            </script>
            <?php
        }
	}

    public function buy_now_button_text($text) {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'buy_now_button_text',
            [
                'class' => 'button-text',
            ]
        );

        ob_start();

        if ( $settings['buy_now_icon_align'] == 'left')
            $this->render_icon( $settings, 'buy_now_' );

        ?>
        <span <?php echo $this->get_render_attribute_string( 'buy_now_button_text' ); ?>><?php echo $text; ?></span>

        <?php
        if ( $settings['buy_now_icon_align'] == 'right')
            $this->render_icon( $settings, 'buy_now_' );

        return ob_get_clean();
    }

	public function add_to_cart_button_text($text, $_product) {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'button_text',
            [
                'class' => 'button-text',
            ]
        );

        ob_start();

        if ( $settings['icon_align'] == 'left')
            $this->render_icon( $settings );

        ?>
        <span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>><?php echo $text; ?></span>

        <?php
        if ( $settings['icon_align'] == 'right')
            $this->render_icon( $settings );

        return ob_get_clean();
    }

    protected function render_icon($settings, $prefix = '') {
        $migrated = isset( $settings['__fa4_migrated'][$prefix.'selected_icon'] );
        $is_new = empty( $settings[$prefix.'icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
        if ( ! empty( $settings[$prefix.'icon'] ) || ! empty( $settings[$prefix.'selected_icon']['value'] ) ) : ?>
            <?php if ( $is_new || $migrated ) :
                \Elementor\Icons_Manager::render_icon( $settings[$prefix.'selected_icon'], [ 'aria-hidden' => 'true' ] );
            else : ?>
                <i class="<?php echo esc_attr( $settings[$prefix.'icon'] ); ?>" aria-hidden="true"></i>
            <?php endif;
        endif;
    }

    public function unescape_html( $safe_text, $text ) {
        return $text;
    }

    function cart_item_quantity( $product_quantity, $cart_item_key, $cart_item ) {
        ob_start();
        $this->before_add_to_cart_quantity();
        echo $product_quantity;
        $this->after_add_to_cart_quantity();

        return ob_get_clean();
    }

	public function before_add_to_cart_quantity() {
	    $settings = $this->get_settings_for_display();
	    ?>
        <div class="quantity-wrapper type-<?php echo $settings['quantity_style']; ?>" data-label="<?php echo esc_html__( 'Quantity:', 'xstore-core' ); ?>">
		<?php if ( !in_array($settings['quantity_style'], array('none', 'select')) ) : ?>
            <span class="minus et-icon et_b-icon">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width=".7em" height=".7em" viewBox="0 0 24 24">
                    <path d="M23.52 11.4h-23.040c-0.264 0-0.48 0.216-0.48 0.48v0.24c0 0.264 0.216 0.48 0.48 0.48h23.040c0.264 0 0.48-0.216 0.48-0.48v-0.24c0-0.264-0.216-0.48-0.48-0.48z"></path>
                </svg>
            </span>
		<?php endif;
    }

    public function after_add_to_cart_quantity() {
	    $settings = $this->get_settings_for_display();
        if ( !in_array($settings['quantity_style'], array('none', 'select')) ) : ?>
            <span class="plus et-icon et_b-icon">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width=".7em" height=".7em" viewBox="0 0 24 24">
                    <path d="M23.52 11.4h-10.92v-10.92c0-0.264-0.216-0.48-0.48-0.48h-0.24c-0.264 0-0.48 0.216-0.48 0.48v10.92h-10.92c-0.264 0-0.48 0.216-0.48 0.48v0.24c0 0.264 0.216 0.48 0.48 0.48h10.92v10.92c0 0.264 0.216 0.48 0.48 0.48h0.24c0.264 0 0.48-0.216 0.48-0.48v-10.92h10.92c0.264 0 0.48-0.216 0.48-0.48v-0.24c0-0.264-0.216-0.48-0.48-0.48z"></path>
                    </svg>
                </span>
        <?php endif; ?>
        </div>
        <?php
    }

	public function filter_swatch_size($value) {
        $settings = $this->get_settings_for_display();
	    return $settings['swatch_size'] != 'inherit' ? $settings['swatch_size'] : $value;
    }
    public function filter_swatch_design($value) {
        $settings = $this->get_settings_for_display();
        return $settings['swatch_design'] != 'inherit' ? $settings['swatch_design'] : $value;
    }
    public function filter_swatch_disabled_design($value) {
        $settings = $this->get_settings_for_display();
        return $settings['swatch_disabled_design'] != 'inherit' ? $settings['swatch_disabled_design'] : $value;
    }
    public function filter_swatch_multicolor_design($value) {
        $settings = $this->get_settings_for_display();
        return $settings['swatch_multicolor_design'] != 'inherit' ? $settings['swatch_multicolor_design'] : $value;
    }
    public function filter_swatch_shape($value) {
        $settings = $this->get_settings_for_display();
        return $settings['swatch_shape'] != 'inherit' ? $settings['swatch_shape'] : $value;
    }


}
