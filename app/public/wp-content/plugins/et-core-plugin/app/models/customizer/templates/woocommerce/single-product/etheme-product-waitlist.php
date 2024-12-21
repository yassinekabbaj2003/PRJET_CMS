<?php 
/**
 * The template for single product waitlist
 *
 * @since   1.5.0
 * @version 1.0.0
 */

$element_options = array();
if ( get_theme_mod('xstore_waitlist', false) ) {
    $element_options['built_in_waitlist_instance'] = XStoreCore\Modules\WooCommerce\XStore_Waitlist::get_instance();
    $custom_icon = false;
    $custom_options = array(
        'is_single' => true,
        'is_spb' => true,
        'has_tooltip' => get_theme_mod('product_waitlist_tooltip', false),
//        'redirect_on_remove' => get_theme_mod('product_waitlist_redirect_on_remove', false),
        // keep inheritance from global options
//        'add_text' => esc_html(get_theme_mod('product_waitlist_label_add_to_waitlist', esc_html__('Add to waitlist', 'xstore-core'))),
//        'remove_text' => esc_html(get_theme_mod('product_waitlist_label_browse_waitlist', esc_html__('Delete from waitlist', 'xstore-core'))),
        'only_icon' => get_theme_mod('product_waitlist_only_icon', false),
    );
    switch ( get_theme_mod( 'product_waitlist_icon_et-desktop', 'type1' ) ) {
        case 'type2':
            $custom_options['custom_icon'] = false;
            $custom_options['add_icon_class'] = 'et-bell';
            $custom_options['remove_icon_class'] = 'et-bell-o';
            break;
        case 'custom':
            $icon_custom = get_theme_mod( 'product_waitlist_icon_custom_svg_et-desktop', '' );
            $icon_custom = isset( $icon_custom['id'] ) ? $icon_custom['id'] : '';
            if ( $icon_custom != '' ) {
                $custom_options['custom_icon'] = str_replace(array('fill="black"', 'stroke="black"'), array('fill="currentColor"', 'stroke="currentColor"'), etheme_get_svg_icon( $icon_custom ));
                $custom_options['add_icon_class'] = false;
                $custom_options['remove_icon_class'] = false;
            }
            else {
                $custom_options['custom_icon'] = false;
                $custom_options['add_icon_class'] = 'et-cart-unavailable';
                $custom_options['remove_icon_class'] = false;
            }
            break;
        case 'none':
            $custom_options['show_icon'] = false;
            $custom_options['custom_icon'] = false;
            $custom_options['add_icon_class'] = false;
            $custom_options['remove_icon_class'] = false;
            break;
    }

    if ( get_query_var('is_mobile', false) ) {
        $custom_options['only_icon'] = false;
        $custom_options['has_tooltip'] = false;
    }

    $element_options['built_in_waitlist_instance']->print_button(null, $custom_options);
    unset($element_options);
    return;
}
else { ?>
    <span class="flex flex-wrap align-items-center">
			<span class="flex-inline justify-content-center align-items-center flex-nowrap">
            <?php esc_html_e( 'Waitlist', 'xstore-core' ); ?>
                <span class="mtips" style="text-transform: none;">
                    <i class="et-icon et-exclamation" style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
                    <span class="mt-mes">
                        <?php echo current_user_can( 'edit_theme_options' ) ? sprintf(
                        /* translators: %s: URL to header image configuration in Customizer. */
                            __( 'Please, enable <a style="text-decoration: underline" href="%s" target="_blank">Waitlist</a>.', 'xstore-core'),
                            admin_url( 'customize.php?autofocus[section]=xstore-waitlist' )) :
                            __( 'Please, enable Waitlist.', 'xstore-core'); ?>
                    </span>
                </span>
            </span>
            <br/>
        </span>
<?php }