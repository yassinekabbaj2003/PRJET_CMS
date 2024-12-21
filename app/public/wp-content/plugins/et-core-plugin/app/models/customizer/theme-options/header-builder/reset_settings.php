<?php
/**
 * The template created for displaying header panel
 *
 * @version 1.0.0
 * @since   1.4.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'header_builder_reset' => array(
			'name'        => 'header_builder_reset',
			'title'       => esc_html__( 'Reset header builder', 'xstore-core' ),
			'icon'        => 'dashicons-image-rotate',
			'panel'       => 'header-builder',
			'type'        => 'kirki-lazy',
			'dependency'  => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/header_builder_reset', function ( $fields ) use ( $sep_style, $index ) {
	$args = array();
	
	// Array of fields
	$args = array(
        // content separator
        'header_builder_reset_separator' => array(
            'name' => 'header_builder_reset_separator',
            'type' => 'custom',
            'settings' => 'header_builder_reset_separator',
            'section' => 'header_builder_reset',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-editor-removeformatting"></span> <span style="padding-inline-start: 5px;">' . esc_html__('Reset header builder', 'xstore-core') . '</span></div>',
        ),
		'et_placeholder_header_builder_reset' => array(
			'name'     => 'et_placeholder_header_builder_reset',
			'type'     => 'custom',
			'settings' => 'et_placeholder_header_builder_reset',
			'label'    => esc_html__( 'Reset elements', 'xstore-core' ),
            'tooltip'  => esc_html__( 'This option will erase your pre-constructed header elements. Note: it will not reset the settings of elements such as colors, sizes, texts etc..', 'xstore-core' ),
			'section'  => 'header_builder_reset',
			'default'  => '<span id="etheme-reset-header-builder" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); text-align: center;">' . esc_html__( 'Reset elements', 'xstore-core' ) . '</span>',
			'priority' => 10,
		)
	);
	
	return array_merge( $fields, $args );
	
} );