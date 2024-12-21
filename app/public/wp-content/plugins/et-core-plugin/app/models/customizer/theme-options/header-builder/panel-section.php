<?php
/**
 * The template created for displaying header builder panel
 *
 * @version 1.0.0
 * @since   5.3.2
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'header-builder' => array(
			'name'        => 'header-builder',
			'title'       => esc_html__( 'Header Builder', 'xstore-core' ),
			'icon'        => 'dashicons-align-left',
			'type'        => 'kirki-lazy',
			'dependency'  => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/header-builder', function ( $fields ) {

    $checked = !get_option( 'etheme_disable_customizer_header_builder', false );

    $checked = ( $checked ) ? 'checked' : '';
	$args = array();
	
	// Array of fields
    $args['disable_customizer_header_builder'] = array(
        'name' => 'disable_customizer_header_builder',
        'type' => 'custom',
        'settings' => 'disable_customizer_header_builder',
        'section' => 'header-builder',
        'default' => '<span class="customize-control-title">' . esc_html__( 'Enable Customizer header builder', 'xstore-core' ) . '</span><span class="customize-control-kirki-toggle flex"><input class="screen-reader-text" id="etheme-disable-default-header" name="etheme-disable-default-header" type="checkbox"><span class="switch" data-text-on="'.esc_attr__( 'On', 'xstore-core' ).'" data-text-off="'. esc_attr__( 'Off', 'xstore-core' ). '"></span><input type="hidden" name="nonce_etheme-switch_default" value="'.wp_create_nonce( 'etheme_switch_default' ).'"><input type="hidden" name="nonce_etheme_header-presets" value="'.wp_create_nonce( 'etheme_header-presets' ).'"></span>',
    );
//	$args = array(
//		'et_placeholder_customizer_header_builder' => array(
//			'name'     => 'et_placeholder_customizer_header_builder',
//			'type'     => 'custom',
//			'settings' => 'et_placeholder_customizer_header_builder',
//			'label'    => false,
//			'section'  => 'header-builder',
//			'default'  => '<span class="et_edit" data-parent="general" data-section="disable_customizer_header_builder" style="text-decoration: underline;">'.esc_html__('Enable Customizer Header builder', 'xstore-core').'</span>',
//		),
//	);
	
	return array_merge( $fields, $args );
	
} );
