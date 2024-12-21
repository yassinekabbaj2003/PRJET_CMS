<?php
/**
 * The template created for displaying header panel
 *
 * @version 1.0.0
 * @since   1.4.0
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
                        'terms' => 'header',
                    ],
                ],
                'fields' => 'ids'
            ]
        );
        $elementor_templates_count = count($created_templates);
    }

    $checked = !get_option( 'etheme_disable_customizer_header_builder', false );

    $checked = ( $checked ) ? 'checked' : '';

	$args = array(
		'header-builder' => array(
			'id'       => 'header-builder',
			'title'    => esc_html__( 'Header Builder', 'xstore-core' ),
			'icon'     => 'dashicons-arrow-up-alt',
			'priority' => 3
		)
	);

    $customizer_header_builder_tooltip = '<span class="tooltip-wrapper"><span class="tooltip-trigger" data-setting="disable_customizer_header_builder"><span class="dashicons dashicons-editor-help"></span></span><span class="tooltip-content" data-setting="disable_customizer_header_builder">' .
        sprintf(esc_html__('%sImportant Notice:%s%s The current header builder will no longer be available after %sJanuary 1, 2025%s. We recommend switching to our new header builder, which is built with Elementor for better performance and user experience.%s To access the new header builder, %s', 'xstore-core'),
            '<span style="color: red">', '</span>', '<br/>', '<span style="color: red">', '</span>', '<br/>', '<a href="' . admin_url('admin.php?page=et-panel-theme-builders') . '" target="_blank">' . __('click here', 'xstore-core') . '</a>') . '</span></span>';

    $args['header-builder']['description'] = '<span class="customize-control-kirki-toggle"> <label for="etheme-disable-default-header"> <span class="customize-control-title">' . esc_html__( 'Customizer header builder', 'xstore-core' ) . '</span> <span><input class="screen-reader-text" id="etheme-disable-default-header" name="etheme-disable-default-header" type="checkbox" ' . $checked . '><span class="switch" data-text-on="'.esc_attr__( 'On', 'xstore-core' ).'" data-text-off="'. esc_attr__( 'Off', 'xstore-core' ). '"></span></span></label><input type="hidden" name="nonce_etheme-switch_default" value="'.wp_create_nonce( 'etheme_switch_default' ).'"><input type="hidden" name="nonce_etheme_header-presets" value="'.wp_create_nonce( 'etheme_header-presets' ).'"></span>';

    if ( $elementor_templates_count > 0 ) {
        $args['header-builder']['description'] = sprintf(esc_html__('It looks like you\'re using Elementor Header templates [%s] on your site. This might cause issues with your customizer header. To avoid problems and make sure everything runs smoothly, we suggest %schoosing%s one header option and disabling the others.', 'xstore-core'), $elementor_templates_count, '<a href="'.admin_url( 'admin.php?page=et-panel-theme-builders' ).'" target="_blank">', '</a>') . '<br/><br/>' . $args['header-builder']['description'];
    }

//    if ( $is_elementor ) {
//        $args['header-builder']['description'] = sprintf(esc_html__('%sImportant Notice:%s%s Starting from %sJanuary 1, 2025%s, the current header builder will be deprecated. But don\'t worry! We\'re moving this feature to a new plugin for better performance.%s To keep your website running smoothly, we recommend switching to our new header builder, which works with Elementor and offers a better experience.%s %sClick here%s to access the new header builder.%s Thanks for your cooperation!', 'xstore-core'),
//            '<span style="color: red">', '</span>', '<br/>', '<span style="color: red">', '</span>', '<br/>', '<br/>', '<a href="'.admin_url( 'admin.php?page=et-panel-theme-builders' ).'" target="_blank">', '</a>', '<br/>');
//    }
	
	return array_merge( $panels, $args );
} );