<?php
/**
 * The template created for displaying shop categories options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'shop-categories' => array(
			'name'       => 'shop-categories',
			'title'      => esc_html__( 'Categories', 'xstore' ),
			'panel'      => 'shop-elements',
			'icon'       => 'dashicons-format-image',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
	
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-categories' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $sep_style, $text_color_scheme ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'cat_style' => array(
			'name'        => 'cat_style',
			'type'        => 'select',
			'settings'    => 'cat_style',
			'label'       => esc_html__( 'Categories design', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the design for the product categories.', 'xstore') . '<br/>'  .
            sprintf(esc_html__('Note: this will apply to categories if they are selected to be displayed on the main shop page in the "%1s" setting, and to subcategories if they are selected to be displayed on the category page in the "%2s" setting.', 'xstore' ),
                '<span class="et_edit" data-parent="woocommerce_product_catalog" data-section="woocommerce_shop_page_display" style="text-decoration: underline">'.esc_html__('Shop page display', 'xstore').'</span>',
                '<span class="et_edit" data-parent="woocommerce_product_catalog" data-section="woocommerce_category_archive_display" style="text-decoration: underline">'.esc_html__('Category display', 'xstore').'</span>'
            ),
			'section'     => 'shop-categories',
			'default'     => 'default',
			'choices'     => array(
				'default'  => esc_html__( 'Default', 'xstore' ),
				'with-bg'  => esc_html__( 'Title with background', 'xstore' ),
				'zoom'     => esc_html__( 'Zoom', 'xstore' ),
				'diagonal' => esc_html__( 'Diagonal', 'xstore' ),
				'classic'  => esc_html__( 'Classic', 'xstore' ),
			),
		),
		
		'cat_text_color' => array(
			'name'        => 'cat_text_color',
			'type'        => 'select',
			'settings'    => 'cat_text_color',
			'label'       => esc_html__( 'Categories text color scheme', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the color scheme for the product categories.', 'xstore' ) . '<br/>' .
                sprintf(esc_html__('Note: this will apply to categories if they are selected to be displayed on the main shop page in the "%1s" setting, and to subcategories if they are selected to be displayed on the category page in the "%2s" setting.', 'xstore' ),
                    '<span class="et_edit" data-parent="woocommerce_product_catalog" data-section="woocommerce_shop_page_display" style="text-decoration: underline">'.esc_html__('Shop page display', 'xstore').'</span>',
                    '<span class="et_edit" data-parent="woocommerce_product_catalog" data-section="woocommerce_category_archive_display" style="text-decoration: underline">'.esc_html__('Category display', 'xstore').'</span>'
                ),
			'section'     => 'shop-categories',
			'default'     => 'dark',
			'choices'     => $text_color_scheme,
		),
		
		'cat_valign' => array(
			'name'        => 'cat_valign',
			'type'        => 'select',
			'settings'    => 'cat_valign',
			'label'       => esc_html__( 'Text vertical position', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the vertical position of the title for the product categories.', 'xstore' ) . '<br/>' .
                sprintf(esc_html__('Note: this will apply to categories if they are selected to be displayed on the main shop page in the "%1s" setting, and to subcategories if they are selected to be displayed on the category page in the "%2s" setting.', 'xstore' ),
                    '<span class="et_edit" data-parent="woocommerce_product_catalog" data-section="woocommerce_shop_page_display" style="text-decoration: underline">'.esc_html__('Shop page display', 'xstore').'</span>',
                    '<span class="et_edit" data-parent="woocommerce_product_catalog" data-section="woocommerce_category_archive_display" style="text-decoration: underline">'.esc_html__('Category display', 'xstore').'</span>'
                ),
			'section'     => 'shop-categories',
			'default'     => 'center',
			'choices'     => array(
				'center' => esc_html__( 'Center', 'xstore' ),
				'top'    => esc_html__( 'Top', 'xstore' ),
				'bottom' => esc_html__( 'Bottom', 'xstore' ),
			),
		),

        // content separator
        'cats_widget_separator'                => array(
            'name'     => 'cats_widget_separator',
            'type'     => 'custom',
            'settings' => 'cats_widget_separator',
            'section'     => 'shop-categories',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-menu-alt"></span> <span style="padding-inline-start: 5px;">' . esc_html__( 'Categories widget', 'xstore' ) . '</span></div>',
        ),

        'cats_accordion' => array(
            'name'        => 'cats_accordion',
            'type'        => 'toggle',
            'settings'    => 'cats_accordion',
            'label'       => esc_html__( 'Accordion type', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to have the accordion toggle for categories with subcategories for the WooCommerce Product Categories widget.', 'xstore' ),
            'section'     => 'shop-categories',
            'default'     => 1,
        ),

        'first_catItem_opened' => array(
            'name'            => 'first_catItem_opened',
            'type'            => 'toggle',
            'settings'        => 'first_catItem_opened',
            'label'           => esc_html__( 'Open state', 'xstore' ),
            'tooltip'     => esc_html__( 'Enable this option to keep the first-level categories open by default.', 'xstore' ),
            'section'         => 'shop-categories',
            'default'         => 1,
            'active_callback' => array(
                array(
                    'setting'  => 'cats_accordion',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'widget_product_categories_advanced_mode' => array(
            'name'        => 'widget_product_categories_advanced_mode',
            'type'        => 'toggle',
            'settings'    => 'widget_product_categories_advanced_mode',
            'label'       => esc_html__( 'Advanced mode', 'xstore' ),
            'tooltip' => esc_html__( 'With this option, you will have a "Show All Categories" link and the children of the current category for the Product Categories widget on the product category page. Note: Preview is not available, so please check outside of the Customizer preview.', 'xstore' ),
            'section'     => 'shop-categories',
            'default'     => 0,
        ),

        'cats_subcategories_line_separated' => array(
            'name'        => 'cats_subcategories_line_separated',
            'type'        => 'toggle',
            'settings'    => 'cats_subcategories_line_separated',
            'label'       => esc_html__( 'Separated line for subcategories', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to have the nice-looking separator for subcategories for the WooCommerce Product Categories widget.', 'xstore' ),
            'section'     => 'shop-categories',
            'default'     => 0,
        ),
	);
	
	return array_merge( $fields, $args );
	
} );