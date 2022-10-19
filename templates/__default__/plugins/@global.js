// Search string into string
	function strpos(haystack, needle)
	{
		var i = haystack.indexOf(needle, 0); // returns -1
		return i >= 0 ? i : false;
	}

// BBCode block: Hide
	// Add the informations : icon to hide/text to reveal content
	var add_icon_bbcodeblockhide = (callback) => {
		if (document.readyState != "loading") callback();
		else document.addEventListener("DOMContentLoaded", callback);
	}

	add_icon_bbcodeblockhide(() => {
		Array.prototype.forEach.call( document.querySelectorAll(".formatter-hide") , function (el, i) {
			if ( el.classList.contains('no-js') )
			{
				el.setAttribute('id', "formatter-hide-container-" + i);
				el.classList.remove('no-js');
				el.setAttribute('onClick', "bb_hide(" + i + ", 1, event);");

				var parent1 = document.createElement("span");
				parent1.setAttribute('id', "formatter-hide-message-" + i);
				parent1.setAttribute('class', "formatter-hide-message");
				var content1 = document.createTextNode(L_HIDE_MESSAGE);
				parent1.appendChild(content1);

				el.insertBefore(parent1, el.childNodes[1].nextSibling);

				var parent2 = document.createElement("span");
				parent2.setAttribute('id', "formatter-hide-close-button-" + i);
				parent2.setAttribute('class', "formatter-hide-close-button pinned error");
				parent2.setAttribute('aria-label', L_HIDE_HIDEBLOCK);
				parent2.setAttribute('onclick', "bb_hide(" + i + ", 0, event)");

				var child2 = document.createElement("i");
				child2.setAttribute('class', "fa fa-times");

				parent2.appendChild(child2);

				el.insertBefore(parent2, el.childNodes[2]);
			}
		})
	});

	// Hide/show content
	function bb_hide(idcode, show, event)
	{
		var idcode = (typeof idcode !== 'undefined') ? idcode : 0;
		var show = (typeof show !== 'undefined') ? show : 0;
		var elem = document.getElementById('formatter-hide-container-' + idcode);

		event.stopPropagation();

		elem.classList.toggle('formatter-show');

		if (show == 1) elem.removeAttribute('onClick');
		else elem.setAttribute('onclick', "bb_hide(" + idcode + ", 1, event)");
	}

// BBCode block: Code
	// Add button "Copy to clipboard" on Coding block
	var add_button_copytoclipboard = (callback) => {
		if (document.readyState != "loading") callback();
		else document.addEventListener("DOMContentLoaded", callback);
	}

	add_button_copytoclipboard(() => {
        Array.prototype.forEach.call( document.querySelectorAll(".formatter-code") , function (el, i) {

			if ( !el.childNodes[1].classList.contains('copy-code-content') )
			{
				var parent = document.createElement("span");
				parent.setAttribute('id', "copy-code-" + i);
				parent.setAttribute('class', "copy-code");
				parent.setAttribute('aria-label', L_COPYTOCLIPBOARD);
				parent.setAttribute('onclick', "copy_code_clipboard(" + i + ")");

				var child = document.createElement("i");
				child.setAttribute('class', "far fa-clone fa-lg");

				parent.appendChild(child);

				el.insertBefore(parent, el.childNodes[0]);

				el.childNodes[2].setAttribute('id',"copy-code-" + i + "-content")
				el.childNodes[2].classList.add("copy-code-content")
			}
		})
	});

// Function copy_code_clipboard
	//
	// Description :
	// This function copy the content of your specific selection to clipboard.
	//
	// parameters : one
	// {idcode} correspond to the ID selector you want to select.
	//  - if it's a number : ID selector is 'copy-code-{idcode}-content'
	//  - if it's a string : ID selector is '{idcode}'
	//
	// Return : -
	//
	// Comments :
	// if container is an HTMLTextAreaElement, we use select() function of TextArea element instead of specific SelectElement function
	//
	function copy_code_clipboard(idcode)
	{
		if ( Number.isInteger(idcode) )
			idcode = 'copy-code-' + idcode + '-content';

		var ElementtoCopy = document.getElementById( idcode );

		if (ElementtoCopy instanceof HTMLTextAreaElement)
			ElementtoCopy.select();
		else
			SelectElement(ElementtoCopy);

		try {
			var successful = document.execCommand('copy');
		}
		catch(err) {
			alert('Your browser do not authorize this operation');
		}
	}

//Function SelectElement
	//
	// Description :
	// The content will be selected on your page as if you had selected it with your mouse
	//
	// parameters : one
	// {element} correspond to the element you want to select
	//
	// Return : -
	//
	// Comments : -
	//
	function SelectElement(element) {
		var range = document.createRange();
		range.selectNodeContents(element);

		var selection = window.getSelection();
		selection.removeAllRanges();
		selection.addRange(range);
	}

//Function copy_to_clipboard
	//
	// Description :
	// This function copy the content of parameter to clipboard.
	//
	// parameters : one
	// {tocopy} correspond to the content you want to copy.
	//
	// Return : -
	//
	// Comments : -
	//
	function copy_to_clipboard(tocopy)
	{
		var dummy = jQuery('<input>').val(tocopy).appendTo('body').select()

		try {
			var successful = document.execCommand('copy');
			alert(COPIED_TO_CLIPBOARD +'\n' + tocopy);
		}
		catch(err) {
			alert('Your browser do not authorize this operation');
		}
	}

// Copy link to clipboard
	//
	// Description :
	// This function copy the href of a link to clipboard.
	//
	// use class 'copy-link-to-clipboard' in the link
	//
	document.querySelectorAll('.copy-link-to-clipboard').forEach( el => {
		el.addEventListener('click', event => {
			event.preventDefault();

			var hrefValue = el.getAttribute('href');

			document.addEventListener('copy', function(e) {
				e.clipboardData.setData('text/plain', hrefValue);
				e.preventDefault();
			}, true);

			document.execCommand('copy');
			alert(COPIED_TO_CLIPBOARD +'\n' + hrefValue);
		});
	});

// Open submenu
	// Function open_submenu
	// for links submenu, in HTML onclick attribute
	//
	// Description :
	// This function add CSS Class to the specified CSS ID
	//
	// parameters : three
	// {myid} correspond to the specific element you want to add your CSS class
	// {myclass} correspond to the name of CSS class you want to add to your specific element.
	// {closeother} correspond to the name of CSS class you want to add to your specific element.
	//
	// Return : -
	//
	// Comments : if {myclass} is missing, we use CSS class "opened"
	// Comments : if {closeother} is defined, we close every elements with {closeother} CSS class
	// Comments : The variable elem contain the Element with the unique ID myid
	// Comments : The variable elems contain all Elements with the class closeother
	//
	function open_submenu(myid, myclass, closeother)
	{
		myclass = (typeof myclass !== 'undefined') ? myclass : "opened";
		closeother = (typeof closeother !== 'undefined') ? closeother : false;

		var elem = document.getElementById(myid);

		if (closeother == false)
			elem.classList.toggle(myclass);
		else {
			var elems = document.querySelectorAll('.' + closeother);

			if (elem.classList.contains(myclass)) {
				closeother_submenu(elems, myclass);
			}
			else {
				closeother_submenu(elems, myclass);
				elem.classList.add(myclass);
			}
		}
	}

	// closeother_submenu
	// Function closeother_submenu
	//
	// Description :
	// This function close submenu with a specified CSS Class
	//
	// parameters : three
	// {elems} correspond to the list of specific elements you want to remove your CSS class
	// {myclass} correspond to the name of CSS class you want to remove to each element.
	//
	// Return : -
	//
	//
	function closeother_submenu(elems, myclass)
	{
		[].forEach.call(elems, function(el) {
			el.classList.remove(myclass);
		});
	}

// plugin opensubmenu
	// for content submenu in javascript script
	//
	// Description :
	// This function add CSS Class to the specified CSS ID to open a submenu
	//
	// options : four
	// {osmCloseExcept} correspond to the specific element you doesn't want to close on click.
	// {osmCloseButton} correspond to the specific button for closed submenu.
	// {osmTarget} correspond to the name of CSS class of you element you want to add a specific CSS class.
	// {osmClass} correspond to the name of CSS class you want to add to your specific element.
	//
	// Return : -
	//
	// Comments :
	//   - if {osmClass} is missing, ".opened" CSS class is used
	//   - if {osmCloseButton} is missing, "a.close-button" element is used
	//   - use CSS selector "." or "#" for {osmCloseExcept} and {osmTarget}
	//   - for all children elements, use * in {osmCloseExcept} like '.myClass *'
	//
	(function($) {
		$.fn.opensubmenu = function( options ) {
			var defaults = jQuery(this), params = jQuery.extend({
				osmCloseExcept: '',
				osmCloseButton: 'a.close-button',
				osmTarget: '',
				osmClass: 'opened'
			}, options);

			return this.each(function() {
				jQuery(this).on('click', function(event) {
					event.preventDefault();
					if (jQuery(this).closest(params.osmTarget).hasClass(params.osmClass))
						jQuery(document).find(params.osmTarget).removeClass(params.osmClass);
					else {
						jQuery(document).find(params.osmTarget).removeClass(params.osmClass);
						jQuery(this).closest(params.osmTarget).addClass(params.osmClass);
					}
					event.stopPropagation();
				});
				jQuery(document).on('click',function(event) {
					if ((jQuery(event.target).is(params.osmCloseExcept) === false || jQuery(event.target).is(params.osmCloseButton) === true)) {
						jQuery(document).find(params.osmTarget).removeClass(params.osmClass);
					}
				});
			});
		};
	})(jQuery);

// Multiple checkboxes
	// Function multiple_checkbox_check
	//
	// Description :
	// This function check or uncheck all checkbox with specific id
	//
	// options : three
	// {status} correspond to the status we need (check or uncheck).
	// {elements_number} corresponds to the total number of elements displayed.
	// {except_element} corresponds to an element to ignore.
	//
	// Return : -
	//
	// Comments :
	//
	function multiple_checkbox_check(status, elements_number, except_element, delete_button_control)
	{
		delete_button_control = true
		except_element = (typeof except_element !== 'undefined') ? except_element : 0;
		var i;

		for (i = 1; i <= elements_number; i++)
		{
			if (jQuery('#multiple-checkbox-' + i)[0] && i != except_element)
				jQuery('#multiple-checkbox-' + i)[0].checked = status;
		}

		try {
			jQuery('.check-all')[0].checked = status;
		}
		catch (err) {}

		if (delete_button_control)
			delete_button_display(elements_number);
	}

	//Function delete_button_display
	//
	// Description :
	// This function change the data-confirmation message of the delete all button and its display
	//
	// options : one
	// {elements_number} corresponds to the total number of elements displayed.
	//
	// Return : -
	//
	// Comments :
	//
	function delete_button_display(elements_number)
	{
		var i;
		var checked_elements_number = 0;
		for (i = 1; i <= elements_number; i++)
		{
			if (jQuery('#multiple-checkbox-' + i)[0] && jQuery('#multiple-checkbox-' + i)[0].checked == true)
				checked_elements_number++;
		}

		try {
			if (checked_elements_number > 0) {
				jQuery('#delete-all-button').attr("disabled", false);
				if (checked_elements_number > 1)
					jQuery('#delete-all-button').attr("data-confirmation", "delete-elements");
				else
					jQuery('#delete-all-button').attr("data-confirmation", "delete-element");
			} else {
				jQuery('#delete-all-button').attr("disabled", true);
			}
			if (checked_elements_number < elements_number)
				jQuery('.check-all')[0].checked = false;
			else if (checked_elements_number == elements_number)
				jQuery('.check-all')[0].checked = true;
			update_data_confirmations();
		}
		catch (err) {}
	}

// Progressbar
	function change_progressbar(id_element, value, informations) {
		var progress_bar_el = jQuery('#' + id_element).children('.progressbar').css('width', value + '%');

		if (informations) {
			jQuery('#' + id_element).children('.progressbar-infos').text(informations);
		}
		else {
			jQuery('#' + id_element).children('.progressbar-infos').text(value + '%');
		}
	}

// XMLHttpRequest
	// Ajax preparation function
	function xmlhttprequest_init(filename)
	{
		var xhr_object = null;
		if (window.XMLHttpRequest) // Firefox
			xhr_object = new XMLHttpRequest();
		else if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");

		xhr_object.open('POST', filename, true);

		return xhr_object;
	}

	// Ajax send function
	function xmlhttprequest_sender(xhr_object, data)
	{
		xhr_object.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr_object.send(data);
	}

	// Escape string variables in xmlhttprequest requests
	function escape_xmlhttprequest(contents)
	{
		contents = contents.replace(/\+/g, '%2B');
		contents = contents.replace(/&/g, '%26');

		return contents;
	}

	// Member search function
	function XMLHttpRequest_search_members(searchid, theme, insert_mode, alert_empty_login)
	{
		var login = jQuery('#login' + searchid).val();
		if( login != "" )
		{
			if (jQuery('#search_img' + searchid))
				jQuery('#search_img' + searchid).append('<i class="fa fa-spinner fa-spin"></i>');

			jQuery.ajax({
				url: PATH_TO_ROOT + '/kernel/framework/ajax/member_xmlhttprequest.php?' + insert_mode + '=1',
				type: "post",
				dataType: "html",
				data: {'login': login, 'divid' : searchid, 'token' : TOKEN},
				success: function(returnData){
					if (jQuery('#search_img' + searchid))
						jQuery('#search_img' + searchid).children("i").remove();

					if (jQuery("#xmlhttprequest-result-search" + searchid))
						jQuery("#xmlhttprequest-result-search" + searchid).html(returnData);

					jQuery("#xmlhttprequest-result-search" + searchid).fadeIn();
				},
				error: function(e){
					jQuery('#search_img' + searchid).children("i").remove();
				}
			});
		}
		else
			alert(alert_empty_login);
	}

// Check if a function name exists
	function functionExists(function_name)
	{
		// https://kvz.io/
		// + original by: Kevin van Zonneveld (https://kvz.io/)
		// + improved by: Steve Clay
		// + improved by: Legaev Andrey
		// * example 1: function_exists('isFinite');
		// * returns 1: true
		if (typeof function_name == 'string')
		{
			return (typeof window[function_name] == 'function');
		}
		else
		{
			return (function_name instanceof Function);
		}
	}

// Includes synchronously a js file
	function include(file)
	{
		if (window.document.getElementsByTagName)
		{
			script = window.document.createElement("script");
			script.type = "text/javascript";
			script.src = file;
			document.documentElement.firstChild.appendChild(script);
		}
	}

// Scroll position management (scroll-to-top + cookie-bar)
	function scroll_to( position ) {
		if ( position > 800) {
			jQuery('#scroll-to-top').fadeIn();
		} else {
			jQuery('#scroll-to-top').fadeOut();
		}

		if ( position > 1) {
			jQuery('#cookie-bar-container').addClass('fixed');
		}
		else {
			jQuery('#cookie-bar-container').removeClass('fixed');
		}

		if ( position > 800 || (jQuery(document).height() == jQuery(window.top).height())) {
			jQuery('#scroll-to-bottom').fadeOut();
		} else {
			jQuery('#scroll-to-bottom').fadeIn();
		}
	}

	jQuery(document).ready(function(){
		scroll_to(jQuery(this).scrollTop());

		jQuery(window.top).scroll(function(){
			scroll_to(jQuery(this).scrollTop());
		});

		// Scroll to Top or Bottom
		jQuery('#scroll-to-top').on('click',function(){
			jQuery('html, body').animate({scrollTop : 0},1200);
			return false;
		});
		jQuery('#scroll-to-bottom').on('click',function(){
			jQuery('html, body').animate({scrollTop: jQuery(document).height()-jQuery(window.top).height()},1200);
			return false;
		});
	});

// Cookies, Cookiebar and BBCode management
	// Send cookie to client
	function sendCookie(name, value, delay)
	{
		delay = (typeof delay !== 'undefined') ? delay : 1; // Default validity: 1 month
		var date = new Date();
		date.setMonth(date.getMonth() + delay);
		document.cookie = name + '=' + value + '; Expires=' + date.toGMTString() + '; Path=/';
	}

	// Retrieve cookie value
	function getCookie(name)
	{
		start = document.cookie.indexOf(name + "=");
		if( start >= 0 )
		{
			start += name.length + 1;
			end = document.cookie.indexOf(';', start);

			if( end < 0 )
				end = document.cookie.length;

			return document.cookie.substring(start, end);
		}
		return '';
	}

	// Delete cookie.
	function eraseCookie(name) {
		sendCookie(name,"",-1);
	}

// set cell's height to width (setInterval needed because of the display:hidden)
	setInterval(function() {
		jQuery('.square-cell').each(function(){
			var cell_width = jQuery(this).outerWidth();
			jQuery(this).outerHeight(cell_width + 'px');
		});
	}, 1);

// colorSurround add a colored square to the element and color its borders if it has.
	jQuery.fn.extend ({
		colorSurround: function() {
			return this.each(function(){
				var color = jQuery(this).data('color-surround');
				jQuery(this).css('border-color', color);
				jQuery(this).prepend('<span style="background-color: ' + color + ';" class="data-color-surround"></span>')
			})
		}
	});

// Scroll to anchor on .sticky-menu
	jQuery('.sticky-menu').each(function(){
		jQuery('.sticky-menu .cssmenu-title').on('click',function(){
			var targetId = jQuery(this).attr("href"),
				hash = targetId.substring(targetId.indexOf('#'));
			if(hash != null || hash != targetId) {
				if (parseInt(jQuery(window).width()) < 769)
					menuOffset = jQuery('.sticky-menu > .cssmenu > ul > li > .cssmenu-title').innerHeight();
				else
					menuOffset = jQuery('.sticky-menu > .cssmenu').innerHeight();
				history.pushState('', '', hash);
				jQuery('html, body').animate({scrollTop:jQuery(hash).offset().top - menuOffset}, 'slow');
			}
		});
		// remove offload class if # is in current page
        var path = window.location.pathname,
            pathSplit = path.split('/');
        pathSplit = pathSplit[pathSplit.length-1];
        jQuery('#component-submenu .has-sub ul .cssmenu-title.offload').each(function() {
            var href = jQuery(this).attr('href');
            if ( href.indexOf(pathSplit) != -1 )
                jQuery(this).removeClass("offload");
        });

	});

// Recognise an anchor link then scroll to
	jQuery('a[href*="/scrollto#"]').each(function() {
		if(jQuery(this).hasClass('offload'))
			jQuery(this).removeClass('offload');
		var getLink = jQuery(this).attr('href'),
			anchorSplit = getLink.split('#'),
			realAnchor = '#' + anchorSplit[1];
		jQuery(this).on('click', function(e) {
			e.preventDefault();
			history.pushState('', '', realAnchor);
			jQuery('html, body').animate({scrollTop:jQuery(realAnchor).offset().top}, 'slow');
		})
	});
