<?php
    $xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
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

<div class="et_popup-theme-required_plugins et_panel-popup-inner with-scroll text-left">
    <?php // echo '<div class="image-block">'.$settings['logo'].'</div>' ?>
    <div class="steps-block-content" style="width: 100%;">
        <div class="et-col-12 etheme-theme-builders" style="width: 100%;">
            <h3><?php echo sprintf(esc_html__('Required plugins for "%s"', 'xstore'), $_POST['popup_heading']); ?></h3>
            <br/>
            <?php
                if ( isset($_POST['feature']) && $_POST['feature'] ) {
                    $description = '';
//                    switch ($_POST['feature']) {
//                        case 'sales_booster':
                            $description = sprintf(esc_html__('"%s" requires specific plugins to enhance its functionality and optimize your website\'s sales performance. Install them now to unlock full potential of %s theme!', 'xstore'),
                                $_POST['popup_heading'],
                                apply_filters('etheme_theme_label', 'XStore')
                            );
//                            break;
//                    }
                    if ( $description )
                        echo '<p>'.$description.'</p>';
                }
            ?>
            <?php
                $plugins_packages = array(
                'woocommerce' =>
                    array(
                        'logo' => '<svg width="42" height="42" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<circle cx="10.5" cy="10.5" r="10.5" fill="#7F54B3"/>
<path d="M15.787 7H5.21296C4.54302 7 4 7.54306 4 8.21296V12.1944C4 12.8643 4.54311 13.4073 5.21296 13.4073H10.2452L12.4755 14.7031L12.0301 13.4073H15.787C16.457 13.4073 17 12.8643 17 12.1944V8.21296C17 7.54302 16.4569 7 15.787 7Z" fill="white"/>
<path d="M5.89059 12.8019C5.88876 12.8019 5.88702 12.8019 5.88523 12.8019C5.73943 12.8001 5.6037 12.7273 5.5217 12.6067C5.39219 12.4162 5.20232 11.9771 4.88119 10.2578C4.70906 9.33628 4.57967 8.46843 4.5784 8.45979C4.54216 8.21608 4.71038 7.9892 4.95409 7.95296C5.19764 7.91668 5.42468 8.0849 5.46092 8.32866C5.46215 8.33713 5.58865 9.18511 5.75695 10.0872C5.85418 10.6083 5.93734 10.997 6.00641 11.2862C6.42706 10.511 6.94053 9.46851 7.16655 8.99771C7.24932 8.82537 7.43264 8.7249 7.62259 8.748C7.81246 8.77109 7.96639 8.9126 8.00543 9.09979C8.07931 9.45316 8.27071 10.189 8.54263 10.8568C8.64292 9.90728 8.837 8.70223 9.207 7.94374C9.31499 7.72231 9.5821 7.63035 9.80353 7.73838C10.025 7.84642 10.1169 8.11349 10.0089 8.33491C9.42056 9.54099 9.33247 12.2884 9.33162 12.316C9.32635 12.494 9.21576 12.6517 9.0503 12.7174C8.88481 12.783 8.69617 12.7441 8.57027 12.6182C8.17059 12.2185 7.80625 11.5297 7.48729 10.571C7.47351 10.5295 7.46011 10.4885 7.44705 10.4479C7.056 11.2262 6.54195 12.2123 6.25336 12.6156C6.16948 12.7326 6.03435 12.8019 5.89059 12.8019Z" fill="#7F54B3"/>
<path d="M12.8157 9.03749C12.63 8.66018 12.3109 8.39928 11.894 8.28329C11.4185 8.15131 10.9034 8.32685 10.5158 8.7535C10.0558 9.25879 9.7203 10.2059 10.1576 11.3472C10.4572 12.1294 10.9283 12.2729 11.2327 12.2729C11.2656 12.2729 11.2962 12.2711 11.3247 12.2685C12.1349 12.1916 12.8153 11.1196 12.9464 10.3846C13.0406 9.85792 12.9966 9.4046 12.8157 9.03749ZM12.0682 10.2277C12.0269 10.4574 11.8833 10.7614 11.7016 11.0018C11.5149 11.2494 11.3318 11.3716 11.2407 11.3801C11.1851 11.3854 11.082 11.2663 10.9909 11.028C10.8078 10.5507 10.7465 9.82542 11.1758 9.35343C11.3038 9.21256 11.4527 9.13298 11.5807 9.13298C11.6065 9.13298 11.6314 9.13608 11.6549 9.14276C12.1327 9.27568 12.1433 9.8059 12.0682 10.2277Z" fill="#7F54B3"/>
<path d="M16.1299 9.03749C16.0365 8.84907 15.9103 8.68906 15.7557 8.5624C15.6001 8.43573 15.4161 8.34152 15.2077 8.28329C14.7326 8.15131 14.2175 8.32685 13.8295 8.7535C13.37 9.25879 13.0344 10.2059 13.4713 11.3472C13.7708 12.1294 14.2419 12.2729 14.5463 12.2729C14.5792 12.2729 14.6104 12.2711 14.6388 12.2685C15.4485 12.1916 16.1289 11.1196 16.2601 10.3846C16.3543 9.85792 16.3103 9.4046 16.1299 9.03749ZM15.3819 10.2277C15.341 10.4574 15.197 10.7614 15.0157 11.0018C14.8286 11.2494 14.6455 11.3716 14.5543 11.3801C14.4992 11.3854 14.3957 11.2663 14.3046 11.028C14.1219 10.5507 14.0606 9.82542 14.4895 9.35343C14.6179 9.21256 14.7668 9.13298 14.8944 9.13298C14.9201 9.13298 14.945 9.13608 14.969 9.14276C15.4468 9.27568 15.457 9.8059 15.3819 10.2277Z" fill="#7F54B3"/>
</svg>
',
                        'price' => esc_html__('Free', 'xstore'),
                        'is_free' => true,
                        'is_installed' => class_exists('WooCommerce'),
                        'title' => esc_html__('WooCommerce', 'xstore'),
                    ),
                'et-core-plugin' =>
                    array(
                        'logo' => '<svg width="42" height="42" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<circle cx="10.5" cy="10.5" r="10.5" fill="#222222"/>
<path d="M8.05094 10.5188L5.12689 6H6.82678C6.94518 6 7.03066 6.01616 7.08375 6.04831C7.13649 6.08046 7.1841 6.13308 7.22639 6.206L9.31315 9.62387C9.33439 9.56819 9.35757 9.51342 9.38306 9.45972C9.40837 9.4062 9.43792 9.35142 9.47173 9.29557L11.381 6.23814C11.4739 6.07956 11.5944 6 11.7425 6H13.3791L10.4232 10.4477L13.4615 15.3077H11.7553C11.6411 15.3077 11.5491 15.2779 11.4794 15.2175C11.4095 15.1576 11.3514 15.0889 11.3049 15.0117L9.18006 11.4457C9.16307 11.497 9.14396 11.5455 9.1229 11.5904C9.10166 11.6357 9.0806 11.6774 9.05954 11.7162L7.0234 15.0117C6.97685 15.0846 6.91969 15.1522 6.85208 15.2143C6.78448 15.2764 6.69988 15.3077 6.59847 15.3077H5L8.05094 10.5188Z" fill="white"/>
<path d="M13.2524 10.4478L15.9245 6H14.4444C14.3789 6 14.3199 6.01839 14.2643 6.05517L12.257 9.60167C12.257 9.60903 12.2537 9.61637 12.2504 9.62374L12.2471 9.62006L11.769 10.4625L14.428 15.304C14.4378 15.3077 14.4477 15.3077 14.4575 15.3077H15.9998L13.2524 10.4478Z" fill="white"/>
</svg>
',
//                        'price' => esc_html__('Free', 'xstore'),
//                        'is_free' => true,
                        'is_installed' => class_exists('ETC\App\Controllers\Admin\Import'),
                        'title' => esc_html__('XStore Core', 'xstore'),
                    ),
                'elementor' =>
                    array(
                        'logo' => '<svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="26" cy="26" r="26" fill="black"/>
                            <path d="M17.0532 35.1873L17.0532 15.6873H20.9532L20.9532 35.1873H17.0532Z" fill="#F6F6F6"/>
                            <path d="M24.8533 15.6877H36.5533V19.5877H24.8533V15.6877Z" fill="#F6F6F6"/>
                            <path d="M24.8533 23.4872H36.5533V27.3872H24.8533V23.4872Z" fill="#F6F6F6"/>
                            <path d="M24.8533 31.2868H36.5533V35.1868H24.8533V31.2868Z" fill="#F6F6F6"/>
                        </svg>',
                        'price' => esc_html__('Free', 'xstore'),
                        'is_free' => true,
                        'is_installed' => defined( 'ELEMENTOR_VERSION' ),
                        'title' => esc_html__('Elementor', 'xstore'),
                    )
            );
            ?>
            <div class="theme-builders-plugins flex flex-wrap justify-content-between">
            <?php
            $make_merge_keys = true;
            $all_plugins_installed = true;
            $plugins_list = $_POST['plugins'];
                foreach ($_POST['plugins'] as $plugin_slug => $plugin_value) {
                    $value_2_check = $plugin_slug;
                    if ( is_integer($plugin_slug) ) {
                        $value_2_check = $plugin_value;
                        $make_merge_keys = false;
                    }
                    if ( $plugins_packages[$value_2_check]['is_installed']) {
                        if ( $make_merge_keys )
                            unset($plugins_list[$plugin_slug]);
                        else {
                            unset($plugins_list[array_search($plugin_slug, $plugins_list)]);
                        }
                        continue;
                    }
                    ?>
                    <div class="theme-builders-plugin flex align-items-center<?php if ( isset($plugins_packages[$value_2_check]['is_free']) ) echo ' is-free'; ?>">
                        <?php echo '<span class="theme-builders-plugin-logo">'. $plugins_packages[$value_2_check]['logo'] . '</span>'; ?>
                        <div class="theme-builders-plugin-details">
                            <h4><?php echo implode(' ', array(
                                    $plugins_packages[$value_2_check]['title'],
                                    (isset($plugins_packages[$value_2_check]['is_free']) ? '<span class="et-title-label green-color">'.$plugins_packages[$value_2_check]['price'].'</span>' : ''))); ?></h4>
                        </div>
                    </div>
                    <?php
                    $all_plugins_installed = false;
                }
                if ( $all_plugins_installed ) {?>
                    <a href="<?php echo esc_url(admin_url( 'admin.php?page=et-panel-welcome' )); ?>" class="et-button et-button-green no-loader">
                        <?php
                        echo esc_html__( 'Refresh Page', 'xstore' );
                        ?>
                    </a>
                <?php
                }
            ?>
            </div>
            <?php if ( !$all_plugins_installed ) : ?>
                <br/><br/>

                <a href="<?php echo esc_url(add_query_arg('plugin', implode(',', ($make_merge_keys ? array_keys($plugins_list) : $plugins_list)), admin_url( 'admin.php?page=et-panel-plugins' ))) ?>" class="et-button et-button-green no-loader">
                    <?php
                        echo _n( 'Install Plugin', 'Install Plugins', count($plugins_list), 'xstore' );
                    ?>
                </a>
            <?php endif; ?>
    </div>
    </div>

</div>