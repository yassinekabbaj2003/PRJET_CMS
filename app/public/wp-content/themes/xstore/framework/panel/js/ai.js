/**
 * Panel AI page scripts
 *
 * @version 1.0.0
 * @since 9.1.0
 */

window.et_panel = window.et_panel || {};
!function($) {
    $(document).ready(function ($) {
        var et_AI = {
            instagram_save_network_options: function(_this,e) {
                e.preventDefault();
                _this.parents('.etheme-div').find('.etheme-network-save-info').addClass('hidden');
                _this.closest('.etheme-div').addClass('processing');

                et_panel.popup_configuration.openPopup();

                var data = {
                    action: 'etheme_update_network',
                    helper: 'instagram',
                    form: _this.closest('form').serializeArray(),
                    security:  $(document).find('[name="nonce_update_network-settings"]').val(),
                };
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: data,
                    success: function (data) {
                        et_panel.popup_configuration.closePopup(data);
                    },
                    error: function () {
                        alert('Error while deleting');
                    },
                    complete: function () {
                        _this.closest('.etheme-div').removeClass('processing');
                    }
                });
            },
        };

        window.et_panel = Object.assign(window.et_panel,et_AI);

        var et_AI_actions = {
            '1': {
                selector: '.etheme-network-save',
                type: 'click',
                callback : et_panel.instagram_save_network_options
            },
        }

        $.each(et_AI_actions, function (i, t) {
            if (t.type == 'ready'){
                $(document).ready(function (e) {
                    t.callback($(this),e);
                });
            } else {
                $(t.selector).on(t.type,function (e){
                    t.callback($(this),e);
                });
            }
        });
    });
}(jQuery,window.et_panel);