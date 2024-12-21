;(function($, window, document, undefined){
    $(document).ready(function ($) {
        // fix for theme builders container layout
        $(document).find(".elementor-theme-builder-content-area").addClass("container");

        // Dark/Light switcher
        $('.et-elementor-editor-dark-light-switcher .switcher').on('click', function (e) {
            e.preventDefault();
            let is_light_active = $(this).hasClass('light-mode');
            let active_color = $(this).data('dark-color');
            if ( is_light_active ) {
                $(this).addClass('dark-mode').removeClass('light-mode');
            }
            else {
                $(this).addClass('light-mode').removeClass('dark-mode');
                active_color = $(this).data('light-color');
            }
            $('.page-wrapper').css('background-color', active_color);
            $('body').css('background-color', active_color);
        })

        // Thumbnail quick actions
        $(document).on('click', '.et-elementor-editor-thumbnail-action', function (e) {
            let action = $(this).data('action');
            let selector = $(this).data('selector');
            let postType = $(this).data('post_type');
            let postId = $(this).data('post_id');
            let setThumbnail = false;

            let security = $(this).parent().find('input[name=etheme_'+postType+'_nonce]').val();
            let removeButton = $(this).parent().find('[data-action=remove]');

            let hasSettingsPopup = $(document).find('.et_panel-popup').length;

            switch (action) {
                case 'upload':
                    var fileUploader = '',
                        attachment,
                        saveValue;
                    fileUploader = wp.media({
                        // title: 'Upload thumbnail image',
                        // button: {
                        //     text: 'Upload thumbnail image button text'
                        // },
                        multiple: false,  // Set this to true to allow multiple files to be selected.
                        library:
                            {
                                type: ['image', 'image/svg+xml']
                            }
                    })
                        .on('select', function () {
                            attachment = fileUploader.state().get('selection').first().toJSON();
                            saveValue = attachment.url;
                            // if ( saveAs == 'id' ) {
                            //     saveValue = attachment.id
                            // }
                            // if ($.inArray(fileType, ['image', 'image/svg+xml']) > -1) {
                                $(document).find(selector).css({'backgroundImage': 'url("'+attachment.url+'")'});
                            // }
                            setThumbnail = attachment.id;
                            setDynamicThumbnail(postType, postId, security, setThumbnail);
                            removeButton.show();
                        })
                        .open();
                    break;
                case 'remove':
                    $(document).find(selector).css({'backgroundImage': 'none'});
                    setDynamicThumbnail(postType, postId, security, 0);
                    removeButton.hide();
                    break;
                case 'settings':
                    if ( !hasSettingsPopup ) {
                        $('body').prepend('<div class="et_panel-popup"></div>')
                    }
                    let popup = $(document).find('.et_panel-popup');
                    $.ajax({
                        method: "POST",
                        url: etElementorFrontendEditorConfig.ajaxUrl,
                        data: {
                            action: 'etheme_slide_settings',
                            security: security,
                            postType: postType,
                            postId: postId,
                        },
                        success: function (response) {
                            $('body').addClass('et_panel-popup-on');
                            popup.html('').addClass('loading');
                            popup.prepend('<span class="et_close-popup et-button-cancel et-button"><i class="et-icon et-delete"></i></span>');
                            popup.addClass('style-2 panel-popup-theme-'+postType+'_settings').append(response.content);

                            // popup.find('.color-field').wpColorPicker({});

                            popup.addClass('active').removeClass('loading');
                        },
                        error: function (response) {
                        },
                        complete: function (response) {
                            $(document).trigger('et_panel_popup_loaded', [popup]);
                        }
                    });
                    break;
            }
        });

        function setDynamicThumbnail (postType, postId, security, setThumbnail) {
            $.ajax({
                url: etElementorFrontendEditorConfig.ajaxUrl,
                method: 'POST',
                data: {
                    action: 'etheme_elementor_dynamic_thumbnail',
                    security: security,
                    postType: postType,
                    postId: postId,
                    setThumbnail: setThumbnail > 0 ? 'yes' : '',
                    thumbnailId: setThumbnail
                },
                success: function (respond) {
                    console.log(respond);
                }
            });
        };

        var slide_selector = 'body .swiper-slide-contents';
        var addedInlineStyle = false;
        var collectedStyles = [];

        function liveCSS(event) {
            if ( !addedInlineStyle ) {
                $('head').prepend('<style id="et-elementor-editor-inline-style"></style>');
                addedInlineStyle = $(document).find('#et-elementor-editor-inline-style');
            }
            if ( !!event.target.nodeName ) {
                let value = $(event.target).val();
                addedInlineStyle.append(slide_selector + ' {'+event.target.id.replace('_', '-') + ': '+value+'}');
                collectedStyles[event.target.id.replace('_', '-')] = value;
            }
            // switch (e.target.nodeName) {
            //     case 'SELECT':
            //         let value = $(event.target).val();
            //         switch (event.target.id) {
            //             case 'background_repeat':
            //             case 'background_position':
            //             case 'background_size':
            //                 collectedStyles[event.target.id.replace('_', '-')] = value;
            //                 break;
            //         }
            //         break;
            //     case 'INPUT':
            //         switch (event.target.id) {
            //             case 'background_color':
            //                 break
            //         }
            //         break;
            // }
        }

        $(document).on('click', '.et_close-popup', function (e) {
            if ($(this).hasClass('processing')){
                if (!confirm('Are you sure? Your delete process will be lost if you leave this page. ')){
                    e.preventDefault();
                    return;
                }
            }
            if ($(this).hasClass('reload')){
                location.reload();
            }

            let hide = false;
            if ( $(this).hasClass('hide-popup')) {
                hide = true;
            }
            let $panel = $('.et_panel-popup');
            $panel.addClass('inactive closing');
            setTimeout(function () {
                $panel.removeClass('inactive')
            }, 20);
            setTimeout(function () {
                if ( hide ) {
                    $panel.removeClass('active auto-size').addClass('hidden');
                }
                else {
                    $panel.html('').removeClass('active auto-size');
                }
                $panel.removeClass('closing style-2');
                $('body').removeClass('et_panel-popup-on');
            }, 300);
            collectedStyles = [];
        });

        $(document).on('change', 'form.xstore-panel-settings[data-in-popup="yes"]', function (e) {
            liveCSS(e);
            if ( $(this).data('et_triggered') ) return;
            $(this).find('button[type="submit"]').attr('style', null);
            $(this).data('et_triggered', 'yes');
        });

        $(document).on('keyup keydown', 'form.xstore-panel-settings[data-in-popup="yes"] textarea, form.xstore-panel-settings[data-in-popup="yes"] input', function (e) {
            liveCSS(e);
            let form = $(this).parents('form.xstore-panel-settings');
            if ( $(form).data('et_triggered') ) return;
            $(form).find('button[type="submit"]').attr('style', null);
            $(form).data('et_triggered', 'yes');
        });
        $(document).on('click', 'form.xstore-panel-settings[data-in-popup="yes"] .xstore-panel-repeater .add-item, form.xstore-panel-settings[data-in-popup="yes"] .xstore-panel-repeater .remove-item', function (e) {
            let form = $(this).parents('form.xstore-panel-settings');
            if ( $(form).data('et_triggered') ) return;
            $(form).find('button[type="submit"]').attr('style', null);
            $(form).data('et_triggered', 'yes');
        });

        // save submit action
        $(document).on('submit', 'form.xstore-panel-settings', function (e) {
            e.preventDefault();

            let postType = 'etheme_slides';
            $.ajax({
                method: "POST",
                url: etElementorFrontendEditorConfig.ajaxUrl,
                data: {
                    action: 'etheme_slide_save_settings',
                    postType: postType,
                    security: $(this).find('input[name=etheme_'+postType+'_save_settings_nonce]').val(),
                    postId: $(this).attr('data-post_id'),
                    local_settings: Object.assign({}, collectedStyles),
                },
                success: function (response) {
                    let popup = $(document).find('.et_panel-popup');
                    let save_alert = popup.find('.saving-alert');
                    save_alert.removeClass('hidden').slideDown();
                    setTimeout(function () {
                        save_alert.slideUp();
                    }, 5000);
                    // $(document).find('.et_panel-popup .et_close-popup').trigger('click');
                },
                error: function (response) {
                },
                complete: function (response) {
                }
            });
        });
    });

})(jQuery, window, document);