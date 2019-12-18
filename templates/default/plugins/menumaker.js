/**
 * Responsive dropdown Menu jQuery plugin - Version: 1.0.2
 * @copyright   &copy; 2005-2020 PHPBoost - 2015 CssMenuMaker
 * @license     https://www.opensource.org/licenses/mit-license.php
 * @author      CssMenuMaker
 * @link        https://app.cssmenumaker.com/?theme_id=8
 * @version     PHPBoost 5.3 - last update: 2019 11 18
 * @since       PHPBoost 5.0 - 2016 03 30
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

(function($) {

	$.fn.menumaker = function(options) {

		var cssmenu = $(this), settings = $.extend({
			title: "Menu",
			format: "dropdown",
			breakpoint: 768,
			sticky: false,
			static: false,
			actionslinks: false
		}, options);

		var regtitle = new RegExp('[^A-Za-z0-9]', 'gi');

		return this.each(function() {
			menu_title = settings.title.replace(regtitle,'').toLowerCase();
			cssmenu.find('li ul').parent().addClass('has-sub');
			cssmenu.prepend('<div id="menu-button-' + menu_title + '" class="menu-button">' + settings.title + '</div>');
			$(this).find(".cssmenu-img").prependTo(this);
			$(this).find(".cssmenu-img").clone().prependTo( "#menu-button-" + menu_title );
			$(this).find('#menu-button-' + menu_title).on('click', function(){
				$(this).toggleClass('menu-opened');
				var mainmenu = $(this).next('ul');
				if (mainmenu.hasClass('open')) {
					mainmenu.addClass('close').removeClass('open');
				}
				else {
					mainmenu.removeClass('close').addClass('open');
				}
			});

			multiTg = function() {
				cssmenu.find('.has-sub').prepend('<span class="submenu-button"></span>');
				cssmenu.find('.submenu-button').on('click', function() {
					$(this).toggleClass('submenu-opened');
					if ($(this).siblings('ul').hasClass('open')) {
						$(this).siblings('ul').addClass('close').removeClass('open');
					}
					else {
						$(this).siblings('ul').addClass('open').removeClass('close');
					}
				});
			};

			multiTg();

			resizeFix = function() {
				$smallscreen = window.matchMedia('(max-width: ' + settings.breakpoint + 'px)').matches;

				if (!$smallscreen) {
					cssmenu.find('ul').removeClass('close');
					cssmenu.find('ul').removeClass('open');
					cssmenu.removeClass('small-screen');
					cssmenu.find('#menu-button-' + menu_title).removeClass('menu-opened');
				}

				if ($smallscreen && !cssmenu.hasClass('small-screen')) {

					if (settings.static) {
						cssmenu.find('ul').addClass('open');
						cssmenu.find('ul').removeClass('close');
						cssmenu.find('#menu-button-' + menu_title).addClass('menu-opened');
					}

					if (settings.actionslinks) {
						cssmenu.find('.level-0').addClass('close');
						cssmenu.find('.level-0').removeClass('open');
					}

					if (!settings.actionslinks && !settings.static) {
						cssmenu.find('ul').removeClass('open');
						cssmenu.find('ul').addClass('close');
					}

					cssmenu.addClass('small-screen');
				}

			};

			resizeFix();
			return $(window).on('resize', resizeFix);
		});
	};
})(jQuery);


// Current li - send class .current to an item from a cssmenu and it's parents if the item href correspond to the page url
	jQuery('.cssmenu-title').each(function(){
		var link = jQuery(this).attr('href');
		if(window.location.href.indexOf(link) > -1) { // if page url contains href of one of the cssmenu items
			jQuery(this).parent().addClass('current'); // add class to it's parent (should be 'li')
			if(jQuery(this).closest('.has-sub').length) { // if item is in subfolder
				jQuery(this).closest('.has-sub').addClass('current');  // add class to the parent subfolder
				jQuery(this).closest('.cssmenu > ul > li').addClass('current'); // and to the first ancestor
			}
		}
	});

// Current li - send class .current to an item from the admin menu and it's parents if the item href correspond to the page url
	jQuery('.modal-menu a').each(function(){
		var link = jQuery(this).attr('href');
		if(window.location.href.indexOf(link) > -1) { // if page url contains href of one of the cssmenu items
			jQuery(this).parent().addClass('current'); // add class to it's parent (should be 'li')
			if(jQuery(this).closest('.modal-menu > li').length) { // if item is in subfolder
				jQuery(this).closest('.modal-menu > li').addClass('current'); // and to the first ancestor in admin panel
				var rootLink = jQuery(this).closest('.modal').attr('id'); // get the target
				jQuery('[data-target="'+rootLink+'"]').parent().addClass('current');
			}
		}
	});

// add picture width
 	jQuery('.cssmenu-title').each(function(){
		if(jQuery(this).children('img').length) {
			var imgWidth = jQuery(this).children('img').outerWidth(),
				marginWidth = jQuery(this).children('span').css('marginLeft');
			jQuery(this).css('padding-right', 'calc(' + imgWidth + 'px + ' + marginWidth + ')');
		}
	});
