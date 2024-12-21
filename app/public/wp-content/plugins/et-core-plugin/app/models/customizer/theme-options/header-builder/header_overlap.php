<?php
/**
 * The template created for displaying header overlap options
 *
 * @version 1.0.1
 * @since   1.4.1
 * last changes in 1.5.4
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'header_overlap' => array(
			'name'        => 'header_overlap',
			'title'       => esc_html__( 'Header overlap & transparent', 'xstore-core' ),
			'description' => sprintf(esc_html__( 'If you want to use header with the overlap & transparent effect for certain pages use %s option', 'xstore-core' ), '<a href="#" class="et_open-multiple">'.esc_html__('Multiple headers', 'xstore-core').'</a>'),
			'panel'       => 'header-builder',
			'icon'        => 'dashicons-archive',
			'type'        => 'kirki-lazy',
			'dependency'  => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/header_overlap', function ( $fields ) use ( $separators, $strings ) {
	$args = array();
	
	// Array of fields
	$args = array(
		// content separator
		'header_overlap_content_separator'      => array(
			'name'     => 'header_overlap_content_separator',
			'type'     => 'custom',
			'settings' => 'header_overlap_content_separator',
			'section'  => 'header_overlap',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// header_overlap
		'header_overlap_et-desktop'             => array(
			'name'        => 'header_overlap_et-desktop',
			'type'        => 'toggle',
			'settings'    => 'header_overlap_et-desktop',
			'label'       => esc_html__( 'Header overlap', 'xstore-core' ),
			'tooltip'  => sprintf(esc_html__( 'Use %1s to make this options work only on specific pages you chose', 'xstore-core' ),
                '<a href="https://www.youtube.com/watch?v=BpeXfzNwkOc&list=PLMqMSqDgPNmDu3kYqh-SAsfUqutW3ohlG&index=5" target="_blank" rel="nofollow">'.esc_html__('conditions', 'xstore-core').'</a>'),
			'section'     => 'header_overlap',
			'default'     => '0',
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => 'body',
					'function' => 'toggleClass',
					'class'    => 'et_b_dt_header-overlap',
					'value'    => true
				),
				array(
					'element'  => 'body',
					'function' => 'toggleClass',
					'class'    => 'et_b_dt_header-not-overlap',
					'value'    => false
				),
			),
		),
		
		// header_overlap
		'header_overlap_et-mobile'              => array(
			'name'        => 'header_overlap_et-mobile',
			'type'        => 'toggle',
			'settings'    => 'header_overlap_et-mobile',
			'label'       => esc_html__( 'Header overlap', 'xstore-core' ),
            'tooltip'  => sprintf(esc_html__( 'Use %1s to make this options work only on specific pages you chose', 'xstore-core' ),
                '<a href="https://www.youtube.com/watch?v=BpeXfzNwkOc&list=PLMqMSqDgPNmDu3kYqh-SAsfUqutW3ohlG&index=5" target="_blank" rel="nofollow">'.esc_html__('conditions', 'xstore-core').'</a>'),
			'section'     => 'header_overlap',
			'default'     => '0',
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => 'body',
					'function' => 'toggleClass',
					'class'    => 'et_b_mob_header-overlap',
					'value'    => true
				),
				array(
					'element'  => 'body',
					'function' => 'toggleClass',
					'class'    => 'et_b_mob_header-not-overlap',
					'value'    => false
				),
			),
		),
		
		// breadcrumb_padding
		'overlap_breadcrumb_padding_et-desktop' => array(
			'name'        => 'overlap_breadcrumb_padding_et-desktop',
			'type'        => 'dimensions',
			'settings'    => 'overlap_breadcrumb_padding_et-desktop',
			'label'       => esc_html__( 'Breadcrumbs padding (overlap only)', 'xstore-core' ),
			'tooltip'  => sprintf( esc_html__( 'Set the padding for the breadcrumb area. Leave it blank to use the default values. You can also configure your breadcrumb settings in the %1s', 'xstore-core' ), '<span class="et_edit" data-parent="breadcrumbs" data-section="breadcrumb_padding" style="text-decoration: underline;">' . esc_html__( 'Breadcrumbs settings', 'xstore-core' ) . '</span>' ),
			'section'     => 'header_overlap',
			'default'     => array(
				'padding-top'    => '13em',
				'padding-right'  => '',
				'padding-bottom' => '5em',
				'padding-left'   => '',
			),
			'choices'     => array(
				'labels' => $strings['label']['paddings'],
                'descriptions' => $strings['description']['paddings'],
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'      => 'padding-top',
					'context'     => array( 'editor', 'front' ),
					'element'     => '.et_b_dt_header-overlap .page-heading',
					'property'    => 'padding-top',
					'media_query' => '@media only screen and (min-width: 993px)',
				),
				array(
					'choice'      => 'padding-bottom',
					'context'     => array( 'editor', 'front' ),
					'element'     => '.et_b_dt_header-overlap .page-heading',
					'property'    => 'padding-bottom',
					'media_query' => '@media only screen and (min-width: 993px)',
				),
				array(
					'choice'      => 'padding-left',
					'context'     => array( 'editor', 'front' ),
					'element'     => '.et_b_dt_header-overlap .page-heading',
					'property'    => 'padding-left',
					'media_query' => '@media only screen and (min-width: 993px)',
				),
				array(
					'choice'      => 'padding-right',
					'context'     => array( 'editor', 'front' ),
					'element'     => '.et_b_dt_header-overlap .page-heading',
					'property'    => 'padding-right',
					'media_query' => '@media only screen and (min-width: 993px)',
				),
			),
		),
		
		// breadcrumb_padding
		'overlap_breadcrumb_padding_et-mobile'  => array(
			'name'        => 'overlap_breadcrumb_padding_et-mobile',
			'type'        => 'dimensions',
			'settings'    => 'overlap_breadcrumb_padding_et-mobile',
			'label'       => esc_html__( 'Custom Breadcrumbs padding (overlap only)', 'xstore-core' ),
			'tooltip'  => sprintf( esc_html__( 'Set the padding for the breadcrumb area on mobile device. Leave it blank to use the default values. You can also configure your breadcrumb settings in the %1s', 'xstore-core' ), '<span class="et_edit" data-parent="breadcrumbs" data-section="breadcrumb_padding" style="text-decoration: underline;">' . esc_html__( 'Breadcrumbs settings', 'xstore-core' ) . '</span>' ),
			'section'     => 'header_overlap',
			'default'     => array(
				'padding-top'    => '11em',
				'padding-right'  => '',
				'padding-bottom' => '1.2em',
				'padding-left'   => '',
			),
			'choices'     => array(
                'labels' => $strings['label']['paddings'],
                'descriptions' => $strings['description']['paddings'],
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'      => 'padding-top',
					'context'     => array( 'editor', 'front' ),
					'element'     => '.et_b_mob_header-overlap .page-heading',
					'property'    => 'padding-top',
					'media_query' => '@media only screen and (max-width: 992px)',
				),
				array(
					'choice'      => 'padding-bottom',
					'context'     => array( 'editor', 'front' ),
					'element'     => '.et_b_mob_header-overlap .page-heading',
					'property'    => 'padding-bottom',
					'media_query' => '@media only screen and (max-width: 992px)',
				),
				array(
					'choice'      => 'padding-left',
					'context'     => array( 'editor', 'front' ),
					'element'     => '.et_b_mob_header-overlap .page-heading',
					'property'    => 'padding-left',
					'media_query' => '@media only screen and (max-width: 992px)',
				),
				array(
					'choice'      => 'padding-right',
					'context'     => array( 'editor', 'front' ),
					'element'     => '.et_b_mob_header-overlap .page-heading',
					'property'    => 'padding-right',
					'media_query' => '@media only screen and (max-width: 992px)',
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
