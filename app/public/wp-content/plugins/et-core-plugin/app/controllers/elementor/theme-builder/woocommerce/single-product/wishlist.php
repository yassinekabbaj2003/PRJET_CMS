<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Wishlist widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Wishlist extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_wishlist';
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
		return __( 'Wishlist', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-wishlist et-elementor-product-widget-icon-only';
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
        return [ 'woocommerce', 'shop', 'store', 'wishlist', 'button', 'product' ];
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

        $activated_option = get_theme_mod('xstore_wishlist', false);

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        if ( !$activated_option ) {
            $this->add_control(
                'description',
                [
                    'raw' => sprintf(esc_html__('To use this widget, please, activate %s option.', 'xstore-core'),
                        '<a href="' . admin_url( '/customize.php?autofocus[section]=xstore-wishlist' ) . '" target="_blank">' . esc_html__('Wishlist', 'xstore-core') . '</a>'),
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
                    'value' => 'et-icon et-heart',
                    'library' => 'xstore-icons',
                ],
                'recommended' => [
                    'xstore-icons' => [
                        'heart',
                        'heart-2',
                        'star',
                        'star-2',
                    ],
                ],
            ]
        );

        $this->add_control(
            'add_to_wishlist',
            [
                'label' => __( '"Add to wishlist" text', 'xstore-core' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'Add to wishlist', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'browse_wishlist',
            [
                'label' => __( '"Browse wishlist" text', 'xstore-core' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'Browse wishlist', 'xstore-core' ),
            ]
        );

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
                'description' => esc_html__('Enable this option to make the "Wishlist" element icon styled only. Tip: Enable the "Tooltips" option above which will make the wishlist icon look better and more informative.', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'redirect_on_remove',
            [
                'label' => __( 'Redirect on remove', 'xstore-core' ),
                'description' => sprintf(esc_html__('Enable this option to automatically redirect customers to the wishlist page when they remove a product from their wishlist. Note: The wishlist page can be set in the "%1s" setting.', 'xstore-core'),
                    '<a href="' . admin_url( '/customize.php?autofocus[section]=xstore-wishlist' ) . '" target="_blank">' . esc_html__('Wishlist page', 'xstore-core') . '</a>'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

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

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'button_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
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
                    '{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .elementor-button.button',
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
                    '{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

	    if ( !get_theme_mod('xstore_wishlist', false) || !class_exists('\XStoreCore\Modules\WooCommerce\XStore_Wishlist' ) ) {
            echo '<div class="elementor-panel-alert elementor-panel-alert-error">' .
                sprintf(esc_html__( 'Please, activate %s option to use this widget.', 'xstore-core' ), '<a href="'.admin_url( '/customize.php?autofocus[section]=xstore-wishlist' ).'" target="_blank">'.esc_html__('Wishlist', 'xstore-core').'</a>') .
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

	    $wishlist_instance = \XStoreCore\Modules\WooCommerce\XStore_Wishlist::get_instance();

	    if ( $edit_mode ) {
            $wishlist_instance->init_added_products();
            $wishlist_instance->define_settings();
        }
		$settings = $this->get_settings_for_display();
	    $wishlist_button_settings = array(
            'is_single' => true,
            'only_icon' => $settings['only_icon'],
            'icon_position' => $settings['icon_align'],
            'has_tooltip' => $settings['tooltip'],
            'class' => 'elementor-button'
        );
	    if (!empty($settings['add_to_wishlist']))
	        $wishlist_button_settings['add_text'] = $settings['add_to_wishlist'];

        if (!empty($settings['browse_wishlist']))
            $wishlist_button_settings['remove_text'] = $settings['browse_wishlist'];

        switch ($settings['selected_icon']['library']) {
            case 'xstore-icons':
                if ( in_array($settings['selected_icon']['value'], array('et-icon et-bell', 'et-icon et-heart', 'et-icon et-heart-2', 'et-icon et-star', 'et-icon et-star-2'))) {
                    $wishlist_button_settings['add_icon_class'] = str_replace('et-icon ', '', $settings['selected_icon']['value']);
                    $wishlist_button_settings['remove_icon_class'] = str_replace('et-icon ', '', $settings['selected_icon']['value']).'-o';
                }
                else {
                    $wishlist_button_settings['add_icon_class'] = $wishlist_button_settings['remove_icon_class'] = false;
                    ob_start();
                    $this->render_icon($settings);
                    $wishlist_button_settings['custom_icon'] = ob_get_clean();
                }
                break;
            case 'svg':
                $wishlist_button_settings['custom_icon'] = str_replace(array('fill="black"', 'stroke="black"'), array('fill="currentColor"', 'stroke="currentColor"'), etheme_get_svg_icon( $settings['selected_icon']['value']['id'] ));
                break;
        }
		$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

		?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
                <?php $wishlist_instance->print_button($product->get_ID(), $wishlist_button_settings); ?>
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
