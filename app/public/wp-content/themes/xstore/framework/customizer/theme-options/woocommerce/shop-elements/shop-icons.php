<?php
/**
 * The template created for displaying shop icons options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'shop-icons' => array(
			'name'       => 'shop-icons',
			'title'      => esc_html__( 'Product badges', 'xstore' ),
			'panel'      => 'shop-elements',
			'icon'       => 'dashicons-tag',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
	
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-icons' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $sep_style ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'separator_of_new_label' => array(
			'name'     => 'separator_of_new_label',
			'type'     => 'custom',
			'settings' => 'separator_of_new_label',
			'section'  => 'shop-icons',
			'default'  => '<div style="' . $sep_style . '">' . esc_html__( '"New" label settings', 'xstore' ) . '</div>',
		),
		
		'product_new_label_range' => array(
			'name'        => 'product_new_label_range',
			'type'        => 'slider',
			'settings'    => 'product_new_label_range',
			'label'       => esc_html__( 'Days limit', 'xstore' ),
			'tooltip' => esc_html__( 'This sets the limit of days for products to be labeled as "new". Tip: setting it to 0 will prevent the "new" label from being added.', 'xstore' ),
			'data-tags' => implode('| ', array(
			    esc_html__('"New" product label', 'xstore'),
            )),
			'section'     => 'shop-icons',
			'default'     => 0,
			'choices'     => array(
				'min'  => 0,
				'max'  => 365,
				'step' => 1,
			),
		),

        'product_new_label_type' => array(
            'name'            => 'product_new_label_type',
            'type'            => 'select',
            'settings'        => 'product_new_label_type',
            'label'           => esc_html__( 'Based on', 'xstore' ),
            'tooltip'     => esc_html__( 'Choose the variant of "new" label to be shown on products in your store.', 'xstore' ),
            'section'     => 'shop-icons',
            'default'         => 'modified',
            'choices'         => array(
                'modified' => esc_html__( 'Date Modified', 'xstore' ),
                'created' => esc_html__( 'Date Created', 'xstore' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'product_new_label_range',
                    'operator' => '!=',
                    'value'    => 0,
                ),
            )
        ),
		
		'new_label_icon_color' => array(
			'name'            => 'new_label_icon_color',
			'type'            => 'color',
			'settings'        => 'new_label_icon_color',
			'label'           => esc_html__( 'Color', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the color for the "new" label.', 'xstore' ),
            'data-tags' => implode('| ', array(
                esc_html__('"New" product label', 'xstore'),
            )),
			'section'         => 'shop-icons',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_new_label_range',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_new-label-color',
				),
			),
		),
		
		'new_label_icon_bg_color' => array(
			'name'            => 'new_label_icon_bg_color',
			'type'            => 'color',
			'settings'        => 'new_label_icon_bg_color',
			'label'           => esc_html__( 'Background color', 'xstore' ),
            'tooltip'     => esc_html__( 'Choose the background color for the "new" label.', 'xstore' ),
            'data-tags' => implode('| ', array(
                esc_html__('"New" product label', 'xstore'),
            )),
			'section'         => 'shop-icons',
			'default'         => '#2e7d32',
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_new_label_range',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_new-label-bg-color',
				),
			),
		),
		
		'separator_of_outofstock_label' => array(
			'name'     => 'separator_of_outofstock_label',
			'type'     => 'custom',
			'settings' => 'separator_of_outofstock_label',
			'section'  => 'shop-icons',
			'default'  => '<div style="' . $sep_style . '">' . esc_html__( '"Out of stock" label settings', 'xstore' ) . '</div>',
		),
		
		'out_of_icon' => array(
			'name'        => 'out_of_icon',
			'type'        => 'toggle',
			'settings'    => 'out_of_icon',
			'label'       => esc_html__( 'Show label', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to display the "Out of Stock" label for products which have been manually marked as "Out of Stock" status or have already sold out.', 'xstore' ),
            'data-tags' => implode('| ', array(
                esc_html__('"Out of stock" product label', 'xstore'),
            )),
			'section'     => 'shop-icons',
			'default'     => 1,
		),
		
		'separator_of_featured_label' => array(
			'name'     => 'separator_of_featured_label',
			'type'     => 'custom',
			'settings' => 'separator_of_featured_label',
			'section'  => 'shop-icons',
			'default'  => '<div style="' . $sep_style . '">' . esc_html__( '"Hot" label settings', 'xstore' ) . '</div>',
		),
		
		'featured_label' => array(
			'name'        => 'featured_label',
			'type'        => 'toggle',
			'settings'    => 'featured_label',
			'label'       => esc_html__( 'Show label', 'xstore' ),
			'tooltip' => sprintf(__( 'Enable this option to display the "Hot" label on %1s configured from the dashboard. Check the details by going %2s.', 'xstore' ),
                '<a href="'.admin_url('edit.php?post_type=product').'" target="_blank" rel="nofollow">'.esc_html__('featured products', 'xstore').'</a>',
                '<a href="https://www.modernmarketingpartners.com/2015/01/19/set-featured-products-woocommerce/" target="_blank" rel="nofollow">'.esc_html__('here', 'xstore').'</a>'
            ),
            'data-tags' => implode('| ', array(
                esc_html__('"Hot" product label', 'xstore'),
            )),
			'section'     => 'shop-icons',
			'default'     => 0,
		),
		
		'featured_label_icon_color' => array(
			'name'            => 'featured_label_icon_color',
			'type'            => 'color',
			'settings'        => 'featured_label_icon_color',
			'label'           => esc_html__( 'Color', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the color for the "hot" label.', 'xstore' ),
            'data-tags' => implode('| ', array(
                esc_html__('"Hot" product label', 'xstore'),
            )),
			'section'         => 'shop-icons',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'featured_label',
					'operator' => '==',
					'value'    => true,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_hot-label-color',
				),
			),
		),
		
		'featured_label_icon_bg_color' => array(
			'name'            => 'featured_label_icon_bg_color',
			'type'            => 'color',
			'settings'        => 'featured_label_icon_bg_color',
			'label'           => esc_html__( 'Background color', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the background color for the "hot" label.', 'xstore' ),
            'data-tags' => implode('| ', array(
                esc_html__('"Hot" product label', 'xstore'),
            )),
			'section'         => 'shop-icons',
			'default'         => '#f57f17',
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'featured_label',
					'operator' => '==',
					'value'    => true,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_hot-label-bg-color',
				),
			),
		),
		
		'separator_of_sale_label' => array(
			'name'     => 'separator_of_sale_label',
			'type'     => 'custom',
			'settings' => 'separator_of_sale_label',
			'section'  => 'shop-icons',
			'default'  => '<div style="' . $sep_style . '">' . esc_html__( '"Sale" label settings', 'xstore' ) . '</div>',
		),
		
		'sale_icon' => array(
			'name'        => 'sale_icon',
			'type'        => 'toggle',
			'settings'    => 'sale_icon',
			'label'       => esc_html__( 'Show label', 'xstore' ),
			'tooltip' => sprintf(esc_html__( 'Enable this option to display the "Sale" label on products that are on sale. To display the "Sale" label as a percentage, please enable the option below labeled "%1s".', 'xstore' ),
                '<span class="et_edit" data-parent="shop-icons" data-section="sale_percentage" style="text-decoration: underline;">'.esc_html__('Percentage', 'xstore').'</span>'),
            'data-tags' => implode('| ', array(
                esc_html__('"Sale" product label', 'xstore'),
            )),
			'section'     => 'shop-icons',
			'default'     => 1,
		),
		
		'sale_icon_text' => array(
			'name'            => 'sale_icon_text',
			'type'            => 'etheme-text',
			'settings'        => 'sale_icon_text',
			'label'           => esc_html__( 'Label text', 'xstore' ),
            'tooltip'   => esc_html__('Customize the text on "sale" label.', 'xstore'),
            'data-tags' => implode('| ', array(
                esc_html__('"Sale" product label', 'xstore'),
            )),
			'section'         => 'shop-icons',
			'default'         => esc_html__( 'Sale', 'xstore' ),
			'active_callback' => array(
				array(
					'setting'  => 'sale_icon',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'sale_icon_color' => array(
			'name'            => 'sale_icon_color',
			'type'            => 'color',
			'settings'        => 'sale_icon_color',
			'label'           => esc_html__( 'Color', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the color for the "sale" label.', 'xstore' ),
            'data-tags' => implode('| ', array(
                esc_html__('"Sale" product label', 'xstore'),
            )),
			'section'         => 'shop-icons',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'sale_icon',
					'operator' => '==',
					'value'    => true,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_on-sale-color',
				),
			),
		),
		
		'sale_icon_bg_color' => array(
			'name'            => 'sale_icon_bg_color',
			'type'            => 'color',
			'settings'        => 'sale_icon_bg_color',
			'label'           => esc_html__( 'Background color', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the background color for the "sale" label.', 'xstore' ),
            'data-tags' => implode('| ', array(
                esc_html__('"Sale" product label', 'xstore'),
            )),
			'section'         => 'shop-icons',
			'default'         => '#c62828',
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'sale_icon',
					'operator' => '==',
					'value'    => true,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_on-sale-bg-color',
				),
			),
		),
		
		'sale_br_radius' => array(
			'name'            => 'sale_br_radius',
			'type'            => 'slider',
			'settings'        => 'sale_br_radius',
			'label'           => esc_html__( '"Sale" & "New" labels border radius (%)', 'xstore' ),
			'tooltip'     => esc_html__( 'This controls the radius of the corners of the "sale" and "new" labels.', 'xstore'),
            'data-tags' => implode('| ', array(
                esc_html__('"New" product label', 'xstore'),
                esc_html__('"Sale" product label', 'xstore'),
            )),
			'section'         => 'shop-icons',
			'default'         => 0,
			'choices'         => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					array(
						'setting'  => 'sale_icon',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'product_new_label_range',
						'operator' => '!=',
						'value'    => 0,
					)
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_on-sale-radius',
					'units'    => '%'
				),
			),
		),
		
		'sale_icon_size' => array(
			'name'            => 'sale_icon_size',
			'type'            => 'etheme-text',
			'settings'        => 'sale_icon_size',
			'label'           => esc_html__( '"Sale" & "New" labels size', 'xstore' ),
			'tooltip'     => esc_html__( 'This controls the dimensions of the "sale" and "new" labels. Note: these values are calculated in "em" units, for example, 3.75x3.75 (width x height).', 'xstore' ),
            'data-tags' => implode('| ', array(
                esc_html__('"New" product label', 'xstore'),
                esc_html__('"Sale" product label', 'xstore'),
            )),
			'section'         => 'shop-icons',
			'default'         => '',
			'active_callback' => array(
				array(
					array(
						'setting'  => 'sale_icon',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'product_new_label_range',
						'operator' => '!=',
						'value'    => 0,
					)
				)
			),
		),
		
		'sale_percentage' => array(
			'name'            => 'sale_percentage',
			'type'            => 'toggle',
			'settings'        => 'sale_percentage',
			'label'           => esc_html__( 'Percentage', 'xstore' ),
			'tooltip'     => sprintf(esc_html__( 'With this option, the "sale" label will be calculated as a percentage discount for the product based on its regular price and sale price. Note: if you want to enable the "sale" label percentage for variable products, please enable the option below - \'%1s\', as it requires additional calculations for such product types to display the percentage value correctly.', 'xstore' ),
            '<span class="et_edit" data-parent="shop-icons" data-section="sale_percentage_variable" style="text-decoration: underline;">'.esc_html__('Percentage for variable products', 'xstore').'</span>'),
            'data-tags' => implode('| ', array(
                esc_html__('"Sale" product label', 'xstore'),
            )),
			'section'         => 'shop-icons',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'sale_icon',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
		
		'sale_percentage_variable' => array(
			'name'            => 'sale_percentage_variable',
			'type'            => 'toggle',
			'settings'        => 'sale_percentage_variable',
			'label'           => esc_html__( 'Percentage for variable products', 'xstore' ),
			'tooltip'     => esc_html__( 'With this option, the "sale" label will be calculated as a percentage discount for the variable products based on the regular and sale prices of the product\'s variations.', 'xstore' ),
            'data-tags' => implode('| ', array(
                esc_html__('"Sale" product label', 'xstore'),
            )),
			'section'         => 'shop-icons',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'sale_icon',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'sale_percentage',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
	);
	
	return array_merge( $fields, $args );
	
} );