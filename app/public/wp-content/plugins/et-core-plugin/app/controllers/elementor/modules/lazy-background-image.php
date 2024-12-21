<?php
/**
 * Lazy Background Image feature for Elementor background settings
 *
 * @package    lazy-background-image.php
 * @since      5.1.3
 * @version    1.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */


namespace ETC\App\Controllers\Elementor\Modules;

use Elementor\Plugin;
use Elementor\Controls_Manager;


class Lazy_Background_Image {

    const KEY = 'etheme_lazy_bg_image';

    const CSS_CLASS = 'etheme-elementor-lazyBg';

    public $should_add_script = false;

    public $js_added = false;

    function __construct() {

        add_action( 'elementor/element/section/section_background/before_section_end', array($this, 'register_controls'));
        add_action( 'elementor/frontend/section/before_render', array($this, 'before_render'));
        add_action( 'elementor/element/container/section_background/before_section_end', array($this, 'register_controls'));
        add_action( 'elementor/frontend/container/before_render', array($this, 'before_render'));
        add_action( 'elementor/element/column/section_style/before_section_end', array($this, 'register_controls'));
        add_action( 'elementor/frontend/column/before_render', array($this, 'before_render'));
        add_action( 'elementor/element/common/_section_background/before_section_end', [$this, 'register_common_controls']);
        add_action( 'elementor/frontend/widget/before_render', array($this, 'before_render'));

        add_action('wp_footer', array($this, 'add_inline_script'));

    }

    /**
     * @param $element
     * @param string $selector
     */
    private function _register_controls($element, $selector = 'background_background') {
        $element->add_control( self::KEY, array(
            'label' => esc_html__( 'Lazy load Background Image', 'xstore-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'condition' => array(
                $selector => 'classic',
            ),
        ) );
    }
    /**
     * Add custom settings into Elementor's Layout elements
     *
     * @since 1.0.0
     */
    public function register_controls( $element )
    {
        $this->_register_controls($element);
    }

    public function register_common_controls($element) {
        $this->_register_controls($element, '_background_background');
    }

    /**
     * Render before
     *
     * @since 1.0.0
     **/
    public function before_render( $element )
    {

//        $bg_key = ('widget' === $element->get_type() ? '_' : '') . 'background_background';

        // Globally enabled
//        if( $this->enabled_globally() ){
//
//            if( 'classic' !== $element->get_settings($bg_key) ){
//                return;
//            }
//
//            $element->add_render_attribute( '_wrapper', 'class', self::CSS_CLASS );
//
//            return;
//        }

        // Element based
        $settings = $element->get_settings();

        if( ! isset($settings[self::KEY]) || $settings[self::KEY] === '')
            return;

        $bg_key = ('widget' === $element->get_type() ? '_' : '') . 'background_background';

        if( 'classic' !== $settings[$bg_key] )
            return;

        $this->should_add_script = true;

        $element->add_render_attribute( '_wrapper', 'class', self::CSS_CLASS );

        $element->add_script_depends( 'etheme_elementor_lazy_background' );
        wp_enqueue_script('etheme_elementor_lazy_background'); // works always

    }

    /**
     * Add script to wp_footer to remove lazy class on scroll close to element
     */
    public function add_inline_script() {
        if ( !apply_filters('etheme_lazy_load_elementor_bg_script', $this->should_add_script) ) return;
        echo '<script data-dont-merge>
            document.addEventListener("DOMContentLoaded", function () {
                var e;
                if ("IntersectionObserver" in window) {
                    e = document.querySelectorAll(".'.self::CSS_CLASS.'");
                    var n = new IntersectionObserver(function (e, t) {
                        e.forEach(function (e) {
                            if (e.isIntersecting) {
                                var t = e.target;
                                t.classList.remove("'.self::CSS_CLASS.'"), n.unobserve(t);
                            }
                        });
                    });
                    e.forEach(function (e) {
                        n.observe(e);
                    });
                } else {
                    var t;
                    function r() {
                        t && clearTimeout(t),
                            (t = setTimeout(function () {
                                var n = window.pageYOffset;
                                e.forEach(function (e) {
                                    e.offsetTop < window.innerHeight + n && ((e.src = e.dataset.src), e.classList.remove("'.self::CSS_CLASS.'"));
                                }),
                                    0 == e.length && (document.removeEventListener("scroll", r), window.removeEventListener("resize", r), window.removeEventListener("orientationChange", r));
                            }, 20));
                    }
                    (e = document.querySelectorAll(".'.self::CSS_CLASS.'")), document.addEventListener("scroll", r), window.addEventListener("resize", r), window.addEventListener("orientationChange", r);
                }
            });
        </script>';
    }

}