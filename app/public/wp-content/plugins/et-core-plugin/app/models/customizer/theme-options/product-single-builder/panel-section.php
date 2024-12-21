<?php
/**
 * The template created for displaying single product builder panel
 *
 * @version 1.0.0
 * @since   1.4.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {

    $checked = get_option( 'etheme_single_product_builder', false );

    $checked = ( $checked ) ? 'checked' : '';

	$args = array(
		'single_product_builder' => array(
			'name'        => 'single_product_builder',
			'title'       => esc_html__( 'Single Product Builder', 'xstore-core' ),
//            'description' => sprintf(esc_html__('%sImportant Notice:%s%s Starting from %sJanuary 1, 2025%s, the current single product builder will be deprecated. But don\'t worry! We\'re moving this feature to a new plugin for better performance.%s To keep your website running smoothly, we recommend switching to our new single product builder, which works with Elementor and offers a better experience.%s %sClick here%s to access the new single product builder.%s Thanks for your cooperation!', 'xstore-core'),
//                '<span style="color: red">', '</span>', '<br/>', '<span style="color: red">', '</span>', '<br/>', '<br/>', '<a href="'.admin_url( 'admin.php?page=et-panel-theme-builders' ).'" target="_blank">', '</a>', '<br/>'),
            'panel'       => 'woocommerce',
			'icon'        => 'dashicons-align-left',
			'type'        => 'kirki-lazy',
			'dependency'  => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/single_product_builder', function ( $fields ) {

    $checked = get_option( 'etheme_single_product_builder', false );

    $checked = ( $checked ) ? 'checked' : '';
	$args = array();
	
	// Array of fields
	$args = array(
		'et_placeholder_single_product_builder' => array(
			'name'     => 'et_placeholder_single_product_builder',
			'type'     => 'custom',
			'settings' => 'et_placeholder_single_product_builder',
			'label'    => false,
			'section'  => 'single_product_builder',
			'default'  => '</label><span class="customize-control-kirki-toggle"> <label for="etheme-disable-default-single-product"> <span class="customize-control-title">' . esc_html__( 'Enable single product builder', 'xstore-core' ) .
                    '<span class="tooltip-wrapper"><span class="tooltip-trigger" data-setting="et_placeholder_single_product_builder"><span class="dashicons dashicons-editor-help"></span></span>'.
                    '<span class="tooltip-content" data-setting="et_placeholder_single_product_builder">'.esc_html__('With the ability to enable or disable the Single Product Builder option, you can take complete control of your online store and provide a shopping experience that meets the specific needs of your business and your customers.', 'xstore-core').
                    '</span></span>'.
                '</span> <input class="screen-reader-text" id="etheme-disable-default-single-product" name="etheme-disable-default-single-product" type="checkbox" ' . $checked . '><span class="switch" data-text-on="'.esc_attr__( 'On', 'xstore-core' ).'" data-text-off="'.esc_attr__( 'Off', 'xstore-core' ).'"></span></label><input type="hidden" name="nonce_etheme-switch_default" value="'.wp_create_nonce( 'etheme_switch_default' ).'"></span>',
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
