<?php

if (!defined('ABSPATH')) {
    exit;
}

$exists_results_critical = !empty($vars['critical-searches']);
$exists_results = !empty($vars['autocomplete']['with-results']);
$exists_reset_stats = $exists_results || $exists_results_critical;
$global_admin_class = EthemeAdmin::get_instance();
$tabs = array();
if ($exists_results)
    $tabs['with-results'] = array(
        'icon' => '<svg width="1em" height="1em" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.30484 12.0821L12.9663 6.42063L12.0789 5.53326L7.30484 10.3074L4.90484 7.90737L4.01747 8.79474L7.30484 12.0821ZM8.50147 16.5C7.39495 16.5 6.35488 16.29 5.38126 15.8701C4.40765 15.4502 3.56077 14.8803 2.84063 14.1604C2.12049 13.4406 1.55032 12.594 1.13011 11.6208C0.710035 10.6476 0.5 9.60786 0.5 8.50147C0.5 7.39495 0.709965 6.35488 1.12989 5.38126C1.54982 4.40765 2.11972 3.56077 2.83958 2.84063C3.55944 2.12049 4.40597 1.55032 5.37916 1.13011C6.35235 0.710035 7.39214 0.5 8.49853 0.5C9.60505 0.5 10.6451 0.709965 11.6187 1.12989C12.5924 1.54982 13.4392 2.11972 14.1594 2.83958C14.8795 3.55944 15.4497 4.40597 15.8699 5.37916C16.29 6.35235 16.5 7.39214 16.5 8.49853C16.5 9.60505 16.29 10.6451 15.8701 11.6187C15.4502 12.5924 14.8803 13.4392 14.1604 14.1594C13.4406 14.8795 12.594 15.4497 11.6208 15.8699C10.6476 16.29 9.60786 16.5 8.50147 16.5ZM8.5 15.2368C10.3807 15.2368 11.9737 14.5842 13.2789 13.2789C14.5842 11.9737 15.2368 10.3807 15.2368 8.5C15.2368 6.6193 14.5842 5.02632 13.2789 3.72105C11.9737 2.41579 10.3807 1.76316 8.5 1.76316C6.6193 1.76316 5.02632 2.41579 3.72105 3.72105C2.41579 5.02632 1.76316 6.6193 1.76316 8.5C1.76316 10.3807 2.41579 11.9737 3.72105 13.2789C5.02632 14.5842 6.6193 15.2368 8.5 15.2368Z" fill="currentColor"/>
        </svg>',
        'text' => esc_html__('Found', 'xstore-core')
    );
if ( $exists_results_critical )
$tabs['critical-searches'] = array(
    'icon' => '<svg width="1em" height="1em" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5.46842 12.4189L8.5 9.38737L11.5316 12.4189L12.4189 11.5316L9.38737 8.5L12.4189 5.46842L11.5316 4.58105L8.5 7.61263L5.46842 4.58105L4.58105 5.46842L7.61263 8.5L4.58105 11.5316L5.46842 12.4189ZM8.50147 16.5C7.39495 16.5 6.35488 16.29 5.38126 15.8701C4.40765 15.4502 3.56077 14.8803 2.84063 14.1604C2.12049 13.4406 1.55032 12.594 1.13011 11.6208C0.710035 10.6476 0.5 9.60786 0.5 8.50147C0.5 7.39495 0.709965 6.35488 1.12989 5.38126C1.54982 4.40765 2.11972 3.56077 2.83958 2.84063C3.55944 2.12049 4.40597 1.55032 5.37916 1.13011C6.35235 0.710035 7.39214 0.5 8.49853 0.5C9.60505 0.5 10.6451 0.709965 11.6187 1.12989C12.5924 1.54982 13.4392 2.11972 14.1594 2.83958C14.8795 3.55944 15.4497 4.40597 15.8699 5.37916C16.29 6.35235 16.5 7.39214 16.5 8.49853C16.5 9.60505 16.29 10.6451 15.8701 11.6187C15.4502 12.5924 14.8803 13.4392 14.1604 14.1594C13.4406 14.8795 12.594 15.4497 11.6208 15.8699C10.6476 16.29 9.60786 16.5 8.50147 16.5ZM8.5 15.2368C10.3807 15.2368 11.9737 14.5842 13.2789 13.2789C14.5842 11.9737 15.2368 10.3807 15.2368 8.5C15.2368 6.6193 14.5842 5.02632 13.2789 3.72105C11.9737 2.41579 10.3807 1.76316 8.5 1.76316C6.6193 1.76316 5.02632 2.41579 3.72105 3.72105C2.41579 5.02632 1.76316 6.6193 1.76316 8.5C1.76316 10.3807 2.41579 11.9737 3.72105 13.2789C5.02632 14.5842 6.6193 15.2368 8.5 15.2368Z" fill="currentColor"/>
            </svg>',
    'text' => esc_html__('Not found', 'xstore-core')
);

$active_tab = array_key_first($tabs);
if (isset($_GET['etheme-panel-search-analytics-tab'])) {
    $active_tab = $_GET['etheme-panel-search-analytics-tab'];
}

if ($exists_results || $exists_results_critical || count($tabs) > 1) { ?>
    <div class="xstore-panel-grid-header et-tabs-filters-wrapper">
            <ul class="et-filters et-filters-style-default et-tabs-filters et-filters-builders">
                <?php if (count($tabs) > 1) :
                    foreach ($tabs as $tab_key => $tab_label) { ?>
                    <li data-tab="<?php echo esc_attr($tab_key); ?>"
                        class="<?php echo $tab_key == $active_tab ? 'active' : ''; ?>">
                        <?php echo $tab_label['icon']; ?>
                        <?php echo '<span>' . $tab_label['text'] . '</span>'; ?>
                    </li>
                <?php }
                endif; ?>
            </ul>
        <?php
        if ($exists_results || $exists_results_critical) {
            if (isset($vars['lang-html']))
                echo $vars['lang-html'];

            if (isset($vars['date-range-html']))
                echo $vars['date-range-html'];
        }
        ?>
    </div>
<?php } ?>

<?php if ( !count($tabs) ) : ?>
    <p class="et-message"><?php echo esc_html__('Currently the search data is empty, please take some time for us to collect the information.', 'xstore-core'); ?></p>
<?php endif; ?>

<div class="et-search-stats-analytics-module-critical et-tabs-content <?php echo 'critical-searches' != $active_tab ? '' : 'active'; ?>"
     data-tab-content="critical-searches">
    <p class="et-message et-info">
        <?php echo esc_html__('Here is a list of search with no results found.', 'xstore-core'); ?>
        <?php printf(_n('We keep these statistics only if the search term or phrase was used by clients at least once, indicating that such searches are popular among customers.', 'We keep these statistics only if the search term or phrase was used by clients at least %d times, indicating that such searches are popular among customers.', $vars['critical-searches-min-rep'], 'xstore-core'), $vars['critical-searches-min-rep']); ?></p>
    <?php if (!$exists_results_critical): ?>
        <p class="et-message"><?php echo esc_html__('Currently this table is empty, please take some time for us to collect the information', 'xstore-core'); ?></p>
    <?php else: ?>
        <div class="et-search-stats-analytics-module-critical-body">
            <div class="etheme-div etheme-table-style-2">
                <table class="et-search-stats-analytics-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?php esc_html_e('Phrase', 'xstore-core'); ?></th>
                        <th><?php esc_html_e('Repetitions', 'xstore-core'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    foreach ($vars['critical-searches'] as $row) {
                        require ET_AJAX_SEARCH_DIR . 'search/partials/admin/stats/critical-searches-row.php';
                        $i++;
                    }
                    ?>
                    </tbody>
                </table>
                <?php
                if ($vars['critical-searches-more'] > 0): ?>
                    <div class="text-center">
                        <br/>
                        <span class="et-button et-button-sm et-search-stats-searches-load-more"
                              data-load-more="critical">
                            <span class="dashicons dashicons-image-rotate"></span>
                            <?php
                            if ($vars['critical-searches-more'] > 100)
                                esc_html_e('show top 100 phrases', 'xstore-core');
                            else
                                esc_html_e('Load more', 'xstore-core'); ?>
                            <?php $global_admin_class->get_loader(); ?>
                        </span>
                    </div>
                <?php endif;

                if ($vars['allow-export-cvv'] && $vars['critical-searches-total'] > 0) {
                    printf('<br/><a class="et-search-stats-analytics-export-csv" data-context="" href="#">%s</a>', __('Export CSV', 'xstore-core'));
                }
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (!defined('ET_AJAX_SEARCH_ANALYTICS_ONLY_CRITICAL') || !ET_AJAX_SEARCH_ANALYTICS_ONLY_CRITICAL): ?>
    <div class="et-tabs-content <?php echo 'with-results' != $active_tab ? '' : 'active'; ?>"
         data-tab-content="with-results">
        <p class="et-message et-info"><?php esc_html_e('The list of phrases with results is shown to users as a dropdown menu with auto-suggestions.', 'xstore-core'); ?></p>
        <div class="xstore-panel-grid-wrapper">
            <div class="xstore-panel-grid-item">
                <div class="xstore-panel-info-blocks one-col etheme-table-style-2">
                    <div>
                        <?php if ($exists_results): ?>
                            <table class="et-search-stats-analytics-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php esc_html_e('Phrase', 'xstore-core'); ?></th>
                                    <th><?php esc_html_e('Repetitions', 'xstore-core'); ?></th>
                                </tr>

                                </thead>
                                <tbody>

                                <?php
                                $i = 1;
                                foreach ($vars['autocomplete']['with-results'] as $row) {
                                    require ET_AJAX_SEARCH_DIR . 'search/partials/admin/stats/ac-searches-row.php';
                                    $i++;
                                }
                                ?>

                                </tbody>
                            </table>
                            <?php
                            if ($vars['autocomplete']['total-unique-results'] > count($vars['autocomplete']['with-results'])): ?>
                                <div class="text-center">
                                    <br/>
                                    <span class="et-button et-button-sm et-search-stats-searches-load-more"
                                          data-load-more="autocomplete">
                                        <span class="dashicons dashicons-image-rotate"></span>
                                        <?php
                                        if ($vars['autocomplete']['total-unique-results'] > 100)
                                            esc_html_e('show top 100 phrases', 'xstore-core');
                                        else
                                            esc_html_e('Load more', 'xstore-core'); ?>
                                                    <?php $global_admin_class->get_loader(); ?>
                                    </span>
                                </div>

                            <?php endif; ?>

                        <?php else: ?>
                            <p class="et-message et-warning"><?php echo esc_html__('There are no results yet', 'xstore-core'); ?></p>
                        <?php endif; ?>

                        <?php
                        if ($vars['allow-export-cvv'] && !empty($vars['autocomplete']['with-results'])) {
                            printf('<br/><a class="et-search-stats-analytics-export-csv" data-context="autocomplete" href="#">%s</a>', __('Export CSV', 'xstore-core'));
                        }
                        ?>

                    </div>

                </div>
            </div>
            <?php if ($exists_results) : ?>
                <div class="xstore-panel-grid-item et-sidebar">
                    <div class="xstore-panel-info-blocks one-col">
                        <div class="xstore-panel-info-block type-3">
                            <div>
                                <svg class="svg-bg" version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">
                                    <path d="M31.712 30.4l-8.224-8.192c2.112-2.464 3.264-5.568 3.264-8.768 0-7.36-5.984-13.376-13.376-13.376-7.36 0-13.344 5.984-13.344 13.344s5.984 13.376 13.376 13.376c3.232 0 6.304-1.152 8.768-3.296l8.224 8.192c0.192 0.192 0.416 0.288 0.64 0.288s0.448-0.096 0.608-0.256c0.192-0.16 0.288-0.384 0.32-0.64 0-0.256-0.096-0.512-0.256-0.672zM24.928 13.44c0 6.336-5.184 11.52-11.552 11.52-6.336 0-11.52-5.184-11.52-11.552 0-6.336 5.184-11.52 11.552-11.52s11.52 5.184 11.52 11.552z"></path>
                                </svg>
                                <h3>
                                    <?php echo esc_html__('Total searches', 'xstore-core'); ?><br/>
                                    <span style="font-size: 1.4em; display: inline-block; margin-top: 10px;">
                                        <span class="hovered-search-stats">
                                            <span class="et-counter" style="color: var(--et_admin_green-color);"><?php echo esc_html($vars['autocomplete']['total-with-results']); ?></span> /
                                            <span class="et-counter" style="color: var(--et_admin_red-color);"><?php echo esc_html($vars['autocomplete']['total-no-results']); ?></span>
                                        </span>
                                        <span class="et-counter"><?php echo esc_html($vars['autocomplete']['total-results']); ?></span>
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <div class="xstore-panel-info-block type-3">
                            <div>
                                <?php
                                if (!empty($vars['returning-results-percent'])) {
                                    if ($vars['returning-results-percent-satisfying']) {
                                        echo \ETC\App\Models\Search\Helpers::get_icon_svg('face-smile', 'svg-bg et-search-stats-stats-icon-smile');
                                    } else {
                                        echo \ETC\App\Models\Search\Helpers::get_icon_svg(($vars['returning-results-percent'] > 50 ? 'face-neutral' : 'face-sad'), 'svg-bg et-search-stats-stats-icon-sad');
                                    }
                                }
                                ?>
                                <h3>
                                    <?php echo esc_html__('Successfull searches', 'xstore-core'); ?><br/>
                                    <span style="font-size: 1.4em; display: inline-block; margin-top: 10px;" class="et-counter" data-postfix="%"><?php echo esc_html($vars['returning-results-percent']); ?>%</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($exists_reset_stats) : ?>
    <br/><br/>
    <div class="et-search-stats-analytics-module-reset">
        <h3><?php esc_html_e('Maintenance', 'xstore-core') ?></h3>
        <?php if (defined('ET_AJAX_SEARCH_ANALYTICS_ONLY_CRITICAL') && ET_AJAX_SEARCH_ANALYTICS_ONLY_CRITICAL): ?>
            <p class="et-message et-info">
                <span class="et-search-stats-analytics-red-color"><b><?php esc_html_e('Warning:', 'xstore-core'); ?></b> </span>
                <?php
                /* Translators: %s PHP constant name. */
                printf(__("You have defined %s constant and it's set to <code>true</code>. It means only critical searches will be stored in the database. Other modules than Critical Searches are not visible in this mode.", 'xstore-core'), '<code>ET_AJAX_SEARCH_ANALYTICS_ONLY_CRITICAL</code>') ?>
            </p>
        <?php endif; ?>
        <p class="et-message et-info">
            <?php
            $reset = sprintf('<a class="et-search-stats-analytics-reset" href="#">%s</a>', __('reset your stats', 'xstore-core'));
            $size = $vars['autocomplete']['total-results'] > 0 ? $vars['table-info']['data'] + $vars['table-info']['index'] : 0;
            ?>
            <?php printf(_x('Stats older than %d days are automatically removed from your database daily. Currently, there are %d records in the database, occupying %.2fMB. You can %s now to start fresh and begin collecting new data.', 'The last placeholder is a button with text "reset your stats"', 'xstore-core'),
                $vars['days'], esc_html($vars['autocomplete']['total-results']), $size, $reset); ?>
        </p>
    </div>

<?php endif; ?>
