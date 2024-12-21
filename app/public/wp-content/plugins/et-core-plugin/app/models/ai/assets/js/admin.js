;(function($) {
    "use strict";

    $(document).ready(function () {
        $(document).find('.cmb2-id--et-ai-answer').addClass('hidden');
    });
    // $(document).on('change', '#_et_ai_content_type', function (e) {
    //     let prompt = $(document).find('#_et_ai_prompt');
    //     prompt.parent().find('.cmb2-metabox-description').html(prompt.data('texts')[$(this).val()]);
    // });

    $(document).on('click', '.et-ai-configure-button', function(e) {
        e.preventDefault();
        var popup = $(this).parent().find('.et_panel-popup');
        popup.removeClass('hidden');
        if ( !$('body').hasClass('block-editor-page'))
            $('body').addClass('et_panel-popup-on');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action : 'et_ajax_ai_load_template',
                template: 'settings_popup',
                model: $(document).find('#_et_ai_content_type').val(),
            },
            dataType: 'json',
            success: function(response) {
                if (response.data && response.data.html){
                    popup.html(response.data.html);
                }
                //alert('AJAX Error found, please contact our dev team for assistance.');
            },
            error: function(response) {
                alert('AJAX Error found, please contact our dev team for assistance.');
            },
            complete: function() {
            }
        });

    });

    $(document).on('click', '#_et_ai_generate', function (e) {
        e.preventDefault();
        $(this).addClass('processing');
        let model = $(document).find('#_et_ai_model_type').val();
        let post_type = $(this).data('post-type');
        let type_val = $(document).find('#_et_ai_content_type').val();
        let content = $(document).find('#_et_ai_prompt');
        let content_val = content.val();
        let style = $(document).find('#_et_ai_write_style');
        let style_val = style.val();

        if ( !content_val ) {
            alert(content.data('texts')[type_val]);
            $(this).removeClass('processing');
            content.parent().find('.cmb2-metabox-description').css({'color': 'red'});
            setTimeout(function () {
                content.parent().find('.cmb2-metabox-description').attr('style', null);
            }, 2000);
            return;
        }
        $(document).find('#_et_ai_answer').addClass('processing');
        var data = {
            type : type_val,
            post_type: post_type,
            content: content_val,
            model: model
        };
        if ( style_val ){
            data.style = style_val
        }

        if ( $(this).data('lang') ){
            data.lang = $(this).data('lang');
        }
        if ( $(document).find('#_et_ai_write_lang').val() ){
            data.lang = $(document).find('#_et_ai_write_lang').val();
        }

        call_assistant(data);
    });

    $(document).on('click', '.et-save-ai-config', function (e) {
        var popup = $(this).parents('.et_popup-ai-configuration');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action : 'et_ajax_ai_save_options',
                data: {
                    temperature: popup.find('#temperature').val(),
                    max_tokens: popup.find('#max_tokens').val(),
                    stop_sequences: popup.find('#stop_sequences').val(),
                    top_p: popup.find('#top_p').val(),
                    frequency_penalty: popup.find('#frequency_penalty').val(),
                    presence_penalty: popup.find('#presence_penalty').val(),
                    best_of: popup.find('#best_of').val(),
                    show_probabilities: popup.find('#show_probabilities').val(),
                    model: $(document).find('#_et_ai_content_type').val(),
                },
                security:  popup.find('[name="nonce_etheme-ai-settings"]').val(),
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.status == 'success') {
                    show_success(response.data.msg, 5000);
                } else {
                    show_error(response.msg);
                }
            },
            error: function(response) {
                alert('AJAX Error found, please contact our dev team for assistance.');
            },
            complete: function() {
                $(document).find('.et-button-cancel').trigger('click');
            }
        });
    });

    $(document).on('click', '.et_ctcb-button', function (e) {
        e.preventDefault();
        var n = $(e.currentTarget),
            i = ($(n).text(), $('#_et_ai_answer').html()),
            r = $("<input>");
        $("body").append(r), r.val(i).select(), document.execCommand("copy"), r.remove();
        let _this = $(this);
        let text = _this.data('text');
        let success_text = _this.data('success-text');
        _this.text(success_text);
        setTimeout(function () {
            _this.text(text);
        }, 1000);
    });

    $(document).on('click', '.et_rtd-button', function (e) {
        $.each( $(document).find('.et_popup-ai-configuration .xstore-panel-option-slider'), function() {
            if ($(this).find('.super-default') && $(this).find('.super-default').val()){
                $(this).find('input:not([type="hidden"])').val($(this).find('.super-default').val());
                $(this).find('.value').text($(this).find('.super-default').val());
            }
        });

        $.each( $(document).find('.et_popup-ai-configuration .xstore-panel-option-code-editor'), function() {
            if ($(this).find('.super-default')){
                $(document).find('textarea:not(.hidden)').val($(this).find('.super-default').val());
                $(document).find('textarea:not(.hidden)').val($(this).find('.super-default').html());
            }
        });
    });

    jQuery(document).ready(function ($) {
        $(document).on('input change', '.et_popup-ai-configuration .xstore-panel-option-slider input[type=range]', function () {
            $(this).parent().find('.value').text($(this).val());
        });
        $(document).on('click', '.et_popup-ai-configuration .xstore-panel-option-slider .reset', function () {
            $(this).parent().find('input:not([type="hidden"])').val($(this).data('default'));
            $(this).parent().find('.value').text($(this).data('default'));
        });
    });


    $(document).on('change', '#_et_ai_content_type', function (e) {
        let content = $( 'input#title' ).length ? $( 'input#title' ).val() : $( 'h1.editor-post-title' ).text(),
            types = ["meta_title", "meta_desc", "meta_key"],
            prompt = $(document).find('#_et_ai_prompt'),
            current = $(this).val();

        if (current == 'custom'){
            $('.cmb2-id--et-ai-write-lang, .cmb2-id--et-ai-write-style').addClass('hidden');
        } else {
            $('.cmb2-id--et-ai-write-lang, .cmb2-id--et-ai-write-style').removeClass('hidden');
        }

        if (current == 'meta_desc'){
            if (
                $(document).find('#aioseo-post-settings-meta-description-row .ql-editor').length
                && $(document).find('#aioseo-post-settings-meta-description-row .ql-editor').text()
            ){
                content = $(document).find('#aioseo-post-settings-meta-description-row .ql-editor').text();
            }
            if (
                $(document).find('#yoast-google-preview-description-metabox').length
                && $(document).find('#yoast-google-preview-description-metabox').text()
            ){
                content = $(document).find('#yoast-google-preview-description-metabox').text();
            }
        }
        if (current == 'meta_title'){
            if (
                $(document).find('#aioseo-post-settings-post-title-row .ql-editor').length
                && $(document).find('#aioseo-post-settings-post-title-row .ql-editor').text()
            ){
                content = $(document).find('#aioseo-post-settings-post-title-row .ql-editor').text();
            }
            if (
                $(document).find('#yoast-google-preview-slug-metabox').length
                && $(document).find('#yoast-google-preview-slug-metabox').val()
                && $(document).find('#yoast-google-preview-slug-metabox').val() !== ''
            ){
                content = $(document).find('#yoast-google-preview-slug-metabox').val();
            }
        }
        if (types.includes(current) && ! prompt.val()){
            prompt.html(content).val(content);
        }
    });



    /**
     * Ajax call for AI response
     *
     * @since 5.1
     * @version 1.0
     */
    function call_assistant(data) {
        $(document).find('.et_assistant-error').addClass('hidden');
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action : 'et_ajax_ai',
                data: data
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.status == 'success') {
                    let answer = $(document).find('.cmb2-id--et-ai-answer'),
                        regex = /^((\s*<br\s*\/>)+)/,
                        content = response.data.replace(regex, "");
                    answer.removeClass('hidden').find('#_et_ai_answer').html(content);
                } else {
                    show_error(response.msg);
                }
            },
            error: function(response) {
                alert('AJAX Error found, please contact our dev team for assistance.');
            },
            complete: function() {
                $(document).find('#_et_ai_generate, #_et_ai_answer').removeClass('processing');
            }
        });
    }

    function show_error(data, timeout){
        if (!data) {
            data = 'Unfortunately, we are unable to provide an explanation for the error at this time, please contact our support team for assistance.';
        }
        $(document).find('.et_assistant-error').html(data).removeClass('hidden');
        if (timeout){
            setTimeout(function () {
                $(document).find('.et_assistant-error').addClass('hidden');
            }, timeout);
        }
    }

    function show_success(data, timeout){
        if (data) {
            $(document).find('.et_assistant-success').html(data).removeClass('hidden');
            if (timeout){
                setTimeout(function () {
                    $(document).find('.et_assistant-success').addClass('hidden');
                }, timeout);
            }
        }
    }


    window.onload = function() {
        let urlParams = new URLSearchParams(window.location.search),
            autofocus = urlParams.get('et_autofocus'),
            is_wpb = urlParams.get('classic-editor');
        if (autofocus === 'et_open_ai') {
            if(is_wpb !== null){
                setTimeout(function () {
                    $(document).find('[href="#et_open_ai"]').focus().trigger('click');
                }, 2000);
            } else {
                $(document).find('[href="#et_open_ai"]').focus().trigger('click');
            }
        }
    };


})(jQuery);