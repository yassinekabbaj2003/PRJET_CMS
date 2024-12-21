<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Demos" for 8theme dashboard.
 *
 * @since   6.0.2
 * @version 1.0.4
 */

$class = '';

$versions = etheme_get_demo_versions();

if ( ! $versions ){
	echo '<p class="et-message et-error" style="width: 100%;">' .
	     esc_html__('We are unable to connect to the 8Theme API to retrieve the list of versions.', 'xstore') .
	     '</p>';
	return;
}

$pages = array_filter($versions, function( $el ) {
	if (isset($el['type'])){
		return $el['type'] == 'page';
	}
});

$demos = array_filter($versions, function( $el ) {
    if (isset($el['type'])){
	    return $el['type'] == 'demo';
    }
});

$installed_versions = array();
$installed_version = get_option('etheme_current_version');

$core_active = class_exists('ETC\App\Controllers\Admin\Import');

if ( $installed_version ) {
	$installed_versions[] = json_decode($installed_version)->name;
}
$is_remove = false;
$et_imported_data = get_option('et_imported_data', array());

if (count($et_imported_data)){
	foreach ($et_imported_data as $type){
		if (count($type)){
			$is_remove = true;
            break; // limit to stop if fount
		}
	}
	if (!$is_remove){
		delete_option('etheme_current_version');
		$installed_versions = array();
		$installed_version = false;
    }
}

$global_admin_class = EthemeAdmin::get_instance();

?>
<div class="etheme-import-section <?php echo esc_attr( $class ); ?>">

            <h1 class="etheme-page-title etheme-page-title-type-2"><?php esc_html_e('Import Demos', 'xstore'); ?>
                <span class="et-counter" data-postfix="+">130+</span>
                <?php if ($is_remove) :
                    $remove_classes = array('et-button et-button-active');
                    $remove_attr = array();
                    $popup_requirements = array();
                    $popup_requirements['heading'] = esc_html__('Delete content', 'xstore');
                    $popup_requirements['feature'] = 'remove_installed_content';
                    if ( !$core_active ) {
                        $remove_classes[] = 'trigger-xstore-control-plugins-popup';
                        $popup_requirements['plugins'] = array('et-core-plugin');
                        $remove_attr[] = 'data-details="'.esc_attr(wp_json_encode($popup_requirements)).'"';
                    }
                    else {
                        $remove_classes[] = 'et_remove-installed-content';
                    }?>
                    <span class="<?php echo implode(' ', $remove_classes); ?>" <?php echo implode(' ', $remove_attr); ?>>
                    <span class="dashicons dashicons-trash"></span>
                    <?php esc_html_e('Delete content', 'xstore'); ?>
                    <?php $global_admin_class->get_loader(); ?>
                </span>
                <?php endif; ?>
            </h1>

        <p>
            <?php esc_html_e('Importing a pre-built website is the easiest way to set up your theme. It allows you to quickly edit existing content, rather than starting from scratch.', 'xstore'); ?>
        </p>
        <div class="xstore-panel-grid-header">
            <?php
                $global_admin_class->get_filters_form(array(
                    'all' => esc_html__('All', 'xstore'),
                    'popular' => esc_html__('Popular', 'xstore'),
                    'catalog' => esc_html__('Catalog', 'xstore'),
                    'one-page' => esc_html__('One page', 'xstore'),
                    'corporate' => esc_html__('Corporate', 'xstore'),
                ), array('type' => 'versions-filter') );
            ?>

            <ul class="et-filters et-filters-style-default et-filters-builders">
                <li class="et-filter engine-filter active" data-filter="all"><?php esc_html_e('All', 'xstore'); ?></li>
                <li class="et-filter engine-filter" data-filter="wpb">
                    <svg width="20px" height="20px" fill="currentColor" viewBox="0 0 66 50" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
                            <path d="M51.3446356,9.04135214 C46.8606356,8.68235214 44.9736356,9.78835214 42.8356356,10.0803521 C45.0046356,11.2153521 47.9606356,12.1793521 51.5436356,11.9703521 C48.2436356,13.2663521 42.8866356,12.8233521 39.1886356,10.5643521 C38.2256356,9.97535214 37.2136356,9.04535214 36.4556356,8.30235214 C33.4586356,5.58335214 31.2466356,0.401352144 21.6826356,0.0183521443 C9.68663559,-0.456647856 0.464635589,8.34735214 0.0156355886,19.6453521 C-0.435364411,30.9433521 8.92563559,40.4883521 20.9226356,40.9633521 C21.0806356,40.9713521 21.2386356,40.9693521 21.3946356,40.9693521 C24.5316356,40.7853521 28.6646356,39.5333521 31.7776356,37.6143521 C30.1426356,39.9343521 24.0316356,42.3893521 20.8506356,43.1673521 C21.1696356,45.6943521 22.5216356,46.8693521 23.6306356,47.6643521 C26.0896356,49.4243521 29.0086356,46.9343521 35.7406356,47.0583521 C39.4866356,47.1273521 43.3506356,48.0593521 46.4746356,49.8083521 L49.7806356,38.2683521 C58.1826356,38.3983521 65.1806356,32.2053521 65.4966356,24.2503521 C65.8176356,16.1623521 59.9106356,9.72335214 51.3446356,9.04135214 L51.3446356,9.04135214 Z" id="Fill-41"></path>
                        </g>
                    </svg>
                    <span><?php esc_html_e('WPBakery', 'xstore'); ?></span>

                </li>
                <li class="et-filter engine-filter" data-filter="elementor">

                    <svg height="20px" version="1.1" viewBox="0 0 512 512" width="20px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="_x31_09-elementor"><g><path d="M462.999,26.001H49c-12.731,0-22.998,10.268-22.998,23v413.998c0,12.732,10.267,23,22.998,23    h413.999c12.732,0,22.999-10.268,22.999-23V49.001C485.998,36.269,475.731,26.001,462.999,26.001" style="fill:var(--et_admin_dark-color, #333);"/><rect height="204.329" style="fill:#FFFFFF;" width="40.865" x="153.836" y="153.836"/><rect height="40.866" style="fill:#FFFFFF;" width="122.7" x="235.566" y="317.299"/><rect height="40.865" style="fill:#FFFFFF;" width="122.7" x="235.566" y="235.566"/><rect height="40.865" style="fill:#FFFFFF;" width="122.7" x="235.566" y="153.733"/></g></g><g id="Layer_1"/></svg>
                    <span><?php esc_html_e('Elementor', 'xstore'); ?></span>
                </li>
            </ul>

            <?php
                $global_admin_class->get_search_form('versions', esc_html__( 'Search for versions', 'xstore' ));
            ?>
        </div>

        <div class="xstore-panel-grid-wrapper import-demos">
			<?php
			foreach ( $demos as $key => $version ) : ?>
				<?php
				$imported = in_array($key, $installed_versions);

				if ( ! isset( $version['filter'] ) ) {
					$version['filter'] = 'all';
				}

				if ( isset( $version['engine'] ) ) {
					$version['filter'] = $version['filter'] . ' ' . implode( " ", $version['engine'] );
				} else {
					$version['filter'] = $version['filter'] . ' wpb';
				}
				$engine = (isset( $version['engine'] )) ? $version['engine'] : array();

				$required = false;

				if ( isset($version['required']) ){
					if ( isset($version['required']['theme']) && version_compare( ETHEME_THEME_VERSION, $version['required']['theme'], '<' )){
						$required['theme'] = $version['required']['theme'];
					}

					if (isset($version['required']['plugin']) && defined('ET_CORE_VERSION') && version_compare( ET_CORE_VERSION, $version['required']['plugin'], '<' )){
						$required['plugin'] = $version['required']['plugin'];
					}

					if ($required){
						$required = json_encode($required);
					}
				}

				if (count( $engine ) > 1){
					$engine = count( $engine );
				}elseif (count( $engine ) == 1 && isset($engine[0])){
					$engine = $engine[0];
				} else {
					$engine = 0;
				}


                $item_classes = array(
                    'xstore-panel-grid-item',
                    'version-preview',
                    'version-preview-'.$key,
                );

                if ( $imported ) {
                    $item_classes[] = 'xstore-panel-grid-item-active';
                    $item_classes[] = 'imported';
                    $item_classes[] = 'prioritized';
                }
                else {
                    $item_classes[] = 'not-imported';
                }

                if ( strpos($version['filter'], 'elementor' ) > 0 ) {

                }
                else {
                    $item_classes[] = 'et-hide';
                }
				?>
                <div
                        class="<?php echo implode(' ', $item_classes); ?>"
                        data-filter="<?php echo esc_js($version['filter']); ?>"
                        data-active-filter="all"
                >
                    <div class="xstore-panel-grid-item-content">
                        <div class="xstore-panel-grid-item-image version-screenshot">
                            <a href="<?php echo esc_url( $version['preview_url'] ); ?>" <?php echo (isset($version['preview_elementor_url'])) ? 'data-href="'.esc_url( $version['preview_elementor_url'] ).'"' : ''; ?> target="_blank">
                                <img
                                    class="lazyload lazyload-simple et-lazyload-fadeIn"
                                    src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/placeholder-350x268.png"
                                    data-src="<?php echo apply_filters('etheme_protocol_url', ETHEME_BASE_URL . 'import/xstore-demos/' . esc_attr( $key ) . '/screenshot.jpg'); ?>"
                                    data-old-src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/placeholder-350x268.png"
                                    alt="<?php echo esc_attr( $key ); ?>">
                            </a>
                            <div class="xstore-panel-grid-item-labels">
                                <?php if ( isset($version['badge']) ) { ?>
                                    <span class="xstore-panel-grid-item-label active-label"><?php echo esc_html($version['badge']); ?></span>
                                <?php } ?>
                            </div>
                            <?php if ( $imported ) : ?>
                                <span class="xstore-panel-grid-item-checkbox">
                                    <span class="mtips mtips-left inverse no-arrow">
                                        <span class="dashicons dashicons-yes"></span>
                                        <span class="mt-mes"><?php echo esc_html__('Activated', 'xstore'); ?></span>
                                    </span>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="xstore-panel-grid-item-info">
                            <span class="xstore-panel-grid-item-name version-title">
                                 <?php echo esc_html( $version['title'] ); ?>
                            </span>
                            <div class="xstore-panel-grid-item-control-wrapper">
                                <?php if ($imported && $is_remove) : ?>
                                    <span
                                            class="xstore-panel-grid-item-control button-import-version et-button et-button-sm"
                                            data-version="<?php echo esc_attr( $key ); ?>"
                                            data-engine="<?php echo esc_attr($engine); ?>"
                                            data-required="<?php echo esc_attr($required); ?>">
                                        <?php $global_admin_class->get_loader(); ?>
                                        <span class="dashicons dashicons-upload"></span>
                                        <span><?php esc_html_e('Import', 'xstore'); ?></span>
                                    </span>
                                    <span class="xstore-panel-grid-item-control et-button et-button-sm et-button-active et_remove-installed-content">
                                        <?php $global_admin_class->get_loader(); ?>
                                        <span class="dashicons dashicons-trash"></span>
                                        <span><?php esc_html_e('Delete', 'xstore'); ?></span>
                                    </span>
                                <?php else: ?>
                                    <a href="<?php echo esc_url( $version['preview_url'] ); ?>" <?php echo (isset($version['preview_elementor_url'])) ? 'data-href="'.esc_url( $version['preview_elementor_url'] ).'"' : ''; ?> target="_blank" class="xstore-panel-grid-item-control button-preview et-button et-button-sm no-loader">
                                        <span class="dashicons dashicons-visibility"></span>
                                        <span><?php esc_html_e('Preview', 'xstore'); ?></span>
                                    </a>
                                    <span
                                            class="xstore-panel-grid-item-control button-import-version et-button et-button-sm et-button-green"
                                            data-version="<?php echo esc_attr( $key ); ?>"
                                            data-engine="<?php echo esc_attr($engine); ?>"
                                            data-required="<?php echo esc_attr($required); ?>">
                                        <?php $global_admin_class->get_loader(); ?>
                                        <span class="dashicons dashicons-upload"></span>
                                        <span><?php esc_html_e('Import', 'xstore'); ?></span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endforeach; ?>
            <?php
                $global_admin_class->get_search_no_found();
            ?>
        </div>

        <?php
            $global_admin_class->get_additional_panel_blocks('demos');
        ?>
</div>
<?php if ( isset( $_GET['after_activate'] ) ): ?>
	<?php $out = ''; ?>

	<?php if ( ! class_exists( 'ETC\App\Controllers\Admin\Import' ) ) : ?>

		<?php
		// $out .= '<p class="et_installing-base-plugin et-message et-info">' . esc_html__( 'Please wait installing base plugin', 'xstore' ) . '</p>';
		// $out .= '<p class="et_installed-base-plugin hidden et-message et-success">' . esc_html__( 'plugin intalled', 'xstore' ) . '</p>';
		?>
        <span class="hidden et_plugin-nonce" data-plugin-nonce="<?php echo wp_create_nonce( 'envato_setup_nonce' ); ?>"></span>
	<?php endif; ?>

	<?php $out .= '<div class="et_all-success">
            <br>
            <img src="' . ETHEME_BASE_URI . ETHEME_CODE .'assets/images/' . 'success-icon.png" alt="installed icon" style="margin-bottom: -7px;"><br/><br/>
            <h3 class="et_step-title text-center">' . esc_html__('Theme successfully activated!', 'xstore') . '</h3>
            <p>' . esc_html__('Now you have access to lifetime updates, top-notch 24/7 live support, and much more features.', 'xstore') . '</p>
        </div>
        <span class="et-button et-button-green no-loader et_close-popup" onclick="window.location=\'' . admin_url( 'admin.php?page=et-panel-demos' ) . '\'">'.esc_html__('ok', 'xstore').'</span><br><br>' ?>
	<?php echo '<div class="et_popup-activeted-content hidden">' . $out . '</div>'; ?>
<?php endif ?>
