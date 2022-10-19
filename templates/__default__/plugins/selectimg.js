/**
 * images-selector jQuery plugin - Version: 1.0
 * @copyright   &copy; 2005-2022 PHPBoost - 2019 babsolune
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 19
 * @since       PHPBoost 6.0 - 2020 01 20
*/

(function($) {
    $.fn.extend({
        selectImg: function(params) {
            var defaults = {
                ariaLabel : 'Click to select'
            };
            params = $.extend(defaults, params);

            return this.each(function() {

                jQuery(this).hide(); // Hide the select

                var option = jQuery(this).children('option'); // define all options of the list

                // Creation of the list structure which will replace it
                var formList = jQuery(this).parent(),
                    navList = jQuery('<nav/>', {class : 'cssmenu cssmenu-select cssmenu-horizontal', role: 'combobox'}).prependTo(formList),
                    uSelect = jQuery('<ul/>').appendTo(navList),
                    liSelect = jQuery('<li/>', {class : 'has-sub'}).prependTo(uSelect),
                    uList = jQuery('<ul/>', {class : 'options-list', role: 'combobox list'}).appendTo(liSelect);

                var selectHasSelected = jQuery(this).val().length; // check if one of the options is selected

                // build the item structure
                var selectedItem = jQuery('<a/>',{'aria-label' : params.ariaLabel}).prependTo(liSelect),
                    selectedText = jQuery('<span/>').appendTo(selectedItem),
                    selectedImg = jQuery('<img/>').prependTo(selectedItem),
                    selectedIcon = jQuery('<i/>').prependTo(selectedItem);

                jQuery.each(option ,function() {
                    // Get values of each option
                    var textOption = jQuery(this).text(),
                        imgOption = jQuery(this).attr('data-option-img'),
                        iconOption = jQuery(this).attr('data-option-icon'),
                        classOption = jQuery(this).attr('data-option-class'),
                        valueOption = jQuery(this).val(),
                        selectedOption = jQuery(this).attr('selected'),
                        disabledOption = jQuery(this).attr('disabled');

                    if(selectHasSelected && selectedOption == 'selected') // if one of the option is already selected
                    {
                        // Send its values to the fake selector
                        selectedItem.addClass('cssmenu-title current').attr({'data-name' : valueOption, 'data-tooltip-pos': 'top'});
                        selectedText.text(textOption);
                        if(imgOption)
                            selectedImg.attr('src', imgOption).attr('alt', textOption);
                        if(iconOption)
                            selectedIcon.addClass(iconOption);
                    }
                    // Build the complete list of options
                    var optionLi = jQuery('<li/>', {value : valueOption, class: classOption, role: 'combobox option'}).appendTo(uList),
                        optionItem = jQuery('<a/>')
                            .addClass('cssmenu-title')
                            .attr('data-name', valueOption)
                            .appendTo(optionLi),
                        optionText = jQuery('<span/>')
                            .text(textOption)
                            .appendTo(optionItem);

                    if(selectedOption) optionLi.addClass('selected-option'); // Add current class to the selected option

                    if(disabledOption) {
                        optionLi.css('cursor', 'text');
                        optionItem.css('pointer-events', 'none');
                    }

                    if(imgOption)
                        var imgItem = jQuery('<img/>')
                            .attr('src', imgOption)
                            .attr('alt', textOption)
                            .prependTo(optionItem);

                    if(iconOption)
                        var iconItem = jQuery('<i/>')
                            .addClass(iconOption)
                            .prependTo(optionItem);
                });

                // Open/close submenu
                var select = jQuery(this),
                    toggleButton = select.siblings('nav').find('ul li a');
                jQuery(toggleButton).on('click', function(e) {
                    jQuery(toggleButton).closest('.cssmenu-select').toggleClass('cssmenu-open');
                    e.preventDefault();
                });

                jQuery('.options-list a').on('click', function(e) { // When an option is clicked
                    e.preventDefault();
                    // Add selected class
                    jQuery(this).parent().addClass('selected-option');
                    // Get values of the chosen option
                    var newOption = jQuery(this).attr('data-name'),
                        newText = jQuery(this).text(),
                        newImg = jQuery(this).find('img').attr('src'),
                        newIcon = jQuery(this).find('i').attr('class');

                    // Send values to the fake selector
                    jQuery(this).closest('nav').find(selectedText).text(newText);
                    if(newImg != null)
                        jQuery(this).closest('nav').find(selectedImg).removeAttr('src').attr('src', newImg);
                    else
                        jQuery(this).closest('nav').find(selectedImg).removeAttr('src');

                    if(newIcon != null)
                        jQuery(this).closest('nav').find(selectedIcon).removeClass().addClass(newIcon);
                    else
                        jQuery(this).closest('nav').find(selectedIcon).removeClass();

                    // Change val() of the real select
                    jQuery(this).closest('nav').siblings('select').val(newOption).change();

                    // Remove selected class from all other items
                    jQuery(this).parent().siblings().removeClass('selected-option');
                });

                // Close all opened fake select when click outside
                jQuery(document).on('click', function(event) {
                    if (jQuery(event.target).is('.cssmenu-select *') === false)
                        jQuery('.cssmenu-open').removeClass('cssmenu-open');
                });
            });
        }
    });
})(jQuery);
