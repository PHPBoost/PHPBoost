/**
 * images-selector jQuery plugin - Version: 1.0
 * @copyright   &copy; 2005-2020 PHPBoost - 2019 babsolune
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babso@labsoweb.fr>
 * @version     PHPBoost 5.3 - last update: 2020 01 20
 * @since       PHPBoost 5.3 - 2020 01 20
*/

(function($) {
    $.fn.extend({
        selectimg: function(params) {
            var defaults = {
                selector : ''
            };
            params = $.extend(defaults, params);

            return this.each(function() {
                jQuery(this).hide();
                var formList = jQuery(this).parent(),
                    navList = jQuery('<nav/>', {class : 'cssmenu cssmenu-horizontal cssmenu-select'}).prependTo(formList),
                    uSelect = jQuery('<ul/>').appendTo(navList),
                    liSelect = jQuery('<li/>', {class : 'has-sub'}).prependTo(uSelect),
                    uList = jQuery('<ul/>', {class : 'options-list'}).appendTo(liSelect);
                var option = jQuery(this).children('option');
                jQuery.each(option ,function() {
                    var textOption = jQuery(this).text(),
                        imgOption = jQuery(this).attr('data-option-img'),
                        iconOption = jQuery(this).attr('data-option-icon'),
                        valueOption = jQuery(this).val(),
                        selectedOption = jQuery(this).attr('selected');
                    if(selectedOption == 'selected')
                    {
                        var selectedItem = jQuery('<a/>')
                                .addClass('cssmenu-title current')
                                .attr('name', valueOption)
                                .prependTo(liSelect),
                            selectedText = jQuery('<span/>')
                               	.text(textOption)
                                .appendTo(selectedItem);
                        if(imgOption)
                            var selectedImg = jQuery('<img/>')
                                .attr('src', imgOption)
                                .prependTo(selectedItem);
                        if(iconOption)
                            var selectedIcon = jQuery('<i/>')
                                .addClass('fa fa-'+ iconOption)
                                .prependTo(selectedItem);
                    }
                    var optionLi = jQuery('<li/>', {value : valueOption}).appendTo(uList),
                        optionItem = jQuery('<a/>')
                            .addClass('cssmenu-title')
                            .attr('name', valueOption)
                            .appendTo(optionLi),
                        optionText = jQuery('<span/>')
                           	.text(textOption)
                            .appendTo(optionItem);
                        if(imgOption)
                            var imgItem = jQuery('<img/>')
                                .attr('src', imgOption)
                                .prependTo(optionItem);
                        if(iconOption)
                            var iconItem = jQuery('<i/>')
                                .addClass('fa fa-'+ iconOption)
                                .prependTo(optionItem);

                });
                var select = jQuery(this);
                jQuery('.options-list a').on('click', function() {
                    var newOption = jQuery(this).attr('name');
                    jQuery(select).val(newOption).trigger('change');
                });
            });
        }
    });
})(jQuery);
