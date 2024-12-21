<?php
/**
 * Template "Templates overrides" for 8theme dashboard.
 *
 * @since 9.3.5
 * @version 1.0.0
 */

?>
<div class="et_popup-theme-templates_overrides et_panel-popup-inner with-scroll">
<?php // echo '<div class="image-block">'.$settings['logo'].'</div>' ?>
    <div class="steps-block-content">
        <p class="et-message et-info"><?php echo sprintf(esc_html__('We need to identify which templates need to be updated, create a backup of the old templates, and then restore any customizations. To do this, go to the %1s -> %2s. Scroll to the bottom of the page, where there is a list of templates that have been overridden by your child theme, along with a warning message indicating that they need to be updated.', 'xstore'),
            '<strong>'.esc_html__('XStore Control Panel', 'xstore').'</strong>',
            '<strong>'.esc_html__('Server Requirements', 'xstore').'</strong>'); ?></p>

        <p><?php echo sprintf(esc_html__('For example, the templates %1s and %2s are outdated. ', 'xstore'), '<strong><em>header.php</em></strong>', '<strong><em>woocommerce/content-product.php</em></strong>'); ?></p>
        <p><?php esc_html__('To update them:', 'xstore'); ?></p>
        <ol>
            <li><?php echo esc_html__('Save a backup of the outdated template.', 'xstore'); ?></li>
            <li><?php echo sprintf(esc_html__('Copy the default template from %1s and paste it in the child-theme folder found at %2s', 'xstore'),
                    '<span class="mtips mtips-top mtips-lg"><strong>'.esc_html__('wp-content/themes/xstore/[path-to-the-template]', 'xstore').'</strong><span class="mt-mes">'.get_template_directory().'/'.esc_html__('[path-to-the-template]', 'xstore').'</span></span>',
                    '<span class="mtips mtips-top mtips-lg"><strong>'.esc_html__('wp-content/themes/xstore-child/[path-to-the-child-template]', 'xstore').'</strong><span class="mt-mes">'.get_stylesheet_directory().'/'.esc_html__('[path-to-the-child-template]', 'xstore').'</span></span>'); ?></li>
            <li><?php echo esc_html__('Open the template with a text editor such as Sublime, Visual Code, BBEdit, or Notepad++ and replicate any changes that were made to the previous template in the new, updated template file.', 'xstore'); ?></li>
        </ol>
        <p><?php echo esc_html__('We understand that this can be time-consuming, so we try to avoid changing Theme templates, but sometimes it is necessary to break backward compatibility.', 'xstore'); ?></p>
    </div>
</div>
<?php
