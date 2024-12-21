<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
/**
 * Template "Plugins" for 8theme dashboard.
 *
 * @since   6.3.4
 * @version 1.0.1
 */

$_plugin = ( isset($_GET['plugin']) ) ? array_map( 'trim', explode(',', $_GET['plugin'])) : false;
$global_admin_class = EthemeAdmin::get_instance();
$allow_full_access = !etheme_activation_required();
$theme_active = etheme_is_activated();

?>
<div class="etheme-plugins-section">
    <h1 class="etheme-page-title etheme-page-title-type-2"><?php echo esc_html__('Plugins Installer', 'xstore'); ?></h1>
    <?php if(!$_plugin): ?>
        <div class="xstore-panel-grid-header">
            <?php
            $global_admin_class->get_filters_form(array(
                'all' => esc_html__( 'All', 'xstore' ),
                'active' => esc_html__( 'Active', 'xstore' ),
                'disabled' => esc_html__( 'Inactive', 'xstore' ),
                'premium' => esc_html__( 'Premium', 'xstore' ),
                'xstore' => esc_html__( 'XStore', 'xstore' ),
                'free' => esc_html__( 'Free', 'xstore' ),
            ), array('type' => 'plugin-filter'));

            $global_admin_class->get_search_form('plugin', esc_html__( 'Search for plugins', 'xstore' ));
            ?>
        </div>
    <?php endif; ?>
    <div class="xstore-panel-grid-wrapper manage-plugins">
		<?php
        $system = class_exists('Etheme_System_Requirements') ? Etheme_System_Requirements::get_instance() : new Etheme_System_Requirements();
		$system_requirements = $system->requirements;
		$system_status = $system->get_system(true);
		$instance  = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$installed = get_plugins();
		if ( !count($instance->plugins) ) {
			echo  '<p class="et-message et-error" style="width: 100%; margin: 0 20px;">' .
                  esc_html__('We are unable to connect to the 8Theme API to retrieve the list of plugins.', 'xstore') .
              '</p>';
			echo '</div></div>';
		    return;
        }

        $filesystem = !($system_status['filesystem'] != $system_requirements['filesystem']);

		foreach ( $instance->plugins as $slug => $plugin ) :
            if (
	            ( $_plugin && !in_array($slug, $_plugin) )
                || ( isset($plugin['ignore']) && $plugin['ignore'] )
            ){
                continue;
            }

			$new_is_plugin_active = (
				( ! empty( $instance->plugins[ $slug ]['is_callable'] ) && is_callable( $instance->plugins[ $slug ]['is_callable'] ) )
				|| in_array( $instance->plugins[ $slug ]['file_path'], (array) get_option( 'active_plugins', array() ) ) || is_plugin_active_for_network( $instance->plugins[ $slug ]['file_path'] )
			);
			$plugin_classes       = array();
			$plugin_classes[]     = 'xstore-panel-grid-item';
			$plugin_classes[]     = ( $new_is_plugin_active ) ? 'xstore-panel-grid-item-active' : '';
			if ( isset($plugin['latest_version']) ){
				$plugin['version']  = $plugin['latest_version'];
			}

			if ( isset( $installed[ $plugin['file_path'] ] ) ){
				$plugin['version'] = $installed[ $plugin['file_path'] ]['Version'];
            }

			$filters = array();
			$filters[] = 'all';

            $filters[] = $new_is_plugin_active ? 'active' : 'disabled';

			if ( isset($plugin['etheme_filters']) ){
				$filters = array_merge($filters, $plugin['etheme_filters'] );
            } else {
			    // @todo remove it in May
				if (isset( $plugin['premium'] ) && $plugin['premium']){
					$filters[] = 'premium';
				} else {
					$filters[] = 'free';
				}

				if (str_contains($plugin['name'], 'XStore')){
					$filters[] = 'xstore';
				}
            }

            // lock premium plugins from display if theme not activate and plugins are not ours (XStore developed)
            $register_theme_redirect = false;
            if ( $allow_full_access && !$theme_active && ( isset( $plugin['premium'] ) && $plugin['premium'] ) && !in_array($slug, array('et-core-plugin')) ) {
                $register_theme_redirect = true;
            }

			?>

            <div class="<?php echo trim( esc_attr( implode( ' ', $plugin_classes ) ) ); ?>"
                 data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"
                 data-filter="<?php echo trim( esc_attr( implode( ' ', $filters ) ) ); ?>>"
                    <?php if( $_plugin == 'xstore-amp' ):?>
                         data-redirect="<?php echo admin_url( 'admin.php?page=et-panel-xstore-amp' ); ?>"
                    <?php endif;?>
                >
                <div class="xstore-panel-grid-item-content">
                    <span
                            class="xstore-panel-grid-item-action-text"
                            data-install="<?php echo esc_html__('Installing', 'xstore') . ' ...'; ?>"
                            data-activate="<?php echo esc_html__('Activating', 'xstore') . ' ...'; ?>"
                            data-deactivate="<?php echo esc_html__('Deactivating', 'xstore') . ' ...'; ?>"
                            data-update="<?php echo esc_html__('Updating', 'xstore') . ' ...'; ?>"
                    ></span>
                    <div class="xstore-panel-grid-item-image">
                        <div class="xstore-panel-grid-item-labels">
							<?php if ( $plugin['required'] ) : ?>
                                <span class="xstore-panel-grid-item-label"><?php esc_html_e( 'Required', 'xstore' ); ?></span>
							<?php endif; ?>
							<?php if ( isset( $plugin['premium'] ) && $plugin['premium'] ) : ?>
                                <span class="xstore-panel-grid-item-label green-label"><?php esc_html_e( 'Premium', 'xstore' ); ?></span>
							<?php endif; ?>
                        </div>
                        <span class="xstore-panel-grid-item-checkbox">
                            <span class="mtips mtips-left inverse no-arrow">
                                <span class="dashicons dashicons-yes"></span>
                                <span class="mt-mes"><?php echo esc_html__('Activated', 'xstore'); ?></span>
                            </span>
                        </span>
						<?php if ( isset( $plugin['image_url'] ) ) : ?>
                            <img
                                class="lazyload lazyload-simple et-lazyload-fadeIn"
                                src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/placeholder-350x268.png"
                                data-src="<?php echo esc_attr( apply_filters('etheme_protocol_url',$plugin['image_url'] ) ); ?>"
                                data-old-src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/placeholder-350x268.png"
                                alt="<?php echo esc_attr( $plugin['slug'] ); ?>"
                            >
						<?php else: ?>
                            <span><?php esc_html_e( 'No image set', 'xstore' ); ?></span>
						<?php endif; ?>
                        <?php if ( isset($plugin['details_url']) ) : ?>
                            <a class="xstore-panel-grid-item-info" target="_blank" href="<?php echo esc_url($plugin['details_url'] ); ?>">
                                    <span class="mtips mtips-left inverse no-arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info" class="svg-inline--fa fa-info fa-w-6" role="img" viewBox="0 0 192 512" style="width: 1;width: 1em;height: 1em;">
                                            <path fill="currentColor" d="M20 424.229h20V279.771H20c-11.046 0-20-8.954-20-20V212c0-11.046 8.954-20 20-20h112c11.046 0 20 8.954 20 20v212.229h20c11.046 0 20 8.954 20 20V492c0 11.046-8.954 20-20 20H20c-11.046 0-20-8.954-20-20v-47.771c0-11.046 8.954-20 20-20zM96 0C56.235 0 24 32.235 24 72s32.235 72 72 72 72-32.235 72-72S135.764 0 96 0z"></path>
                                        </svg>
                                        <span class="mt-mes"><?php echo esc_html__('More information', 'xstore'); ?></span>
                                    </span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="xstore-panel-grid-item-info">
                    <span class="xstore-panel-grid-item-name">
                         <?php echo esc_html( $plugin['name'] ); ?>
                    </span>
                        <span class="xstore-panel-grid-item-version">
                        <?php
                        $new_is_plugin_active = (
	                        ( ! empty( $instance->plugins[ $plugin['slug'] ]['is_callable'] ) && is_callable( $instance->plugins[ $plugin['slug'] ]['is_callable'] ) )
	                        || in_array( $instance->plugins[ $plugin['slug'] ]['file_path'], (array) get_option( 'active_plugins', array() ) )
	                        || is_plugin_active_for_network( $instance->plugins[ $plugin['slug'] ]['file_path'] )
                        );
                        $is_update_available = false;
                        if (
                                $new_is_plugin_active &&
                                $instance->is_plugin_installed( $plugin['slug'] ) &&
                                (false !== $instance->does_plugin_have_update( $plugin['slug'] )
                                 || ( isset($plugin['premium']) && $plugin['premium']  && version_compare( $plugin['latest_version'] ,  $plugin['version'], '>' ) ) ) )
                        {
	                        $is_update_available = true;
                        }

                        $update_text = '<span class="success" style="visibility: hidden; opacity: 0;">' . esc_html__( 'Latest version', 'xstore' ) . '</span>';

                        if ($is_update_available){
	                        $update_text = '<span class="success new-version-text">' . esc_html__( 'New update available', 'xstore' ) . '</span>';
                        }

                        if ( ! $plugin['version'] ) {
	                        echo '<span class="warning">' . esc_html__( 'Can not get plugin version', 'xstore' ) . '</span>';
                        } else {
	                        printf(
		                        '<span class="current-version">v.%s</span> %s <span class="hidden new-version">%s</span>',
		                        $plugin['version'],
		                        $update_text,
		                        $instance->does_plugin_have_update( $plugin['slug'] )
	                        );
                        }
                        ?>
                    </span>
                        <div class="xstore-panel-grid-item-control-wrapper">
                            <?php if ( $register_theme_redirect ) : ?>
                                <span
                                        class="xstore-panel-grid-item-control et-button et-button-green et-button-sm no-loader"
                                        data-redirect="<?php echo admin_url( 'admin.php?page=et-panel-welcome' ); ?>"
                                        data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"
                                        data-action="register_theme">
                                    <span class="dashicons dashicons-admin-network"></span>
                                    <span><?php esc_html_e( 'Register theme', 'xstore' ); ?></span>
                                </span>
                            <?php else: ?>
							<?php $btn_class = $install = ( ! $instance->is_plugin_installed( $plugin['slug'] ) ) ? '' : 'hidden'; ?>
                                <span
                                        class="xstore-panel-grid-item-control et-button et-button-green et-button-sm no-loader <?php echo esc_attr( $btn_class ); ?>"
                                        data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"
                                        data-action="install">
                                    <span class="dashicons dashicons-upload"></span>
                                    <span><?php esc_html_e( 'Install', 'xstore' ); ?></span>
                                </span>
                                <?php $btn_class = $activate = ( $instance->is_plugin_installed( $plugin['slug'] ) && ! $new_is_plugin_active ) ? '' : 'hidden';

                                    $btn_text = __( 'Activate', 'xstore' );
                                    if ( $instance->does_plugin_require_update( $plugin['slug'] ) ) {
                                        $btn_text = __( 'Update & Activate', 'xstore' );
                                    }
                                ?>
                                <span
                                        class="xstore-panel-grid-item-control et-button et-button-green et-button-sm no-loader <?php echo esc_attr( $btn_class ); ?>"
                                        data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"
                                        data-action="activate">
                                    <span class="dashicons dashicons-unlock"></span>
                                    <span><?php echo esc_html($btn_text); ?></span>
                                </span>

                                <?php $btn_class = ( $activate != '' && $install != '' && $is_update_available ) ? '' : 'hidden'; ?>
                                <span
                                        class="xstore-panel-grid-item-control et-button et-button-green et-button-sm no-loader <?php echo esc_attr( $btn_class ); ?>"
                                        data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"
                                        data-action="update">
                                    <span class="dashicons dashicons-update"></span>
                                    <span><?php esc_html_e( 'Update', 'xstore' ); ?></span>
                                </span>

                                <?php $btn_class = ( $activate != '' && $install != '' ) ? '' : 'hidden'; ?>
                                <span
                                        class="xstore-panel-grid-item-control et-button et-button-active et-button-sm no-loader <?php echo esc_attr( $btn_class ); ?>"
                                        data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"
                                        data-action="deactivate">
                                    <span class="dashicons dashicons-lock"></span>
                                    <span><?php esc_html_e( 'Deactivate', 'xstore' ); ?></span>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
		<?php endforeach; ?>
        <span class="hidden xstore-panel-grid-item-nonce"
              data-plugin-nonce="<?php echo wp_create_nonce( 'envato_setup_nonce' ); ?>"></span>
        <span class="hidden error-text"><?php esc_html_e( 'Oops it looks something went wrong. Please check your system requirements first. In case it will happened again, please, contact us 8theme.com', 'xstore' ); ?></span>
        <span class="hidden et_filesystem" data-filesystem="<?php echo esc_js($filesystem); ?>"></span>
        <?php
            $global_admin_class->get_search_no_found();
        ?>
    </div>
    <?php
        $global_admin_class->get_additional_panel_blocks('plugins');
    ?>
</div>