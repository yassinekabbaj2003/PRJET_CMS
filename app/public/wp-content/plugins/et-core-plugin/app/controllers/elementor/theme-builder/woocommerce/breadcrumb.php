<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce;

use ETC\App\Classes\Elementor;

/**
 * Breadcrumb widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Breadcrumb extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-etheme_breadcrumb';
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
		return __( 'WooCommerce Breadcrumb', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-breadcrumb';
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
        return [ 'woocommerce-elements', 'shop', 'store', 'breadcrumbs', 'internal links', 'product' ];
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
    	return ['woocommerce-elements-archive', 'woocommerce-elements-single'];
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
		return [ 'etheme-breadcrumbs' ];
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
            'style',
            [
                'label' => esc_html__( 'Style', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left2',
                'options' => array(
                    'left2'   => esc_html__( 'Left inline', 'xstore-core' ),
                    'default' => esc_html__( 'Align center', 'xstore-core' ),
                    'left'    => esc_html__( 'Align left', 'xstore-core' ),
                ),
            ]
        );

        $this->add_control(
            'effect',
            [
                'label' => esc_html__( 'Effect', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => array(
                    'none'        => esc_html__( 'None', 'xstore-core' ),
                    'mouse'       => esc_html__( 'Parallax on mouse move', 'xstore-core' ),
                    'text-scroll' => esc_html__( 'Text animation on scroll', 'xstore-core' ),
                ),
            ]
        );

        $this->add_control(
            'separator_type',
            [
                'label' => esc_html__( 'Separator Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'arrow',
                'options' => array(
                    'slash' => esc_html__('Slash', 'xstore-core'),
                    'dot' => esc_html__('Dot', 'xstore-core'),
                    'square' => esc_html__('Square', 'xstore-core'),
                    'arrow' => esc_html__('Arrow', 'xstore-core'),
                ),
            ]
        );

        $this->add_control(
            'return_to_previous',
            [
                'label' => esc_html__( '"Return to previous page" link', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => esc_html__( 'Show title', 'xstore-core' ),
                'description' => esc_html__('Works for single product pages only', 'xstore-core'),
                'default' => 'yes',
                'type' => \Elementor\Controls_Manager::SWITCHER,
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

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .page-heading',
            ]
        );

        $this->start_controls_tabs('tabs_colors');

        $this->start_controls_tab(
            'tab_color_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core'),
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-breadcrumb' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_color_hover',
            [
                'label' => esc_html__('Links Hover', 'xstore-core'),
            ]
        );

        $this->add_control(
            'link_color_hover',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-breadcrumb a:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .page-heading',
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .page-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .page-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_separator_style',
            [
                'label' => esc_html__( 'Separator', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'separator_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'em' ],
                'range' => [
                    'px' => [
                        'min'  => 5,
                        'max'  => 30,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .page-heading .delimeter' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .page-heading .delimeter' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'separator_space',
            [
                'label' => esc_html__( 'Separator Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .page-heading .delimeter' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Title', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .page-heading .title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .page-heading .title' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_return_to_previous_style',
            [
                'label' => esc_html__( 'Return to previous page', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'return_to_previous!' => ''
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'return_to_previous_typography',
                'selector' => '{{WRAPPER}} .page-heading a.back-history',
            ]
        );

        $this->start_controls_tabs('tabs_return_to_previous_colors');

        $this->start_controls_tab(
            'tab_return_to_previous_color_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core'),
            ]
        );

        $this->add_control(
            'return_to_previous_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .page-heading a.back-history' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_return_to_previous_color_hover',
            [
                'label' => esc_html__('Hover', 'xstore-core'),
            ]
        );

        $this->add_control(
            'return_to_previous_color_hover',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .page-heading a.back-history:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 5.2
	 * @access protected
	 */
	protected function render() {

        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();

        $filters = array(
            'etheme_elementor_theme_builder' => '__return_true',
            'woocommerce_breadcrumb_delimiter' => array($this, 'separator_type'),
            'product_name_single' => !!$settings['show_title'] ? '__return_true' : '__return_false',
            'etheme_breadcrumbs_shop_step' => !!$settings['show_title'] ? '__return_true' : '__return_false',
            'return_to_previous' => !!$settings['return_to_previous'] ? '__return_true' : '__return_false',
            'breadcrumb_params' => array($this, 'filter_breadcrumb_params'),
        );
        foreach ($filters as $filter_name => $filter_func) {
            add_filter($filter_name, $filter_func);
        }

        woocommerce_breadcrumb();

        foreach (array_reverse($filters) as $filter_name => $filter_func) {
            remove_filter($filter_name, $filter_func);
        }
	}

	public function separator_type() {
        $separators = array(
            'slash' => '<i style="font-family: auto;">&#47;</i>',
            'dot' => '<i class="et-icon et-dot"></i>',
            'square' => '<i class="et-icon et-square-filled"></i>',
            'arrow' => '<i class="et-icon et-' . ( ! is_rtl() ? 'right' : 'left' ) . '-arrow"></i>'
        );
        return '<span class="delimeter">'.$separators[$this->get_settings_for_display('separator_type')].'</span>';
    }

    public function filter_breadcrumb_params($params) {
        $settings = $this->get_settings_for_display();
        $params['type'] = $settings['style'];
        $params['effect'] = $settings['effect'];
        $params['color'] = 'dark';
	    return $params;
    }

}
