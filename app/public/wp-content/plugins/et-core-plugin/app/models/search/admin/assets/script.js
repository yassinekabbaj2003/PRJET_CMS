/**
 * Panel instagram page scripts
 *
 * @version 1.0.0
 * @since 5.4.0
 */

window.et_panel = window.et_panel || {};
!function ($) {
    $(document).ready(function ($) {

        var et_search_stats = {
            placeholderClass: 'et-search-stats-stats-placeholder',
            placeholderClassLoaded: 'et-search-stats-stats-placeholder-loaded',
            criticalSearchesLoadMoreClass: 'et-search-stats-searches-load-more',
            autocompleteWithResultsLoadMoreClass: 'et-search-stats-autocomplete-with-results-load-more',
            searchPageWithResultsLoadMoreClass: 'et-search-stats-search-page-with-results-load-more',
            checkPhraseStatusClass: 'et-search-stats-stats-critical-check',
            checkPhraseStatusInitClass: 'et-search-stats-stats-critical-check-init',
            selectSwitcherClass: 'et-search-stats-analytics-select',
            excludePhraseClass: 'et-search-stats-analytics-exclude-phrase',
            checkIndexerAction: 'et-search-stats-analytics-check-indexer',
            resetAnalyticsAction: 'et-search-stats-analytics-reset',
            analyticsExportCSVAction: 'et-search-stats-analytics-export-csv',

            etheme_ajax_search_analytics_switcher: function (_this, e) {

                var checked = $(_this).is(':checked');
                $(_this).attr("checked", checked);
                $(_this).checked = checked;
                $(_this).parent().toggleClass('switched');

                var data = {
                    action: 'et_ajax_search_analytics_switch',
                    value: checked
                };
                $.ajax({
                    type: 'POST',
                    url: XStorePanelAjaxSearchStatsConfig.ajaxurl,
                    data: data,
                    success: function (response) {
                        location.reload();
                    },
                    error: function () {
                        alert('Error while switching');
                    },
                    complete: function () {
                    }
                });
            },

            reset_statsListener: function (_this, e) {
                e.preventDefault();
                if (confirm(XStorePanelAjaxSearchStatsConfig.confirmQuestion)) {

                    et_panel.popup_configuration.openPopup();

                    var data = {
                        'action': 'et_ajax_search_reset_stats',
                        '_wpnonce': XStorePanelAjaxSearchStatsConfig.nonce.reset_stats
                    };

                    $.ajax({
                        type: 'POST',
                        url: XStorePanelAjaxSearchStatsConfig.ajaxurl,
                        data: data,
                        success: function (data) {
                            et_panel.popup_configuration.closePopup(data, true, true);
                        },
                        error: function () {
                            alert('Error while deleting');
                        },
                        complete: function () {
                            // $('.etheme-instagram-settings').removeClass('processing');
                        }
                    });
                }
            },
            getLang: function () {
                let $lang = $('.' + et_search_stats.selectSwitcherClass + '[data-type=lang] option:selected');
                if ($lang.length > 0)
                    return $lang.val();
                else {
                    let queryString = window.location.search;
                    let urlParams = new URLSearchParams(queryString);
                    if (urlParams.has('lang'))
                        return urlParams.get('lang');
                }

                return false;
            },
            getMinCriticalRep: function () {
                let queryString = window.location.search;
                let urlParams = new URLSearchParams(queryString);
                if (urlParams.has('min-critical-rep'))
                    return urlParams.get('min-critical-rep');
                return false;
            },
            export_statsListener: function (_this, e) {
                e.preventDefault();

                var url = new URL(XStorePanelAjaxSearchStatsConfig.ajaxurl);
                url.searchParams.append('action', 'et_ajax_search_export_stats_csv');
                url.searchParams.append('context', $(_this).data('context'));
                url.searchParams.append('_wpnonce', XStorePanelAjaxSearchStatsConfig.nonce.export_stats_csv);

                let lang = et_search_stats.getLang();
                if (!!lang)
                    url.searchParams.append('lang', lang);
                window.location = url;
            },
            loadMoreListeners: function (_this, e) {
                // Critical searches - load more
                switch ($(_this).data('load-more')) {
                    case 'critical':
                        // $(this).before('<img src="' + dgwt_wcas.images.admin_preloader_url + '" />');
                        // console.log(this.rowLoadingClass);
                        // console.log(et_panel.rowLoadingClass);
                        // console.log(et_search_stats.rowLoadingClass);
                        $(this).closest('tr').addClass(et_search_stats.rowLoadingClass);
                        et_panel.load_more_critical_searches($(_this));
                        break;
                    case 'autocomplete':
                        et_panel.loadMorePhrases('autocomplete', $(_this));
                        break;
                }
            },
            load_more_critical_searches: function ($el) {
                var data = {
                    'action': 'et_ajax_search_load_more_critical_searches',
                    'loaded': $('.et-search-stats-critical-searches-row').length,
                    '_wpnonce': XStorePanelAjaxSearchStatsConfig.nonce.load_more_critical_searches
                };

                let lang = et_search_stats.getLang();
                if (!!lang)
                    data.lang = lang;

                let minCriticalRep = et_search_stats.getMinCriticalRep();
                if (minCriticalRep !== false)
                    data.minCriticalRep = minCriticalRep;

                $.ajax({
                    type: 'POST',
                    url: XStorePanelAjaxSearchStatsConfig.ajaxurl,
                    data: data,
                    success: function (response) {
                        if (typeof response == 'object' && response.success) {
                            let parent_wrapper = $el.parent().parent();
                            var $tBody = parent_wrapper.find('table tbody');

                            if (response.data.html.length > 0) {
                                $tBody.append(response.data.html);
                            }

                            if (response.data.more > 0) {

                            } else {
                                $el.parent().remove();
                            }
                        }
                    },
                    error: function () {
                        alert('Error while deleting');
                    },
                    complete: function () {
                        // $('.etheme-instagram-settings').removeClass('processing');
                    }
                });
            },
            loadMorePhrases: function (context, $el) {
                var data = {
                    'action': 'et_ajax_search_load_more_autocomplete',
                    'loaded': $('.et-search-stats-autocomplete-row').length,
                    '_wpnonce': XStorePanelAjaxSearchStatsConfig.nonce.load_more_autocomplete
                };

                let lang = et_search_stats.getLang();
                if (!!lang)
                    data.lang = lang;

                $.ajax({
                    type: 'POST',
                    url: XStorePanelAjaxSearchStatsConfig.ajaxurl,
                    data: data,
                    success: function (response) {
                        if (typeof response == 'object' && response.success) {
                            let parent_wrapper = $el.parent().parent();
                            var $tBody = parent_wrapper.find('table tbody');
                            $tBody.html(response.data.html);
                            $el.parent().remove();
                        }
                    },
                    error: function () {
                        alert('Error while deleting');
                    },
                    complete: function () {

                    }
                });
            },
            reloadStatsForLang: function (_this, e) {
                var url = new URL(window.location.href);
                url.searchParams.append($(_this).data('type'), $(_this).val());
                window.location = url;
            }
        };

        window.et_panel = Object.assign(window.et_panel, et_search_stats);

        var et_search_stats_actions = {
            '1': {
                selector: '.et-search-stats-analytics-reset',
                type: 'click',
                callback: et_panel.reset_statsListener
            },
            '2': {
                selector: '.et-search-stats-analytics-export-csv',
                type: 'click',
                callback: et_panel.export_statsListener
            },
            '3': {
                selector: '.et-search-stats-searches-load-more',
                type: 'click',
                callback: et_panel.loadMoreListeners
            },
            '4': {
                selector: '#etheme_ajax_search_analytics',
                type: 'click',
                callback: et_panel.etheme_ajax_search_analytics_switcher
            },
            '5': {
                selector: '.et-search-stats-analytics-select',
                type: 'change',
                callback: et_panel.reloadStatsForLang
            },
        };

        $.each(et_search_stats_actions, function (i, t) {
            if (t.type == 'ready') {
                $(document).ready(function (e) {
                    t.callback($(this), e);
                });
            } else {
                $(t.selector).on(t.type, function (e) {
                    t.callback($(this), e);
                });
            }
        });
    });
}(jQuery, window.et_panel);