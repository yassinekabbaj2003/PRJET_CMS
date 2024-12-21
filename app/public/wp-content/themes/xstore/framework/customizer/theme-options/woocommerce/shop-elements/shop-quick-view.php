<?php
/**
 * The template created for displaying shop quick view options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'shop-quick-view' => array(
			'name'       => 'shop-quick-view',
			'title'      => esc_html__( 'Quick view', 'xstore' ),
            'description' => esc_html__('Shopping just got easier with this handy feature that allows you to view product details without having to leave the main page. With a simple click, you can see all the important information, including product images, prices, and descriptions.', 'xstore'),
			'panel'      => 'shop-elements',
			'icon'       => 'dashicons-external',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
	
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-quick-view' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		'quick_view' => array(
			'name'        => 'quick_view',
			'type'        => 'toggle',
			'settings'    => 'quick_view',
			'label'       => esc_html__( 'Enable quick view', 'xstore' ),
			'tooltip' => esc_html__( 'With "Quick View", customers are more likely to make a purchase as they can quickly access product information without disrupting their browsing flow. Enable the Quick View option today and provide your customers with an efficient and convenient shopping experience!', 'xstore' ),
			'section'     => 'shop-quick-view',
			'default'     => 1,
		),
		
		'quick_view_content_type' => array(
			'name'            => 'quick_view_content_type',
			'type'            => 'radio-buttonset',
			'settings'        => 'quick_view_content_type',
			'label'           => esc_html__( 'Type', 'xstore' ),
            'tooltip' => esc_html__('Choose between two different types of "Quick View" displays, either a sleek popup or a convenient off-canvas view, to enhance the shopping experience for your customers.', 'xstore'),
			'section'         => 'shop-quick-view',
			'default'         => 'popup',
			'multiple'        => 1,
			'choices'         => array(
				'popup'      => esc_html__( 'Popup', 'xstore' ),
				'off_canvas' => esc_html__( 'Off-Canvas', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'quick_view_content_position' => array(
			'name'            => 'quick_view_content_position',
			'type'            => 'radio-buttonset',
			'settings'        => 'quick_view_content_position',
			'label'           => esc_html__( 'Position', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the position for off-canvas content.', 'xstore' ) . '<br/>' .
                esc_html__( 'Note: this option will work only if content type is set to Off-Canvas.', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => 'right',
			'multiple'        => 1,
			'choices'         => array(
				'left'  => esc_html__( 'Left side', 'xstore' ),
				'right' => esc_html__( 'Right side', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'quick_view_content_type',
					'operator' => '==',
					'value'    => 'off_canvas',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et-quick-view-canvas .et-close',
					'function' => 'toggleClass',
					'class'    => 'full-right',
					'value'    => 'right'
				),
				array(
					'element'  => '.et-quick-view-canvas .et-close',
					'function' => 'toggleClass',
					'class'    => 'full-left',
					'value'    => 'left'
				),
				array(
					'element'  => 'et-quick-view-canvas',
					'function' => 'toggleClass',
					'class'    => 'et-content-right',
					'value'    => 'right'
				),
				array(
					'element'  => '.et-quick-view-canvas',
					'function' => 'toggleClass',
					'class'    => 'et-content-left',
					'value'    => 'left'
				),
			),
		),
		
		'quick_dimentions' => array(
			'name'            => 'quick_dimentions',
			'type'            => 'dimensions',
			'settings'        => 'quick_dimentions',
			'label'           => esc_html__( 'Custom dimensions', 'xstore' ),
            'tooltip'   => esc_html__( 'Configure the dimensions for the quick view popup.', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => array(
				'width'  => '',
				'height' => '',
			),
			'choices'         => array(
                'labels' => array(
                    'width'  => esc_html__( 'Width', 'xstore' ),
                    'height' => esc_html__( 'Height', 'xstore' ),
                ),
                'descriptions' => array(
                    'width'  => esc_html__( 'Width', 'xstore' ),
                    'height' => esc_html__( 'Height', 'xstore' ),
                ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'quick_view_content_type',
					'operator' => '==',
					'value'    => 'popup',
				),
			)
		),
		
		'quick_images' => array(
			'name'            => 'quick_images',
			'type'            => 'select',
			'settings'        => 'quick_images',
			'label'           => esc_html__( 'Images type', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose how to display product images in the "Quick View" content.', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => 'slider',
			'choices'         => array(
				'slider' => esc_html__( 'Slider', 'xstore' ),
				'single' => esc_html__( 'Single', 'xstore' ),
				'grid'   => esc_html__( 'Horizontal scroll', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'quick_view_canvas_width' => array(
			'name'            => 'quick_view_canvas_width',
			'type'            => 'slider',
			'settings'        => 'quick_view_canvas_width',
			'label'           => esc_html__( 'Off-canvas width (px)', 'xstore' ),
            'tooltip' => esc_html__( 'This setting controls the width of the off-canvas content in pixels, with the default being 400px.', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => 400,
			'choices'         => array(
				'min'  => '200',
				'max'  => '1000',
				'step' => '1',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et-quick-view-canvas.et-off-canvas-wide > .et-mini-content',
					'property' => 'max-width',
					'units'    => 'px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et-quick-view-canvas .swiper-container',
					'property'      => 'max-width',
					'value_pattern' => 'calc($px - 40px)',
				)
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'quick_view_content_type',
					'operator' => '==',
					'value'    => 'off_canvas',
				),
			)
		),
		
		'quick_image_height' => array(
			'name'            => 'quick_image_height',
			'type'            => 'etheme-text',
			'settings'        => 'quick_image_height',
			'label'           => esc_html__( 'Images height', 'xstore' ),
			'tooltip'     => esc_html__( 'This sets the minimum height for images in "Quick View" content. Note: Leave it empty to not set any minimum height and let the browser automatically adjust the minimum height of the images according to the window\'s height and width.', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'quick_view_content_type',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
			//			'transport' => 'auto',
			//			'output' => array(
			//				array(
			//					'element' => '.et-quick-view-canvas.et-off-canvas-wide .swiper-grid .main-images',
			//					'property' => 'height',
			//					'units' => 'px'
			//				),
			//			)
		),
		
		'quick_view_layout' => array(
			'name'            => 'quick_view_layout',
			'type'            => 'select',
			'settings'        => 'quick_view_layout',
			'label'           => esc_html__( 'Layout', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the design of the content for the "Quick view" popup.', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => 'default',
			'choices'         => array(
				'default'  => esc_html__( 'Default', 'xstore' ),
				'centered' => esc_html__( 'Centered', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'quick_view_content_type',
					'operator' => '==',
					'value'    => 'popup',
				),
			)
		),
		
		'quick_view_content' => array(
			'name'            => 'quick_view_content',
			'type'            => 'sortable',
			'settings'        => 'quick_view_content',
			'label'           => esc_html__( 'Elements', 'xstore' ),
			'tooltip'     => esc_html__( 'You can easily revamp the content of the product in the "Quick View" window by turning on or off the essential elements you need. Our drag and drop feature allows you to rearrange them in a snap.', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => array(
				'quick_gallery',
				'quick_product_name',
				'quick_price',
				'quick_rating',
				'quick_short_descr',
				'quick_add_to_cart',
				'quick_wishlist',
				'quick_categories',
				'quick_share',
				'product_link'
			),
			'priority'        => 10,
			'choices'         => array(
				'quick_gallery'      => esc_html__( 'Gallery (drag for off-canvas only)', 'xstore' ),
				'quick_product_name' => esc_html__( 'Product Name', 'xstore' ),
				'quick_price'        => esc_html__( 'Price', 'xstore' ),
				'quick_rating'       => esc_html__( 'Product Star Rating', 'xstore' ),
				'quick_short_descr'  => esc_html__( 'Product Short Description', 'xstore' ),
				'quick_add_to_cart'  => esc_html__( 'Add To Cart', 'xstore' ),
				'quick_wishlist'     => esc_html__( 'Wishlist', 'xstore' ),
				'quick_categories'   => esc_html__( 'Product Meta', 'xstore' ),
				'quick_share'        => esc_html__( 'Product Share', 'xstore' ),
				'product_link'       => esc_html__( 'More Details Link', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
				//                array(
				//                    'setting'  => 'quick_view_content_type',
				//                    'operator' => '==',
				//                    'value'    => 'popup',
				//                ),
			)
		),
		
		'quick_descr' => array(
			'name'            => 'quick_descr',
			'type'            => 'toggle',
			'settings'        => 'quick_descr',
			'label'           => esc_html__( '"More details" toggle', 'xstore' ),
			'tooltip'     => esc_html__( 'Enable this option to add additional details toggle in the "Quick View" content window.', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
                array(
                    'setting'  => 'quick_view_content',
                    'operator' => 'in',
                    'value'    => 'product_link'
                ),
			)
		),
		
		'quick_descr_length' => array(
			'name'            => 'quick_descr_length',
			'type'            => 'etheme-text',
			'settings'        => 'quick_descr_length',
			'label'           => esc_html__( '"More details" length (chars)', 'xstore' ),
			'tooltip'     => esc_html__( 'This controls the character length of product excerpt in the "Quick View" content window.', 'xstore') . '<br/>'.
                esc_html__('Use a value of -1 to show all product details at once without any limit on the number of symbols.', 'xstore'),
			'section'         => 'shop-quick-view',
			'default'         => 120,
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
                array(
                    'setting'  => 'quick_view_content',
                    'operator' => 'in',
                    'value'    => 'product_link'
                ),
                array(
                    'setting'  => 'quick_descr',
                    'operator' => '==',
                    'value'    => true,
                ),
			)
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );