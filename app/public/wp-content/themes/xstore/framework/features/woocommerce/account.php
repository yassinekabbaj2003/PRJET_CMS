<?php
/**
 * Description
 *
 * @package    account.php
 * @since      8.1.3
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Etheme_WooCoommerce_Account {
    public static $instance = null;

    public static $option_name = 'account';

    public $settings = array(
        'loyalty_program' => array(),
        'tabs' => array(),
    );

    public function init() {
        if ( !class_exists('WooCommerce')) return;
        $this->sub_init('_loyalty_program');
        $this->sub_init('_tabs');
    }

    public function sub_init($prefix = '') {
        if ( !$prefix ) return;
        if ( !get_option('xstore_sales_booster_settings_'.self::$option_name . $prefix ) ) return;

        switch ($prefix) {
            case '_loyalty_program':
                $this->init_loyalty_program();
                break;
            case '_tabs':
                if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' )
                    $this->init_tabs();
                break;
        }
    }

    public function init_loyalty_program() {
        add_action( 'woocommerce_register_form_end', array($this, 'register_benefits'), 10 );
    }

    public function register_benefits() {
        $settings = $this->register_benefits_settings();
        if ( !count($settings['benefits_ready'])) return;
        ?>
        <div class="sales-booster-account-loyalty-program">
            <?php echo !empty($settings['title']) ? '<h4>'.$settings['title'].'</h4>' : ''; ?>
            <ul>
                <?php
                    foreach ($settings['benefits_ready'] as $benefit) {
                        $description = isset($benefit['description']) && !empty($benefit['description']) ? '<span class="mt-mes">'.$benefit['description'].'</span>' : '';
                        $icon = isset($benefit['icon']) && $benefit['icon'] != 'none' ? '<span class="et_b-icon et-icon '.str_replace('et_icon-', 'et-', $benefit['icon']).'"></span>' : '';
                        $title = isset($benefit['title']) && !empty($benefit['title']) ? '<span>'.$benefit['title'] .'</span>' : '';
                        if ( !$icon && !$title) continue;

                        echo '<li'.($description ? ' class="mtips mtips-top"' : '').'>' . $icon . $title . $description . '</li>';
                    }
                ?>
            </ul>
        </div>
        <?php
    }

    public function register_benefits_settings($custom_settings = array()) {
        $postfix = '_loyalty_program';
        $this->settings['loyalty_program'] = array(
            'title' => esc_html__('Sign up today and you will be able to:', 'xstore'),
            'benefits' => '',
            'benefits_ready' => [],
        );

        $local_settings = $this->settings['loyalty_program'];
        $settings = (array)get_option('xstore_sales_booster_settings', array());

        if (count($settings) && isset($settings[self::$option_name . $postfix])) {
            $local_settings = wp_parse_args( $settings[ self::$option_name . $postfix ], $this->settings['loyalty_program'] );
        }

        $benefits = explode(',', $local_settings['benefits']);
        if ( count($benefits) < 1) return $this->settings['loyalty_program'];

        $local_settings['benefits_ready'] = array();

        foreach ($benefits as $benefit) {
            if ( '' == $benefit) continue;
            $steps_benefits_ready = array(
                'icon' => $local_settings[$benefit . '_icon'],
                'title' => $local_settings[$benefit . '_title'],
                'description' => $local_settings[$benefit . '_description'],
            );

            if ( array_filter($steps_benefits_ready ) )
                $local_settings['benefits_ready'][] = $steps_benefits_ready;
        }

        // empty all values
        if ( !array_filter($local_settings['benefits_ready']))
            return $this->settings['loyalty_program'];

        return $local_settings;

    }

    public function init_tabs() {
        $settings = $this->register_tabs_settings();
        add_action('wp_enqueue_scripts', array($this, 'load_assets'));
        add_filter('woocommerce_form_'.($settings['active_tab'] == 'login' ? 'register' : 'login').'_classes', array($this, 'add_classes_hide_form'), 10, 1);
        add_action('etheme_before_customer_login_form', array($this, 'register_login_form_switcher'), 10);
        add_action('etheme_before_customer_register_form', array($this, 'register_register_form_switcher'), 10);
        add_action('woocommerce_login_form_tag', array($this, 'login_form_action'));
        add_action('woocommerce_register_form_tag', array($this, 'register_form_action'));
    }

    public function login_form_action() {
        echo ' action="'.add_query_arg(array('account-active-tab' => 'login'), get_permalink()).'"';
    }

    public function register_form_action() {
        echo ' action="'.add_query_arg(array('account-active-tab' => 'register'), get_permalink()).'"';
    }

    public function register_login_form_switcher() {
        $settings = apply_filters('account_tab_settings', array());
        ?>
        <div class="sales-booster-account-tab<?php echo 'login' == $settings['active_tab'] ? ' hidden' : ''; ?>" data-tab="login">
            <?php if ($settings['login_text'])
                echo '<p>'.$settings['login_text'].'</p>';
            ?>
            <div><span class="woocommerce-Button woocommerce-button button wp-element-button pointer"><?php echo esc_html__('Login', 'xstore'); ?></span></div>
        </div>
        <?php
    }

    public function register_register_form_switcher() {
        $settings = apply_filters('account_tab_settings', array());
        ?>
        <div class="sales-booster-account-tab<?php echo 'register' == $settings['active_tab'] ? ' hidden' : ''; ?>" data-tab="register">
            <?php if ($settings['register_text'])
                echo '<p>'.$settings['register_text'].'</p>';
            ?>
            <div><span class="woocommerce-Button woocommerce-button button wp-element-button pointer"><?php echo esc_html__('Register', 'xstore'); ?></span></div>
        </div>
        <?php
    }

    public function add_classes_hide_form($classes) {
        $classes[] = 'hidden';
        return $classes;
    }

    public function load_assets() {
        if ( !function_exists( 'is_account_page' ) || !is_account_page() ) return;
        wp_add_inline_script( 'etheme', '
			jQuery(document).ready(function($) {
            $(".sales-booster-account-tab .button").on("click", function(){
                let active_tab_wrapper = $(this).parents(".sales-booster-account-tab");
                let active_tab = active_tab_wrapper.data("tab");
                let inactive_tab = active_tab == "login" ? "register" : "login"; 
                active_tab_wrapper.addClass("hidden").next("form").removeClass("hidden");
                $("."+inactive_tab+"-column").find("form").addClass("hidden");
                $(".sales-booster-account-tab[data-tab="+inactive_tab+"]").removeClass("hidden");
            });
		});
		', 'after' );
    }

    public function register_tabs_settings($custom_settings = array()) {
        $postfix = '_tabs';
        $this->settings['tabs'] = array(
            'login_text' => esc_html__('Please log in to your account below in order to continue shopping.', 'xstore'),
            'register_text' => esc_html__('Registering for this site will give you access to your order status and history. Please fill in the fields below and we will quickly set up a new account for you. We will only ask you for information that is necessary to make the purchasing process faster and easier.', 'xstore'),
            'active_tab' => 'login',
        );

        $local_settings = $this->settings['tabs'];
        $settings = (array)get_option('xstore_sales_booster_settings', array());

        if (count($settings) && isset($settings[self::$option_name . $postfix])) {
            $local_settings = wp_parse_args( $settings[ self::$option_name . $postfix ], $this->settings['tabs'] );
        }

        if ( isset($_GET['account-active-tab']) )
            $local_settings['active_tab'] = $_GET['account-active-tab'];

        add_filter('account_tab_settings', function ($options) use ($local_settings) {
            return $local_settings;
        });

        return $local_settings;

    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  5.1.3
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }
}