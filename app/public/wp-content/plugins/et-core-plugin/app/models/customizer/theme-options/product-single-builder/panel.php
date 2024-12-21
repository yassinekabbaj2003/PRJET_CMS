<?php
/**
 * The template created for displaying single product panel
 *
 * @version 1.0.0
 * @since   0.0.1
 */
add_filter( 'et/customizer/add/panels', function ( $panels ) {

    $is_elementor = defined('ELEMENTOR_VERSION') && defined('ELEMENTOR_PRO_VERSION');

    $elementor_templates_count = 0;

    if ( $is_elementor ) {
        $created_templates = get_posts(
            [
                'post_type' => 'elementor_library',
                'post_status' => 'publish',
                'posts_per_page' => '-1',
                'tax_query' => [
                    [
                        'taxonomy' => 'elementor_library_type',
                        'field' => 'slug',
                        'terms' => 'product',
                    ],
                ],
                'fields' => 'ids'
            ]
        );
        $elementor_templates_count = count($created_templates);
    }

	$checked = get_option( 'etheme_single_product_builder', false );
	
	$checked = ( $checked ) ? 'checked' : '';
	
	$args = array(
		'single_product_builder' => array(
			'id'          => 'single_product_builder',
			'title'       => esc_html__( 'Single product builder', 'xstore-core' ),
			'panel'       => 'woocommerce',
			'icon'        => 'dashicons-align-left',
		)
	);

    $args['single_product_builder']['description'] = '<span class="customize-control-kirki-toggle"> <label for="etheme-disable-default-single-product"> <span class="customize-control-title">' . esc_html__( 'Enable single product builder', 'xstore-core' ) . '</span> <input class="screen-reader-text" id="etheme-disable-default-single-product" name="etheme-disable-default-single-product" type="checkbox" ' . $checked . '><span class="switch" data-text-on="'.esc_attr__( 'On', 'xstore-core' ).'" data-text-off="'. esc_attr__( 'Off', 'xstore-core' ). '"></span></label><input type="hidden" name="nonce_etheme-switch_default" value="'.wp_create_nonce( 'etheme_switch_default' ).'"></span>';
//                sprintf(esc_html__('%sImportant Notice:%s%s Starting from %sJanuary 1, 2025%s, the current single product builder will be deprecated. But don\'t worry! We\'re moving this feature to a new plugin for better performance.%s To keep your website running smoothly, we recommend switching to our new single product builder, which works with Elementor and offers a better experience.%s %sClick here%s to access the new single product builder.%s Thanks for your cooperation!', 'xstore-core'),
//                    '<span style="color: red">', '</span>', '<br/>', '<span style="color: red">', '</span>', '<br/>', '<br/>', '<a href="'.admin_url( 'admin.php?page=et-panel-theme-builders' ).'" target="_blank">', '</a>', '<br/>'),

    if ( $elementor_templates_count > 0 ) {
        $args['single_product_builder']['description'] = sprintf(esc_html__('It looks like you\'re using Elementor Single Product templates [%s] on your site. This might cause issues with your customizer Single Product. To avoid problems and make sure everything runs smoothly, we suggest %schoosing%s one Single Product Builder option and disabling the others.', 'xstore-core'), $elementor_templates_count, '<a href="'.admin_url( 'admin.php?page=et-panel-theme-builders' ).'" target="_blank">', '</a>') . '<br/><br/>' . $args['single_product_builder']['description'];
    }
	
	return array_merge( $panels, $args );
} );
