<?php
    $global_admin_class = EthemeAdmin::get_instance();
    $xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
    $has_pro = defined( 'ELEMENTOR_PRO_VERSION' );
    $settings = array();
    $settings['brand_label'] = 'XStore';
    $settings['support_url'] = 'https://www.8theme.com/forums/';
    if ( count( $xstore_branding_settings ) ) {
        if (isset($xstore_branding_settings['control_panel']['label']) && !empty($xstore_branding_settings['control_panel']['label'])) {
            $settings['brand_label'] = $xstore_branding_settings['control_panel']['label'];
        }

        if ( isset($xstore_branding_settings['plugins_data']) && isset($xstore_branding_settings['plugins_data']['support_url']) && !empty($xstore_branding_settings['plugins_data']['support_url'])) {
            $settings['support_url'] = $xstore_branding_settings['plugins_data']['support_url'];
        }
    }
?>

<div class="et_popup-theme-registration et_panel-popup-inner with-scroll et_popup-content-remove text-left">
    <?php // echo '<div class="image-block">'.$settings['logo'].'</div>' ?>
    <div class="steps-block-content" style="width: 100%;">

        <div class="et-col-12 etheme-theme-builders" style="width: 100%;">
            <?php
                $is_pro_elements = function_exists('pro_elements_plugin_load_plugin');

                $theme_builders_plugins = array(
                    'pro-elements' =>
                        array(
                            'logo' => '<svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <circle cx="26" cy="26" r="26" fill="url(#pattern0)"/>
                                <defs>
                                    <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                                        <use xlink:href="#image0_1981_2436" transform="translate(0 -0.0172414) scale(0.00431034)"/>
                                    </pattern>
                                    <image id="image0_1981_2436" width="232" height="240" xlink:href="data:image/png;base64,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"/>
                                </defs>
                            </svg>',
                            'title' => esc_html__('PRO Elements', 'xstore'),
                            'price' => esc_html__('Free', 'xstore'),
                            'is_free' => true,
                            'is_installed' => false,
                            'description' => sprintf(esc_html__('If you prefer the free option, you can download and install the PRO Elements plugin from the %sofficial website%s or GitHub.', 'xstore'), '<a href="https://proelements.org/" target="_blank">', '</a>'),
                            'url' => 'https://proelements.org/'
                        ),
                    'elementor-pro' =>
                        array(
                            'logo' => '<svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="26" cy="26" r="26" fill="black"/>
                                <path d="M17.0532 35.1873L17.0532 15.6873H20.9532L20.9532 35.1873H17.0532Z" fill="#F6F6F6"/>
                                <path d="M24.8533 15.6877H36.5533V19.5877H24.8533V15.6877Z" fill="#F6F6F6"/>
                                <path d="M24.8533 23.4872H36.5533V27.3872H24.8533V23.4872Z" fill="#F6F6F6"/>
                                <path d="M24.8533 31.2868H36.5533V35.1868H24.8533V31.2868Z" fill="#F6F6F6"/>
                            </svg>',
                            'title' => esc_html__('Elementor Pro', 'xstore'),
                            'price' => esc_html__('From $59', 'xstore'),
                            'is_installed' => false,
                            'description' => esc_html__('If you have already purchased Elementor Pro, go ahead and install it on your website.', 'xstore'),
                            'url' => 'https://elementor.com/pro/'
                        )
                );
                if ( !$has_pro ) {
                    $all_plugins = get_plugins();
                    $theme_builders_plugins['elementor-pro']['is_installed'] = array_key_exists('elementor-pro/elementor-pro.php', $all_plugins);

                    if ( $theme_builders_plugins['elementor-pro']['is_installed'] )
                        $theme_builders_plugins['elementor-pro']['url'] = admin_url('plugins.php');
                    $theme_builders_plugins['pro-elements']['is_installed'] = array_key_exists('pro-elements/pro-elements.php', $all_plugins);

                    if ( $theme_builders_plugins['pro-elements']['is_installed'] )
                        $theme_builders_plugins['pro-elements']['url'] = admin_url('plugins.php');
                }
            if ( !$has_pro ) : ?>
                <h3><?php echo esc_html__('Required Plugin for this Builder', 'xstore'); ?></h3>
                <p><?php echo sprintf(esc_html__('IMPORTANT: To maximize the functionality of this Builder, it is essential to have either the Free %s or the %s plugin installed and activated on your website.', 'xstore'),
                        '<a href="'.$theme_builders_plugins['pro-elements']['url'].'" target="_blank" rel="nofollow">'.$theme_builders_plugins['pro-elements']['title'].'</a>',
                        '<a href="'.$theme_builders_plugins['elementor-pro']['url'].'" target="_blank" rel="nofollow">'.$theme_builders_plugins['elementor-pro']['title'].'</a>'); ?></p>

                <div class="theme-builders-plugins flex justify-content-between">
                    <?php
                    $system              = new Etheme_System_Requirements();
                    $system_requirements = $system->requirements;
                    $system_status       = $system->get_system( true );
                    $theme_builders_plugins_count = 0;

                    foreach ($theme_builders_plugins as $theme_builders_plugin_key => $theme_builders_plugin_details) {
                        if ( $theme_builders_plugins_count > 0 ) { ?>
                            <div class="theme-builder-plugin-separator">
                                <?php echo esc_html__('Or', 'xstore'); ?>
                            </div>
                        <?php } ?>
                        <div class="theme-builders-plugin<?php if ( isset($theme_builders_plugin_details['is_free']) ) echo ' is-free'; ?>">
                            <?php echo '<span class="theme-builders-plugin-logo">'.
                                       $theme_builders_plugin_details['logo'] .
                                       '</span>'; ?>
                            <div class="theme-builders-plugin-details">
                                <h4><?php echo implode(' ', array(
                                        $theme_builders_plugin_details['title'],
                                        '<span class="et-title-label'.(isset($theme_builders_plugin_details['is_free']) ? ' green-color' : '').'">'.$theme_builders_plugin_details['price'].'</span>')); ?></h4>
                                <?php echo '<p>'.$theme_builders_plugin_details['description'].'</p>'; ?>

                                <?php // if this is free plugin then we can install/activate it with our code ?>
                                <?php if (isset($theme_builders_plugin_details['is_free'])) : ?>
                                    <span class="et_plugin-control et-button et-button-sm et-button-green " data-slug="pro-elements" data-action="<?php echo $theme_builders_plugin_details['is_installed'] ? 'activate' : 'install'; ?>">
                                        <?php echo $theme_builders_plugin_details['is_installed'] ? esc_html__('Activate', 'xstore') : esc_html__('Install', 'xstore'); ?>
                                        <?php $global_admin_class->get_loader(true); ?>
                                    </span>
                                    <p class="et-message et-error builder-plugin-install hidden"></p>
                                <?php
                                else: ?>
                                    <a href="<?php echo esc_url($theme_builders_plugin_details['url']) ?>" target="_blank" class="et-button et-button-sm et-button-grey2 no-loader">
                                        <?php
                                        echo esc_html__('Learn more', 'xstore');
                                        ?>
                                    </a>
                            <?php endif; ?>

                            </div>
                        </div>
                    <?php $theme_builders_plugins_count++;
                    }
                    ?>
                    <span class="hidden et_plugin-nonce" data-plugin-nonce="<?php echo wp_create_nonce( 'envato_setup_nonce' ); ?>"></span>
                    <span class="hidden et_filesystem" data-filesystem="<?php echo esc_js(!($system_status['filesystem'] != $system_requirements['filesystem'])); ?>"></span>
                </div>
            <?php endif;
            ?>
    </div>
    </div>

</div>
