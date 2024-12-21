<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class XStore_GRPD {

    public static $instance = null;

    public static $is_customizer = false;

    public static $enable_cookies_popup = false;

    public function init() {
        $this->cookies_popup();
    }

    public function cookies_popup() {
        add_filter('etheme_et_js_config', function ($config) {
            $config['etCookies'] = array(
                'cache_time' => get_theme_mod('et_cookies_notice_cache', 3),
            );
            return $config;
        });
        add_action('after_page_wrapper', array($this, 'cookies_popup_content'));
    }
    public function cookies_popup_content() {
        self::$is_customizer = is_customize_preview();
        self::$enable_cookies_popup = get_theme_mod('et_cookies_notice_switcher', 0);

        // html_blocks_callback functions is located in XStore Core plugin
        if ( ! defined( 'ET_CORE_DIR' ) ) {
            return;
        }

        if ( !self::$is_customizer ) {
            if ( !self::$enable_cookies_popup) return;
            if ( isset( $_COOKIE['etheme_cookies'] ) && $_COOKIE['etheme_cookies'] == 'false' ) return;
        }

        $position = get_theme_mod('et_cookies_notice_position', 'left_bottom');
        $class = array(
            'pos-fixed',
            'hidden'
        );
        if ( !get_theme_mod('et_cookies_notice_visibility_et-desktop', true)) {
            $class[] = 'dt-hide';
        }
        if ( !get_theme_mod('et_cookies_notice_visibility_et-mobile', true)) {
            $class[] = 'mob-hide';
        }
        switch ($position) {
            case 'left_bottom':
                $class[] = 'bottom';
                $class[] = 'left';
                break;
            case 'right_bottom':
                $class[] = 'bottom';
                $class[] = 'right';
                break;
            case 'full_bottom':
                $class[] = 'bottom';
                $class[] = 'right';
                $class[] = 'left';
                break;
        }
        $content_class = array();
        if ( $position != 'full_bottom' ) {
            $content_class[] = 'align-' . get_theme_mod('et_cookies_notice_content_content_alignment', 'center');
            if (get_theme_mod('et_cookies_notice_content_content_width_height', 'auto') == 'custom')
                $class[] = 'cookies-content-custom-dimensions';
        }
        $button_text = get_theme_mod('et_cookies_notice_content_button_text', esc_html__('Ok, I am ready', 'xstore'));
        $details_page = get_theme_mod('et_cookies_notice_details_page', '');
        $details_page_link = $details_page ? get_permalink( $details_page ) : '';
        ?>
        <div class="et-cookies-popup-wrapper <?php echo implode(' ', $class); ?>"<?php echo ( self::$is_customizer && !self::$enable_cookies_popup ) ? ' style="display: none;"' : ''; ?>>
            <span class="close pos-absolute right top">
                <svg xmlns="http://www.w3.org/2000/svg" width="0.55em" height="0.55em" viewBox="0 0 24 24"
                     fill="currentColor">
                    <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                </svg>
            </span>
            <div class="cookies-content <?php echo implode(' ', $content_class); ?>">
                <?php
                    echo html_blocks_callback(array(
                        'section' => 'et_cookies_notice_content_section',
                        'sections' => 'et_cookies_notice_content_sections',
                        'html_backup' => 'et_cookies_notice_content',
                        'html_backup_default' => sprintf(esc_html__('This website uses cookies to improve your experience. %s By using this website you agree to our %s.', 'xstore'), '<br/>', '<a href="#">'.esc_html__('Privacy Policy', 'xstore').'</a>'),
                        'section_content' => true
                    ));
                ?>
            </div>
            <?php
            if ( !empty($button_text) || $details_page_link ) {
                echo '<div class="text-center">';
                if ($details_page_link)
                    echo '<a href="' . $details_page_link . '" target="_blank" class="cookies-details">' . esc_html__('More details', 'xstore') . '</a>';
                if ( !empty($button_text) )
                    echo '<span class="cookies-button pointer black btn">' . $button_text . '</span>';
                echo '</div>';
            }
            ?>
        </div>
        <?php
    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  1.0.0
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }
}

$gdpr = new XStore_GRPD();
$gdpr->init();