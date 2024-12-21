<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Size guide widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Frequently_Bought_Together extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_frequently_bought_together';
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
		return __( 'Frequently Bought Together', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-product-frequently-bought-together et-elementor-product-widget-icon-only';
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
        return [ 'woocommerce', 'shop', 'store', 'sale', 'package', 'order', 'title', 'heading', 'product' ];
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
		return [ 'etheme-single-product-bought-together-products' ];
	}

    /**
     * Get widget dependency.
     *
     * @since 4.1.4
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_script_depends() {
        return [ 'et_single_product_bought_together' ];
    }
	
	/**
	 * Help link.
	 *
	 * @since 4.1.5
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
        return etheme_documentation_url('132-frequently-bought-together', false);
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
            'description',
            [
                'raw' => sprintf(esc_html__('This widget displays products set in %s settings.', 'xstore-core'),
                    '<a href="'.etheme_documentation_url('132-frequently-bought-together', false).'" rel="nofollow" target="_blank">' . esc_html__('Bought together', 'xstore-core') . '</a>'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'redirect_url',
            [
                'label' => esc_html__( 'Redirect on added to cart', 'xstore-core' ),
                'label_block' 	=> true,
                'description' => esc_html__('Choose the page to which the customer should be redirected upon successfully adding these frequently bought products to their cart.', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Cart page', 'xstore-core' ),
                    'checkout' => esc_html__( 'Checkout page', 'xstore-core' ),
                    'product' => esc_html__( 'Product page', 'xstore-core' ),
                ],
                'default' => '',
            ]
        );

        $this->end_controls_section();

        // slider global settings
        Elementor::get_slider_general_settings($this);

        $remove_controls_list = array(
            'slides_per_group',
            'space_between',
            'slider_vertical_align',
            'loop',
            'autoplay',
            'autoplay_speed',
            'pause_on_hover',
            'pause_on_interaction',
//            'arrows_position'
        );
        foreach ($remove_controls_list as $remove_control) {
            $this->remove_control($remove_control);
        }

        $this->start_injection( [
            'type' => 'control',
            'at' => 'after',
            'of' => 'slides_per_view',
        ] );

        $this->add_control(
            'space_between',
            [
                'label' => esc_html__( 'Space Between', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0
                    ],
                ],
                'render_type' => 'template',
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .bought-together-products .swiper-container .swiper-slide:not(:last-child):after' => 'right: calc({{SIZE}}{{UNIT}} / -2);',
                    'body.rtl {{WRAPPER}} .bought-together-products .swiper-container .swiper-slide:not(:last-child):after' => 'left: calc({{SIZE}}{{UNIT}} / -2);',
                ],
            ]
        );

        $this->end_injection();

        Elementor::get_slider_style_settings($this);

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Title', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_html_tag',
            [
                'label' => esc_html__( 'HTML Tag', 'xstore-core' ),
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
                'default' => 'h3',
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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .products-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .products-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .products-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'title_text_stroke',
                'selector' => '{{WRAPPER}} .products-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'selector' => '{{WRAPPER}} .products-title',
            ]
        );

        $this->add_control(
            'title_blend_mode',
            [
                'label' => esc_html__( 'Blend Mode', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Normal', 'xstore-core' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .products-title' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_bought_together_button_style',
            [
                'label' => __( 'Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'bought_together_button_typography',
                'selector' => '{{WRAPPER}} .bought-together-button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'bought_together_button_text_shadow',
                'selector' => '{{WRAPPER}} .bought-together-button',
            ]
        );

        $this->start_controls_tabs( 'tabs_bought_together_button_style' );

        $this->start_controls_tab(
            'tab_bought_together_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'bought_together_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bought-together-button' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bought_together_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bought-together-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_bought_together_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'bought_together_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bought-together-button:hover, {{WRAPPER}} .bought-together-button:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .bought-together-button:hover svg, {{WRAPPER}} .bought-together-button:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bought_together_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bought-together-button:hover, {{WRAPPER}} .bought-together-button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bought_together_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'bought_together_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bought-together-button:hover, {{WRAPPER}} .bought-together-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'bought_together_button_border',
                'selector' => '{{WRAPPER}} .bought-together-button, {{WRAPPER}} .bought-together-button.button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bought_together_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bought-together-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bought_together_button_box_shadow',
                'selector' => '{{WRAPPER}} .bought-together-button',
            ]
        );

        $this->add_responsive_control(
            'bought_together_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bought-together-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $local_settings = array(
            'force_load_assets' => false,
            'title_tag' => $settings['title_html_tag']
        );
        if ( $settings['redirect_url'] )
            $local_settings['custom_redirect_url'] = $settings['redirect_url'];
        if ( $settings['slides_per_view'] )
            $local_settings['large'] = $settings['slides_per_view'];
        if ( $settings['slides_per_view_tablet'])
            $local_settings['tablet'] = $settings['slides_per_view_tablet'];
        if ( $settings['slides_per_view_mobile'])
            $local_settings['mobile'] = $settings['slides_per_view_mobile'];

        if ( $settings['space_between']['size'] )
            $local_settings['slider_space'] = $settings['space_between']['size'];

        $local_settings['autoheight'] = !!$settings['autoheight'];

        $local_settings['hide_buttons'] = !in_array($settings['navigation'], array('both', 'arrows'));

        $local_settings['pagination_type'] = in_array($settings['navigation'], array('both', 'dots') ) ? 'dots' : 'hide';

        $local_settings['navigation_type'] = $settings['arrows_type'];
        $local_settings['navigation_style'] = $settings['arrows_style'];
        $local_settings['navigation_position'] = $settings['arrows_position'];
        $local_settings['navigation_position_style'] = $settings['arrows_position_style'];
        $local_settings['elementor'] = true;

        if ( !!$settings['arrows_hide_desktop'] )
            $local_settings['hide_buttons_for'] = 'desktop';
        elseif ( !!$settings['arrows_hide_mobile'] )
            $local_settings['hide_buttons_for'] = 'mobile';

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

		if ( $edit_mode )
		    add_filter('etheme_elementor_edit_mode', '__return_true');
		?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
                <?php if ( function_exists('etheme_bought_together') ) {
                    etheme_bought_together($local_settings);
                }
                else 
                    echo esc_html__('Activate XStore theme please', 'xstore-core'); ?>
            </div>
		<?php


        if ( $edit_mode ) : ?>
            <script>jQuery(document).ready(function(){
                    etTheme.swiperFunc();
                    etTheme.secondInitSwipers();
                    if ( etTheme.sliderVertical !== undefined )
                        etTheme.sliderVertical();
                    etTheme.global_image_lazy();
                    if ( etTheme.contentProdImages !== undefined )
                        etTheme.contentProdImages();
                    if ( window.hoverSlider !== undefined ) {
                        window.hoverSlider.init({});
                        window.hoverSlider.prepareMarkup();
                    }
                    if ( etTheme.countdown !== undefined )
                        etTheme.countdown();
                    etTheme.customCss();
                    etTheme.customCssOne();
                    if ( etTheme.reinitSwatches !== undefined )
                        etTheme.reinitSwatches();
                });</script>
        <?php endif;
	}
}
