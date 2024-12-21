<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Cart;

use ETC\App\Classes\Elementor;

/**
 * Cart Totals widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Cart_Table extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-cart-etheme_table';
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
		return __( 'Cart Table', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-checkout-page et-elementor-cart-builder-new-widget-icon';
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
        return [ 'woocommerce', 'cart' ];
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
    	return ['woocommerce-elements'];
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
        $styles = [];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            $styles = [ 'etheme-cart-page', 'etheme-no-products-found', 'etheme-checkout-page' ];
        }
		return $styles;
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
        return ['etheme_elementor_checkout_page'];
    }
	
	/**
	 * Help link.
	 *
	 * @since 5.2
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('110-sales-booster', false);
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
            'design_type',
            [
                'label' => esc_html__( 'Design type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Classic', 'xstore-core' ),
                    'separated' => esc_html__( 'Separated', 'xstore-core' ),
                ],
            ]
        );

        $this->add_control(
            'design_type_separated_description',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf( __( 'Use this type for making next column filled with <a href="%s" target="_blank">full-height background</a>, we recommend you to add aside Cart Totals widget', 'xstore-core' ), 'https://prnt.sc/jS61G4_OQnK3' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'design_type' => 'separated',
                ],
            ]
        );

        $this->add_control(
            'design_separated_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => '--et_ccsl-2d-color: {{VALUE}};',
                ],
                'condition' => [
                    'design_type' => 'separated',
                ],
            ]
        );

        $this->add_control(
            'design_separated_direction',
            [
                'label' => esc_html__( 'Separated Direction', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'ltr',
                'options' => array(
                    'ltr' => __( 'LTR', 'xstore-core' ),
                    'rtl' => __( 'RTL', 'xstore-core' ),
                ),
                'prefix_class' => 'direction-',
                'condition' => [
                    'design_type' => 'separated',
                ],
            ]
        );

        $this->add_responsive_control(
            'design_separated_direction_offset',
            [
                'label' => __( 'Offset', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'design_type' => 'separated',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--design-element-overlay-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'clear_cart_button',
            [
                'label' => esc_html__('Clear Cart Button', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'update_cart_automatically',
            [
                'label' => esc_html__('Update Cart Automatically', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_fields',
            [
                'label' => esc_html__( 'Table Fields', 'xstore-core' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'status',
            [
                'label' => esc_html__('Enable', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_control(
            'label',
            [
                'label' => esc_html__( 'Label', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'status!' => ''
                ]
            ]
        );

        $repeater->add_control(
            'hide_mobile',
            [
                'label' => esc_html__('Hide on mobile', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'status!' => '',
                    'field_key!' => 'details'
                ]
            ]
        );

        $repeater->add_control(
            'details_image',
            [
                'label' => __( 'Show Image', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'status!' => '',
                    'field_key' => 'details',
                ]
            ]
        );

        $repeater->add_control(
            'details_remove',
            [
                'label' => __( 'Show Remove Link', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'status!' => '',
                    'field_key' => 'details',
                ]
            ]
        );

        $repeater->add_control(
            'quantity_style',
            [
                'label' => __( 'Style', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Inherit', 'xstore-core'),
                    'simple' => esc_html__('Simple', 'xstore-core'),
                    'circle' => esc_html__('Circle', 'xstore-core'),
                    'square' => esc_html__('Square', 'xstore-core'),
                ],
                'condition' => [
                    'field_key' => 'quantity',
                    'status!' => ''
                ]
            ]
        );

        $this->add_control(
            'table_fields',
            [
                'label' => esc_html__( 'Sortable Fields (Drag & Drop)', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'item_actions' => [
                    'add' => false,
                    'duplicate' => false,
                    'remove' => false,
                    'sort' => true,
                ],
                'default' => $this->get_table_field_defaults(),
                'title_field' => '{{{ field_label }}}',
            ]
        );
//
//        $this->add_control(
//            'update_cart_automatically',
//            [
//                'label' => esc_html__( 'Update Cart Automatically', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SWITCHER,
//                'selectors' => [
//                    '{{WRAPPER}}' => '{{VALUE}};',
//                ],
//                'selectors_dictionary' => [
//                    'yes' => '--update-cart-automatically-display: none;',
//                ],
//                'frontend_available' => true,
//                'render_type' => 'template',
//            ]
//        );
//
//        $this->add_control(
//            'update_cart_automatically_description',
//            [
//                'raw' => esc_html__( 'Changes to the cart will update automatically.', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::RAW_HTML,
//                'content_classes' => 'elementor-descriptor',
//            ]
//        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_additional',
            [
                'label' => esc_html__( 'Additional Options', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'additional_empty_cart_template_switch',
            [
                'label' => esc_html__( 'Customize empty cart', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'additional_template_description',
            [
                'raw' => sprintf(
                /* translators: 1: Saved templates link opening tag, 2: Link closing tag. */
                    esc_html__( 'Replaces the default WooCommerce Empty Cart screen with a custom template. (Don’t have one? Head over to %1$sSaved Templates%2$s)', 'xstore-core' ),
                    sprintf( '<a href="%s" target="_blank">', admin_url( 'edit.php?post_type=elementor_library&tabs_group=library#add_new' ) ),
                    '</a>'
                ),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor elementor-descriptor-subtle',
                'condition' => [
                    'additional_empty_cart_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_empty_cart_template_heading',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => esc_html__( 'Choose template', 'xstore-core' ),
                'condition' => [
                    'additional_empty_cart_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_empty_cart_content_type',
            [
                'label' 		=>	__( 'Content Type', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::SELECT,
                'options' => Elementor::get_saved_content_list(array('global_widget' => false)),
                'default'	=> 'custom',
                'condition' => [
                    'additional_empty_cart_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_empty_cart_save_template_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_saved_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'additional_empty_cart_template_switch!' => '',
                    'additional_empty_cart_content_type' => 'saved_template'
                ]
            ]
        );

        $this->add_control(
            'additional_empty_cart_static_block_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_static_block_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'additional_empty_cart_template_switch!' => '',
                    'additional_empty_cart_content_type' => 'static_block'
                ]
            ]
        );

        $this->add_control(
            'additional_empty_cart_template_content',
            [
                'type'        => \Elementor\Controls_Manager::WYSIWYG,
                'label'       => __( 'Content', 'xstore-core' ),
                'condition'   => [
                    'additional_empty_cart_template_switch!' => '',
                    'additional_empty_cart_content_type' => 'custom',
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'additional_empty_cart_saved_template',
            [
                'label' => __( 'Saved Template', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => Elementor::get_saved_content(),
                'default' => 'select',
                'condition' => [
                    'additional_empty_cart_template_switch!' => '',
                    'additional_empty_cart_content_type' => 'saved_template'
                ],
            ]
        );

        $this->add_control(
            'additional_empty_cart_static_block',
            [
                'label' => __( 'Static Block', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => Elementor::get_static_blocks(),
                'default' => 'select',
                'condition' => [
                    'additional_empty_cart_template_switch!' => '',
                    'additional_empty_cart_content_type' => 'static_block'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_cart_table_style',
            [
                'label' => esc_html__( 'Table', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'cart_thead_heading',
            [
                'label' => __( 'Head', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'cart_thead_typography',
                'selector' => '{{WRAPPER}} .woocommerce-cart-form table thead th',
            ]
        );

        $this->add_control(
            'cart_thead_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form table thead th' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_tbody_heading',
            [
                'label' => __( 'Content', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'cart_table_typography',
                'selector' => '{{WRAPPER}} .woocommerce-cart-form table tbody',
            ]
        );

        $this->add_control(
            'cart_table_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form table tbody' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_table_price_color',
            [
                'label' => __( 'Price Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form table tbody .amount' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_table_product_valign',
            [
                'label' 	=>	__( 'Vertical Align', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'xstore-core' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => __( 'Middle', 'xstore-core' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'xstore-core' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form table tbody' => '--et_table-v-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_table_product_space',
            [
                'label' => __( 'Items Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vw', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form' => '--et_table-space-v: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'cart_table_space',
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
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form table' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

//        $this->start_controls_section(
//            'section_actions_style',
//            [
//                'label' => esc_html__( 'Actions', 'xstore-core' ),
//                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
//            ]
//        );
//
//        $this->add_control(
//            'actions_space',
//            [
//                'label' => __( 'Spacing', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SLIDER,
//                'size_units' => [ 'px' ],
//                'range' => [
//                    'px' => [
//                        'min' => 0,
//                        'max' => 100,
//                        'step' => 1,
//                    ],
//                ],
//                'selectors' => [
//                    '{{WRAPPER}} .actions' => 'padding-top: {{SIZE}}{{UNIT}};',
//                ],
//            ]
//        );
//
//        $this->end_controls_section();

        $this->start_controls_section(
            'section_clear_cart_button_style',
            [
                'label' => __( 'Clear Cart Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'clear_cart_button!' => ''
                ]
            ]
        );

        $this->add_control(
            'clear_cart_button_selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'clear_cart_button_icon',
                'label_block' => false,
                'default' => [
                    'value' => 'et-icon et-trash',
                    'library' => 'xstore-icons',
                ],
            ]
        );

        $this->add_control(
            'clear_cart_button_icon_align',
            [
                'label' => __( 'Icon Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'xstore-core' ),
                    'right' => __( 'After', 'xstore-core' ),
                ],
                'condition' => [
                    'clear_cart_button_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'clear_cart_button_icon_indent',
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
                    '{{WRAPPER}} .clear-cart .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .clear-cart .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'clear_cart_button_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'clear_cart_button_typography',
                'selector' => '{{WRAPPER}} .clear-cart',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'clear_cart_button_text_shadow',
                'selector' => '{{WRAPPER}} .clear-cart',
            ]
        );

        $this->start_controls_tabs( 'tabs_clear_cart_button_style' );

        $this->start_controls_tab(
            'tab_clear_cart_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'clear_cart_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .clear-cart' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'clear_cart_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .clear-cart' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_clear_cart_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'clear_cart_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .clear-cart:hover, {{WRAPPER}} .clear-cart:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .clear-cart:hover svg, {{WRAPPER}} .clear-cart:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'clear_cart_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .clear-cart:hover, {{WRAPPER}} .clear-cart:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'clear_cart_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'clear_cart_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .clear-cart:hover, {{WRAPPER}} .clear-cart:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'clear_cart_button_border',
                'selector' => '{{WRAPPER}} .clear-cart, {{WRAPPER}} .clear-cart.button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'clear_cart_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .clear-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'clear_cart_button_box_shadow',
                'selector' => '{{WRAPPER}} .clear-cart',
            ]
        );

        $this->add_responsive_control(
            'clear_cart_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .clear-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_update_cart_button_style',
            [
                'label' => __( 'Update Cart Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'update_cart_automatically' => ''
                ]
            ]
        );

        $this->add_control(
            'update_cart_button_selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'update_cart_button_icon',
                'label_block' => false,
                'default' => [
                    'value' => 'et-icon et-compare',
                    'library' => 'xstore-icons',
                ],
            ]
        );

        $this->add_control(
            'update_cart_button_icon_align',
            [
                'label' => __( 'Icon Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'xstore-core' ),
                    'right' => __( 'After', 'xstore-core' ),
                ],
                'condition' => [
                    'update_cart_button_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'update_cart_button_icon_indent',
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
                    '{{WRAPPER}} button[name=update_cart] .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} button[name=update_cart] .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'update_cart_button_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'update_cart_button_typography',
                'selector' => '{{WRAPPER}} button[name=update_cart]',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'update_cart_button_text_shadow',
                'selector' => '{{WRAPPER}} button[name=update_cart]',
            ]
        );

        $this->start_controls_tabs( 'tabs_update_cart_button_style' );

        $this->start_controls_tab(
            'tab_update_cart_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'update_cart_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} button[name=update_cart]' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'update_cart_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button[name=update_cart]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_update_cart_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'update_cart_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button[name=update_cart]:hover, {{WRAPPER}} button[name=update_cart]:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} button[name=update_cart]:hover svg, {{WRAPPER}} button[name=update_cart]:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'update_cart_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button[name=update_cart]:hover, {{WRAPPER}} button[name=update_cart]:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'update_cart_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'update_cart_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} button[name=update_cart]:hover, {{WRAPPER}} button[name=update_cart]:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'update_cart_button_border',
                'selector' => '{{WRAPPER}} button[name=update_cart]',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'update_cart_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} button[name=update_cart]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'update_cart_button_box_shadow',
                'selector' => '{{WRAPPER}} button[name=update_cart]',
            ]
        );

        $this->add_responsive_control(
            'update_cart_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} button[name=update_cart]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
		
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 5.2
	 * @access protected
	 */
	protected function render() {

        if ( !class_exists('WooCommerce') ) {
            echo esc_html__('Install WooCommerce Plugin to use this widget', 'xstore-core');
            return;
        }

        if ( ! is_object( WC()->cart ) ) {
            return;
        }

        $empty_cart = WC()->cart->is_empty();
        if ( $empty_cart )
            $this->add_render_attribute( '_wrapper', 'class', 'full-width' );

        $settings = $this->get_settings_for_display();
        $update_cart_automatically = !!$settings['update_cart_automatically'];

        $fallback_text = '<h1 style="text-align: center;">'.esc_html__('YOUR SHOPPING CART IS EMPTY', 'xstore-core').'</h1><p style="text-align: center;">'.esc_html__('We invite you to get acquainted with an assortment of our shop. Surely you can find something for yourself!', 'xstore-core').'</p>';
        if ( wc_get_page_id( 'shop' ) > 0 ) :
            $fallback_text .= '<p class="text-center"><a class="btn black" href="' . get_permalink(wc_get_page_id('shop')) .'"><span>' . esc_html__('Return To Shop', 'xstore-core') . '</span></a></p>';
        endif;
        if ( $empty_cart ) {
            echo '<div class="woocommerce"><div class="wc-empty-cart-message">';
                if ( !!$settings['additional_empty_cart_template_switch'] ) {
                    switch ($settings['additional_empty_cart_content_type']) {
                        case 'custom':
                            if ( $empty_cart ) {
                                if (!empty($settings['additional_empty_cart_template_content']))
                                    $this->print_unescaped_setting('additional_empty_cart_template_content');
                                else
                                    echo $fallback_text;
                            }
                            break;
                        case 'global_widget':
                        case 'saved_template':
                        $prefix = 'additional_empty_cart_';
                            if (!empty($settings[$prefix.$settings[$prefix.'content_type']])):
                            //								echo \Elementor\Plugin::$instance->frontend->get_builder_content( $settings[$settings['content_type']], true );
                            $posts = get_posts(
                                [
                                    'name' => $settings[$prefix.$settings[$prefix.'content_type']],
                                    'post_type'      => 'elementor_library',
                                    'posts_per_page' => '1',
                                    'tax_query'      => [
                                        [
                                            'taxonomy' => 'elementor_library_type',
                                            'field'    => 'slug',
                                            'terms'    => str_replace(array('global_widget', 'saved_template'), array('widget', 'section'), $settings[$prefix.'content_type']),
                                        ],
                                    ],
                                    'fields' => 'ids'
                                ]
                            );

                            if (!isset($posts[0]) || !$content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($posts[0])) { // @todo maybe try to enchance TRUE value with on ajax only
                                if ( $empty_cart )
                                    echo esc_html__('We have imported popup template successfully. To setup it in the correct way please, save this page, refresh and select it in dropdown.', 'xstore-core');
                            } else {
                                if ( $empty_cart )
                                    echo $content;
                            }
                        elseif($empty_cart) :
                            echo $fallback_text;
                        endif;
                        break;
                        case 'static_block':
                            $prefix = 'additional_empty_cart_';
                            Elementor::print_static_block($settings[$prefix.$settings[$prefix.'content_type']]);
                        break;
                    }
                }
                else {
                    echo '<span class="etheme-elementor-cart-widgets-contain screen-reader-text hidden elementor-etheme_cart_placeholder">'.esc_html__('Placeholder for replacement with default shortcode', 'xstore-core').'</span>';
                }
            echo '</div></div>';
            return;
        }

        $this->add_render_attribute( 'button_text',
            [
                'class' => 'button-text',
            ]
        );

        $xstore_theme = defined('ETHEME_THEME_VERSION');

        if ( $xstore_theme ) {
            remove_action('woocommerce_before_quantity_input_field', 'et_quantity_minus_icon');
            remove_action('woocommerce_after_quantity_input_field', 'et_quantity_plus_icon');
        }

        add_action( 'woocommerce_before_quantity_input_field', 'etheme_woocommerce_before_add_to_cart_quantity_with_type', 10 );
        add_action( 'woocommerce_after_quantity_input_field', 'etheme_woocommerce_after_add_to_cart_quantity_with_type', 10 );

        add_filter('et_sales_booster_cart_checkout_progress_bar_enabled', '__return_false');
        $default_fields = $this->get_table_field_defaults(false);
        ?>
        <div class="woocommerce<?php if ( $settings['design_type'] == 'separated' ) echo ' design-styled-part'; ?>">
            <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

                <?php do_action( 'woocommerce_before_cart_table' ); ?>
                <div class="table-responsive">
                    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                        <thead>
                        <tr>
                            <?php
                            foreach ( $settings['table_fields'] as $repeater_field ) {
                                $field_classes = array('product-'.$repeater_field['field_key']);
                                if ( !in_array($repeater_field['field_key'], array('details')) ) {
                                    if ( !!!$repeater_field['status'] )
                                        continue;
                                    $field_classes[] = 'elementor-hidden-mobile';
                                }
                                $field_attr = array(
                                    'class="'.implode(' ', $field_classes).'"'
                                );
                                if ( in_array($repeater_field['field_key'], array('details')) && !!$repeater_field['details_image'] )
                                    $field_attr[] = 'colspan="2"';
                                if ( !in_array($repeater_field['field_key'], array('remove')) && !$repeater_field['label'] ) {
                                    $repeater_field['label'] = $default_fields[$repeater_field['field_key']]['label'];
                                }
                                ?>
                                <th <?php echo implode(' ', $field_attr) ?>><?php echo esc_html($repeater_field['label']); ?></th>
                                <?php
                            }
                            ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                        <?php
                        $rendered_fields = array();
                        foreach ( $settings['table_fields'] as $repeater_field ) {
                            if ( $repeater_field['field_key'] == 'details' )
                                $repeater_field['hide_mobile'] = ''; // force to display product title on mobile
                            $rendered_fields[$repeater_field['field_key']] = $repeater_field;
                        }
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            /**
                             * Filter the product name.
                             *
                             * @since 7.8.0
                             * @param string $product_name Name of the product in the cart.
                             */
                            $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                ?>
                                <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                                    <?php
                                    foreach ( $rendered_fields as $repeater_field_key => $repeater_field_options ) {
                                        if ( !!!$repeater_field_options['status'] ) continue;
                                        $field_classes = array();
                                        if ( (!in_array($repeater_field_key, array('details')) && !!$rendered_fields['details']['status']) )
                                            $field_classes[] = ' elementor-hidden-mobile';
                                        switch ($repeater_field_key) {
                                            case 'remove':
                                                ?>
                                                <td class="product-remove<?php echo implode(' ', $field_classes); ?>" data-title="<?php esc_attr_e( 'Remove', 'xstore-core' ); ?>">
                                                    <div class="product-remove">
                                                        <?php
                                                        echo apply_filters( 'woocommerce_cart_item_remove_link',
                                                            sprintf(
                                                                '<a href="%s" class="remove-item" title="%s">%s</a>',
                                                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                                /* translators: %s is the product name */
                                                                esc_attr( sprintf( __( 'Remove %s from cart', 'xstore-core' ), wp_strip_all_tags( $product_name ) ) ),
                                                                '<span class="et-icon et-trash"></span>'
                                                            ),
                                                            $cart_item_key );
                                                        ?>
                                                    </div>
                                                </td>
                                                <?php
                                                break;
                                            case 'details':
                                                $show_details = !!$repeater_field_options['status'];
                                                $show_image = $show_details && !!$repeater_field_options['details_image']; ?>
                                                <?php if ( $show_image ) : ?>
                                                <td class="product-name<?php echo implode(' ', $field_classes); ?>" data-title="<?php esc_attr_e( 'Product', 'xstore-core' ); ?>">
                                                    <div class="product-thumbnail">
                                                        <?php
                                                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                                        if ( ! $_product->is_visible() || ! $product_permalink){
                                                            echo wp_kses_post( $thumbnail );
                                                        } else {
                                                            printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                            <?php endif;
                                                $hide_mobile = !!$repeater_field_options['hide_mobile'];
                                                $product_name_classes = [];
                                                if ( !$show_details ) {
                                                    $product_name_classes[] = 'elementor-hidden-tablet';
                                                    $product_name_classes[] = 'elementor-hidden-desktop';
                                                }
                                                if ( $hide_mobile )
                                                    $product_name_classes[] = 'elementor-hidden-mobile';?>
                                                <td class="product-details<?php echo implode(' ', $field_classes); ?>">
                                                    <div class="cart-item-details">
                                                        <div class="<?php echo implode(' ', $product_name_classes); ?>">
                                                            <?php
                                                            if ( ! $_product->is_visible() || ! $product_permalink  ){
                                                                /**
                                                                 * Filter the product name.
                                                                 *
                                                                 * @since 7.8.0
                                                                 * @param string $product_name Name of the product in the cart.
                                                                 * @param array $cart_item The product in the cart.
                                                                 * @param string $cart_item_key Key for the product in the cart.
                                                                 */
                                                                echo wp_kses_post( $product_name );
                                                            } else {
                                                                /**
                                                                 * Filter the product name.
                                                                 *
                                                                 * @since 7.8.0
                                                                 * @param string $product_url URL the product in the cart.
                                                                 */
                                                                echo wp_kses_post( sprintf( '<a href="%s" class="product-title">%s</a>', esc_url( $product_permalink ), $product_name ) );
                                                            }
                                                            ?>
                                                        </div>
                                                        <?php
                                                        do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                                        echo wc_get_formatted_cart_item_data( $cart_item );

                                                        // Backorder notification
                                                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
                                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<div class="backorder_notification">' . esc_html__( 'Available on backorder', 'xstore-core' ) . '</div>', $product_id ) );

                                                        foreach ($rendered_fields as $rendered_field_local_key => $rendered_field_local_options) {
                                                            if ( !!!$rendered_field_local_options['status'] || !!$rendered_field_local_options['hide_mobile'] ) continue;
                                                            if ( in_array($rendered_field_local_key, array('remove', 'details', 'quantity'))) continue;

                                                            $field_local_classes = array();
                                                            if ( !!$rendered_field_local_options['status'] )
                                                                $field_local_classes = array(' elementor-hidden-tablet', 'elementor-hidden-desktop');
                                                            ?>
                                                            <div class="product-<?php echo $rendered_field_local_key . implode(' ', $field_local_classes); ?>">
                                                                <?php
                                                                switch ($rendered_field_local_key) {
                                                                    case 'price':
                                                                        if ( !!$rendered_fields['quantity']['status'] && !!$rendered_fields['quantity']['hide_mobile'] )
                                                                            echo (int) $cart_item['quantity'] . ' x ' . apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                                                        else {
                                                                            echo '<div class="product-price-quantity">';
                                                                            $quantity_input = !in_array($repeater_field_options['quantity_style'], array('', 'select'));
                                                                            $quantity_size = 'size-sm'; // could be as an option later
                                                                            if ( $quantity_input )
                                                                                add_filter('theme_mod_shop_quantity_type', array($this, 'return_input_value'));
                                                                            $sold_individually = $_product->is_sold_individually();
                                                                            if ( $sold_individually ) {
                                                                                $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                                                            } else {
                                                                                add_filter('woocommerce_quantity_input_classes', 'etheme_woocommerce_woocommerce_quantity_input_class_duplicated');
                                                                                $product_quantity = woocommerce_quantity_input( array(
                                                                                    'input_name'  => "cart[{$cart_item_key}][qty]",
                                                                                    'input_value' => $cart_item['quantity'],
                                                                                    'max_value'   => $_product->get_max_purchase_quantity(),
                                                                                    'min_value'   => '0',
                                                                                    'product_name'  => $product_name,
                                                                                ), $_product, false );
                                                                                remove_filter('woocommerce_quantity_input_classes', 'etheme_woocommerce_woocommerce_quantity_input_class_duplicated');
                                                                            }
                                                                            $quantity_style = $repeater_field_options['quantity_style'];
                                                                            if ( !$quantity_style )
                                                                                $quantity_style = 'square';
                                                                            echo apply_filters( 'woocommerce_cart_item_quantity', str_replace('{{quantity_type}}', $quantity_style . ' ' . $quantity_size, $product_quantity), $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                                                            if ( $quantity_input )
                                                                                remove_filter('theme_mod_shop_quantity_type', array($this, 'return_input_value'));
                                                                            echo  ' &times;&nbsp;' . apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                                                            echo '</div>';
                                                                        }
                                                                        break;
                                                                    case 'sku':
                                                                        $sku_label = $rendered_fields[$rendered_field_local_key]['label'];
                                                                        if ( !$sku_label ) {
                                                                            $sku_label = $default_fields[$rendered_field_local_key]['label'];
                                                                        }
                                                                        echo sprintf( esc_html__( '%s: %s', 'xstore-core' ), $sku_label, '<span>'.( $sku = $_product->get_sku() ) ? $sku : esc_html__( 'N/A', 'xstore-core' ) . '</span>' );
                                                                        break;
                                                                    case 'subtotal':
                                                                        $subtotal_label = $rendered_fields[$rendered_field_local_key]['label'];
                                                                        if ( !$subtotal_label ) {
                                                                            $subtotal_label = $default_fields[$rendered_field_local_key]['label'];
                                                                        }
                                                                        echo sprintf(__('%s: %s', 'xstore-core'), $subtotal_label, '<span>' . apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ) . '</span>');
                                                                        break;
                                                                }
                                                                ?>
                                                            </div>
                                                            <?php
                                                        }
                                                        if ( !!$repeater_field_options['details_remove'] || (!!$rendered_fields['remove']['status'] && !!!$rendered_fields['remove']['hide_mobile']) ) {
                                                            $field_local_classes = !!!$repeater_field_options['details_remove'] ? array(' elementor-hidden-tablet', 'elementor-hidden-desktop') : array();
                                                            ?>
                                                            <div class="product-remove<?php echo implode(' ', $field_local_classes); ?>">
                                                                <?php
                                                                echo apply_filters( 'woocommerce_cart_item_remove_link',
                                                                    sprintf(
                                                                        '<a href="%s" class="remove-item text-underline" title="%s">%s</a>',
                                                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                                        /* translators: %s is the product name */
                                                                        esc_attr( sprintf( __( 'Remove %s from cart', 'xstore-core' ), wp_strip_all_tags( $product_name ) ) ),
                                                                        esc_html__('Remove', 'xstore-core')
                                                                    ),
                                                                    $cart_item_key );
                                                                ?>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <?php
                                                break;
                                            case 'price':
                                                ?>
                                                <td class="product-price<?php echo implode(' ', $field_classes); ?>" data-title="<?php esc_attr_e( 'Price', 'xstore-core' ); ?>">
                                                    <?php
                                                    echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                                    ?>
                                                </td>
                                                <?php
                                                break;
                                            case 'sku':
                                                ?>
                                                <td class="product-sku<?php echo implode(' ', $field_classes); ?>" data-title="<?php esc_attr_e( 'SKU', 'xstore-core' ); ?>">
                                                    <?php
                                                    echo esc_html( ( $sku = $_product->get_sku() ) ? $sku : esc_html__( 'N/A', 'xstore-core' ) );
                                                    ?>
                                                </td>
                                                <?php
                                                break;
                                            case 'quantity':
                                                $quantity_input = !in_array($repeater_field_options['quantity_style'], array('', 'select'));
                                                if ( $quantity_input )
                                                    add_filter('theme_mod_shop_quantity_type', array($this, 'return_input_value'));
                                                ?>
                                                <td class="product-quantity<?php echo implode(' ', $field_classes); ?>" data-title="<?php esc_attr_e( 'Quantity', 'xstore-core' ); ?>">
                                                    <?php
                                                    $sold_individually = $_product->is_sold_individually();
                                                    if ( $sold_individually ) {
                                                        $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                                    } else {
                                                        $product_quantity = woocommerce_quantity_input( array(
                                                            'input_name'  => "cart[{$cart_item_key}][qty]",
                                                            'input_value' => $cart_item['quantity'],
                                                            'max_value'   => $_product->get_max_purchase_quantity(),
                                                            'min_value'   => '0',
                                                            'product_name'  => $product_name,
                                                        ), $_product, false );
                                                    }
                                                    $quantity_style = $repeater_field_options['quantity_style'];
                                                    $quantity_size = 'size-sm';
                                                    if ( !$quantity_style )
                                                        $quantity_style = 'square';
                                                    echo apply_filters( 'woocommerce_cart_item_quantity', str_replace('{{quantity_type}}', $quantity_style . ' ' . $quantity_size, $product_quantity), $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                                    if ( $quantity_input )
                                                        remove_filter('theme_mod_shop_quantity_type', array($this, 'return_input_value'));
                                                    ?>
                                                </td>
                                                <?php
                                                break;
                                            case 'subtotal':
                                                ?>
                                                <td class="product-subtotal<?php echo implode(' ', $field_classes); ?>" data-title="<?php esc_attr_e( 'Subtotal', 'xstore-core' ); ?>">
                                                    <?php
                                                    echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                                                    ?>
                                                </td>
                                                <?php
                                                break;
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                        }

                        do_action( 'woocommerce_cart_contents' );
                        ?>

                        <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                        </tbody>
                    </table>
                </div>

                <?php do_action( 'woocommerce_after_cart_table' ); ?>

                <div class="actions">
            <?php if ( wc_coupons_enabled() ) : ?>
                <div class="text-left mob-center">
                    <form class="checkout_coupon" method="post">
                        <div class="coupon">

                            <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'xstore-core' ); ?></label>
                            <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_html_e( 'Coupon code', 'xstore-core' ); ?>" />
                            <input type="submit" class="btn" name="apply_coupon" value="<?php esc_attr_e('OK', 'xstore-core'); ?>" />

                            <?php do_action('woocommerce_cart_coupon'); ?>

                        </div>
                    </form>
                </div>
            <?php endif; ?>
            <div class="mob-center actions-buttons">
                <?php if ( !!$settings['clear_cart_button'] ) : ?>
                    <a class="clear-cart btn bordered flex-inline align-items-center">
                    <?php
                        if ( $settings['clear_cart_button_icon_align'] == 'left')
                            $this->render_icon( $settings, 'clear_cart_button_' );
                        ?>
                        <span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
                            <?php esc_html_e('Clear shopping cart', 'xstore-core'); ?>
                        </span>
                        <?php
                        if ( $settings['clear_cart_button_icon_align'] == 'right')
                            $this->render_icon( $settings, 'clear_cart_button_' );
                        ?>
                    </a>
                <?php endif; ?>
                <button type="submit" class="btn bordered flex-inline align-items-center<?php if ( $update_cart_automatically ): ?> hidden<?php endif; ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'xstore-core' ); ?>">
                    <?php
                        if ( $settings['update_cart_button_icon_align'] == 'left')
                            $this->render_icon( $settings, 'update_cart_button_' );
                        ?>
                            <span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
                                <?php esc_html_e( 'Update cart', 'xstore-core' ); ?>
                            </span>
                        <?php
                        if ( $settings['update_cart_button_icon_align'] == 'right')
                            $this->render_icon( $settings, 'update_cart_button_' );
                        ?>
                </button>
                <?php wp_nonce_field( 'woocommerce-cart' ); ?>
                <?php do_action( 'woocommerce_cart_actions' ); ?>
            </div>
        </div>
            </form>
        </div>
        <?php
        remove_filter('et_sales_booster_cart_checkout_progress_bar_enabled', '__return_false');

        remove_action( 'woocommerce_before_quantity_input_field', 'etheme_woocommerce_before_add_to_cart_quantity_with_type', 10 );
        remove_action( 'woocommerce_after_quantity_input_field', 'etheme_woocommerce_after_add_to_cart_quantity_with_type', 10 );

        if ( $xstore_theme ) {
            add_action('woocommerce_before_quantity_input_field', 'et_quantity_minus_icon');
            add_action('woocommerce_after_quantity_input_field', 'et_quantity_plus_icon');
        }

        // On render widget from Editor - trigger the init manually.
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            ?>
            <style>
                [data-elementor-device-mode=desktop] .elementor-element-<?php echo $this->get_id(); ?> .elementor-hidden-desktop,
                [data-elementor-device-mode=tablet] .elementor-element-<?php echo $this->get_id(); ?> .elementor-hidden-tablet,
                [data-elementor-device-mode=mobile] .elementor-element-<?php echo $this->get_id(); ?> .elementor-hidden-mobile {
                    display: none !important;
                }
            </style>
        <?php }
	}

    /*
     * Use for filter to return input type forcelly
     */
    public function return_input_value() {
        return 'input';
    }

    /**
     * Get Billing Field Defaults
     *
     * Get defaults used for the billing details repeater control.
     *
     * @since 5.2
     *
     * @return array
     */
    private function get_table_field_defaults($reformat = true) {
        $fields = [
            'remove' => [
                'label' => esc_html__( 'Remove', 'xstore-core' ),
                'status' => ''
            ],
            'details' => [
                'label' => esc_html__( 'Product', 'xstore-core' ),
            ],
            'price' => [
                'label' => esc_html__( 'Price', 'xstore-core' ),
            ],
            'sku' => [
                'label' => esc_html__( 'SKU', 'xstore-core' ),
                'hide_mobile' => 'yes'
            ],
            'quantity' => [
                'label' => esc_html__( 'Quantity', 'xstore-core' ),
            ],
            'subtotal' => [
                'label' => esc_html__( 'Subtotal', 'xstore-core' ),
            ],
        ];

        return $reformat ? $this->reformat_field_defaults( $fields ) : $fields;
    }

    /**
     * Reformat Table Field Defaults
     *
     * Used with the `get_..._field_defaults()` methods.
     * Takes the fields array and converts it into the format expected by the repeater controls.
     *
     * @since 5.2
     *
     * @param $fields
     * @return array
     */
    private function reformat_field_defaults( $fields ) {
        $defaults = [];
        foreach ( $fields as $key => $value ) {
            $field_label = $value['label'];
            if ($key == 'details')
                $field_label = esc_html__('Product Details', 'xstore-core');
            $defaults[] = [
                'field_key' => $key,
                'field_label' => $field_label,
                'label' => $key == 'remove' ? '' : $value['label'],
                'status' => (!isset($value['status']) || $value['status']) ? 'yes' : '',
                'hide_mobile' => isset($value['hide_mobile']) ? $value['hide_mobile'] : '',
            ];
        }

        return $defaults;
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
}
