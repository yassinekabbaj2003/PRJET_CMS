/**
 * Description
 *
 * @package    slider.js
 * @since      1.0.0
 * @version    1.0.1
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */
jQuery(document).ready(function ($) {
    $(document).on('input change', '.xstore-panel-option-slider input[type=range]', function () {
        $(this).parent().find('.value').text($(this).val());
    });
    $(document).on('click', '.xstore-panel-option-slider .reset', function () {
        $(this).parent().find('input').val($(this).data('default'));
        $(this).parent().find('.value').text($(this).data('default'));
    });
});