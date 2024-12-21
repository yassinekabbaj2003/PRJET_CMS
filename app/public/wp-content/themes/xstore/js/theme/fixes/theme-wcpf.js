/**
 * Fix navigation for wcpf plugin
 *
 * @version 1.0.0
 * @since 1.0.0
 */
;(function ($) {
    "use strict";
    etTheme.autoinit.wcpfPaginationFix = function () {
        $(window).on('wcpf_update_products', function() {
            $('nav.etheme-elementor-pagination').find('a').each(function () {
                let href = $(this).attr('href'),               // Get the current href from pagination links
                    url = new URL(href, window.location.origin), // Ensure the href is converted to a full URL
                    params = new URLSearchParams(url.search),   // Get parameters from the pagination link
                    current_params = new URLSearchParams(window.location.search); // Get current page parameters

                // Merge current_params into params (overwriting existing values if necessary)
                current_params.forEach((value, key) => {
                    params.set(key, value); // Add or update parameters
                });

                // Update the href with the merged parameters
                url.search = params.toString();
                $(this).prop('href', url.toString()); // Set the new href with merged params
            });
        });
    };// End of wcpfPaginationFix
})(jQuery);