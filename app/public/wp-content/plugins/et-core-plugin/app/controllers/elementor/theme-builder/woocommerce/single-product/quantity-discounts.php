<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Estimated delivery widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Quantity_Discounts extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_sales_booster_quantity_discounts';
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
		return __( 'Quantity Discounts (Sales Booster)', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-sales-booster et-elementor-product-widget-icon-only';
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
        return [ 'woocommerce', 'shop', 'store', 'sale', 'percentage', 'quantity', 'booster', 'product' ];
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
		return [ 'etheme-sale-booster-quantity-discounts' ];
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
	    $activated_option = get_option('xstore_sales_booster_settings_quantity_discounts');

        if ( !$activated_option ) {

            $this->start_controls_section(
                'section_general',
                [
                    'label' => esc_html__('General', 'xstore-core'),
                ]
            );

            $this->add_control(
                'alert_description',
                [
                    'raw' => sprintf(esc_html__('To use this widget, please, activate %1s within the %2s section.', 'xstore-core'),
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'quantity_discounts', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Quantity discounts', 'xstore-core') . '</a>',
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'quantity_discounts', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Sales Booster', 'xstore-core') . '</a>'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );

            $this->end_controls_section();

        }

        $this->start_controls_section(
            'section_box_style',
            [
                'label' => esc_html__( 'Box', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'description',
            [
                'raw' => sprintf(esc_html__('This widget inherits global settings set in %s settings.', 'xstore-core'),
                    '<a href="' . add_query_arg('etheme-sales-booster-tab', 'quantity_discounts', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Sales Booster', 'xstore-core') . '</a>'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'box_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .sales-booster-quantity-discounts'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'label' => esc_html__('Border', 'xstore-core'),
                'selector' => '{{WRAPPER}} .sales-booster-quantity-discounts',
            ]
        );


        $this->add_control(
            'box_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-quantity-discounts' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__('Padding', 'xstore-core'),
                'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-quantity-discounts' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .sales-booster-quantity-discounts',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_style',
            [
                'label' => esc_html__( 'Item', 'xstore-core' ),
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
                    '{{WRAPPER}} .sales-booster-quantity-discounts' => '--rows-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .quantity-discount-item'
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .quantity-discount-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => esc_html__('Border', 'xstore-core'),
                'selector' => '{{WRAPPER}} .quantity-discount-item',
                'fields_options' => [
                    'border' => [
                        'default' => ''
                    ],
                    'color' => [
                        'condition' => [
                            'border!' => ['none'],
                        ],
                    ]
                ]
            ]
        );

        $this->end_controls_section();

        // discount title
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __( 'Title', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'html_title_tag',
            [
                'label'       => esc_html__( 'HTML tag', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h4',
            ]
        );

        $this->add_responsive_control(
            'title_align',
            [
                'label' => esc_html__( 'Alignment', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'xstore-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'xstore-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'xstore-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'xstore-core' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .quantity-discounts-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .quantity-discounts-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity-discounts-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_space',
            [
                'label' => __( 'Bottom Space', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .quantity-discounts-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // discount title
        $this->start_controls_section(
            'section_discount_title_style',
            [
                'label' => __( 'Discount title', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'html_discount_title_tag',
            [
                'label'       => esc_html__( 'HTML tag', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h5',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'discount_title_typography',
                'selector' => '{{WRAPPER}} .quantity-discount-name',
            ]
        );

        $this->add_control(
            'discount_title_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity-discount-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'discount_title_space',
            [
                'label' => __( 'Bottom Space', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .quantity-discount-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // discount subtitle
        $this->start_controls_section(
            'section_discount_subtitle_style',
            [
                'label' => __( 'Discount subtitle', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'discount_subtitle_typography',
                'selector' => '{{WRAPPER}} .quantity-discount-suggest',
            ]
        );

        $this->add_control(
            'discount_subtitle_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity-discount-suggest' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => __( 'Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .sales-booster-quantity-discounts button' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .sales-booster-quantity-discounts button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'button_text_shadow',
                'selector' => '{{WRAPPER}} .sales-booster-quantity-discounts button',
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
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-quantity-discounts button' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-quantity-discounts button' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .sales-booster-quantity-discounts button:hover, {{WRAPPER}} .sales-booster-quantity-discounts button:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .sales-booster-quantity-discounts button:hover svg, {{WRAPPER}} .sales-booster-quantity-discounts button:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-quantity-discounts button:hover, {{WRAPPER}} .sales-booster-quantity-discounts button:focus' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .sales-booster-quantity-discounts button:hover, {{WRAPPER}} .sales-booster-quantity-discounts button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .sales-booster-quantity-discounts button',
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
                    '{{WRAPPER}} .sales-booster-quantity-discounts button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .sales-booster-quantity-discounts button',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-quantity-discounts button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_icon_style',
            [
                'label' => __( 'Button Icon', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'icon',
                'label_block' => false,
                'default' => [
                    'value' => 'et-icon et-shopping-bag',
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
                    '{{WRAPPER}} .sales-booster-quantity-discounts button .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .sales-booster-quantity-discounts button .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
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

//        $settings = $this->get_settings_for_display();

        if ( !get_option('xstore_sales_booster_settings_quantity_discounts') ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message(esc_html__('This message is shown only in edit mode.', 'xstore-core') . '<br/>' . sprintf(esc_html__('To use this widget, please, activate %1s within the %2s section.', 'xstore-core'),
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'quantity_discounts', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank"><strong>' . esc_html__('Quantity discounts', 'xstore-core') . '</strong></a>',
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'quantity_discounts', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank"><strong>' . esc_html__('Sales Booster', 'xstore-core') . '</strong></a>'));
            }
            return;
        }

		if (!class_exists('Etheme_Sales_Booster_Quantity_Discounts')) return;

        add_filter( 'etheme_sales_booster_quantity_discounts', '__return_true' );
        $quantity_discounts = \Etheme_Sales_Booster_Quantity_Discounts::get_instance();
        $quantity_discounts->init( $product );
        $quantity_discounts->add_actions();
        add_filter( 'et_quantity_discounts_settings', array( $this, 'filter_settings' ) );

        if ( $edit_mode ) {
            $quantity_discounts->args['hidden_default'] = false;
        }
        $quantity_discounts->output();
        remove_filter( 'et_quantity_discounts_settings', array( $this, 'filter_settings' ) );
        remove_filter( 'etheme_sales_booster_quantity_discounts', '__return_true' );
	}

	public function filter_settings($local_settings) {
        $settings = $this->get_settings_for_display();
        ob_start();
        $this->render_icon($settings);
        $icon = ob_get_clean();
        if ( $icon ) {
            // if it is built-in xstore-icons library then just send an icon class to settings
            if ( $settings['selected_icon']['library'] == 'xstore-icons' )
                $local_settings['button_icon'] = str_replace('et-icon ', '', $settings['selected_icon']['value']);
            else
                $local_settings['custom_icon'] = $icon;
        }
        $local_settings['force_load_assets'] = false;
        $local_settings['title_tag'] = $settings['html_title_tag'];
        $local_settings['icon_position'] = $settings['icon_align'];
        $local_settings['discount_title_tag'] = $settings['html_discount_title_tag'];
        return $local_settings;
    }
    protected function render_icon($settings) {
        $migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
        $is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
        if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
            <?php if ( $is_new || $migrated ) :
                \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
            else : ?>
                <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
            <?php endif;
        endif;
    }
}
