<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Panel Dashboard.
 *
 * Add admin panel dashboard pages to admin menu.
 * Output dashboard pages.
 *
 * @since   5.0.0
 * @version 1.0.8
 */

class EthemeAdmin{
    /**
     * Theme name
     *
     * @var string
     */
    protected $theme_name;

    /**
     * Panel page
     *
     * @var array
     */
    protected $page = array();

    protected $settingJsConfig = array();

    protected static $instance = null;

    // ! Main construct/ add actions
    public function main_construct(){
        add_action( 'admin_menu', array( $this, 'et_add_menu_page' ) );
        add_action( 'admin_head', array( $this, 'et_add_menu_page_target') );
        add_action( 'wp_ajax_et_ajax_panel_popup', array($this, 'et_ajax_panel_popup') );

        // Enable svg support
        add_filter( 'upload_mimes', [ $this, 'add_svg_support' ] );
        add_filter( 'wp_check_filetype_and_ext', array( $this, 'correct_svg_filetype' ), 10, 5 );

        if ( isset($_REQUEST['helper']) && $_REQUEST['helper']){
            $this->require_class($_REQUEST['helper']);
        }

        add_action( 'wp_ajax_et_panel_ajax', array($this, 'et_panel_ajax') );

        add_action('wp_ajax_et_close_installation_video', array($this, 'et_close_installation_video'));

        $current_theme         = wp_get_theme();
        $this->theme_name      = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );

        add_action( 'admin_init', array( $this, 'admin_redirects' ), 30 );
        add_action('admin_init',array($this,'add_page_admin_script'), 1140);
        add_filter('admin_body_class', array($this, 'admin_body_class'));

        if(!is_child_theme()){
            add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
        }

        if ( ! $this->set_page_data() ){
            return;
        }

        if (isset($this->page['class']) && ! empty($this->page['class'])){
            $this->require_class($this->page['class']);
        }

        // Stas
        $this->init_vars();
    }

    public static function add_svg_support( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        $mimes['json'] = 'application/json';
        return $mimes;
    }

    /**
     * Correct SVG file uploads to make them pass the WP check.
     *
     * WP upload validation relies on the fileinfo PHP extension, which causes inconsistencies.
     * E.g. json file type is application/json but is reported as text/plain.
     * ref: https://core.trac.wordpress.org/ticket/45633
     *
     * @access public
     * @since 4.3.4
     * @param array       $data                      Values for the extension, mime type, and corrected filename.
     * @param string      $file                      Full path to the file.
     * @param string      $filename                  The name of the file (may differ from $file due to
     *                                               $file being in a tmp directory).
     * @param string[]    $mimes                     Array of mime types keyed by their file extension regex.
     * @param string|bool $real_mime                 The actual mime type or false if the type cannot be determined.
     *
     * @return array
     */
    public function correct_svg_filetype( $data, $file, $filename, $mimes, $real_mime = false ) {

        // If both ext and type are.
        if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
            return $data;
        }

        $wp_file_type = wp_check_filetype( $filename, $mimes );

        if ( 'svg' === $wp_file_type['ext'] ) {
            $data['ext']  = 'svg';
            $data['type'] = 'image/svg+xml';
        }

        return $data;
    }

    public function init_vars() {
        $this->settingJsConfig = array(
            'ajaxurl'          => admin_url( 'admin-ajax.php' ),
            'resetOptions'     => __( 'All your settings will be reset to default values. Are you sure you want to do this ?', 'xstore' ),
            'pasteYourOptions' => __( 'Please, paste your options there.', 'xstore' ),
            'loadingOptions'   => __( 'Loading options', 'xstore' ) . '...',
            'ajaxError'        => __( 'Ajax error', 'xstore' ),
            'nonce'      => wp_create_nonce( 'xstore_panel_saving_nonce' ),
            'audioPlaceholder' => ETHEME_BASE_URI.'framework/panel/images/audio.png',
        );
        return $this->settingJsConfig;
    }

    public static function get_instance() {
        if ( ! self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function admin_body_class($class) {
        global $pagenow;

        $screen    = get_current_screen();
        $screen_id = $screen ? $screen->id : '';
        if ( strpos($screen_id, 'et-panel') ) {
            $class .= ' et-'.get_option('et_panel_dark_light_default', 'light').'-mode';
        }
        return $class;
    }

    /**
     * enqueue scripts for current panel page
     *
     * @version  1.0.2
     * @since  7.0.0
     */
    public function add_page_admin_script(){
        wp_register_script('etheme_panel_global',ETHEME_BASE_URI.'framework/panel/js/global.min.js', array('jquery','etheme_admin_js'), false,true);
        wp_localize_script( 'etheme_panel_global', 'XStorePanelConfig', array(
            'messages' => array(
                'register_licence' => esc_html__('Oops... There was an issue updating your theme. We could not validate your purchase code. Please register the theme and try again, or contact our support forum for more information.', 'xstore'),
            )
        ));
        if ( isset($this->page['script']) && ! empty($this->page['script']) ){
            wp_register_script('etheme_panel_'.$this->page['script'],ETHEME_BASE_URI.'framework/panel/js/'.$this->page['script'].'.js', array('jquery','etheme_admin_js'), false,true);
            // prevent loading this JS file and load it only in case it requires from the other files
            if ( !in_array($this->page['script'], array('system_requirements.min')))
                wp_enqueue_script('etheme_panel_'.$this->page['script']);
            switch ($this->page['script']) {
                case 'patcher.min':
                    wp_localize_script( 'etheme_panel_'.$this->page['script'], 'XStorePanelPatcherConfig', array(
                        'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                        'success' => esc_html__('Successfully applied', 'xstore'),
                        'applied_btn' => '<span class="patch-unavailable success">'.
                            '<svg width="1em" height="1em" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.5 0C2.01911 0 0 2.01911 0 4.5C0 6.98089 2.01911 9 4.5 9C6.98089 9 9 6.98089 9 4.5C9 2.01911 6.98089 0 4.5 0ZM4.5 8.2666C2.41751 8.2666 0.7334 6.5825 0.7334 4.5C0.7334 2.41751 2.41751 0.7334 4.5 0.7334C6.5825 0.7334 8.2666 2.41751 8.2666 4.5C8.2666 6.5825 6.5825 8.2666 4.5 8.2666ZM6.80885 2.85211C6.70926 2.84306 6.6006 2.87928 6.52817 2.95171L3.85714 5.54125L2.47183 4.11972C2.3994 4.04728 2.2998 4.01107 2.19115 4.01107C2.0825 4.01107 1.9829 4.05634 1.92857 4.14688C1.86519 4.22837 1.82897 4.33702 1.83803 4.43662C1.84708 4.51811 1.8833 4.5996 1.94668 4.64487L3.58551 6.33803C3.65795 6.41046 3.74849 6.44668 3.84809 6.44668C3.93863 6.44668 4.02918 6.41046 4.10161 6.33803L7.02616 3.48592C7.09859 3.41348 7.13481 3.31388 7.13481 3.20523C7.13481 3.11469 7.09859 3.02414 7.03521 2.96982C6.98089 2.89738 6.8994 2.86117 6.80885 2.85211Z" fill="currentColor"/>
                                    </svg>' . esc_html__('Applied', 'xstore').'</span>',
                        'error_btn' => '<span class="patch-unavailable error">'.
                            '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 9 9">
                            <path d="M4.5 0.009c-2.475 0-4.491 2.016-4.491 4.491s2.016 4.491 4.491 4.491 4.491-2.016 4.491-4.491-2.016-4.491-4.491-4.491zM4.5 8.271c-2.079 0-3.771-1.692-3.771-3.771s1.692-3.771 3.771-3.771 3.771 1.692 3.771 3.771-1.692 3.771-3.771 3.771zM4.59 3.492h-0.18c-0.18 0-0.315 0.099-0.315 0.234v3.294c0 0.126 0.135 0.234 0.315 0.234h0.18c0.18 0 0.315-0.099 0.315-0.234v-3.294c0-0.135-0.135-0.234-0.315-0.234zM4.59 1.737h-0.18c-0.171 0-0.315 0.144-0.315 0.315v0.54c0 0.171 0.144 0.315 0.315 0.315h0.18c0.171 0 0.315-0.144 0.315-0.315v-0.54c0-0.171-0.144-0.315-0.315-0.315z" fill="currentColor"></path>
                        </svg>' . esc_html__('Error', 'xstore').'</span>',
                        'apply_all' => esc_html__('Before proceeding, please confirm that you wish to apply all patches.', 'xstore'),
                        'backup_info' => esc_html__('We recommend that you make backups of your website before making any changes.', 'xstore'),
                        'question' => esc_html__('Before proceeding, please confirm that you wish to apply this patch.', 'xstore'),
                        'test_mode' => isset($_GET['xstore-patches-test-mode']),
                        'nonce'      => wp_create_nonce( 'xstore_patches_apply_nonce' ),
                        'file_will_modify' => esc_html__('Please, note that the following file will be modified: {{file}}', 'xstore'),
                        'files_will_modify' => esc_html__('Please, note that the following files will be modified: {{files}}', 'xstore')
                    ) );
                    break;
                case 'welcome.min':
                    wp_register_script(
                        'slick_carousel',
                        get_template_directory_uri() . '/js/libs/slick.min.js',
                        array('jquery')
                    );
                    wp_register_style( 'slick-carousel', get_template_directory_uri() . '/css/libs/slick.css' );
                    break;
                case 'demos.min':
                case 'plugins.min':
                case 'sales_booster.min':
                    wp_enqueue_script( 'jquery_lazyload');
                    break;
            }
        }

        if (
            isset($this->page['template'])
            && ! empty($this->page['template'])
        ){
            if ( etheme_is_activated() && get_option('et_documentation_beacon', false) !== 'off')
                wp_enqueue_script('etheme_panel_documentation',ETHEME_BASE_URI.'framework/panel/js/documentation.min.js', array('jquery','etheme_admin_js'), false,true);
            if ( in_array($this->page['template'], array('changelog', 'theme-builders')) )
                wp_enqueue_script('etheme_panel_global');
        }

        if (did_action('etheme_panel_page_has_options') ) {
            wp_enqueue_script('etheme_panel_global');
            wp_enqueue_script( 'xstore_panel_settings_admin_js', ETHEME_BASE_URI.'framework/panel/js/settings/save_action.min.js' );

            wp_localize_script( 'xstore_panel_settings_admin_js', 'XStorePanelSettingsConfig', $this->settingJsConfig );
        }
    }

    // deprecated since v9.4 but need to be to prevent fatal errors in XStore AMO/White label branding
    public function add_page_admin_settings_scripts() {

//        wp_enqueue_script( 'xstore_panel_settings_admin_js', ETHEME_BASE_URI.'framework/panel/js/settings/save_action.min.js', array('wp-color-picker') );
//
//        wp_localize_script( 'xstore_panel_settings_admin_js', 'XStorePanelSettingsConfig', $this->settingJsConfig );
    }

    // deprecated since v9.4 but need to be to prevent fatal errors in XStore AMO/White label branding
    public function add_page_admin_settings_xstore_icons() {
    }

    public function xstore_panel_icons_fonts_enqueue() {
        $dir_uri = get_template_directory_uri();
        $icons_type = ( etheme_get_option('bold_icons', 0) ) ? 'bold' : 'light';
        wp_register_style( 'xstore-icons-font', false );
        wp_enqueue_style( 'xstore-icons-font' );
        wp_add_inline_style( 'xstore-icons-font',
            "@font-face {
		  font-family: 'xstore-icons';
		  src:
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".ttf') format('truetype'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".woff2') format('woff2'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".woff') format('woff'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".svg#xstore-icons') format('svg');
		  font-weight: normal;
		  font-style: normal;
		  font-display: swap;
		}"
        );
        wp_enqueue_style( 'xstore-icons-font-style', $dir_uri . '/css/xstore-icons.css' );
    }

    /**
     * Set panel page data
     *
     * @version  1.0.2
     * @since  7.0.0
     * @log added sales_booster actions
     */
    public function set_page_data(){
        if (! isset($_REQUEST['page'])){
            return false;
        }
        switch ( $_REQUEST['page'] ) {
            case 'et-panel-system-requirements':
                $this->page['template'] = 'system-requirements';
                $this->page['script'] = 'system_requirements.min';
                break;
            case 'et-panel-changelog':
                $this->page['template'] = 'changelog';
                break;
            case 'et-panel-support':
                $this->page['template'] = 'support';
                $this->page['class'] = 'youtube';
                $this->page['script'] = 'support.min';
                break;
            case 'et-panel-demos':
                $this->page['template'] = 'demos';
                $this->page['script'] = 'demos.min';
                break;
            case 'et-panel-patcher':
                $this->page['template'] = 'patcher';
                $this->page['script'] = 'patcher.min';
                break;
            case 'et-panel-custom-fonts':
                $this->page['template'] = 'custom-fonts';
                break;
            case 'et-panel-sales-booster':
                $this->page['script'] = 'sales_booster.min';
                $this->page['template'] = 'sales-booster';
                $this->page['class'] = 'sales-booster';
                do_action('etheme_panel_page_has_options');
                break;
            case 'et-panel-maintenance-mode':
                $this->page['script'] = 'maintenance_mode.min';
                $this->page['template'] = 'maintenance-mode';
                $this->page['class'] = 'maintenance-mode';
                break;
            case 'et-panel-social-authentication':
                $this->page['script'] = 'instagram.min';
                $this->page['template'] = 'social-authentication';
                $this->page['class'] = 'instagram';
                break;
            case 'et-panel-social':
                $this->page['script'] = 'instagram.min';
                $this->page['template'] = 'instagram';
                $this->page['class'] = 'instagram';
                break;
            case 'et-panel-open-ai':
                $this->page['script'] = 'ai.min';
                $this->page['template'] = 'ai';
//				$this->page['class'] = 'ai';
                break;
            case 'et-panel-plugins':
                $this->page['script'] = 'plugins.min';
                $this->page['template'] = 'plugins';
                $this->page['class'] = 'plugins';
                break;
            case 'et-panel-email-builder':
                $this->page['script'] = 'email_builder.min';
                $this->page['template'] = 'email-builder';
                $this->page['class'] = 'email-builder';
                break;
            case 'et-panel-theme-builders':
                $this->page['script'] = 'theme-builder.min';
                $this->page['template'] = 'theme-builders';
                $this->page['class'] = 'plugins';
                break;
            case 'et-panel-white-label-branding':
            case 'et-panel-xstore-amp':
                do_action('etheme_panel_page_has_options');
                break;
            case 'et-panel-search-stats':
//                do_action('etheme_panel_page_has_options');
                break;
            default:
                if ( strpos($_REQUEST['page'], 'et-panel') !== false ) {
                    if ( $_REQUEST['page'] == 'et-panel-email-builder-templates' ) {
                        wp_safe_redirect( admin_url('edit.php?post_type=viwec_template') );
                    }
                    else {
                        $this->page['template'] = 'welcome';
                        $this->page['script'] = 'welcome.min';
                    }
                }
                break;
        }
        return true;
    }

    /**
     * Require page classes
     *
     * require page classes when ajax process and return the callbacks for ajax requests
     *
     * @version  1.0.0
     * @since  7.0.0
     * @param string $class class filename
     */
    public function require_class($class=''){
        if (! $class){
            return;
        }
        require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'panel/classes/'.sanitize_file_name($class).'.php') );
    }

    /**
     * Global panel ajax
     *
     * require page classes when ajax process and return the callbacks for ajax requests
     *
     * @version  1.0.2
     * @since  7.0.0
     * @todo remove this
     * @log added sales_booster actions
     */
    public function et_panel_ajax(){
        if ( isset($_POST['action_type']) ){
            switch ( $_POST['action_type'] ) {
                case 'et_instagram_user_add':
                    $this->require_class('instagram');
                    $class = new Instagram();
                    $class->et_instagram_user_add();
                    break;
                case 'et_instagram_user_remove':
                    $this->require_class('instagram');
                    $class = new Instagram();
                    $class->et_instagram_user_remove();
                    break;
                case 'et_instagram_save_settings':
                    $this->require_class('instagram');
                    $class = new Instagram();
                    $class->et_instagram_save_settings();
                    break;
                case 'et_email_builder_switch_default':
                    $this->require_class('email-builder');
                    $class = new EmailBuilder();
                    $class->et_email_builder_switch_default();
                    break;
                case 'et_documentation_beacon':
                    $this->require_class('youtube');
                    $class = new YouTube();
                    $class->et_documentation_beacon();
                    break;
                case 'et_email_builder_switch_dev_mode_default':
                    $this->require_class('email-builder');
                    $class = new EmailBuilder();
                    $class->et_email_builder_switch_dev_mode_default();
                    break;
                case 'et_maintenance_mode_switch_default':
                    $this->require_class('maintenance-mode');
                    $class = new MaintenanceMode();
                    $class->et_maintenance_mode_switch_default();
                    break;
                case 'et_panel_dark_light_switch_default':
                    $this->et_panel_dark_light_switch_default();
                    break;
                default:
                    break;
            }
        }
    }

    public function et_panel_dark_light_switch_default(){
        update_option( 'et_panel_dark_light_default', $_POST['value']);
        die();
    }

    /**
     * Add admin panel dashboard pages to admin menu.
     *
     * @since   5.0.0
     * @version 1.0.3
     */
    public function et_add_menu_page(){
        $system = class_exists('Etheme_System_Requirements') ? Etheme_System_Requirements::get_instance() : new Etheme_System_Requirements();
        $system->system_test();
        $result = $system->result();

        $allow_full_access = !etheme_activation_required();
        $allow_force_access_woocommerce_features = true;
        $is_et_core = class_exists('ETC\App\Controllers\Admin\Import');
        $is_activated = etheme_is_activated();
        $is_wc = class_exists('WooCommerce');
        $is_elementor = class_exists('\Elementor\Plugin');
        $info_base = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: {{info_color}}; font-size: 22px;"></span></span>';
        $info = str_replace('{{info_color}}', 'var(--et_admin_orange-color)', $info_base);
        $info = '';
        $update_info = str_replace('{{info_color}}', 'var(--et_admin_green-color)', $info_base);
        $update_info = '';

        $icon = ETHEME_CODE_IMAGES . 'wp-icon.svg';
        $label = 'XStore';
        $plugin_label = 'XStore';
        $show_pages = array(
            'welcome',
            'system_requirements',
            'demos',
            'plugins',
            'patcher',
            'open_ai',
            'customize',
            'email_builder',
            'sales_booster',
            'custom_fonts',
            'maintenance_mode',
            'social_authentication',
            'social',
            'support',
            'changelog',
            'sponsors'
        );

        $hide_theme_builders = false;

        $xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

        if ( count($xstore_branding_settings) ) {
            if (isset($xstore_branding_settings['control_panel'])) {
                if ($xstore_branding_settings['control_panel']['icon'])
                    $icon = $xstore_branding_settings['control_panel']['icon'];
                if ($xstore_branding_settings['control_panel']['label'])
                    $label = $xstore_branding_settings['control_panel']['label'];
                if (isset($xstore_branding_settings['control_panel']['hide_theme_builders']) && $xstore_branding_settings['control_panel']['hide_theme_builders'] == 'on')
                    $hide_theme_builders = true;

                $show_pages_parsed = array();
                foreach ($show_pages as $show_page) {
                    if (isset($xstore_branding_settings['control_panel']['page_' . $show_page]))
                        $show_pages_parsed[] = $show_page;
                };
                $show_pages = $show_pages_parsed;
            }
            if ( isset($xstore_branding_settings['plugins_data'] ) ) {
                if (isset($xstore_branding_settings['plugins_data']['label']) && !empty($xstore_branding_settings['plugins_data']['label']))
                    $plugin_label = $xstore_branding_settings['plugins_data']['label'];
            }
        }

        $priorities = array(
            'welcome' => 52,
            'theme-builders' => 52.2,
            'staticblocks' => 53.2, // in another file
            'etheme_portfolio' => 54, // in another file
            'sales-booster' => 52.3,
            'widgets' => 52.35,
            'email-builder' => 52.4,
            'theme-options' => 52.1,
            'etheme_slides' => 53.1,
            'etheme_mega_menus' => 53
        );

        $icons = array(
            'welcome' => $icon,
            'theme-builders' => 'dashicons-schedule',
            'staticblocks' => 'dashicons-welcome-widgets-menus', // in another file
            'etheme_portfolio' => 'dashicons-images-alt',
            'sales-booster' => 'dashicons-chart-bar',
            'widgets' => 'dashicons-align-right',
            'email-builder' => 'dashicons-email-alt',
            'theme-options' => 'dashicons-admin-settings',
            'etheme_slides' => 'dashicons-images-alt2',
            'etheme_mega_menus' => 'dashicons-welcome-widgets-menus'
        );

        $is_update_support = 'active';

        $is_subscription = false;

        if (
            $is_activated
        ){
            if (apply_filters('etheme_hide_updates', false)){
                $is_update_support = 'active';
                $is_update_available = false;
            } else {
                $check_update = new ETheme_Version_Check();
                $is_update_available = $check_update->is_update_available();
                $is_update_support = 'active'; //$check_update->get_support_status();

                $is_subscription = $check_update->is_subscription;
            }

        } else {
            $is_update_available = false;
        }

        if ($is_activated && $is_update_support !='active' && $result){
            if ($is_update_support == 'expire-soon'){
                $info = str_replace('{{info_color}}', 'var(--et_admin_orange-color)', $info_base);
            } else {
                $info = str_replace('{{info_color}}', 'var(--et_admin_red-color)', $info_base);
            }
        } else if ($is_activated && $is_update_available && $result ){
            $info = $update_info;
        } elseif(!$is_activated){
            $info = str_replace('{{info_color}}', 'var(--et_admin_orange-color)', $info_base);
        }

        // temporary disable setter for notices
        // @todo remove when alerts will be fully redesigned
        $info = '';

        $server_label = esc_html__( 'System Status', 'xstore' );

        if (!$result && ($is_activated || $allow_full_access)){
            $server_label = esc_html__( 'Server Reqs.', 'xstore' );
            $server_label .= ' ' . $info;
        }

        add_menu_page(
            $label . ' ' . ( ( (!$is_activated && !$allow_full_access) || !$result || $is_update_available || $is_update_support !='active' ) ? $info : '' ),
            $label . ' ' . ( ( (!$is_activated && !$allow_full_access) || !$result || $is_update_available || $is_update_support !='active' ) ? $info : '' ),
            'manage_options',
            'et-panel-welcome',
            array( $this, 'etheme_panel_page' ),
            $icons['welcome'],
            $priorities['welcome']
        );

        if ( in_array('welcome', $show_pages) ) {
            add_submenu_page(
                'et-panel-welcome',
                esc_html__( 'Dashboard', 'xstore' )  .' '. ( (!$is_activated && !$allow_full_access) || ($is_update_support !='active' && $result) ? $info : ''),
                esc_html__( 'Dashboard', 'xstore' ) .' '. ( (!$is_activated && !$allow_full_access) || ($is_update_support !='active' && $result) ? $info : ''),
                'manage_options',
                'et-panel-welcome',
                array( $this, 'etheme_panel_page' )
            );
        }

        if ( (!$is_activated || !$is_et_core) || $allow_full_access ) {
            if ( in_array('system_requirements', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    $server_label,
                    $server_label,
                    'manage_options',
                    'et-panel-system-requirements',
                    array( $this, 'etheme_panel_page' )
                );
            }
        }

        if ( $is_activated || $allow_full_access ) {

            // make active link if theme is active
            if ( in_array('demos', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Import Demos 130+', 'xstore' ),
                    esc_html__( 'Import Demos 130+', 'xstore' ),
                    'manage_options',
                    'et-panel-demos',
                    array( $this, 'etheme_panel_page' )
                );
            }

            if ( in_array('plugins', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Plugins Installer', 'xstore' ),
                    esc_html__( 'Plugins Installer', 'xstore' ),
                    'manage_options',
                    'et-panel-plugins',
                    array( $this, 'etheme_panel_page' )
                );
            }

            // tweak this page link to make link available always
            // but is hidden with css if is disabled from XStore White Label Branding plugin
            $available_patches_affix = '';
            //            if ( class_exists('Etheme_Patcher') ) {
            //                $patcher = Etheme_Patcher::get_instance();
            //                $available_patches = count($patcher->get_available_patches(ETHEME_THEME_VERSION));
            //                if ( $available_patches ) {
            //                    $available_patches_affix = ' <span class="awaiting-mod update-plugins patches-count count-'.$available_patches.'">'.
            //                        $available_patches.
            //                    '</span>';
            //                }
            //            }
            add_submenu_page(
                'et-panel-welcome',
                (in_array('patcher', $show_pages) ? esc_html__( 'Patcher', 'xstore' ) : ''),
                (in_array('patcher', $show_pages) ? esc_html__( 'Patcher', 'xstore' ) : '') . $available_patches_affix,
                'manage_options',
                'et-panel-patcher',
                array( $this, 'etheme_panel_page' )
            );

        }

        if ( ($is_activated || $allow_full_access) && in_array('custom_fonts', $show_pages) ) {
            add_submenu_page(
                'et-panel-welcome',
                esc_html__( 'Custom Fonts', 'xstore' ),
                esc_html__( 'Custom Fonts', 'xstore' ),
                'manage_options',
                'et-panel-custom-fonts',
                array( $this, 'etheme_panel_page' )
            );
        }

        if ( ($is_activated && $is_et_core) || $allow_full_access ) {

            $is_amp = class_exists('XStore_AMP');
            if ( !$is_amp ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__('AMP XStore', 'xstore'),
                    esc_html__('AMP XStore', 'xstore'),
                    'manage_options',
                    (!$is_amp || !$is_activated ? 'et-panel-plugins&plugin=xstore-amp' : 'et-panel-xstore-amp'),
                    (!$is_amp || !$is_activated ? array($this, 'etheme_panel_page') : '')
                );
            }

            if (in_array('open_ai', $show_pages)) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__('ChatGPT', 'xstore'),
                    esc_html__('ChatGPT', 'xstore'),
                    'manage_options',
                    'et-panel-open-ai',
                    array($this, 'etheme_panel_page')
                );
            }

            if (in_array('social_authentication', $show_pages)) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__('Social Authentication', 'xstore'),
                    esc_html__('Social Authentication', 'xstore'),
                    'manage_options',
                    'et-panel-social-authentication',
                    array($this, 'etheme_panel_page')
                );
            }

            if (in_array('social', $show_pages)) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__('API Integrations', 'xstore'),
                    esc_html__('API Integrations', 'xstore'),
                    'manage_options',
                    'et-panel-social',
                    array($this, 'etheme_panel_page')
                );
            }

            if (in_array('maintenance_mode', $show_pages)) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__('Maintenance Mode', 'xstore'),
                    esc_html__('Maintenance Mode', 'xstore'),
                    'manage_options',
                    'et-panel-maintenance-mode',
                    array($this, 'etheme_panel_page')
                );
            }

            if ( in_array('support', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Tutorials & Support', 'xstore' ),
                    esc_html__( 'Tutorials & Support', 'xstore' ),
                    'manage_options',
                    'et-panel-support',
                    array( $this, 'etheme_panel_page' )
                );
            }

            if (in_array('system_requirements', $show_pages)) {
                add_submenu_page(
                    'et-panel-welcome',
                    $server_label,
                    $server_label,
                    'manage_options',
                    'et-panel-system-requirements',
                    array($this, 'etheme_panel_page')
                );
            }

            if ( in_array('changelog', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Changelog', 'xstore' ),
                    esc_html__( 'Changelog', 'xstore' ),
                    'manage_options',
                    'et-panel-changelog',
                    array( $this, 'etheme_panel_page' )
                );
            }

        }

        if ( ($is_activated || $allow_full_access) && in_array('sponsors', $show_pages) ) {

            add_submenu_page(
                'et-panel-welcome',
                esc_html__('SEO Experts', 'xstore'),
                esc_html__('SEO Experts', 'xstore'),
                'manage_options',
                'https://overflowcafe.com/am/aff/go/8theme',
                ''
            );
        }
        //add_submenu_page(
        //  'et-panel-welcome',
        //  esc_html__( 'Customization Services', 'xstore' ),
        //  esc_html__( 'Customization Services', 'xstore' ),
        //  'manage_options',
        //  'https://wpkraken.io/?ref=8theme',
        //  ''
        //);

        //          add_submenu_page(
        //              'et-panel-welcome',
        //              esc_html__( 'Woocommerce Hosting', 'xstore' ),
        //              esc_html__( 'Woocommerce Hosting', 'xstore' ),
        //              'manage_options',
        //              'http://www.bluehost.com/track/8theme',
        //              ''
        //          );

        add_submenu_page(
            'et-panel-welcome',
            esc_html__( 'Get WPML Plugin', 'xstore' ),
            esc_html__( 'Get WPML Plugin', 'xstore' ),
            'manage_options',
            'https://wpml.org/?aid=46060&affiliate_key=YI8njhBqLYnp&dr',
            ''
        );
        //          add_submenu_page(
        //              'et-panel-welcome',
        //              esc_html__( 'Hosting Service', 'xstore' ),
        //              esc_html__( 'Hosting Service', 'xstore' ),
        //              'manage_options',
        //              'https://www.siteground.com/index.htm?afcode=37f764ca72ceea208481db0311041c62',
        //              ''
        //          );
        //            if (!$is_subscription){
        //                add_submenu_page(
        //                    'et-panel-welcome',
        //                    esc_html__( 'Go Unlimited', 'xstore' ),
        //                    esc_html__( 'Go Unlimited', 'xstore' ),
        //                    'manage_options',
        //                    'https://www.8theme.com/#price-section-anchor',
        //                    ''
        //                );
        //            }


        //          add_submenu_page(
        //              'et-panel-welcome',
        //              esc_html__( 'WooCommerce Plugins', 'xstore' ),
        //              esc_html__( 'WooCommerce Plugins', 'xstore' ),
        //              'manage_options',
        //              'https://yithemes.com/product-category/plugins/?refer_id=1028760',
        //              ''
        //          );

        //            if ( $is_et_core ) {
        //              add_submenu_page(
        //                  'et-panel-welcome',
        //                  esc_html__( 'Rate Theme', 'xstore' ),
        //                  esc_html__( 'Rate Theme', 'xstore' ),
        //                  'manage_options',
        //                  'https://themeforest.net/item/xstore-responsive-woocommerce-theme/reviews/15780546',
        //                  ''
        //              );
        //          }

        if ( ($allow_force_access_woocommerce_features || $is_wc) && in_array( 'email_builder', $show_pages ) ) {
            add_menu_page(
                esc_html__( 'Email Builder', 'xstore' ),
                esc_html__( 'Email Builder', 'xstore' ),
                'manage_options',
                'et-panel-email-builder',
                array( $this, 'etheme_panel_page' ),
                $icons['email-builder'],
                $priorities['email-builder']
            );
            if ( get_option('etheme_built_in_email_builder', false) ) {
                add_submenu_page(
                    'et-panel-email-builder',
                    esc_html__( 'Email Builder', 'xstore' ),
                    esc_html__( 'General Settings', 'xstore'),
                    'manage_options',
                    'et-panel-email-builder',
                    array($this, 'etheme_panel_page')
                );
                add_submenu_page(
                    'et-panel-email-builder',
                    esc_html__('View Templates', 'xstore'),
                    esc_html__('View Templates', 'xstore'),
                    'manage_options',
                    'et-panel-email-builder-templates',
                    array($this, 'etheme_panel_page')
                );
            }
        }

        if ( ($is_et_core && $is_activated) || $allow_full_access ) {
            add_menu_page(
                esc_html__('Widgets', 'xstore'),
                esc_html__('Widgets', 'xstore'),
                'edit_theme_options',
                admin_url('widgets.php'),
                '',
                $icons['widgets'],
                $priorities['widgets']
            );
        }

        if ( ($is_activated || $allow_full_access) && ($allow_force_access_woocommerce_features || $is_wc) && in_array( 'sales_booster', $show_pages ) ) {
            add_menu_page(
                esc_html__( 'Sales Booster', 'xstore' ),
                esc_html__( 'Sales Booster', 'xstore' ),
                'manage_options',
                'et-panel-sales-booster',
                array( $this, 'etheme_panel_page' ),
                $icons['sales-booster'],
                $priorities['sales-booster']
            );
            add_submenu_page(
                'et-panel-sales-booster',
                esc_html__( 'Sales Booster', 'xstore' ),
                esc_html__( 'All Features', 'xstore' ),
                'manage_options',
                'et-panel-sales-booster',
                array($this, 'etheme_panel_page')
            );
            $sales_booster_main_features = array(
                'fake_sale_popup' => esc_html__('Fake Sale Popup', 'xstore'),
                'cart_checkout_countdown' => esc_html__( 'Cart Countdown', 'xstore' ),
                'cart_checkout_progress_bar' => esc_html__( 'Progress Bar', 'xstore' ),
                'fake_live_viewing' => esc_html__('Fake Live Viewing', 'xstore'),
                'fake_product_sales' => esc_html__('Item Sold Indicator', 'xstore'),
                'customer_reviews_advanced' => esc_html__('Advanced Reviews', 'xstore'),
                'quantity_discounts' => esc_html__('Quantity Discounts', 'xstore'),
                'safe_checkout' => esc_html__('Safe Checkout', 'xstore'),
            );
            foreach ($sales_booster_main_features as $sales_booster_main_feature_key => $sales_booster_main_feature_title) {
                add_submenu_page(
                    'et-panel-sales-booster',
                    esc_html__( 'Sales Booster', 'xstore' ),
                    $sales_booster_main_feature_title,
                    'manage_options',
                    'et-panel-sales-booster&etheme-sales-booster-tab='.$sales_booster_main_feature_key,
                    array($this, 'etheme_panel_page')
                );
            }
            add_submenu_page(
                'et-panel-sales-booster',
                esc_html__( 'Sales Booster', 'xstore' ),
                esc_html__( 'More Features', 'xstore' ) . ' &rarr;',
                'manage_options',
                'et-panel-sales-booster',
                array($this, 'etheme_panel_page')
            );
        }

        if ( $allow_full_access && !$is_et_core ) {
            add_menu_page(
                esc_html__( 'Static Blocks', 'xstore' ),
                esc_html__( 'Static Blocks', 'xstore' ),
                'manage_options',
                'et-panel-staticblocks_post_type',
                array( $this, 'etheme_panel_page' ),
                $icons['staticblocks'],
                $priorities['staticblocks']
            );
        }

        if ( $allow_full_access && (!$is_et_core || !$is_elementor) ) {
            add_menu_page(
                esc_html__( 'Mega Menus', 'xstore' ),
                esc_html__( 'Mega Menus', 'xstore' ),
                'manage_options',
                'et-panel-etheme_mega_menus_post_type',
                array( $this, 'etheme_panel_page' ),
                $icons['etheme_mega_menus'],
                $priorities['etheme_mega_menus']
            );

            add_menu_page(
                esc_html__( 'Slides', 'xstore' ),
                esc_html__( 'Slides', 'xstore' ),
                'manage_options',
                'et-panel-etheme_slides_post_type',
                array( $this, 'etheme_panel_page' ),
                $icons['etheme_slides'],
                $priorities['etheme_slides']
            );
        }

        if ( $allow_full_access && !$is_et_core ) {
            add_menu_page(
                esc_html__( 'Portfolio', 'xstore' ),
                esc_html__( 'Portfolio', 'xstore' ),
                'manage_options',
                'et-panel-etheme_portfolio_post_type',
                array( $this, 'etheme_panel_page' ),
                $icons['etheme_portfolio'],
                $priorities['etheme_portfolio']
            );
        }

        if ( ($is_activated || $allow_full_access) && !$hide_theme_builders ) { // && class_exists('\Elementor\Plugin')

            //            $theme_builder_key = 'et-elementor'. ($has_pro ? '-pro' : '') . '-theme-builder';
            $theme_builder_key = 'et-panel-theme-builders';
            add_menu_page(
                sprintf(esc_html__('%s Builders', 'xstore'), $label),
                sprintf(esc_html__('%s Builders', 'xstore'), $label),
                'manage_options',
                $theme_builder_key,
                array( $this, 'etheme_panel_page_theme_builders' ),
                $icons['theme-builders'],
                $priorities['theme-builders']
            );

//            if ( $has_pro ) {

            add_submenu_page(
                $theme_builder_key,
                esc_html__('Builders Panel', 'xstore'),
                esc_html__('Builders Panel', 'xstore'),
                'manage_options',
                $theme_builder_key,
                array( $this, 'etheme_panel_page_theme_builders' )
            );

            $theme_builders = array(
                'header' => array(
                    'title' => esc_html__('Header', 'xstore'),
                    'multiple_builders' => [
                        'customizer' => admin_url('/customize.php?autofocus[panel]=header-builder'),
                        'elementor' => true
                    ],
                ),
                'footer' => array(
                    'title' => esc_html__('Footer', 'xstore'),
                    'multiple_builders' => [
                        'elementor' => true
                    ],
                )
            );
//                if ( $allow_force_access_woocommerce_features || $is_wc ) {
            $theme_builders = array_merge($theme_builders,
                array(
                    'product' => array(
                        'title' => esc_html__('Single Product', 'xstore'),
                        'multiple_builders' => [
                            'customizer' => admin_url('/customize.php?autofocus['.(get_option( 'etheme_single_product_builder', false ) ? 'panel' : 'section').']=single_product_builder'),
                            'elementor' => true
                        ],
                    ),
                    'product-archive' => array(
                        'title' => esc_html__('Products Archive', 'xstore'),
                        'multiple_builders' => [
                            'elementor' => true
                        ],
                    ),
                    'myaccount' => array(
                        'title' => esc_html__('My Account Page', 'xstore'),
                        'multiple_builders' => [
                            'elementor' => true
                        ],
                    ),
                    'cart' => array(
                        'title' => esc_html__('Cart Page', 'xstore'),
                        'multiple_builders' => [
                            'elementor' => true
                        ],
                    ),
                    'checkout' => array(
                        'title' => esc_html__('Checkout Page', 'xstore'),
                        'multiple_builders' => [
                            'elementor' => true
                        ],
                    ),
                    'archive' => array(
                        'title' => esc_html__('Archive', 'xstore'),
                        'multiple_builders' => [
                            'elementor' => true
                        ],
                    ),
                ));
//                }
            $theme_builders = array_merge($theme_builders, array(
                'search-results' => array(
                    'title' => esc_html__('Search Results', 'xstore'),
                    'multiple_builders' => [
                        'elementor' => true
                    ],
                ),
                'error-404' => array(
                    'title' => esc_html__('Error-404', 'xstore'),
                    'multiple_builders' => [
                        'elementor' => true
                    ],
                ),
            ));

            $has_pro = $is_elementor && defined( 'ELEMENTOR_PRO_VERSION' );
            $elementor_pro_theme_builder_link = $has_pro ? \Elementor\Plugin::$instance->app->get_settings('menu_url') : '';

            foreach ($theme_builders as $theme_builder_unique_key => $theme_builder_unique_info) {
                $allow_customizer_builder = isset($theme_builder_unique_info['multiple_builders']['customizer']);
                $allow_elementor_builder = isset($theme_builder_unique_info['multiple_builders']['elementor']);

                if ( !$is_wc && in_array($theme_builder_unique_key, array('product','product-old', 'product-archive', 'myaccount', 'cart', 'checkout') ) )
                    $allow_customizer_builder = $allow_elementor_builder = false;

                $builder_url = admin_url( 'admin.php?page=et-panel-theme-builders' );
                $builders_panel_url = admin_url( 'admin.php?page=et-panel-theme-builders' );

                // takeover the priority for Customizer builder over other theme builders
                if ( $allow_customizer_builder )
                    $builder_url = $theme_builder_unique_info['multiple_builders']['customizer'];

                // takeover the priority for Elementor builder over other theme builders
                if ( $allow_elementor_builder ) {
                    if ( in_array($theme_builder_unique_key, array('myaccount', 'cart', 'checkout')) ) {
                        if ($has_pro) {
                            if (
                                !get_option('et_theme_builder_wc_install_done', false)
                                && method_exists('\WC_Install', 'create_pages')
                                && get_option('etheme_current_version')
                            ) {
                                remove_filter('woocommerce_create_pages', 'etheme_do_not_setup_demo_pages', 10);
                                \WC_Install::create_pages();
                                // to start this once only on first load
                                update_option('et_theme_builder_wc_install_done', 'yes');
                            }
                            $cart_checkout_page_id = get_option('woocommerce_' . $theme_builder_unique_key . '_page_id'); // wc_get_page_id($theme_builder_unique_key) not working correct;
                            if ($cart_checkout_page_id > 0) {
                                $builder_url = add_query_arg(array('post' => $cart_checkout_page_id, 'action' => 'elementor', 'et_page' => $theme_builder_unique_key), admin_url('post.php'));
                            } else {
                                // $builder_url = $elementor_pro_theme_builder_link . '/templates/single-page';
                                $builder_url = add_query_arg(array('post_type' => 'page', 'action' => 'elementor', 'et_page' => $theme_builder_unique_key), admin_url('post-new.php'));
                            }
                        }
                    }
                    else
                        $builder_url = !$has_pro ? $builders_panel_url : $elementor_pro_theme_builder_link . '/templates/' . $theme_builder_unique_key;
                }

                add_submenu_page(
                    $theme_builder_key,
                    $theme_builder_unique_info['title'],
                    $theme_builder_unique_info['title'],
                    'manage_options',
                    $builder_url,
                    ''
                );
            }
//            }

        }

        if ( ($allow_full_access || $is_et_core) && in_array('customize', $show_pages) ) {
            if ( ($is_activated || $allow_full_access) && $is_et_core ) {
                add_menu_page(
                    esc_html__('Theme Options', 'xstore'),
                    esc_html__('Theme Options', 'xstore'),
                    'manage_options',
                    'et-panel-theme-options',
                    '',
                    $icons['theme-options'],
                    $priorities['theme-options']
                );
                add_submenu_page(
                    'et-panel-theme-options',
                    'Header Builder',
                    'Header Builder',
                    'manage_options',
                    admin_url('/customize.php?autofocus[panel]=header-builder'),
                    ''
                );
            }
            else {
                add_menu_page(
                    esc_html__('Theme Options', 'xstore'),
                    esc_html__('Theme Options', 'xstore'),
                    'manage_options',
                    'et-panel-theme-options',
                    admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
                    $icons['theme-options'],
                    $priorities['theme-options']
                );
            }
        }

    }

    /**
     * Add target blank to some dashboard pages.
     *
     * @since   6.2
     * @version 1.0.0
     */
    public function et_add_menu_page_target() {
        ob_start(); ?>
        <script type="text/javascript">
            jQuery(document).ready( function($) {
                $('#adminmenu .wp-submenu a[href*=themeforest]').attr('target','_blank');
            });
        </script>
        <?php echo ob_get_clean();
    }

    /**
     * Show Add admin panel dashboard pages.
     *
     * @since   5.0.0
     * @version 1.0.4
     */
    public function etheme_panel_page(){
        $has_template = isset($this->page['template']) && ! empty($this->page['template']);
        ob_start();
        get_template_part( 'framework/panel/templates/page', 'header' );
        get_template_part( 'framework/panel/templates/page', 'navigation' );
        echo '<div class="et-row etheme-page-content'.($has_template ? ' etheme-panel-page-'.$this->page['template'] : '').'">';

        if ($has_template){
            get_template_part( 'framework/panel/templates/page', $this->page['template'] );
        }
        echo '</div>';
        get_template_part( 'framework/panel/templates/page', 'footer' );
        echo ob_get_clean();
    }

    /**
     * Show Add admin panel dashboard pages.
     *
     * @since   5.0.0
     * @version 1.0.4
     */
    public function etheme_panel_page_theme_builders(){
        ob_start();
        get_template_part( 'framework/panel/templates/page', 'header', array('theme_builders_page' => true) );
//        get_template_part( 'framework/panel/templates/page', 'navigation' );
        echo '<div class="etheme-page-content">';

        if (isset($this->page['template']) && ! empty($this->page['template'])){
            get_template_part( 'framework/panel/templates/theme-builders/page', $this->page['template'] );
        }
        echo '</div>';
        get_template_part( 'framework/panel/templates/page', 'footer' );
        echo ob_get_clean();
    }

    /**
     * Load content for panel popups
     *
     * @since   6.0.0
     * @version 1.0.1
     * @log 1.0.2
     * ADDED: et_ajax_panel_popup header param
     */
    public function et_ajax_panel_popup(){
        $response = array();

        if ( isset( $_POST['type'] ) ) {
            if ( in_array($_POST['type'], array('registration', 'deregister', 'remove_content', 'install_theme_builder')) ) {
                ob_start();
                get_template_part( 'framework/panel/templates/popup-theme', $_POST['type']);
                $response['content'] = ob_get_clean();
            }
            else {
                ob_start();
                get_template_part( 'framework/panel/templates/popup-'.$_POST['type'], 'content' );
                $response['content'] = ob_get_clean();
            }
        } else {

            if (! isset( $_POST['header'] ) || $_POST['header'] !== 'false'){
                ob_start();
                get_template_part( 'framework/panel/templates/popup-import', 'head' );
                $response['head'] = ob_get_clean();
            } else {
                $response['head'] = '';
            }

            ob_start();
            get_template_part( 'framework/panel/templates/popup-import', 'content');
            $response['content'] = ob_get_clean();
        }
        wp_send_json($response);
    }

    /**
     * Redirect after theme was activated
     *
     * @since   6.0.0
     * @version 1.0.0
     */
    public function admin_redirects() {
        ob_start();
        if ( ! get_transient( '_' . $this->theme_name . '_activation_redirect' ) || get_option( 'envato_setup_complete', false ) ) {
            return;
        }
        delete_transient( '_' . $this->theme_name . '_activation_redirect' );
        wp_safe_redirect( admin_url( 'admin.php?page=et-panel-welcome' ) );
        exit;
    }

    public function switch_theme() {
        set_transient( '_' . $this->theme_name . '_activation_redirect', 1 );


//		if (
//			! get_theme_mod( 'header_top_elements', false )
//			&& ! get_theme_mod( 'header_main_elements', false )
//			&& ! get_theme_mod( 'header_bottom_elements', false )
//		){
//			$ooo = '{"header_top_elements":"{\"element-DWnDe\":{\"size\":\"12\",\"index\":\"1\",\"offset\":\"0\",\"element\":\"promo_text\"}}","header_main_elements":"{\"element-KRycs\":{\"size\":\"2\",\"index\":\"6\",\"offset\":\"0\",\"element\":\"logo\"},\"element-twJZy\":{\"size\":\"5\",\"index\":\"1\",\"offset\":\"0\",\"element\":\"main_menu\"},\"element-LtRTH\":{\"size\":\"5\",\"index\":\"8\",\"offset\":\"0\",\"element\":\"secondary_menu\"}}","header_bottom_elements":"{}","connect_block_package":"[]","options":{"logo_img_et-desktop":{"id":"","url":"","width":"","height":""},"retina_logo_img_et-desktop":{"id":"","url":"","width":"","height":""},"logo_align_et-desktop":"center","logo_width_et-desktop":"140","logo_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"logo_border_et-desktop":"solid","logo_border_color_custom_et-desktop":"","top_header_wide_et-desktop":true,"top_header_height_et-desktop":"30","top_header_fonts_et-desktop":{"text-transform":"none"},"top_header_zoom_et-desktop":"100","top_header_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"top_header_color_et-desktop":"#000000","top_header_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"top_header_border_et-desktop":"solid","top_header_border_color_custom_et-desktop":"#e1e1e1","main_header_wide_et-desktop":false,"main_header_height_et-desktop":"100","main_header_fonts_et-desktop":{"text-transform":"uppercase","font-backup":"","variant":"regular","font-weight":400,"font-style":"normal"},"main_header_zoom_et-desktop":"100","main_header_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"main_header_color_et-desktop":"#000000","main_header_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"main_header_border_et-desktop":"solid","main_header_border_color_custom_et-desktop":"#e1e1e1","bottom_header_wide_et-desktop":false,"bottom_header_height_et-desktop":"40","bottom_header_fonts_et-desktop":{"text-transform":"none"},"bottom_header_zoom_et-desktop":"100","bottom_header_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"bottom_header_color_et-desktop":"#000000","bottom_header_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"bottom_header_border_et-desktop":"solid","bottom_header_border_color_custom_et-desktop":"","top_header_sticky_et-desktop":false,"main_header_sticky_et-desktop":true,"bottom_header_sticky_et-desktop":false,"header_sticky_type_et-desktop":"smart","headers_sticky_animation_et-desktop":"toBottomFull","headers_sticky_animation_duration_et-desktop":0.70000000000000007,"headers_sticky_start_et-desktop":"80","headers_sticky_logo_img_et-desktop":{"id":"","url":"","width":"","height":""},"top_header_sticky_height_et-desktop":"60","top_header_sticky_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"top_header_sticky_color_et-desktop":"#000000","main_header_sticky_height_et-desktop":"75","main_header_sticky_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"main_header_sticky_color_et-desktop":"#000000","bottom_header_sticky_height_et-desktop":"60","bottom_header_sticky_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"bottom_header_sticky_color_et-desktop":"#000000","menu_item_style_et-desktop":"underline","main_menu_term":"53","menu_zoom_et-desktop":"100","menu_alignment_et-desktop":"flex-end","menu_item_fonts_et-desktop":{"font-family":"","variant":"","letter-spacing":"0px","text-transform":"inherit","font-weight":0,"font-style":""},"menu_item_border_radius_et-desktop":"30","menu_item_color_custom_et-desktop":"","menu_item_background_color_custom_et-desktop":"#c62828","menu_item_hover_color_custom_et-desktop":"#888888","menu_item_line_hover_color_custom_et-desktop":"#888888","menu_item_dots_color_custom_et-desktop":"#888888","menu_item_background_hover_color_custom_et-desktop":"","menu_item_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"10px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"},"menu_nice_space_et-desktop":false,"menu_item_border_color_custom_et-desktop":"","menu_item_border_hover_color_custom_et-desktop":"","menu_2_item_style_et-desktop":"underline","main_menu_2_term":"","menu_2_zoom_et-desktop":"100","menu_2_alignment_et-desktop":"flex-start","menu_2_item_fonts_et-desktop":{"font-family":"","variant":"","letter-spacing":"0px","text-transform":"inherit","font-weight":0,"font-style":""},"menu_2_item_border_radius_et-desktop":"30","menu_2_item_color_custom_et-desktop":"","menu_2_item_background_color_custom_et-desktop":"#c62828","menu_2_item_hover_color_custom_et-desktop":"#888888","menu_2_item_line_hover_color_custom_et-desktop":"#888888","menu_2_item_dots_color_custom_et-desktop":"#888888","menu_2_item_background_hover_color_custom_et-desktop":"","menu_2_item_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"10px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"},"menu_2_nice_space_et-desktop":false,"menu_2_item_border_color_custom_et-desktop":"","menu_2_item_border_hover_color_custom_et-desktop":"","menu_dropdown_zoom_et-desktop":"110","menu_dropdown_fonts_et-desktop":{"font-family":"","variant":"","letter-spacing":"0px","text-transform":"none","font-weight":0,"font-style":""},"menu_dropdown_background_custom_et-desktop":"#ffffff","menu_dropdown_color_et-desktop":"#000000","menu_dropdown_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"1px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"1em","padding-right":"2.14em","padding-bottom":"1em","padding-left":"2.14em"},"menu_dropdown_border_et-desktop":"solid","menu_dropdown_border_color_custom_et-desktop":"","secondary_menu_visibility":"on_hover","secondary_menu_home":true,"all_departments_text":"All departments","secondary_menu_term":"","secondary_title_fonts_et-desktop":{"font-family":"","variant":"","letter-spacing":"0px","text-transform":"inherit","font-weight":0,"font-style":""},"secondary_title_background_color_custom_et-desktop":"#c62826","secondary_title_color_et-desktop":"#ffffff","secondary_title_border_radius_et-desktop":"0","secondary_title_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"15px","padding-right":"10px","padding-bottom":"15px","padding-left":"10px"},"secondary_title_border_et-desktop":"solid","secondary_title_border_color_custom_et-desktop":"#e1e1e1","secondary_menu_content_zoom_et-desktop":"100","secondary_menu_content_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"15px","padding-right":"30px","padding-bottom":"15px","padding-left":"30px"},"secondary_menu_content_border_et-desktop":"solid","secondary_menu_content_border_color_custom_et-desktop":"#e1e1e1","mobile_menu_type_et-desktop":"off_canvas_left","mobile_menu_icon_et-desktop":"icon1","mobile_menu_icon_zoom_et-desktop":1.5,"mobile_menu_label_et-desktop":false,"mobile_menu_text":"Menu","mobile_menu_item_click_et-desktop":false,"mobile_menu_content":["logo","search","menu","wishlist","cart","account","header_socials"],"mobile_menu_2":"categories","mobile_menu_term":"","mobile_menu_logo_type_et-desktop":"simple","mobile_menu_logo_width_et-desktop":"120","mobile_menu_content_alignment_et-desktop":"start","mobile_menu_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"mobile_menu_border_et-desktop":"solid","mobile_menu_border_color_custom_et-desktop":"#e1e1e1","mobile_menu_content_fonts_et-desktop":{"text-transform":"capitalize","font-backup":"","variant":"regular","font-weight":400,"font-style":"normal"},"mobile_menu_zoom_dropdown_et-desktop":"100","mobile_menu_zoom_popup_et-desktop":"100","mobile_menu_overlay_et-desktop":"rgba(0,0,0,.3)","mobile_menu_color2_et-desktop":"#ffffff","mobile_menu_max_height_et-desktop":"500","mobile_menu_background_color_et-desktop":"#ffffff","mobile_menu_color_et-desktop":"#000000","mobile_menu_content_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"10px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"},"mobile_menu_content_border_et-desktop":"solid","mobile_menu_content_border_color_custom_et-desktop":"#e1e1e1","cart_style_et-desktop":"type1","cart_icon_et-desktop":"type2","cart_icon_zoom_et-desktop":1.3,"cart_label_et-desktop":false,"cart_label_custom":"Cart","cart_total_et-desktop":false,"cart_content_type_et-desktop":"dropdown","mini-cart-items-count":"3","cart_link_to":"cart_url","cart_custom_url":"#","cart_quantity_et-desktop":true,"cart_quantity_size_et-desktop":0.75,"cart_quantity_active_background_custom_et-desktop":"#ffffff","cart_quantity_active_color_et-desktop":"#000000","cart_content_alignment_et-desktop":"end","cart_background_et-desktop":"current","cart_background_custom_et-desktop":"#ffffff","cart_color_et-desktop":"#000000","cart_border_radius_et-desktop":"0","cart_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"cart_border_et-desktop":"solid","cart_border_color_custom_et-desktop":"#e1e1e1","cart_zoom_et-desktop":"100","cart_dropdown_position_et-desktop":"right","cart_dropdown_position_custom_et-desktop":"0","cart_dropdown_background_custom_et-desktop":"#ffffff","cart_dropdown_color_et-desktop":"#000000","cart_content_position_et-desktop":"right","cart_content_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"1px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"20px","padding-right":"20px","padding-bottom":"20px","padding-left":"20px"},"cart_content_border_et-desktop":"solid","cart_content_border_color_custom_et-desktop":"#e1e1e1","cart_footer_content_et-desktop":"Free shipping over 49$","cart_footer_background_custom_et-desktop":"#f5f5f5","cart_footer_color_et-desktop":"#555555","account_background_et-desktop":"","account_style_et-desktop":"type1","account_icon_et-desktop":"type1","account_icon_zoom_et-desktop":1.3,"account_content_type_et-desktop":"dropdown","account_label_et-desktop":true,"account_label_username":false,"account_text":"Log in \/ Sign in","account_logged_in_text":"My account","account_content_alignment_et-desktop":"start","account_background_custom_et-desktop":null,"account_color_et-desktop":"#ffffff","account_border_radius_et-desktop":"0","account_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"account_border_et-desktop":"solid","account_border_color_custom_et-desktop":"","account_zoom_et-desktop":"100","account_dropdown_position_et-desktop":"right","account_dropdown_position_custom_et-desktop":"0","account_dropdown_background_custom_et-desktop":"#ffffff","account_dropdown_color_et-desktop":"#000000","account_content_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"1px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"10px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"},"account_content_border_et-desktop":"solid","account_content_border_color_custom_et-desktop":"#e1e1e1","header_widget1":"","header_widget2":"","html_block1":"","html_block1_sections":false,"html_block1_section":"","html_block2":"","html_block2_sections":false,"html_block2_section":"","html_block3":"","html_block3_sections":false,"html_block3_section":"","promo_text_package":[{"text":"Take 30% off when you spend $120","icon":"et_icon-delivery","icon_position":"before","link_title":"Go shop","link":"#"},{"text":"Free 2-days standard shipping on orders $255+","icon":"et_icon-coupon","icon_position":"before","link_title":"Custom link","link":"#"}],"promo_text_autoplay_et-desktop":true,"promo_text_speed_et-desktop":"3","promo_text_delay_et-desktop":"4","promo_text_navigation_et-desktop":false,"promo_text_close_button_et-desktop":true,"promo_text_close_button_action_et-desktop":false,"promo_text_height_et-desktop":"38","promo_text_background_custom_et-desktop":"#000000","promo_text_color_et-desktop":"#ffffff","button_text_et-desktop":"Button","button_link_et-desktop":"","button_custom_link_et-desktop":"","button_fonts_et-desktop":{"text-transform":"none"},"button_zoom_et-desktop":1,"button_content_align_et-desktop":"start","button_background_custom_et-desktop":"#000000","button_border_radius_et-desktop":"0","button_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"button_border_et-desktop":"solid","button_border_color_custom_et-desktop":"","button_target_et-desktop":false,"button_no_follow_et-desktop":false,"newsletter_shown_on_et-desktop":"click","newsletter_delay_et-desktop":"300","newsletter_icon_et-desktop":"type1","newsletter_label_show_et-desktop":true,"newsletter_label_et-desktop":"Newsletter","newsletter_title_et-desktop":"Title","newsletter_content_et-desktop":"<p>You can add any HTML here (admin -&gt; Theme Options -&gt; E-Commerce -&gt; Promo Popup).<br \/> We suggest you create a static block and use it by turning on the settings below<\/p>","newsletter_sections_et-desktop":false,"newsletter_section_et-desktop":"","newsletter_content_alignment_et-desktop":"start","newsletter_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"newsletter_background_et-desktop[background-color]":null,"newsletter_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"15px","padding-right":"15px","padding-bottom":"15px","padding-left":"15px"},"newsletter_border_et-desktop":"solid","newsletter_border_color_custom_et-desktop":"","contacts_icon_et-desktop":"left","contacts_direction_et-desktop":"hor","contacts_package_et-desktop":[{"contact_title":"Phone","contact_subtitle":"Call us any time","contact_icon":"et_icon-phone"},{"contact_title":"Hours","contact_subtitle":"Call us any time 24\/7","contact_icon":"et_icon-calendar"},{"contact_title":"Email","contact_subtitle":"Write us any time","contact_icon":"et_icon-chat"}],"contacts_alignment_et-desktop":"start","contact_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"},"contact_border_et-desktop":"solid","contact_border_color_custom_et-desktop":"#e1e1e1","header_socials_type_et-desktop":"type1","header_socials_package_et-desktop":[{"social_name":"Facebook","social_url":"#","social_icon":"et_icon-facebook"},{"social_name":"Twitter","social_url":"#","social_icon":"et_icon-twitter"},{"social_name":"Instagram","social_url":"#","social_icon":"et_icon-instagram"},{"social_name":"Google plus","social_url":"#","social_icon":"et_icon-google_plus"},{"social_name":"Youtube","social_url":"#","social_icon":"et_icon-youtube"},{"social_name":"Linkedin","social_url":"#","social_icon":"et_icon-linkedin"}],"header_socials_content_alignment_et-desktop":"start","header_socials_elements_zoom_et-desktop":"100","header_socials_elements_spacing_et-desktop":"10","header_socials_target_et-desktop":false,"header_socials_no_follow_et-desktop":false,"search_type_et-desktop":"input","search_ajax_et-desktop":true,"search_by_sku_et-desktop":true,"search_category_et-desktop":true,"search_all_categories_text_et-desktop":"All categories","search_placeholder_et-desktop":"Search for...","search_limit_results_et-desktop":"3","search_icon_zoom_et-desktop":1,"search_content_alignment_et-desktop":"center","search_width_et-desktop":"100","search_height_et-desktop":"40","search_border_radius_et-desktop":"0","search_color_et-desktop":"#888888","search_button_background_custom_et-desktop":"#000000","search_button_color_et-desktop":"#ffffff","search_input_box_model_et-desktop":{"margin-top":"0px","margin-right":"","margin-bottom":"0px","margin-left":"","border-top-width":"1px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"10px"},"search_input_border_et-desktop":"solid","search_input_border_color_custom_et-desktop":"#e1e1e1","search_icon_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"10px","padding-right":"0px","padding-bottom":"10px","padding-left":"0px"},"search_icon_border_et-desktop":"solid","search_icon_border_color_custom_et-desktop":"#e1e1e1","search_zoom_et-desktop":"100","search_content_position_et-desktop":"right","search_content_position_custom_et-desktop":"0","search_content_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"1px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"20px","padding-right":"30px","padding-bottom":"30px","padding-left":"30px"},"search_content_border_et-desktop":"solid","search_content_border_color_custom_et-desktop":"#e1e1e1","wishlist_style_et-desktop":"type1","wishlist_icon_et-desktop":"type1","wishlist_icon_zoom_et-desktop":1.3,"wishlist_label_et-desktop":true,"wishlist_label_custom_et-desktop":"Wishlist","wishlist_content_type_et-desktop":"dropdown","wishlist_link_to":"wishlist_url","wishlist_custom_url":"#","mini-wishlist-items-count":null,"wishlist_quantity_et-desktop":true,"wishlist_quantity_size_et-desktop":1,"wishlist_quantity_active_background_custom_et-desktop":"#ffffff","wishlist_quantity_active_color_et-desktop":"#000000","wishlist_content_alignment_et-desktop":"start","wishlist_background_et-desktop":"current","wishlist_background_custom_et-desktop":"#ffffff","wishlist_color_et-desktop":"#000000","wishlist_border_radius_et-desktop":"0","wishlist_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"wishlist_border_et-desktop":"solid","wishlist_border_color_custom_et-desktop":"#e1e1e1","wishlist_zoom_et-desktop":"100","wishlist_dropdown_position_et-desktop":"right","wishlist_dropdown_position_custom_et-desktop":"0","wishlist_dropdown_background_custom_et-desktop":"#ffffff","wishlist_content_position_et-desktop":"right","wishlist_content_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"1px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"20px","padding-right":"20px","padding-bottom":"20px","padding-left":"20px"},"wishlist_content_border_et-desktop":"solid","wishlist_content_border_color_custom_et-desktop":"#e1e1e1"}}';
//			$ooo = json_decode($ooo, true);
//			foreach ( $ooo as $key => $val ) {
//				set_theme_mod( $key, $val );
//			}
//		}


    }


//	Stas fields

    public $xstore_panel_section_settings, $settings_name;
    public function enqueue_settings_scripts($script) {
        if ( $script == 'icons_select' ) {
            $this->xstore_panel_icons_fonts_enqueue();
        }
        wp_register_script('etheme_panel_'.$script, ETHEME_BASE_URI.'framework/panel/js/settings/'.$script.'.js', array('jquery','etheme_admin_js'), false,true);
        wp_enqueue_script('etheme_panel_'.$script);
        wp_localize_script( 'xstore_panel_settings_'.$script, 'XStorePanelSettings'.ucfirst($script).'Config', $this->settingJsConfig );
    }

    public function xstore_panel_icons_list($type = 'simple') {
        $icons = array(
            'simple' => array(
                'none'                    => esc_html__( 'None', 'xstore' ),
                'et_icon-delivery'        => esc_html__( 'Delivery', 'xstore' ),
                'et_icon-coupon'          => esc_html__( 'Coupon', 'xstore' ),
                'et_icon-calendar'        => esc_html__( 'Calendar', 'xstore' ),
                'et_icon-compare'         => esc_html__( 'Compare', 'xstore' ),
                'et_icon-checked'         => esc_html__( 'Checked', 'xstore' ),
                'et_icon-tick'            => esc_html__( 'Tick', 'xstore' ),
                'et_icon-chat'            => esc_html__( 'Chat', 'xstore' ),
                'et_icon-phone-call'           => esc_html__( 'Phone', 'xstore' ),
                'et_icon-whatsapp'        => esc_html__( 'Whatsapp', 'xstore' ),
                'et_icon-viber'           => esc_html__( 'Viber', 'xstore' ),
                'et_icon-exclamation'     => esc_html__( 'Exclamation', 'xstore' ),
                'et_icon-gift'            => esc_html__( 'Gift', 'xstore' ),
                'et_icon-heart'           => esc_html__( 'Heart', 'xstore' ),
                'et_icon-heart-2'         => esc_html__( 'Heart 2', 'xstore' ),
                'et_icon-message'         => esc_html__( 'Message', 'xstore' ),
                'et_icon-internet'        => esc_html__( 'Internet', 'xstore' ),
                'et_icon-user'            => esc_html__( 'Account', 'xstore' ),
                'et_icon-sent'            => esc_html__( 'Sent', 'xstore' ),
                'et_icon-home'            => esc_html__( 'Home', 'xstore' ),
                'et_icon-shop'            => esc_html__( 'Shop', 'xstore' ),
                'et_icon-shopping-bag'    => esc_html__( 'Bag', 'xstore' ),
                'et_icon-shopping-bag-2'  => esc_html__( 'Bag 2', 'xstore' ),
                'et_icon-shopping-bag-3'  => esc_html__( 'Bag 3', 'xstore' ),
                'et_icon-shopping-cart'   => esc_html__( 'Cart', 'xstore' ),
                'et_icon-shopping-cart-2' => esc_html__( 'Cart 2', 'xstore' ),
                'et_icon-shopping-basket' => esc_html__( 'Basket', 'xstore' ),
                'et_icon-burger'          => esc_html__( 'Burger', 'xstore' ),
                'et_icon-star'            => esc_html__( 'Star', 'xstore' ),
                'et_icon-time'            => esc_html__( 'Time', 'xstore' ),
                'et_icon-location'        => esc_html__( 'Location', 'xstore' ),
                'et_icon-dev-menu'        => esc_html__( 'Dev menu', 'xstore' ),
                'et_icon-clock'           => esc_html__( 'clock', 'xstore' ),
                'et_icon-size'            => esc_html__( 'Size', 'xstore' ),
                'et_icon-more'            => esc_html__( 'More', 'xstore' ),
            ),
        );
        return $icons[$type];
    }
    // don't name setting with key of elements it will break saving for this field
    public function xstore_panel_settings_repeater_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = '', $template = array(), $active_callbacks = array(), $custom_item_title = false ) {

        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');

        $this->enqueue_settings_scripts( 'sortable' );
        $this->enqueue_settings_scripts( 'repeater' );

        $settings = $this->xstore_panel_section_settings;

        if ( isset( $settings[ $section ][ $setting ] ) ) {
            $selected_value = $settings[ $section ][ $setting ];
        } else {
            $selected_value = $default;
        }

        $values_2_save = $selected_value;
        if ( is_array( $selected_value ) ) {
            $values_2_save = array();
            foreach ( $selected_value as $item_value => $item_name ) {
                $values_2_save[] = $item_value;
            }
            $values_2_save = implode( ',', $values_2_save );
        }

        $sorted_list_parsed = array();
        if ( ! empty( $values_2_save ) ) {
            $sorted_list_values = explode( ',', $values_2_save );
            foreach ( $sorted_list_values as $item ) {
                $sorted_list_parsed[ $item ] = array(
                    'callbacks' => $template
                );
//			foreach ( $template as $template_item => $template_item_value) {
//			    $current_template = $template;
//				$current_template[$template_item]['args'][1] = $item.'_'.$template_item_value['args'][1];
//				$sorted_list_parsed[$item] = array(
//                    'callbacks' => $template
//                );
//		    }
            }
        }
//		foreach ($sorted_list_values as $item) {
//			$sorted_list_parsed[$item] = $default[$item];
//		}
        if ( count($sorted_list_parsed))
            $sorted_list_parsed = array_merge($sorted_list_parsed, $default);

        $class = '';
        $to_hide = false;
        $attr = array();
        if ( count($active_callbacks) ) {

            $this->enqueue_settings_scripts('callbacks');

            $attr['data-callbacks'] = array();
            foreach ( $active_callbacks as $key) {
                if ( isset($settings[ $key['section'] ]) ) {
                    if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                    }
                    else {
                        $to_hide = true;
                    }
                }
                elseif ( $key['value'] != $key['default'] ) {
                    $to_hide = true;
                }
                $attr['data-callbacks'][] = $key['name'].':'.$key['value'];
            }
            $attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
            unset($attr['data-callbacks']);
        }

        if ( $to_hide ) {
            $class .= ' hidden';
        }

        ob_start();
        ?>
        <div class="xstore-panel-option xstore-panel-repeater<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
            <div class="xstore-panel-option-title">

                <h4><?php echo esc_html( $setting_title ); ?>:</h4>

                <?php if ( $setting_descr ) : ?>
                    <p class="description"><?php echo esc_html( $setting_descr ); ?></p>
                <?php endif; ?>

            </div>
            <div class="xstore-panel-sortable-items">
                <?php
                $i=0;
                foreach ( $sorted_list_parsed as $item_value => $item_name) { $i++;?>
                    <div class="sortable-item" data-name="<?php echo esc_attr($item_value); ?>">
                        <h4 class="sortable-item-title">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="down-arrow" fill="currentColor" width=".85em" height=".85em" viewBox="0 0 24 24">
                                <path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>
                            </svg>
                            <?php if ( $custom_item_title ) echo esc_html($custom_item_title) . ' ' . $i; else echo esc_html__('Item', 'xstore') . ' ' . $i; ?>
                        </h4>
                        <div class="settings">
                            <div class="settings-inner">
                                <?php
                                if ( isset($item_name['callbacks'])) {
                                    foreach ( $item_name['callbacks'] as $callback ) {
                                        $callback['args'][1] = $item_value.'_'.$callback['args'][1];
                                        call_user_func_array( $callback['callback'], $callback['args'] );
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php }
                ?>
            </div>
            <div class="sortable-item-template hidden">
                <div class="sortable-item" data-name="{{name}}">
                    <h4 class="sortable-item-title">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="down-arrow" fill="currentColor" width=".85em" height=".85em" viewBox="0 0 24 24">
                            <path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>
                        </svg>
                        <?php if ( $custom_item_title ) echo esc_html($custom_item_title) . ' {{item_number}}' ; else echo esc_html__('Item', 'xstore') . ' {{item_number}}'; ?>
                    </h4>
                    <div class="settings">
                        <div class="settings-inner">
                            <?php
                            foreach ( $template as $template_callback ) {
                                $template_callback['args'][1] = '{{name}}_'.$template_callback['args'][1];
                                call_user_func_array( $template_callback['callback'], $template_callback['args'] );
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <input type="button" class="add-item et-button no-loader" value="<?php echo esc_attr('Add new item', 'xstore'); ?>">
            <input type="button" class="remove-item et-button et-button-active no-loader" value="<?php echo esc_attr('Remove last item', 'xstore'); ?>">
            <input type="hidden" class="option-val" name="<?php echo esc_attr($setting); ?>" value="<?php echo esc_attr($values_2_save); ?>">
        </div>
        <?php
        echo ob_get_clean();
    }

    public function xstore_panel_settings_sortable_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = '', $active_callbacks = array() ) {

        $this->enqueue_settings_scripts('sortable');

        $settings = $this->xstore_panel_section_settings;

        if ( isset( $settings[ $section ][ $setting ] ) ) {
            $selected_value = $settings[ $section ][ $setting ];
        } else {
            $selected_value = $default;
        }

        $values_2_save = $selected_value;
        if ( is_array($selected_value)) {
            $values_2_save = array();
            foreach ( $selected_value as $item_value => $item_name ) {
                $values_2_save[] = $item_value;
            }
            $values_2_save = implode(',', $values_2_save);
        }

        $sorted_list_parsed = array();
        $sorted_list_values = explode(',', $values_2_save);
        foreach ($sorted_list_values as $item) {
            if ( !isset($default[$item])) continue;
            $sorted_list_parsed[$item] = $default[$item];
        }
        $sorted_list_parsed = array_merge($sorted_list_parsed, $default);

        $class = '';
        $to_hide = false;
        $attr = array();
        if ( count($active_callbacks) ) {

            $this->enqueue_settings_scripts('callbacks');

            $attr['data-callbacks'] = array();
            foreach ( $active_callbacks as $key) {
                if ( isset($settings[ $key['section'] ]) ) {
                    if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                    }
                    else {
                        $to_hide = true;
                    }
                }
                elseif ( $key['value'] != $key['default'] ) {
                    $to_hide = true;
                }
                $attr['data-callbacks'][] = $key['name'].':'.$key['value'];
            }
            $attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
            unset($attr['data-callbacks']);
        }

        if ( $to_hide ) {
            $class .= ' hidden';
        }

        ob_start();
        ?>
        <div class="xstore-panel-option xstore-panel-sortable<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
            <?php if ( $setting_title || $setting_descr) { ?>
                <div class="xstore-panel-option-title">

                    <?php if ( $setting_title ) { ?>
                        <h4><?php echo esc_html( $setting_title ); ?>:</h4>
                    <?php } ?>

                    <?php if ( $setting_descr ) : ?>
                        <p class="description"><?php echo esc_html( $setting_descr ); ?></p>
                    <?php endif; ?>

                </div>
            <?php } ?>
            <div class="xstore-panel-sortable-items">
                <?php
                foreach ( $sorted_list_parsed as $item_value => $item_name) {
                    if ( !$item_name['name'] ) continue;
                    $with_options = isset($item_name['callbacks']);
                    $visibility_setting_name = $item_value . '_visibility';
                    if ( isset($settings[ $section ]) ) {
                        if ( isset( $settings[ $section ][ $visibility_setting_name ] ) && $settings[ $section ][ $visibility_setting_name ] ) {
                            $visibility_setting_value = true;
                        }
                        else {
                            $visibility_setting_value = false;
                        }
                    }
                    else {
                        $visibility_setting_value = isset($item_name['visible']) ? $item_name['visible'] : true;
                    }
                    ?>
                    <div class="sortable-item<?php if(!$visibility_setting_value) {echo ' disabled'; }?><?php if (!$with_options) {?> no-settings<?php } ?>" data-name="<?php echo esc_attr($item_value); ?>">
                        <h4 class="sortable-item-title">
                            <?php if ( $with_options) : ?>
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="down-arrow" fill="currentColor" width=".85em" height=".85em" viewBox="0 0 24 24">
                                    <path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>
                                </svg>
                            <?php endif;
                            echo esc_html( $item_name['name'] ); ?>
                            <span class="item-visibility">
                                <input class="screen-reader-text" type="checkbox" id="<?php echo esc_attr($visibility_setting_name); ?>" name="<?php echo esc_attr($visibility_setting_name); ?>"
                                <?php if($visibility_setting_value) echo 'checked'; ?>>
                                <label for="<?php echo esc_attr($visibility_setting_name); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.14em" viewBox="0 0 24 24" width="1.14em" class="show-item"><path d="M0 0h24v24H0V0z" fill="none"/>
                                        <path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"/>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.14em" viewBox="0 0 24 24" width="1.14em" class="hide-item">
                <path d="M0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/>
                <path d="M12 6c3.79 0 7.17 2.13 8.82 5.5-.59 1.22-1.42 2.27-2.41 3.12l1.41 1.41c1.39-1.23 2.49-2.77 3.18-4.53C21.27 7.11 17 4 12 4c-1.27 0-2.49.2-3.64.57l1.65 1.65C10.66 6.09 11.32 6 12 6zm-1.07 1.14L13 9.21c.57.25 1.03.71 1.28 1.28l2.07 2.07c.08-.34.14-.7.14-1.07C16.5 9.01 14.48 7 12 7c-.37 0-.72.05-1.07.14zM2.01 3.87l2.68 2.68C3.06 7.83 1.77 9.53 1 11.5 2.73 15.89 7 19 12 19c1.52 0 2.98-.29 4.32-.82l3.42 3.42 1.41-1.41L3.42 2.45 2.01 3.87zm7.5 7.5l2.61 2.61c-.04.01-.08.02-.12.02-1.38 0-2.5-1.12-2.5-2.5 0-.05.01-.08.01-.13zm-3.4-3.4l1.75 1.75c-.23.55-.36 1.15-.36 1.78 0 2.48 2.02 4.5 4.5 4.5.63 0 1.23-.13 1.77-.36l.98.98c-.88.24-1.8.38-2.75.38-3.79 0-7.17-2.13-8.82-5.5.7-1.43 1.72-2.61 2.93-3.53z"/>
                </svg>
                                </label>
                            </span>
                        </h4>
                        <div class="settings">
                            <div class="settings-inner">
                                <?php
                                if ( $with_options ) {
                                    foreach ( $item_name['callbacks'] as $callback ) {
                                        call_user_func_array( $callback['callback'], $callback['args'] );
                                    }
                                }
                                //                                    if ( isset($item_name['callback']) )
                                //                                        call_user_func_array( $item_name['callback'], $item_name['args'] );
                                ?>
                            </div>
                        </div>
                    </div>
                <?php }
                ?>
            </div>
            <input type="hidden" class="option-val" name="<?php echo esc_attr($setting); ?>" value="<?php echo esc_html($values_2_save); ?>">
        </div>
        <?php
        echo ob_get_clean();
    }

    public function xstore_panel_settings_colorpicker_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = '', $config_var = '' ) {

        wp_enqueue_script( 'jquery-color' );
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

        $this->enqueue_settings_scripts('colorpicker');

        $settings = $this->xstore_panel_section_settings;

        ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-color">
            <div class="xstore-panel-option-title">

                <h4><?php echo esc_html( $setting_title ); ?>:</h4>

                <?php if ( $setting_descr ) : ?>
                    <p class="description"><?php echo esc_html( $setting_descr ); ?></p>
                <?php endif; ?>

            </div>
            <div class="xstore-panel-option-input">
                <input type="text" data-alpha="true" id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
                       class="color-field color-picker"
                       value="<?php echo ( isset( $settings[ $section ][ $setting ] ) ) ? esc_attr( $settings[ $section ][ $setting ] ) : $default; ?>"
                       <?php if ( $default ) : ?>data-default="<?php echo esc_attr($default); ?>"<?php endif; ?>
                       data-css-var="<?php echo esc_attr( $config_var ); ?>"/>
            </div>
        </div>

        <?php echo ob_get_clean();
    }

    public function xstore_panel_settings_upload_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $type = 'image', $save_as = 'url', $js_selector = '', $js_img_var = '' ) {

        wp_enqueue_media();

        $this->enqueue_settings_scripts('media');

        $settings = $this->xstore_panel_section_settings;

        ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-upload">
            <div class="xstore-panel-option-title">

                <h4><?php echo esc_html( $setting_title ); ?>:</h4>

                <?php if ( $setting_descr ) : ?>
                    <p class="description"><?php echo esc_html( $setting_descr ); ?></p>
                <?php endif; ?>

            </div>
            <div class="xstore-panel-option-input">
                <div class="<?php echo esc_attr( $setting ); ?>_preview xstore-panel-option-file-preview">
                    <?php
                    if ( ! empty( $settings[ $section ][ $setting ] ) ) {
                        $url = $settings[ $section ][ $setting ];
                        if ( $type == 'audio' ) {
                            $url = ETHEME_BASE_URI.'framework/panel/images/audio.png';
                        }
                        echo '<img src="' . esc_url( $url ) . '" />';
                    }
                    ?>
                </div>
                <div class="file-upload-container">
                    <div class="upload-field-input">
                        <input type="text" id="<?php echo esc_html( $setting ); ?>"
                               name="<?php echo esc_html( $setting ); ?>"
                               value="<?php echo ( isset( $settings[ $section ][ $setting ] ) ) ? esc_html( $settings[ $section ][ $setting ] ) : ''; ?>"
                               <?php if ( $js_selector ) : ?>data-js-selector="<?php echo esc_attr( $js_selector ); ?>"<?php endif; ?>
                            <?php if ( $js_img_var ) : ?> data-js-img-var="<?php echo esc_attr( $js_img_var ); ?>" <?php endif; ?>/>
                    </div>
                    <div class="upload-field-buttons">
                        <input type="button"
                               data-title="<?php esc_html_e( 'Login Screen Background Image', 'xstore' ); ?>"
                               data-button-title="<?php esc_html_e( 'Use File', 'xstore' ); ?>"
                               data-option-name="<?php echo esc_html( $setting ); ?>"
                               class="et-button et-button-dark-grey no-loader button-upload-file button-default"
                               value="<?php esc_html_e( 'Upload', 'xstore' ); ?>"
                               data-file-type="<?php echo esc_attr( $type ); ?>"
                               data-save-as="<?php echo esc_attr($save_as); ?>"/>
                        <input type="button"
                               data-option-name="<?php echo esc_html( $setting ); ?>"
                               class="et-button et-button-semiactive no-loader button-remove-file button-default <?php echo ( ! isset( $settings[ $section ][ $setting ] ) || '' === $settings[ $section ][ $setting ] ) ? 'hidden' : ''; ?>"
                               value="<?php esc_html_e( 'Remove', 'xstore' ); ?> "/>
                    </div>
                </div>
            </div>
        </div>
        <?php echo ob_get_clean();
    }

    /**
     * Description of the function.
     *
     * @param string $section
     * @param string $setting
     * @param string $setting_title
     * @param string $setting_descr
     * @param false  $default
     * @return void
     *
     * @since 1.0.0
     *
     */
    public function xstore_panel_settings_switcher_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = false, $active_callbacks = array() ) {

        $this->enqueue_settings_scripts('switch');

        $settings = $this->xstore_panel_section_settings;

//		$value = $settings[ $section ][ $setting ] ?? $default;
//		$value = $value === 'no' ? false : $value;

        if ( isset($settings[ $section ]) ) {
            if ( isset( $settings[ $section ][ $setting ] ) && $settings[ $section ][ $setting ] == 'on' ) {
                $value = true;
            }
            else {
                $value = false;
            }
        }
        else {
            $value = $default;
        }

        $class = '';
        $to_hide = false;
        $attr = array();
        if ( count($active_callbacks) ) {

            $this->enqueue_settings_scripts('callbacks');

            $attr['data-callbacks'] = array();
            foreach ( $active_callbacks as $key) {
                if ( isset($settings[ $key['section'] ]) ) {
                    if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                    }
                    else {
                        $to_hide = true;
                    }
                }
                elseif ( $key['value'] != $key['default'] ) {
                    $to_hide = true;
                }
                $attr['data-callbacks'][] = $key['name'].':'.$key['value'];
            }
            $attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
            unset($attr['data-callbacks']);
        }

        if ( $to_hide ) {
            $class .= ' hidden';
        }

        ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-switcher<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
            <div class="xstore-panel-option-title">
                <?php echo '<h4>'. $setting_title . ':</h4>'; ?>
                <?php if ( $setting_descr ) :
                    echo '<p class="description">'. $setting_descr . '</p>';
                endif; ?>
            </div>
            <div class="xstore-panel-option-input">
                <label for="<?php echo esc_attr($setting); ?>">
                    <input class="screen-reader-text" id="<?php echo esc_attr($setting); ?>"
                           name="<?php echo esc_attr($setting); ?>"
                           type="checkbox"
                           value="<?php if($value) echo 'on'; ?>"
                        <?php if($value) echo 'checked'; ?>>
                    <span class="switch"></span>
                </label>
            </div>
        </div>

        <?php echo ob_get_clean();
    }

    /**
     * Multicheckbox field type.
     *
     * @param string $section
     * @param string $setting
     * @param string $setting_title
     * @param string $setting_descr
     * @param array  $elements
     * @param array  $default_elements
     *
     * @return void
     *
     * @version 1.0.0
     * @since   3.2.2
     *
     */
    public function xstore_panel_settings_multicheckbox_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $elements = array(), $default_elements = array(), $active_callbacks = array() ) {

        $settings = $this->xstore_panel_section_settings;

        $class   = '';
        $to_hide = false;
        $attr    = array();
        if ( count( $active_callbacks ) ) {

            $this->enqueue_settings_scripts( 'callbacks' );

            $attr['data-callbacks'] = array();
            foreach ( $active_callbacks as $key ) {
                if ( isset( $settings[ $key['section'] ] ) ) {
                    if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                    } else {
                        $to_hide = true;
                    }
                } elseif ( $key['value'] != $key['default'] ) {
                    $to_hide = true;
                }
                $attr['data-callbacks'][] = $key['name'] . ':' . $key['value'];
            }
            $attr[] = 'data-callbacks="' . implode( ',', $attr['data-callbacks'] ) . '"';
            unset( $attr['data-callbacks'] );
        }

        if ( $to_hide ) {
            $class .= ' hidden';
        }

        // fix for white label prefix of elements
        $element_prefix = $this->settings_name == 'xstore_white_label_branding_settings' ? 'page' : $section;

        ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-multicheckbox<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attr ); ?>>
            <div class="xstore-panel-option-title">

                <h4><?php echo esc_html( $setting_title ); ?>:</h4>

                <?php if ( $setting_descr ) : ?>
                    <p class="description"><?php echo esc_html( $setting_descr ); ?></p>
                <?php endif; ?>

            </div>

            <div class="xstore-panel-option-input">
                <?php foreach ( $elements as $key => $val ) {
                    $key_origin = $key;
                    $key        = $element_prefix . '_' . $key; ?>
                    <label for="<?php echo esc_attr( $key ); ?>">
                        <input id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>"
                               type="checkbox"
                            <?php echo ( ( ! isset( $settings[ $section ] ) && in_array( $key_origin, $default_elements ) )
                                || ( isset( $settings[ $section ][ $key ] ) && $settings[ $section ][ $key ] ) ) ? 'checked' : ''; ?>>
                        <?php echo esc_attr( $val ); ?>
                    </label>
                <?php } ?>
            </div>
        </div>

        <?php echo ob_get_clean();
    }

    public function xstore_panel_settings_select_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $options = array(), $default = '', $active_callbacks = array() ) {

        $settings = $this->xstore_panel_section_settings;

        if ( isset( $settings[ $section ][ $setting ] ) ) {
            $selected_value = $settings[ $section ][ $setting ];
        } else {
            $selected_value = $default;
        }

        $class = '';
        $to_hide = false;
        $attr = array();
        if ( count($active_callbacks) ) {

            $this->enqueue_settings_scripts('callbacks');

            $attr['data-callbacks'] = array();
            foreach ( $active_callbacks as $key) {
                if ( isset($settings[ $key['section'] ]) ) {
                    if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                    }
                    else {
                        $to_hide = true;
                    }
                }
                elseif ( $key['value'] != $key['default'] ) {
                    $to_hide = true;
                }
                $attr['data-callbacks'][] = $key['name'].':'.$key['value'];
            }
            $attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
            unset($attr['data-callbacks']);
        }

        if ( $to_hide ) {
            $class .= ' hidden';
        }

        ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-select<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
            <div class="xstore-panel-option-title">

                <h4><?php echo esc_html( $setting_title ); ?>:</h4>

                <?php if ( $setting_descr ) :
                    echo '<p class="description">' . $setting_descr . '</p>';
                endif; ?>

            </div>
            <div class="xstore-panel-option-select">
                <select name="<?php echo esc_attr($setting); ?>" id="<?php echo esc_attr($setting); ?>">
                    <?php foreach ( $options as $key => $value ) { ?>
                        <option value="<?php echo esc_attr($key); ?>"
                            <?php if($key == $selected_value) echo 'selected'; ?>>
                            <?php echo esc_attr($value); ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <?php echo ob_get_clean();
    }

    public function xstore_panel_settings_icons_select( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $options = array(), $default = '' ) {
        $this->enqueue_settings_scripts('icons_select');

        $settings = $this->xstore_panel_section_settings;

        if ( isset( $settings[ $section ][ $setting ] ) ) {
            $selected_value = $settings[ $section ][ $setting ];
        } else {
            $selected_value = $default;
        }

        ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-icons-select">
            <div class="xstore-panel-option-title">

                <h4><?php echo esc_html( $setting_title ); ?>:</h4>

                <?php if ( $setting_descr ) : ?>
                    <p class="description"><?php echo esc_html( $setting_descr ); ?></p>
                <?php endif; ?>

            </div>
            <div class="xstore-panel-option-select">
                <select name="<?php echo esc_attr($setting); ?>" id="<?php echo esc_attr($setting); ?>">
                    <?php foreach ( $options as $key => $value ) { ?>
                        <option value="<?php echo esc_attr($key); ?>"
                            <?php if($key == $selected_value) echo 'selected'; ?>>
                            <?php echo esc_attr($value); ?></option>
                    <?php } ?>
                </select>
                <div class="<?php echo esc_attr( $setting ); ?>_preview xstore-panel-option-icon-preview">
                    <i class="et-icon <?php echo str_replace( 'et_icon', 'et', $selected_value ); ?>"></i>
                </div>
            </div>
        </div>

        <?php echo ob_get_clean();
    }

    public function xstore_panel_settings_slider_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $min = 0, $max = 50, $default = 12, $step = 1, $postfix = '', $active_callbacks = array(), $super_default = '', $enforce_super_defaults = false ) {

        $this->enqueue_settings_scripts('slider');

        $settings = $this->xstore_panel_section_settings;

        if ( isset( $settings[ $section ][ $setting ] ) ) {
            $value = $settings[ $section ][ $setting ];
        } else {
            $value = $default;
        }

        $class = '';
        $to_hide = false;
        $attr = array();
        if ( count($active_callbacks) ) {

            $this->enqueue_settings_scripts('callbacks');

            $attr['data-callbacks'] = array();
            foreach ( $active_callbacks as $key) {
                if ( isset($settings[ $key['section'] ]) ) {
                    if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                    }
                    else {
                        $to_hide = true;
                    }
                }
                elseif ( $key['value'] != $key['default'] ) {
                    $to_hide = true;
                }
                $attr['data-callbacks'][] = $key['name'].':'.$key['value'];
            }
            $attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
            unset($attr['data-callbacks']);
        }

        if ( $to_hide ) {
            $class .= ' hidden';
        }

        ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-slider<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
            <div class="xstore-panel-option-title">

                <h4><?php echo esc_html( $setting_title ); ?>:</h4>

                <?php if ( $setting_descr ) : ?>
                    <p class="description"><?php echo esc_html( $setting_descr ); ?></p>
                <?php endif; ?>

            </div>
            <div class="xstore-panel-option-input">
                <input type="range" id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
                       min="<?php echo esc_attr($min); ?>" max="<?php echo esc_attr($max); ?>" value="<?php echo esc_attr( $value ); ?>"
                       step="<?php echo esc_attr($step); ?>">
                <span class="value"
                      <?php if ( $postfix ) { ?>data-postfix="<?php echo esc_html($postfix); ?>" <?php } ?>><?php echo esc_attr( $value ); ?></span>

                <span class="reset dashicons dashicons-image-rotate" data-default="<?php echo esc_attr($default); ?>" data-text="<?php echo esc_attr('Reset', 'xstore'); ?>"></span>

                <?php if($super_default || $enforce_super_defaults) : ?>
                    <input type="hidden" class="super-default" value="<?php echo esc_attr($super_default);?>">
                <?php endif; ?>
            </div>
        </div>

        <?php echo ob_get_clean();
    }

    /**
     * Description of the function.
     *
     * @param       $title
     * @param array $active_callbacks - multiple array with must-have values as
     *                                'name' => name of option to compare,
     *                                'value' => value of option to compare,
     *                                'section' => section of option to compare,
     *                                'default' => default value of option for backward compatibility then
     *
     * @return void
     *
     * @since 1.0.0
     *
     */
public function xstore_panel_settings_tab_field_start($title, $active_callbacks = array()) {

    $this->enqueue_settings_scripts('tab');

    $class = '';
    $to_hide = false;
    $attr = array();
    if ( count($active_callbacks) ) {

        $this->enqueue_settings_scripts('callbacks');

        $attr['data-callbacks'] = array();
        foreach ( $active_callbacks as $key) {
            if ( isset($settings[ $key['section'] ]) ) {
                if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                }
                else {
                    $to_hide = true;
                }
            }
            elseif ( $key['value'] != $key['default'] ) {
                $to_hide = true;
            }
            $attr['data-callbacks'][] = $key['name'].':'.$key['value'];
        }
        $attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
        unset($attr['data-callbacks']);
    }

    if ( $to_hide ) {
        $class .= ' hidden';
    }

    ?>
    <div class="xstore-panel-option xstore-panel-option-tab <?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
        <?php echo '<h4 class="tab-title">' . $title; ?>
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="down-arrow" fill="currentColor" width=".85em" height=".85em" viewBox="0 0 24 24">
            <path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>
        </svg>
        <?php echo '</h4>'; ?>
        <div class="tab-content">
            <?php
            }

            public function xstore_panel_settings_tab_field_end() {
            ?>
        </div>
    </div>
    <?php
}

    public function xstore_panel_settings_input_number_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $min = 0, $max = 100, $default = '', $step = 1, $active_callbacks = array() ) {

        $settings = $this->xstore_panel_section_settings;

        if ( isset( $settings[ $section ][ $setting ] ) ) {
            $value = $settings[ $section ][ $setting ];
        } else {
            $value = $default;
        }

        $class = '';
        $to_hide = false;
        $attr = array();
        if ( count($active_callbacks) ) {

            $this->enqueue_settings_scripts('callbacks');

            $attr['data-callbacks'] = array();
            foreach ( $active_callbacks as $key) {
                if ( isset($settings[ $key['section'] ]) ) {
                    if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                    }
                    else {
                        $to_hide = true;
                    }
                }
                elseif ( $key['value'] != $key['default'] ) {
                    $to_hide = true;
                }
                $attr['data-callbacks'][] = $key['name'].':'.$key['value'];
            }
            $attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
            unset($attr['data-callbacks']);
        }

        if ( $to_hide ) {
            $class .= ' hidden';
        }

        ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-input<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
            <div class="xstore-panel-option-title">

                <h4><?php echo esc_html( $setting_title ); ?>:</h4>

                <?php if ( $setting_descr ) : ?>
                    <p class="description"><?php echo esc_html( $setting_descr ); ?></p>
                <?php endif; ?>

            </div>
            <div class="xstore-panel-option-input">
                <input type="number" id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
                       min="<?php echo esc_attr($min); ?>" max="<?php echo esc_attr($max); ?>" step="<?php echo esc_attr($step); ?>"
                       value="<?php echo esc_attr($value); ?>">
            </div>
        </div>

        <?php echo ob_get_clean();
    }

    public function xstore_panel_settings_input_text_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $placeholder = '', $default = '', $active_callbacks = array(), $js_selector = '' ) {

        $settings = $this->xstore_panel_section_settings;

        if ( isset( $settings[ $section ][ $setting ] ) ) {
            $value = $settings[ $section ][ $setting ];
        } else {
            $value = $default;
        }

        $class = '';
        $to_hide = false;
        $attr = array();
        if ( count($active_callbacks) ) {

            $this->enqueue_settings_scripts('callbacks');

            $attr['data-callbacks'] = array();
            foreach ( $active_callbacks as $key) {
                if ( isset($settings[ $key['section'] ]) ) {
                    if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                    }
                    else {
                        $to_hide = true;
                    }
                }
                elseif ( $key['value'] != $key['default'] ) {
                    $to_hide = true;
                }
                $attr['data-callbacks'][] = $key['name'].':'.$key['value'];
            }
            $attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
            unset($attr['data-callbacks']);
        }

        if ( $to_hide ) {
            $class .= ' hidden';
        }

        ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-input<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
            <div class="xstore-panel-option-title">

                <h4><?php echo esc_html( $setting_title ); ?>:</h4>

                <?php if ( $setting_descr ) :
                    echo '<p class="description">' . $setting_descr . '</p>';
                endif; ?>

            </div>
            <div class="xstore-panel-option-input">
                <input type="text" id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
                       placeholder="<?php echo esc_attr( $placeholder ); ?>"
                       value="<?php echo esc_attr($value); ?>"
                       <?php if ( $js_selector ) : ?>data-js-selector="<?php echo esc_attr( $js_selector ); ?>" <?php endif; ?>>
            </div>
        </div>

        <?php echo ob_get_clean();
    }

    // @todo not used
    public function xstore_panel_settings_button_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $options = array(), $active_callbacks = array() ) {

        $settings = $this->xstore_panel_section_settings;

        $class = '';
        $to_hide = false;
        $attr = array();
        if ( count($active_callbacks) ) {

            $this->enqueue_settings_scripts('callbacks');

            $attr['data-callbacks'] = array();
            foreach ( $active_callbacks as $key) {
                if ( isset($settings[ $key['section'] ]) ) {
                    if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                    }
                    else {
                        $to_hide = true;
                    }
                }
                elseif ( $key['value'] != $key['default'] ) {
                    $to_hide = true;
                }
                $attr['data-callbacks'][] = $key['name'].':'.$key['value'];
            }
            $attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
            unset($attr['data-callbacks']);
        }

        if ( $to_hide ) {
            $class .= ' hidden';
        }

        ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-button <?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
            <?php if ( $setting_title || $setting_descr ) : ?>
                <div class="xstore-panel-option-title">

                    <?php if ( $setting_title ) : ?>
                        <h4><?php echo esc_html( $setting_title ); ?>:</h4>
                    <?php endif; ?>

                    <?php if ( $setting_descr ) : ?>
                        <p class="description"><?php echo esc_html( $setting_descr ); ?></p>
                    <?php endif; ?>

                </div>
            <?php endif; ?>
            <div class="xstore-panel-option-input">
                <a class="et-button no-loader"
                   href="<?php echo esc_url($options['url']); ?>"
                   target="<?php echo esc_attr($options['target']); ?>">
                    <?php echo esc_html($options['text']); ?>
                </a>
            </div>
        </div>

        <?php echo ob_get_clean();
    }

    public function xstore_panel_settings_textarea_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = '', $super_default =  '', $enforce_super_defaults = false ) {
        global $allowedposttags;

        $settings = $this->xstore_panel_section_settings;

        if ( isset( $settings[ $section ][ $setting ] ) ) {
            $value = $settings[ $section ][ $setting ];
        } else {
            $value = $default;
        }

        ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-code-editor">
            <div class="xstore-panel-option-title">

                <h4><?php echo esc_html( $setting_title ); ?>:</h4>

                <?php if ( $setting_descr ) :
                    echo '<p class="description">' . $setting_descr . '</p>';
                endif; ?>

            </div>
            <div class="xstore-panel-option-input">
                    <textarea id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
                              style="width: 100%; height: 120px;"
                              class="regular-textarea"><?php echo wp_kses( $value, $allowedposttags ); ?></textarea>
                <?php if($super_default || $enforce_super_defaults) : ?>
                    <textarea class="super-default hidden" value="<?php echo wp_kses($super_default, $allowedposttags);?>"></textarea>
                <?php endif; ?>
            </div>
        </div>

        <?php echo ob_get_clean();
    }

    public function xstore_panel_settings_save() {

        $settings_name = isset( $_POST['settings_name'] ) ? $_POST['settings_name'] : $this->settings_name;
        if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['_nonce'] ), 'xstore_panel_saving_nonce' ) ||
            !in_array($settings_name, array('xstore_amp_settings', 'xstore_sales_booster_settings', 'xstore_white_label_branding_settings')) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, it's used only for nonce verification
            wp_send_json(array(
                'success' => false,
                'msg'  => '<h4 style="margin-bottom: 15px;">' . __( 'You are not allowed to complete this task due to invalid nonce validation.', 'xstore' )  . '</h4>',
                'icon' => '<img src="' . ETHEME_BASE_URI . ETHEME_CODE . 'assets/images/error-icon.png" alt="error icon" style="margin-top: 15px;"><br/><br/>',
            ));
            exit;
        }

        $all_settings            = (array)get_option( $settings_name, array() );
        $local_settings          = isset( $_POST['settings'] ) ? $_POST['settings'] : array();
        if ( isset( $_POST['type'] ) ) {
            $local_settings_key = $_POST['type'];
        }
        else {
            switch ( $settings_name ) {
                case 'xstore_sales_booster_settings':
                    $local_settings_key = 'fake_sale_popup';
                    break;
                default:
                    $local_settings_key = 'general';
            }
        }
        $updated                 = false;
        $local_settings_parsed   = array();

        foreach ( $local_settings as $setting ) {
//			$local_settings_parsed[ $local_settings_key ][ $setting['name'] ] = $setting['value'];
            // if ( $this->settings_name == 'xstore_sales_booster_settings' )
            $local_settings_parsed[ $local_settings_key ][ $setting['name'] ] = stripslashes( $setting['value'] );
        }

        $all_settings = array_merge( $all_settings, $local_settings_parsed );

        update_option( $settings_name, $all_settings );
        $updated = true;

        switch ($local_settings_key) {
            case 'fake_sale_popup':
                delete_transient('etheme_'.$local_settings_key.'_products_rendered');
                delete_transient('etheme_'.$local_settings_key.'_orders_rendered');
                break;
            case 'fake_live_viewing':
            case 'fake_product_sales':
                $product_ids = (array)get_transient('etheme_'.$local_settings_key.'_ids', array());
                if ( count($product_ids) ) {
                    foreach ($product_ids as $product_id) {
                        if ( $product_id )
                            delete_transient('etheme_'.$local_settings_key.'_' . $product_id);
                    }
                }
                break;
        }

        wp_send_json(array(
            'success' => !!$updated,
            'msg'  => '<h4 style="margin-bottom: 15px;">' . ( ( $updated ) ? esc_html__( 'Settings successfully saved!', 'xstore' ) : esc_html__( 'Settings saving error!', 'xstore' ) ) . '</h4>',
            'icon' => ( $updated ) ? '<img src="' . ETHEME_BASE_URI . ETHEME_CODE . 'assets/images/success-icon.png" alt="installed icon" style="margin-top: 15px;"><br/><br/>' : '',
        ));
    }

    public function et_close_installation_video(){
        check_ajax_referer('etheme_installation_video', 'security');

        if (!current_user_can( 'manage_options' )){
            wp_send_json(array('result'=> 'error'));
        }

        add_option('et_close_installation_video', true, '', false);
        wp_send_json(array('result'=> 'success'));
    }

    public function get_filters_form($filters_list = array(), $extra_settings = array()) {
        $settings = array(
            'type' => '',
            'title' => true,
            'custom_title' => false,
            'icon' => true,
            'custom_class' => '',
            'tag' => 'span',
            'arrow' => false,
            'custom_icon' => false,
            'on_hover' => true,
            'ghost_filters' => false // if needed only design of filters but not the work of them
        );
        $settings = wp_parse_args( $extra_settings, $settings );
        $active_key = !$settings['ghost_filters'] ? array_key_first($filters_list) : '';

        $classes = array();
        $classes[] = !empty($settings['custom_class']) ? $settings['custom_class'] . ' ' . 'et-filter-toggle-ghost' : 'et-filter-toggle';
        if ( !$settings['on_hover'] )
            $classes[] = 'et-filters-on-click';
        if (!$settings['title'])
            $classes[] = 'et-filter-toggle-icon';
        ?>
        <ul class="et-filters et-filters-type-1">
            <li>
                <<?php echo esc_attr($settings['tag']); ?> class="<?php echo implode(' ' , $classes); ?>">
                <?php if ( $settings['icon']) {
                    echo (false != $settings['custom_icon']) ? $settings['custom_icon'] :
                        '<svg width="1em" height="1em" viewBox="0 0 15 15" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="vertical-align: -2px;">
                            <path d="M13.4742 0H0.736341C0.142439 0 -0.206675 0.637571 0.133612 1.1015C0.140163 1.11035 -0.0680259 0.8403 4.99408 7.4036C5.10763 7.5601 5.16764 7.74323 5.16764 7.93353V14.2591C5.16764 14.8742 5.90791 15.2134 6.40988 14.8516L8.56755 13.3023C8.86527 13.0918 9.04292 12.7554 9.04292 12.4021V7.93353C9.04292 7.74323 9.10289 7.5601 9.21648 7.4036C14.2747 0.84534 14.0704 1.11029 14.0769 1.1015C14.4171 0.637776 14.0684 0 13.4742 0V0ZM8.46932 6.88784C8.26014 7.15903 8.12023 7.53716 8.12023 7.9335V12.402C8.12023 12.4786 8.08163 12.5515 8.01705 12.597C7.96024 12.6368 8.39049 12.3288 6.09032 13.9804V7.93353C6.09032 7.56071 5.97182 7.20207 5.74764 6.89639C5.74207 6.8888 5.89828 7.09148 2.57538 2.78316H11.6351L8.46932 6.88784ZM12.313 1.90425H1.89754L1.10671 0.878883H13.1038L12.313 1.90425Z"/>
                        </svg>';
                } ?>
                <?php
                if ( $settings['title'] )
                    echo (false == $settings['custom_title'] ? esc_html__('Filter', 'xstore') : $settings['custom_title']);
                ?>
            </<?php echo esc_attr($settings['tag']); ?>>
            <ul>
                <?php
                foreach ($filters_list as $filter_key => $filter_details) {
                    $filter_link = false;
                    $filter_title = $filter_details;
                    $filter_classes = '';
                    if ( is_array($filter_details) ) {
                        $filter_title = $filter_details['title'];
                        $filter_link = $filter_details['url'];
                        if (isset($filter_details['classes']))
                            $filter_classes = ' ' . $filter_details['classes'];
                    }
                    ?>
                    <li class="et-filter <?php echo esc_attr($settings['type']); if ( $active_key && $filter_key == $active_key ) echo ' active'; ?>" data-filter="<?php echo esc_attr($filter_key); ?>">
                        <?php if ( $filter_link ) { ?>
                        <a href="<?php echo esc_url($filter_link); ?>" target="_blank" rel="nofollow" <?php echo $filter_classes ? ' class="'.$filter_classes.'"' : ''; ?>>
                            <?php }
                            echo '<span>' . $filter_title . '</span>';
                            if ( $settings['arrow']) : ?>
                                <svg width="0.85em" height="0.85em" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg" class="et-filter-arrow">
                                    <path d="M3.34781 0.891602V1.79683H7.71231L0.28125 9.16597L0.937351 9.8166L8.36841 2.44747V6.77559H9.28125V0.891602H3.34781Z" fill="currentColor"/>
                                </svg>
                            <?php endif; ?>
                            <?php if ( $filter_link ) { ?>
                        </a>
                    <?php } ?>
                    </li>
                    <?php
                }
                ?>
            </ul>
            </li>
        </ul>
        <?php
    }

    public function get_loader($percentage = false, $echo = true) {
        ob_start(); ?>
        <span class="et-loader<?php if ($percentage) echo ' et-loader-percent'; ?>">
            <svg class="loader-circular" viewBox="25 25 50 50">
                <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
            </svg>
            <?php
            if ( $percentage )
                echo '<span class="loader-percent" data-percent="1">1%</span>';
            ?>
        </span>
        <?php
        if ( $echo )
            echo ob_get_clean();
        else
            return ob_get_clean();
    }
    public function get_search_form($type = 'demos', $placeholder_text = '') { ?>
        <div class="etheme-search">
            <input type="text" class="etheme-<?php echo esc_attr($type); ?>-search form-control" placeholder="<?php echo esc_attr($placeholder_text); ?>"<?php if (isset($_GET['s']) && !empty($_GET['s'])) echo ' value="'.$_GET['s'].'"'; ?>>
            <i class="etheme-search-icon">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32" fill="currentColor">
                    <path d="M31.712 30.4l-8.224-8.192c2.112-2.464 3.264-5.568 3.264-8.768 0-7.36-5.984-13.376-13.376-13.376-7.36 0-13.344 5.984-13.344 13.344s5.984 13.376 13.376 13.376c3.232 0 6.304-1.152 8.768-3.296l8.224 8.192c0.192 0.192 0.416 0.288 0.64 0.288s0.448-0.096 0.608-0.256c0.192-0.16 0.288-0.384 0.32-0.64 0-0.256-0.096-0.512-0.256-0.672zM24.928 13.44c0 6.336-5.184 11.52-11.552 11.52-6.336 0-11.52-5.184-11.52-11.552 0-6.336 5.184-11.52 11.552-11.52s11.52 5.184 11.52 11.552z"></path>
                </svg>
            </i>
            <span class="spinner">
                <div class="et-loader ">
                    <svg class="loader-circular" viewBox="25 25 50 50">
                        <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
                    </svg>
                </div>
            </span>
        </div>
    <?php }

    public function get_search_no_found() {
        ?>
        <div class="et-hide et-not-found text-center" style="width:100%">
            <p><img src="<?php echo esc_url( ETHEME_BASE_URI.'framework/panel/images/empty-search.png' ); ?>" alt="search"></p>
            <h4><?php echo esc_html__('Whoops...', 'xstore'); ?></h4>
            <h4><?php echo sprintf(__('We couldn\'t find "%s"', 'xstore'), '<span class="et-search-request"></span>'); ?></h4>
        </div>
        <?php
    }
    public function get_additional_panel_blocks($type = '') {
        $feedback_text = esc_html__('If you have any brilliant suggestions for new widgets, features, or improvements to enhance Elementor or our theme, we welcome your feedback.', 'xstore');
        switch ($type) {
            case 'demos':
                $feedback_text = esc_html__('Your feedback matters. If you have brilliant suggestions for new prebuilt website, we\'re all ears. Your ideas fuel our innovation, ensuring our tools are custom-tailored to your unique needs and desires.', 'xstore');
                break;
            case 'plugins':
                $feedback_text = esc_html__('We value your feedback immensely. If you have brilliant suggestions for a new plugin, we\'re eager to hear them. Your ideas are the driving force behind our innovation.', 'xstore');
                break;
        }
        ?>
        <div class="xstore-panel-info-blocks">
            <div class="xstore-panel-info-block">
                <svg width="24" height="28" viewBox="0 0 28 28" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.66676 0C3.46795 4.9215e-05 3.27367 0.0602806 3.10879 0.172988C2.9439 0.285695 2.81591 0.445752 2.74118 0.63268C2.66645 0.819609 2.64839 1.02491 2.68931 1.22231C2.73022 1.41971 2.82825 1.60024 2.97086 1.7408L3.63684 2.41653C3.7289 2.5138 3.83915 2.59147 3.96116 2.64497C4.08316 2.69847 4.21446 2.72673 4.34736 2.7281C4.48027 2.72947 4.61211 2.70392 4.73516 2.65295C4.85821 2.60198 4.97 2.52661 5.06398 2.43125C5.15796 2.33589 5.23225 2.22247 5.28249 2.09762C5.33273 1.97277 5.35791 1.839 5.35655 1.70415C5.3552 1.5693 5.32735 1.43608 5.27462 1.3123C5.22189 1.18851 5.14534 1.07664 5.04947 0.98324L4.38348 0.30751C4.29036 0.210214 4.17895 0.132866 4.05584 0.0800452C3.93273 0.0272245 3.80043 5.40939e-06 3.66676 0ZM24.3033 0.00131979C24.0439 0.00899156 23.7976 0.118804 23.6165 0.30751L22.9505 0.98324C22.8547 1.07664 22.7781 1.18851 22.7254 1.3123C22.6727 1.43608 22.6448 1.5693 22.6434 1.70415C22.6421 1.839 22.6673 1.97277 22.7175 2.09762C22.7678 2.22247 22.842 2.33589 22.936 2.43125C23.03 2.52661 23.1418 2.60198 23.2648 2.65295C23.3879 2.70392 23.5197 2.72947 23.6526 2.7281C23.7855 2.72673 23.9168 2.69847 24.0388 2.64497C24.1608 2.59147 24.2711 2.5138 24.3632 2.41653L25.0291 1.7408C25.1733 1.59838 25.2717 1.41514 25.3114 1.21505C25.3512 1.01497 25.3305 0.807355 25.2521 0.619388C25.1737 0.431422 25.0411 0.271858 24.8719 0.161579C24.7026 0.0512997 24.5044 -0.00455651 24.3033 0.00131979ZM14 0.0158374C8.86507 0.0173443 4.67615 4.25355 4.67615 9.4655C4.67615 12.1681 5.80261 14.6142 7.59896 16.3363C7.81024 16.5387 7.96543 16.8239 8.03732 17.1506L9.68018 24.6536C9.98356 26.0393 11.2066 27.0345 12.6056 27.0345H15.3944C16.7936 27.0345 18.0162 26.0392 18.3198 24.6536L19.964 17.1493C20.0352 16.8235 20.1895 16.54 20.401 16.3376V16.3363H20.4023C22.1979 14.6143 23.3238 12.1681 23.3238 9.4655C23.3238 4.25355 19.1349 0.0173471 14 0.0158374ZM14 2.04303C18.0584 2.04422 21.3259 5.34968 21.3259 9.4655C21.3259 11.5978 20.4437 13.5056 19.03 14.8621C18.4983 15.3706 18.1627 16.026 18.0128 16.7111L17.2324 20.2772H10.7676L9.98716 16.7098C9.83669 16.026 9.50139 15.3717 8.97126 14.8634V14.8621C7.55666 13.5056 6.67412 11.5981 6.67412 9.4655C6.67412 5.34967 9.94161 2.04422 14 2.04303ZM14.0013 4.06494C13.4499 4.06494 13.0023 4.51835 13.0023 5.07853V5.5299C11.7816 5.86641 10.8509 6.9868 10.8509 8.28825C10.8509 9.87149 12.1202 11.1601 13.6813 11.1601H14.5008C14.9597 11.1601 15.3333 11.5392 15.3333 12.0048C15.3333 12.4703 14.959 12.8494 14.4995 12.8494H13.36C12.8139 12.8494 12.5993 12.4711 12.5627 12.3954C12.3162 11.8947 11.7164 11.6941 11.2216 11.9427C10.7281 12.1934 10.529 12.802 10.7754 13.3034C11.1157 13.9927 11.8702 14.7185 13.001 14.8502V15.2145C13.001 15.7747 13.4486 16.2281 14 16.2281C14.5514 16.2281 14.999 15.7747 14.999 15.2145V14.8251C16.3216 14.5846 17.3299 13.415 17.3299 12.0048C17.3299 10.4215 16.0606 9.13291 14.4995 9.13291H13.68C13.2211 9.13291 12.8475 8.75383 12.8475 8.28825C12.8475 7.83822 13.2662 7.44359 13.7438 7.44359H14.3083C14.8131 7.44359 14.9885 7.66177 15.1043 7.8976C15.3514 8.39899 15.9513 8.6003 16.4454 8.35028C16.9389 8.09959 17.1393 7.49098 16.8929 6.98958C16.4966 6.18614 15.8361 5.67404 15.0003 5.49295V5.07853C15.0003 4.51835 14.5527 4.06494 14.0013 4.06494ZM1.01321 8.11932C0.880835 8.11742 0.749405 8.14223 0.626562 8.19232C0.503719 8.24241 0.391911 8.31676 0.297638 8.41107C0.203364 8.50538 0.128504 8.61776 0.0774082 8.74168C0.0263125 8.8656 0 8.99859 0 9.13291C0 9.26724 0.0263125 9.40023 0.0774082 9.52415C0.128504 9.64807 0.203364 9.76045 0.297638 9.85476C0.391911 9.94907 0.503719 10.0234 0.626562 10.0735C0.749405 10.1236 0.880835 10.1484 1.01321 10.1465H2.34519C2.47757 10.1484 2.609 10.1236 2.73184 10.0735C2.85468 10.0234 2.96649 9.94907 3.06076 9.85476C3.15504 9.76045 3.2299 9.64807 3.28099 9.52415C3.33209 9.40023 3.3584 9.26724 3.3584 9.13291C3.3584 8.99859 3.33209 8.8656 3.28099 8.74168C3.2299 8.61776 3.15504 8.50538 3.06076 8.41107C2.96649 8.31676 2.85468 8.24241 2.73184 8.19232C2.609 8.14223 2.47757 8.11742 2.34519 8.11932H1.01321ZM25.6548 8.11932C25.5224 8.11742 25.391 8.14223 25.2682 8.19232C25.1453 8.24241 25.0335 8.31676 24.9392 8.41107C24.845 8.50538 24.7701 8.61776 24.719 8.74168C24.6679 8.8656 24.6416 8.99859 24.6416 9.13291C24.6416 9.26724 24.6679 9.40023 24.719 9.52415C24.7701 9.64807 24.845 9.76045 24.9392 9.85476C25.0335 9.94907 25.1453 10.0234 25.2682 10.0735C25.391 10.1236 25.5224 10.1484 25.6548 10.1465H26.9868C27.1192 10.1484 27.2506 10.1236 27.3734 10.0735C27.4963 10.0234 27.6081 9.94907 27.7024 9.85476C27.7966 9.76045 27.8715 9.64807 27.9226 9.52415C27.9737 9.40023 28 9.26724 28 9.13291C28 8.99859 27.9737 8.8656 27.9226 8.74168C27.8715 8.61776 27.7966 8.50538 27.7024 8.41107C27.6081 8.31676 27.4963 8.24241 27.3734 8.19232C27.2506 8.14223 27.1192 8.11742 26.9868 8.11932H25.6548ZM23.6464 15.5418C23.4476 15.5418 23.2533 15.6021 23.0885 15.7148C22.9236 15.8275 22.7956 15.9875 22.7209 16.1745C22.6461 16.3614 22.6281 16.5667 22.669 16.7641C22.7099 16.9615 22.8079 17.142 22.9505 17.2826L23.6165 17.9583C23.7086 18.0556 23.8188 18.1333 23.9408 18.1868C24.0628 18.2403 24.1941 18.2685 24.327 18.2699C24.4599 18.2713 24.5918 18.2457 24.7148 18.1947C24.8379 18.1438 24.9497 18.0684 25.0437 17.973C25.1376 17.8777 25.2119 17.7643 25.2622 17.6394C25.3124 17.5146 25.3376 17.3808 25.3362 17.2459C25.3349 17.1111 25.307 16.9779 25.2543 16.8541C25.2016 16.7303 25.125 16.6184 25.0291 16.525L24.3632 15.8493C24.27 15.752 24.1586 15.6747 24.0355 15.6218C23.9124 15.569 23.7801 15.5418 23.6464 15.5418ZM4.32365 15.5431C4.0642 15.5508 3.81789 15.6606 3.63684 15.8493L2.97086 16.525C2.87498 16.6184 2.79844 16.7303 2.74571 16.8541C2.69298 16.9779 2.66512 17.1111 2.66377 17.2459C2.66242 17.3808 2.6876 17.5146 2.73784 17.6394C2.78808 17.7643 2.86236 17.8777 2.95635 17.973C3.05033 18.0684 3.16212 18.1438 3.28517 18.1947C3.40822 18.2457 3.54006 18.2713 3.67296 18.2699C3.80587 18.2685 3.93716 18.2403 4.05917 18.1868C4.18117 18.1333 4.29143 18.0556 4.38348 17.9583L5.04947 17.2826C5.19359 17.1402 5.29198 16.9569 5.33176 16.7568C5.37154 16.5568 5.35087 16.3491 5.27244 16.1612C5.194 15.9732 5.06147 15.8136 4.89218 15.7034C4.7229 15.5931 4.52474 15.5372 4.32365 15.5431ZM11.2125 22.3044H16.7875L16.37 24.2141C16.2673 24.6827 15.868 25.0073 15.3944 25.0073H12.6056C12.1305 25.0073 11.7329 24.6839 11.63 24.2141L11.2125 22.3044Z"/>
                </svg>
                <div>
                    <h3><?php echo esc_html__('Your Ideas, Our Inspiration!', 'xstore'); ?></h3>
                    <?php echo '<p>'.$feedback_text.'</p>'; ?>
                    <p><a href="<?php etheme_contact_us_url(true); ?>" target="_blank" rel="nofollow"><?php echo esc_html__('Send a message', 'xstore'); ?></a></p>
                </div>
            </div>
            <div class="xstore-panel-info-block">
                <svg width="24" height="28" viewBox="0 0 24 28" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.7209 0C5.25919 0 0 5.25919 0 11.7209C0 17.853 4.73849 22.893 10.7441 23.3922V26.0464H2.93022C2.80079 26.0446 2.67229 26.0685 2.55218 26.1168C2.43207 26.165 2.32276 26.2367 2.23058 26.3276C2.13841 26.4184 2.06521 26.5267 2.01526 26.6462C1.9653 26.7656 1.93957 26.8937 1.93957 27.0232C1.93957 27.1526 1.9653 27.2808 2.01526 27.4002C2.06521 27.5196 2.13841 27.6279 2.23058 27.7188C2.32276 27.8096 2.43207 27.8813 2.55218 27.9296C2.67229 27.9778 2.80079 28.0017 2.93022 27.9999H20.5116C20.641 28.0017 20.7695 27.9778 20.8896 27.9296C21.0097 27.8813 21.119 27.8096 21.2112 27.7188C21.3034 27.6279 21.3766 27.5196 21.4265 27.4002C21.4765 27.2808 21.5022 27.1526 21.5022 27.0232C21.5022 26.8937 21.4765 26.7656 21.4265 26.6462C21.3766 26.5267 21.3034 26.4184 21.2112 26.3276C21.119 26.2367 21.0097 26.165 20.8896 26.1168C20.7695 26.0685 20.641 26.0446 20.5116 26.0464H12.6976V23.3922C18.7033 22.893 23.4418 17.853 23.4418 11.7209C23.4418 5.25919 18.1826 0 11.7209 0ZM11.7209 1.95348C14.071 1.95348 16.2228 2.77898 17.9056 4.15496L15.4969 6.56375C14.418 5.77074 13.1564 5.20928 11.7209 5.20928C10.2854 5.20928 9.02374 5.77074 7.94492 6.56375L5.53614 4.15496C7.21893 2.77898 9.3708 1.95348 11.7209 1.95348ZM4.15496 5.53614L6.56375 7.94492C5.77074 9.02374 5.20928 10.2854 5.20928 11.7209C5.20928 13.1564 5.77074 14.418 6.56375 15.4969L4.15496 17.9056C2.77899 16.2228 1.95348 14.071 1.95348 11.7209C1.95348 9.3708 2.77899 7.21893 4.15496 5.53614ZM19.2868 5.53614C20.6628 7.21893 21.4883 9.3708 21.4883 11.7209C21.4883 14.071 20.6628 16.2228 19.2868 17.9056L16.878 15.4969C17.671 14.418 18.2325 13.1564 18.2325 11.7209C18.2325 10.2854 17.671 9.02374 16.878 7.94492L19.2868 5.53614ZM11.7209 7.16277C14.2498 7.16277 16.279 9.19194 16.279 11.7209C16.279 14.2498 14.2498 16.279 11.7209 16.279C9.19194 16.279 7.16277 14.2498 7.16277 11.7209C7.16277 9.19194 9.19194 7.16277 11.7209 7.16277ZM7.94492 16.878C9.02374 17.671 10.2854 18.2325 11.7209 18.2325C13.1564 18.2325 14.418 17.671 15.4969 16.878L17.9056 19.2868C16.2228 20.6628 14.071 21.4883 11.7209 21.4883C9.3708 21.4883 7.21893 20.6628 5.53614 19.2868L7.94492 16.878Z"/>
                </svg>
                <div>
                    <h3><?php echo esc_html__('Need Help? 24/7 Support Available', 'xstore'); ?></h3>
                    <p><?php echo esc_html__('Whether you\'re facing technical difficulties, seeking guidance on theme customization, or simply have questions about our products, we\'re here to help.', 'xstore'); ?></p>
                    <p><a href="<?php etheme_support_forum_url(true); ?>" target="_blank" rel="nofollow"><?php echo esc_html__('Get assistance now', 'xstore'); ?></a></p>
                </div>
            </div>
        </div>
        <?php
    }
}
$EtAdmin = new EthemeAdmin();
$EtAdmin->main_construct();