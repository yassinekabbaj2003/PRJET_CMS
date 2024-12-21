<?php

namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive;

/**
 * Result count widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Result_Count extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * @return string Widget name.
     * @since 5.2
     * @access public
     *
     */
    public function get_name()
    {
        return 'woocommerce-archive-etheme_result_count';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     * @since 5.2
     * @access public
     *
     */
    public function get_title()
    {
        return __('Result count', 'xstore-core');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     * @since 5.2
     * @access public
     *
     */
    public function get_icon()
    {
        return 'eight_theme-elementor-icon et-elementor-result-count et-elementor-product-builder-widget-icon-only';
    }

    /**
     * Get widget keywords.
     *
     * @return array Widget keywords.
     * @since 5.2
     * @access public
     *
     */
    public function get_keywords()
    {
        return ['woocommerce', 'shop', 'store', 'select', 'count', 'category', 'product', 'archive'];
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     * @since 5.2
     * @access public
     *
     */
    public function get_categories()
    {
        return ['woocommerce-elements-archive'];
    }

    /**
     * Get widget dependency.
     *
     * @return array Widget dependency.
     * @since 5.2
     * @access public
     *
     */
//	public function get_style_depends() {
//		return [ 'etheme-off-canvas', 'etheme-cart-widget' ];
//	}

    /**
     * Get widget dependency.
     *
     * @return array Widget dependency.
     * @since 4.1.4
     * @access public
     *
     */
//    public function get_script_depends() {
//        return [ 'etheme_et_wishlist' ];
//    }

    /**
     * Help link.
     *
     * @return string
     * @since 4.1.5
     *
     */
    public function get_custom_help_url()
    {
        return etheme_documentation_url('110-sales-booster', false);
    }

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label'                => esc_html__( 'Alignment', 'xstore-core' ),
                'type'                 => \Elementor\Controls_Manager::CHOOSE,
                'options'              => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'xstore-core' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'xstore-core' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'xstore-core' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors'            => [
                    '{{WRAPPER}} .woocommerce-result-count' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bottom_spacing',
            [
                'label'                => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type'                 => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'yes',
                'selectors'            => [
                    '{{WRAPPER}} .woocommerce-result-count' => 'margin-bottom: 0;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'text_typography',
                'label'    => esc_html__( 'Typography', 'xstore-core' ),
                'selector' => '{{WRAPPER}} .woocommerce-result-count',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => esc_html__( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-result-count' => 'color: {{VALUE}}',
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
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $edit_mode ) {
            wc_setup_loop(
                [
                    'total' => 20,
                    'per_page' => 12
                ]
            );
        }

        add_filter('etheme_elementor_theme_builder', '__return_true');

        woocommerce_result_count();

        remove_filter('etheme_elementor_theme_builder', '__return_true');

        if ( $edit_mode ) {
            wc_reset_loop();
        }

    }

}
