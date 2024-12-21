<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout;

use ETC\App\Classes\Elementor;

/**
 * Checkout Page widget.
 *
 * @since      5.2.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Checkout_Page extends \Elementor\Widget_Base {

    private $reformatted_form_fields;
    public static $checkout_login_reminder_feature_status = null;
    public static $shipping_feature_status = null;
    public static $coupons_feature_status = null;
    public static $signup_and_login_from_checkout_status = null;
    public static $ship_to_billing_address_only_feature_status = null;
    /**
     * Get widget name.
     *
     * @since 5.2.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'woocommerce-checkout-etheme_page';
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
        return __( 'Checkout Page (Default)', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-checkout-page';
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
        return [ 'xstore-core', 'checkout' ];
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
        return ['etheme-cart-page', 'etheme-no-products-found', 'etheme-checkout-page', 'etheme-elementor-checkout-page'];
    }

    /**
     * Get widget dependency.
     *
     * @since 5.2.4
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_script_depends() {
        $scripts = [ 'wc-checkout' ];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            $scripts[] = 'cart_checkout_advanced_labels';
            $scripts[] = 'sticky-kit';
            $scripts[] = 'etheme_elementor_checkout_page';
            $scripts[] = 'checkout_product_quantity';
        }
        return $scripts;
    }

    /**
     * Help link.
     *
     * @since 5.2.4
     *
     * @return string
     */
    public function get_custom_help_url() {
        return etheme_documentation_url('110-sales-booster', false);
    }

    /**
     * Register widget controls.
     *
     * @since 5.2.4
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
            'cols',
            [
                'label' => __( 'Columns', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                ],
                'default' => '2',
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}}' => '--cols: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'reverse_order',
            [
                'label' => __( 'Reverse Columns', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'advanced_labels',
            [
                'label' => esc_html__('Advanced Labels', 'xstore-core'),
                'description' => esc_html__( 'Enable this option to have aesthetically pleasing animated labels when filling out forms on the checkout page.', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'checkout_page_class',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'checkout-widgets-contain',
                'prefix_class' => 'etheme-elementor-',
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
            'heading_html_tag',
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
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'heading_type',
            [
                'label' => esc_html__( 'Design Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => array(
                    'classic' => __( 'Classic', 'xstore-core' ),
                    'line-aside' => __( 'Line aside', 'xstore-core' ),
                    'square-aside' => __( 'Square aside', 'xstore-core' ),
                    'circle-aside' => __( 'Circle aside', 'xstore-core' ),
                    'underline' => __( 'With Underline', 'xstore-core' ),
                    'colored-underline' => __( 'With Colored Underline', 'xstore-core' ),
                ),
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        foreach ($this->get_elements_list() as $element => $element_title) {
            switch ($element) {
                case 'billing_details':
                case 'shipping_details':
                    if ($element == 'billing_details') {
                        $section_title = $this->is_wc_feature_active('ship_to_billing_address_only') ?
                            esc_html__('Billing and Shipping Details', 'xstore-core') : $element_title;
                    }
                    else {
                        $section_title = $element_title;

//                        if ( !($this->is_wc_feature_active( 'shipping' ) && ! $this->is_wc_feature_active( 'ship_to_billing_address_only' )) ) {
                        if ( $this->is_wc_feature_active( 'ship_to_billing_address_only' ) ) {
                            break;
                        }
                    }

                    $this->start_controls_section(
                        $element . '_section',
                        [
                            'label' => ucwords($section_title),
                        ]
                    );

                    $this->add_control(
                        $element . '_section_heading',
                        [
                            'label' => esc_html__('Section Title', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'placeholder' => $section_title,
                            'default' => '',
                            'dynamic' => [
                                'active' => true,
                            ],
                            'condition' => [
                                'show_heading!' => '',
                            ],
                        ]
                    );

                    if ( $element == 'shipping_details' ) {
                        $this->add_control(
                            $element . '_notice',
                            [
                                'raw' => $this->get_shipping_details_information(),
                                'type' => \Elementor\Controls_Manager::RAW_HTML,
                                'content_classes' => 'elementor-descriptor',
                            ]
                        );
                        $this->add_control(
                            $element . '_changable_fields_notice',
                            [
                                'raw' => $this->get_changable_fields_information(),
                                'type' => \Elementor\Controls_Manager::RAW_HTML,
                                'content_classes' => 'elementor-descriptor',
                            ]
                        );
                        $this->add_control(
                            $element . '_checkbox_active',
                            [
                                'label'           => esc_html__('Active Checkbox', 'xstore-core'),
                                'type' => \Elementor\Controls_Manager::SWITCHER,
                                'frontend_available' => true
                            ]
                        );
                    }
                    else {
                        $this->add_control(
                            $element . '_changable_fields_notice',
                            [
                                'raw' => $this->get_changable_fields_information(),
                                'type' => \Elementor\Controls_Manager::RAW_HTML,
                                'content_classes' => 'elementor-descriptor',
                            ]
                        );
                    }

                    $repeater = new \Elementor\Repeater();

                    $repeater->start_controls_tabs('tabs');

                    $repeater->start_controls_tab('content_tab', [
                        'label' => esc_html__('Content', 'xstore-core'),
                    ]);

                    $repeater->add_control(
                        'label',
                        [
                            'label' => esc_html__('Label', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'repeater_state' => '',
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'placeholder',
                        [
                            'label' => esc_html__('Placeholder', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'repeater_state' => '',
                            ],
                        ]
                    );

//        $repeater->add_control(
//            'stretched',
//            [
//                'label' => esc_html__('Full-width row', 'xstore-core'),
//                'type' => \Elementor\Controls_Manager::SWITCHER,
//            ]
//        );

                    $repeater->add_control(
                        'content_notice',
                        [
                            'raw' => __('Note: This content cannot be changed due to local regulations.', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::RAW_HTML,
                            'content_classes' => 'elementor-descriptor',
                            'condition' => [
                                'repeater_state!' => '',
                            ]
                        ]
                    );

                    $repeater->end_controls_tab();

                    $repeater->start_controls_tab('advanced_tab', [
                        'label' => esc_html__('Advanced', 'xstore-core'),
                    ]);

                    $repeater->add_control(
                        'default',
                        [
                            'label' => esc_html__('Default Value', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'dynamic' => [
                                'active' => true,
                            ],
                            'condition' => [
                                'repeater_state' => '',
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'locale_notice',
                        [
                            'raw' => __('Note: This content cannot be changed due to local regulations.', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::RAW_HTML,
                            'content_classes' => 'elementor-descriptor',
                            'condition' => [
                                'repeater_state' => 'locale',
                            ],
                        ]
                    );

                    $repeater->end_controls_tab();

                    $repeater->end_controls_tabs();

                    $repeater->add_control(
                        'repeater_state',
                        [
                            'label' => esc_html__('Repeater State - hidden', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::HIDDEN,
                        ]
                    );

                    $this->add_control(
                        $element . '_form_fields',
                        [
                            'label' => esc_html__('Form Items', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'fields' => $repeater->get_controls(),
                            'item_actions' => [
                                'add' => false,
                                'duplicate' => false,
                                'remove' => false,
                                'sort' => false,
                            ],
                            'default' => $element == 'billing_details' ? $this->get_billing_field_defaults() : $this->get_shipping_field_defaults(),
                            'title_field' => '{{{ label }}}',
                        ]
                    );

                    if ( $element == 'billing_details' ) {
                        $this->add_control(
                            $element . '_email_field_first',
                            [
                                'label'           => esc_html__( 'Email field prioritized', 'xstore-core' ),
                                'description' => esc_html__('Enable this option to move the email field to the first position of the billing details form so that it will become the highest priority for filling in among the other fields.', 'xstore-core'),
                                'type' => \Elementor\Controls_Manager::HIDDEN, // hide till not decide if there is better solution
                            ]
                        );
                    }

                    $this->end_controls_section();
                    break;
                case 'new_account':
                    $this->start_controls_section(
                        'section_'.$element,
                        [
                            'label' => ucwords($element_title),
                        ]
                    );

                    $this->add_control(
                        $element . '_notice',
                        [
                            'raw' => $this->get_new_account_information(),
                            'type' => \Elementor\Controls_Manager::RAW_HTML,
                            'content_classes' => 'elementor-descriptor',
                        ]
                    );

                    $this->add_control(
                        $element . '_checkbox_active',
                        [
                            'label'           => esc_html__('Active Checkbox', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'frontend_available' => true
                        ]
                    );

                    $this->add_control(
                        $element . '_section_heading',
                        [
                            'label' => esc_html__('Section Title', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'placeholder' => $element_title,
                            'dynamic' => [
                                'active' => true,
                            ],
                            'condition' => [
                                'show_heading!' => '',
                            ],
                        ]
                    );
                    $this->end_controls_section();
                    break;
                case 'additional_information':
                    $this->start_controls_section(
                        'section_'.$element,
                        [
                            'label' => ucwords($element_title),
                        ]
                    );

                    $this->add_control(
                        $element . '_switcher',
                        [
                            'label'           => $element_title,
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'yes'
                        ]
                    );

//                    if ( apply_filters($this->get_name().'_'.$element.'_heading_section_displayed', false) || $this->is_wc_feature_active( 'ship_to_billing_address_only' ) ) {
//                    if ( $this->is_wc_feature_active( 'ship_to_billing_address_only' ) ) {
                        $this->add_control(
                            $element . '_section_heading',
                            [
                                'label' => esc_html__('Section Title', 'xstore-core'),
                                'type' => \Elementor\Controls_Manager::TEXT,
                                'placeholder' => $section_title,
                                'default' => esc_html__('Additional Information', 'xstore-core'),
                                'dynamic' => [
                                    'active' => true,
                                ],
                                'condition' => [
                                    'show_heading!' => '',
                                    $element . '_switcher!' => ''
                                ],
                            ]
                        );
//                    }

                    $repeater = new \Elementor\Repeater();

                    $repeater->start_controls_tabs( $element . '_form_fields_tabs' );

                    $repeater->start_controls_tab( $element . '_form_fields_content_tab', [
                        'label' => esc_html__( 'Content', 'xstore-core' ),
                    ] );

                    $repeater->add_control(
                        'label',
                        [
                            'label' => esc_html__( 'Label', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'dynamic' => [
                                'active' => true,
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'placeholder',
                        [
                            'label' => esc_html__( 'Placeholder', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'dynamic' => [
                                'active' => true,
                            ],
                        ]
                    );

                    $repeater->end_controls_tab();

                    $repeater->start_controls_tab( $element. '_form_fields_advanced_tab', [
                        'label' => esc_html__( 'Advanced', 'xstore-core' ),
                    ] );

                    $repeater->add_control(
                        'default',
                        [
                            'label' => esc_html__( 'Default Value', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'dynamic' => [
                                'active' => true,
                            ],
                        ]
                    );

                    $repeater->end_controls_tab();

                    $repeater->end_controls_tabs();

                    $this->add_control(
                        $element . '_form_fields',
                        [
                            'label' => esc_html__( 'Items', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'fields' => $repeater->get_controls(),
                            'item_actions' => [
                                'add' => false,
                                'duplicate' => false,
                                'remove' => false,
                                'sort' => false,
                            ],
                            'default' => [
                                [
                                    'field_key' => 'order_comments',
                                    'field_label' => esc_html__( 'Order Notes', 'xstore-core' ),
                                    'label' => esc_html__( 'Order Notes', 'xstore-core' ),
                                    'placeholder' => esc_html__( 'Notes about your order, e.g. special notes for delivery.', 'xstore-core' ),
                                ],
                            ],
                            'title_field' => '{{{ label }}}',
                            'condition' => [
                                $element . '_switcher!' => '',
                            ],
                        ]
                    );

                    $this->end_controls_section();
                    break;
                case 'coupon':
                case 'login_form':
                    $coupon_settings = $element == 'coupon';
                    $heading = esc_html__( 'Have a coupon?', 'xstore-core' );
                    $link_text = esc_html__( 'Click here to enter', 'xstore-core' );
                    $button_text = esc_html__( 'Apply Button', 'xstore-core' );
                    $selector = '.woocommerce-form-coupon';
                    $notice = '';
                    if ( !$coupon_settings ) {
                        $heading = esc_html__( 'Returning customer?', 'xstore-core' );
                        $button_text = esc_html__( 'Login', 'xstore-core' );
                        $link_text = esc_html__( 'Click here to login', 'xstore-core' );
                        $selector = '.woocommerce-form-login';
                        $notice = sprintf(__('Note: This content will be displayed on frontend in case %s option is activated.', 'xstore-core'), '<a href="'.admin_url('admin.php?page=wc-settings&tab=account').'" target="_blank">'.
                            esc_html__('Allow customers to log into an existing account during checkout', 'xstore-core').
                            '</a>');
                    }
                    $this->start_controls_section(
                        'section_'.$element,
                        [
                            'label' => ucwords($element_title),
                        ]
                    );

                    if ( $notice ) {
                         $this->add_control(
                             $element.'_settings_notice',
                             [
                                 'raw' => $notice,
                                 'type' => \Elementor\Controls_Manager::RAW_HTML,
                                 'content_classes' => 'elementor-descriptor',

                             ]
                         );
                    }

                    $this->add_control(
                        $element . '_switcher',
                        [
                            'label'           => $element_title,
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'yes'
                        ]
                    );

                    $this->add_control(
                        $element . '_opened',
                        [
                            'label'           => esc_html__('Opened', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'condition' => [
                                $element . '_switcher!' => '',
                            ],
                        ]
                    );

                    if ( $coupon_settings ) {
                        $this->add_control(
                            $element . '_position',
                            [
                                'label' => __('Position', 'xstore-core'),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'default' => 'above_all',
                                'options' => [
                                    'above_all' => esc_html__('Above All', 'xstore-core'),
                                    'order_review_top' => esc_html__('Above Order Review', 'xstore-core'),
                                    'order_review_bottom' => esc_html__('Below Order Review', 'xstore-core'),
                                ],
                                'condition' => [
                                    $element . '_switcher!' => ''
                                ]
                            ]
                        );
                    }

                    $this->add_control(
                        $element . '_section_heading',
                        [
                            'label' => esc_html__('Section Title', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'placeholder' => $heading,
                            'default' => $heading,
                            'dynamic' => [
                                'active' => true,
                            ],
                            'condition' => [
                                $element . '_switcher!' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        $element . '_section_heading_link_text',
                        [
                            'label' => esc_html__( 'Link Text', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => $link_text,
                            'dynamic' => [
                                'active' => true,
                            ],
                            'condition' => [
                                $element . '_switcher!' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        $element . '_button_text',
                        [
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label' => esc_html__('Button text', 'xstore-core'),
                            'default' => $button_text,
                            'separator' => 'before',
                            'condition' => [
                                $element . '_switcher!' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        $element . '_button_selected_icon',
                        [
                            'label' => __( 'Icon', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::ICONS,
                            'fa4compatibility' => $element . '_button_icon',
                            'skin' => 'inline',
                            'label_block' => false,
                            'condition' => [
                                $element . '_switcher!' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        $element . '_button_icon_align',
                        [
                            'label' => __( 'Icon Position', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'left',
                            'options' => [
                                'left' => __( 'Before', 'xstore-core' ),
                                'right' => __( 'After', 'xstore-core' ),
                            ],
                            'condition' => [
                                $element . '_switcher!' => '',
                                $element . '_button_text!' => '',
                                $element . '_button_selected_icon[value]!' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        $element . '_button_icon_indent',
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
                                '{{WRAPPER}} '.$selector.' .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} '.$selector.' .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                $element . '_switcher!' => '',
                                $element . '_button_text!' => '',
                                $element . '_button_selected_icon[value]!' => '',
                            ],
                        ]
                    );

                    $this->end_controls_section();
                    break;
                case 'order_review':
                    $this->start_controls_section(
                        'section_'.$element,
                        [
                            'label' => ucwords($element_title),
                        ]
                    );

                    $this->add_control(
                        $element . '_product_images',
                        [
                            'label'           => esc_html__( 'Show images', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'description'     => esc_html__( 'Enable this option to display product images in the order details information on the checkout and thank you pages.', 'xstore-core' ),
                            'default' => 'yes'
                        ]
                    );

                    $this->add_control(
                        $element . '_product_quantity',
                        [
                            'label'           => esc_html__( 'Show quantity', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'description'     => esc_html__( 'Enable this option to add the ability to change the quantity of product displayed in the order details information on the checkout page.', 'xstore-core' ),
                            'default' => 'yes'
                        ]
                    );

                    $this->add_control(
                        $element . '_product_quantity_style',
                        [
                            'label' => __( 'Quantity Style', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '',
                            'options' => [
                                '' => esc_html__('Default', 'xstore-core'),
                                'simple' => esc_html__('Simple', 'xstore-core'),
                                'circle' => esc_html__('Circle', 'xstore-core'),
                                'square' => esc_html__('Square', 'xstore-core'),
                            ],
                            'condition' => [
                                $element . '_product_quantity!' => ''
                            ]
                        ]
                    );

                    $this->add_control(
                        $element . '_product_remove',
                        [
                            'label'           => esc_html__( 'Show "remove" button', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'description'     => esc_html__( 'Enable this option to display a "Remove" button for products displayed in the order details information on the checkout page.', 'xstore-core' ),
                        ]
                    );

                    $this->add_control(
                        $element . '_product_link',
                        [
                            'label'           => esc_html__( 'Product link', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'description'     => esc_html__( 'Enable this option to give your customers the ability to access the product page by clicking on either the product title or product image for products displayed in the order details information on the checkout page.', 'xstore-core' ),
                        ]
                    );

                    $this->add_control(
                        $element . '_product_subtotal',
                        [
                            'label'           => esc_html__( 'Product subtotal', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'description'     => esc_html__( 'Enable this option to display a subtotal for each product displayed in the order details information on the checkout page.', 'xstore-core' ),
                            'default' => 'yes'
                        ]
                    );

                    $this->add_control(
                        $element.'_sticky_buttons_mobile',
                        array(
                            'label'        => esc_html__( 'Sticky Buttons on responsive (beta)', 'xstore-core' ),
                            'type'         => \Elementor\Controls_Manager::SWITCHER,
                            'separator' => 'before',
                            'return_value' => 'order-review-sticky-buttons',
                            'prefix_class' => 'etheme-elementor-',
                        )
                    );

                    if ( \Elementor\Plugin::$instance->breakpoints && method_exists( \Elementor\Plugin::$instance->breakpoints, 'get_active_breakpoints')) {
                        $active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
                        $breakpoints_list   = array();

                        foreach ($active_breakpoints as $key => $value) {
                            $breakpoints_list[$key] = $value->get_label();
                        }

                        $breakpoints_list['desktop'] = 'Desktop';
                        $breakpoints_list            = array_reverse($breakpoints_list);
                    } else {
                        $breakpoints_list = array(
                            'desktop' => 'Desktop',
                            'tablet'  => 'Tablet',
                            'mobile'  => 'Mobile'
                        );
                    }

                    $this->add_control(
                        $element . '_sticky',
                        array(
                            'label'        => esc_html__( 'Sticky Column', 'xstore-core' ),
                            'type'         => \Elementor\Controls_Manager::SWITCHER,
                            'description' 	=>	__( 'Works for live mode only, not for the editor mode', 'xstore-core' ),
                            'separator' => 'before',
                            'frontend_available' => true,
                            'condition' => [
                                'cols' => '2'
                            ]
                        )
                    );

                    $this->add_control(
                        $element . '_sticky_top_offset',
                        array(
                            'label'   => esc_html__( 'Top Spacing', 'xstore-core' ),
                            'type'    => \Elementor\Controls_Manager::NUMBER,
                            'default' => 50,
                            'min'     => 0,
                            'max'     => 500,
                            'step'    => 1,
                            'frontend_available' => true,
                            'condition' => array(
                                'cols' => '2',
                                $element . '_sticky!' => '',
                            ),
                        )
                    );

                    $this->add_control(
                        $element . '_sticky_on',
                        array(
                            'label'    => __( 'Sticky On', 'xstore-core' ),
                            'type'     => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'label_block' => 'true',
                            'default' => array(
                                'desktop',
                                'tablet',
                            ),
                            'frontend_available' => true,
                            'options' => $breakpoints_list,
                            'condition' => array(
                                'cols' => '2',
                                $element . '_sticky!' => '',
                            ),
//				'render_type'        => 'none',
                        )
                    );

                    $this->end_controls_section();
                    break;
                case 'shipping_methods':
                case 'payment_methods':
                    $this->start_controls_section(
                        'section_'.$element,
                        [
                            'label' => ucwords($element_title),
                        ]
                    );

                    $this->add_control(
                        $element . '_section_heading',
                        [
                            'label' => esc_html__('Section Title', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'placeholder' => $element_title,
                            'default' => '',
                            'dynamic' => [
                                'active' => true,
                            ],
                            'condition' => [
                                'show_heading!' => '',
                                $element.'_position' => 'separated',
                            ],
                        ]
                    );

                    $this->add_control(
                        $element . '_position',
                        [
                            'label' => __('Position', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'order_review',
                            'options' => [
                                'separated' => esc_html__('Separated Type', 'xstore-core'),
                                'order_review' => esc_html__('In Order Review', 'xstore-core'),
                            ],
                        ]
                    );

                    $this->end_controls_section();
                    break;
            }
        }

        do_action('etheme_elementor_checkout_page_before_section_general_style', $this);
        
        $this->start_controls_section(
            'section_general_style',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
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
                        'max' => 50,
                        'step' => 1,
                    ],
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'cols' => '2'
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--column-proportion: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cols_gap',
            [
                'label' => __( 'Columns Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'cols' => '2'
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--cols-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rows_gap',
            [
                'label' => __( 'Rows Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'cols' => '1'
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--rows-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'steps_gap',
            [
                'label' => __( 'Steps Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--steps-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_coupon_login_section_style(
            'coupon',
            esc_html__('Coupon', 'xstore-core'),
            '{{WRAPPER}} .etheme-elementor-cart-checkout-page-coupon',
            'coupon_button',
            '{{WRAPPER}} .etheme-elementor-cart-checkout-page-coupon button',
            'coupon_input');

        $this->add_coupon_login_section_style(
            'login_form',
            esc_html__('Login form', 'xstore-core'),
            '{{WRAPPER}} .etheme-elementor-cart-checkout-page-login-form',
            'login_form_button',
            '{{WRAPPER}} .etheme-elementor-cart-checkout-page-login-form button',
            'login_form_input');

        do_action('etheme_elementor_checkout_page_before_section_heading_style', $this);

        $this->start_controls_section(
            'section_heading_style',
            [
                'label' => esc_html__( 'Sections Heading', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_heading!' => ''
                ]
            ]
        );

        $this->add_responsive_control(
            'heading_align',
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
                    '{{WRAPPER}} .step-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .step-title',
            ]
        );

        if ( apply_filters('etheme_checkout_page_heading_color', true) ) {
            $this->add_control(
                'heading_color',
                [
                    'label' => esc_html__('Color', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .step-title' => 'color: {{VALUE}}',
                    ],
                ]
            );
        }

        $this->add_responsive_control(
            'heading_border_width',
            [
                'label' => esc_html__( 'Border Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range' => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 5,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-border-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'heading_type!' => ['classic']
                ]
            ]
        );

        $this->add_control(
            'heading_border_color',
            [
                'label'     => __( 'Border Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-border-color: {{VALUE}};',
                ],
                'condition' => [
                    'heading_type!' => ['classic']
                ]
            ]
        );

        $this->add_responsive_control(
            'heading_inner_spacing',
            [
                'label' => esc_html__( 'Inner Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-inner-space-bottom: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
                'condition' => [
                    'heading_type!' => ['classic']
                ]
            ]
        );

        $this->add_responsive_control(
            'heading_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-space-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_element_heading',
            [
                'label' => __( 'Design element', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'heading_type' => ['line-aside', 'square-aside', 'circle-aside', 'colored-underline']
                ]
            ]
        );

//        $this->add_responsive_control(
//            'heading_element_width',
//            [
//                'label' => esc_html__( 'Element Width', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SLIDER,
//                'size_units' => [ 'px', 'rem' ],
//                'range' => [
//                    'px' => [
//                        'min'  => 1,
//                        'max'  => 20,
//                        'step' => 1
//                    ],
//                ],
//                'selectors' => [
//                    '{{WRAPPER}}' => '--widget-title-element-width: {{SIZE}}{{UNIT}}',
//                ],
//                'condition' => [
//                    'heading_type' => ['line-aside']
//                ]
//            ]
//        );

        $this->add_control(
            'heading_element_color',
            [
                'label'     => __( 'Color Active', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-element-color: {{VALUE}};',
                ],
                'condition' => [
                    'heading_type' => ['line-aside', 'square-aside', 'circle-aside', 'colored-underline']
                ]
            ]
        );

        $this->end_controls_section();

        if ( apply_filters('etheme_checkout_fields_section_style', true) ) :

            $this->start_controls_section(
                'section_fields_section_style',
                [
                    'label' => esc_html__( 'Forms Wrapper', 'xstore-core' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'fields_section_background',
                    'types' => [ 'classic', 'gradient' ], // classic, gradient, video, slideshow
                    'selector'    => '{{WRAPPER}} .etheme-elementor-cart-checkout-page-column:has(.woocommerce-billing-fields)',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'fields_section_border',
                    'selector' => '{{WRAPPER}} .etheme-elementor-cart-checkout-page-column:has(.woocommerce-billing-fields)',
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'fields_section_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                    'selectors' => [
                        '{{WRAPPER}} .etheme-elementor-cart-checkout-page-column:has(.woocommerce-billing-fields)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'fields_section_box_shadow',
                    'selector' => '{{WRAPPER}} .etheme-elementor-cart-checkout-page-column:has(.woocommerce-billing-fields)',
                ]
            );

            $this->add_responsive_control(
                'fields_section_padding',
                [
                    'label' => esc_html__( 'Padding', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                    'selectors' => [
                        '{{WRAPPER}} .etheme-elementor-cart-checkout-page-column:has(.woocommerce-billing-fields)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();

        endif;

        $this->start_controls_section(
            'section_fields_style',
            [
                'label' => esc_html__( 'Fields', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'fields_stretch',
            [
                'label' => esc_html__('Stretch Fields', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'selectors_dictionary'  => [
                    'yes'         => 'width: 100%; padding-left: 0; padding-right: 0; float: none;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-cart-checkout-page-wrapper .form-row-first, {{WRAPPER}} .etheme-elementor-cart-checkout-page-wrapper .form-row-last' => '{{VALUE}}',
                ]
            ]
        );

        $this->add_responsive_control(
            'fields_cols_gap',
            [
                'label' => __( 'Columns Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'fields_stretch' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--fields-h-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'fields_rows_gap',
            [
                'label' => __( 'Rows Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--fields-v-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_label_style',
            [
                'label'                 => __( 'Labels', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .woocommerce-billing-fields label, {{WRAPPER}} .woocommerce-shipping-fields label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'label_typography',
                'selector'              => '{{WRAPPER}} .woocommerce-billing-fields label, {{WRAPPER}} .woocommerce-shipping-fields label',
            ]
        );

        $this->add_control(
            'label_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-billing-fields label, {{WRAPPER}} .woocommerce-shipping-fields label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'advanced_labels' => ''
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_input_field_style',
            [
                'label' => esc_html__('Input/Textarea Fields', 'xstore-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'      => 'input_field_typography',
                'label'     => esc_html__('Typography', 'xstore-core'),
                'selector'  => '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select, {{WRAPPER}} .woocommerce-input-wrapper .select2.select2-container--default .select2-selection--single',
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('tabs_input_field_style');

        $this->start_controls_tab(
            'tab_input_field_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core'),
            ]
        );

        $this->add_control(
            'input_field_text_color',
            [
                'label'     => esc_html__('Text Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select'  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_field_placeholder_color',
            [
                'label'     => esc_html__('Placeholder Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_field_background_color',
            [
                'label'     => esc_html__('Background Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select, {{WRAPPER}} .woocommerce-input-wrapper .select2.select2-container--default .select2-selection--single'  => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_field_focus',
            [
                'label' => esc_html__('Focus', 'xstore-core'),
            ]
        );

        $this->add_control(
            'input_field_focus_text_color',
            [
                'label'     => esc_html__('Text Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input:focus, {{WRAPPER}} .woocommerce-input-wrapper textarea:focus'  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_field_focus_background',
            [
                'label'     => esc_html__('Background', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input:focus, {{WRAPPER}} .woocommerce-input-wrapper textarea:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_field_focus_border_color',
            [
                'label'     => esc_html__('Border Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input:focus, {{WRAPPER}} .woocommerce-input-wrapper textarea:focus' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'input_field_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'        => 'input_field_border',
                'label'       => esc_html__('Border', 'xstore-core'),
                'selector'    => '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select, {{WRAPPER}} .woocommerce-input-wrapper .select2.select2-container--default .select2-selection--single',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'input_field_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select, {{WRAPPER}} .woocommerce-input-wrapper .select2.select2-container--default .select2-selection--single'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'input_field_box_shadow',
                'selector' => '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select, {{WRAPPER}} .woocommerce-input-wrapper .select2.select2-container--default .select2-selection--single',
            ]
        );

        $this->add_responsive_control(
            'input_field_padding',
            [
                'label'      => esc_html__('Padding', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select, {{WRAPPER}} .woocommerce-input-wrapper .select2.select2-container--default .select2-selection--single'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
                ],
                'separator'  => 'before',
            ]
        );

        $this->end_controls_section();

//        $this->start_controls_section(
//            'section_radio_checkbox_style',
//            [
//                'label' => esc_html__( 'Radio & Checkbox', 'xstore-core' ),
//                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
//            ]
//        );
//
//        $this->add_responsive_control(
//            'radio_checkbox_size',
//            [
//                'label'                 => __( 'Size', 'xstore-core' ),
//                'type'                  => \Elementor\Controls_Manager::SLIDER,
//                'range'                 => [
//                    'px'        => [
//                        'min'   => 0,
//                        'max'   => 80,
//                        'step'  => 1,
//                    ],
//                ],
//                'size_units'            => [ 'px', 'em', '%' ],
//                'selectors'             => [
//                    '{{WRAPPER}}' => '--et_inputs-radio-size: {{SIZE}}{{UNIT}};',
//                ],
//            ]
//        );
//
//        $this->end_controls_section();

        $this->start_controls_section(
            'section_order_total_style',
            [
                'label' => esc_html__( 'Order Total', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'order_total_table_typography',
                'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'order_total_background',
                'types' => [ 'classic', 'gradient' ], // classic, gradient, video, slideshow
                'selector'    => '{{WRAPPER}} .order-review',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'order_total_border',
                'selector' => '{{WRAPPER}} .cart-collaterals, {{WRAPPER}} .order-review',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'order_total_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .cart-collaterals, {{WRAPPER}} .order-review' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'order_total_box_shadow',
                'selector' => '{{WRAPPER}} .cart-collaterals, {{WRAPPER}} .order-review',
            ]
        );

        $this->add_responsive_control(
            'order_total_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .cart-collaterals, {{WRAPPER}} .order-review' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'order_total_table_space',
            [
                'label' => __( 'Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-checkout-review-order-table' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_payments_style',
            [
                'label'                 => __( 'Payments', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_payment_label',
            [
                'label' => esc_html__('Label', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'payment_label_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} #payment .payment_methods label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'payment_label_typography',
                'selector'              => '{{WRAPPER}} #payment .payment_methods label',
            ]
        );

        $this->add_control(
            'payment_label_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} #payment .payment_methods label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_payment_box',
            [
                'label' => esc_html__('Content', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'box_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .payment_box' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'box_typography',
                'selector'              => '{{WRAPPER}} .payment_box',
            ]
        );

        $this->add_control(
            'box_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .payment_box' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_terms_style',
            [
                'label'                 => __( 'Terms & Conditions', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'terms_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_terms_links_colors');

        $this->start_controls_tab(
            'tab_terms_link_color_color_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core'),
            ]
        );

        $this->add_control(
            'terms_link_color',
            [
                'label' => esc_html__( 'Link Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper a' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_terms_link_color_color_hover',
            [
                'label' => esc_html__('Links Hover', 'xstore-core'),
            ]
        );

        $this->add_control(
            'terms_link_color_hover',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper a:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'terms_typography',
                'selector'              => '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper',
            ]
        );

        $this->add_control(
            'terms_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_place_order_button_style',
            [
                'label' => __( 'Place Order Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'place_order_button_typography',
                'selector' => '{{WRAPPER}} #place_order',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'place_order_button_text_shadow',
                'selector' => '{{WRAPPER}} #place_order',
            ]
        );

        $this->start_controls_tabs( 'tabs_place_order_button_style' );

        $this->start_controls_tab(
            'tab_place_order_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'place_order_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} #place_order' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'place_order_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #place_order' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_place_order_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'place_order_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #place_order:hover, {{WRAPPER}} #place_order:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} #place_order:hover svg, {{WRAPPER}} #place_order:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'place_order_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #place_order:hover, {{WRAPPER}} #place_order:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'place_order_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'place_order_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} #place_order.button:hover, {{WRAPPER}} #place_order.button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'place_order_button_border',
                'selector' => '{{WRAPPER}} #place_order, {{WRAPPER}} #place_order.button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'place_order_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'place_order_button_shadow',
                'selector' => '{{WRAPPER}} #place_order',
            ]
        );

        $this->add_responsive_control(
            'place_order_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

    }

    public function add_coupon_login_section_style($prefix, $section_title, $selector, $button_prefix, $button_selector, $prefix_input) {
        $this->start_controls_section(
            'section_'.$prefix.'_section_style',
            [
                'label' => ucwords($section_title),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    $prefix.'_switcher!' => '',
                ]
            ]
        );

        $this->add_control(
            $prefix.'_align',
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
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            $prefix.'_section_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selector => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => $prefix.'_section_border',
                'selector' => $selector,
            ]
        );

        $this->add_control(
            $prefix.'_section_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => $prefix.'_section_box_shadow',
                'selector' => $selector,
            ]
        );

        $this->add_responsive_control(
            $prefix.'_section_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            $prefix.'_section_space',
            [
                'label' => __( 'Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    $selector => '--checkout-section-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            $prefix.'_heading_style',
            [
                'label' => __( 'Heading', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => $prefix.'heading_typography',
                'selector' => $selector . ' .section-heading',
            ]
        );

        $this->add_control(
            $prefix.'heading_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selector . ' .section-heading' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix.'heading_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    $selector => '--checkout-section-heading-spacing: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        if ( $prefix == 'login_form' ) {
            $this->add_control(
                $prefix . '_label_style',
                [
                    'label' => __('Label', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                $prefix . 'label_color',
                [
                    'label' => __('Text Color', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        $selector . ' label' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => $prefix . 'label_typography',
                    'selector' => $selector . ' label',
                ]
            );

            $this->add_control(
                $prefix . 'label_spacing',
                [
                    'label' => esc_html__('Bottom Spacing', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', 'rem'],
                    'selectors' => [
                        $selector . ' label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
                ]
            );
        }

        $this->add_control(
            $button_prefix.'_button_style',
            [
                'label' => __( 'Button', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => $button_prefix . '_typography',
                'selector' => $button_selector,
            ]
        );

        $this->start_controls_tabs( 'tabs_'.$button_prefix.'_style' );

        $this->start_controls_tab(
            'tab_'.$button_prefix.'_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            $button_prefix . '_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    $button_selector => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $button_prefix . '_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $button_selector => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_'.$button_prefix.'_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            $button_prefix . '_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $button_selector.':hover, ' . $button_selector.':focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    $button_selector.':hover svg, ' . $button_selector.':focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $button_prefix . '_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $button_selector.':hover, ' . $button_selector.':focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $button_prefix . '_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    $button_prefix . '_border_border!' => '',
                ],
                'selectors' => [
                    $button_selector.':hover, ' . $button_selector.':focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => $button_prefix . '_border',
                'selector' => $button_selector,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            $button_prefix . '_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    $button_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $button_prefix . '_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    $button_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        if ( $prefix == 'login_form' ) {
            $this->add_control(
                $button_prefix . '_spacing',
                [
                    'label' => esc_html__('Spacing', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', 'rem'],
                    'selectors' => [
                        $button_selector => 'margin-inline-start: {{SIZE}}{{UNIT}}',
                    ],
                ]
            );
        }

        $this->add_control(
            $prefix_input.'_style',
            [
                'label' => __( 'Input/Textarea', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'      => $prefix_input . '_typography',
                'label'     => esc_html__('Typography', 'xstore-core'),
                'selector'  => $selector . ' input, ' . $selector . ' textarea, ' . $selector . ' select, ' . $selector . ' .select2.select2-container--default .select2-selection--single',
            ]
        );

        $this->start_controls_tabs('tabs_' . $prefix_input . '_style');

        $this->start_controls_tab(
            'tab_' . $prefix_input . '_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core'),
            ]
        );

        $this->add_control(
            $prefix_input . '_text_color',
            [
                'label'     => esc_html__('Text Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selector . ' input, ' . $selector . ' textarea, ' . $selector . ' select'  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix_input . '_placeholder_color',
            [
                'label'     => esc_html__('Placeholder Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selector . ' input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix_input . '_background_color',
            [
                'label'     => esc_html__('Background Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selector . ' input, ' . $selector . ' textarea, ' . $selector . ' select, ' . $selector . ' .select2.select2-container--default .select2-selection--single'  => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_' . $prefix_input . '_focus',
            [
                'label' => esc_html__('Focus', 'xstore-core'),
            ]
        );

        $this->add_control(
            $prefix_input . '_focus_text_color',
            [
                'label'     => esc_html__('Text Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selector . ' input:focus, ' . $selector . ' textarea:focus'  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix_input . '_focus_background',
            [
                'label'     => esc_html__('Background', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selector . ' input:focus, ' . $selector . ' textarea:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix_input . '_focus_border_color',
            [
                'label'     => esc_html__('Border Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selector . ' input:focus, ' . $selector . ' textarea:focus' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    $prefix_input . '_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'        => $prefix_input . '_border',
                'label'       => esc_html__('Border', 'xstore-core'),
                'selector'    => $selector . ' input, ' . $selector . ' textarea, ' . $selector . ' select, ' . $selector . ' .select2.select2-container--default .select2-selection--single',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            $prefix_input . '_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    $selector . ' input, ' . $selector . ' textarea, ' . $selector . ' select, ' . $selector . ' .select2.select2-container--default .select2-selection--single'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix_input . '_padding',
            [
                'label'      => esc_html__('Padding', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    $selector . ' input, ' . $selector . ' textarea, ' . $selector . ' select, ' . $selector . ' .select2.select2-container--default .select2-selection--single'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            $prefix_input . '_spacing',
            [
                'label' => esc_html__('Spacing', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem'],
                'selectors' => [
                    $selector => '--fields-h-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        if ( $prefix == 'login_form' ) {
            $prefix_link = $prefix . '_link';
            $selector_link = $selector . ' .section-content a';
            $this->add_control(
                $prefix_link.'_style',
                [
                    'label' => __( 'Links', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name'      => $prefix_link . '_typography',
                    'selector'  => $selector_link,
                ]
            );

            $this->start_controls_tabs('tabs_' . $prefix_link . '_style');

            $this->start_controls_tab(
                'tab_' . $prefix_link . '_normal',
                [
                    'label' => esc_html__('Normal', 'xstore-core'),
                ]
            );

            $this->add_control(
                $prefix_link . '_color',
                [
                    'label'     => esc_html__('Text Color', 'xstore-core'),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        $selector_link => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'tab_' . $prefix_link . '_hover',
                [
                    'label' => esc_html__('Hover', 'xstore-core'),
                ]
            );

            $this->add_control(
                $prefix_link . '_color_hover',
                [
                    'label'     => esc_html__('Text Color', 'xstore-core'),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        $selector_link . ':hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();
        }

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2.4
     * @access protected
     */
    protected function render() {
        $is_checkout_basic = get_query_var('et_is-checkout-basic', false);
        if ( $is_checkout_basic || !$this->is_woocommerce() || 0 === WC()->cart->get_cart_contents_count() ) {
            if ( $is_checkout_basic ) {
                echo do_shortcode('[woocommerce_checkout]');
            }
            return;
        }

        // to prevent any hook/filters added from advanced cart/checkout feature
        add_filter('theme_mod_cart_checkout_advanced_layout', '__return_false');

        $settings = $this->get_settings_for_display();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        $two_columns = $settings['cols'] == '2';
        $elements_list = $this->get_elements_list();
        unset($elements_list['coupon']);
        $elements_list_first = $elements_list;
        unset($elements_list_first['order_review']);
        $elements_list_last = isset($elements_list['order_review']) ? array('order_review' => $elements_list['order_review']) : array();

        if ( $edit_mode )
            add_filter('etheme_checkout_form_shipping_address', '__return_true');

        if ( $edit_mode ) {
            add_action('etheme_before_checkout_shipping_checkbox', array($this, 'add_shipping_details_information') );
            add_action('etheme_before_checkout_createaccount_checkbox', array($this, 'add_new_account_information') );
        }

        if ( !!$settings['advanced_labels'] ) {
            wp_enqueue_script('cart_checkout_advanced_labels');

            add_filter('woocommerce_default_address_fields', array($this, 'filter_form_placeholders'));
            add_filter( 'woocommerce_form_field_args', array($this, 'filter_form_fields'));
        }

        if ( !!$settings['order_review_sticky'] ) {
            wp_enqueue_script('sticky-kit');
        }

        if ( !!$settings['order_review_sticky'] || !!$settings['order_review_product_remove'] || $this->should_render_coupon() || $this->should_render_login())
            wp_enqueue_script('etheme_elementor_checkout_page');

        // ajax functions added in framework/compatibility/elementor.php
        // @see woocommerce_review_order_before_submit/woocommerce_review_order_after_submit hook
        add_action('woocommerce_review_order_before_submit', [$this, 'woocommerce_review_order_before_submit'], 999);
        add_action('woocommerce_review_order_after_submit', [$this, 'woocommerce_review_order_after_submit'], 999);
        add_filter( 'woocommerce_form_field_args', [ $this, 'modify_form_field' ], 70, 3 );
        add_filter('woocommerce_form_field_args', [$this, 'sorting_address_fields'], 90, 1);

//        if ( $edit_mode )
//            add_filter('wp_doing_ajax', '__return_false');
//

        $added_new_account_display_filter = false;
        if ( !$edit_mode ) {
            $checkout = WC()->checkout();
            if ( ! get_query_var( 'et_is-loggedin', false) && $checkout->is_registration_enabled() ) {
                if ( ! $checkout->is_registration_required() || $checkout->get_checkout_fields( 'account' ) || has_action('etheme_before_checkout_createaccount_checkbox') || has_action('woocommerce_before_checkout_registration_form') || has_action('woocommerce_after_checkout_registration_form') ) {
                    $added_new_account_display_filter = true;
                }
            }
            if ( !$added_new_account_display_filter ) {
                add_filter('etheme_checkout_form_account_registration', '__return_false');
            }
        }

        $headings = array(
            'etheme_checkout_form_billing_title',
            'etheme_checkout_form_shipping_title',
            'etheme_checkout_form_new_account_title',
            'etheme_checkout_form_additional_information_title_force_display',
            'etheme_checkout_form_additional_information_title',
            'etheme_checkout_your_order_title',
            'etheme_checkout_form_payment_methods_title',
        );

//        if ( apply_filters($this->get_name().'_additional_information_heading_section_displayed', false) ) {
//            $headings[] = 'etheme_checkout_form_additional_information_title_force_display';
//        }

        if ( !!$settings['show_heading'] ) {
            foreach ($headings as $heading) {
                add_filter($heading, '__return_true');
            }
            add_filter('etheme_woocommerce_checkout_title_tag', array($this, 'title_tag'));
            add_filter('etheme_woocommerce_checkout_title_class', array($this, 'title_class'));
        }
        else {
            foreach ($headings as $heading) {
                add_filter($heading, '__return_false');
            }
        }

        // ajax functions added in framework/compatibility/elementor.php
        // @see woocommerce_checkout_update_order_review hook
        add_filter('etheme_checkout_order_review_product_details_one_column', '__return_true');
        $order_review_features = array(
            'etheme_checkout_order_review_product_images',
            'etheme_checkout_order_review_product_quantity',
            'etheme_checkout_order_review_product_remove',
            'etheme_checkout_order_review_product_link',
            'etheme_checkout_order_review_product_subtotal'
        );
        foreach ($order_review_features as $order_review_feature) {
            $order_review_feature_key = str_replace('etheme_checkout_', '', $order_review_feature);
            if ( !!$settings[$order_review_feature_key] ) {
                add_filter($order_review_feature, '__return_true');
                if ( $order_review_feature_key == 'order_review_product_quantity' ) {
                    wp_enqueue_script('checkout_product_quantity');
                    add_filter($order_review_feature.'_style', array($this, 'product_quantity_style'));
                    $quantity_input = !in_array($settings['order_review_product_quantity_style'], array('', 'select'));
                    if ( $quantity_input )
                        add_filter('theme_mod_shop_quantity_type', array($this, 'return_input_value'));
                }
            }
            else
                add_filter($order_review_feature, '__return_false');
        }
        $display_coupon = $settings['coupon_position'] == 'above_all';

        // wrap it only for edit mode because on real frontend we wrap whole section with such widget inside form
        if ( $edit_mode ) { ?>
        <form action="" class="checkout">
            <?php }

        $this->render_woocommerce_checkout_login_form();

        if ( $display_coupon )
            $this->render_woocommerce_checkout_coupon_form();

        ?>
        <div class="<?php if ( $two_columns ): ?>flex align-items-start <?php endif; ?>etheme-elementor-cart-checkout-page-wrapper">
            <div class="<?php echo implode(' ', apply_filters('etheme_elementor_checkout_page_column_'.(!!$settings['reverse_order'] ? 'last': 'first').'_classes', array('etheme-elementor-cart-checkout-page-column', (!!$settings['reverse_order'] ? 'last': 'first')))); ?>">
                <?php $this->print_elements($settings, (!!$settings['reverse_order'] ? $elements_list_last : $elements_list_first)); ?>
            </div>
            <div class="<?php echo implode(' ', apply_filters('etheme_elementor_checkout_page_column_'.(!!$settings['reverse_order'] ? 'first': 'last').'_classes', array('etheme-elementor-cart-checkout-page-column', (!!$settings['reverse_order'] ? 'first': 'last')))); ?>">
                <?php $this->print_elements($settings, (!!$settings['reverse_order'] ? $elements_list_first : $elements_list_last)); ?>
            </div>
        </div>
        <?php
        if ( $edit_mode ) { ?>
            </form>
        <?php }
        foreach ($order_review_features as $order_review_feature) {
            $order_review_feature_key = str_replace('etheme_checkout_', '', $order_review_feature);
            if ( !!$settings[$order_review_feature_key] ) {
                remove_filter($order_review_feature, '__return_true');
                if ( $order_review_feature_key == 'order_review_product_quantity' ) {
                    add_filter($order_review_feature.'_style', array($this, 'product_quantity_style'));
                    add_filter($order_review_feature.'_size', array($this, 'product_quantity_size'));
                    $quantity_input = !in_array($settings['order_review_product_quantity_style'], array('', 'select'));
                    if ( $quantity_input )
                        remove_filter('theme_mod_shop_quantity_type', array($this, 'return_input_value'));
                }
            }
            else
                remove_filter($order_review_feature, '__return_false');
        }
        remove_filter('etheme_checkout_order_review_product_details_one_column', '__return_true');

        remove_filter( 'woocommerce_form_field_args', [ $this, 'modify_form_field' ], 70, 3 );
        remove_filter('woocommerce_form_field_args', [$this, 'sorting_address_fields'], 90, 1);

        remove_action('woocommerce_review_order_before_submit', [$this, 'woocommerce_review_order_before_submit'], 999);
        remove_action('woocommerce_review_order_after_submit', [$this, 'woocommerce_review_order_after_submit'], 999);

        if ( !!$settings['advanced_labels'] ) {
            remove_filter('woocommerce_default_address_fields', array($this, 'filter_form_placeholders'));
            remove_filter( 'woocommerce_form_field_args', array($this, 'filter_form_fields'));
        }
        if ( !!$settings['show_heading'] ) {
            foreach ($headings as $heading) {
                remove_filter($heading, '__return_true');
            }
            remove_filter('etheme_woocommerce_checkout_title_tag', array($this, 'title_tag'));
            remove_filter('etheme_woocommerce_checkout_title_class', array($this, 'title_class'));
        }
        else {
            foreach ($headings as $heading) {
                remove_filter($heading, '__return_false');
            }
        }

        if ( $edit_mode ) {
            remove_action('etheme_before_checkout_createaccount_checkbox', array($this, 'add_new_account_information') );
            remove_action('etheme_before_checkout_shipping_checkbox', array($this, 'add_shipping_details_information') );
        }

        if ( !$edit_mode && !$added_new_account_display_filter ) {
            remove_filter('etheme_checkout_form_account_registration', '__return_false');
        }

//        if ( $edit_mode )
//            remove_filter('wp_doing_ajax', '__return_false');
        
        // On render widget from Editor - trigger the init manually.
        if ( $edit_mode ) {
            ?>
            <script>
                jQuery(document).ready(function ($) {
                    $(document).find( 'div.shipping_address' ).hide();
                    $(document).find('#ship-to-different-address input').on('change', function () {
                        $(document).find( 'div.shipping_address' ).hide();
                        if ( $( this ).is( ':checked' ) ) {
                            $(document).find( 'div.shipping_address' ).slideDown();
                        }
                    })
                    $( 'input#createaccount' ).on( 'change', function () {
                        $(document).find('div.create-account').hide();
                        $(document).find('p.create-account').show();
                        if ($(this).is(':checked')) {
                            // Ensure password is not pre-populated.
                            $('#account_password').val('').trigger('change');
                            $('div.create-account').slideDown();
                        }
                    }).trigger('change');
                    if ( etTheme.cart_checkout_advanced_labels !== undefined )
                        etTheme.cart_checkout_advanced_labels();
                });
            </script>
            <style>
                /* On real frontend there will be select2 by WooCommerce script */
                [data-id="<?php echo $this->get_id(); ?>"] .woocommerce-input-wrapper select {
                    width: 100%;
                }
            </style>
            <?php
        }
    }

    protected function is_woocommerce() {
        return !(! function_exists( 'WC' ) || ! property_exists( WC(), 'cart' ) || ! is_object( WC()->cart ));
    }

    public function print_elements($settings, $elements) {
        $display_coupon = in_array($settings['coupon_position'], array('order_review_top', 'order_review_bottom'));
        $display_coupon_priority = 15;
        if ( $display_coupon && $settings['coupon_position'] == 'order_review_top' )
            $display_coupon_priority = 5;

        $payments_separated = $settings['payment_methods_position'] == 'separated';
        $shipping_methods_separated = $settings['shipping_methods_position'] == 'separated';
        foreach ($elements as $checkout_element_key => $checkout_element_title) {
            switch ($checkout_element_key) {
                case 'billing_details':
                    $this->print_billing_details($settings);
                    break;
                case 'shipping_details':
                    $this->print_shipping_details($settings);
                    break;
                case 'payment_methods':
                    if ( $payments_separated )
                        $this->print_payment_methods();
                    break;
                case 'shipping_methods':
                    if ( $shipping_methods_separated )
                        $this->print_shipping_methods();
                    break;
                case 'order_review':
                    if ( $display_coupon )
                        add_action( 'woocommerce_checkout_order_review', [ $this, 'render_woocommerce_checkout_coupon_form' ], $display_coupon_priority );
                    if ( $payments_separated )
                        remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
                    if ( $shipping_methods_separated )
                        add_filter( 'etheme_checkout_form_shipping_methods', '__return_false' );
                        ?>
                    <div class="etheme-elementor-checkout-page-order-details-wrapper">
                        <div class="<?php echo implode(' ', apply_filters('etheme_elementor_checkout_page_order_details_inner_classes', array('etheme-elementor-checkout-page-order-details-inner'))); ?>">
                            <div class="cart-order-details">
                                <div class="order-review">
                                    <?php if ( apply_filters('etheme_checkout_your_order_title', false) ) { ?>
                                        <<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?> class="<?php echo apply_filters('etheme_woocommerce_checkout_title_class', 'step-title'); ?>">
                                            <span><?php echo apply_filters('etheme_woocommerce_checkout_your_order_title', esc_html__( 'Your order', 'xstore-core' )); ?></span>
                                        </<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?>>
                                    <?php } ?>

                                    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                                    <div id="order_review" class="woocommerce-checkout-review-order">
                                        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                                    </div>

                                    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ( $shipping_methods_separated )
                        remove_filter( 'etheme_checkout_form_shipping_methods', '__return_false' );
                    if ( $payments_separated )
                        add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
                    if ( $display_coupon )
                        remove_action( 'woocommerce_checkout_order_review', [ $this, 'render_woocommerce_checkout_coupon_form' ], $display_coupon_priority );
                    break;
            }
        }
    }

    public function print_billing_details($settings) {
        if ( !!$settings['billing_details_email_field_first'] ) {
            add_filter('woocommerce_billing_fields', array($this, 'prioritize_email_field'));
            // compatibility with Brazilian Market on WooCommerce plugin
            add_filter('wcbcf_billing_fields', array($this, 'wcbcf_prioritize_email_field'));
        }

        add_filter('etheme_woocommerce_checkout_billing_title', array($this, 'modify_billing_section_title'));
        add_filter('etheme_woocommerce_checkout_new_account_title', array($this, 'modify_new_account_section_title'));

        WC()->checkout()->checkout_form_billing();

        remove_filter('etheme_woocommerce_checkout_new_account_title', array($this, 'modify_new_account_section_title'));
        remove_filter('etheme_woocommerce_checkout_billing_title', array($this, 'modify_billing_section_title'));

        if ( !!$settings['billing_details_email_field_first'] ) {
            remove_filter('woocommerce_billing_fields', array($this, 'prioritize_email_field'));
            // compatibility with Brazilian Market on WooCommerce plugin
            remove_filter('wcbcf_billing_fields', array($this, 'wcbcf_prioritize_email_field'));
        }
    }

    public function modify_billing_section_title($title) {
        $new_title = $this->get_settings_for_display('billing_details_section_heading');
        return !empty($new_title) ? $new_title : $title;
    }

    public function print_shipping_details($settings) {
        add_filter('etheme_woocommerce_checkout_shipping_title', array($this, 'modify_shipping_section_title'));
        add_filter('etheme_woocommerce_checkout_additional_information_title', array($this, 'modify_additional_information_section_title'));
        if ( !!$settings['additional_information_switcher'] )
            add_filter('etheme_checkout_form_additional_information', '__return_true');
        else {
            add_filter('etheme_checkout_form_additional_information', '__return_false');
            add_filter('etheme_checkout_form_additional_information_wrapper', '__return_false');
            add_filter('etheme_checkout_form_additional_information_separated', '__return_false');
        }

            WC()->checkout()->checkout_form_shipping();

        if ( !!$settings['additional_information_switcher'] )
            remove_filter('etheme_checkout_form_additional_information', '__return_true');
        else {
            remove_filter('etheme_checkout_form_additional_information', '__return_false');
            remove_filter('etheme_checkout_form_additional_information_wrapper', '__return_false');
            remove_filter('etheme_checkout_form_additional_information_separated', '__return_false');
        }

        remove_filter('etheme_woocommerce_checkout_additional_information_title', array($this, 'modify_additional_information_section_title'));
        remove_filter('etheme_woocommerce_checkout_shipping_title', array($this, 'modify_shipping_section_title'));
    }

    public function modify_shipping_section_title($title) {
        $new_title = $this->get_settings_for_display('shipping_details_section_heading');
        return !empty($new_title) ? $new_title : $title;
    }

    public function modify_additional_information_section_title($title) {
        $new_title = $this->get_settings_for_display('additional_information_section_heading');
        return !empty($new_title) ? $new_title : $title;
    }

    public function modify_new_account_section_title($title) {
        $new_title = $this->get_settings_for_display('new_account_section_heading');
        return !empty($new_title) ? $new_title : $title;
    }

    public function print_payment_methods() {
        WC()->cart->calculate_fees();
        WC()->cart->calculate_shipping();
        WC()->cart->calculate_totals();

        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $edit_mode )
            add_filter('wp_doing_ajax', '__return_false');

        add_action('woocommerce_review_order_before_payment', array($this, 'add_payment_methods_wrapper_start'));
        add_action('woocommerce_review_order_before_payment', array($this, 'add_payment_methods_heading'));
        add_action('woocommerce_review_order_after_payment', array($this, 'add_payment_methods_wrapper_end'));

        add_filter('etheme_woocommerce_checkout_payment_methods_title', array($this, 'modify_payment_methods_section_title'));

        woocommerce_checkout_payment();

        remove_filter('etheme_woocommerce_checkout_payment_methods_title', array($this, 'modify_payment_methods_section_title'));

        remove_action('woocommerce_review_order_after_payment', array($this, 'add_payment_methods_wrapper_end'));
        remove_action('woocommerce_review_order_before_payment', array($this, 'add_payment_methods_heading'));
        remove_action('woocommerce_review_order_before_payment', array($this, 'add_payment_methods_wrapper_start'));

        if ( $edit_mode )
            remove_filter('wp_doing_ajax', '__return_false');

    }

    public function get_shipping_methods_content() {
        ob_start(); ?>
        <div class="etheme-woocommerce-shipping-methods">
            <table>
                <tbody>
                <?php
                add_filter('etheme_show_chosen_shipping_method', '__return_false');
                add_filter('etheme_cart_shipping_heading', '__return_false');
                add_filter('etheme_cart_shipping_full_width', '__return_true');
                $packages = WC()->shipping()->get_packages();
                $rates = false;
                foreach ($packages as $package) {
                    if ( $rates ) break;

                    if ( $package['rates'] )
                        $rates = true;
                }
                if ( count($packages) && $rates ) {
                    wc_cart_totals_shipping_html();
                }
                else {
                    echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' .
                        esc_html__( 'Sorry, it seems that there are no shipping options available. Please contact us if you require assistance or wish to make alternate arrangements.', 'xstore-core' ) .
                        '</li>'; // @codingStandardsIgnoreLine
                }
                remove_filter('etheme_cart_shipping_full_width', '__return_true');
                remove_filter('etheme_cart_shipping_heading', '__return_false');
                remove_filter('etheme_show_chosen_shipping_method', '__return_false');
                ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }
    public function print_shipping_methods() {
        if ( !WC()->cart->needs_shipping() || !WC()->cart->show_shipping() ) return;

        add_filter('etheme_woocommerce_checkout_shipping_methods_title', array($this, 'modify_shipping_methods_section_title'));

        $wrap_fields_wrapper = apply_filters('etheme_checkout_form_shipping_methods_wrapper', false);
        ?>
            <div class="<?php echo implode(' ', apply_filters('etheme_checkout_form_shipping_methods_wrapper_classes', array('woocommerce-shipping-methods-fields'))); ?>">
                <?php if ( apply_filters('etheme_checkout_form_shipping_title', true) ) { ?>
                    <<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?> class="<?php echo apply_filters('etheme_woocommerce_checkout_title_class', 'step-title'); ?>">
                    <span><?php echo apply_filters('etheme_woocommerce_checkout_shipping_methods_title', esc_html__( 'Shipping methods', 'xstore-core' )); ?></span>
                    </<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?>>
                <?php }
                if ( $wrap_fields_wrapper ) : ?>
                    <div class="<?php echo implode(' ', apply_filters('etheme_checkout_form_shipping_fields_wrapper_classes', array('woocommerce-shipping-methods-fields-wrapper'))); ?>">
                <?php endif;
                        echo $this->get_shipping_methods_content();
                        do_action('etheme_after_checkout_shipping_methods_form_fields_wrapper' );
                    if ( $wrap_fields_wrapper ) : ?>
                </div>
                <?php endif; ?>
            </div>
        <?php
        remove_filter('etheme_woocommerce_checkout_shipping_methods_title', array($this, 'modify_shipping_methods_section_title'));
    }

    public function modify_payment_methods_section_title($title) {
        $new_title = $this->get_settings_for_display('payment_methods_section_heading');
        return !empty($new_title) ? $new_title : $title;
    }

    public function modify_shipping_methods_section_title($title) {
        $new_title = $this->get_settings_for_display('shipping_methods_section_heading');
        return !empty($new_title) ? $new_title : $title;
    }

    public function add_payment_methods_heading() {
        if ( apply_filters('etheme_checkout_form_payment_methods_title', true) ) { ?>
            <<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?> class="<?php echo apply_filters('etheme_woocommerce_checkout_title_class', 'step-title'); ?>">
            <span><?php echo apply_filters('etheme_woocommerce_checkout_payment_methods_title', esc_html__( 'Payment', 'xstore-core' )); ?></span>
            </<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?>>
        <?php }
        if ( apply_filters('etheme_checkout_form_payment_methods_wrapper', false) ) : ?>
            <div class="<?php echo implode(' ', apply_filters('etheme_checkout_form_payment_methods_fields_wrapper_classes', array('woocommerce-payment-methods-fields-wrapper'))); ?>">
        <?php endif;
    }

    public function add_payment_methods_wrapper_start() {
        ?>
        <div class="<?php echo implode(' ', apply_filters('etheme_checkout_form_payment_methods_wrapper_classes', array('woocommerce-checkout-payment-wrapper'))); ?>">
        <?php
    }
    public function add_payment_methods_wrapper_end() {
            do_action('etheme_after_checkout_payment_methods_form_fields_wrapper' ) ?>
            <?php
            if ( apply_filters('etheme_checkout_form_payment_methods_wrapper', false) ) : ?>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function return_input_value() {
        return 'input';
    }

    public function product_quantity_style() {
        $quantity_style = $this->get_settings_for_display('order_review_product_quantity_style');
        if ( !$quantity_style )
            $quantity_style = 'square';
        return $quantity_style;
    }

    public function product_quantity_size() {
        return 'size-sm';
    }

    public function woocommerce_review_order_before_submit() {?>
        <div class="etheme-before-place-order-button">
    <?php }

    public function woocommerce_review_order_after_submit() {?>
        </div>
    <?php }

    public function title_tag($html_tag) {
        $settings = $this->get_settings_for_display();
        return $settings['heading_html_tag'];
    }

    public function title_class($class) {
        $settings = $this->get_settings_for_display();
        return $class . ' style-' . $settings['heading_type'];
    }

    public function add_shipping_details_information() {
        echo Elementor::elementor_frontend_alert_message(
                '<strong>'.esc_html__('This message is shown only in edit mode.', 'xstore-core').'</strong>' . '<br/>' .
                $this->get_shipping_details_information()
            ).'<br/>';
    }

    public function get_shipping_details_information() {
        return __('Shipping form will be displayed on frontend only in case WooCommerce settings are set correct and Products in your cart need to have shipping', 'xstore-core') .
            '<br/>' .
            sprintf(__('Note: Shipping settings you can find in WooCommerce -> Settings -> %s.', 'xstore-core'),
            '<a href="'.admin_url('admin.php?page=wc-settings&tab=shipping').'" target="_blank"><strong>'.esc_html__('Shipping', 'xstore-core').'</strong></a>');
    }

    public function add_new_account_information() {
        echo Elementor::elementor_frontend_alert_message(
                '<strong>'.esc_html__('This message is shown only in edit mode.', 'xstore-core').'</strong>' . '<br/>' .
                $this->get_new_account_information()
            ).'<br/>';
    }

    public function get_new_account_information() {
        return sprintf(__('New Account section will be displayed in case you have %s options properly configured in WooCommerce -> Settings and customer is not logged in on checkout page.', 'xstore-core'),
                '<a href="'.admin_url('admin.php?page=wc-settings&tab=account').'" target="_blank"><strong>'.esc_html__('Account', 'xstore-core').'</strong></a>');
    }

    public function get_changable_fields_information() {
        return sprintf(__('You can change the %s (optional/required/hidden) by properly configuring them in Theme Options -> WooCommerce -> Checkout Settings.', 'xstore-core'),
            '<a href="'.admin_url( '/customize.php?autofocus[section]=woocommerce_checkout' ).'" target="_blank"><strong>'.esc_html__('status of fields', 'xstore-core').'</strong></a>');
    }

    public function sorting_address_fields($address_fields)
    {
        $sorted_fields = $this->get_reformatted_form_fields();
        foreach ($address_fields as $address_field_key => $address_field) {
            if (isset($sorted_fields[$address_field_key])) {
                if (isset($sorted_fields[$address_field_key]['priority'])) {
                    $address_fields[$address_field_key]['priority'] = $sorted_fields[$address_field_key]['priority'];
                }
//                $address_fields[$address_field_key]['et_stretched'] = $sorted_fields[$address_field_key]['stretched'];
            }
        }
        return $address_fields;
    }
    
    public function filter_form_placeholders($fields) {
        $new_fields = array();
        foreach ($fields as $field_key => $field) {
            if ( isset($field['label']) && $field['label'] != '' ) {
                if ( isset($field['label_class']) ) {
                    if ( !in_array( 'screen-reader-text', $field['label_class'] ) )
                        $field['placeholder'] = '';
                }
                elseif ( isset($field['placeholder']) ) {
                    $field['placeholder'] = '';
                }
            }
            $new_fields[$field_key] = $field;
        }
        return $new_fields;
    }

    public function filter_form_fields ( $args ) {
        if ( $args['label'] != '' && ! in_array( 'screen-reader-text', $args['label_class'] ) ) {
            $args['class'][] = 'et-advanced-label';
            $args['placeholder'] = '';
        }

        if ( $args['type'] == 'textarea' ) {
            $args['label_class'][] = 'textarea-label';
        }

        if ( ! in_array( 'screen-reader-text', $args['label_class'] ) ) {
            $args['class'][] = 'et-validated';
        }

        return $args;
    }

    public function prioritize_email_field($fields) {
        if ( isset($fields['billing_email'])) {
            $fields['billing_email']['priority'] = 3;
        }
        // Fix autofocus - maybe be useful in future updates
        //                if ( isset( $fields['billing'] ) ) $fields['billing']['billing_first_name']['autofocus'] = false;
        //                if ( isset( $fields['shipping'] ) ) $fields['shipping']['shipping_first_name']['autofocus'] = false;
        return $fields;
    }

    public function wcbcf_prioritize_email_field($fields){
        if ( isset($fields['billing_first_name'])) {
            $fields['billing_first_name']['priority'] = 15;
        }
        if ( isset($fields['billing_last_name'])) {
            $fields['billing_last_name']['priority'] = 15;
        }
        return $fields;
    }

    /**
     * Modify Form Field.
     *
     * WooCommerce filter is used to apply widget settings to the Checkout forms address fields
     * from the Billing and Shipping Details widget sections, e.g. label, placeholder, default.
     *
     * @since 5.2.4
     *
     * @param array $args
     * @param string $key
     * @param string $value
     * @return array
     */
    public function modify_form_field( $args, $key, $value ) {
        $reformatted_form_fields = $this->get_reformatted_form_fields();
        // Check if we need to modify the args of this form field.
        if ( isset( $reformatted_form_fields[ $key ] ) ) {
            $apply_fields = [
                'label',
                'placeholder',
                'default',
                'priority',
            ];

            foreach ( $apply_fields as $field ) {
                if ( ! empty( $reformatted_form_fields[ $key ][ $field ] ) ) {
                    $args[ $field ] = $reformatted_form_fields[ $key ][ $field ];
                }
            }
        }

        return $args;
    }

    /**
     * Get Reformatted Form Fields.
     *
     * Combines the 3 relevant repeater settings arrays into a one level deep associative array
     * with the keys that match those that WooCommerce uses for its form fields.
     *
     * The result is cached so the conversion only ever happens once.
     *
     * @since 5.2.4
     *
     * @return array
     */
    private function get_reformatted_form_fields() {
        if ( ! isset( $this->reformatted_form_fields ) ) {
            $instance = $this->get_settings_for_display();

            // Reformat form repeater field into one usable array.
            $repeater_fields = [
                'billing_details_form_fields',
                'shipping_details_form_fields',
                'additional_information_form_fields',
            ];

            $this->reformatted_form_fields = [];

            // Apply other modifications to inputs.
            foreach ( $repeater_fields as $repeater_field ) {
                if ( isset( $instance[ $repeater_field ] ) ) {
                    foreach ( $instance[ $repeater_field ] as $item_index => $item ) {
                        if ( ! isset( $item['field_key'] ) ) {
                            continue;
                        }
                        $item['priority'] = ($item_index*10);

                        $this->reformatted_form_fields[$item['field_key']] = $item;

                    }
                }
            }
        }

        return $this->reformatted_form_fields;
    }

    /**
     * Get Billing Field Defaults
     *
     * Get defaults used for the billing details repeater control.
     *
     * @since 5.2.4
     *
     * @return array
     */
    private function get_billing_field_defaults() {
        $fields = [
            'billing_first_name' => [
                'label' => esc_html__( 'First Name', 'xstore-core' ),
                'repeater_state' => '',
//                'stretched' => false,
            ],
            'billing_last_name' => [
                'label' => esc_html__( 'Last Name', 'xstore-core' ),
                'repeater_state' => '',
//                'stretched' => false,
            ],
            'billing_company' => [
                'label' => esc_html__( 'Company Name', 'xstore-core' ),
                'repeater_state' => '',
//                'stretched' => 'yes',
            ],
            'billing_country' => [
                'label' => esc_html__( 'Country / Region', 'xstore-core' ),
                'repeater_state' => 'locale',
//                'stretched' => 'yes',
            ],
            'billing_address_1' => [
                'label' => esc_html__( 'Street Address', 'xstore-core' ),
                'repeater_state' => 'locale',
//                'stretched' => 'yes',
            ],
            'billing_postcode' => [
                'label' => esc_html__( 'Post Code', 'xstore-core' ),
                'repeater_state' => 'locale',
//                'stretched' => 'yes',
            ],
            'billing_city' => [
                'label' => esc_html__( 'Town / City', 'xstore-core' ),
                'repeater_state' => 'locale',
//                'stretched' => 'yes',
            ],
            'billing_state' => [
                'label' => esc_html__( 'State', 'xstore-core' ),
                'repeater_state' => 'locale',
//                'stretched' => 'yes',
            ],
            'billing_phone' => [
                'label' => esc_html__( 'Phone', 'xstore-core' ),
                'repeater_state' => '',
//                'stretched' => 'yes',
            ],
            'billing_email' => [
                'label' => esc_html__( 'Email Address', 'xstore-core' ),
                'repeater_state' => '',
//                'stretched' => 'yes',
            ],
        ];

        $fields = apply_filters('etheme_billing_details_fields', $fields);

        return $this->reformat_address_field_defaults( $fields );
    }


    /**
     * Get Shipping Field Defaults
     *
     * Get defaults used for the shipping details repeater control.
     *
     * @since 5.2.4
     *
     * @return array
     */
    private function get_shipping_field_defaults() {
        $fields = [
            'shipping_first_name' => [
                'label' => esc_html__( 'First Name', 'xstore-core' ),
                'repeater_state' => '',
//                'stretched' => false,
            ],
            'shipping_last_name' => [
                'label' => esc_html__( 'Last Name', 'xstore-core' ),
                'repeater_state' => '',
//                'stretched' => false,
            ],
            'shipping_company' => [
                'label' => esc_html__( 'Company Name', 'xstore-core' ),
                'repeater_state' => '',
//                'stretched' => 'yes',
            ],
            'shipping_country' => [
                'label' => esc_html__( 'Country / Region', 'xstore-core' ),
                'repeater_state' => 'locale',
//                'stretched' => 'yes',
            ],
            'shipping_address_1' => [
                'label' => esc_html__( 'Street Address', 'xstore-core' ),
                'repeater_state' => 'locale',
//                'stretched' => 'yes',
            ],
            'shipping_postcode' => [
                'label' => esc_html__( 'Post Code', 'xstore-core' ),
                'repeater_state' => 'locale',
//                'stretched' => 'yes',
            ],
            'shipping_city' => [
                'label' => esc_html__( 'Town / City', 'xstore-core' ),
                'repeater_state' => 'locale',
//                'stretched' => 'yes',
            ],
            'shipping_state' => [
                'label' => esc_html__( 'State', 'xstore-core' ),
                'repeater_state' => 'locale',
//                'stretched' => 'yes',
            ],
        ];

        $fields = apply_filters('etheme_shipping_details_fields', $fields);

        return $this->reformat_address_field_defaults( $fields );
    }

    /**
     * Reformat Address Field Defaults
     *
     * Used with the `get_..._field_defaults()` methods.
     * Takes the address array and converts it into the format expected by the repeater controls.
     *
     * @since 5.2.4
     *
     * @param $address
     * @return array
     */
    private function reformat_address_field_defaults( $address ) {
        $defaults = [];
        foreach ( $address as $key => $value ) {
            $defaults[] = [
                'field_key' => $key,
                'field_label' => $value['label'],
                'label' => $value['label'],
                'placeholder' => $value['label'],
                'repeater_state' => $value['repeater_state'],
//                'stretched' => $value['stretched'],
            ];
        }

        return $defaults;
    }

    /**
     * Is WooCommerce Feature Active.
     *
     * Checks whether a specific WooCommerce feature is active. These checks can sometimes look at multiple WooCommerce
     * settings at once so this simplifies and centralizes the checking.
     *
     * @since 5.2.4
     *
     * @param string $feature
     * @return bool
     */
    protected function is_wc_feature_active( $feature ) {
        // Return if wc is not active
        if (!class_exists( 'WooCommerce' )) {
            return false;
        }
        switch ( $feature ) {
            case 'checkout_login_reminder':
                if (self::$checkout_login_reminder_feature_status != null)
                    return self::$checkout_login_reminder_feature_status;
                self::$checkout_login_reminder_feature_status = 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' );
                return self::$checkout_login_reminder_feature_status;
            case 'shipping':
                if (self::$shipping_feature_status != null)
                    return self::$shipping_feature_status;
                if ( class_exists( 'WC_Shipping_Zones' ) ) {
                    $all_zones = \WC_Shipping_Zones::get_zones();
                    self::$shipping_feature_status = count( $all_zones ) > 0;
                }
                return self::$shipping_feature_status;
                break;
            case 'coupons':
                if (self::$coupons_feature_status != null)
                    return self::$coupons_feature_status;
                self::$coupons_feature_status = function_exists( 'wc_coupons_enabled' ) && wc_coupons_enabled();
                return self::$coupons_feature_status;
            case 'signup_and_login_from_checkout':
                if (self::$signup_and_login_from_checkout_status != null)
                    return self::$signup_and_login_from_checkout_status;
                self::$signup_and_login_from_checkout_status = 'yes' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' );
                return self::$signup_and_login_from_checkout_status;
            case 'ship_to_billing_address_only':
                if (self::$ship_to_billing_address_only_feature_status != null)
                    return self::$ship_to_billing_address_only_feature_status;
                self::$ship_to_billing_address_only_feature_status = wc_ship_to_billing_address_only();
                return self::$ship_to_billing_address_only_feature_status;
        }

        return false;
    }

    /**
     * Should Render Coupon
     *
     * Decide if the coupon form should be rendered.
     * The coupon form should be rendered if:
     * 1) The WooCommerce setting is enabled
     * 2) And the Coupon Display toggle hasn't been set to 'no'
     * 3) AND: a payment is needed, OR the Editor is open
     *
     * @since 3.5.0
     *
     * @return boolean
     */
    private function should_render_coupon() {
        $settings = $this->get_settings_for_display();
        $coupon_display_control = true;

        if ( '' === $settings['coupon_switcher'] ) {
            $coupon_display_control = false;
        }

        return ( WC()->cart->needs_payment() || \Elementor\Plugin::$instance->editor->is_edit_mode() ) && wc_coupons_enabled() && $coupon_display_control;
    }

    public function render_woocommerce_checkout_coupon_form() {
        if ( !$this->should_render_coupon() ) return;
        $settings = $this->get_settings_for_display();
        $prefix = 'coupon_';
        $prefix_button = $prefix . 'button_';

        $this->add_render_attribute( $prefix.'form',
            [
                'class' => ['checkout_coupon', 'woocommerce-form-coupon', 'section-content'],
            ]
        );

        if (!!!$settings[$prefix.'opened']) {
            $this->add_render_attribute( $prefix.'form', 'style', 'display: none;');
        }

        $this->add_render_attribute( $prefix.'heading', [
            'class' => ['section-heading', 'woocommerce-form-coupon-toggle']
        ]);

        if ( $settings[$prefix.'align'] ) {
            $this->add_render_attribute( $prefix.'heading', [
                'class' => ['text-'.$settings[$prefix.'align']]
            ]);
            $this->add_render_attribute( $prefix.'form', [
                'class' => ['justify-content-'.str_replace(array('left', 'right'), array('start', 'end'), $settings[$prefix.'align'])]
            ]);
        }

        $this->add_render_attribute(
            $prefix_button, [
                'class' => [ 'woocommerce-button', 'button' ],
                'name' => 'apply_coupon',
                'type' => 'submit',
                'value' => $settings[$prefix_button.'text'] ? $settings[$prefix_button.'text'] : esc_attr__( 'Apply coupon', 'xstore-core' )
            ]
        );

        $this->add_render_attribute( $prefix_button.'text',
            [
                'class' => 'button-text',
            ]
        );
        ?>
        <div class="etheme-elementor-cart-checkout-page-coupon">
            <div <?php $this->print_render_attribute_string( $prefix.'heading' ); ?>>
                <?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', $settings[$prefix.'section_heading'] . ' <a href="#" class="showcoupon">' . $settings[$prefix.'section_heading_link_text'] . '</a>' ), 'notice' ); ?>
            </div>

            <div <?php $this->print_render_attribute_string( $prefix.'form' ); ?>>

                <p class="form-row form-row-first">
                    <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'xstore-core' ); ?></label>
                    <input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'xstore-core' ); ?>" id="coupon_code" value="" />
                </p>

                <p class="form-row form-row-last">
                    <button <?php $this->print_render_attribute_string( $prefix_button ); ?>>
                        <?php $this->render_text($settings, $prefix_button) ?>
                    </button>
                </p>

                <div class="clear"></div>
            </div>
        </div>
        <?php
    }

    /**
     * Should Render Login
     *
     * Decide if the login form should be rendered.
     * The login form should be rendered if:
     * 1) The WooCommerce setting is enabled
     * 2) AND: a logged out user is viewing the page, OR the Editor is open
     *
     * @since 3.5.0
     *
     * @return boolean
     */
    private function should_render_login() {
        $settings = $this->get_settings_for_display();
        $login_form_display_control = true;

        if ( '' === $settings['login_form_switcher'] ) {
            $login_form_display_control = false;
        }
        return $login_form_display_control && ('no' !== get_option( 'woocommerce_enable_checkout_login_reminder' ) && ( ! is_user_logged_in() || \Elementor\Plugin::$instance->editor->is_edit_mode() ));
    }

    /**
     * Render Woocommerce Checkout Login Form
     *
     * A custom function to render a login form on the Checkout widget. The default WC Login form
     * was removed in this file's render() method with:
     * remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form' );
     *
     * And then we are adding this form into the widget at the
     * 'woocommerce_checkout_before_customer_details' hook.
     *
     * We are doing this in order to match the placement of the Login form to the provided design.
     * WC places these forms ABOVE the checkout form section where as we needed to place them inside the
     * checkout form section. So we removed the default login form and added our own form.
     *
     * @since 3.5.0
     */
    private function render_woocommerce_checkout_login_form() {
        if ( !$this->should_render_login() ) return;
        $settings = $this->get_settings_for_display();
        $prefix = 'login_form_';
        $prefix_button = $prefix . 'button_';

        $this->add_render_attribute( $prefix.'form',
            [
                'class' => ['woocommerce-form', 'woocommerce-form-login', 'login', 'section-content'],
            ]
        );


        $this->add_render_attribute( $prefix.'heading', [
            'class' => ['section-heading', 'woocommerce-form-login-toggle']
        ]);

        if (!!!$settings[$prefix.'opened']) {
            $this->add_render_attribute( $prefix.'form', 'style', 'display: none;');
        }

        if ( $settings[$prefix.'align'] ) {
            $this->add_render_attribute( $prefix.'heading', [
                'class' => ['text-'.$settings[$prefix.'align']]
            ]);
            $this->add_render_attribute( $prefix.'form', [
                'class' => [
                    'text-'.$settings[$prefix.'align'],
                    'justify-content-'.str_replace(array('left', 'right'), array('start', 'end'), $settings[$prefix.'align'])
                ]
            ]);
        }

        $this->add_render_attribute(
            $prefix_button, [
                'class' => [ 'woocommerce-button', 'button', 'elementor-button' ],
                'name' => 'login',
                'type' => 'submit',
                'value' => $settings[$prefix_button.'text'] ? $settings[$prefix_button.'text'] : esc_attr__( 'Login', 'xstore-core' )
            ]
        );

        $this->add_render_attribute( $prefix_button.'text',
            [
                'class' => 'button-text',
            ]
        );
        ?>
        <div class="etheme-elementor-cart-checkout-page-login-form">
            <div <?php $this->print_render_attribute_string( $prefix.'heading' ); ?>>
                <?php wc_print_notice( apply_filters( 'woocommerce_checkout_login_message', $settings[$prefix.'section_heading'] . ' <a href="#" class="show_login">' . $settings[$prefix.'section_heading_link_text'] . '</a>' ), 'notice' ); ?>
            </div>
            <div <?php $this->print_render_attribute_string( $prefix.'form' ); ?>>

                <?php do_action( 'woocommerce_login_form_start' ); ?>

                <p class="form-row form-row-first">
                    <label for="username"><?php esc_html_e( 'Username or email', 'xstore-core' ); ?>&nbsp;<span class="required">*</span></label>
                    <input type="text" class="input-text" name="username" id="username" autocomplete="username" />
                </p>
                <p class="form-row form-row-last">
                    <label for="password"><?php esc_html_e( 'Password', 'xstore-core' ); ?>&nbsp;<span class="required">*</span></label>
                    <input class="input-text woocommerce-Input" type="password" name="password" id="password" autocomplete="current-password" />
                </p>

                <?php do_action( 'woocommerce_login_form' ); ?>

                <p class="form-row">
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                        <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'xstore-core' ); ?></span>
                    </label>
                    <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                    <input type="hidden" name="redirect" value="" />
                    <button <?php $this->print_render_attribute_string( $prefix_button ); ?>>
                        <?php $this->render_text($settings, $prefix_button) ?>
                    </button>
                </p>
                <p class="lost_password">
                    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'xstore-core' ); ?></a>
                </p>

                <?php do_action( 'woocommerce_login_form_end' ); ?>

            </div>
        </div>
        <?php
    }



    /**
     * Render button text.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_text($settings, $prefix = '') {

        if ( !$settings[$prefix.'text'] || $settings[$prefix.'icon_align'] == 'left' )
            $this->render_icon( $settings, $prefix );
        ?>

        <span <?php echo $this->get_render_attribute_string( $prefix.'text' ); ?>>
            <?php echo $settings[$prefix.'text']; ?>
        </span>

        <?php
        if ( $settings[$prefix.'icon_align'] == 'right')
            $this->render_icon( $settings, $prefix );
    }

    protected function render_icon($settings, $prefix = '') {
        $migrated = isset( $settings['__fa4_migrated'][$prefix.'selected_icon'] );
        $is_new = empty( $settings[$prefix.'icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
        if ( ! empty( $settings[$prefix.'icon'] ) || ! empty( $settings[$prefix.'selected_icon']['value'] ) ) : ?>
            <?php if ( $is_new || $migrated ) :
                \Elementor\Icons_Manager::render_icon( $settings[$prefix.'selected_icon'], [ 'aria-hidden' => 'true' ] );
            else : ?>
                <i class="<?php echo esc_attr( $settings[$prefix.'icon'] ); ?>" aria-hidden="true"></i>
            <?php endif;
        endif;
    }

    public function get_elements_list() {
        return array(
            'coupon' => esc_html__('Coupon', 'xstore-core'),
            'login_form' => esc_html__('Login Form', 'xstore-core'),
            'billing_details' => esc_html__('Billing details', 'xstore-core'),
            'new_account' => esc_html__('New customer', 'xstore-core'),
            'shipping_details' => esc_html__('Shipping details', 'xstore-core'),
            'additional_information' => esc_html__('Additional Information', 'xstore-core'),
            'shipping_methods' => esc_html__('Shipping methods', 'xstore-core'),
            'payment_methods' => esc_html__('Payment methods', 'xstore-core'),
            'order_review' => esc_html__('Order review', 'xstore-core'),
        );
    }

}
