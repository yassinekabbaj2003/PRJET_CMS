<?php
/**
 * The template created for displaying 404 page options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

// section general-page-not-found
add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'customizer-user-preferences' => array(
			'name'       => 'customizer-user-preferences',
			'title'      => esc_html__( 'User preferences', 'xstore' ),
			'icon'       => 'dashicons-admin-appearance',
			'priority'   => $priorities['preferences'],
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/customizer-user-preferences' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
        'customizer_ui_theme' => array(
            'name'        => 'customizer_ui_theme',
            'type'        => 'select',
            'settings'    => 'customizer_ui_theme',
            'label'       => esc_html__( 'UI theme', 'xstore' ),
            'tooltip' => esc_html__( 'Set light or dark mode, or use Auto Detect to sync it with your OS setting.', 'xstore' ),
            'section'     => 'customizer-user-preferences',
            'default'     => 'auto',
            'choices'     => array(
                'auto' => esc_html__( 'Auto Detect', 'xstore' ),
                'dark'     => esc_html__( 'Dark', 'xstore' ),
                'light'    => esc_html__( 'White', 'xstore' ),
            ),
        ),

        'customizer_ui_width' => array(
            'name'        => 'customizer_ui_width',
            'type'        => 'slider',
            'settings'    => 'customizer_ui_width',
            'label'       => esc_html__( 'Panel width (%)', 'xstore' ),
            'tooltip' => esc_html__( 'This setting controls the width of the Customizer panel in percents, with the default being 21%.', 'xstore' ),
            'section'     => 'customizer-user-preferences',
            'default'     => 21,
            'choices'     => array(
                'min'  => 10,
                'max'  => 30,
                'step' => 1,
            ),
            'transport'   => 'auto',
            'output'      => array(
                array(
                    'context'       => array( 'editor', 'front' ),
                    'element'       => 'body',
                    'property'      => '--customizer-ui-width',
                    'value_pattern' => '$%'
                ),
            ),
        ),

        'customizer_options_descriptions' => array(
            'name'        => 'customizer_options_descriptions',
            'type'        => 'select',
            'settings'    => 'customizer_options_descriptions',
            'label'       => esc_html__( 'Option description', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the type of each option description.', 'xstore' ),
            'section'     => 'customizer-user-preferences',
            'default'     => 'tooltip',
            'choices'     => array(
                'tooltip' => esc_html__( 'Tooltip', 'xstore' ),
                'description'     => esc_html__( 'Description', 'xstore' ),
            ),
        ),

        'customizer_options_columns' => array(
            'name'        => 'customizer_options_columns',
            'type'        => 'select',
            'settings'    => 'customizer_options_columns',
            'label'       => esc_html__( 'Option column', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the amount of each option\' columns.', 'xstore' ),
            'section'     => 'customizer-user-preferences',
            'default'     => 'two',
            'choices'     => array(
                'one' => esc_html__( 'One column', 'xstore' ),
                'two'     => esc_html__( 'Two columns', 'xstore' ),
            ),
        ),

        'customizer_ui_zoom' => array(
            'name'        => 'customizer_ui_zoom',
            'type'        => 'slider',
            'settings'    => 'customizer_ui_zoom',
            'label'       => esc_html__( 'Content zoom', 'xstore' ),
            'tooltip'          => esc_html__( 'This option allows you to zoom in or out on the content of the Customizer options.','xstore'),
            'section'     => 'customizer-user-preferences',
            'default'     => 1,
            'choices'     => array(
                'min'  => 0.7,
                'max'  => 1.5,
                'step' => .1,
            ),
            'transport'   => 'auto',
            'output'      => array(
                array(
                    'context'       => array( 'editor', 'front' ),
                    'element'       => 'body',
                    'property'      => '--customizer-ui-content-zoom',
                    'value_pattern' => '$%'
                ),
            ),
        ),
	);
	
	
	return array_merge( $fields, $args );
} );