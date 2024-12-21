<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

/**
 * Product stock widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Product_Stock extends \ElementorPro\Modules\Woocommerce\Widgets\Product_Stock {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_stock';
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
		return 'eight_theme-elementor-icon et-elementor-product-stock et-elementor-product-widget-icon-only';
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
		parent::register_controls();

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'start',
            'of'   => 'section_product_stock_style',
        ] );

        $this->add_control(
            'advanced_colors',
            [
                'label' 		=> __( 'Advanced Stock Colors', 'xstore-core' ),
                'description' => sprintf(esc_html__('Choose the colors for the different product stock levels. Note: Here you can check the "%1s" of your products configured on your website.', 'xstore-core'),
                    '<a href="' . admin_url( "admin.php?page=wc-settings&tab=products&section=inventory" ) . '" target="_blank">' . esc_html__( 'Low stock threshold values', 'xstore-core' ) . '</a>'),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $default_colors = get_theme_mod('product_stock_colors', array(
            'step1' => '#2e7d32',
            'step2' => '#f57f17',
            'step3' => '#c62828',
        ));

        foreach (array(
             'full_stock' => esc_html__('Full Stock Color', 'xstore-core'),
             'middle_stock' => esc_html__('Middle stock (sold 50%+) Color', 'xstore-core'),
             'low_stock' => esc_html__('Low Stock Color', 'xstore-core'),
                 ) as $stock_key => $stock_title ) {
            $selector = '.woocommerce {{WRAPPER}} .stock';
            $step = 'step2';
            switch ($stock_key) {
                case 'middle_stock':
                    $step_selector = '.step-1';
                    $step = 'step1';
                    break;
                case 'low_stock':
                    $step_selector = '.step-3';
                    $step = 'step3';
                    break;
                default:
                    $step_selector = '.step-2';
                    break;
            }
            $selector .= $step_selector;
            $this->add_control(
                $stock_key.'_text_color',
                [
                    'label' => $stock_title,
                    'type' => \Elementor\Controls_Manager::COLOR,
//                    'default' => $default_colors[$step],
                    'selectors' => [
                        $selector => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'advanced_colors!' => ''
                    ]
                ]
            );
        }
        $this->end_injection();

        $this->update_control('text_color', [
            'condition' => [
                'advanced_colors' => ''
            ]
        ]);

        $this->remove_control('wc_style_warning');
	}

    protected function render() {
        // add filter only in editor because on frontend it is done from theme code
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() )
            add_filter( 'woocommerce_get_availability_class', 'etheme_wc_get_availability_class', 20, 2 );
        parent::render();
    }
}
