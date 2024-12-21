<?php
/**
 * Header Sticky feature for Elementor header
 *
 * @package    header-sticky.php
 * @since      5.3.4
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */


namespace ETC\App\Controllers\Elementor\Modules;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use ETC\App\Classes\Elementor;


class Header_Sticky {

    public static $header_classes = [
        'overlap' => 'etheme-elementor-header-overlap',
        'sticky' => 'etheme-elementor-header-sticky'
    ];

    function __construct() {
        add_action( 'elementor/documents/register_controls', [ $this, 'action_register_template_document_header_controls' ] );

//        add_action( 'elementor/documents/register_controls', [ $this, 'action_register_template_document_control' ] );
        add_action( 'elementor/frontend/before_render', array( $this, 'before_section_render' ) );
//        add_filter('elementor/document/wrapper_attributes', array($this, 'wrapper_attributes'), 10, 2);

        add_action( 'elementor/element/container/section_effects/before_section_start', array( $this, 'action_register_template_control' ) );
    }

    public function action_register_template_document_header_controls($document) {
        if ( $document instanceof \ElementorPro\Modules\ThemeBuilder\Documents\Header ) {
            $document->start_controls_section(
                '_section_etheme_header_overlap',
                [
                    'label' => sprintf(__( '%s Overlap Header', 'xstore-core' ), apply_filters('etheme_theme_label', 'XSTORE')),
                    'tab' => Controls_Manager::TAB_SETTINGS,
                ]
            );

            $document->add_control(
                'etheme_header_overlap_description',
                [
                    'raw' => '<div class="elementor-panel-alert elementor-panel-alert-info">' . sprintf(__( 'To use the header overlap feature, please navigate to the Container settings in the Advanced section -> %s Header Overlap. %s', 'xstore-core' ), apply_filters('etheme_theme_label', 'XStore'),
                            '<a href="https://prnt.sc/o7v1sM8v4jgu" target="_blank">'.esc_html__('See screenshot', 'xstore-core').'</a>') . '</div>',
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                ]
            );

            $document->end_controls_section();

            $document->start_controls_section(
                '_section_etheme_header_sticky',
                [
                    'label' => sprintf(__( '%s Sticky Header', 'xstore-core' ), apply_filters('etheme_theme_label', 'XSTORE')),
                    'tab' => Controls_Manager::TAB_SETTINGS,
                ]
            );

            $document->add_control(
                'etheme_header_sticky_description',
                [
                    'raw' => '<div class="elementor-panel-alert elementor-panel-alert-info">' . sprintf(__( 'To use the header sticky feature, please navigate to the Container settings in the Advanced section -> %s Sticky Header. %s', 'xstore-core' ), apply_filters('etheme_theme_label', 'XStore'),
                            '<a href="https://prnt.sc/sH2_LYDAcpro" target="_blank">'.esc_html__('See screenshot', 'xstore-core').'</a>') . '</div>',
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                ]
            );

            $document->end_controls_section();
        }
    }
    public function action_register_template_document_control( $document ) {
        if ( $document instanceof \ElementorPro\Modules\ThemeBuilder\Documents\Header ) {
            $this->register_header_sticky_controls($document, true);
        }
    }

    public function action_register_template_control($document) {
        // keep these options added for containers in Header editor only and not Editor (for correct saving styles in CSS files)
        if ( !\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->documents->get_current() instanceof \ElementorPro\Modules\ThemeBuilder\Documents\Header) {
            $this->register_header_sticky_controls($document, false);
        }

    }
    public function register_header_sticky_controls($document, $origin_document = false) {

        $breakpoints_list = Elementor::get_breakpoints_list();

        $document->start_controls_section(
            '_section_etheme_header_overlap',
            [
                'label' => sprintf(__( '%s Transparent / Overlap Header', 'xstore-core' ), apply_filters('etheme_theme_label', 'XSTORE')),
                'tab' => $origin_document ? Controls_Manager::TAB_SETTINGS : Controls_Manager::TAB_ADVANCED,
            ]
        );

        if ( !$origin_document ) {
            $document->add_control(
                'etheme_header_overlap_description',
                [
                    'raw' => sprintf(__( 'Works correctly for live mode only, not for the editor mode. Please, watch the %stutorial%s to set the Transparent Header correctly', 'xstore-core' ), '<a href="https://youtu.be/n0PGQDm0I2o" target="_blank">', '</a>'),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                ]
            );
        }

        $document->add_control(
            'etheme_header_overlap',
            [
                'label'     => __( 'Enable Header Overlap', 'xstore-core' ),
                'type'      => Controls_Manager::SWITCHER,
                'render_type' => 'template',
            ]
        );

        $document->add_control(
            'etheme_header_overlap_on',
            [
                'label'    => __( 'Overlap On Devices', 'xstore-core' ),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => true,
                'default' => array_key_exists('desktop', $breakpoints_list) ? ['desktop'] : [],
                'frontend_available' => true,
                'render_type' => 'template',
                'options' => $breakpoints_list,
                'condition' => [
                    'etheme_header_overlap!' => '',
                ]
            ]
        );

        if ( $origin_document ) {
            $document->add_control(
                'apply_etheme_header_overlap',
                [
                    'type' => Controls_Manager::BUTTON,
                    'label_block' => true,
                    'show_label' => false,
                    'button_type' => 'default elementor-button-center',
                    'text' => esc_html__('Apply', 'xstore-core'),
                    'separator' => 'none',
                    'event' => 'elementorThemeBuilder:ApplyPreview',
//                    'condition' => [
//                        'etheme_header_overlap!' => '',
//                    ]
                ]
            );
        }

        $document->end_controls_section();

        $document->start_controls_section(
            '_section_etheme_header_sticky',
            [
                'label' => sprintf(__( '%s Sticky Header', 'xstore-core' ), apply_filters('etheme_theme_label', 'XSTORE')),
                'tab' => $origin_document ? Controls_Manager::TAB_SETTINGS : Controls_Manager::TAB_ADVANCED,
            ]
        );

        if ( !$origin_document ) {
            $document->add_control(
                'etheme_header_sticky_description',
                [
                    'raw' => __( 'Works correctly for live mode only, not for the editor mode', 'xstore-core' ),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                ]
            );
        }

        $document->add_control(
            'etheme_header_sticky',
            [
                'label'     => __( 'Enable Sticky Header', 'xstore-core' ),
                'type'      => Controls_Manager::SWITCHER,
                'frontend_available' => true,
                'render_type' => 'template',
                'return_value' => 'sticky',
                'prefix_class' => str_replace('sticky', '', self::$header_classes['sticky'])
            ]
        );

//        $document->add_control(
//			'arrows_responsive_description',
//			[
//				'raw' => '<div class="elementor-update-preview">
//                        <div class="elementor-update-preview-title">'.esc_html__( 'Update changes to page', 'xstore-core' ).'</div>
//                        <div class="elementor-update-preview-button-wrapper">
//                            <button class="elementor-update-preview-button elementor-button">'.esc_html__( 'Apply', 'xstore-core' ).'</button>
//                        </div>
//                </div>',
//				'type' => \Elementor\Controls_Manager::RAW_HTML,
//				'content_classes' => 'elementor-descriptor',
//			]
//		);

//        if ( $origin_document ) {
            $document->add_control(
                'etheme_header_sticky_type',
                [
                    'label' => __('Type', 'xstore-core'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'stacked',
                    'frontend_available' => true,
                    'options' => array(
                        'custom' => esc_html__('Custom', 'xstore-core'),
                        'stacked' => esc_html__('Stacked', 'xstore-core'),
                        'smart' => esc_html__('Smart', 'xstore-core')
                    ),
                    'condition' => [
                        'etheme_header_sticky!' => '',
                    ]
                ]
            );

            $document->add_responsive_control(
                'etheme_header_sticky_offset',
                [
                    'label' => __('Custom Scroll Distance (px)', 'xstore-core'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                        ],
                    ],
                    'frontend_available' => true,
                    'size_units' => ['px'],
                    'condition' => [
                        'etheme_header_sticky!' => '',
                        'etheme_header_sticky_type' => 'custom'
                    ],
                ]
            );

            $document->add_control(
                'etheme_header_sticky_animation',
                [
                    'label' => esc_html__('Entrance Animation', 'xstore-core'),
                    'type' => Controls_Manager::ANIMATION,
                    'frontend_available' => true,
                    'condition' => [
                        'etheme_header_sticky!' => '',
                        'etheme_header_sticky_type' => 'custom'
                    ],
                ]
            );

            $document->add_control(
                'etheme_header_sticky_animation_duration',
                [
                    'label' => esc_html__('Animation Duration', 'xstore-core'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'fast',
                    'options' => [
                        'slow' => esc_html__('Slow', 'xstore-core'),
                        '' => esc_html__('Normal', 'xstore-core'),
                        'fast' => esc_html__('Fast', 'xstore-core'),
                    ],
//                'prefix_class' => 'animated-',
                    'frontend_available' => true,
                    'condition' => [
                        'etheme_header_sticky!' => '',
                        'etheme_header_sticky_type' => 'custom',
                        'etheme_header_sticky_animation!' => '',
                    ],
                ]
            );
//        }

        $document->add_control(
            'etheme_header_sticky_on',
            [
                'label'    => __( 'Sticky On Devices', 'xstore-core' ),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => true,
                'default' => array_keys($breakpoints_list),
                'frontend_available' => true,
                'options' => $breakpoints_list,
                'condition' => [
                    'etheme_header_sticky!' => '',
                ]
            ]
        );

//        $document->start_controls_tabs( 'tabs_etheme_header_sticky_background' );
//
//        /**
//         * Normal.
//         */
//        $document->start_controls_tab(
//            'tab_etheme_header_sticky_background_normal',
//            [
//                'label' => esc_html__( 'Normal', 'xstore-core' ),
//            ]
//        );

        $document->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'etheme_header_sticky_background',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '.sticky-on .etheme-elementor-header-wrapper > .elementor-element.elementor-element-{{ID}}',
                'condition' => [
                    'etheme_header_sticky!' => '',
                ]
            ]
        );

        $document->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'etheme_header_sticky_border',
                'selector' => '.sticky-on .etheme-elementor-header-wrapper > .elementor-element.elementor-element-{{ID}}',
                'condition' => [
                    'etheme_header_sticky!' => '',
                ]
            ]
        );

        $document->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'etheme_header_sticky_box_shadow',
                'selector' => '.sticky-on .etheme-elementor-header-wrapper > .elementor-element.elementor-element-{{ID}}',
                'condition' => [
                    'etheme_header_sticky!' => '',
                ],
                'fields_options' => [
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 2,
                            'vertical' => 0,
                            'blur' => 12,
                            'spread' => 0,
                            'color' => 'rgba(0,0,0,0.15)',
                        ],
                    ],
                ],
            ]
        );

        $document->add_control(
            'etheme_header_sticky_backdrop_filter',
            [
                'label'    => __( 'Backdrop Filter', 'xstore-core' ),
                'type'     => Controls_Manager::SELECT,
                'description' => esc_html__('For optimal performance of these filters, we recommend setting the background of the container with opacity using an RGBA color value.', 'xstore-core'),
                'default' => '',
                'options' => array(
                    '' => esc_html__('None', 'xstore-core'),
                    'blur' => esc_html__('Blur', 'xstore-core'),
                    'grayscale' => esc_html__('Grayscale', 'xstore-core'),
                    'saturate' => esc_html__('Saturate', 'xstore-core'),
                ),
                'condition' => [
                    'etheme_header_sticky!' => '',
                ]
            ]
        );

        $document->add_control(
            'etheme_header_sticky_backdrop_filter_value',
            [
                'label' => __('Filter value', 'xstore-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1
                    ],
                ],
                'size_units' => ['%'],
                'selectors' => [
                    '.sticky-on .etheme-elementor-header-wrapper > .elementor-element.elementor-element-{{ID}}' => 'backdrop-filter: {{etheme_header_sticky_backdrop_filter.VALUE}}({{SIZE}}); -webkit-backdrop-filter: {{etheme_header_sticky_backdrop_filter.VALUE}}({{SIZE}});'
                ],
                'condition' => [
                    'etheme_header_sticky!' => '',
                    'etheme_header_sticky_backdrop_filter' => ['grayscale', 'saturate']
                ],
            ]
        );

        $document->add_control(
            'etheme_header_sticky_backdrop_filter_value_units',
            [
                'label' => __('Filter value (px)', 'xstore-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'size' => 5,
                    'unit' => 'px'
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '.sticky-on .etheme-elementor-header-wrapper > .elementor-element.elementor-element-{{ID}}' => 'backdrop-filter: {{etheme_header_sticky_backdrop_filter.VALUE}}({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: {{etheme_header_sticky_backdrop_filter.VALUE}}({{SIZE}}{{UNIT}});'
                ],
                'condition' => [
                    'etheme_header_sticky!' => '',
                    'etheme_header_sticky_backdrop_filter' => ['blur']
                ],
            ]
        );

//        $document->end_controls_tab();

//        /**
//         * Hover.
//         */
//        $document->start_controls_tab(
//            'tab_etheme_header_sticky_background_hover',
//            [
//                'label' => esc_html__( 'Hover', 'xstore-core' ),
//            ]
//        );
//
//        $document->add_group_control(
//            \Elementor\Group_Control_Background::get_type(),
//            [
//                'name' => 'etheme_header_sticky_background_hover',
//                'selector' => '{{WRAPPER}}:hover',
//            ]
//        );
//
//        $document->add_control(
//            'etheme_header_sticky_background_hover_transition',
//            [
//                'label' => esc_html__( 'Transition Duration', 'xstore-core' ) . ' (s)',
//                'type' => Controls_Manager::SLIDER,
//                'default' => [
//                    'size' => 0.3,
//                ],
//                'range' => [
//                    'px' => [
//                        'min' => 0,
//                        'max' => 3,
//                        'step' => 0.1,
//                    ],
//                ],
//                'render_type' => 'ui',
//                'separator' => 'before',
//                'selectors' => [
//                    '{{WRAPPER}}' => '--background-transition: {{SIZE}}s;',
//                ],
//            ]
//        );
//
//        $document->end_controls_tab();
//
//        $document->end_controls_tabs();

        if ( $origin_document ) {
            $document->add_control(
                'apply_etheme_header_sticky',
                [
                    'type' => Controls_Manager::BUTTON,
                    'label_block' => true,
                    'show_label' => false,
                    'button_type' => 'default elementor-button-center',
                    'text' => esc_html__('Apply', 'xstore-core'),
                    'separator' => 'none',
                    'event' => 'elementorThemeBuilder:ApplyPreview',
//                    'condition' => [
//                        'etheme_header_sticky!' => '',
//                    ]
                ]
            );
        }

        $document->end_controls_section();
    }

    public static function before_section_render( $element ) {

        $header_settings = $element->get_settings_for_display();

        $is_overlay = isset($header_settings['etheme_header_overlap']) && !!$header_settings['etheme_header_overlap'];
        $is_sticky = isset($header_settings['etheme_header_sticky']) && !!$header_settings['etheme_header_sticky'];
        if ( !$is_overlay && !$is_sticky ) return;

        wp_enqueue_style('etheme-elementor-header-sticky'); // works always
        if ( $is_sticky && isset($header_settings['etheme_header_sticky_animation']) && !!$header_settings['etheme_header_sticky_animation']) {
            wp_enqueue_style('e-animations');
        }

        if ( $is_overlay ) {
            $element->add_render_attribute(
                '_wrapper', [
                    'class' => self::$header_classes['overlap'],
                ]
            );
            foreach ($header_settings['etheme_header_overlap_on'] as $overlap_on_device) {
                $element->add_render_attribute(
                    '_wrapper', 'class', self::$header_classes['overlap'].'-'.$overlap_on_device
                );
            }
        }

        if ( $is_sticky ) {
            $element->add_render_attribute(
                '_wrapper', 'class', self::$header_classes['sticky']
            );
            wp_enqueue_script('etheme_elementor_header_sticky'); // works always
        }
    }

    public function wrapper_attributes($attributes, $_this) {
        if ( $_this->get_name() !== 'header' ) return $attributes;
        $header_settings = $_this->get_settings_for_display();
        $is_overlay = $header_settings['etheme_header_overlap'];
        $is_sticky = $header_settings['etheme_header_sticky'];

        $custom_classes = array();
        if ( !!$is_overlay )
            $custom_classes[] = self::$header_classes['overlap'];

        if ( !!$is_sticky ) {
            $custom_classes[] = self::$header_classes['sticky'];
            wp_enqueue_script('etheme_elementor_header_sticky'); // works always
        }

        if ( count($custom_classes) ) {
            $attributes['class'] .= ' ' . implode(' ', $custom_classes);
            wp_enqueue_style('etheme-elementor-header-sticky'); // works always
        }

        return $attributes;
    }

}