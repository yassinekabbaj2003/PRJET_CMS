<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Product Additional Information widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Product_Attributes extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @since 5.2
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'woocommerce-product-etheme_attributes';
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
        return __( 'Product Attributes', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-additional-information et-elementor-product-widget-icon-only';
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
        return [ 'woocommerce', 'shop', 'store', 'attributes', 'variations', 'product' ];
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
     * Help link.
     *
     * @since 5.2
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

//        $this->add_control(
//            'description',
//            [
//                'raw'             => esc_html__('This element has no settings to configure.', 'xstore-core'),
//                'type'            => \Elementor\Controls_Manager::RAW_HTML,
//                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
//            ]
//        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__( 'Layout', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'vertical' => esc_html__( 'Vertical', 'xstore-core' ),
                    'table' => esc_html__( 'Horizontal', 'xstore-core' ),
                ],
                'default' => 'table',
                'render_type' => 'template',
                'prefix_class' => 'etheme-product-attributes-layout-'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'general_style',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'rows_gap',
            [
                'label' => __( 'Rows Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--items-v-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_style_section',
            [
                'label' => esc_html__( 'Content', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} table',
            ]
        );

        $this->start_controls_tabs('items_colors_tabs');

        $this->start_controls_tab( 'label_color_tab',
            [
                'label' => esc_html__('Label', 'xstore-core')
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'value_color_tab',
            [
                'label' => esc_html__('Value', 'xstore-core')
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'separator_style_section',
            [
                'label' => esc_html__( 'Separator', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'separator_width',
            [
                'label' => __( 'Separator Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--separator-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separator_style',
            [
                'label' => __( 'Style', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Default', 'xstore-core' ),
                    'solid' => __( 'Solid', 'xstore-core' ),
                    'double' => __( 'Double', 'xstore-core' ),
                    'dotted' => __( 'Dotted', 'xstore-core' ),
                    'dashed' => __( 'Dashed', 'xstore-core' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => '--separator-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'separator_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--separator-color: {{VALUE}}',
                ],
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
    protected function render()
    {
        global $product;

        $product = Elementor::get_product();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if (!$product) {
            if ($edit_mode) {
                echo Elementor::elementor_frontend_alert_message();
            }
            return;
        }

        wc_display_product_attributes($product);
    }
}
