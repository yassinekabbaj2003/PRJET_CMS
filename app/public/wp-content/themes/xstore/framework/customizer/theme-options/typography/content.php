<?php
/**
 * The template created for displaying typography content options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'typography-content' => array(
			'name'       => 'typography-content',
			'title'      => esc_html__( 'Typography', 'xstore' ),
			'icon'       => 'dashicons-editor-spellcheck',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/typography-content' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
    $is_elementor = defined('ELEMENTOR_VERSION');
	$args = array();
	
	// Array of fields
	$args = array(
		'sfont'                 => array(
			'name'        => 'sfont',
			'type'        => 'typography',
			'settings'    => 'sfont',
			'label'       => esc_html__( 'Body typeface', 'xstore' ),
			'tooltip' => esc_html__( 'This sets the global font style for your entire website.', 'xstore'),
			'section'     => 'typography-content',
			'default'     => array(
				'font-family'    => 'Lato',
				'variant'        => 'regular',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#555',
				'text-transform' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
//				array(
//					'context'   => array('editor', 'front'),
//					'element' => 'body, .quantity input[type="number"], .page-wrapper',
//				),
				array(
					'context' => array( 'editor', 'front' ),
					'element' => 'body, body.yith-woocompare-popup',
				),
				array(
					'choice'   => 'color',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_font-color',
				),
			),
		),
		
		// paragraph_font_size 
		'paragraph_font_size'   => array(
			'name'      => 'paragraph_font_size',
			'type'      => 'slider',
			'settings'  => 'paragraph_font_size',
			'label'     => esc_html__( 'Paragraphs font-size (px)', 'xstore' ),
			'tooltip'   => esc_html__( 'This option allows you to globally change the size of the paragraphs on your website in pixels.', 'xstore' ),
			'section'   => 'typography-content',
			'default'   => 16,
			'choices'   => array(
				'min'  => '10',
				'max'  => '35',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
//				array(
//					'context'   => array('editor', 'front'),
//					'element' => 'p, .content-article, blockquote p, .testimonials-slider .swiper-container:not(.with-grid) blockquote, .posts-slider article .content-article, .posts-slider article .content-article p, #wcfmmp-store p',
//					'property' => 'font-size',
//					'units' => 'px'
//				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--p-font-size',
					'units'    => 'px'
				),
			)
		),
		
		// paragraph_line_height 
		'paragraph_line_height' => array(
			'name'      => 'paragraph_line_height',
			'type'      => 'slider',
			'settings'  => 'paragraph_line_height',
			'label'     => esc_html__( 'Paragraphs line-height (proportion)', 'xstore' ),
            'tooltip'   => esc_html__( 'This option allows you to globally change the line-height of the paragraphs on your website using a unitless value. Note: the value you set will be multiplied by the paragraph\'s font size; for example, 20px (font-size) x 1.6 (your value) = 32px (line-height).', 'xstore' ),
			'section'   => 'typography-content',
			'default'   => 1.6,
			'choices'   => array(
				'min'  => '1',
				'max'  => '2',
				'step' => '.01',
			),
			'transport' => 'auto',
			'output'    => array(
//				array(
//					'context'   => array('editor', 'front'),
//					'element' => 'p, .content-article, blockquote p, .testimonials-slider .swiper-container:not(.with-grid) blockquote, .posts-slider article .content-article, .posts-slider article .content-article p, #wcfmmp-store p',
//					'property' => 'line-height',
//				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--p-line-height',
				),
			)
		),
		
		'headings' => array(
			'name'        => 'headings',
			'type'        => 'typography',
			'settings'    => 'headings',
			'label'       => esc_html__( 'Headings', 'xstore' ),
			'tooltip' => esc_html__( 'This sets the font style of all headings on your website globally.', 'xstore') . '<br/>' .
                esc_html__('Note: By "headings", we mean texts on your website which are displayed using HTML tags such as h1, h2, h3, h4, h5, and h6.', 'xstore') . ( $is_elementor ? '<br/>' .
            esc_html__('Note: This does not apply to headings in Elementor widgets, as they have their own settings for this.', 'xstore') : ''),
			'section'     => 'typography-content',
			'default'     => array(
				'font-family'    => 'Lato',
				'variant'        => 'regular',
				// 'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '',
				'text-transform' => '',
			),
			'transport'   => 'postMessage',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' =>
						'.title h3,
					blockquote,
					.share-post .share-title,
					.sidebar-widget .tabs .tab-title,
					.widget-title,
					.related-posts .title span,
					.content-product .product-title a,
					.results-ajax-list .ajax-item-title,
					table.cart .product-details .product-title,
					.product_list_widget li .product-title a,
					.woocommerce table.wishlist_table .product-name a,
					.comment-reply-title,
					.et-tabs .vc_tta-title-text,
					.single-product-right .product-information-inner .product_title,
					.single-product-right .product-information-inner h1.title,
					.post-heading h2 a,
					.post-heading h2,
					.post-heading h1,
					.sidebar .recent-posts-widget .post-widget-item h4 a,
					.et-tabs-wrapper .tabs .accordion-title span,
					.woocommerce-cart-form table thead th',
				),
				array(
					'choice'  => 'font-family',
					'context' => array( 'editor', 'front' ),
					'element' => 'h1, h2, h3, h4, h5, h6, .products-title',
				),
				array(
					'choice'  => 'variant',
					'context' => array( 'editor', 'front' ),
					'element' => 'h1, h2, h3, h4, h5, h6, .products-title',
				),
				array(
					'choice'  => 'letter-spacing',
					'context' => array( 'editor', 'front' ),
					'element' => 'h1, h2, h3, h4, h5, h6, .products-title',
				),
				array(
					'choice'  => 'color',
					'context' => array( 'editor', 'front' ),
					'element' => 'h1, h2, h3, h4, h5, h6, .products-title',
				),
				array(
					'choice'  => 'text-transform',
					'context' => array( 'editor', 'front' ),
					'element' => 'h1, h2, h3, h4, h5, h6, .products-title',
				)
			),
		),
	);
	
	return array_merge( $fields, $args );
	
} );