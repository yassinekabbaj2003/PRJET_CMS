<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<div class="xstore-dashboard-overview xstore-dashboard-widget">
    <?php

    $is_activated         = etheme_is_activated();
    $check_update = new ETheme_Version_Check();

    $theme = wp_get_theme();
    $is_child_theme = is_child_theme();
    $version = !$is_child_theme && $white_label_settings->version ? $white_label_settings->version : 'v.'.$theme->get( 'Version' );
    $name = $theme->get( 'Name' );
    $version_string = $version;
    $screenshot = $theme->get( 'Screenshot' );

    if ( $is_child_theme ) {
        $parent  = wp_get_theme( 'xstore' );
        $name = $parent->get( 'Name' );
        $parent_version = $white_label_settings->version ? $white_label_settings->version : 'v.'.$parent->version;
        $version_string = $parent_version . ' (' . sprintf(esc_html__('child %s', 'xstore-core'), $version) . ')';
    }

    ?>
    <div class="xstore-overview__header xstore-dashboard-widget__table-zone">
            <div class="xstore-overview__logo">
                <div class="xstore-logo-wrapper">
                    <img src="<?php echo $white_label_settings->title_logo; ?>" alt="<?php echo $white_label_settings->title_text; ?>">
                </div>
            </div>
            <div class="xstore-overview__versions">
                <span class="xstore-overview__version"><?php echo esc_html($name) . ' ' . esc_html($version_string); ?></span>
                <?php
                /**
                 * XStore dashboard widget after the version.
                 * Fires after XStore version display in the dashboard widget.
                 *
                 * @since 5.1.3
                 */
                do_action( 'xstore/admin/dashboard_overview_widget/after_version' );
                ?>
            </div>
        <div class="xstore-overview__create">
            <?php if ( !$white_label_settings->hide_updates && $is_activated && $check_update->is_update_available() ) : ?>
                <a href="<?php echo admin_url( 'admin.php?page=et-panel-changelog' ); ?>" class="button"><?php echo esc_html__('New update available', 'xstore-core'); ?></a>
            <?php endif; ?>
            <?php if ( !$is_activated ) : ?>
                <a href="<?php echo admin_url( 'admin.php?page=et-panel-welcome' ); ?>" target="_blank" class="button button-primary"><?php echo esc_html__('Register License', 'xstore-core'); ?></a>
            <?php endif; ?>
        </div>
    </div>
    <?php if ( in_array('welcome', $white_label_settings->show_pages) ) : ?>
        <div class="xstore-overview__support xstore-dashboard-widget__table-zone">
            <div class="xstore-overview__title">
                <h3 class="xstore-overview__heading"><?php echo esc_html__('Support', 'xstore-core'); ?></h3>
            </div>
            <div class="xstore-overview__create">
                <a href="<?php etheme_support_forum_url(true); ?>" class="button"><?php echo esc_html__('Create a new topic', 'xstore-core'); ?></a>
                <a href="https://1.envato.market/2rXmmA" class="button button-primary"><?php echo esc_html__('Renew support', 'xstore-core'); ?></a>
            </div>
        </div>
    <?php endif; ?>
    <?php if ( in_array('system_requirements', $white_label_settings->show_pages) && count($system_logs) ) : ?>
        <div class="xstore-overview__system-req">
            <div class="xstore-overview__title">
                <h3 class="xstore-overview__heading"><?php echo esc_html__('System requirements', 'xstore-core'); ?></h3>
            </div>
            <div class="xstore-overview__system-req-list">
                <?php
                    foreach ($system_logs as $system_log) {
                        echo '<div class="et-message et-'.$system_log['type'].'">'.$system_log['message'].'</div>';
                    }
                ?>
            </div>
            <br/>
            <div class="text-center">
                <a href="<?php echo admin_url('admin.php?page=et-panel-system-requirements'); ?>" class="button"><?php echo esc_html__('More details', 'xstore-core'); ?></a>
            </div>
        </div>
    <?php endif; ?>
    <?php if ( $recently_edited_query->have_posts() ) : ?>
        <div class="xstore-overview__system-req">
            <div class="xstore-overview__title">
                <h3 class="xstore-overview__heading"><?php echo esc_html__('Recently Edited', 'xstore-core'); ?></h3>
            </div>
            <ul>
                <?php
                while ( $recently_edited_query->have_posts() ) :
                    $recently_edited_query->the_post();

                    $date = date_i18n( _x( 'M jS', 'Dashboard Overview Widget Recently Date', 'xstore-core' ), get_the_modified_time( 'U' ) );
                    ?>
                    <li class="xstore-overview__recent-post">
                        <a href="<?php echo esc_url( get_edit_post_link( get_the_ID() ) ); ?> "><?php the_title(); ?> <span class="dashicons dashicons-edit"></span></a> <span><?php echo esc_html( $date ); ?>, <?php the_time(); ?></span>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if ( in_array('sales_booster', $white_label_settings->show_pages) ) : ?>
        <div class="xstore-overview__sales-booster xstore-dashboard-widget__table-zone">
            <div>
                <div class="xstore-overview__title">
                    <h3 class="xstore-overview__heading"><?php echo sprintf(esc_html__('%s Sales Booster', 'xstore-core'), $white_label_settings->title_text); ?></h3>
                </div>
                <p><?php printf(esc_html__('Boosts your eCommerce store\'s conversion rates and success with helpful %1sfeatures%2s.', 'xstore-core'), '<a href="'.admin_url( 'admin.php?page=et-panel-sales-booster' ).'">', '</a>'); ?></p>
            </div>
            <div>
                <img src="<?php echo ET_CORE_URL . '/app/models/overview/assets/img/sales-booster.jpeg'; ?>" alt="<?php echo sprintf(esc_attr__('%s Sales Booster', 'xstore-core'), $white_label_settings->title_text); ?>">
            </div>
        </div>
    <?php endif; ?>
    <div class="xstore-overview__footer xstore-dashboard-widget__table-zone">
        <?php
            $footer_links = array(
                array(
                    'title' => esc_html__('Docs. & Tutorials', 'xstore-core'),
                    'link' => etheme_documentation_url(false, false),
                    'icon' => 'dashicons-admin-generic'
                ),
                array(
                    'title' => esc_html__('Changelog', 'xstore-core'),
                    'link' => esc_url(apply_filters('etheme_documentation_url', 'https://xstore.8theme.com/update-history/')),
                    'icon' => 'dashicons-update-alt'
                ),
                array(
                    'title' => esc_html__('Help', 'xstore-core'),
                    'link' => etheme_support_forum_url(),
                    'icon' => 'dashicons-format-chat'
                ),
                array(
                    'title' => esc_html__('FAQ', 'xstore-core'),
                    'link' => has_filter('etheme_support_forum_url') ? etheme_support_forum_url() : 'https://www.8theme.com/faq/',
                    'icon' => 'dashicons-editor-help'
                ),
            );
            ?>
            <ul>
            <?php
            foreach ($footer_links as $footer_link) { ?>
                <li>
                    <a href="<?php echo esc_url($footer_link['link']); ?>" rel="nofollow" target="_blank">
                        <?php if ( $footer_link['icon'] ) : ?>
                            <span class="dashicons <?php echo $footer_link['icon']; ?>"></span>
                        <?php endif; ?>
                        <?php echo esc_html($footer_link['title']); ?>
                    </a>
                </li>
            <?php } ?>
            </ul>
            <?php
        ?>
    </div>
</div>
