<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Related widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Product_Related extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @since 5.2
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'woocommerce-product-etheme_related';
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
        return __( 'Related Products', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-products et-elementor-product-widget-icon-only';
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
        return [ 'woocommerce', 'slider', 'shop', 'store', 'related', 'similar', 'product' ];
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
        $styles = [ 'etheme-woocommerce', 'etheme-woocommerce-archive' ];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            $styles[] = 'etheme-elementor-countdown';
            if ( get_theme_mod( 'enable_swatch', 1 ) && class_exists( 'St_Woo_Swatches_Base' ) )
                $styles[] = 'etheme-swatches-style';
        }
        return $styles;
    }

    /**
     * Get widget dependency.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_script_depends() {
        $scripts = ['etheme_elementor_slider'];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            $scripts[] = 'et_product_hover_slider';
        }
        return $scripts;
    }

    /**
     * Help link.
     *
     * @since 5.2
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
        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'type',
            [
                'label' => __('Type', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slider',
                'options' => [
                    'grid' => __('Grid', 'xstore-core'),
                    'slider' => __('Slider', 'xstore-core'),
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'prefix_class' => 'elementor-grid%s-',
                'min' => 1,
                'max' => 12,
                'default' => '4',
                'tablet_default' => '3',
                'mobile_default' => '2',
                'required' => true,
                'device_args' => $this->get_devices_default_args(),
                'min_affected_device' => [
                    \Elementor\Controls_Stack::RESPONSIVE_DESKTOP => \Elementor\Controls_Stack::RESPONSIVE_TABLET,
                    \Elementor\Controls_Stack::RESPONSIVE_TABLET => \Elementor\Controls_Stack::RESPONSIVE_TABLET,
                ],
                'condition' => [
                    'type' => 'grid'
                ]
            ]
        );

        $this->add_control(
            'products_class',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'wc-products',
                'prefix_class' => 'elementor-products-grid elementor-',
                'condition' => [
                    'type' => 'grid'
                ]
            ]
        );

        $this->add_control(
            'limit',
            [
                'label'      => esc_html__( 'Products Limit', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::NUMBER,
                'min' => -1,
                'max' => 200,
                'step' => 1,
                'default' => 6
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__( 'Order By', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => esc_html__( 'Date', 'xstore-core' ),
                    'title' => esc_html__( 'Title', 'xstore-core' ),
                    'price' => esc_html__( 'Price', 'xstore-core' ),
                    'popularity' => esc_html__( 'Popularity', 'xstore-core' ),
                    'rating' => esc_html__( 'Rating', 'xstore-core' ),
                    'rand' => esc_html__( 'Random', 'xstore-core' ),
                    'menu_order' => esc_html__( 'Menu Order', 'xstore-core' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'     => esc_html__( 'Sort Order', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default'   => 'ASC',
                'options' => [
                    'DESC' => esc_html__( 'Descending', 'xstore-core' ),
                    'ASC'  => esc_html__( 'Ascending', 'xstore-core' ),
                ],
            ]
        );

        $this->add_control(
            'hide_free',
            [
                'label'        => esc_html__( 'Hide Free Products', 'xstore-core' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'hide_out_of_stock',
            [
                'label'        => esc_html__( 'Hide Out Of Stock', 'xstore-core' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_sale',
            [
                'label'        => esc_html__( 'Hide Sale Products', 'xstore-core' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_hidden',
            [
                'label'        => esc_html__( 'Show Hidden Products', 'xstore-core' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_heading',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'show_heading',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'heading_text',
            [
                'label' => __( 'Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'html_heading_tag',
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
                'default' => 'h2',
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        // slider global settings
        Elementor::get_slider_general_settings($this, [
            'type' => 'slider'
        ]);

        $this->update_control( 'slides_per_view', [
            'default' => 4,
            'tablet_default' => 3,
            'mobile_default' => 2,
        ] );

        $this->update_control( 'loop', [
            'default' => '',
        ] );

        // slider style settings
        Elementor::get_slider_style_settings($this, [
            'type' => 'slider'
        ]);

        $this->remove_control('slider_vertical_align');

        $this->start_controls_section(
            'section_general_style_section',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'type' => 'grid'
                ]
            ]
        );

        $this->add_responsive_control(
            'cols_gap',
            [
                'label' => esc_html__( 'Columns Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'size' => 20,
                ],
                'tablet_default' => [
                    'size' => 20,
                ],
                'mobile_default' => [
                    'size' => 20,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'rows_gap',
            [
                'label' => esc_html__( 'Rows Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'size' => 40,
                ],
                'tablet_default' => [
                    'size' => 40,
                ],
                'mobile_default' => [
                    'size' => 40,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_heading_style',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .related-products-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .related-products-title',
            ]
        );

        $this->add_responsive_control(
            'heading_text_align',
            [
                'label' => esc_html__( 'Text Align', 'xstore-core' ),
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .related-products-title' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_spacing',
            [
                'label' => esc_html__( 'Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .related-products-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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
        global $product, $woocommerce_loop;

        if( !empty($woocommerce_loop['product_view'])) {
            $product_view = $woocommerce_loop['product_view'];
        }
        else {
            $product_view = function_exists('etheme_get_option') ? etheme_get_option('product_view', 'disable') : 'disable';
        }

        $product = Elementor::get_product();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( ! $product ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message();
            }
            return;
        }

        $settings = $this->get_settings_for_display();
        $slider_type = $settings['type'] == 'slider';
        $swiper_latest = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' );

        if ( $swiper_latest && in_array($settings['arrows_position'], array('middle', 'middle-inside') ) )
            $settings['arrows_position'] = 'middle-inbox';

        $this->add_render_attribute( 'wrapper', [
            'class' => [
                'etheme-elementor-swiper-entry',
                'swiper-entry',
                $settings['arrows_position'],
                $settings['arrows_position_style']
            ]
        ]);

        $this->add_render_attribute( 'wrapper-inner',
            [
                'class' =>
                    [
                        $swiper_latest ? 'swiper' : 'swiper-container',
                        'etheme-elementor-slider',
                    ],
                'dir' => is_rtl() ? 'rtl' : 'ltr',
            ]
        );

        $this->add_render_attribute( 'products-wrapper', 'class', 'swiper-wrapper');

        $related = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id() ) ), 'wc_products_array_filter_visible' ), $settings['orderby'], $settings['order'] );
        $related = $settings['limit'] > 0 ? array_slice( $related, 0, $settings['limit'] ) : $related;

        if ( !count($related) ) return;

        if ( $slider_type ) {
            wp_enqueue_script('etheme_elementor_slider');
            if ( ! in_array( $product_view, array( 'disable', 'custom' ) ) && function_exists('etheme_enqueue_style') ) {
                etheme_enqueue_style( 'product-view-' . $product_view, $edit_mode);
            }
        }

        $settings['include_products'] = array_map(function ($local_product) {
            return $local_product->get_ID();
        }, $related);

        // loop start classes, html tag filter
        add_filter('woocommerce_product_loop_start', array($this, 'product_loop_start_filter'), -10, 1);

        $products = self::get_query( $settings );
        if ( !$slider_type ) {
            wc_set_loop_prop( 'name', 'related' );
            wc_set_loop_prop('columns', $settings['columns']);
            wc_set_loop_prop('etheme_elementor_product_widget', true);
            wc_set_loop_prop('etheme_default_elementor_products_widget', true);
            wc_set_loop_prop('is_shortcode', true);
        }

        global $local_settings;
        $local_settings = $settings;

        if ( $products && $products->have_posts() ) {

            if ( !!$settings['show_heading'] ) {
                echo '<'.$settings['html_heading_tag'].' class="products-title related-products-title"><span>' .
                    apply_filters( 'woocommerce_product_related_products_heading', !empty($settings['heading_text']) ? $settings['heading_text'] : esc_html__( 'Related products', 'xstore-core' ) ) .
                    '</span></'.$settings['html_heading_tag'].'>';
            }
            ?>

            <?php if ( $slider_type ) : ?>
                <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
                <div <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?>>
                <div <?php $this->print_render_attribute_string( 'products-wrapper' ); ?>>

            <?php
            else :
                woocommerce_product_loop_start();
            endif;

            while ( $products->have_posts() ) {
                $products->the_post();
                global $product;

                // Ensure visibility.
                if ( empty( $product ) || ! $product->is_visible() )
                    continue;

                if ( $slider_type )
                    echo '<div class="swiper-slide">';

                $this->get_content_product($local_settings);

                if ( $slider_type )
                    echo '</div>';
            }

            if ( $slider_type ) : ?>
                </div>

                <?php

                if ( $swiper_latest ) {
                    if (in_array($settings['navigation'], array('both', 'arrows')))
                        Elementor::get_slider_navigation($settings, $edit_mode);
                }
                //                if ( 1 < count($products) ) {
                if ( in_array($settings['navigation'], array('both', 'dots')) )
                    Elementor::get_slider_pagination($this, $settings, $edit_mode);
                //                }
                ?>
                </div>
                <?php
                if ( !$swiper_latest ) {
                    if (in_array($settings['navigation'], array('both', 'arrows')))
                        Elementor::get_slider_navigation($settings, $edit_mode);
                } ?>
                </div>
            <?php else :
                woocommerce_product_loop_end();
            endif;

        }

        else {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message(esc_html__('No products were found matching your selection.', 'xstore-core'), 'warning');
            }
        }

        if ( !$slider_type )
            wc_reset_loop();
        wp_reset_postdata();

        remove_filter('woocommerce_product_loop_start', array($this, 'product_loop_start_filter'), -10, 1);

    }

    /**
     * Get query for render products.
     *
     * @param $settings
     * @return \WP_Query
     *
     * @since 4.1.3
     *
     */
    public static function get_query($settings, $extra_params = array()) {

        $page = 1;

        $query_args = array(
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'page' => $page,
            'no_found_rows'  => $settings['navigation'] != 'none' ? false : 1,
            'order'          => $settings['order'],
            'meta_query'     => array(),
            'tax_query'      => array(
                'relation' => 'AND',
            ),
        ); // WPCS: slow query ok.

        $posts_per_page = $settings['limit'];
        $query_args['posts_per_page'] = $posts_per_page;

        if ( 1 < $page ) {
            $query_args['paged'] = $page;
        }

        $query_args = wp_parse_args( $extra_params, $query_args );

        $product_visibility_term_ids = wc_get_product_visibility_term_ids();

        if ( empty( $settings['show_hidden'] ) ) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => is_search() ? $product_visibility_term_ids['exclude-from-search'] : $product_visibility_term_ids['exclude-from-catalog'],
                'operator' => 'NOT IN',
            );
//		    $query_args['post_parent'] = 0;
        }

        if ( ! empty( $settings['hide_free'] ) ) {
            $query_args['meta_query'][] = array(
                'key'     => '_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'DECIMAL',
            );
        }

//	    if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
        if ( $settings['hide_out_of_stock'] ) {
            $query_args['tax_query'][] = array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $product_visibility_term_ids['outofstock'],
                    'operator' => 'NOT IN',
                ),
            ); // WPCS: slow query ok.
        }

        if ( $settings['hide_sale'] ) {
            $product_ids_on_sale    = wc_get_product_ids_on_sale();
            $product_ids_on_sale[]  = 0;
            $query_args['post__not_in'] = $product_ids_on_sale;
        }

        // backup value for limit if not products are set
        $query_args['posts_per_page'] = 8;
        $settings['include_products'] = !is_array($settings['include_products']) ? explode(',', $settings['include_products']) : $settings['include_products'];
        if ( count($settings['include_products']) ) {
            $query_args['post_type'] = array_merge((array)$query_args['post_type'], array('product_variation'));
            $query_args['post__in']       = $settings['include_products'];
            $query_args['orderby']        = 'post__in';
            $query_args['posts_per_page'] = - 1;
        }

        switch ( $settings['orderby'] ) {
            case 'price':
                $query_args['meta_key'] = '_price'; // WPCS: slow query ok.
                $query_args['orderby']  = 'meta_value_num';
                break;
            case 'rand':
            case 'menu_order':
                $query_args['orderby'] = $settings['orderby'];
                break;
            case 'sales':
                $query_args['meta_key'] = 'total_sales'; // WPCS: slow query ok.
                $query_args['orderby']  = 'meta_value_num';
                break;
            default:
                $query_args['orderby'] = 'date';
        }

        return new \WP_Query( apply_filters( 'woocommerce_products_widget_query_args', $query_args ) );
    }

    /**
     * Get content of product.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_content_product($settings) {
        global $local_settings;
        $local_settings = $settings;
        wc_get_template_part( 'content', ($local_settings['type'] == 'slider' ? 'product-slider' : 'product') );
    }

    /**
     * Filter loop start html for compatibility with 3d-party plugins.
     *
     * @param $html
     * @return string|string[]
     *
     * @since 4.1.3
     *
     */
    public function product_loop_start_filter($html) {
        $class = 'products elementor-grid products-loop products-grid related-products';
        $html = str_replace('class="', 'class="'.$class.' ', $html);
        return $html;
    }

    /**
     * Filter loop end html for compatibility with 3d-party plugins.
     *
     * @param $html
     * @return string|string[]
     *
     * @since 5.2
     *
     */
    public function product_loop_end_filter($html) {
        return str_replace('</ul', '</div', $html);
    }

    // This widget extends the woocommerce core widget and therefore needs to overwrite the widget-base core CSS config.
    public function get_css_config() {
        $widget_name = 'woocommerce';

        $direction = is_rtl() ? '-rtl' : '';

        $css_file_path = 'css/widget-' . $widget_name . $direction . '.min.css';

        /*
         * Currently this widget does not support custom-breakpoints in its CSS file.
         * In order to support it, this widget needs to get the CSS config from the base-widget-trait.php.
         * But to make sure that it implements the Pro assets-path due to the fact that it extends a Core widget.
        */
        return [
            'key' => $widget_name,
            'version' => ELEMENTOR_PRO_VERSION,
            'file_path' => ELEMENTOR_PRO_ASSETS_PATH . $css_file_path,
            'data' => [
                'file_url' => ELEMENTOR_PRO_ASSETS_URL . $css_file_path,
            ],
        ];
    }

    protected function get_devices_default_args() {
        $devices_required = [];

        // Make sure device settings can inherit from larger screen sizes' breakpoint settings.
        foreach ( \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints() as $breakpoint_name => $breakpoint_config ) {
            $devices_required[ $breakpoint_name ] = [
                'required' => false,
            ];
        }

        return $devices_required;
    }
}
