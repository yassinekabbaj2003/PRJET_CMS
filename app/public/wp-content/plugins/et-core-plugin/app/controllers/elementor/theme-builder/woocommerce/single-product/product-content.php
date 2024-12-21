<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use \ElementorPro\Modules\Posts\Skins\Skin_Content_Base;
/**
 * Product content widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Product_Content extends \Elementor\Widget_Base {
    use Skin_Content_Base;
    /**
     * Get widget name.
     *
     * @since 5.2
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'woocommerce-product-etheme_content';
    }

    public function get_title() {
        return esc_html__( 'Product Content', 'xstore-core' );
    }

    public function get_categories() {
        return [ 'woocommerce-elements-single' ];
    }

    public function get_keywords() {
        return [ 'content', 'post', 'product' ];
    }

    public function get_group_name() {
        return 'woocommerce';
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
        return 'eight_theme-elementor-icon et-elementor-product-content et-elementor-product-widget-icon-only';
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

    protected function register_controls() {
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Style', 'xstore-core' ),
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
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
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
                    '{{WRAPPER}}' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        // Post CSS should not be printed here because it overrides the already existing post CSS.
        $this->render_post_content( false, false );
    }

    public function render_plain_content() {}

}
