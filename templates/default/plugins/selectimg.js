/**
 * images-selector jQuery plugin - Version: 1.0
 * @copyright   &copy; 2005-2020 PHPBoost - 2019 babsolune
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 20
 * @since       PHPBoost 5.3 - 2020 01 20
*/

(function($) {
    $.fn.extend({
        selectimg: function(params) {
            var defaults = {
                ariaLabel : 'Click to select'
            };
            params = $.extend(defaults, params);

            return this.each(function() {

                jQuery(this).hide(); // Hide the select

                var option = jQuery(this).children('option'); // define all options of the list

                // Creation of the list structure which will replace it
                var formList = jQuery(this).parent(),
                    navList = jQuery('<nav/>', {class : 'cssmenu cssmenu-select cssmenu-horizontal'}).prependTo(formList),
                    uSelect = jQuery('<ul/>').appendTo(navList),
                    liSelect = jQuery('<li/>', {class : 'has-sub'}).prependTo(uSelect),
                    uList = jQuery('<ul/>', {class : 'options-list'}).appendTo(liSelect);

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
                        valueOption = jQuery(this).val(),
                        selectedOption = jQuery(this).attr('selected');

                    if(selectHasSelected && selectedOption == 'selected') // if one of the option is already selected
                    {
                        // Send its values to the fake selector
                        selectedItem.addClass('cssmenu-title current').attr('data-name', valueOption);
                        selectedText.text(textOption);
                        if(imgOption)
                            selectedImg.attr('src', imgOption).attr('alt', textOption);
                        if(iconOption)
                            selectedIcon.addClass(iconOption);
                    }
                    // Build the complete list of options
                    var optionLi = jQuery('<li/>', {value : valueOption}).appendTo(uList),
                        optionItem = jQuery('<a/>')
                            .addClass('cssmenu-title')
                            .attr('data-name', valueOption)
                            .appendTo(optionLi),
                        optionText = jQuery('<span/>')
                           	.text(textOption)
                            .appendTo(optionItem);
                    if(selectedOption) optionLi.addClass('current'); // Add current class to the selected option
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
                    // Get values of the chosen option
                    var newOption = jQuery(this).attr('data-name'),
                        newText = jQuery(this).text(),
                        newImg = jQuery(this).find('img').attr('src'),
                        newIcon = jQuery(this).find('i').attr('class') ;

                    // Send values to the fake selector
                    jQuery(this).closest('nav').find(selectedText).text(newText);
                    if(newImg != null)
                        jQuery(this).closest('nav').find(selectedImg).removeAttr('src').attr('src', newImg);

                    if(newIcon != null)
                        jQuery(this).closest('nav').find(selectedIcon).removeClass().addClass(newIcon);

                    // Change val() of the real select
                    jQuery(select).val(newOption).change();
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
