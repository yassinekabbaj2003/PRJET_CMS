<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Fake live views widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Fake_Live_Viewing extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_sales_booster_fake_live_viewing';
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
		return __( 'Fake Live Views (Sales Booster)', 'xstore-core' );
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
        return [ 'woocommerce', 'shop', 'store', 'sale', 'person', 'customer', 'payment', 'order', 'view', 'booster', 'product' ];
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
//	public function get_style_depends() {
//		return [ 'etheme-off-canvas', 'etheme-cart-widget' ];
//	}

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
	    $activated_option = get_option('xstore_sales_booster_settings_fake_live_viewing');

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
                    'raw' => sprintf(esc_html__('To use this widget, please, activate %1s within the %2s section.', 'xstore-core'),
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'fake_live_viewing', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Fake live viewing', 'xstore-core') . '</a>',
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'fake_live_viewing', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Sales Booster', 'xstore-core') . '</a>'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
        }
        else {

            $this->add_control(
                'description',
                [
                    'raw' => sprintf(esc_html__('This widget inherits global settings set in %s settings.', 'xstore-core'),
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'fake_live_viewing', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Sales Booster', 'xstore-core') . '</a>'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );

            $this->add_control(
                'html_wrapper_tag',
                [
                    'label' => esc_html__('HTML tag', 'xstore-core'),
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
                    'default' => 'div',
                ]
            );
        }

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Text Editor', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'align',
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
                    '{{WRAPPER}} .sales-booster-live-viewing' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-live-viewing' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .sales-booster-live-viewing',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .sales-booster-live-viewing',
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

        if ( !get_option('xstore_sales_booster_settings_fake_live_viewing') ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message(esc_html__('This message is shown only in edit mode.', 'xstore-core') . '<br/>' . sprintf(esc_html__('To use this widget, please, activate %1s within the %2s section.', 'xstore-core'),
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'fake_live_viewing', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank"><strong>' . esc_html__('Fake live viewing', 'xstore-core') . '</strong></a>',
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'fake_live_viewing', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank"><strong>' . esc_html__('Sales Booster', 'xstore-core') . '</strong></a>'));
            }
            return;
        }

        $settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'sales-booster-live-viewing' );

        if ( !function_exists('etheme_set_fake_live_viewing_count') ) return;

        $rendered_string = etheme_set_fake_live_viewing_count($product->get_ID());
        if ( $rendered_string ) { ?>
            <<?php echo $settings['html_wrapper_tag']; ?> <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
                <?php echo $rendered_string; ?>
            </<?php echo $settings['html_wrapper_tag']; ?>>
        <?php }
	}

}
