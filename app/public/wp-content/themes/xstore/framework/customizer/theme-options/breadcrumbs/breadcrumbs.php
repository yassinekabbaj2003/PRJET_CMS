<?php
/**
 * The template created for displaying breadcrumb options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

// section breadcrumbs
add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'breadcrumbs' => array(
			'name'       => 'breadcrumbs',
			'title'      => esc_html__( 'Breadcrumbs', 'xstore' ),
			'icon'       => 'dashicons-carrot',
			'priority'   => $priorities['breadcrumbs'],
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/breadcrumbs' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $text_color_scheme, $paddings_empty, $padding_labels ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'breadcrumb_type' => array(
			'name'        => 'breadcrumb_type',
			'type'        => 'select',
			'settings'    => 'breadcrumb_type',
			'label'       => esc_html__( 'Style', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the breadcrumb style or disable them.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 'left2',
			'choices'     => array(
				'left2'   => esc_html__( 'Left inline', 'xstore' ),
				'default' => esc_html__( 'Align center', 'xstore' ),
				'left'    => esc_html__( 'Align left', 'xstore' ),
				'disable' => esc_html__( 'Disable', 'xstore' ),
			),
			// 'transport' => 'postMessage',
			// 'js_vars'     => array(
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'bc-type-left2',
			// 		'value' => 'left2'
			// 	),
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'bc-type-default',
			// 		'value' => 'default'
			// 	),
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'bc-type-left',
			// 		'value' => 'left'
			// 	),
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'none',
			// 		'value' => 'disable'
			// 	),
			// ),
		),
		
		'breadcrumb_title_tag' => array(
			'name'        => 'breadcrumb_title_tag',
			'type'        => 'select',
			'settings'    => 'breadcrumb_title_tag',
			'label'       => esc_html__( 'Title tag', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the main title tag for the breadcrumbs.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 'h1',
			'choices'     => array(
				'h1'   => esc_html__( 'h1', 'xstore' ),
				'h2'   => esc_html__( 'h2', 'xstore' ),
				'h3'   => esc_html__( 'h3', 'xstore' ),
				'h4'   => esc_html__( 'h4', 'xstore' ),
				'h5'   => esc_html__( 'h5', 'xstore' ),
				'p'    => esc_html__( 'Paragraph', 'xstore' ),
				'span' => esc_html__( 'Span', 'xstore' ),
				'div'  => esc_html__( 'Div', 'xstore' ),
			),
		),
		
		'breadcrumb_category_title_tag' => array(
			'name'        => 'breadcrumb_category_title_tag',
			'type'        => 'select',
			'settings'    => 'breadcrumb_category_title_tag',
			'label'       => esc_html__( 'Categories title tag', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the main title tag for the breadcrumbs on category and tag post archives.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 'h1',
			'choices'     => array(
				'h1'   => esc_html__( 'h1', 'xstore' ),
				'h2'   => esc_html__( 'h2', 'xstore' ),
				'h3'   => esc_html__( 'h3', 'xstore' ),
				'h4'   => esc_html__( 'h4', 'xstore' ),
				'h5'   => esc_html__( 'h5', 'xstore' ),
				'p'    => esc_html__( 'Paragraph', 'xstore' ),
				'span' => esc_html__( 'Span', 'xstore' ),
				'div'  => esc_html__( 'Div', 'xstore' ),
			),
		),
		
		'cart_special_breadcrumbs' => array(
			'name'        => 'cart_special_breadcrumbs',
			'type'        => 'toggle',
			'settings'    => 'cart_special_breadcrumbs',
			'label'       => esc_html__( 'Special breadcrumb on the cart, checkout, and order pages.', 'xstore' ),
			'tooltip' => esc_html__( 'Turn on the feature to show step-by-step breadcrumb on the cart, checkout, and order pages.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 1,
		),
		
		'breadcrumb_bg' => array(
			'name'        => 'breadcrumb_bg',
			'type'        => 'background',
			'settings'    => 'breadcrumb_bg',
			'label'       => esc_html__( 'Background', 'xstore' ),
			'tooltip' => esc_html__( 'This controls the style of the background in the breadcrumb area.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => array(
				'background-color'      => '',
				'background-image'      => '',
				'background-repeat'     => '',
				'background-position'   => '',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.page-heading',
				),
                array(
                    'context' => array( 'editor', 'front' ),
                    'element' => '.breadcrumb-trail',
                ),
			),
		),
		
		'breadcrumb_color' => array(
			'name'        => 'breadcrumb_color',
			'type'        => 'select',
			'settings'    => 'breadcrumb_color',
			'label'       => esc_html__( 'Text color', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the color scheme for the breadcrumbs.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 'dark',
			'choices'     => $text_color_scheme,
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.page-heading',
					'function' => 'toggleClass',
					'class'    => 'bc-color-dark',
					'value'    => 'dark'
				),
				array(
					'element'  => '.page-heading',
					'function' => 'toggleClass',
					'class'    => 'bc-color-white',
					'value'    => 'white'
				),
			),
		),
		
		'breadcrumb_effect' => array(
			'name'        => 'breadcrumb_effect',
			'type'        => 'select',
			'settings'    => 'breadcrumb_effect',
			'label'       => esc_html__( 'Breadcrumb effect', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the animation effect for the breadcrumb.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 'mouse',
			'choices'     => array(
				'none'        => esc_html__( 'None', 'xstore' ),
				'mouse'       => esc_html__( 'Parallax on mouse move', 'xstore' ),
				'text-scroll' => esc_html__( 'Text animation on scroll', 'xstore' ),
			),
			
			// 'transport' => 'postMessage',
			// 'js_vars'     => array(
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'bc-effect-none',
			// 		'value' => 'none'
			// 	),
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'bc-effect-mouse',
			// 		'value' => 'mouse'
			// 	),
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'bc-effect-text-scroll',
			// 		'value' => 'text-scroll'
			// 	),
			// ),
		),
		
		'breadcrumb_padding' => array(
			'name'        => 'breadcrumb_padding',
			'type'        => 'dimensions',
			'settings'    => 'breadcrumb_padding',
			'label'       => esc_html__( 'Padding', 'xstore' ),
			'tooltip' => sprintf( esc_html__( 'Set the padding for the breadcrumb area. Leave it blank to use the default values. You can also configure your breadcrumb settings in the %1s if the %2s setting is enabled.', 'xstore' ), '<span class="et_edit" data-parent="header_overlap" data-section="header_overlap_content_separator" style="text-decoration: underline;">' . esc_html__( 'Breadcrumb settings', 'xstore' ) . '</span>', '<span class="et_edit" data-parent="header_overlap" data-section="header_overlap_content_separator" style="var(--customizer-dark-color, #222);">' . esc_html__('Header overlap', 'xstore') . '</span>' ),
			'section'     => 'breadcrumbs',
			'transport'   => 'auto',
			'default'     => $paddings_empty,
			'choices'     => array(
				'labels' => $padding_labels,
                'descriptions' => $padding_labels,
			),
			'output'      => array(
				array(
					'choice'   => 'padding-top',
					'element'  => '.page-heading, .et-header-overlap .page-heading',
					'property' => 'padding-top',
				),
                array(
                    'choice'   => 'padding-top',
                    'element'  => '.breadcrumb-trail .page-heading .back-history',
                    'property' => 'top',
                ),
				array(
					'choice'   => 'padding-bottom',
					'element'  => '.page-heading, .et-header-overlap .page-heading',
					'property' => 'padding-bottom',
				),
				array(
					'choice'   => 'padding-left',
					'element'  => '.page-heading, .et-header-overlap .page-heading, .breadcrumb-trail .page-heading',
					'property' => 'padding-left',
				),
				array(
					'choice'   => 'padding-right',
					'element'  => '.page-heading, .et-header-overlap .page-heading, .breadcrumb-trail .page-heading',
					'property' => 'padding-right',
				),
			),
		),
		
		'bc_breadcrumbs_font' => array(
			'name'        => 'bc_breadcrumbs_font',
			'type'        => 'typography',
			'settings'    => 'bc_breadcrumbs_font',
			'label'       => esc_html__( 'Typeface', 'xstore' ),
            'tooltip'     => esc_html__( 'This controls the typeface settings of the breadcrumb steps.', 'xstore'),
			'section'     => 'breadcrumbs',
			'default'     => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'color'          => '',
				'letter-spacing' => '',
				'text-transform' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' =>
						'.page-heading .breadcrumbs,
					.page-heading .woocommerce-breadcrumb,
					.page-heading .bbp-breadcrumb,
					.page-heading .a-center,
					.page-heading .title,
					.page-heading .breadcrumb_last,
					.page-heading a,
					.page-heading .span-title,
					[class*=" paged-"] .page-heading.bc-type-left2 .span-title,
					.bbp-breadcrumb-current,
					.page-heading .breadcrumbs a,
					.page-heading .woocommerce-breadcrumb a,
					.page-heading .bbp-breadcrumb a'
				),
			),
		
		),
		
		'bc_title_font' => array(
			'name'        => 'bc_title_font',
			'type'        => 'typography',
			'settings'    => 'bc_title_font',
			'label'       => esc_html__( 'Title typeface', 'xstore' ),
            'tooltip'     => esc_html__( 'This controls the typeface settings of the breadcrumb title.', 'xstore'),
			'section'     => 'breadcrumbs',
			'default'     => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'color'          => '',
				'letter-spacing' => '',
				'text-transform' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' =>
						'.page-heading.bc-type-left2 .title,
					.page-heading.bc-type-left .title,
					.page-heading.bc-type-default .title,
					.page-heading .breadcrumb_last,
					[class*=" paged-"] .page-heading .span-title:last-of-type,
					[class*=" paged-"] .page-heading.bc-type-left2 .span-title:last-of-type,
					.single-post .page-heading.bc-type-left2 #breadcrumb a:last-of-type,
					.bbp-breadcrumb-current',
				),
			),
		
		),
		
		'bc_page_numbers' => array(
			'name'        => 'bc_page_numbers',
			'type'        => 'toggle',
			'settings'    => 'bc_page_numbers',
			'label'       => esc_html__( 'Disable pages steps on product archives', 'xstore' ),
			'tooltip' => esc_html__( 'Turn on the option to disable page steps on the product archive pages. For example, "Page 2" will not be included in the breadcrumb steps.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 0,
		),
		
		'return_to_previous' => array(
			'name'        => 'return_to_previous',
			'type'        => 'toggle',
			'settings'    => 'return_to_previous',
			'label'       => esc_html__( '"Return to previous page" link', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to display the "Return to Previous Page" link.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 1,
		),
		
		'bc_return_font' => array(
			'name'            => 'bc_return_font',
			'type'            => 'typography',
			'settings'        => 'bc_return_font',
			'label'           => esc_html__( '"Return to previous page" typeface', 'xstore' ),
			'tooltip'     => esc_html__( 'This controls the typeface settings of the "Return to previous page" link.', 'xstore'),
			'section'         => 'breadcrumbs',
			'default'         => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'color'          => '',
				'letter-spacing' => '',
				'text-transform' => '',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.page-heading .back-history, .page-heading .breadcrumbs .back-history, .page-heading .woocommerce-breadcrumb .back-history, .page-heading .bbp-breadcrumb .back-history, .single-post .page-heading.bc-type-left2 #breadcrumb a:last-of-type',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'return_to_previous',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );