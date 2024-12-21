<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Advanced Stock Status widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Advanced_Stock_Status extends \ETC\App\Controllers\Elementor\General\Linear_Progress_Bar {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_advanced_stock_status';
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
		return __( 'Advanced Stock Status', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-product-advanced-stock-status et-elementor-product-widget-icon-only';
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
        return [ 'woocommerce', 'shop', 'store', 'stock', 'quantity', 'product', 'progress', 'linear', 'bar', 'count', 'percent', 'size', 'line', 'animation' ];
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
        $styles = [];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
            $styles[] = 'etheme-elementor-linear-progress-bar';

        return $styles;
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
        $scripts = [];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
            $scripts[] = 'etheme_linear_progress_bar';

        return $scripts;
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

        parent::register_controls();

        $this->update_control( 'progress_type', [
            'options' => [
                'style-06' => esc_html__( 'Basic', 'xstore-core' ),
                'style-01' => esc_html__( 'Text Above', 'xstore-core' ),
                'style-02' => esc_html__( 'Value Label', 'xstore-core' ),
                'style-03' => esc_html__( 'Text Aside', 'xstore-core' ),
                'style-04' => esc_html__( 'Text Inside', 'xstore-core' ),
                'style-05' => esc_html__( 'Text Below', 'xstore-core' ),
            ],
        ] );

        $this->remove_control('title');

        $this->remove_control('progress_value');

        $this->remove_control('progress_background_active_background');
        $this->remove_control('progress_background_active_color');

        $this->update_control( 'section_style_percent', [
            'label' => esc_html__('Value', 'xstore-core')
        ] );

        $this->update_control( 'height', [
            'default' => [
                'unit' => 'px',
                'size' => 10
            ]
        ] );

        $this->update_control( 'section_style_title', [
            'condition' => []
        ] );

        $this->start_injection( [
            'type' => 'section',
            'at' => 'start',
            'of' => 'progress_type',
        ] );

        $this->add_control(
            'stock_priority',
            [
                'label' => esc_html__( 'Stock Priority', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'available' => esc_html__( 'Available', 'xstore-core' ),
                    'sold' => esc_html__( 'Sold', 'xstore-core' ),
                ],
                'default'   => 'sold',
                'condition' => [
                    'progress_type!' => 'style-06'
                ]
            ]
        );

        $this->add_control(
            'available_text',
            [
                'label' => __( '"Available" text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Available:', 'xstore-core' ),
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'Available:', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'sold_text',
            [
                'label' => __( '"Sold" text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Sold:', 'xstore-core' ),
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'Sold', 'xstore-core' ),
            ]
        );

        $this->end_injection();

        $default_colors = get_theme_mod('product_stock_colors', array(
            'step1' => '#2e7d32',
            'step2' => '#f57f17',
            'step3' => '#c62828',
        ));

        $this->start_controls_section(
            'section_steps_style',
            [
                'label' => __( 'Steps colors', 'xstore-core' ),
//                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'steps_description',
            [
                'raw'             =>
                    sprintf(esc_html__('Choose the colors for the different product stock levels. Note: Here you can check the "%1s" of your products configured on your website.', 'xstore-core'),
                        '<a href="' . admin_url( "admin.php?page=wc-settings&tab=products&section=inventory" ) . '" target="_blank">' . esc_html__( 'Low stock threshold values', 'xstore-core' ) . '</a>'),
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'step_1_color',
            [
                'label' => __( 'Full stock color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => $default_colors['step1'],
                'selectors' => [
                    '{{WRAPPER}} .etheme-advanced-product-stock[data-step="1"] .etheme-linear-progress-bar-inner' => 'background-color: {{VALUE}}; --progress-active-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'step_2_color',
            [
                'label' => __( 'Middle stock (sold 50%+) color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => $default_colors['step2'],
                'selectors' => [
                    '{{WRAPPER}} .etheme-advanced-product-stock[data-step="2"] .etheme-linear-progress-bar-inner' => 'background-color: {{VALUE}}; --progress-active-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'step_3_color',
            [
                'label' => __( 'Low stock color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => $default_colors['step3'],
                'selectors' => [
                    '{{WRAPPER}} .etheme-advanced-product-stock[data-step="3"] .etheme-linear-progress-bar-inner' => 'background-color: {{VALUE}}; --progress-active-color: {{VALUE}}'
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

        $this->add_render_attribute( 'wrapper', 'class', [
            'etheme-advanced-product-stock',
            $settings['progress_type']
        ] );

        if ( $settings['progress_type'] != 'style-06' ) {
            $this->add_render_attribute( 'wrapper', 'class', 'etheme-linear-progress-bar-wrapper' );
            wp_enqueue_script('etheme_elementor_slider');
            wp_enqueue_style('etheme-elementor-linear-progress-bar');
        }

        $this->add_render_attribute( 'title', 'class', 'etheme-linear-progress-bar-title' );

        $this->add_render_attribute( 'progress-inner', 'class', 'etheme-linear-progress-bar-inner' );

        if ( $settings['loading_animation'] )
            $this->add_render_attribute( 'progress-inner', 'class', 'loading' );

        $this->add_render_attribute( 'progress_value', 'class', 'etheme-linear-progress-bar-label' );

        if ( $settings['progress_type'] == 'style-02' )
            $this->add_render_attribute( 'progress_value', 'class', 'with-tooltip' );

        $this->stock_line($product, $settings, $edit_mode);

	}

    public function stock_line( $product, $settings, $edit_mode ) {
        $stock_quantity = $product->get_stock_quantity();

        if ( ! empty( $stock_quantity ) ) {
            $already_sold   = get_post_meta( $product->get_ID(), 'total_sales', true );
            $already_sold = empty( $already_sold ) ? 0 : $already_sold;
            $all_stock    = $stock_quantity + $already_sold;
            $stock_line_inner = ( ( $already_sold * 100 ) / $all_stock );
            if ( $stock_quantity <= get_option( 'woocommerce_notify_low_stock_amount' ) ) {
                $data_step = '3';
            } elseif ( ( 100 - $stock_line_inner ) > 50 ) {
                $data_step = '1';
            } else {
                $data_step = '2';
            }
            $this->add_render_attribute( 'wrapper', 'data-step', $data_step );

            $this->add_render_attribute( 'progress', [
                'class' => 'etheme-linear-progress-bar',
                'role' => 'progressbar',
                'data-step' => $data_step,
                'data-maxwidth' => $settings['stock_priority'] == 'sold' ? $stock_line_inner : 100 - $stock_line_inner,
                'data-postfix' => '',
                'aria-valuemin' => '0',
                'aria-valuemax' => $all_stock,
                'aria-valuenow' => $settings['stock_priority'] == 'sold' ? $already_sold : $stock_quantity,
                'aria-valuetext' => $settings['stock_priority'] == 'sold' ? $settings['sold_text'] : $settings['available_text'],
            ] );

            $title = $settings['stock_priority'] == 'sold' ? $settings['sold_text'] : $settings['available_text'];
            $progress_value = $settings['stock_priority'] == 'sold' ? $already_sold : $stock_quantity;

            ?>
            <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>

                <?php

            if ( in_array($settings['progress_type'], array('style-06') ) ) {
                $this->render_progress_bar_title($settings['available_text'], $stock_quantity);
                $this->render_progress_bar_title($settings['sold_text'], $already_sold);
            }

                if ( in_array($settings['progress_type'], array('style-01', 'style-02', 'style-03') ) ) {
                    $this->render_progress_bar_title($title);
                    if ( $settings['progress_type'] == 'style-01' )
                        $this->render_progress_bar_value($progress_value);
                }
                ?>

                <div <?php $this->print_render_attribute_string( 'progress' ); ?>>
                    <div <?php $this->print_render_attribute_string( 'progress-inner' ); ?>>
                        <?php
                        if ( in_array($settings['progress_type'], array('style-02', 'style-03', 'style-04')) ) {
                            if ( $settings['progress_type'] == 'style-04')
                                $this->render_progress_bar_title($title);
                            $this->render_progress_bar_value($progress_value);
                        }
                        ?>
                    </div>
                </div>

                <?php
                if ( $settings['progress_type'] == 'style-05' ) {
                    $this->render_progress_bar_title($title);
                    $this->render_progress_bar_value($progress_value);
                }
                ?>
            </div>

        <?php }
        elseif ( $edit_mode ) {
            echo '<div class="elementor-panel-alert elementor-panel-alert-info">'.
                esc_html__('This message is displayed on edit mode only.', 'xstore-core') . '<br/>' .
                esc_html__('This product does not have stock quantity set.', 'xstore-core') .
            '</div>';
        }

    }

    /**
     * Render Title Content.
     *
     * @param $settings
     * @return void
     *
     * @since 4.0.12
     *
     */
    public function render_progress_bar_title($title, $progress_value = false) {
        if ( ! \Elementor\Utils::is_empty( $title ) ) { ?>
            <span <?php $this->print_render_attribute_string( 'title' ); ?>>
                <?php echo $title;
                if ( $progress_value )
                    $this->render_progress_bar_value($progress_value);
                ?>
            </span>
            <?php
        }
    }

    /**
     * Render Value Content.
     *
     * @param $settings
     * @return void
     *
     * @since 4.0.12
     *
     */
    public function render_progress_bar_value($value) {
        ?>
        <span <?php $this->print_render_attribute_string( 'progress_value' ); ?>>
            <?php
                echo $value;
            ?>
        </span>
        <?php
    }

}
