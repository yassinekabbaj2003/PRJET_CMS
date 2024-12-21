/**
 * Fix for BEROCKET AJAX FILTERS plugin
 *
 * @version 1.0.0
 * @since 1.0.0
 */
;(function ($) {
    "use strict";
    etTheme.autoinit.BerocketFix = function () {
        $('.elementor-widget-etheme_sidebar_off_canvas .etheme-elementor-off-canvas__toggle_button[aria-expanded="false"]').on('click', function(){
            var sidebar = $('.elementor-widget-container .etheme-elementor-sidebar').first().clone();
            $('.etheme-elementor-off-canvas_content').html(sidebar);
        });
    };// End of elementorInSizeGuide
})(jQuery);