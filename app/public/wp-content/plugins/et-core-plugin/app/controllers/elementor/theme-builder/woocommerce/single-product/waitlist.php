<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Waitlist widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Waitlist extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_waitlist';
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
		return __( 'Waitlist', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-waitlist et-elementor-product-widget-icon-only';
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
        return [ 'woocommerce', 'shop', 'store', 'back in stock', 'notify', 'stock', 'waitlist', 'button', 'product' ];
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

        $activated_option = get_theme_mod('xstore_waitlist', false);

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'general_description',
            [
                'raw' => esc_html__('You can view the widget in the editor, but it will only appear on the live frontend if the current product is out of stock.', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        if ( !$activated_option ) {
            $this->add_control(
                'description',
                [
                    'raw' => sprintf(esc_html__('To use this widget, please, activate %s option.', 'xstore-core'),
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'xstore_waitlist', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Waitlist', 'xstore-core') . '</a>'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
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
                    'value' => 'et-icon et-bell',
                    'library' => 'xstore-icons',
                ],
                'recommended' => [
                    'xstore-icons' => [
                        'bell',
                        'bell-o',
                        'heart',
                        'heart-2',
                        'star',
                        'star-2',
                    ],
                ],
            ]
        );

        $this->add_control(
            'add_to_waitlist',
            [
                'label' => __( '"Add to waitlist" text', 'xstore-core' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'Notify when available', 'xstore-core' ),
            ]
        );

//        $this->add_control(
//            'browse_waitlist',
//            [
//                'label' => __( '"Browse waitlist" text', 'xstore-core' ),
//                'label_block' => true,
//                'type' => \Elementor\Controls_Manager::TEXT,
//                'dynamic' => [
//                    'active' => true,
//                ],
//                'placeholder' => __( 'Remove', 'xstore-core' ),
//            ]
//        );

        $this->add_control(
            'tooltip',
            [
                'label' => __( 'Tooltips', 'xstore-core' ),
                'description' => esc_html__('Enable this option to add tooltips to the element. Tip: tooltips will look better if the "Only icon" option below is enabled.', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'only_icon',
            [
                'label' => __( 'Only icon', 'xstore-core' ),
                'description' => esc_html__('Enable this option to make the "Waitlist" element icon styled only. Tip: Enable the "Tooltips" option above which will make the waitlist icon look better and more informative.', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

//        $this->add_control(
//            'redirect_on_remove',
//            [
//                'label' => __( 'Redirect on remove', 'xstore-core' ),
//                'description' => sprintf(esc_html__('Enable this option to automatically redirect customers to the waitlist page when they remove a product from their waitlist. Note: The waitlist page can be set in the "%1s" setting.', 'xstore-core'),
//                    '<a href="' . admin_url( '/customize.php?autofocus[section]=xstore-waitlist' ) . '" target="_blank">' . esc_html__('Waitlist page', 'xstore-core') . '</a>'),
//                'type' => \Elementor\Controls_Manager::SWITCHER,
//            ]
//        );

        $this->add_responsive_control(
            'button_align',
            [
                'label' => __( 'Alignment', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' => __( 'Left', 'xstore-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'xstore-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'xstore-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'xstore-core' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'prefix_class' => 'elementor%s-align-',
                'default' => '',
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
                    '{{WRAPPER}} .elementor-button' => 'min-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .button-text:last-child, {{WRAPPER}} .mtips .button-text:nth-last-child(2)' => 'margin-left: {{SIZE}}{{UNIT}}; padding-left: 0;',
                    '{{WRAPPER}} .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}}; padding-right: 0;',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
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

        $this->button_controls($this);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_popup_style',
            [
                'label' => __( 'Popup', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'popup_style_heading',
            [
                'label' => __( 'Popup Content', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'popup_border',
                'selector' => '{{WRAPPER}} .et-popup-content',
            ]
        );

        $this->add_control(
            'popup_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .et-popup-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'popup_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .et-popup-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'popup_box_shadow',
                'selector' => '{{WRAPPER}} .et-popup-content',
            ]
        );

        $this->add_control(
            'popup_heading_style_heading',
            [
                'label' => __( 'Popup Heading', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'popup_heading_typography',
                'selector' => '{{WRAPPER}} .et-popup-heading',
            ]
        );

        $this->add_control(
            'popup_heading_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .et-popup-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'popup_heading_space',
            [
                'label' => __( 'Space', 'xstore-core' ),
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
                    '{{WRAPPER}} .et-popup-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'popup_button_heading',
            [
                'label' => __( 'Popup Button', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->button_controls($this, 'popup_', '{{WRAPPER}} .et-availability-notify-popup button');

        $this->end_controls_section();
		
	}

    public function button_controls($_class, $prefix = '', $selector = '{{WRAPPER}} .elementor-button') {
        $_class->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => $prefix.'button_typography',
                'selector' => $selector,
            ]
        );

        $_class->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => $prefix.'button_text_shadow',
                'selector' => $selector,
            ]
        );

        $_class->start_controls_tabs( 'tabs_'.$prefix.'button_style' );

        $_class->start_controls_tab(
            'tab_'.$prefix.'button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $_class->add_control(
            $prefix.'button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    $selector => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $_class->add_control(
            $prefix.'button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    $selector => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $_class->end_controls_tab();

        $_class->start_controls_tab(
            'tab_'.$prefix.'button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $_class->add_control(
            $prefix.'button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selector . ':hover, ' . $selector . ':focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    $selector . ':hover svg, ' . $selector . ':focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $_class->add_control(
            $prefix.'button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selector . ':hover, ' . $selector . ':focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $_class->add_control(
            $prefix.'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    $prefix.'button_border_border!' => '',
                ],
                'selectors' => [
                    $selector . ':hover, ' . $selector . ':focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $_class->end_controls_tab();

        $_class->end_controls_tabs();

        $_class->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => $prefix.'button_border',
                'selector' => $selector . ', ' . $selector.'.button',
                'separator' => 'before',
                'fields_options' => [
//                    'border' => [
//                        'default' => 'none',
//                    ],
                    'color' => [
                        'default' => '#000000'
                    ]
                ],
            ]
        );

        $_class->add_control(
            $prefix.'button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $_class->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => $prefix.'button_box_shadow',
                'selector' => $selector,
            ]
        );

        $_class->add_responsive_control(
            $prefix.'button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
    }

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 5.2
	 * @access protected
	 */
	protected function render() {

	    if ( !get_theme_mod('xstore_waitlist', false) || !class_exists('\XStoreCore\Modules\WooCommerce\XStore_Waitlist' ) ) {
            echo '<div class="elementor-panel-alert elementor-panel-alert-error">' .
                sprintf(esc_html__( 'Please, activate %s option to use this widget.', 'xstore-core' ), '<a href="'.add_query_arg('etheme-sales-booster-tab', 'xstore_waitlist', admin_url('admin.php?page=et-panel-sales-booster')).'" target="_blank">'.esc_html__('Waitlist', 'xstore-core').'</a>') .
                '</div>';
            return;
        }

        global $product;

        $product = Elementor::get_product();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( ! $product ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message();
            }
            return;
        }

        if ( !$edit_mode ) {
            if ( !('outofstock' === $product->get_stock_status() || ( $product->managing_stock() && 0 === (int) $product->get_stock_quantity() && 'no' === $product->get_backorders() ) ) ) {
                return;
            }
        }

	    $waitlist_instance = \XStoreCore\Modules\WooCommerce\XStore_Waitlist::get_instance();

	    if ( $edit_mode ) {
            $waitlist_instance->init_added_products();
            $waitlist_instance->define_settings();
        }
		$settings = $this->get_settings_for_display();
	    $waitlist_button_settings = array(
            'is_single' => true,
            'only_icon' => $settings['only_icon'],
            'icon_position' => $settings['icon_align'],
            'has_tooltip' => $settings['tooltip'],
            'class' => 'elementor-button'
        );
	    if (!empty($settings['add_to_waitlist']))
	        $waitlist_button_settings['add_text'] = $settings['add_to_waitlist'];

//        if (!empty($settings['browse_waitlist']))
//            $waitlist_button_settings['remove_text'] = $settings['browse_waitlist'];

        switch ($settings['selected_icon']['library']) {
            case 'xstore-icons':
                if ( in_array($settings['selected_icon']['value'], array('et-icon et-bell', 'et-icon et-heart', 'et-icon et-heart-2', 'et-icon et-star', 'et-icon et-star-2'))) {
                    $waitlist_button_settings['add_icon_class'] = str_replace('et-icon ', '', $settings['selected_icon']['value']);
                    $waitlist_button_settings['remove_icon_class'] = str_replace('et-icon ', '', $settings['selected_icon']['value']).'-o';
                }
                else {
                    $waitlist_button_settings['add_icon_class'] = $waitlist_button_settings['remove_icon_class'] = false;
                    ob_start();
                    $this->render_icon($settings);
                    $waitlist_button_settings['custom_icon'] = ob_get_clean();
                }
                break;
            case 'svg':
                $waitlist_button_settings['custom_icon'] = str_replace(array('fill="black"', 'stroke="black"'), array('fill="currentColor"', 'stroke="currentColor"'), etheme_get_svg_icon( $settings['selected_icon']['value']['id'] ));
                break;
        }
        if ( $edit_mode ) {
            $waitlist_button_settings['force_display'] = true;
//            echo Elementor::elementor_frontend_alert_message(
//                sprintf(esc_html__('You can %sview the widget in the editor%s, but it will only appear on the live frontend if the %scurrent product is out of stock%s.', 'xstore-core'),
//                    '<strong>', '</strong>', '<strong>', '</strong>')
//            );
//            echo '<br/>';
        }
		$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

		?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
                <?php $waitlist_instance->print_button($product->get_ID(), $waitlist_button_settings); ?>
            </div>
		<?php
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
