 <?php $global_admin_class->xstore_panel_settings_multicheckbox_field( $tab_content,
                'elements',
                esc_html__( 'Elements', 'xstore-core' ),
                esc_html__( 'Use this option to enable/disable popup elements.', 'xstore-core' ),
                array(
                    'image'    => esc_html__( 'Image', 'xstore-core' ),
                    'title'    => esc_html__( 'Title', 'xstore-core' ),
                    'price'    => esc_html__( 'Price', 'xstore-core' ),
                    'time'     => esc_html__( 'Time ago (hours, mins)', 'xstore-core' ),
                    'location' => esc_html__( 'Location', 'xstore-core' ),
                    'button'   => esc_html__( 'Button', 'xstore-core' ),
                    'close'    => esc_html__( 'Close', 'xstore-core' ),
                ),
                array(
                    'image',
                    'title',
                    'time',
                    'location',
                    'button',
                    'close',
                )
            ); ?>

<?php $global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
    'bag_icon',
    esc_html__( 'Bag emoji icon', 'xstore-core' ),
    esc_html__( 'Write emoji icon, 1 (to leave default one) or leave empty to remove it', 'xstore-core' ),
    false,
    1 ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'products_type',
    esc_html__( 'Show product source', 'xstore-core' ),
    false,
    array(
//                                        'recently_viewed' => esc_html__('Recently viewed', 'xstore-core'),
        'featured'     => esc_html__( 'Featured', 'xstore-core' ),
        'sale'         => esc_html__( 'On sale', 'xstore-core' ),
        'bestsellings' => esc_html__( 'Bestsellings', 'xstore-core' ),
        'orders'       => esc_html__( 'From real orders', 'xstore-core' ),
        'random'       => esc_html__( 'Random', 'xstore-core' ),
    ),
    'random' ); ?>

<?php $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'hide_outofstock_products',
    esc_html__( 'Hide out of stock products', 'xstore-core' ),
    false,
    false ); ?>

<?php $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'play_sound',
    esc_html__( 'Sound notification', 'xstore-core' ),
    esc_html__( 'Modern browsers recently changed their policy to let users able to disable auto play audio so this option is not working correctly now. ', 'xstore-core' ) .
    '<a href="https://developers.google.com/web/updates/2017/09/autoplay-policy-changes" target="_blank">' . esc_html__( 'More details', 'xstore-core' ) . '</a>' ); ?>

<?php $global_admin_class->xstore_panel_settings_upload_field( $tab_content,
    'sound_file',
    esc_html__( 'Custom audio file', 'xstore-core' ),
    false,
    'audio' ); ?>

<?php $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'show_on_mobile',
    esc_html__( 'Show on mobile', 'xstore-core' ),
    false,
    true ); ?>

<?php $global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
    'locations',
    esc_html__( 'Locations description', 'xstore-core' ),
    '{{{Washington D.C., USA 🇺🇸}}}; {{{London, UK 🇬🇧}}}; {{{New Delhi, India 🇮🇳}}} <span class="mtips mtips-left"><span class="dashicons dashicons-warning"></span><span class="mt-mes">' . esc_html__( 'Locations don\'t work if product source equals From real orders', 'xstore-core' ) . '</span></span>',
    '{{{Washington D.C., USA 🇺🇸}}}; {{{London, UK 🇬🇧}}}; {{{Madrid, Spain 🇪🇸}}}; {{{Berlin, Germany 🇩🇪}}}; {{{New Delhi, India 🇮🇳}}}; {{{Ottawa, Canada 🇨🇦}}}; {{{Paris, France 🇫🇷}}}; {{{Rome, Italy 🇮🇹}}}; {{{Dhaka, Bangladesh 🇧🇩}}}; {{{Kyiv, Ukraine 🇺🇦}}}; {{{Islamabad, Pakistan 🇵🇰}}}; {{{Athens, Greece 🇬🇷}}}; {{{Brasilia, Brazil 🇧🇷}}}; {{{Lima, Peru 🇵🇪}}}; {{{Ankara, Turkey 🇹🇷}}}; {{{Colombo, Sri Lanka 🇱🇰}}}; {{{Warsaw, Poland 🇵🇱}}}; {{{Amsterdam, Netherlands 🇳🇱}}}; {{{Mexico City, Mexico 🇲🇽}}}; {{{Canberra, Australia 🇦🇺}}}' ); ?>

<?php $global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
    'customers',
    esc_html__( 'Custom customers\' name list', 'xstore-core' ),
    esc_html__('You have the option to enter the names of your customers in the provided format, using placeholders like {{{Customer 01}}}; {{{Customer 02}}}; and so on. However, if you have obtained permission from your customers, you can use their real names instead of placeholders. Please refer to the next option above this one for further details. Additionally, don\'t forget to switch the "Show product source" option to "From real products."', 'xstore-core'),
    '' ); ?>

<?php $global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
    'orders_customers',
    esc_html__( 'Show real customers names', 'xstore-core' ),
    esc_html__('By activating this option, you can add authenticity and social proof to your website, showcasing real people who have experienced positive results. This feature is designed to boost trust and confidence among potential customers, as they can see genuine testimonials from satisfied customers.', 'xstore-core'),
    false,
    array(
        array(
            'name'    => 'products_type',
            'value'   => 'orders',
            'section' => $tab_content,
            'default' => 'random'
        ),
    ) ); ?>

<?php $global_admin_class->xstore_panel_settings_slider_field( $tab_content,
    'repeat_every',
    esc_html__( 'Repeat every x seconds', 'xstore-core' ),
    false,
    3,
    500,
    15,
    1,
    's' ); ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'animation_type',
    esc_html__( 'Popup animation', 'xstore-core' ),
    false,
    array(
        'slide_right' => esc_html__( 'Slide right', 'xstore-core' ),
        'slide_up'    => esc_html__( 'Slide up', 'xstore-core' ),
    ) ); ?>
