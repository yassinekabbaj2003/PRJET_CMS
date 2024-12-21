<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Account;

use ETC\App\Classes\Elementor;

/**
 * Account Page widget.
 *
 * @since      5.2.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Account_Page extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @since 5.2.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'woocommerce-account-etheme_page';
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
        return __( 'My Account Page', 'xstore-core' );
    }

    /**
     * Get widget icon.
     *
     * @since 5.2.4
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eight_theme-elementor-icon et-elementor-account';
    }

    /**
     * Get widget keywords.
     *
     * @since 5.2.4
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return [ 'woocommerce', 'account', 'user', 'login', 'register' ];
    }

    /**
     * Get widget categories.
     *
     * @since 5.2.4
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['woocommerce-elements'];
    }

    /**
     * Get widget dependency.
     *
     * @since 5.2.4
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_style_depends() {
        return ['etheme-account-page'];
    }

    /**
     * Get widget dependency.
     *
     * @since 5.2.4
     * @access public
     *
     * @return array Widget dependency.
     */
//    public function get_script_depends() {
//        $scripts = [];
//        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
//            $scripts[] = 'sticky-kit';
//        }
//        $scripts[] = 'etheme_elementor_checkout_page';
//        return $scripts;
//    }

    /**
     * Help link.
     *
     * @since 5.2.4
     *
     * @return string
     */
    public function get_custom_help_url() {
        return etheme_documentation_url(false, false);
    }

    /**
     * Register widget controls.
     *
     * @since 5.2.4
     * @access protected
     */
    protected function register_controls() {

        if ( !class_exists('WooCommerce') ) {
            $this->start_controls_section(
                'section_general',
                [
                    'label' => esc_html__( 'General', 'xstore-core' ),
                ]
            );
            $this->add_control(
                'description',
                [
                    'type'            => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => esc_html__('Install WooCommerce Plugin to use this widget', 'xstore-core'),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                ]
            );
            $this->end_controls_section();
            return;
        }

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf( __( 'You can find more settings for Account page in <a href="%s" target="_blank">My account page settings</a>', 'xstore-core' ), admin_url('admin.php?page=wc-settings&tab=account') ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'nav_layout',
            [
                'label' => esc_html__( 'Layout', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'vertical' => esc_html__( 'Vertical', 'xstore-core' ),
                    'horizontal' => esc_html__( 'Horizontal', 'xstore-core' ),
                ],
                'default' => 'vertical',
                'render_type' => 'template',
                'prefix_class' => 'etheme-account-page-tabs-'
            ]
        );

        $this->add_control(
            'column_width',
            [
                'label' => __( 'Columns Proportion', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => '%'
                ],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 70,
                        'step' => 1,
                    ],
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'nav_layout' => 'vertical'
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--tabs-proportion: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_spacing',
            [
                'label' => esc_html__( 'Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--tabs-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_navigation',
            [
                'label' => esc_html__( 'Navigation', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'show_user_info',
            [
                'label'        => esc_html__( 'Show User Info', 'xstore-core' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'show_nav_icons',
            [
                'label'        => esc_html__( 'Show Nav Icons', 'xstore-core' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $nav_items = $this->get_all_menu_items();
        $nav_items_repeater_select = array();
        $nav_items_repeater = array();
        foreach ($nav_items as $nav_item_key => $nav_item_label) {
            $nav_items_repeater_select[$nav_item_key] = $nav_item_label;
            $selected_icon = [
                'value' => 'et-icon et-star',
                'library' => 'xstore-icons',
            ];
            switch ($nav_item_key) {
                case 'dashboard':
                    $selected_icon['value'] = 'et-icon et-calendar';
                    break;
                case 'orders':
                    $selected_icon['value'] = 'et-icon et-sent';
                    break;
                case 'downloads':
                    $selected_icon['value'] = 'et-icon et-downloads';
                    break;
                case 'edit-address':
                    $selected_icon['value'] = 'et-icon et-internet';
                    break;
                case 'payment-methods':
                    $selected_icon['value'] = 'et-icon et-transfer';
                    break;
                case 'edit-account':
                    $selected_icon['value'] = 'et-icon et-user';
                    break;
                case 'xstore-compare':
                    $selected_icon['value'] = 'et-icon et-compare';
                    break;
                case 'xstore-wishlist':
                    $selected_icon['value'] = 'et-icon et-heart';
                    break;
                case 'xstore-waitlist':
                    $selected_icon['value'] = 'et-icon et-bell';
                    break;
                case 'customer-logout':
                    $selected_icon['value'] = 'et-icon et-logout';
                    break;
            }
            $nav_items_repeater[] = array(
                'nav_item_key' => $nav_item_key,
                'field_key' => $nav_item_key,
                'nav_item' => $nav_item_label,
                'nav_item_title' => $nav_item_label,
                'selected_icon' => $selected_icon
            );
        }

        $nav_items_repeater_select['custom'] = esc_html__('Custom', 'xstore-core');
        $nav_items_repeater[] = array(
            'nav_item_key' => 'custom',
            'field_key' => 'custom',
            'nav_item' => $nav_items_repeater_select['custom'],
            'nav_item_title' => $nav_items_repeater_select['custom'],
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'nav_item_key',
            [
                'label' => esc_html__( 'Nav Item', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => false,
                'options' => $nav_items_repeater_select,
                'default'   => array_key_first($nav_items_repeater_select),
            ]
        );

        $repeater->add_control(
            'nav_item_title',
            [
                'label' => esc_html__( 'Item Name', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Item', 'xstore-core'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'icon',
                'label_block' => false,
                'default' => [
                    'value' => 'et-icon et-star',
                    'library' => 'xstore-icons',
                ],
            ]
        );

        $repeater->add_control(
            'nav_item_link',
            [
                'label' => __( 'Link', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
                'condition' => [
                    'nav_item_key' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'order_display_description',
            [
                'raw' => esc_html__( 'Note: By default, only your last order is displayed while editing the orders section. You can see other orders on your live site or in the WooCommerce orders section', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    'nav_item_key' => 'orders',
                ],
            ]
        );

        $this->add_control(
            'navigation_items',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => $nav_items_repeater,
                'title_field' => '{{{ nav_item_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_additional',
            [
                'label' => esc_html__( 'Advanced Options', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'additional_dashboard_content_template_switch',
            [
                'label' => esc_html__( 'Customize Dashboard', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'additional_template_description',
            [
                'raw' => sprintf(
                /* translators: 1: Saved templates link opening tag, 2: Link closing tag. */
                    esc_html__( 'Replaces the default content with a custom template. (Don’t have one? Head over to %1$sSaved Templates%2$s)', 'xstore-core' ),
                    sprintf( '<a href="%s" target="_blank">', admin_url( 'edit.php?post_type=elementor_library&tabs_group=library#add_new' ) ),
                    '</a>'
                ),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor elementor-descriptor-subtle',
                'condition' => [
                    'additional_dashboard_content_template_switch!' => '',
                ],
            ]
        );

        $content_types = Elementor::get_saved_content_list(array('global_widget' => false));
        $saved_templates = Elementor::get_saved_content();
        $static_blocks = Elementor::get_static_blocks();

        $this->add_control(
            'additional_dashboard_template_heading',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => esc_html__( 'Choose template', 'xstore-core' ),
                'condition' => [
                    'additional_dashboard_content_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_dashboard_content_content_type',
            [
                'label' 		=>	__( 'Content Type', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::SELECT,
                'options' => $content_types,
                'default'	=> 'custom',
                'condition' => [
                    'additional_dashboard_content_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_dashboard_content_save_template_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_saved_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'additional_dashboard_content_template_switch!' => '',
                    'additional_dashboard_content_content_type' => 'saved_template'
                ]
            ]
        );

        $this->add_control(
            'additional_dashboard_content_static_block_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_static_block_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'additional_dashboard_content_template_switch!' => '',
                    'additional_dashboard_content_content_type' => 'static_block'
                ]
            ]
        );

        $this->add_control(
            'additional_dashboard_content_template_content',
            [
                'type'        => \Elementor\Controls_Manager::WYSIWYG,
                'label'       => __( 'Content', 'xstore-core' ),
                'condition'   => [
                    'additional_dashboard_content_template_switch!' => '',
                    'additional_dashboard_content_content_type' => 'custom',
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'additional_dashboard_content_saved_template',
            [
                'label' => __( 'Saved Template', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $saved_templates,
                'default' => 'select',
                'condition' => [
                    'additional_dashboard_content_template_switch!' => '',
                    'additional_dashboard_content_content_type' => 'saved_template'
                ],
            ]
        );

        $this->add_control(
            'additional_dashboard_content_static_block',
            [
                'label' => __( 'Static Block', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $static_blocks,
                'default' => 'select',
                'condition' => [
                    'additional_dashboard_content_template_switch!' => '',
                    'additional_dashboard_content_content_type' => 'static_block'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'user_info_style',
            [
                'label' => esc_html__( 'User Info', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_user_info!' => ''
                ]
            ]
        );

        $this->add_responsive_control(
            'user_info_alignment',
            [
                'label' => esc_html__('Alignment', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'xstore-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'xstore-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'xstore-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors_dictionary'  => [
                    'left'          => 'flex-start',
                    'right'         => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .MyAccount-user-info' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'tabs_style',
            [
                'label' => esc_html__( 'Tabs', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'tabs_alignment',
            [
                'label' => esc_html__('Alignment', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'xstore-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'xstore-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'xstore-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors_dictionary'  => [
                    'left'          => 'flex-start',
                    'right'         => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation-wrapper ul' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation-wrapper li a' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tabs_typography',
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a',
            ]
        );

        $this->start_controls_tabs( 'tabs_section' );

        $this->start_controls_tab( 'tabs_normal', [ 'label' => esc_html__( 'Normal', 'xstore-core' ) ] );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tabs_normal_background',
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li:not(.is-active) a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabs_normal_box_shadow',
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li:not(.is-active) a',
            ]
        );

        $this->add_control(
            'tabs_normal_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li:not(.is-active) a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'tabs_hover', [ 'label' => esc_html__( 'Hover', 'xstore-core' ) ] );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tabs_hover_background',
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabs_hover_box_shadow',
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a:hover',
            ]
        );

        $this->add_control(
            'tabs_hover_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabs_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a:hover' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'tabs_border_type!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'tabs_active', [ 'label' => esc_html__( 'Active', 'xstore-core' ) ] );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tabs_active_background',
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabs_active_box_shadow',
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active a',
            ]
        );

        $this->add_control(
            'tabs_active_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabs_active_border_color',
            [
                'label' => esc_html__( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active a' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'tabs_border_type!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'tabs_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_spacing',
            [
                'label' => esc_html__( 'Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                    'em' => [
                        'max' => 10,
                    ],
                    'rem' => [
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'size' => 12,
                    'unit' => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}}.etheme-account-page-tabs-vertical .woocommerce-MyAccount-navigation ul li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.etheme-account-page-tabs-horizontal .woocommerce-MyAccount-navigation ul li:not(:last-child)' => 'margin-inline-end: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2.4
     * @access protected
     */
    protected function render()
    {

        if (!class_exists('WooCommerce')) {
            echo esc_html__('Install WooCommerce Plugin to use this widget', 'xstore-core');
            return;
        }

        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( !$edit_mode && !get_query_var( 'et_is-loggedin', false) ) {
            echo do_shortcode('[woocommerce_my_account]');
            return;
        }

        $settings = $this->get_settings_for_display();

        add_filter('account_page_design_new', (!!$settings['show_user_info'] ? '__return_true' : '__return_false'));

        add_filter('woocommerce_account_menu_items', array($this, 'menu_items'), 2, 10);

        $has_custom_template = $this->has_custom_template() && ('dashboard' === $this->get_current_endpoint() || \Elementor\Plugin::$instance->editor->is_edit_mode());
        if ( $has_custom_template ) {
            remove_action( 'woocommerce_account_content', 'woocommerce_account_content', 10 );
            add_action( 'woocommerce_account_content', [ $this, 'display_custom_template' ], 10 );
        }

//        woocommerce_account_navigation();
        $this->account_navigation();

        ?>
        <div class="woocommerce-MyAccount-content">
            <?php
            /**
             * My Account content.
             *
             * @since 2.6.0
             */
            do_action( 'woocommerce_account_content' );
            ?>
        </div>
        <?php

        if ( $has_custom_template ) {
            remove_action( 'woocommerce_account_content', [ $this, 'display_custom_template' ], 10 );
            add_action( 'woocommerce_account_content', 'woocommerce_account_content', 10 );
        }

        remove_filter('woocommerce_account_menu_items', array($this, 'menu_items'), 2, 10);

        remove_filter('account_page_design_new', (!!$settings['show_user_info'] ? '__return_true' : '__return_false'));

        if ( $edit_mode ) {
            echo '<span style="height: 0; font-size: 0; line-height: 0; overflow: hidden; opacity: 0;">'. esc_html__( 'Placeholder text to prevent empty widget overlay', 'xstore-core' ). '</span>';
                echo '<script>jQuery(document).ready(function(){
                        if (etTheme.swiperFunc !== undefined)
                            etTheme.swiperFunc();
                        if (etTheme.secondInitSwipers !== undefined)
                            etTheme.secondInitSwipers();
                    });</script>';
        }
    }


    /**
     * Display a custom template inside the My Account dashboard section
     *
     * @since 3.7.0
     */
    public function display_custom_template() {
        if ( !!!$this->render_custom_dashboard_content($this->get_settings_for_display()) ) {
            woocommerce_account_content(); // if the custom content does not have anything to output
            // let's show the default one
        }
    }

    public function menu_items($items, $endpoints) {
        $settings = $this->get_settings_for_display();
        $return = array();
        foreach ($settings['navigation_items'] as $nav_item) {
            $item_key = $nav_item['nav_item_key'] == 'custom' ? 'et_nav_custom_'.$nav_item['_id'] : $nav_item['nav_item_key'];
            $return[$item_key] = $nav_item['nav_item_title'];
        }
        return $return;
    }

    public function account_navigation() {
        $settings = $this->get_settings_for_display();
        $navigation_items = $settings['navigation_items'];
        $show_icons = !!$settings['show_nav_icons'];
        do_action( 'woocommerce_before_account_navigation' );
        $account_page_type = get_option('et_wc_account_page_type', 'new');
        $account_page_type_new = apply_filters('account_page_design_new', $account_page_type) == 'new';

        $current_user = wp_get_current_user();

        ?>

        <div class="woocommerce-MyAccount-navigation-wrapper type-<?php echo esc_attr($account_page_type_new ? 'new' : 'default'); ?> without-icons">
            <?php if ( $account_page_type_new ) : ?>
                <div class="MyAccount-user-info">
                    <?php echo get_avatar($current_user->ID); ?>
                    <div>
                        <?php echo '<div class="MyAccount-user-name">' . $current_user->display_name . '</div>'; ?>
                        <?php echo '<div>' . $current_user->user_email . '</div>'; ?>
                    </div>
                </div>
            <?php endif; ?>

            <nav class="woocommerce-MyAccount-navigation">
                <ul>
                    <?php
                    foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
                        $nav_item_id = $endpoint;
                        $custom_nav_id = false !== strpos($endpoint, 'et_nav_custom_') ? str_replace('et_nav_custom_', '', $endpoint) : false;
                        if ( $custom_nav_id ) {
                            $local_custom_item = array_filter($navigation_items, function ($nav_item) use ($custom_nav_id) {
                                return $nav_item['_id'] == $custom_nav_id;
                            });
                        }
                        else {
                            $local_custom_item = array_filter($navigation_items, function ($nav_item) use ($endpoint) {
                                return $nav_item['nav_item_key'] == $endpoint;
                            });
                        }
                        $local_custom_item = $local_custom_item[array_key_first($local_custom_item)];
                        if ( !!$custom_nav_id ) {
                            $nav_item_id = $custom_nav_id;
                            if ( ! empty( $local_custom_item['nav_item_link']['url'] ) )
                                $this->add_link_attributes( $nav_item_id, $local_custom_item['nav_item_link'] );
                        }
                        else {
                            $this->add_render_attribute( $nav_item_id, 'href', wc_get_account_endpoint_url( $endpoint ) );
                        } ?>
                        <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                            <a <?php $this->print_render_attribute_string( $nav_item_id ); ?>>
                                <?php if ($show_icons) $this->render_icon($local_custom_item); ?>
                                <?php echo esc_html( $label ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

        </div>

        <?php do_action( 'woocommerce_after_account_navigation' );
    }
    
    public function get_all_menu_items() {
        $endpoints = array(
            'orders'          => get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' ),
            'downloads'       => get_option( 'woocommerce_myaccount_downloads_endpoint', 'downloads' ),
            'edit-address'    => get_option( 'woocommerce_myaccount_edit_address_endpoint', 'edit-address' ),
            'payment-methods' => get_option( 'woocommerce_myaccount_payment_methods_endpoint', 'payment-methods' ),
            'edit-account'    => get_option( 'woocommerce_myaccount_edit_account_endpoint', 'edit-account' ),
            'customer-logout' => get_option( 'woocommerce_logout_endpoint', 'customer-logout' ),
        );

        $items = array(
            'dashboard'       => __( 'Dashboard', 'xstore-core' ),
            'orders'          => __( 'Orders', 'xstore-core' ),
            'downloads'       => __( 'Downloads', 'xstore-core' ),
            'edit-address'    => _n( 'Address', 'Addresses', ( 1 + (int) wc_shipping_enabled() ), 'xstore-core' ),
            'payment-methods' => __( 'Payment methods', 'xstore-core' ),
            'edit-account'    => __( 'Account details', 'xstore-core' ),
            'customer-logout' => __( 'Log out', 'xstore-core' ),
        );
        return apply_filters( 'woocommerce_account_menu_items', $items, $endpoints );
    }

    /**
     * Check if the My Account dashboard intro content is replaced with a custom Elementor template
     *
     * Conditions:
     * 1. Customize Dashboard = Show
     * 2. A Template ID has been set
     *
     * @since 3.7.0
     *
     * @return boolean
     */
    public function has_custom_template() {
        return !!$this->get_settings_for_display('additional_dashboard_content_template_switch');
    }

    /**
     * Get Current Endpoint
     *
     * Used to determine which page Account Page the user is on currently.
     * This is used so we can add a unique wrapper class around the page's content.
     *
     * @since 3.5.0
     *
     * @return string
     */
    private function get_current_endpoint() {
        global $wp_query;
        $current = '';

        $pages = $this->get_all_menu_items();

        foreach ( $pages as $page => $val ) {
            if ( isset( $wp_query->query[ $page ] ) ) {
                $current = $page;
                break;
            }
        }

        if ( '' === $current && isset( $wp_query->query_vars['page'] ) ) {
            $current = 'dashboard'; // Dashboard is not an endpoint so it needs a custom check.
        }

        return $current;
    }

    public function render_custom_dashboard_content($settings) {
        // parse setting for making sure default values are set correctly
        $settings = wp_parse_args($settings, array(
            'additional_dashboard_content_template_switch' => '',
            'additional_dashboard_content_template_content' => '',
            'additional_dashboard_content_content_type' => 'custom'
        ) );
        $rendered_content = false;
        if ( !!$settings['additional_dashboard_content_template_switch'] ) {
            switch ($settings['additional_dashboard_content_content_type']) {
                case 'custom':
                    if (!empty($settings['additional_dashboard_content_template_content'])) {

                        //                        $this->print_unescaped_setting('additional_dashboard_content_template_content');
                        echo $settings['additional_dashboard_content_template_content'];
                        $rendered_content = true;
                    }
                    break;
                case 'global_widget':
                case 'saved_template':
                    $prefix = 'additional_dashboard_content_';
                    if (!empty($settings[$prefix . $settings[$prefix . 'content_type']])):
                        //								echo \Elementor\Plugin::$instance->frontend->get_builder_content( $settings[$settings['content_type']], true );
                        $posts = get_posts(
                            [
                                'name' => $settings[$prefix . $settings[$prefix . 'content_type']],
                                'post_type' => 'elementor_library',
                                'posts_per_page' => '1',
                                'tax_query' => [
                                    [
                                        'taxonomy' => 'elementor_library_type',
                                        'field' => 'slug',
                                        'terms' => str_replace(array('global_widget', 'saved_template'), array('widget', 'section'), $settings[$prefix . 'content_type']),
                                    ],
                                ],
                                'fields' => 'ids'
                            ]
                        );

                        if (!isset($posts[0]) || !$content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($posts[0])) { // @todo maybe try to enchance TRUE value with on ajax only

                        } else {
                            echo $content;
                            $rendered_content = true;
                        }
                    endif;
                    break;
                case 'static_block':
                    $prefix = 'additional_dashboard_content_';
                    Elementor::print_static_block($settings[$prefix . $settings[$prefix . 'content_type']]);
                    $rendered_content = true;
                    break;
            }
        }

        return $rendered_content;
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
