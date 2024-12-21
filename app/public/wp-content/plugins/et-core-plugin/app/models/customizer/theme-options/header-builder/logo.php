<?php
/**
 * The template created for displaying header logo element options
 *
 * @version 1.0.2
 * @since   1.4.0
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'logo' => array(
			'name'       => 'logo',
			'title'      => esc_html__( 'Logo', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-format-image',
			//'priority'       => 160,
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/logo', function ( $fields ) use ( $separators, $strings, $choices, $box_models ) {
	$args = array();
	// Array of fields
	$args = array(
		// content separator
		'logo_content_separator'       => array(
			'name'     => 'logo_content_separator',
			'type'     => 'custom',
			'settings' => 'logo_content_separator',
			'section'  => 'logo',
			'default'  => $separators['content'],
			'priority' => 1,
		),
		
		// logo_img
		'logo_img_et-desktop'          => array(
			'name'        => 'logo_img_et-desktop',
			'type'        => 'image',
			'settings'    => 'logo_img_et-desktop',
			'label'       => esc_html__( 'Site logo', 'xstore-core' ),
			'tooltip' => esc_html__( 'Upload an image of the logo for the header section.', 'xstore-core' ),
			'section'     => 'logo',
			'default'     => '',
			'choices'     => array(
				'save_as' => 'array',
			),
			'priority'    => 2,
		),
		
		// retina_logo_img
		'retina_logo_img_et-desktop'   => array(
			'name'        => 'retina_logo_img_et-desktop',
			'type'        => 'image',
			'settings'    => 'retina_logo_img_et-desktop',
			'label'       => esc_html__( 'Retina logo', 'xstore-core' ),
			'tooltip' => esc_html__( 'Upload the retina image of the logo for the header section.', 'xstore-core' ) . '<br/>' .
                esc_html__('Tip: Most of the newest devices have Retina displays. To ensure a positive user experience, it is essential to include Retina images when designing a website.', 'xstore-core'),
			'section'     => 'logo',
			'default'     => '',
			'choices'     => array(
				'save_as' => 'array',
			),
			'priority'    => 3,
		),
		
		// go_to_sticky_logo 
		'go_to_section_headers_sticky' => array(
			'name'        => 'go_to_section_headers_sticky',
			'type'        => 'custom',
			'label'       => $strings['label']['sticky_logo'],
			'tooltip' => $strings['description']['sticky_logo'],
			'settings'    => 'go_to_section_headers_sticky',
			'section'     => 'logo',
			'default'     => '<span class="et_edit" data-parent="headers_sticky" data-section="header_sticky_content_separator" style="padding: 5px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__('Configure sticky logo', 'xstore-core') . '</span>',
			'priority'    => 4,
		),
		
		// style separator
		'logo_style_separator'         => array(
			'name'     => 'logo_style_separator',
			'type'     => 'custom',
			'settings' => 'logo_style_separator',
			'section'  => 'logo',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// logo_align
		'logo_align_et-desktop'        => array(
			'name'      => 'logo_align_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'logo_align_et-desktop',
			'label'     => $strings['label']['alignment'],
            'tooltip' => $strings['description']['alignment'],
			'section'   => 'logo',
			'default'   => 'center',
			'choices'   => $choices['alignment'],
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-logo.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'align-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.et_b_header-logo.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'align-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.et_b_header-logo.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'align-end',
					'value'    => 'end'
				),
			)
		),
		
		// logo_align
		'logo_align_et-mobile'         => array(
			'name'      => 'logo_align_et-mobile',
			'type'      => 'radio-buttonset',
			'settings'  => 'logo_align_et-mobile',
			'label'     => $strings['label']['alignment'],
            'tooltip' => $strings['description']['alignment'],
			'section'   => 'logo',
			'default'   => 'center',
			'choices'   => $choices['alignment'],
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-logo.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'mob-align-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.et_b_header-logo.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'mob-align-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.et_b_header-logo.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'mob-align-end',
					'value'    => 'end'
				),
			)
		),
		
		// logo_width
		'logo_width_et-desktop'        => array(
			'name'      => 'logo_width_et-desktop',
			'type'      => 'slider',
			'settings'  => 'logo_width_et-desktop',
			'label'     => esc_html__( 'Logo width (px)', 'xstore-core' ),
            'tooltip' => sprintf(esc_html__('This setting controls the width of the logo image in pixels, with the default being %s.', 'xstore-core'), '140px'),
			'section'   => 'logo',
			'default'   => 140,
			'choices'   => array(
				'min'  => '20',
				'max'  => '1000',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-logo.et_element-top-level img',
					'property' => 'width',
					'units'    => 'px'
				)
			)
		),
		
		// logo_width
		'logo_width_et-mobile'         => array(
			'name'      => 'logo_width_et-mobile',
			'type'      => 'slider',
			'settings'  => 'logo_width_et-mobile',
			'label'     => esc_html__( 'Logo width (px)', 'xstore-core' ),
            'tooltip' => sprintf(esc_html__('This setting controls the width of the logo image in pixels, with the default being %s.', 'xstore-core'), '320px'),
			'section'   => 'logo',
			'default'   => 320,
			'choices'   => array(
				'min'  => '20',
				'max'  => '1000',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-logo.et_element-top-level img',
					'property' => 'width',
					'units'    => 'px'
				)
			)
		),
		
		'logo_box_model_et-desktop' => array(
			'name'        => 'logo_box_model_et-desktop',
			'settings'    => 'logo_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'tooltip' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'logo',
			'default'     => $box_models['empty'],
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_b_header-logo.et_element-top-level'
				)
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.et_b_header-logo.et_element-top-level' )
		),
		
		'logo_box_model_et-mobile'            => array(
			'name'        => 'logo_box_model_et-mobile',
			'settings'    => 'logo_box_model_et-mobile',
			'label'       => $strings['label']['computed_box'],
			'tooltip' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'logo',
			'default'     => $box_models['empty'],
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .et_b_header-logo.et_element-top-level'
				)
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.mobile-header-wrapper .et_b_header-logo.et_element-top-level' )
		),
		
		// logo_border
		'logo_border_et-desktop'              => array(
			'name'      => 'logo_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'logo_border_et-desktop',
			'label'     => $strings['label']['border_style'],
            'tooltip' => $strings['description']['border_style'],
			'section'   => 'logo',
			'default'   => 'none',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-logo.et_element-top-level',
					'property' => 'border-style'
				)
			)
		),
		
		// logo_border_color_custom
		'logo_border_color_custom_et-desktop' => array(
			'name'        => 'logo_border_color_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'logo_border_color_custom_et-desktop',
			'label'       => $strings['label']['border_color'],
			'tooltip' => $strings['description']['border_color'],
			'section'     => 'logo',
			'default'     => '#e1e1e1',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-logo.et_element-top-level',
					'property' => 'border-color',
				),
			),
		),
	);
	
	return array_merge( $fields, $args );
	
} );
