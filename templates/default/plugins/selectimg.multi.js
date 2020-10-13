/**
 * images-selector multiple jQuery plugin - Version: 1.0
 * @copyright   &copy; 2005-2020 PHPBoost - 2019 babsolune
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 10 12
 * @since       PHPBoost 6.0 - 2020 10 12
*/

(function($) {
    $.fn.extend({
        multipleSelectImg: function() {

            return this.each(function() {

                jQuery(this).hide(); // Hide the select

                var option = jQuery(this).children('option'); // define all options of the list

                // Creation of the list structure which will replace it
                var formList = jQuery(this).parent(),
                    navList = jQuery('<nav/>', {class : 'cssmenu cssmenu-select-multiple cssmenu-vertcal'}).prependTo(formList),
                    uSelect = jQuery('<ul/>', {class : 'multiple-select-list'}).appendTo(navList);

                jQuery.each(option ,function() {
                    // Get values of each option
                    var
                        $this = jQuery(this),
                        textOption = jQuery(this).text(),
                        imgOption = jQuery(this).attr('data-option-img'),
                        iconOption = jQuery(this).attr('data-option-icon'),
                        classOption = jQuery(this).attr('data-option-class'),
                        valueOption = jQuery(this).val(),
                        selectedOption = jQuery(this).attr('selected');

                    // Build the complete list of options
                    var optionLi = jQuery('<li/>', {value : valueOption}).appendTo(uSelect),
                        optionItem = jQuery('<a/>')
                            .addClass('cssmenu-title')
                            .attr('data-name', valueOption)
                            .appendTo(optionLi),
                        optionText = jQuery('<span/>')
                           	.text(textOption)
                            .appendTo(optionItem);

                    if(classOption)
                        optionItem.addClass(' ' + classOption);

                    if(selectedOption) optionLi.addClass('selected-option'); // Add selected-option class to the selected option

                    if(imgOption)
                        var imgItem = jQuery('<img/>')
                            .attr('src', imgOption)
                            .attr('alt', textOption)
                            .prependTo(optionItem);
                    if(iconOption)
                        var iconItem = jQuery('<i/>')
                            .addClass(iconOption)
                            .prependTo(optionItem);

                    // Change selected value of the option in the real select
                    optionItem.on('click', function(e){
                        e.preventDefault();
                        optionLi.toggleClass('selected-option');
                        $this.attr('selected', function(index, attr){
                            return attr == 'selected' ? null : 'selected';
                        });
                    });
                });
            });
        }
    });
})(jQuery);
