<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
/**
 * Template "Welcome" for 8theme dashboard.
 *
 * @since   6.0.2
 * @version 1.0.0
 */
?>

<?php
$system = class_exists('Etheme_System_Requirements') ? Etheme_System_Requirements::get_instance() : new Etheme_System_Requirements();
//    $system->html();
$system->system_test();
$result       = $system->result();
$is_activated = etheme_is_activated();
$is_et_core = defined( 'ET_CORE_VERSION' );
$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
$xstore_branding_settings_inited = false;

$settings                 = array();
$settings['welcome_text'] = 'Welcome to XStore!';
$settings['brand_logo'] = ETHEME_CODE_IMAGES . 'wp-icon.svg';
$settings['hide_registration_form'] = false;
$settings['hide_buy_license'] = false;

if ( count($xstore_branding_settings) && isset($xstore_branding_settings['control_panel']) ) {
    $xstore_branding_settings_inited = true;
    if ( $xstore_branding_settings['control_panel']['icon'] ) {
        $settings['brand_logo'] = $xstore_branding_settings['control_panel']['icon'];
    }

    if ( $xstore_branding_settings['control_panel']['welcome_text'] ) {
        $settings['welcome_text'] = $xstore_branding_settings['control_panel']['welcome_text'];
    }

    if ( $xstore_branding_settings['control_panel']['thank_you_text'] ) {
        $settings['thank_you_text'] = $xstore_branding_settings['control_panel']['thank_you_text'];
    }

    if ( isset($xstore_branding_settings['control_panel']['hide_registration_form']) ){
        $settings['hide_registration_form'] = $xstore_branding_settings['control_panel']['hide_registration_form'];
    }

    if ( isset($xstore_branding_settings['control_panel']['hide_buy_license']) ){
        $settings['hide_buy_license'] = $xstore_branding_settings['control_panel']['hide_buy_license'];
    }
}

// in case carousel banners should be displayed
if ( !$xstore_branding_settings_inited ) {
    wp_enqueue_script('slick_carousel');
    wp_enqueue_style('slick-carousel');
}
?>

<?php if ( ! $is_et_core && $is_activated && ! class_exists( 'ETC\App\Controllers\Admin\Import' ) ): ?>

    <p class="et-message et-error flex align-items-center justify-content-between">
        <?php esc_html_e( 'The following required plugin is currently inactive: ', 'xstore' ); ?>
        <?php echo 'XStore Core'; ?>

        <a href="<?php echo admin_url( 'admin.php?page=et-panel-plugins&plugin=et-core-plugin' ); ?>" class="et_core-plugin et-button et-button-green no-transform"
           data-slug="et-core-plugin"><?php echo esc_html__( 'Activate', 'xstore' ); ?>
            <?php
                $global_admin_class = EthemeAdmin::get_instance();
                $global_admin_class->get_loader();
            ?>
        </a>
        <span class="hidden et_plugin-nonce"
              data-plugin-nonce="<?php echo wp_create_nonce( 'envato_setup_nonce' ); ?>"></span>
    </p>
    <?php endif; ?>

    <?php if ( ! $result ) : ?>
        <p class="et-message et-error"><?php esc_html_e( 'Your system does not meet the server requirements. For more efficient result, we strongly recommend to contact your host provider and check the necessary settings.', 'xstore' ); ?></p>
    <?php endif; ?>
<?php if ( !$xstore_branding_settings_inited ) : ?>
<div class="xstore-panel-grid-wrapper">
        <div class="xstore-panel-grid-item">
<?php endif; ?>
            <div class="xstore-panel-info-blocks<?php if ( !$xstore_branding_settings_inited ) echo ' one-col'; ?>">
                <div class="xstore-panel-info-block type-2">
                    <div>
                        <h2>
                            <svg width="24" height="24" viewBox="0 0 28 26" fill="var(--et_admin_dark2white-color, var(--et_admin_dark-color, #222))" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.61728 0C6.82129 0 6.02126 0.303041 5.41922 0.905082C4.96668 1.35762 4.69192 1.93138 4.57879 2.5213C4.46969 2.50918 4.36464 2.48897 4.25555 2.48897C3.45956 2.48897 2.65953 2.79202 2.05749 3.39406C0.954422 4.49712 0.881692 6.25476 1.7989 7.46692C1.33019 7.68107 0.897854 8.01644 0.57057 8.46898C-0.354714 9.75791 -0.104201 11.4792 0.958462 12.5418L0.990787 12.5742L12.8215 24.0816C15.1569 26.3928 18.9631 26.409 21.2905 24.0816L21.5167 23.823L22.6158 22.724C23.7916 21.5482 25.1734 20.2068 25.6543 18.3279L27.8846 10.2145H27.8523C28.3695 8.6104 27.4846 6.86892 25.8805 6.33557C25.5613 6.22648 25.2462 6.17395 24.9108 6.17395C23.5815 6.17395 22.3895 7.04671 21.9693 8.30736L20.8379 11.9277L18.155 9.24476L9.97695 1.09903L9.81533 0.905082C9.21329 0.303041 8.41326 0 7.61728 0ZM13.8559 0.0323243L13.2094 1.97178C16.5509 3.10314 18.8661 6.72346 18.8661 6.72346L20.6117 5.62444C20.6117 5.62444 18.1388 1.48692 13.8559 0.0323243ZM7.58495 2.03643C7.84355 2.03643 8.12234 2.15361 8.32841 2.35968L21.8077 15.8389L22.3249 14.1904L23.9411 8.95384C24.0865 8.5215 24.4623 8.24271 24.9108 8.24271C25.0239 8.24271 25.129 8.27099 25.234 8.30736C25.7836 8.48918 26.0624 9.05082 25.8805 9.60033V9.66498L23.6501 17.8107C23.3552 18.9501 22.3168 20.1098 21.1612 21.2694L19.8036 22.5947C18.2601 24.1382 15.8196 24.1382 14.2761 22.5947H14.2438L2.44538 11.0872C2.04941 10.6913 1.97264 10.0812 2.25144 9.6973C2.66357 9.12759 3.35046 9.08314 3.803 9.53568L9.36279 15.0955L10.8497 13.6409L5.28992 8.08109C5.2576 8.04876 5.22528 8.01644 5.19295 7.98411L3.51209 6.33557C3.09995 5.92344 3.09995 5.29311 3.51209 4.88098C3.92422 4.46884 4.58687 4.46884 4.999 4.88098L12.3043 12.1863L13.7589 10.7317L6.87382 3.8466C6.46168 3.43446 6.46168 2.77181 6.87382 2.35968C7.07988 2.15361 7.32636 2.03643 7.58495 2.03643ZM2.607 17.1966L0.764516 18.134L1.24938 19.0714C1.24938 19.0714 3.17672 22.9422 6.87382 24.6958L7.81122 25.1483L8.7163 23.2735L7.7789 22.821C4.92628 21.4714 3.05954 18.1016 3.05954 18.1016L2.607 17.1966Z"/>
                            </svg>
                            <?php echo esc_html($settings['welcome_text']); ?>
                        </h2>
                        <p><?php echo !isset($settings['thank_you_text']) ? sprintf(esc_html__('Welcome, and thank you for purchasing %s! We hope you have a fantastic experience with our product and that it helps you create an outstanding online business!', 'xstore'), apply_filters('etheme_theme_label', 'XStore')) : $settings['thank_you_text']; ?></p>
                        <p>
                            <?php if ( ! $is_activated || ! $is_et_core ) : ?>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1.2em" height="1.2em" fill="var(--et_admin_dark2white-color, var(--et_admin_dark-color, #222))" style="vertical-align: -4px;">    <path d="M 12 4 C 12 4 5.7455469 3.9999687 4.1855469 4.4179688 C 3.3245469 4.6479688 2.6479687 5.3255469 2.4179688 6.1855469 C 1.9999687 7.7455469 2 12 2 12 C 2 12 1.9999687 16.254453 2.4179688 17.814453 C 2.6479687 18.675453 3.3255469 19.352031 4.1855469 19.582031 C 5.7455469 20.000031 12 20 12 20 C 12 20 18.254453 20.000031 19.814453 19.582031 C 20.674453 19.352031 21.352031 18.674453 21.582031 17.814453 C 22.000031 16.254453 22 12 22 12 C 22 12 22.000031 7.7455469 21.582031 6.1855469 C 21.352031 5.3255469 20.674453 4.6479688 19.814453 4.4179688 C 18.254453 3.9999687 12 4 12 4 z M 12 6 C 14.882 6 18.490875 6.1336094 19.296875 6.3496094 C 19.465875 6.3946094 19.604391 6.533125 19.650391 6.703125 C 19.891391 7.601125 20 10.342 20 12 C 20 13.658 19.891391 16.397875 19.650391 17.296875 C 19.605391 17.465875 19.466875 17.604391 19.296875 17.650391 C 18.491875 17.866391 14.882 18 12 18 C 9.119 18 5.510125 17.866391 4.703125 17.650391 C 4.534125 17.605391 4.3956094 17.466875 4.3496094 17.296875 C 4.1086094 16.398875 4 13.658 4 12 C 4 10.342 4.1086094 7.6011719 4.3496094 6.7011719 C 4.3946094 6.5331719 4.533125 6.3946094 4.703125 6.3496094 C 5.508125 6.1336094 9.118 6 12 6 z M 10 8.5351562 L 10 15.464844 L 16 12 L 10 8.5351562 z"></path></svg>
                                <a href="https://www.youtube.com/watch?v=i7STFGZapx8&list=PLMqMSqDgPNmCCyem_z9l2ZJ1owQUaFCE3&index=1" class="inline-flex align-items-center et-open-installation-video <?php if( get_option('et_close_installation_video', false) ) echo 'hide_installation_video'; ?>"
                                        data-text="<?php echo esc_html__( 'Watch now', 'xstore' ); ?>">
                                    <?php echo esc_html__('Installation Guide', 'xstore'); ?>
                                </a>
                            </span>
                            <?php else: ?>
                            <?php // @todo replace link to https://xstore.8theme.com page with 9.2 changelog page ?>
                            <span>
                                <a href="<?php echo esc_url(apply_filters('etheme_documentation_url', 'https://xstore.8theme.com/update-history/')); ?>" target="_blank" rel="nofollow">
                                    <?php echo sprintf(esc_html__('What\'s new in %s v.9.4', 'xstore'), apply_filters('etheme_theme_label', 'XStore')); ?>
                                </a>
                                <svg width="0.7em" height="0.7em" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.90212 0.81318C9.82454 0.777002 9.72835 0.760481 9.66164 0.771876L3.49636 0.787033C3.35259 0.781384 3.2104 0.833138 3.07907 0.932464L3.06046 0.952121C2.94879 1.07006 2.89505 1.20556 2.8894 1.34933C2.88375 1.49309 2.9355 1.63529 3.03431 1.74748C3.14294 1.86898 3.2981 1.94133 3.47083 1.95577L8.26726 1.94926L0.748243 9.89042C0.524906 10.1263 0.534832 10.4898 0.770708 10.7132C1.00658 10.9365 1.3603 10.9173 1.58364 10.6814L9.11196 2.73042L9.38633 7.51954C9.40034 7.68191 9.48053 7.81376 9.58812 7.89699C9.6864 7.99005 9.83121 8.03396 9.99411 8.03909C10.1477 8.05404 10.3085 7.98263 10.4109 7.87452C10.5039 7.77624 10.5577 7.64074 10.5726 7.48714L10.2498 1.27282C10.197 1.09235 10.1664 1.02617 10.137 0.998249C10.0775 0.923281 10.0087 0.858141 9.90212 0.81318Z" fill="#222222"/>
                                </svg>
                            </span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <?php if ( !isset($settings['hide_registration_form']) || !$settings['hide_registration_form'] ) : ?>
                    <div class="xstore-panel-info-block type-2">
                    <div>
                            <h2>
                                <?php if ( !$is_activated ) : ?>
                                    <svg width="24" height="22" viewBox="0 0 22 28" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.6667 0C6.23492 0 2.66667 3.56825 2.66667 8V9.33333C1.20925 9.33333 0 10.5426 0 12V25.3333C0 26.7908 1.20925 28 2.66667 28H18.6667C20.1241 28 21.3333 26.7908 21.3333 25.3333V12C21.3333 10.5426 20.1241 9.33333 18.6667 9.33333V8C18.6667 3.56825 15.0984 0 10.6667 0ZM10.6667 2.66667C13.7016 2.66667 16 4.96508 16 8V9.33333H5.33333V8C5.33333 4.96508 7.63175 2.66667 10.6667 2.66667ZM2.66667 12H18.6667V25.3333H2.66667V12ZM10.6667 16C9.2 16 8 17.2 8 18.6667C8 20.1333 9.2 21.3333 10.6667 21.3333C12.1333 21.3333 13.3333 20.1333 13.3333 18.6667C13.3333 17.2 12.1333 16 10.6667 16Z"/>
                                    </svg>
                                <?php else: ?>
                                    <svg width="24" height="22" viewBox="0 0 22 28" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.6667 0C6.83704 0 3.62521 2.72819 2.83073 6.38281L5.4375 6.95052C5.97635 4.47181 8.0963 2.66667 10.6667 2.66667C13.7016 2.66667 16 4.96508 16 8V9.33333H2.66667C1.20925 9.33333 0 10.5426 0 12V25.3333C0 26.7908 1.20925 28 2.66667 28H18.6667C20.1241 28 21.3333 26.7908 21.3333 25.3333V12C21.3333 10.5426 20.1241 9.33333 18.6667 9.33333V8C18.6667 3.56825 15.0984 0 10.6667 0ZM2.66667 12H18.6667V25.3333H2.66667V12ZM10.6667 16C9.2 16 8 17.2 8 18.6667C8 20.1333 9.2 21.3333 10.6667 21.3333C12.1333 21.3333 13.3333 20.1333 13.3333 18.6667C13.3333 17.2 12.1333 16 10.6667 16Z"/>
                                    </svg>
                                <?php endif; ?>
                                <?php esc_html_e( 'Theme Registration', 'xstore' );
                                ?>
                                <?php
                                if ( ! $is_activated ) : ?>
                                    <span class="et-title-label">
                                        <span class="mtips mtips-lg mtips-top helping">
                                            <svg width="1em" height="1em" viewBox="0 0 10 10" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 0C2.24284 0 0 2.24284 0 5C0 7.75716 2.24284 10 5 10C7.75716 10 10 7.75716 10 5C10 2.24284 7.75716 0 5 0ZM5 0.833333C7.30631 0.833333 9.16667 2.69369 9.16667 5C9.16667 7.30631 7.30631 9.16667 5 9.16667C2.69369 9.16667 0.833333 7.30631 0.833333 5C0.833333 2.69369 2.69369 0.833333 5 0.833333ZM5 2.5C4.08366 2.5 3.33333 3.25033 3.33333 4.16667H4.16667C4.16667 3.70117 4.53451 3.33333 5 3.33333C5.46549 3.33333 5.83333 3.70117 5.83333 4.16667C5.83333 4.48568 5.62826 4.76888 5.32552 4.86979L5.15625 4.92188C4.81608 5.03418 4.58333 5.3597 4.58333 5.71615V6.25H5.41667V5.71615L5.58594 5.66406C6.22721 5.45085 6.66667 4.84212 6.66667 4.16667C6.66667 3.25033 5.91634 2.5 5 2.5ZM4.58333 6.66667V7.5H5.41667V6.66667H4.58333Z"/>
                                            </svg>
                                            <?php echo esc_html__('How to?', 'xstore'); ?>
                                        <span class="mt-mes">
                                             <ul>
                                <!--                <li><b>--><?php //esc_html_e( 'If you bought theme on  ', 'xstore' ); ?><!-- <a-->
                                                                                 <!--                            href="https://themeforest.net/">https://themeforest.net/ : </a></b>-->
                                                                                 <!--                </li>-->
                                                <li>1. <?php echo sprintf(esc_html__( 'Log in to your Envato account and navigate to %s Downloads tab %s', 'xstore' ), '<a
                                                            href="https://themeforest.net/downloads">', '</a>'); ?>
                                                </li>
                                                <li>2. <?php echo sprintf(esc_html__( 'Locate the XStore theme in the list and click the corresponding %s Download %s button', 'xstore' ),
                                                        '<span>', '</span>'); ?>
                                                </li>
                                                <li>3. <?php echo sprintf(esc_html__( 'Select the %s"License Certificate & Purchase Code"%s option to download the file', 'xstore' ),
                                                        '<span>', '</span>'); ?>
                                                </li>
                                                <li>4. <?php echo sprintf(esc_html__( 'Open the downloaded document and copy the %s"Item Purchase Code"%s to your clipboard.', 'xstore' ),
                                                        '<span>', '</span>'); ?>
                                                </li>
                                                                                 <!--                <br/>-->
                                                                                 <!--                <li><b>--><?php //esc_html_e( 'If you bought a subscription on  ', 'xstore' ); ?><!-- <a-->
                                                                                 <!--                            href="https://www.8theme.com/">https://www.8theme.com/ :</a></b>-->
                                                                                 <!--                </li>-->
                                                                                 <!--                <li>1. --><?php //esc_html_e( 'Please enter your 8theme account and find the Subscription License Key section', 'xstore' ); ?>
                                <!--                </li>-->
                                                                                 <!--                <li>2. --><?php //esc_html_e( 'Copy the existing code or generate the new one if you already used previously generated code. You need to generate separate codes for every single activation on different domains.', 'xstore' ); ?>
                                <!--                </li>-->
                                                                                 <!--                <li>3. --><?php //esc_html_e( 'Use it to activate the theme', 'xstore' ); ?>
                                <!--                </li>-->
                                            </ul>
                                        </span></span>
                                    </span>
                                <?php endif; ?>
                            </h2>
                            <?php
                                $version = new ETheme_Version_Check();
                                $version->activation_page();
                            ?>
                    </div>
                </div>
            <?php endif; ?>
            </div>
            <br/>
            <br/>
        <?php if ( !$xstore_branding_settings_inited ) : ?>
        </div>
        <div class="xstore-panel-grid-item">
                <div class="main-features-slider" style="visibility: hidden; opacity: 0;">
                    <?php
                        for ($i=0; $i<=7; $i++) {
                            echo '<div class="slick-carousel-item"><a href="https://bit.ly/3hewHb7" rel="nofollow" target="_blank"><img src="' .esc_url( ETHEME_BASE_URI.'framework/panel/images/welcome/9.2-banner-'.$i.'.jpg' ) . '" alt="'.apply_filters('etheme_theme_label', 'XStore') . ' 9.2 - ' . $i.'"></a></div>';
                        }
                    ?>
                </div>
                <br/>
                <br/>
            </div>
        <?php endif; ?>

    <?php
    if ( !$xstore_branding_settings_inited ) : ?>
    <div>
        <?php
            $extended_links = array(
                'xstore' => array(
                        'icon' => '<svg width="18" height="17" viewBox="0 0 18 17" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.2835 8.09868L17.7646 0.000213623H15.6302C15.5503 0.000213623 15.4795 0.014188 15.4178 0.0491309C15.3882 0.0607738 15.3608 0.079411 15.3357 0.100365C15.2535 0.167906 15.1804 0.254097 15.1097 0.365892L11.4 5.98613L11.037 6.53348C10.9091 6.72878 11.0235 6.55723 10.763 6.9344L10.4982 7.34471L9.96866 8.09868L15.6302 16.8633C15.6621 16.8679 15.6964 16.8703 15.7329 16.8703H17.9495L12.2835 8.09868Z"></path>
                        <path d="M5.68865 8.20571L0.23085 1.10181e-07H2.45775C2.61938 1.10181e-07 2.73854 0.0275223 2.81545 0.0823392C2.89226 0.137384 2.96159 0.215856 3.02323 0.317756L7.33871 7.07548C7.39243 6.91057 7.47325 6.7302 7.58104 6.5339L11.6544 0.365067C11.7235 0.255206 11.7984 0.166953 11.8793 0.100081C11.9601 0.0334361 12.0581 0 12.1736 0H14.3082L8.82725 8.09971L14.4928 16.8704H12.2773C12.1081 16.8704 11.9754 16.8254 11.8793 16.7351C11.783 16.645 11.7043 16.5449 11.6428 16.4348L7.21175 9.35937C7.15791 9.52427 7.08868 9.68122 7.00408 9.8302L2.6886 16.4348C2.61938 16.5449 2.54035 16.645 2.45207 16.7351C2.36345 16.8254 2.23861 16.8704 2.07698 16.8704H0L5.68865 8.20571Z"></path>
                        </svg>',
                        'title' => sprintf(esc_html__('Buy %s License', 'xstore'), apply_filters('etheme_theme_label', 'XStore')),
                        'link' => 'https://1.envato.market/2rXmmA',
                        'description' => sprintf(esc_html__('Get more out of %s by adding another license for your next project.', 'xstore'), apply_filters('etheme_theme_label', 'XStore'))
                ),
            'wpml' => array(
                'icon' => '<svg width="16" height="18" viewBox="0 0 16 18" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
    <path d="M11.6619 15.9726C11.877 16.4423 11.8504 17.1449 12.1675 17.5168C12.1939 17.5377 12.2221 17.5569 12.2526 17.5743C14.1212 17.4685 14.2211 15.4214 13.3138 14.4693C12.8997 14.1008 12.3096 13.9455 11.6012 14.2396C7.8905 15.7762 4.34668 14.7947 2.20013 12.6766C1.02876 11.5614 0.251846 10.1157 0.0595919 8.54774C-0.553732 3.55475 3.70212 0.40729 6.62506 0.0619783C9.25757 -0.248637 11.6855 0.627035 13.3522 2.26768C15.1587 3.98416 16.1135 6.57814 15.5401 9.54551C15.2751 10.9119 14.4309 12.6132 12.5388 12.615C11.7673 12.6163 11.0218 12.2945 10.4913 11.7591C10.0048 11.3147 9.67417 10.7012 9.62611 9.99243C9.44415 7.2985 13.0529 6.55831 12.8149 3.85199C12.7725 3.36954 12.5532 2.94988 12.2059 2.59878C11.1056 1.59473 8.91528 1.20597 6.91104 1.5992C4.44401 2.08329 2.07294 4.24059 1.65873 6.83555C1.324 8.93551 2.09268 11.3345 3.721 12.9438C5.16239 14.3106 7.24594 15.0677 9.81424 14.5035C10.6411 14.3214 11.2682 13.5756 12.6282 13.7814C13.1145 13.8551 13.4992 14.0693 13.7834 14.363C14.8666 15.3345 14.6937 17.3841 13.2897 17.8487C12.5578 18.0909 11.2338 17.8978 10.6505 17.2803L10.6348 17.2666C10.5609 17.2275 10.494 17.1728 10.4351 17.1054C10.2046 16.922 10.0621 16.5717 10.0889 16.1808C10.128 15.623 10.4974 15.1921 10.9128 15.2192C11.0819 15.2298 11.2328 15.3147 11.3497 15.4489C11.497 15.5665 11.6079 15.7522 11.6619 15.9726Z"></path>
    </svg>',
                'title' => esc_html__('Get WPML Plugin', 'xstore'),
                'link' => 'https://wpml.org/?aid=46060&affiliate_key=YI8njhBqLYnp&dr',
                'description' => esc_html__('Elevate your website\'s language capabilities by purchasing the WPML plugin.', 'xstore')
            ),
            'codeable' => array(
                'icon' => '<svg width="18" height="18" viewBox="0 0 18 18" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.98322 11.9664C5.98322 15.2712 8.66201 17.95 11.9664 17.95C15.2712 17.95 17.95 15.2712 17.95 11.9664C17.95 8.662 15.2712 5.9832 11.9664 5.9832V11.9664H5.98322Z"></path>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9664 5.9832V0H0V11.9664H5.9832V5.9832H11.9664Z"></path>
    </svg>',
                'title' => esc_html__('Customization Services', 'xstore'),
                'link' => etheme_contact_us_url(),
                'description' => esc_html__('Unleash the full potential of your website with our premium customization services.', 'xstore')
            )
        );
            foreach ($extended_links as $extended_link) {
                ?>
                <div class="et-col-3 extended-link">
                    <br/>
                    <br/>
                    <?php echo '<span class="extended-link-icon">'.$extended_link['icon'].'</span>';?>
                    <h3><a href="<?php echo esc_url($extended_link['link']); ?>" rel="nofollow" target="_blank" class="et-animated-link type-1"><?php echo esc_html($extended_link['title']); ?></a></h3>
                    <p><?php echo esc_html($extended_link['description']); ?></p>
                </div>
                <?php
            }
        ?>
    </div>
    <?php endif; ?>

    <input type="hidden" name="nonce_etheme-theme-actions" value="<?php echo wp_create_nonce( 'etheme_theme-actions' );?>">
    <?php if ( !$xstore_branding_settings_inited ) : ?>
</div><?php // .xstore-panel-grid-wrapper ?>
<?php endif; ?>

<?php if ( !$xstore_branding_settings_inited ) : ?>
    <style>
        /* Dots */
        .slick-dots
        {
            position: absolute;
            display: block;
            width: 100%;
            bottom: 40px;
            padding: 0;
            margin: 0;
            list-style: none;
            text-align: center;
        }
        .slick-dots li
        {
            position: relative;
            display: inline-block;
            width: 10px;
            height: 10px;
            margin: 0 7px;
            padding: 0;
            cursor: pointer;
        }
        .slick-dots li button
        {
            font-size: 0;
            line-height: 0;
            display: block;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            cursor: pointer;
            color: transparent;
            padding: 0;
            border: 0;
            outline: none;
            background: #fff;
        }
        .slick-dots li button:hover,
        .slick-dots li button:focus
        {
            outline: none;
        }

        .slick-dots li button:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: inherit;
            border: 1px solid #fff;
            transform: scale(0);
            transition: all .3s linear;
        }

        .slick-dots li:hover button:after,
        .slick-dots li.slick-active button:after {
            transform: scale(2);
        }

        .main-features-slider {
            transition: all .3s linear;
            cursor: grab;
        }
        .main-features-slider img {
            width: 100%;
            border-radius: 5px;
        }

        .slick-carousel-item {
            padding: 0 5px;
        }
    </style>
<?php
    wp_add_inline_script( 'etheme_admin_js', '
			jQuery(document).ready(function($) {
                $(".main-features-slider").slick({
                    arrows: false,
                    dots: true,
                    infinite: true,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 5000,
                }).attr("style", null);
		    });
		', 'after' );
endif; ?>
<?php
wp_add_inline_script( 'etheme_admin_js', '
			jQuery(document).ready(function($) {
            	$("head").find("title").text("'.apply_filters('etheme_theme_label', 'XStore').'");
		    });
		', 'after' );