    /**
     * Finds the position of the first occurrence of a substring in a string.
     * @param {string} haystack - The string to search within.
     * @param {string} needle - The substring to search for.
     * @returns {number|boolean} The position of the first occurrence of the substring, or false if not found.
     */
	function strpos(haystack, needle)
	{
		var i = haystack.indexOf(needle, 0); // returns -1
		return i >= 0 ? i : false;
	}

	/**
     * Adds an icon and functionality to hide/show BBCode blocks once the DOM is fully loaded.
     * @param {function} callback - The function to execute once the DOM is fully loaded.
     */
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

    /**
     * Toggles the visibility of an element with the specified ID.
     * @param {string|number} idcode - The ID of the element to toggle. Defaults to 0 if undefined.
     * @param {number} show - Flag indicating whether to show the element. Defaults to 0 if undefined.
     * @param {Event} event - The event object.
     */
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

    /**
     * Adds a copy-to-clipboard button to code elements once the DOM is fully loaded.
     * @param {function} callback - The function to execute once the DOM is fully loaded.
     */
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
				parent.setAttribute('aria-label', L_COPY_TO_CLIPBOARD);
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

    /**
     * Copies the content of a specified element to the clipboard.
     * if container is an HTMLTextAreaElement, we use select() function of TextArea element instead of specific SelectElement function
     * @param {string|number} idcode - The ID of the element to copy. 
     *  - If a number, ID selector is converted to a string with the format 'copy-code-{idcode}-content'.
     *  - If a string, ID selector is '{idcode}'
     */
	function copy_code_clipboard(idcode)
	{
		if ( Number.isInteger(idcode) )
			idcode = 'copy-code-' + idcode + '-content';

		var ElementtoCopy = document.getElementById( idcode );

		if (ElementtoCopy instanceof HTMLTextAreaElement)
			ElementtoCopy.select();
		else
			SelectElement(ElementtoCopy);

        navigator.clipboard.writeText(ElementtoCopy.value || ElementtoCopy.innerText).then(function() {
            console.log('Text copied to clipboard');
        }).catch(function(err) {
            console.error('Error in copying text: ', err);
        });
	}

    /**
     * Selects the contents of the specified element.
     * @param {HTMLElement} element - The HTML element whose contents will be selected.
     */
	function SelectElement(element)
    {
		var range = document.createRange();
		range.selectNodeContents(element);
		var selection = window.getSelection();
		selection.removeAllRanges();
		selection.addRange(range);
	}

    /**
     * Copies the content of parameter to the clipboard and displays an ephemeral message.
     * @param {string} tocopy - The content to be copied to the clipboard.
     */
    function copy_to_clipboard(tocopy)
    {
        navigator.clipboard.writeText(tocopy).then(function() {
            showEphemeralMessage(tocopy);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
        });
    }

    /**
     * Displays an ephemeral message with the copied value.
     * @param {string} value - The value to be displayed in the ephemeral message.
     */
    function showEphemeralMessage(value) {
        var messageDiv = document.createElement('div');
        messageDiv.classList.add('ephemeral', 'bgc-full', 'success');
        messageDiv.innerHTML = L_COPIED_TO_CLIPBOARD + ' : <br />' + value;

        document.body.appendChild(messageDiv);

        setTimeout(function() {
            messageDiv.style.right = '0.618em';
            messageDiv.style.opacity = '1';
        }, 10);

        setTimeout(function() {
            messageDiv.style.opacity = '0';
            setTimeout(function() {
                document.body.removeChild(messageDiv);
            }, 500);
        }, 3000);
    }

	/**
     * Opens a submenu with the specified ID and toggles its class.
     * @param {string} myid - The ID of the submenu element to open.
     * @param {string} [myclass="opened"] - The class to toggle on the submenu element. Defaults to "opened".
     * @param {boolean|string} [closeother=false]
     *  - If true, closes other submenus with the specified class.
     *  - If a string, used as a selector to close other submenus.
     */
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

    /**
     * Closes other submenus by removing the specified class.
     * @param {NodeList} elems - The list of elements to close.
     * @param {string} myclass - The class to remove from the elements.
     */
	function closeother_submenu(elems, myclass)
	{
		[].forEach.call(elems, function(el) {
			el.classList.remove(myclass);
		});
	}

    /**
     * Opens a submenu based on the provided selector and options.     *
     * @param {string} selector - The CSS selector for the elements that trigger the submenu.
     * @param {Object} options - The options for configuring the submenu behavior.
     * @param {string} [options.osmCloseExcept] - Selector for elements that should not close the submenu.
     * @param {string} [options.osmCloseButton] - Selector for the close button within the submenu.
     * @param {string} [options.osmTarget] - Selector for the submenu target elements.
     * @param {string} [options.osmClass] - The CSS class to add/remove for opening/closing the submenu.
     * 
     *  - use CSS selector "." or "#" for {osmCloseExcept} and {osmTarget}
     *  - for all children elements, use * in {osmCloseExcept} like '.myClass *'
     */
    function opensubmenu(selector, options) {
        var params = {
            osmCloseExcept: '',
            osmCloseButton: 'a.close-button',
            osmTarget: '',
            osmClass: 'opened',
            ...options
        };

        document.querySelectorAll(selector).forEach(function (element) {
            element.addEventListener('click', function (event) {
                event.preventDefault();
                var target = element.closest(params.osmTarget);
                if (target.classList.contains(params.osmClass)) {
                    document.querySelectorAll(params.osmTarget).forEach(function (el) {
                        el.classList.remove(params.osmClass);
                    });
                } else {
                    document.querySelectorAll(params.osmTarget).forEach(function (el) {
                        el.classList.remove(params.osmClass);
                    });
                    target.classList.add(params.osmClass);
                }
                event.stopPropagation();
            });
        });

        document.addEventListener('click', function (event) {
            if ((!event.target.matches(params.osmCloseExcept) || event.target.matches(params.osmCloseButton))) {
                document.querySelectorAll(params.osmTarget).forEach(function (el) {
                    el.classList.remove(params.osmClass);
                });
            }
        });
    }
    // Usage example:
    // opensubmenu('.your-selector', { osmTarget: '.your-target', osmClass: 'your-class' });

    /**
     * Checks or unchecks multiple checkboxes based on the given status.
     * @param {boolean} status - The status to set for the checkboxes (true for checked, false for unchecked).
     * @param {number} elements_number - The total number of checkbox elements.
     * @param {number} [except_element=0] - The index of the checkbox to exclude from the operation.
     * @param {boolean} delete_button_control - Whether to control the display of the delete button.
     */
	function multiple_checkbox_check(status, elements_number, except_element, delete_button_control)
	{
		delete_button_control = true;
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

	/**
     * Changes the data-confirmation message of the "delete all" button and its display
     * @param {number} elements_number - The total number of elements displayed
     */
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

    /**
     * @author Kevin van Zonneveld
     * @link https://kvz.io/
     * @contributor Steve Clay
     * @contributor Legaev Andrey
     * @param {string} function_name 
     * @returns 
     */
	function functionExists(function_name)
	{
		if (typeof function_name == 'string') {
			return (typeof window[function_name] == 'function');
		} else {
			return (function_name instanceof Function);
		}
	}

    /**
     * Includes synchronously a js file
     * @param {string} file - The path to the file location
     */
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

    /**
     * Sets cell's height to width (setInterval is needed because of the display:hidden)
     */
	setInterval(() => {
        const squareCells = document.querySelectorAll('.square-cell');
        squareCells.forEach(cell => {
            const cellWidth = cell.offsetWidth;
            cell.style.height = cellWidth + 'px';
        });
    }, 1);

    /**
     * Applies a color surround effect to elements matching the provided selector.
     * Sets border color and prepends a colored span element based on the
     * `data-color-surround` dataset value of each element.
     * 
     * @param {string} selector - CSS selector string to target elements. Can be multiple comma-separated selectors (e.g. ".class1, .class2")
     */
    const colorSurround = (selector) => {
        document.querySelectorAll(selector).forEach(element => {
            const color = element.dataset.colorSurround;
            if (color) {
                element.style.borderColor = color;
                const span = document.createElement('span');
                span.style.backgroundColor = color;
                span.classList.add('data-color-surround');
                element.prepend(span);
            }
        });
    };

// Scroll to anchor on .sticky-menu
    jQuery('.sticky-menu').each( function() {
        jQuery('.sticky-menu .cssmenu-title').on('click', function() {
            if (jQuery('#menu-button-navigation').css('display') === 'block')
            {
                jQuery('#menu-button-navigation').removeClass('menu-opened');
                jQuery('.sticky-menu ul').each( function() { jQuery(this).removeClass('open').addClass('close') });
            }
            var targetId = jQuery(this).attr("href"),
                hash = targetId.substring(targetId.indexOf('#'));
            if (hash != null || hash != targetId)
            {
                if (parseInt(jQuery(window).width()) < 769)
                    menuOffset = jQuery('.sticky-menu > .cssmenu > ul > li > .cssmenu-title').innerHeight();
                else
                    menuOffset = jQuery('.sticky-menu > .cssmenu').innerHeight();
                history.pushState('', '', hash);
                jQuery('html, body').animate({scrollTop:jQuery(hash).offset().top - menuOffset}, 'slow');
            }
        });

        var path = window.location.pathname,
            pathSplit = path.split('/');
        pathSplit = pathSplit[pathSplit.length-1];
        jQuery(this).find('.has-sub ul .cssmenu-title.offload').each(function() {
            var href = jQuery(this).attr('href');
            if ( href.indexOf(pathSplit) != -1 )
                jQuery(this).removeClass("offload");
        });
    });
    jQuery(document).on('click', function(event) {
        if (!jQuery(event.target).closest('.sticky-menu').length) {
            history.pushState('', '', ' ');
        }
    });


    /**
     * Recognise an anchor link then scroll to
     */
    document.querySelectorAll('a[href*="/scrollto#"]').forEach(anchor => {
        if (anchor.classList.contains('offload')) {
            anchor.classList.remove('offload');
        }

        const getLink = anchor.getAttribute('href');
        const [, realAnchor] = getLink.split('#');

        anchor.addEventListener('click', event => {
            event.preventDefault();
            history.pushState('', '', '#' + realAnchor);

            const targetElement = document.getElementById(realAnchor);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });


    /**
     * AJAX to upload one file from a single button
     * @param {HTMLElement} thisElement - The element that triggered the upload (typically a button)
     * @param {string} targetId - ID of the input element to receive the uploaded file URL
     * @param {string} token - Authentication token to include in the upload request
     */
    function direct_upload(thisElement, targetId, token) 
    {
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.className = 'hidden';
        fileInput.name = 'upload_file[]';
        thisElement.parentNode.appendChild(fileInput);

        fileInput.click();

        const targetInput = document.getElementById(targetId);

        fileInput.addEventListener('change', function () {
            targetInput.classList.remove('warning');
            const file = fileInput.files[0];
            const formData = new FormData();
            formData.append('upload_file[]', file);
            formData.append('token', token);

            // Use AJAX to upload the file
            const xhr = new XMLHttpRequest();
            xhr.open('POST', PATH_TO_ROOT + '/user/ajax_upload.php', true);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    targetInput.value = response.url;
                    if (response.class) {
                        targetInput.classList.add(response.class);
                    }
                    targetInput.focus();
                } else {
                    targetInput.value = xhr.responseText;
                    if (xhr.class) {
                        targetInput.classList.add(xhr.class);
                    }
                    targetInput.focus();
                }
            };
            xhr.onerror = function () {
                console.error('Error occurred during the XMLHttpRequest');
            };
            xhr.send(formData);
        });
    }

// Hide/reveal password in password inputs
    document.addEventListener('DOMContentLoaded', () => {
        const passwordContainers = document.querySelectorAll('.password-container');

        passwordContainers.forEach(container => {
            const input = container.querySelector('input[type="password"]');
            const toggleButton = container.querySelector('.toggle-password');

            toggleButton.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                toggleButton.innerHTML = type === 'password' ? '<i class="fa fa-eye" aria-hidden="true"></i>' : '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
                toggleButton.setAttribute('aria-label', type === 'password' ? REVEAL_PASSWORD : HIDE_PASSWORD);
            });
        });
    });
