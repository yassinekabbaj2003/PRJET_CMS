/**
 * Description
 *
 * @package    callbacks.js
 * @since      1.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */
jQuery(document).ready(function ($) {
   $(document).find('[data-callbacks]').each( function (){
       callback_init($(this));
   });
   function callback_init(_this) {
       var callbacks = _this.attr('data-callbacks');
       var callbacks2 = callbacks.split(',');
       var values_compare = [];
       $.each(callbacks2, function() {
           var callback = this.split(':');
           values_compare = version_compare($(document).find('#'+callback[0]).val(), callback[0], callback[1], values_compare);
           $(document).on('change', '#'+callback[0], function (){
               // var check_val = $('#'+callback[0]).val();
               var check_val = this.value;
               if ( this.tagName == 'INPUT' && $.inArray(this.type, ['radio', 'checkbox']) > -1) {
                   check_val = $(this).prop('checked') == true ? 'on' : '';
               }
               values_compare = version_compare(check_val, callback[0], callback[1], values_compare);
               if ( values_compare.length < 1) {
                   _this.slideDown();
               }
               else {
                   _this.slideUp();
               }
           });
       });
   }
   function version_compare (first_value, first_name, needed_value, values_compare){
       if ( first_value == needed_value) {
           // delete values_compare[callback[0]];
           values_compare = $.grep(values_compare, function( a ) {
               return a !== first_name;
           });
       }
       else {
           values_compare.push(first_name);
       }
       return values_compare;
   }

    $(document).on('et_panel_popup_loaded', function (e, popup) {
        popup.find('form.xstore-panel-settings [data-callbacks]').each(function () {
            callback_init($(this));
        });
    });
});