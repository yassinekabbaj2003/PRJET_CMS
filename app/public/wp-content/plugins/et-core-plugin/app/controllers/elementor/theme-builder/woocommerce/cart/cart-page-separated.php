<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Cart;

use ETC\App\Classes\Elementor;

/**
 * Cart Page Separated widget.
 *
 * @since      5.2.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Cart_Page_Separated extends Cart_Page {

    /**
     * Get widget name.
     *
     * @since 5.2.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'woocommerce-cart-etheme_page_separated';
    }

    /**
     * Get widget title.
     *
     * @since 5.2.4
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Cart Page (Separated)', 'xstore-core' );
    }

    /**
     * Register widget controls.
     *
     * @since 5.2.4
     * @access protected
     */
    protected function register_controls() {

        parent::register_controls();

        $this->update_control('cols', [
            'type' => \Elementor\Controls_Manager::HIDDEN,
        ]);

        $this->update_control('cols_gap', [
            'default' => [
                'size' => 30,
                'unit' => 'px'
            ]
        ]);

//        $this->update_control('cart_totals_sticky', [
//            'type' => \Elementor\Controls_Manager::HIDDEN,
//        ]);

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'end',
            'of'   => 'section_general',
        ] );

        $this->add_control(
            'design_separated_color',
            [
                'label' => __( 'Overlay Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => '--et_ccsl-2d-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_page_design_class',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'separated',
                'prefix_class' => 'etheme-elementor-cart-',
            ]
        );

        $this->end_injection();
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2.4
     * @access protected
     */
    protected function render() {
        add_filter('etheme_elementor_cart_page_totals_inner_classes', function ($classes) {
            $classes[] = 'design-styled-part';
            return $classes;
        });
        parent::render();
    }
}
