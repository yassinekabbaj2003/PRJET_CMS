<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout;

use ETC\App\Classes\Elementor;

/**
 * Checkout Page Separated widget.
 *
 * @since      5.2.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Checkout_Page_Separated extends Checkout_Page {
    /**
     * Get widget name.
     *
     * @since 5.2.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'woocommerce-checkout-etheme_page_separated';
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
        return __( 'Checkout Page (Separated)', 'xstore-core' );
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
            'checkout_page_design_class',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'separated',
                'prefix_class' => 'etheme-elementor-checkout-',
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
        add_filter('etheme_elementor_checkout_page_order_details_inner_classes', function ($classes) {
            $classes[] = 'design-styled-part';
            return $classes;
        });

        parent::render();
    }

}
