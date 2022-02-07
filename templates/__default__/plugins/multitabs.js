/**
 * Multi Tabs jQuery plugin - Version: 1.0
 * @copyright   &copy; 2005-2022 PHPBoost - 2019 babsolune
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 11 17
 * @since       PHPBoost 6.0 - 2019 09 06
*/

(function(jQuery) {

    jQuery.fn.extend({
        multiTabs: function(options) {
            var defaults = {
                pluginType: '', // modal, accordion, tabs
                contentClass: '.content-panel',
                animation: false,
                animationClass: 'animated ',
                animationIn: 'slideInLeft',
                animationOut: 'slideOutRight',
                animationDuration: '1000', // milisecondes
                animationDelay: '0', // milisecondes
                accordionSiblings: false,
            };
            options = jQuery.extend(defaults, options);

            if(options.pluginType == 'accordion') // accordion controls management
            {
                jQuery('.open-all-accordions').on('click', function(){
                    jQuery(this).closest('.accordion-container').find('.accordion').addClass('active-panel').css('height', 'auto');
                    jQuery(this).closest('.accordion-container').find('[data-target]').addClass('active-tab');
                });
                jQuery('.close-all-accordions').on('click', function(){
                    jQuery(this).closest('.accordion-container').find('.accordion').removeClass('active-panel');
                    jQuery(this).closest('.accordion-container').find('.accordion').delay(800).queue(function (next) {jQuery(this).css('height', 0); next();});
                    jQuery(this).closest('.accordion-container').find('[data-target]').removeClass('active-tab');
                });
            }

            // When page loads, open target if hash correspond to an id
            var hashUrl = location.hash;
            var hashTarget = hashUrl.substring(1);

            if(options.pluginType == 'modal') {
                jQuery('.modal-container').find(hashUrl).addClass('active-panel');
                jQuery('.modal-container').on('click', '.hide-modal, .close-modal', function() {
                    jQuery('.modal-container ' + hashUrl).removeClass('active-panel'); // remove activation class from the target
                    history.pushState('', '', ' '); // delete the hash of the url without apllying it
                });
            } else if(options.pluginType == 'accordion') {
                if(jQuery('.accordion-container').find('[data-target="'+hashTarget+'"]').closest('.accordion').length){ // if target is nested
                    jQuery('.accordion-container').find('[data-target="'+hashTarget+'"]').closest('.accordion').addClass('active-panel').css('height', 'auto');
                }
                jQuery('.accordion-container').find('[data-target="'+hashTarget+'"]').addClass('active-tab');
                jQuery('.accordion-container').find(hashUrl).addClass('active-panel').css('height', 'auto');
            } else if(options.pluginType == 'tabs') {
                if(hashUrl) {
                    if(jQuery('.tabs-container').find('[data-target="'+hashTarget+'"]').closest('.tabs').length) // if target is nested
                        jQuery('.tabs-container').find('[data-target="'+hashTarget+'"]').closest('.tabs').addClass('active-panel').css('height', 'auto');

                    if(jQuery('.tabs-container').find('[data-target="'+hashTarget+'"]').length) {
                        jQuery('.tabs-container').find('[data-target="'+hashTarget+'"]').addClass('active-tab');
                        jQuery('.tabs-container').find(hashUrl).addClass('active-panel').css('height', 'auto');
                    } else {
                        jQuery('.tabs-container .tabs.first-tab').addClass('active-panel').css('height', 'auto'); // show the first target when the page loads
                    }
                } else {
                    jQuery('.tabs-container .tabs.first-tab').addClass('active-panel').css('height', 'auto'); // show the first target when the page loads
                    jQuery('.tabs-container li:first-child [data-tabs]').addClass('active-tab'); // and add activation class to the first target's trigger
                    jQuery('.tabs-container .tabs .tabs.first-tab').addClass('active-panel').css('height', 'auto'); // show the first target when it's a nested tabs menu
                    jQuery('.tabs-container .tabs li:first-child [data-tabs]').addClass('active-tab'); // and add activation class to the first target's trigger
                }
            }

            return this.each(function() {
                var dataId = jQuery(this).data('target'), // get the target name
                    targetPanel = jQuery('#' + dataId), // set the target var
                    contentPanel = jQuery('#' + dataId + ' ' + options.contentClass),
                    animStyles = {
                        'animation-duration': options.animationDuration + 'ms',
                        'animation-delay': options.animationDelay + 'ms',
                    };

                if(options.pluginType == 'modal')
                {
                    jQuery(this).on('click', function(e) { //when click on a trigger
                        e.preventDefault(); // stop the trigger action
                        history.pushState('', '', '#'+dataId); // set the hash of the url whitout apllying it
                        jQuery(targetPanel).siblings().removeClass('active-panel'); // remove all active panel
                        jQuery(targetPanel).addClass('active-panel'); // add activation class to the target
                        jQuery('.modal-container').on('click', '.close-modal, .hide-modal', function() {
                            jQuery(targetPanel).removeClass('active-panel'); // remove activation class from the target
                            history.pushState('', '', ' '); // delete the hash of the url whitout apllying it
                        });
                        jQuery('.modal [data-target]').on('click', function(){ // when trigger is inside one of the targets
                            jQuery(this).closest('nav').siblings(targetPanel).removeClass('active-panel'); // remove activation class from the target
                            jQuery(this).closest('.modal').removeClass('active-panel'); // remove activation class from the running target
                        });
                        if(options.animation) { // if animate.css is active
                            jQuery(targetPanel).removeClass().css(animStyles);// remove all classes from target & add animation details attributes
                            jQuery(targetPanel).addClass('modal active-panel ' + options.animationClass + ' ' + options.animationIn); // then add necessary opening classes for animate.css
                            jQuery('.modal-container').on('click', '.close-modal, .hide-modal', function(){
                                jQuery(this).parent().removeClass(options.animationIn).addClass(options.animationOut); // change animation classes to closing ones
                            });
                        }
                    });
                }
                else if(options.pluginType == 'accordion')
                {
                    jQuery(this).after(targetPanel).appendTo(); // get target and place it just after trigger
                    jQuery(this).on('click', function(e) {
                        e.preventDefault(); // stop the trigger action
                        history.pushState('', '', '#'+dataId); // set the hash of the url whitout apllying it
                        var contentHeight = contentPanel.outerHeight(); // calculate height of target
                        if(options.accordionSiblings == true) {
                            jQuery(this).closest('.accordion-container').find('.accordion').not(targetPanel).removeClass('active-panel').height(0);
                            jQuery(this).closest('.accordion-container').find('[data-accordion]').not(this).removeClass('active-tab');
                        }
                        if(targetPanel.hasClass('active-panel')) // if target is active
                        {
                            jQuery(this).removeClass('active-tab');
                            jQuery(targetPanel).removeClass('active-panel'); // remove activation class
                            jQuery(targetPanel).css('height', 0); // set height of target to zero
                            history.pushState('', '', ' '); // delete the hash of the url whitout apllying it
                            if(options.animation) { // if animate.css
                                jQuery(targetPanel).removeClass().css(animStyles);// remove all classes from target & add animation details attributes
                                jQuery(targetPanel).addClass('accordion ' + options.animationClass + ' ' + options.animationOut);  // then add necessary closing classes for animate.css
                            }
                        }
                        else // if target is not active
                        {
                            jQuery(this).addClass('active-tab');
                            jQuery(targetPanel).addClass('active-panel'); // add activation class
                            jQuery(targetPanel).css('height', contentHeight + 'px'); // set the height of the target
                            if(jQuery(this).parents(options.contentClass).length) // if the trigger is inside a target
                            {
                                jQuery(targetPanel).closest(options.contentClass).closest('.accordion').css('height', 'auto');
                            }
                            if(options.animation) { // if animate.css
                                jQuery(targetPanel).removeClass().css({ // remove all classes from target & add animation details attributes
                                    'animation-duration': options.animationDuration + 'ms',
                                    'animation-delay': options.animationDelay + 'ms',
                                });
                                jQuery(targetPanel).addClass('accordion active-panel ' + options.animationClass + ' ' + options.animationIn); // then add necessary opening classes for animate.css
                            }
                        }
                    });
                }
                else if(options.pluginType == 'tabs')
                {
                    jQuery(this).on('click', function(e) {
                        e.preventDefault(); // stop the trigger action
                        history.pushState('', '', '#'+dataId); // send the id of the target as hash of the url whitout apllying it
                        if(!jQuery(this).hasClass('active-tab')) // if the trigger isn't active
                        {
                            jQuery(targetPanel).siblings('.tabs').removeClass('active-panel').css('height', 0); // remove the activation class from all targets and set height of targets to zero
                            jQuery(this).parent().siblings().find('[data-target]').removeClass('active-tab'); // and from all sibling triggers
                        }
                        jQuery(this).addClass('active-tab'); // add activation class to the trigger
                        jQuery(targetPanel).addClass('active-panel').css('height', 'auto'); // set the height of the target
                        if(jQuery(this).parents(options.contentClass).length) // if the trigger is inside a target
                        {
                            jQuery(targetPanel).closest(options.contentClass).closest('.tabs').css('height', 'auto'); // set the new height of the container
                        }
                    });
                }
            });


        }

    });
})(jQuery);
