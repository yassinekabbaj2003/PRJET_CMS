<?php
    /**
     * The template for displaying header waitlist block
     *
     * @since   1.4.0
     * @version 1.0.4
     * last changes in 2.2.4
     */
 ?>

<?php 

    global $et_waitlist_icons, $et_builder_globals;

    $element_options = array();
    $element_options['built_in_waitlist'] = get_theme_mod('xstore_waitlist', false) && class_exists('WooCommerce');
    $element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
    $element_options['attributes'] = array();

    if ( $element_options['is_customize_preview'] )
    $element_options['attributes'] = array(
        'data-title="' . esc_html__( 'Waitlist', 'xstore-core' ) . '"',
        'data-element="'.( $element_options['built_in_waitlist'] ? 'waitlist' : 'xstore-waitlist').'"'
    );
    $html = '';

    if ( !$element_options['built_in_waitlist'] ) : ?>
        <div class="et_element et_b_header-waitlist" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
            <span class="flex flex-wrap full-width align-items-center currentColor">
                <span class="flex-inline justify-content-center align-items-center flex-nowrap">
                    <?php esc_html_e( 'Waitlist ', 'xstore-core' ); ?> 
                    <span class="mtips" style="text-transform: none;">
                        <i class="et-icon et-exclamation" style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
                        <span class="mt-mes"><?php echo current_user_can( 'edit_theme_options' ) ? sprintf(
                            /* translators: %s: URL to header image configuration in Customizer. */
                                __( 'Please, enable <a style="text-decoration: underline" href="%s" target="_blank">Waitlist</a>.', 'xstore-core'),
                                admin_url( 'customize.php?autofocus[section]=xstore-waitlist' )) :
                                __( 'Please, enable Waitlist.', 'xstore-core'); ?></span>
                    </span>
                </span>
            </span>
        </div>
    <?php return; 
    endif;

    $element_options['waitlist_style'] = get_theme_mod( 'waitlist_style_et-desktop', 'type1' );
    $element_options['waitlist_style'] = apply_filters('waitlist_style', $element_options['waitlist_style']);
    
    $element_options['waitlist_type_et-desktop'] = get_theme_mod( 'waitlist_icon_et-desktop', 'type1' );
    $element_options['waitlist_type_et-desktop'] = apply_filters('waitlist_icon', $element_options['waitlist_type_et-desktop']);

    if ( !get_theme_mod('bold_icons', 0) ) { 
        $element_options['waitlist_icons'] = $et_waitlist_icons['light'];
    }
    else {
        $element_options['waitlist_icons'] = $et_waitlist_icons['bold'];
    }

    $element_options['icon_custom'] = get_theme_mod('waitlist_icon_custom_svg_et-desktop', '');
    $element_options['icon_custom'] = apply_filters('waitlist_icon_custom', $element_options['icon_custom']);
    $element_options['icon_custom'] = isset($element_options['icon_custom']['id']) ? $element_options['icon_custom']['id'] : '';

    if ( $element_options['waitlist_type_et-desktop'] == 'custom' ) {
        if ( $element_options['icon_custom'] != '' ) {
	        $element_options['waitlist_icons']['custom'] = etheme_get_svg_icon($element_options['icon_custom']);
        }
        else {
            $element_options['waitlist_icons']['custom'] = $element_options['waitlist_icons']['type1'];
        }
    }
    
    $element_options['waitlist_icon'] = $element_options['waitlist_icons'][$element_options['waitlist_type_et-desktop']];

    $element_options['waitlist_quantity_et-desktop'] = get_theme_mod( 'waitlist_quantity_et-desktop', '1' );
    $element_options['waitlist_quantity_position_et-desktop'] = ( $element_options['waitlist_quantity_et-desktop'] ) ? ' et-quantity-' . get_theme_mod( 'waitlist_quantity_position_et-desktop', 'right' ) : '';

    $element_options['waitlist_content_position_et-desktop'] = get_theme_mod( 'waitlist_content_position_et-desktop', 'right' );

    $element_options['waitlist_content_type_et-desktop'] = get_theme_mod( 'waitlist_content_type_et-desktop', 'dropdown' );

    $element_options['waitlist_dropdown_position_et-desktop'] = get_theme_mod( 'waitlist_dropdown_position_et-desktop', 'right' );

    if ( $et_builder_globals['in_mobile_menu'] ) {
        $element_options['waitlist_style'] = 'type1';
        $element_options['waitlist_quantity_et-desktop'] = false;
        $element_options['waitlist_quantity_position_et-desktop'] = '';
        $element_options['waitlist_content_type_et-desktop'] = 'none';
    }

    $element_options['not_waitlist_page'] = true;
    $waitlist_page_id = get_theme_mod('xstore_waitlist_page', '');
    if ( ! empty( $waitlist_page_id ) && is_page( $waitlist_page_id ) || (isset($_GET['et-waitlist-page']) && is_account_page()) ) {
        $element_options['not_waitlist_page'] = false;
    }

    // filters 
    $element_options['etheme_mini_waitlist_content_type'] = apply_filters('etheme_mini_waitlist_content_type', $element_options['waitlist_content_type_et-desktop']);

    $element_options['etheme_mini_waitlist_content'] = $element_options['etheme_mini_waitlist_content_type'] != 'none';
    $element_options['etheme_mini_waitlist_content'] = apply_filters('etheme_mini_waitlist_content', $element_options['etheme_mini_waitlist_content']);

    $element_options['etheme_mini_waitlist_content_position'] = apply_filters('etheme_mini_waitlist_content_position', $element_options['waitlist_content_position_et-desktop']);

    $element_options['waitlist_off_canvas'] = $element_options['etheme_mini_waitlist_content_type'] == 'off_canvas';
    $element_options['waitlist_off_canvas'] = apply_filters('waitlist_off_canvas', $element_options['waitlist_off_canvas']);

    // header waitlist classes 
    $element_options['wrapper_class'] = ' flex align-items-center';
    if ( $et_builder_globals['in_mobile_menu'] ) $element_options['wrapper_class'] .= ' justify-content-inherit';
    $element_options['wrapper_class'] .= ' waitlist-' . $element_options['waitlist_style'];
    $element_options['wrapper_class'] .= ' ' . $element_options['waitlist_quantity_position_et-desktop'];
    $element_options['wrapper_class'] .= ( $element_options['waitlist_off_canvas'] ) ? ' et-content-' . $element_options['etheme_mini_waitlist_content_position'] : '';
    $element_options['wrapper_class'] .= ( !$element_options['waitlist_off_canvas'] && $element_options['waitlist_dropdown_position_et-desktop'] != 'custom' ) ? ' et-content-' . $element_options['waitlist_dropdown_position_et-desktop'] : '';
    $element_options['wrapper_class'] .= ( $element_options['waitlist_off_canvas'] && $element_options['etheme_mini_waitlist_content'] && $element_options['not_waitlist_page']) ? ' et-off-canvas et-off-canvas-wide et-content_toggle' : ' et-content-dropdown et-content-toTop';
    $element_options['wrapper_class'] .= ( $element_options['waitlist_quantity_et-desktop'] && $element_options['waitlist_icon'] == '' ) ? ' static-quantity' : '';
    $element_options['wrapper_class'] .= ( $et_builder_globals['in_mobile_menu'] ) ? '' : ' et_element-top-level';

    if ( $element_options['waitlist_off_canvas'] || $element_options['is_customize_preview'] ) {
        // could be via default wp
	    if ( function_exists('etheme_enqueue_style')) {
		    etheme_enqueue_style( 'off-canvas' );
	    }
    }
    
    if ( $element_options['etheme_mini_waitlist_content_type'] || $element_options['is_customize_preview'] ) {
	    if ( function_exists('etheme_enqueue_style')) {
		    etheme_enqueue_style( 'cart-widget' );
	    }
    }
?>

<div class="et_element et_b_header-waitlist <?php echo $element_options['wrapper_class']; ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
    <?php echo header_waitlist_callback(); ?>
</div>

<?php unset($element_options);