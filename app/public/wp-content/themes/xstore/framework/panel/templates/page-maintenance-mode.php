<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Maintenance mode" for 8theme dashboard.
 *
 * @since   8.1.2
 * @version 1.0.0
 */

$maintenance_mode_page_options = array();

$maintenance_mode_page_options['is_enabled'] = get_option('etheme_maintenance_mode', false);
?>

    <h2 class="etheme-page-title etheme-page-title-type-2"><?php echo esc_html__('Maintenance Mode', 'xstore'); ?></h2>
    <p>
		<?php
            esc_html_e('Maintenance mode allows you to display a user-friendly notice to your visitors instead of a broken site during website maintenance. Build a sleek maintenance page that will be shown to your site visitors. Only registered users with sufficient privileges can view the front end. Switch it off when you\'re ready to relaunch the site.', 'xstore');
        ?>
    </p>
    <p>
        <?php
        echo sprintf(esc_html__('You can create the maintenance page from scratch in Dashboard > Pages > %s. Choose "Maintenance" from the "Template" list under "Page attributes." Alternatively, you can import the page from our demo using the XStore Control Panel > Import demos > %s demos.', 'xstore'),
            '<a href="'.admin_url('post-new.php?post_type=page').'" target="_blank">'.esc_html__('Add new', 'xstore').'</a>',
            '<a href="'.admin_url('admin.php?page=et-panel-demos&s=coming+soon').'" target="_blank">'.esc_html__('Coming Soon', 'xstore').'</a>');
        ?>
    </p>
    <p>
        <label class="et-panel-option-switcher<?php if ( $maintenance_mode_page_options['is_enabled']) { ?> switched<?php } ?>" for="et_maintenance_mode">
            <input type="checkbox" id="et_maintenance_mode" name="et_maintenance_mode" <?php if ( $maintenance_mode_page_options['is_enabled']) { ?>checked<?php } ?>>
            <span></span>
        </label>
    </p>

<?php if ( $maintenance_mode_page_options['is_enabled'] ) :
    $access_key = get_option('etheme_maintenance_mode_access_key', false);
    $access_key_default = 'bypass_maintenance'; ?>
    <p class="et-message et-info">
        <?php echo sprintf(esc_html__( 'You can bypass Maintenance mode by using a specific GET parameter. For example, add the \'%s\' key to the URL like this: %s. This will allow you to access the site without triggering Maintenance mode.', 'xstore' ), '<strong>'.$access_key_default.'</strong>', '<strong>'.add_query_arg($access_key_default, '', home_url()).'</strong>'); ?>
    </p>
    <form>
        <p>
            <label for="maintenance_access_key"><?php echo esc_html__('Access key for maintenance mode', 'xstore'); ?></label>
        </p>
        <p>
            <input id="maintenance_access_key" placeholder="<?php echo esc_attr($access_key_default); ?>" name="maintenance_access_key" type="text" value="<?php echo esc_attr($access_key); ?>">
            <input class="etheme-maintenance-access-key-save et-button no-loader" type="submit" value="<?php echo esc_attr('Save', 'xstore'); ?>">
            <input type="hidden" name="nonce_update_maintenance-settings" value="<?php echo wp_create_nonce( 'etheme_update_maintenance-settings' ); ?>">
        </p>
        <?php if ( !!$access_key ) : ?>
            <p><?php echo sprintf(esc_html__('Your bypass Maintenance URL: %s', 'xstore'), '<strong>'.add_query_arg($access_key, '', home_url()).'</strong>'); ?></p>
        <?php endif; ?>
    </form>

    <p class="et-message">
        <?php echo esc_html__('Your maintenance mode is activated. Add maintenance page by clicking the button below.', 'xstore'); ?>
    </p>
    <a href="<?php echo admin_url( 'edit.php?post_type=page' ); ?>" class="et-button et-button-green no-loader" target="_blank">
        <?php esc_html_e('Go to Pages', 'xstore'); ?>
    </a>
<?php endif; ?>

<?php unset($maintenance_mode_page_options); ?>