<?php

/**
 * The template created for enqueueing all files for header panel
 *
 * @version 1.0.1
 * @since   1.4.0
 * last changes in 1.5.5
 */

function et_b_get_posts( $args ) {
    if ( is_string( $args ) ) {
        $args = add_query_arg(
            array(
                'suppress_filters' => false,
            )
        );
    } elseif ( is_array( $args ) && ! isset( $args['suppress_filters'] ) ) {
        $args['suppress_filters'] = false;
    }

    $add_none = isset($args['with_none']);
    $add_custom = isset($args['with_custom']);
    $add_select_page = isset($args['with_select_page']);
    if ( $add_none )
        unset($args['with_none']);
    if ( $add_custom )
        unset($args['with_custom']);
    if ( $add_select_page )
        unset($args['with_select_page']);
    // Get the posts.
    $posts = get_posts( $args );

    // Properly format the array.
    $items = array();
    foreach ( $posts as $post ) {
        $items[ $post->ID ] = $post->post_title . ' (id - ' . $post->ID . ')';
    }

    wp_reset_postdata();

    if ( $add_none )
        $items[0] = esc_html__( 'None', 'xstore-core' );

    if ( $add_custom )
        $items['custom'] = esc_html__( 'Custom', 'xstore-core' );

    if ( $add_select_page )
        $items[0] = esc_html__( 'Select page', 'xstore-core' );

    return $items;
}

function et_b_get_terms( $taxonomies ) {
    $items = array();

    // Get the post types.
    $terms = get_terms( $taxonomies );

    if ( is_wp_error( $terms ) ) {
        return $items;
    }

    // Build the array.
    foreach ( $terms as $term ) {
        $items[ $term->term_id ] = $term->name . ' (id - ' . $term->term_id . ')';;
    }

    if ( 'nav_menu' == $taxonomies )
        $items[0] = esc_html__( 'Select menu', 'xstore-core' );

    return $items;
}

function et_b_get_widgets() {
    global $wp_widget_factory;
    $field = array();
    foreach ( $wp_widget_factory->widgets as $widget ) {
        $widget_class           = get_class( $widget );
        $field[ $widget_class ] = $widget->name;
    }
    asort( $field );

    return $field;
}

//	function et_b_get_shortcodes() {
//		// Get the array of all the shortcodes
//		global $shortcode_tags;
//
//		$shortcodes = $shortcode_tags;
//
//		// sort the shortcodes with alphabetical order
//		ksort($shortcodes);
//
//		$shortcode_output = array();
//
//		foreach ($shortcodes as $shortcode => $value) {
//			$shortcode_output[$shortcode] = '['.$shortcode.']';
//		}
//
//		return $shortcode_output;
//	}

//$post_types = array(
//	'pages'              => et_b_get_posts(
//		array(
//			'post_per_page' => -1,
//			'nopaging'      => true,
//			'post_type'     => 'page'
//		)
//	),
//	'menus'              => et_b_get_terms( 'nav_menu' ),
//	'sections'           => et_b_get_posts(
//		array(
//			'post_per_page' => -1,
//			'nopaging'      => true,
//			'post_type'     => 'staticblocks'
//		)
//	),
//	'product_categories' => et_b_get_terms( 'product_cat' ),
//	'sidebars'           => etheme_get_sidebars(),
//);
//
//$post_types['pages_all'] = $post_types['pages'];
//
//$post_types['pages']['custom'] = esc_html__( 'Custom', 'xstore-core' );
//$post_types['pages'][0]        = $post_types['pages_all'][0] = esc_html__( 'Select page', 'xstore-core' );
//
//$post_types['menus'][0] = esc_html__( 'Select menu', 'xstore-core' );
//
//$post_types['sections'][0] = esc_html__( 'None', 'xstore-core' );



function et_b_header_presets(){
    $versions   = get_transient( 'et_b_header_presets' );

    $url = apply_filters('etheme_protocol_url', 'https://www.8theme.com/import/xstore-headers/');

    if (defined('ETHEME_BASE_URL')) {
        $url = apply_filters('etheme_protocol_url', ETHEME_BASE_URL . 'import/xstore-headers/');
    }

    if ( ! $versions || empty( $versions ) || isset($_GET['et_b_header_presets_transient']) ) {
        $api_response = wp_remote_get( $url );
        $code         = wp_remote_retrieve_response_code( $api_response );

        if ( $code == 200 ) {
            $api_response = wp_remote_retrieve_body( $api_response );
            $api_response = json_decode( $api_response, true );
            $versions = $api_response;
            set_transient( 'et_b_header_presets', $versions, WEEK_IN_SECONDS );
        } else {
            $versions = array();
        }
    }
    return $versions;
}

$is_customize_preview = is_customize_preview();
$mobile_panel_elements = array(
    'shop'           => esc_html__( 'Shop', 'xstore-core' ),
    'cart'           => esc_html__( 'Cart', 'xstore-core' ),
    'home'           => esc_html__( 'Home', 'xstore-core' ),
    'account'        => esc_html__( 'Account', 'xstore-core' ),
    'wishlist'       => esc_html__( 'Wishlist', 'xstore-core' ),
    'compare'        => esc_html__( 'Compare', 'xstore-core' ),
    'search'         => esc_html__( 'Search', 'xstore-core' ),
    'mobile_menu'    => esc_html__( 'Mobile menu', 'xstore-core' ),
    'more_toggle'    => esc_html__( 'More toggle 01', 'xstore-core' ),
    'more_toggle_02' => esc_html__( 'More toggle 02', 'xstore-core' ),
    'custom'         => esc_html__( 'Custom', 'xstore-core' ),
);

$header_presets = et_b_header_presets();

$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
$brand_label = 'XStore';
if ( count($xstore_branding_settings) && isset($xstore_branding_settings['control_panel'])) {
    if ( $xstore_branding_settings['control_panel']['icon'] )
        $brand_icon = $xstore_branding_settings['control_panel']['icon'];
    if ( $xstore_branding_settings['control_panel']['label'] )
        $brand_label = $xstore_branding_settings['control_panel']['label'];
};

$strings = array(
    'label'           => array(
        'alignment'              => esc_html__( 'Alignment', 'xstore-core' ),
        'style'                  => esc_html__( 'Style', 'xstore-core' ),
        'mode'                   => esc_html__( 'Mode', 'xstore-core' ),
        'type'                   => esc_html__( 'Type', 'xstore-core' ),
        'icon'                   => esc_html__( 'Icon', 'xstore-core' ),
        'show_label'             => esc_html__('Show label', 'xstore-core'),
        'colors'                 => esc_html__( 'Colors', 'xstore-core' ),
        'color'                  => esc_html__( 'Color', 'xstore-core' ),
        'fonts'                  => esc_html__( 'Typeface', 'xstore-core' ),
        'elements'               => esc_html__( 'Elements', 'xstore-core' ),
        'elements_spacing'       => esc_html__( 'Elements spacing (px)', 'xstore-core' ),
        'wide_header'            => esc_html__( 'Full-width header', 'xstore-core' ),
//		'select_menu'            => esc_html__( 'Select menu', 'xstore-core' ),
//		'select_menu_extra'      => esc_html__( 'Select Extra Tab Menu', 'xstore-core' ),
        'content_zoom'           => esc_html__( 'Content zoom (%)', 'xstore-core' ),
        'content_size'           => esc_html__( 'Content size (%)', 'xstore-core' ),
        'size_proportion'        => esc_html__( 'Size proportion', 'xstore-core' ),
        'icon_size_proportion'   => esc_html__( 'Icon size proportion', 'xstore-core' ),
        'title_size_proportion'  => esc_html__( 'Size proportion', 'xstore-core' ),
        'title_sizes'            => esc_html__( 'Title sizes', 'xstore-core' ),
        'wcag_color'             => esc_html__( 'WCAG Color', 'xstore-core' ),
        'wcag_color_hover'       => esc_html__( 'WCAG Color (hover)', 'xstore-core' ),
        'wcag_color_active'      => esc_html__( 'WCAG Color (active)', 'xstore-core' ),
        'wcag_bg_color'          => esc_html__( 'WCAG Background control', 'xstore-core' ),
        'wcag_bg_color_hover'    => esc_html__( 'WCAG Background control (hover)', 'xstore-core' ),
        'wcag_bg_color_active'   => esc_html__( 'WCAG Background control (active)', 'xstore-core' ),
        'computed_box'           => esc_html__( 'Computed box', 'xstore-core' ),
        'border_radius'          => esc_html__( 'Border radius (px)', 'xstore-core' ),
        'border_style'           => esc_html__( 'Border style', 'xstore-core' ),
        'min_height'             => esc_html__( 'Min height (px)', 'xstore-core' ),
//		'icons_zoom'             => esc_html__( 'Icons zoom (proportion)', 'xstore-core' ),
        'custom_icon_svg'        => esc_html__( 'Custom icon SVG code', 'xstore-core' ),
        'custom_image_svg'       => esc_html__( 'Custom icon SVG', 'xstore-core' ),
        'show_title'             => esc_html__( 'Show title', 'xstore-core' ),
        'bg_color'               => esc_html__( 'Background color', 'xstore-core' ),
        'border_color'           => esc_html__( 'Border color', 'xstore-core' ),
        'button_text'            => esc_html__( 'Button text', 'xstore-core' ),
        'button_size_proportion' => esc_html__( 'Button size (proportion)', 'xstore-core' ),
        'page_links'             => esc_html__( 'Page links', 'xstore-core' ),
        'custom_link'            => esc_html__( 'Custom link', 'xstore-core' ),
        'target_blank'           => esc_html__( 'Open in new window', 'xstore-core' ),
        'rel_no_follow'          => esc_html__( 'Add no-follow rel', 'xstore-core' ),
        'choose_static_block' => esc_html__( 'Choose static block', 'xstore-core' ),
        'use_static_block'       => sprintf(esc_html__( 'Use %s', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: var(--customizer-dark-color, #555)">' . esc_html__( 'static block', 'xstore-core' ) . '</a>'),
        'direction'              => esc_html__( 'Direction', 'xstore-core' ),
//		'editor_control'         => esc_html__( 'This is an editor control.', 'xstore-core' ),
        'sticky_logo'            => esc_html__( 'Custom sticky logo', 'xstore-core' ),
        'paddings'               => array(
            'padding-top'    => esc_html__( 'Padding top', 'xstore-core' ),
            'padding-right'  => esc_html__( 'Padding right', 'xstore-core' ),
            'padding-bottom' => esc_html__( 'Padding bottom', 'xstore-core' ),
            'padding-left'   => esc_html__( 'Padding left', 'xstore-core' ),
        ),
        'popup_dimensions' => array(
            'width'  => esc_html__( 'Width (for custom only)', 'xstore-core' ),
            'height' => esc_html__( 'Height (for custom only)', 'xstore-core' ),
        ),
        'rows_gap'               => esc_html__( 'Rows gap (px)', 'xstore-core' ),
        'cols_gap'               => esc_html__( 'Columns gap (px)', 'xstore-core' ),
        'product_view'           => esc_html__( 'Products design type', 'xstore-core' ),
        'product_view_color'     => esc_html__( 'Hover Color Scheme', 'xstore-core' ),
        'product_img_hover'      => esc_html__( 'Image hover effect', 'xstore-core' )
    ),
    'separator_label' => array(
        'main_configuration' => esc_html__( 'Main configuration', 'xstore-core' ),
        'style'              => esc_html__( 'Style', 'xstore-core' ),
        'advanced'           => esc_html__( 'Advanced', 'xstore-core' ),
	    'custom_content'     => esc_html__( 'Content', 'xstore-core' ),
    ),
    'description'     => array(
        'style' => esc_html__( 'Using this option, you can choose the style for this element.', 'xstore-core' ),
        'type' => esc_html__( 'With this option, you can select the type of this element.', 'xstore-core' ),
        'alignment'             => esc_html__( 'Using this option, you can choose an alignment value for this element.', 'xstore-core' ),
        'alignment_with_inherit'             => esc_html__( 'Using this option, you can choose an alignment value for this element. Tip: select the "Inherit" value to take on the alignment from the parent of this element.', 'xstore-core' ),
        'wide_header'           => esc_html__( 'Expand the current header area to the full width of the page.', 'xstore-core'),
        'min_height'            => esc_html__( 'This controls the minimum height of the current header area.', 'xstore-core' ),
        'icon'                  => esc_html__( 'With this option, you can select an available icon for your element, deactivate it, or choose "Custom" variant to upload a custom SVG icon using the form below.', 'xstore-core' ),
        'target_blank'           => esc_html__( 'Enable this option to open the link when this element is clicked in a new window.', 'xstore-core' ),
        'rel_no_follow' => sprintf(esc_html__('Enable this option to add the "nofollow" attribute to the links of this element. Note: The "nofollow" setting on a web page hyperlink tells %1s not to use the link for %2s calculations.', 'xstore-core'),
            '<a href="https://en.wikipedia.org/wiki/Search_engine" target="_blank" rel="nofollow">'.esc_html__('search engines', 'xstore-core').'</a>',
            '<a href="https://en.wikipedia.org/wiki/Ranking_(information_retrieval)" target="_blank" rel="nofollow">'.esc_html__('page ranking', 'xstore-core').'</a>'),
        'use_static_block'       => esc_html__( 'This option allows you to easily add custom content to your website without needing to code. With this feature, you can add custom images, text, and multimedia content to any page on your website, helping you to create a unique and engaging user experience for your visitors.', 'xstore-core' ),
        'choose_static_block'   => sprintf(esc_html__('By selecting specific %1s for [different areas of your website], you can tailor your content to suit the needs of your audience, creating a more personalized and immersive user experience. Additionally, this option allows you to easily update and modify your content as needed, giving you complete control over your website\'s design and functionality.'), '<a href="'.etheme_documentation_url('47-static-blocks', false).'" target="_blank">'.esc_html__('static block', 'xstore-core').'</a>'),
        'show_label' => esc_html__( 'Turn on/off to show/hide the label text for this element', 'xstore-core' ),
        'show_title' => esc_html__( 'Turn on/off to show/hide the title text for this element', 'xstore-core' ),
        'custom_link'            => esc_html__( 'Enter your own link for this purpose.', 'xstore-core' ),
        'fonts'                 => esc_html__( 'This controls the typeface settings of this element.', 'xstore-core'),
        'border_radius'         => esc_html__( 'This controls the radius of the corners of this element.', 'xstore-core'),
        'icon_size_proportion'  => esc_html__( 'This option allows you to zoom in or out size of the current icon.', 'xstore-core' ),
        'content_zoom'          => esc_html__( 'This option allows you to zoom in or out on the content of the current element.','xstore-core'),
        'size_proportion'       => esc_html__( 'This option allows you to increase or decrease the size proportion of the current element.', 'xstore-core' ),
        'title_size_proportion'  => esc_html__( 'This option allows you to increase or decrease the size proportion of the title.', 'xstore-core' ),
        'colors'                => esc_html__( 'Choose the color to be applied to this element, or specify a custom one using the option below.', 'xstore-core' ),
        'wcag_color'            => esc_html__( 'Select the text color for your content. Please choose auto color to ensure readability with your selected background-color, or switch to the "Custom Color" tab to select any other color you want.', 'xstore-core' ),
        'wcag_bg_color'         => esc_html__( 'WCAG control is designed to be used by webdesigners, web developers or web accessibility professionals to compute the contrast between two colors (background color, text color)', 'xstore-core' ) . ' <a href="https://app.contrast-finder.org/?lang=en" rel="nofollow" target="_blank" style="text-decoration: underline;">' . esc_html__( 'More details', 'xstore-core' ) . '</a>',
        'bg_color'              => esc_html__( 'Choose the background color for this element.', 'xstore-core' ),
        'button_text'            => esc_html__( 'You can customize the text on your button.', 'xstore-core' ),
        'page_links'             => esc_html__( 'With this option, you can select a page link that is available for your element or create a custom link using the form below.', 'xstore-core' ),
        'icons_style'           => sprintf(esc_html__( 'There are two types of icons: bold and thin. You can easily change them for the entire website by going to %s', 'xstore-core' ), '<span class="et_edit" data-parent="style" data-section="bold_icons" style="text-decoration: underline;">'.esc_html__('Icons style', 'xstore-core').'</span>'),
        'border_style'          => esc_html__( 'This controls border style of this element.', 'xstore-core' ),
        'border_color'          => esc_html__( 'Choose the border color to be applied to this element.', 'xstore-core').'<br/>'.esc_html__('Info: You must first set the border width using the Computed box. To have an invisible border, please set the alpha channel to 0.', 'xstore-core' ),
        'editor_control'        => esc_html__( 'Here, you can write your own custom HTML using the tags in the top bar of the editor. However, please note that not all HTML tags and element attributes can be used due to Theme Options safety reasons.', 'xstore-core' ),
        'computed_box'          => esc_html__( 'You can select the margin, border width, and padding for the element.', 'xstore-core' ),
        'paddings'               => array(
            'padding-top'    => esc_html__( 'Padding top', 'xstore-core' ),
            'padding-right'  => esc_html__( 'Padding right', 'xstore-core' ),
            'padding-bottom' => esc_html__( 'Padding bottom', 'xstore-core' ),
            'padding-left'   => esc_html__( 'Padding left', 'xstore-core' ),
        ),
        'popup_dimensions' => array(
            'width'  => esc_html__( 'Width', 'xstore-core' ),
            'height' => esc_html__( 'Height', 'xstore-core' ),
        ),
        'rows_gap' => esc_html__('This controls the spacing between the rows of this element.', 'xstore-core'),
        'cols_gap' => esc_html__('This controls the spacing between the columns of this element.', 'xstore-core'),
        'size_bigger_attention' => esc_html__( 'Attention, if your element will have the size bigger than the column this element is in, then your element positioning may be a bit not as aspected', 'xstore-core' ),
        'sticky_logo'           => esc_html__( 'By default, the sticky header uses the site logo. Upload an image to set up a different logo for the sticky header.', 'xstore-core' ),
        'custom_image_svg'      => sprintf(esc_html__( 'Upload the SVG icon. Install the %s plugin to enable the uploading of SVG files.', 'xstore-core' ), '<a href="https://wordpress.org/plugins/svg-support/" rel="nofollow" target="_blank">' . esc_html__( 'SVG Support', 'xstore-core' ) . '</a>'),
        'product_view'          => esc_html__( 'Choose the design type for the products displayed in this element.', 'xstore-core' ) . '<br/>' . sprintf(esc_html__('Tip: Select the "Inherit" value to take the design type you have set in the global "%1s" settings.', 'xstore-core'), '<span class="et_edit" data-parent="products-style" data-section="product_view" style="text-decoration: underline;">'.esc_html__('Products design', 'xstore-core').'</span>'),
        'product_view_color'    => esc_html__( 'Choose the color scheme for the product content.', 'xstore-core' ) . '<br/>' . sprintf(esc_html__('Tip: Select the "Inherit" value to take the design type you have set in the global "%1s" settings.', 'xstore-core'), '<span class="et_edit" data-parent="products-style" data-section="product_view_color" style="text-decoration: underline;">'.esc_html__('Products design', 'xstore-core').'</span>') . '<br/>' . esc_html__('Note: this option will only apply to products that have a design with buttons on hover.', 'xstore-core'),
        'product_img_hover'     => esc_html__( 'Choose the hover effect that will be displayed for all products shown in this element. There are many attractive effects, so you are sure to find the one that you and your customers will like. Alternatively, you can disable the effect if you prefer a static design without any effects.', 'xstore-core' ) . '<br/>' . sprintf(esc_html__('Tip: Select the "Inherit" value to take the design type you have set in the global "%1s" settings.', 'xstore-core'), '<span class="et_edit" data-parent="products-style" data-section="product_img_hover" style="text-decoration: underline;">'.esc_html__('Products design', 'xstore-core').'</span>')
    )
);

$sep_style = 'display: flex; justify-content: flex-start; align-items: center; padding: calc(var(--customizer-ui-content-zoom, 1) * 12px) 15px;margin: 0 -15px;text-align: start;font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px);font-weight: 500;line-height: 1;text-transform: uppercase; letter-spacing: 1px;background-color: var(--customizer-white-color, #fff);color: var(--customizer-dark-color, #222);border-top: 1px solid var(--customizer-border-color, #e1e1e1);border-bottom: 1px solid var(--customizer-border-color, #e1e1e1);';

$separators = array(
    'content'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-settings"></span> <span style="padding-inline-start: 5px;">' . $strings['separator_label']['main_configuration'] . '</span></div>',
    'style'    => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-customizer"></span> <span style="padding-inline-start: 5px;">' . $strings['separator_label']['style'] . '</span></div>',
    'advanced' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-star-filled"></span> <span style="padding-inline-start: 5px;">' . $strings['separator_label']['advanced'] . '</span></div>',
    'custom_content' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-editor-table"></span> <span style="padding-inline-start: 5px;">' . $strings['separator_label']['custom_content'] . '</span></div>'
);

function et_b_element_styles( $element ) {
    return array(
        'type1' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/' . $element . '/Style-' . $element . '-icon-1.svg',
        'type2' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/' . $element . '/Style-' . $element . '-icon-2.svg',
        'type3' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/' . $element . '/Style-' . $element . '-icon-3.svg',
    );
}

$sidebars = array(
    'without' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/layout/full-width.svg',
    'left'    => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/layout/left-sidebar.svg',
    'right'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/layout/right-sidebar.svg',
);

$sidebars_with_inherit = array(
    'inherit' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/layout/inherit.svg',
    'without' => $sidebars['without'],
    'left'    => $sidebars['left'],
    'right'   => $sidebars['right']
);

$menu_settings = array(
    'strings'            => array(
        'label'       => array(
            'style'                    => esc_html__( 'Menu style', 'xstore-core' ),
            'type' => esc_html__('Menu type', 'xstore-core'),
            'select_menu'            => esc_html__( 'Select menu', 'xstore-core' ),
            'select_menu_extra'      => esc_html__( 'Select Extra Tab Menu', 'xstore-core' ),
            'sep_type'                 => esc_html__( 'Separator type', 'xstore-core' ),
            'one_page'                 => esc_html__( 'One page menu', 'xstore-core' ),
            'menu_dropdown_full_width' => esc_html__( 'Mega menu dropdown full-width', 'xstore-core' ),
            'arrows'                   => esc_html__( 'Dropdown\'s parent\'s arrows', 'xstore-core' ),
            'color'                    => esc_html__( 'Text color', 'xstore-core' ),
            'hover_color'              => esc_html__( 'Text color (hover, active)', 'xstore-core' ),
            'line_color'               => esc_html__( 'Line color (hover, active)', 'xstore-core' ),
            'dots_color'               => esc_html__( 'Separator color', 'xstore-core' ),
            'arrow_color'              => esc_html__( 'Arrow color (hover, active)', 'xstore-core' ),
            'bg_hover_color'           => esc_html__( 'Background color (hover, active)', 'xstore-core' ),
            'item_box_model'           => esc_html__( 'Computed box for the menu item', 'xstore-core' ),
            'nice_space'               => esc_html__( 'Remove spacing on sides', 'xstore-core' ),
            'border_hover_color'       => esc_html__( 'Border color (hover, active)', 'xstore-core' ),
        ),
        'description' => array(
            'style' => esc_html__('Choose the design for this element. Give them a try and see the difference for yourself.','xstore-core'),
            'type' => esc_html__('Using this option, you can choose a design type for menu element.', 'xstore-core'),
            'select_menu' => sprintf(esc_html__('Choose the menu from the list of menus you have created in Dashboard -> Appearance -> %s','xstore-core'), '<a href="'.admin_url('nav-menus.php').'" target="_blank">'.esc_html__('Menus', 'xstore-core').'</a>'),
            'sep_type'                 => esc_html__( 'Choose the best separator to place between your menu items. It will make your menu beauty and nice-looking comparing to standard menu items.', 'xstore-core' ),
            'one_page'                 => esc_html__( 'Enable when your menu is working only for one page by anchors', 'xstore-core' ),
            'menu_dropdown_full_width' => esc_html__( 'Enable when you want to make your dropdown block full-width', 'xstore-core' ),
            'arrows'                   => esc_html__( 'Add arrows for 1-level menu items with dropdowns', 'xstore-core' ),
            'color'                    => esc_html__( 'Choose the text color for this element.', 'xstore-core' ),
            'hover_color'              => esc_html__( 'Choose the text color on hover for this element.', 'xstore-core' ),
            'line_color'               => esc_html__( 'This option will apply on specific hover element.', 'xstore-core' ),
            'arrow_color'              => esc_html__( 'This option will apply on specific hover/active element.', 'xstore-core' ),
            'dots_color'               => esc_html__( 'This option will apply on specific element separator', 'xstore-core' ),
            'bg_hover_color'           => esc_html__( 'This option will apply on specific hover element. If you use custom type it will appeare on your items background color', 'xstore-core' ),
            'nice_space'               => esc_html__( 'Crop the margin spaces on sides of your menu to make it looks grid-system ready.', 'xstore-core' ),
            'item_box_model'           => esc_html__( 'You can select the margin, border width, and padding for menu item element.', 'xstore-core' ),
        )
    ),
    'style'              => array(
        'none'      => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/menu/Style-hovers-icon-1.svg',
        'underline' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/menu/Style-hovers-icon-2.svg',
        'overline'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/menu/Style-hovers-icon-3.svg',
        'dots'      => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/menu/Style-hovers-icon-4.svg',
        'arrow'     => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/menu/Style-hovers-icon-5.svg',
        'custom'    => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/menu/Style-hovers-icon-custom.svg',
    ),
    'separators'         => array(
        '2502' => esc_html__( 'Icon 01', 'xstore-core' ),
        '2022' => esc_html__( 'Icon 02', 'xstore-core' ),
        '2044' => esc_html__( 'Icon 03', 'xstore-core' ),
        '2016' => esc_html__( 'Icon 04', 'xstore-core' ),
        '2059' => esc_html__( 'Icon 05', 'xstore-core' ),
        '2217' => esc_html__( 'Icon 06', 'xstore-core' ),
        '2248' => esc_html__( 'Icon 07', 'xstore-core' ),
        '2299' => esc_html__( 'Icon 08', 'xstore-core' ),
        '2301' => esc_html__( 'Icon 09', 'xstore-core' ),
        '2605' => esc_html__( 'Icon 10', 'xstore-core' ),
    ),
    'fonts'              => array(
        'font-family'    => '',
        'variant'        => 'regular',
        // 'font-size'      => '15px',
        // 'line-height'    => '1.5',
        'letter-spacing' => '0',
        // 'color'          => '#555',
        'text-transform' => 'inherit',
        // 'text-align'     => 'left',
    ),
    'item_box_model'     => array(
        'margin-top'          => '0px',
        'margin-right'        => '0px',
        'margin-bottom'       => '0px',
        'margin-left'         => '0px',
        'border-top-width'    => '0px',
        'border-right-width'  => '0px',
        'border-bottom-width' => '0px',
        'border-left-width'   => '0px',
        'padding-top'         => '10px',
        'padding-right'       => '10px',
        'padding-bottom'      => '10px',
        'padding-left'        => '10px',
    ),
    'dropdown_selectors' => '.et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown:not(.nav-sublist),
	      .et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li .nav-sublist ul,
	      .et_b_header-menu.et_element-top-level .item-design-mega-menu .nav-sublist-dropdown:not(.nav-sublist),

	      .site-header .widget_nav_menu .menu > li .sub-menu,

	      .site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown:not(.nav-sublist),
	      .site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li .nav-sublist ul,
	      .site-header .etheme_widget_menu .item-design-mega-menu .nav-sublist-dropdown:not(.nav-sublist)'
);

$choices = array(
    'alignment'                => array(
        'start'  => '<span class="dashicons dashicons-editor-alignleft"></span>',
        'center' => '<span class="dashicons dashicons-editor-aligncenter"></span>',
        'end'    => '<span class="dashicons dashicons-editor-alignright"></span>',
    ),
    'alignment2'               => array(
        'flex-start' => '<span class="dashicons dashicons-editor-alignleft"></span>',
        'center'     => '<span class="dashicons dashicons-editor-aligncenter"></span>',
        'flex-end'   => '<span class="dashicons dashicons-editor-alignright"></span>',
    ),
    'direction'                => array(
        'type1' => array(
            'hor' => esc_html__( 'Horizontal', 'xstore-core' ),
            'ver' => esc_html__( 'Vertical', 'xstore-core' ),
        ),
        'type2' => array(
            'column' => 'column',
            'row'    => 'row',
        ),
    ),
    'dropdown_position'        => array(
        'left'   => esc_html__( 'Left side', 'xstore-core' ),
        'right'  => esc_html__( 'Right side', 'xstore-core' ),
        'custom' => esc_html__( 'Custom', 'xstore-core' )
    ),
    'header_vertical_elements' => array(
        'logo'           => esc_html__( 'Logo', 'xstore-core' ),
        'menu'           => esc_html__( 'Menu', 'xstore-core' ),
        'wishlist'       => esc_html__( 'Wishlist', 'xstore-core' ),
        'cart'           => esc_html__( 'Cart', 'xstore-core' ),
        'account'        => esc_html__( 'Account', 'xstore-core' ),
        'header_socials' => esc_html__( 'Socials', 'xstore-core' ),
        'html_block1'    => esc_html__( 'HTML block 1', 'xstore-core' ),
        'html_block2'    => esc_html__( 'HTML block 2', 'xstore-core' ),
        'html_block3'    => esc_html__( 'HTML block 3', 'xstore-core' ),
    ),
    'border_style'             => array(
        'dotted' => esc_html__( 'Dotted', 'xstore-core' ),
        'dashed' => esc_html__( 'Dashed', 'xstore-core' ),
        'solid'  => esc_html__( 'Solid', 'xstore-core' ),
        'double' => esc_html__( 'Double', 'xstore-core' ),
        'groove' => esc_html__( 'Groove', 'xstore-core' ),
        'ridge'  => esc_html__( 'Ridge', 'xstore-core' ),
        'inset'  => esc_html__( 'Inset', 'xstore-core' ),
        'outset' => esc_html__( 'Outset', 'xstore-core' ),
        'none'   => esc_html__( 'None', 'xstore-core' ),
        'hidden' => esc_html__( 'Hidden', 'xstore-core' ),
    ),
    'colors'                   => array(
        'current' => esc_html__( 'Default', 'xstore-core' ),
        'custom'  => esc_html__( 'Custom', 'xstore-core' ),
    ),
    'product_types'            => array(
        'grid'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/grid.svg',
        'slider' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/slider.svg',
        'widget' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/widget.svg',
    )
);

$choices['alignment_with_inherit']  = $choices['alignment'];
$choices['alignment2_with_inherit'] = $choices['alignment2'];

$choices['alignment_with_inherit']['inherit'] = $choices['alignment2_with_inherit']['inherit'] = esc_html__( 'Inherit', 'xstore-core' );

$box_models = array(
    'empty' => array(
        'margin-top'          => '0px',
        'margin-right'        => '0px',
        'margin-bottom'       => '0px',
        'margin-left'         => '0px',
        'border-top-width'    => '0px',
        'border-right-width'  => '0px',
        'border-bottom-width' => '0px',
        'border-left-width'   => '0px',
        'padding-top'         => '0px',
        'padding-right'       => '0px',
        'padding-bottom'      => '0px',
        'padding-left'        => '0px',
    )
);

$box_models['col_paddings']                  = $box_models['empty'];
$box_models['col_paddings']['padding-right'] = $box_models['col_paddings']['padding-left'] = '15px';

function box_model_output( $selector ) {
    $properties = array(
        'margin-top',
        'margin-right',
        'margin-bottom',
        'margin-left',

        'padding-top',
        'padding-right',
        'padding-bottom',
        'padding-left',

        'border-top-width',
        'border-right-width',
        'border-bottom-width',
        'border-left-width',
    );

    $return = array();

    foreach ( $properties as $key ) {
        $return[] = array(
            'choice'   => $key,
            'element'  => $selector,
            'type'     => 'css',
            'property' => $key
        );
    }

    return $return;
}

$product_settings = array(
    'view'       => array(
        'disable' => esc_html__( 'Disable', 'xstore-core' ),
        'default' => esc_html__( 'Default', 'xstore-core' ),
        'mask3'   => esc_html__( 'Buttons on hover middle', 'xstore-core' ),
        'mask'    => esc_html__( 'Buttons on hover bottom', 'xstore-core' ),
        'mask2'   => esc_html__( 'Buttons on hover right', 'xstore-core' ),
        'info'    => esc_html__( 'Information mask', 'xstore-core' ),
        'booking' => esc_html__( 'Booking', 'xstore-core' ),
        'light'   => esc_html__( 'Light', 'xstore-core' ),
        'inherit' => esc_html__( 'Inherit', 'xstore-core' ),
//			'custom'  => esc_html__( 'Custom', 'xstore-core' )
    ),
    'view_color' => array(
        'white'       => esc_html__( 'White', 'xstore-core' ),
        'dark'        => esc_html__( 'Dark', 'xstore-core' ),
        'transparent' => esc_html__( 'Transparent', 'xstore-core' ),
        'inherit'     => esc_html__( 'Inherit', 'xstore-core' )
    ),
    'img_hover'  => array(
        'disable' => esc_html__( 'Disable', 'xstore-core' ),
        'swap'    => esc_html__( 'Swap', 'xstore-core' ),
        'back-zoom-in'    => esc_html__( 'Back Image - Zoom In', 'xstore-core' ),
        'back-zoom-out'    => esc_html__( 'Back Image - Zoom Out', 'xstore-core' ),
        'zoom-in'    => esc_html__( 'Zoom In', 'xstore-core' ),
        'slider'  => esc_html__( 'Images Slider', 'xstore-core' ),
        'carousel'=> esc_html__( 'Smart Carousel', 'xstore-core' ),
        'inherit' => esc_html__( 'Inherit', 'xstore-core' )
    )
);

$icons = array(
    'simple'  => array(
        'et_icon-delivery'        => esc_html__( 'Delivery', 'xstore-core' ),
        'et_icon-coupon'          => esc_html__( 'Coupon', 'xstore-core' ),
        'et_icon-calendar'        => esc_html__( 'Calendar', 'xstore-core' ),
        'et_icon-compare'         => esc_html__( 'Compare', 'xstore-core' ),
        'et_icon-checked'         => esc_html__( 'Checked', 'xstore-core' ),
        'et_icon-chat'            => esc_html__( 'Chat', 'xstore-core' ),
        'et_icon-phone'           => esc_html__( 'Phone', 'xstore-core' ),
        'et_icon-exclamation'     => esc_html__( 'Exclamation', 'xstore-core' ),
        'et_icon-gift'            => esc_html__( 'Gift', 'xstore-core' ),
        'et_icon-heart'           => esc_html__( 'Heart', 'xstore-core' ),
        'et_icon-heart-2'         => esc_html__( 'Heart 2', 'xstore-core' ),
        'et_icon-message'         => esc_html__( 'Message', 'xstore-core' ),
        'et_icon-internet'        => esc_html__( 'Internet', 'xstore-core' ),
        'et_icon-account'         => esc_html__( 'Account', 'xstore-core' ),
        'et_icon-sent'            => esc_html__( 'Sent', 'xstore-core' ),
        'et_icon-home'            => esc_html__( 'Home', 'xstore-core' ),
        'et_icon-shop'            => esc_html__( 'Shop', 'xstore-core' ),
        'et_icon-shopping-basket' => esc_html__( 'Basket', 'xstore-core' ),
        'et_icon-shopping-bag'    => esc_html__( 'Bag', 'xstore-core' ),
        'et_icon-shopping-bag-2'  => esc_html__( 'Bag 2', 'xstore-core' ),
        'et_icon-shopping-bag-3'  => esc_html__( 'Bag 3', 'xstore-core' ),
        'et_icon-shopping-cart'   => esc_html__( 'Cart', 'xstore-core' ),
        'et_icon-shopping-cart-2' => esc_html__( 'Cart 2', 'xstore-core' ),
        'et_icon-burger'          => esc_html__( 'Burger', 'xstore-core' ),
        'et_icon-star'            => esc_html__( 'Star', 'xstore-core' ),
        'et_icon-time'            => esc_html__( 'Time', 'xstore-core' ),
        'et_icon-zoom'            => esc_html__( 'Search', 'xstore-core' ),
        'et_icon-size'            => esc_html__( 'Size', 'xstore-core' ),
        'et_icon-location'        => esc_html__( 'Location', 'xstore-core' ),
        'et_icon-dev-menu'        => esc_html__( 'Dev menu', 'xstore-core' ),
        'et_icon-conversation'    => esc_html__( 'Conversation', 'xstore-core' ),
        'et_icon-clock'           => esc_html__( 'Clock', 'xstore-core' ),
        'et_icon-file'            => esc_html__( 'File', 'xstore-core' ),
        'et_icon-share'           => esc_html__( 'Share', 'xstore-core' ),
        'et_icon-more'            => esc_html__( 'More', 'xstore-core' ),
        'none'                    => esc_html__( 'Without Icon', 'xstore-core' ),
    ),
    'socials' => array(
        'et_icon-behance'     => esc_html__( 'Behance', 'xstore-core' ),
        'et_icon-facebook'    => esc_html__( 'Facebook', 'xstore-core' ),
        'et_icon-houzz'       => esc_html__( 'Houzz', 'xstore-core' ),
        'et_icon-instagram'   => esc_html__( 'Instagram', 'xstore-core' ),
        'et_icon-linkedin'    => esc_html__( 'Linkedin', 'xstore-core' ),
        'et_icon-pinterest'   => esc_html__( 'Pinterest', 'xstore-core' ),
        'et_icon-rss'         => esc_html__( 'Rss', 'xstore-core' ),
        'et_icon-skype'       => esc_html__( 'Skype', 'xstore-core' ),
        'et_icon-snapchat'    => esc_html__( 'Snapchat', 'xstore-core' ),
        'et_icon-tripadvisor' => esc_html__( 'Tripadvisor', 'xstore-core' ),
        'et_icon-telegram'    => esc_html__( 'Telegram', 'xstore-core' ),
        'et_icon-tumblr'      => esc_html__( 'Tumblr', 'xstore-core' ),
        'et_icon-twitter'     => esc_html__( 'Twitter', 'xstore-core' ),
        'et_icon-vimeo'       => esc_html__( 'Vimeo', 'xstore-core' ),
        'et_icon-etsy'        => esc_html__( 'Etsy', 'xstore-core' ),
        'et_icon-tik-tok'     => esc_html__( 'Tik-tok', 'xstore-core' ),
        'et_icon-twitch'      => esc_html__( 'Twitch', 'xstore-core' ),
        'et_icon-untapped'    => esc_html__( 'Untapped', 'xstore-core' ),
        'et_icon-vk'          => esc_html__( 'Vk', 'xstore-core' ),
        'et_icon-whatsapp'    => esc_html__( 'Whatsapp', 'xstore-core' ),
        'et_icon-youtube'     => esc_html__( 'Youtube', 'xstore-core' ),
        'et_icon-discord'     => esc_html__( 'Discord', 'xstore-core' ),
        'et_icon-reddit'      => esc_html__( 'Reddit', 'xstore-core' ),
        'et_icon-strava'      => esc_html__( 'Strava', 'xstore-core' ),
        'et_icon-patreon'     => esc_html__( 'Patreon', 'xstore-core' ),
        'et_icon-line'        => esc_html__( 'Line', 'xstore-core' ),
        'et_icon-kofi'        => esc_html__( 'Kofi', 'xstore-core' ),
        'et_icon-dribbble'    => esc_html__( 'Dribbble', 'xstore-core' ),
        'et_icon-cafecito'    => esc_html__( 'Cafecito', 'xstore-core' ),
        'et_icon-viber'       => esc_html__( 'Viber', 'xstore-core' ),
    )
);