<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Tabs widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Tabs extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_tabs';
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
		return __( 'Single Product Tabs', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-product-tabs et-elementor-product-widget-icon-only';
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
        return [ 'woocommerce', 'shop', 'store', 'data', 'product', 'accordion', 'tabs', 'toggle' ];
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
     * @since 4.3
     * @access public
     *
     * @return array Widget dependency.
     */

    public function get_script_depends() {
        return [
            'et_single_product',
            'et_single_product_builder'
        ];
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
        $styles = [
            'etheme-tabs',
            'etheme-wc-tabs-types-style'
        ];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            $styles[] = 'etheme-toggles-by-arrow';
        }
        return $styles;
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
                'label' => esc_html__( 'Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => false,
                'options' => [
                    'simple' => esc_html__( 'Simple', 'xstore-core' ),
                    'folders' => esc_html__( 'Folders', 'xstore-core' ),
                    'overline' => esc_html__( 'Overline', 'xstore-core' ),
                    'underline' => esc_html__( 'Underline', 'xstore-core' ),
                    'accordion' => esc_html__( 'Accordion', 'xstore-core' ),
                ],
                'default'   => 'underline',
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => esc_html__( 'Horizontal', 'xstore-core' ),
                    'vertical' => esc_html__( 'Vertical', 'xstore-core' ),
                ],
                'default'   => 'horizontal',
                'condition' => [
                    'type!' => 'accordion'
                ]
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label'       => __('Alignment', 'xstore-core'),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'options'     => [
                    'start'    => __('Start', 'xstore-core'),
                    'center'  => __('Center', 'xstore-core'),
                    'end'   => __('End', 'xstore-core'),
                ],
                'selectors_dictionary'  => [
                    'start'          => 'flex-start',
                    'end' => 'flex-end'
                ],
                'default'     => 'center',
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .woocommerce-tabs.horizontal .wc-tabs' => 'justify-content: {{VALUE}};',
                    '.woocommerce {{WRAPPER}} .woocommerce-tabs.vertical .wc-tabs' => 'align-content: {{VALUE}};',
                ],
                'condition' => [
                    'type!' => 'accordion'
                ]
            ]
        );

        $this->add_control(
            'first_tab_opened',
            [
                'label'     => esc_html__( 'First tab opened', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'type' => 'accordion',
                    'tabs_opened' => ''
                ]
            ]
        );

        $this->add_control(
            'tabs_opened',
            [
                'label'     => esc_html__( 'Tabs opened', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'type' => 'accordion',
                    'first_tab_opened' => ''
                ]
            ]
        );

        $this->add_control(
            'tabs_scroll',
            [
                'label'     => esc_html__( 'Scrollable content', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_responsive_control(
            'tabs_scroll_height',
            [
                'label' => __( 'Content max height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'rem' ],
                'default' => [
                    'size' => 250,
                    'unit' => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 800,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tabs-with-scroll .wc-tab' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tabs_scroll!' => ''
                ]
            ]
        );

        $this->add_responsive_control(
            'tabs_spacing',
            [
                'label' => __( 'Space between (px)', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-tabs.horizontal .wc-tabs' => 'margin: 0 -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .woocommerce-tabs.horizontal .wc-tabs:after' => 'left: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .woocommerce-tabs.horizontal .wc-tabs .et-woocommerce-tab' => 'margin: 0 {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .woocommerce-tabs.vertical .wc-tabs' => 'margin: -{{SIZE}}{{UNIT}} 0;',
                    '{{WRAPPER}} .woocommerce-tabs.vertical .wc-tabs:after, .woocommerce-tabs.vertical.type-overline .wc-tabs:after, .woocommerce-tabs.vertical.type-underline .wc-tabs:after' => 'top: {{SIZE}}{{UNIT}}; bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .woocommerce-tabs.vertical .wc-tabs .et-woocommerce-tab' => 'margin: {{SIZE}}{{UNIT}} 0;',
                ],
                'condition' => [
                    'type!' => 'accordion'
                ]
            ]
        );

        $this->add_responsive_control(
            'tabs_according_spacing',
            [
                'label' => __( 'Inner space between', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .type-accordion .wc-tabs .et-woocommerce-tab' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'type' => 'accordion'
                ]
            ]
        );

        $this->add_responsive_control(
            'tabs_vertical_proportion',
            [
                'label' => __( 'Columns proportion', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', '%' ],
                'default' => [
                    'size' => 20,
                    'unit' => '%'
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-tabs.vertical .wc-tabs' => 'flex-basis: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .woocommerce-tabs.vertical .wc-tab' => 'flex-basis: calc(100% - {{SIZE}}{{UNIT}});',
                ],
                'condition' => [
                    'type!' => 'accordion',
                    'style' => 'vertical'
                ]
            ]
        );

        $this->end_controls_section();

        $repeater = new \Elementor\Repeater();

        $tabs_list = $this->product_tabs_list();

        $repeater->add_control(
            'tab_key',
            [
                'label'   => esc_html__( 'Tab name', 'xstore-core' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => $tabs_list,
                'default' => array_key_exists('description', $tabs_list) ? 'description' : array_key_first($tabs_list),
            ]
        );

        $repeater->add_control(
            'reviews_layout',
            [
                'label'     => esc_html__( 'Layout', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default' => 'two',
                'options' => [
                    'two' => esc_html__('Two columns', 'xstore-core'),
                    'one' => esc_html__('One column', 'xstore-core'),
                ],
                'condition' => [
                    'tab_key' => 'reviews'
                ]
            ]
        );

        $repeater->add_control(
            'tab_title',
            [
                'label' => __( 'Title', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Custom tab', 'xstore-core' ),
                'placeholder' => __( 'Custom tab', 'xstore-core' ),
                'dynamic' => [
                    'active' => true
                ],
                'condition' => [
                    'tab_key' => 'et_custom_tab'
                ]
            ]
        );

        $repeater->add_control(
            'tab_content',
            [
                'label' => __( 'Content', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'dynamic' => [
                    'active' => true
                ],
                'placeholder' => __( 'Lorem ipsum dolor ...', 'xstore-core' ),
                'condition' => [
                    'tab_key' => 'et_custom_tab'
                ]
            ]
        );

        $this->start_controls_section(
            'section_tabs',
            [
                'label' => esc_html__( 'Tabs', 'xstore-core' ),
            ]
        );

        //	Repeater
        $this->add_control(
            'tabs_list',
            [
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'tab_key' => 'description',
                    ],
                    [
                        'tab_key' => 'reviews',
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Navigation', 'xstore-core' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'navigation_border_color',
            [
                'label' => esc_html__( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-tabs:after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

//        $this->add_responsive_control(
//            'navigation_border_width',
//            [
//                'label' => esc_html__( 'Border Width', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SLIDER,
//                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
//                'range' => [
//                    'px' => [
//                        'max' => 20,
//                    ],
//                    'em' => [
//                        'max' => 2,
//                    ],
//                ],
//                'selectors' => [
//                    '{{WRAPPER}} .wc-tabs:after' => 'border-width: {{SIZE}}{{UNIT}}',
//                ],
//            ]
//        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tab_style',
            [
                'label' => esc_html__( 'Tab', 'xstore-core' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tab_typography',
                'selector' => '{{WRAPPER}} .wc-tabs .et-woocommerce-tab a',
            ]
        );

        $this->start_controls_tabs( 'tab_colors' );

        $this->start_controls_tab(
            'tab_colors_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'tab_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-tabs .et-woocommerce-tab:not(.active) a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wc-tabs .et-woocommerce-tab:not(.active):before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tab_background',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .wc-tabs .et-woocommerce-tab:not(.active)',
                'condition' => [
                    'type' => ['folders', 'accordion']
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_colors_active',
            [
                'label' => __( 'Active', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'tab_color_active',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-tabs .et-woocommerce-tab.active a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce-tabs.type-overline .wc-tabs .et-woocommerce-tab:before, {{WRAPPER}} .woocommerce-tabs.type-underline .wc-tabs .et-woocommerce-tab:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tab_background_active',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .wc-tabs .et-woocommerce-tab.active, {{WRAPPER}} .woocommerce-tabs.type-accordion .wc-tabs .et-woocommerce-tab.active',
            ]
        );

        $this->add_control(
            'tab_border_color_active',
            [
                'label' => esc_html__( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'tab_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-tabs .et-woocommerce-tab.active a, {{WRAPPER}} .woocommerce-tabs.type-accordion .wc-tabs .et-woocommerce-tab.active' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tab_border',
                'selector' => '{{WRAPPER}} .wc-tabs .et-woocommerce-tab a, {{WRAPPER}} .woocommerce-tabs.type-accordion .wc-tabs .et-woocommerce-tab',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tab_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wc-tabs .et-woocommerce-tab a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tab_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wc-tabs .et-woocommerce-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'style' => 'horizontal'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tab_panel_style',
            [
                'label' => esc_html__( 'Tab content', 'xstore-core' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tab_panel_border',
                'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs .panel',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tab_panel_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .woocommerce-tabs .panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	protected function render() {
        global $product;

        $product = Elementor::get_product();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( ! $product ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message();
            }
            return;
        }

        $settings = $this->get_settings_for_display();

        if ( !count($settings['tabs_list'] ) ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message(esc_html__('Please, set at least one product tab in widget setting', 'xstore-core'));
            }
            return;
        }

        if ( $settings['type'] == 'accordion' )
            wp_enqueue_style( 'etheme-toggles-by-arrow' );

        if ( $edit_mode )
            add_filter('etheme_should_reinit_swiper_script', '__return_true');

        $filters_list = array(
            'etheme_elementor_theme_builder' => '__return_true',
            'woocommerce_product_description_heading' => '__return_false',
            'woocommerce_product_additional_information_heading' => '__return_false',
            'etheme_single_product_tabs_list' => array($this, 'filter_tabs'),
            'etheme_single_product_tabs_force_load_assets' => '__return_false',
            'etheme_single_product_tabs_type' => array($this, 'tabs_type'),
            'etheme_single_product_tabs_style' => array($this, 'tabs_style'),
            'etheme_single_product_tabs_first_tab_opened' => (!!$settings['first_tab_opened'] ? '__return_true' : '__return_false'),
            'etheme_single_product_tabs_opened' => array($this, 'tabs_opened'),
            'etheme_single_product_tabs_scroll' => array($this, 'tabs_scroll'),
            'etheme_single_product_tabs_wrapper_classes' => array($this, 'tabs_wrapper_classes'),
            'etheme_single_product_builder_tabs' => array($this, 'tabs_list'),
            'etheme_single_product_builder_tabs_skip' => '__return_false'
        );
        foreach ($filters_list as $filter_name => $filter_action) {
            add_filter($filter_name, $filter_action);
        }

        add_filter( 'woocommerce_product_tabs', 'etheme_single_product_custom_tabs', 98 );

        wc_get_template( 'single-product/tabs/tabs.php' );

        remove_filter( 'woocommerce_product_tabs', 'etheme_single_product_custom_tabs', 98 );

        foreach (array_reverse($filters_list) as $filter_name => $filter_action) {
            remove_filter($filter_name, $filter_action);
        }
	}

	public function tabs_type() {
        $settings = $this->get_settings_for_display();
	    return $settings['type'];
    }

    public function tabs_style() {
        $settings = $this->get_settings_for_display();
        return $settings['style'];
    }

	public function filter_tabs($tabs) {
        $settings = $this->get_settings_for_display();
        if ( count($settings['tabs_list'] ) ) {
            $filtered_tabs_list = array();
            foreach ($settings['tabs_list'] as $tab) {
                if ( $tab['tab_key'] != 'et_custom_tab' ) {
                    $filtered_tabs_list[] = $tab;
                    continue;
                }
                $tab['tab_key'] .= $tab['_id'];
                $filtered_tabs_list[] = $tab;
            }
            return array_unique(array_map(function ($k) {
                return $k['tab_key'];
            }, $filtered_tabs_list));
        }

        return array();
    }

    public function tabs_list($tabs) {
        $settings = $this->get_settings_for_display();
        foreach ($tabs as $tab_key => $tab) {
            if ( !in_array($tab_key, array('et_custom_tab_01', 'et_custom_tab_02')) && strpos( $tab_key, 'et_custom_tab') !== false ) {
                $current_tab_settings = array_filter($settings['tabs_list'], function ($k) use ($tab_key) {
                    return $k['tab_key'] == 'et_custom_tab' && $k['_id'] == str_replace('et_custom_tab', '', $tab_key);
                });

                $current_tab_settings = $current_tab_settings[array_key_first($current_tab_settings)];
                $tab_title = $current_tab_settings['tab_title'] ?? esc_html__('Tab', 'xstore-core');
                $tab_content = $current_tab_settings['tab_content'] ?? '';
                $tabs[$tab_key] = array_merge($tabs[$tab_key], array(
                    'title' => $tab_title,
                    'callback' => function($custom_content) use ($tab_content) {
                        echo do_shortcode($tab_content);
                    })
                );
            }
        }
        return $tabs;
    }

    public function first_tab_opened() {
        $settings = $this->get_settings_for_display();
        return !!$settings['first_tab_opened'];
    }

    public function tabs_opened() {
        $settings = $this->get_settings_for_display();
        return !!$settings['tabs_opened'];
    }

    public function tabs_scroll() {
        $settings = $this->get_settings_for_display();
        return !!$settings['tabs_scroll'];
    }

    public function tabs_wrapper_classes($classes) {
        $settings = $this->get_settings_for_display();
        // check if we have at list one reviews = idealy only one reviews at all
        if ( count($settings['tabs_list']) && array_filter($settings['tabs_list'], function ($k) {
            return $k['tab_key'] == 'reviews';
        })) {
            $reviews_tab_one_column = array_filter($settings['tabs_list'], function ($k) {
                return $k['tab_key'] == 'reviews' && $k['reviews_layout'] == 'one';
            });
            $classes .= ' etheme-product-review-columns-'.($reviews_tab_one_column ? 'one':'two');
        }
        return $classes;
    }

    /**
     * Return filtered product tabs list items.
     *
     * @since 5.2
     *
     * @return mixed
     */
    public function product_tabs_list() {
        return apply_filters('etheme_product_tabs_list', array(
            'description'            => esc_html__( 'Description', 'xstore-core' ),
            'additional_information' => esc_html__( 'Additional information', 'xstore-core' ),
            'reviews'                => esc_html__( 'Reviews', 'xstore-core' ),
//            'et_custom_tab_01'       => esc_html__( 'Custom tab 01', 'xstore-core' ),
//            'et_custom_tab_02'       => esc_html__( 'Custom tab 02', 'xstore-core' ),
            'et_custom_tab'       => esc_html__( 'Custom tab', 'xstore-core' ),
            'single_custom_tab_01'   => esc_html__( 'Single product Custom tab', 'xstore-core' ),
        ) );
    }
}
