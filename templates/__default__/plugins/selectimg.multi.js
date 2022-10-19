/**
 * images-selector multiple jQuery plugin - Version: 1.0
 * @copyright   &copy; 2005-2022 PHPBoost - 2019 babsolune
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 10 14
 * @since       PHPBoost 6.0 - 2020 10 12
*/

(function($) {
    $.fn.extend({
        multipleSelectImg: function() {

            return this.each(function() {

                jQuery(this).hide(); // Hide the select
                jQuery(this).siblings('.general-selector').hide();

                var option = jQuery(this).children('option'); // define all options of the list

                // Creation of the list structure which will replace it
                var formList = jQuery(this).parent(),
                    navList = jQuery('<nav/>', {class : 'cssmenu cssmenu-select-multiple cssmenu-vertcal'}).prependTo(formList),
                    uSelect = jQuery('<ul/>', {class : 'multiple-select-list', role: 'listbox'}).appendTo(navList);

                jQuery.each(option ,function() {
                    // Get values of each option
                    var
                        $this = jQuery(this),
                        textOption = jQuery(this).text(),
                        imgOption = jQuery(this).attr('data-option-img'),
                        iconOption = jQuery(this).attr('data-option-icon'),
                        classOption = jQuery(this).attr('data-option-class'),
                        valueOption = jQuery(this).val(),
                        selectedOption = jQuery(this).attr('selected'),
                        disabledOption = jQuery(this).attr('disabled');

                    // Build the complete list of options
                    var optionLi = jQuery('<li/>', {value : valueOption, role: 'listbox option'}).appendTo(uSelect),
                        optionItem = jQuery('<a/>')
                            .addClass('cssmenu-title')
                            .attr('data-name', valueOption)
                            .appendTo(optionLi),
                        optionText = jQuery('<span/>')
                            .text(textOption)
                            .appendTo(optionItem);

                    if(classOption)
                        optionItem.addClass(' ' + classOption);

                    if(disabledOption) {
                        optionLi.css('cursor', 'text').addClass('disabled-option');
                        optionItem.css('pointer-events', 'none');
                    }

                    if(selectedOption) optionLi.addClass('selected-option').attr('aria-selected', true); // Add selected-option class to the selected option

                    if(imgOption)
                        var imgItem = jQuery('<img/>')
                            .attr('src', imgOption)
                            .attr('alt', textOption)
                            .prependTo(optionItem);
                    if(iconOption)
                        var iconItem = jQuery('<i/>')
                            .addClass(iconOption)
                            .prependTo(optionItem);

                    // Change selected value of the option
                    optionItem.on('click', function(e){
                        e.preventDefault();
                        optionLi.toggleClass('selected-option');
                        $this.attr('selected', function(index, attr){
                            return attr == 'selected' ? null : 'selected';
                        });
                        optionLi.attr('aria-selected', function(index, access){
                            return access == 'true' ? null : true;
                        });
                    });
                });

                var $select = jQuery(this),
                    selectAll = $select.siblings('.select-all'),
                    deselectAll = $select.siblings('.deselect-all');

                selectAll.on('click', function(){
                    $select.siblings('.cssmenu-select-multiple').find('li:not(.disabled-option)').addClass('selected-option').attr('aria-selected', 'true');
                    $select.find('option:not([disabled])').attr('selected', 'selected');
                });

                deselectAll.on('click', function(){
                    $select.siblings('.cssmenu-select-multiple').find('li:not(.disabled-option)').removeClass('selected-option').attr('aria-selected', null);
                    $select.find('option:not([disabled])').attr('selected', null);
                });
            });
        }
    });
})(jQuery);
