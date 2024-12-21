<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Panel Plugins Class.
 *
 *
 * @since   7.2.0
 * @version 1.0.0
 *
 */
class SalesBooster{
	
	// ! Main construct/ add actions
	function __construct(){
        add_action( 'wp_ajax_et_sales_booster_feature_switcher', array( $this, 'feature_switcher' ) );
        add_action( 'wp_ajax_et_sales_booster_feature_settings', array( $this, 'feature_settings' ) );
	}

    /**
     * Sale booster feature actions called by ajax
     *
     * @version  1.0.0
     * @since  9.2
     *
     */
    public function feature_switcher()
    {
        if (!check_ajax_referer('sales_booster_nonce', 'wpnonce') || empty($_POST['slug'])) {
            wp_send_json_error(array('error' => 1, 'message' => esc_html__('No Slug Found', 'xstore')));
        }

        $sale_booster_feature = $_POST['slug'];
        $action = $_POST['type'];
        $option_value = $action == 'activate';
        if ( $option_value )
            set_transient( 'xstore_sales_booster_settings_active_tab', $sale_booster_feature, HOUR_IN_SECONDS);

        if ( isset($_POST['theme_mod']) )
            set_theme_mod($sale_booster_feature, $option_value);
        else
            update_option('xstore_sales_booster_settings_' . $sale_booster_feature, $option_value, 'no');

        wp_send_json(array(
            'sale_booster_feature' => $sale_booster_feature,
            'action' => $action,
            'value' => $option_value
        ));
        exit;
    }

    public function feature_settings() {
//        if ( !defined('ET_CORE_DIR') )
//            wp_send_json_error(array('error' => 1, 'message' => esc_html__('XStore Core plugin required', 'xstore')));

        if (!check_ajax_referer('sales_booster_nonce', 'wpnonce') || empty($_POST['slug'])) {
            wp_send_json_error(array('error' => 1, 'message' => esc_html__('No Slug Found', 'xstore')));
        }

        $sale_booster_feature = $_POST['slug'];

        $response = array();
        $global_admin_class = EthemeAdmin::get_instance();
        $tab_content = $sale_booster_feature;
        $global_admin_class->settings_name = 'xstore_sales_booster_settings';

        $global_admin_class->xstore_panel_section_settings = get_option( $global_admin_class->settings_name, array() );

//        $dir_url = ET_CORE_URL . 'app/models/sales-booster';

        $response['content'] = $this->render_feature_settings_form(
                $global_admin_class,
                $global_admin_class->settings_name,
                array(
                    'title' => $_POST['name'],
                    'description' => $_POST['description']
                ),
                $tab_content
        );

        wp_send_json($response);
    }

    public function render_feature_settings_form($admin_class, $setting_name, $details, $tab_content) {
        $global_admin_class = $admin_class;
        ob_start(); ?>
        <h3>
            <?php echo esc_html($details['title']); ?>
            <?php if ( $details['description'] ) : ?>
            <span class="mtips<?php echo strlen($details['description']) > 70 ? ' mtips-lg' : ''; ?>">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="mt-mes">
                    <?php echo esc_html($details['description']); ?>
                </span>
            </span>
            <?php endif; ?>
        </h3>
        <p class="et-message saving-alert hidden"></p>
        <form class="xstore-panel-settings" method="post"
              data-settings-name="<?php echo esc_attr( $setting_name ); ?>"
              data-save-tab="<?php echo esc_attr( $tab_content ); ?>"
              data-in-popup="yes">
            <div class="et_panel-popup-inner with-scroll">
                <div class="xstore-panel-settings-inner">
                    <?php require_once( ET_CORE_DIR . 'app/models/sales-booster/features/'.$tab_content.'.php' ); ?>
                </div>
            </div>
            <br/>
            <br/>
            <button class="et-button et-button-grey2 full-width" type="submit" style="pointer-events: none">
                <?php echo esc_html__( 'Save changes', 'xstore' ); ?>
                <span class="et-loader">
                    <svg class="loader-circular" viewBox="25 25 50 50">
                        <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2"
                                stroke-miterlimit="10"></circle>
                    </svg>
                </span>
            </button>
        </form>
        <?php
        return ob_get_clean();
    }
}

new SalesBooster();