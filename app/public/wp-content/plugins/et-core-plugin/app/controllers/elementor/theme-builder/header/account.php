<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

/**
 * Account widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Account extends Off_Canvas_Skeleton {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'theme-etheme_account';
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
		return __( 'My Account', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-account et-elementor-header-builder-widget-icon-only';
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
        return array_merge(parent::get_keywords(), [ 'login', 'register' ]);
	}

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls() {

        parent::register_controls();

        $this->remove_control('automatically_open_canvas');
        $this->remove_control('items_count');
        $this->remove_control('off_canvas_advanced');
        $this->remove_control('content_align_top');
        $this->remove_control('product_title_full');

        // for getting account url in needed places
        $this->update_control(
                'redirect',
                [
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                    'default' => 'account',
                ]
        );

        $this->update_control(
            'selected_icon',
            [
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-user'
                ],
            ]
        );

        $this->update_control(
            'button_text',
            [
                'label' => __( 'Sign In Text', 'xstore-core' ),
                'default' => __( 'Sign In', 'xstore-core' ),
                'placeholder' => __( 'Sign In', 'xstore-core' ),
            ]
        );

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'after',
            'of'   => 'button_text',
        ] );

        $this->add_control(
            'button_text_extra',
            [
                'label' => __( 'My Account Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'My Account', 'xstore-core' ),
                'placeholder' => __( 'My Account', 'xstore-core' ),
            ]
        );

        $this->end_injection();

        $this->remove_control('section_additional');

        $disable_options = array(
            'show_quantity',
            'show_view_page',
            'show_view_page_extra'
        );

        foreach ($disable_options as $disable_option) {
            $this->update_control($disable_option,
                [
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                    'default' => '',
                ]
            );
        }
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2
     * @access protected
     */
    protected function render()
    {
        $is_loggedIn = is_user_logged_in();

        $account_page = function_exists( 'is_account_page' ) && is_account_page();
        // filter for prevent displaying off-canvas/dropdown account content on My account page
        $account_page_canvas = apply_filters( 'etheme_account_content_shown_account_pages', !$account_page );

        if ( !$account_page_canvas )
            add_filter('etheme_elementor_header_off_canvas_content', '__return_false');

        if ( $is_loggedIn )
            add_filter('etheme_elementor_header_off_canvas_button_text', [$this, 'button_loggedIn_text']);
        parent::render();
        if ( $is_loggedIn )
            remove_filter('etheme_elementor_header_off_canvas_button_text', [$this, 'button_loggedIn_text']);

        if ( !$account_page_canvas )
            remove_filter('etheme_elementor_header_off_canvas_content', '__return_false');
    }

    public function button_loggedIn_text() {
        $button_text = $this->get_settings_for_display('button_text_extra');
        return $button_text ?? esc_html__('My Account', 'xstore-core');
    }

    protected function render_main_content($settings, $element_page_url, $extra_args = array()) {
        $is_woocommerce   = class_exists( 'WooCommerce' );
        $element_page_url = $element_page_url ?? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
        if ( is_user_logged_in() ) {
            if ( $is_woocommerce )
                $this->loggedIn_main_content($element_page_url);
        }
        else {
            $this->loggedOut_main_content($element_page_url, $is_woocommerce);
        }
    }

    protected function loggedIn_main_content($element_page_url) { ?>
            <ul class="menu">
                <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) {
                    $url = ( $endpoint != 'dashboard' ) ? wc_get_endpoint_url( $endpoint, '', $element_page_url ) : $element_page_url;
                    ?>
                    <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                        <a href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( $label ); ?></a>
                    </li>
                <?php } ?>
            </ul>
        <?php
    }

    protected function loggedOut_main_content($element_page_url, $is_woocommerce = false) {
        $login_options = array();
        if ( $is_woocommerce ) {
            add_filter('etheme_account_login_form_new_layout', '__return_true');
            $with_tabs = get_query_var('et_account-registration', false);

            $login_options['form_tabs_start'] = '<div class="et_b-tabs-wrapper">';
            $login_options['form_tabs_end']   = '</div>';
            ob_start(); ?>
            <div class="et_b-tabs">
                        <span class="et-tab active" data-tab="login">
                            <?php esc_html_e( 'Login', 'xstore-core' ); ?>
                        </span>
                <span class="et-tab" data-tab="register">
                            <?php esc_html_e( 'Register', 'xstore-core' ); ?>
                        </span>
            </div>
            <?php
            $login_options['form_tabs'] = ob_get_clean();

            if ( $with_tabs ) {
                echo $login_options['form_tabs_start'];
                echo $login_options['form_tabs'];
            }

                    if ( $with_tabs ) :
                        ob_start();
                            woocommerce_login_form();
                        echo str_replace(array('<form', 'woocommerce-form-login '), array('<form data-tab-name="login" autocomplete="off" action="' . $element_page_url . '"', 'et_b-tab-content active woocommerce-form-login ' ), ob_get_clean());
                    else:
                        woocommerce_login_form();
                    endif;
                        ?>

                    <?php if ( $with_tabs ) : ?>
                        <form method="post" autocomplete="off"
                              class="woocommerce-form woocommerce-form-register et_b-tab-content register"
                              data-tab-name="register" <?php do_action( 'woocommerce_register_form_tag' ); ?>
                              action="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ?>">

                            <?php do_action( 'woocommerce_register_form_start' ); ?>

                            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row-wide">
                                    <label for="reg_username"><?php esc_html_e( 'Username', 'xstore-core' ); ?>
                                        &nbsp;<span class="required">*</span></label>
                                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                                           name="username" id="reg_username" autocomplete="username"
                                           value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                                </p>

                            <?php endif; ?>

                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row-wide">
                                <label for="reg_email"><?php esc_html_e( 'Email address', 'xstore-core' ); ?>
                                    &nbsp;<span class="required">*</span></label>
                                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text"
                                       name="email" id="reg_email" autocomplete="email"
                                       value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                            </p>

                            <?php if ( !get_query_var('et_account-registration-generate-pass', false) ) : ?>

                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row-wide">
                                    <label for="reg_password"><?php esc_html_e( 'Password', 'xstore-core' ); ?>
                                        &nbsp;<span class="required">*</span></label>
                                    <input type="password"
                                           class="woocommerce-Input woocommerce-Input--text input-text"
                                           name="password" id="reg_password" autocomplete="new-password"/>
                                </p>

                            <?php else : ?>

                                <p><?php esc_html_e( 'A password will be sent to your email address.', 'xstore-core' ); ?></p>

                            <?php endif; ?>

                            <?php do_action( 'woocommerce_register_form' ); ?>

                            <p class="woocommerce-FormRow">
                                <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce', false ); ?>
                                <input type="hidden" name="_wp_http_referer"
                                       value="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
                                <button type="submit" class="woocommerce-Button button" name="register"
                                        value="<?php esc_attr_e( 'Register', 'xstore-core' ); ?>"><?php esc_html_e( 'Register', 'xstore-core' ); ?></button>
                            </p>

                            <?php do_action( 'woocommerce_register_form_end' ); ?>

                        </form>

                        <?php
                        echo $login_options['form_tabs_end'];
                    endif;

            remove_filter('etheme_account_login_form_new_layout', '__return_true');
        } else {
            wp_login_form(
                array(
                    'echo'           => true,
                    'label_username' => esc_html__( 'Username or email *', 'xstore-core' ),
                    'label_password' => esc_html__( 'Password *', 'xstore-core' )
                )
            );
        }
    }
}
