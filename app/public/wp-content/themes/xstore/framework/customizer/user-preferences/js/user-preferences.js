/**
 * Customizer modes panel scripts
 *
 * all customizer modes scripts here
 *
 * @version 1.0.0
 * @since 1.0.0
 */

var etCustomizerPreferences;

;(function($) {
    "use strict"

    /*****************************************************************
     * Customizer Search functions
     *
     * Functions that bring a life for Customizer Search
     * 
     * {function} init                 - primary initing
     * {function} et_add_modes        - add modes switcher
     *
     * @version 1.0.0
     * @since 1.0.0
     ******************************************************************/
    etCustomizerPreferences = {
    	/**
         * Primary initing
         *
         * @version 1.0.0
         * @since 1.0.0
         */
        init: function() {
            this.et_add_button();
            this.et_ui_settings();
        },// End of init

        /**
         * Add go-to preferences button
         *
         * Move button to its place
         *
         * @version 1.0.0
         * @since 1.0.0
         */
        et_add_button: function(){
        	var colormodes = $( '#et_customizer-user-preferences-wrapper' ).html();
            $( '#et_customizer-user-preferences-wrapper' ).remove();

            $( '#customize-header-actions' ).after( colormodes );
        },// End of et_add_modes

        et_ui_settings: function() {
            // customizer options width
            this.et_customizer_ui_width();

            // ui theme mode
            this.et_customizer_ui_theme();

            // ui options columns
            this.et_customizer_options_columns();

            // ui content zoom
            this.et_customizer_ui_zoom();
        },

        et_customizer_ui_width: function () {
            $(document).on( 'mouseup', '#customize-control-customizer_ui_width input[type="range"]', function(){
                let styles_id = 'customizer_ui_width-styles';
                let styles = 'body {--customizer-ui-width: '+this.value+'%; }';
                if ( $('head').find('#'+styles_id).length )
                    $('head').find('#'+styles_id).html(styles);
                else
                    $('head').append('<style id="'+styles_id+'">'+styles+'</style>');
            });

            $(document).on( 'keyup', '#customize-control-customizer_ui_width input[type="text"]', function(){
                if ( $(this).parent().attr('data-info', null));
                if ( !this.value || this.value < 10 || this.value > 30 ) {
                    $(this).parent().attr('data-info', CustomizeUserPreferences.empty_value.replace('{{min}}', '10').replace('{{max}}', '30'));
                    return;
                }
                let styles_id = 'customizer_ui_width-styles';
                let styles = 'body {--customizer-ui-width: '+this.value+'%; }';
                if ( $('head').find('#'+styles_id).length )
                    $('head').find('#'+styles_id).html(styles);
                else
                    $('head').append('<style id="'+styles_id+'">'+styles+'</style>');
            });

            $(document).on( 'click', '#customize-control-customizer_ui_width .slider-reset', function(){
                let styles_id = 'customizer_ui_width-styles';
                let styles = 'body {--customizer-ui-width: 21%; }';
                if ( $('head').find('#'+styles_id).length )
                    $('head').find('#'+styles_id).html(styles);
                else
                    $('head').append('<style id="'+styles_id+'">'+styles+'</style>');
            });
        },

        et_customizer_ui_theme: function () {
            $(document).on('change', '#customize-control-customizer_ui_theme select', function () {
                var ui_theme = this.value;
                if ( this.value == 'auto' ) {
                    if ( window.matchMedia && window.matchMedia( `(prefers-color-scheme: dark)` ).matches )
                        ui_theme = 'dark';
                    else
                        ui_theme = 'light';
                }
                $('.wp-customizer').attr('data-mode', ui_theme);
            });
        },

        et_customizer_options_columns: function () {
            $(document).on('change', '#customize-control-customizer_options_descriptions select', function () {
                var ui_description_type = this.value;
                if ( ui_description_type == 'description' )
                    $('.wp-customizer').attr('data-options-description', 'yes');
                else
                    $('.wp-customizer').attr('data-options-description', null);
            });

            $(document).on('change', '#customize-control-customizer_options_columns select', function () {
                var ui_cols_amount = this.value;
                if ( ui_cols_amount == 'one' )
                    $('.wp-customizer').attr('data-options-column', 'yes');
                else
                    $('.wp-customizer').attr('data-options-column', null);
            });
        },

        et_customizer_ui_zoom: function () {
            $(document).on( 'mouseup', '#customize-control-customizer_ui_zoom input[type="range"]', function(){
                let styles_id = 'customizer_ui_zoom-styles';
                let styles = 'body {--customizer-ui-content-zoom: '+this.value+'; }';
                if ( $('head').find('#'+styles_id).length )
                    $('head').find('#'+styles_id).html(styles);
                else
                    $('head').append('<style id="'+styles_id+'">'+styles+'</style>');
            });

            $(document).on( 'keyup', '#customize-control-customizer_ui_zoom input[type="text"]', function(){
                if ( $(this).parent().attr('data-info', null));
                if ( !this.value || $.inArray(this.value, ['1.', '.', '0.']) > -1 || this.value < 0.7 || this.value > 1.5 ) {
                    $(this).parent().attr('data-info', CustomizeUserPreferences.empty_value.replace('{{min}}', '0.7').replace('{{max}}', '1.5'));
                    return;
                }
                let styles_id = 'customizer_ui_zoom-styles';
                let styles = 'body {--customizer-ui-content-zoom: '+this.value+'; }';
                if ( $('head').find('#'+styles_id).length )
                    $('head').find('#'+styles_id).html(styles);
                else
                    $('head').append('<style id="'+styles_id+'">'+styles+'</style>');
            });

            $(document).on( 'click', '#customize-control-customizer_ui_zoom .slider-reset', function(){
                let styles_id = 'customizer_ui_zoom-styles';
                let styles = 'body {--customizer-ui-content-zoom: 1; }';
                if ( $('head').find('#'+styles_id).length )
                    $('head').find('#'+styles_id).html(styles);
                else
                    $('head').append('<style id="'+styles_id+'">'+styles+'</style>');
            });
        },

    };// End of etCustomizerModes

   	$(document).ready(function(){
        etCustomizerPreferences.init();
    });
})(jQuery);