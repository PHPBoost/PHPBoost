<script>
    <!--
        function check_form()
        {
            if(document.getElementById('menu_element_{ID}_name').value == "") {
                alert("{@warning.title}");
                return false;
            }
            return true;
        }

        var idMax = {ID_MAX};

        /**
         * Initialise SortableJS on a single <ul class="sortable-block">.
         * All lists share the same group name so drag-and-drop works across
         * every nesting level without any re-initialisation tricks.
         *
         * @param {HTMLElement} ulEl  The <ul> DOM element to make sortable.
         */
        function makeSortable(ulEl)
        {
            if (!ulEl || ulEl._sortableInstance) return;

            ulEl._sortableInstance = Sortable.create(ulEl, {
                group:     'menu-tree',
                handle:    '.sortable-selector',
                animation: 150,
                ghostClass:  'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass:   'dragged',
                onStart: function(evt) {
                    document.body.classList.add('dragging');
                },
                onEnd: function(evt) {
                    document.body.classList.remove('dragging');
                }
            });
        }

        /**
         * Initialise SortableJS on every <ul class="sortable-block"> in the page.
         * Safe to call multiple times — already-initialised lists are skipped.
         */
        function initSortableMenu()
        {
            document.querySelectorAll('ul.sortable-block').forEach(makeSortable);
        }

        /**
         * Recursively serialize the menu tree to match the exact JSON structure
         * that links.php expects from the original jquery-sortable output.
         *
         * jquery-sortable used jQuery.data() on each <li data-id="4"> which returns
         * { id: "4" } — just the numeric id, no "menu_element_" prefix.
         * The PHP then does: 'menu_element_' . $t->id to build the field name.
         *
         * children structure: [ [ child1, child2, ... ] ]
         * (outer array + inner array of siblings — artefact of jquery-sortable double wrapping)
         * The PHP accesses: $element->children[0]  →  the inner array of siblings.
         *
         * Root level: flat array of items — PHP does foreach($links_list as $pos => $tree)
         *
         * @param  {HTMLElement} ulEl  A <ul class="sortable-block">
         * @returns {Array}  Flat array of item objects
         */
        function serializeList(ulEl)
        {
            const items = [];
            ulEl.querySelectorAll(':scope > li.sortable-element').forEach(function(li) {
                const dataId = li.getAttribute('data-id');
                const entry  = { id: dataId };  // raw numeric id, PHP prepends 'menu_element_'
                const nested = li.querySelector(':scope > ul.sortable-block');
                if (nested && nested.querySelectorAll(':scope > li.sortable-element').length) {
                    // Wrap in outer array: children[0] = the sibling array
                    entry.children = [ serializeList(nested) ];
                }
                items.push(entry);
            });
            return items;
        }

        function get_sortable_sequence()
        {
            const rootUl = document.getElementById('menu_element_{ID}_list');
            return serializeList(rootUl);
        }

        function build_menu_elements_tree()
        {
            jQuery('#menu_tree').val(JSON.stringify(get_sortable_sequence()));
        }

        function toggleProperties(id)
        {
            if (jQuery("#menu_element_" + id + "_properties").is(':hidden'))
            {   //Si les propriétés sont repliées, on les affiche
                jQuery("#menu_element_" + id + "_properties").fadeIn();
                jQuery("#menu_element_" + id + "_more_image").html('<i class="fa fa-minus"></i>');
            }
            else
            {   //Sinon, on les cache
                jQuery("#menu_element_" + id + "_properties").fadeOut();
                jQuery("#menu_element_" + id + "_more_image").html('<i class="fa fa-cog"></i>');
            }
        }

        var authForm = {J_AUTH_FORM}; 

        function getAuthForm(id)
        {
            return authForm.replace(/##UID##/g, id);
        }

        function addSubElement(menu_element_id)
        {
            var id = idMax++;

            jQuery('<li/>', {id : 'menu_element_' + id, 'data-id' : id, class : 'sortable-element'}).appendTo('#' + menu_element_id + '_list');
            jQuery('<div/>', {class : 'sortable-selector', 'aria-label' : ${escapejs(@common.move)}}).appendTo('#menu_element_' + id);
            jQuery('<div/>', {id : 'menu_title_' + id, class : 'sortable-title', 'aria-label' : ${escapejs(@menu.item)}}).appendTo('#menu_element_' + id);

            jQuery('<div/>', {id : 'menu_inputs_' + id, class : 'grouped-inputs inputs-with-sup large-inputs-group'}).appendTo('#menu_title_' + id);

            jQuery('<span/>', {class : 'grouped-element bgc-full link-color'}).appendTo('#menu_inputs_' + id);
            jQuery('<i/>', {class : 'fa fa-globe', 'aria-hidden' : 'true'}).appendTo('#menu_inputs_' + id + ' span');

            jQuery('<label/>', {id : 'menu_label_name_' + id, for : 'menu_element_' + id + '_name', class : 'label-sup grouped-element'}).appendTo('#menu_inputs_' + id);
            jQuery('<span/>').text(${escapejs(@common.name)}).appendTo('#menu_label_name_' + id);
            jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_name', name : 'menu_element_' + id + '_name', placeholder : ${escapejs(@menu.add.item)}}).appendTo('#menu_label_name_' + id);

            jQuery('<label/>', {id : 'menu_label_url_' + id, for : 'menu_element_' + id + '_url', class : 'label-sup grouped-element'}).appendTo('#menu_inputs_' + id);
            jQuery('<span/>').text(${escapejs(@common.url)}).appendTo('#menu_label_url_' + id);
            jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_url', name : 'menu_element_' + id + '_url'}).appendTo('#menu_label_url_' + id);

            jQuery('<label/>', {id : 'menu_label_target_' + id, for : 'menu_element_' + id + '_target', class : 'label-sup label-mini grouped-element', 'aria-label' : ${escapejs(@common.open.new.window)}}).appendTo('#menu_inputs_' + id);
            jQuery('<span/>').html('<i class="far fa-share-from-square"></i>').appendTo('#menu_label_target_' + id);
            jQuery('<input/>', {type : 'checkbox', id : 'menu_element_' + id + '_target', name : 'menu_element_' + id + '_target', class : 'checkbox-mini'}).appendTo('#menu_label_target_' + id);

            jQuery('<label/>', {id : 'menu_label_image_' + id, for : 'menu_element_' + id + '_image', class : 'label-sup grouped-element'}).appendTo('#menu_inputs_' + id);
            jQuery('<span/>').text(${escapejs(@common.image)}).appendTo('#menu_label_image_' + id);
            jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_image', name : 'menu_element_' + id + '_image', onblur: "image_preview(this,menu_element_" + id + "_image_preview)"}).appendTo('#menu_label_image_' + id);

            jQuery('<label/>', {id : 'menu_label_icon_' + id, for : 'menu_element_' + id + '_icon', class : 'label-sup grouped-element'}).appendTo('#menu_inputs_' + id);
            jQuery('<span/>').text(${escapejs(@common.icon)}).appendTo('#menu_label_icon_' + id);
            jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_icon', name : 'menu_element_' + id + '_icon'}).appendTo('#menu_label_icon_' + id);

            jQuery('<span/>', {id : 'menu_element_' + id + '_image_preview_span', class : 'preview grouped-element'}).appendTo('#menu_inputs_' + id);
            jQuery('<img/>', {id : 'menu_element_' + id + '_image_preview', style : 'display:none;'}).appendTo('#menu_element_' + id + '_image_preview_span');

            jQuery('<div/>', {id : 'menu_element_' + id + '_actions', class : 'sortable-actions'}).appendTo('#menu_element_' + id);
            jQuery('<a/>', {id : 'menu_element_' + id + '_more_image', 'aria-label' : ${escapejs(@form.authorizations.management)}, onclick: 'toggleProperties(' + id + ');return false;'}).html('<i class="fa fa-cog" aria-hidden="true"></i>').appendTo('#menu_element_' + id + '_actions');
            jQuery('#menu_element_' + id + '_actions').append(' ');
            jQuery('<a/>', {id : 'menu_element_' + id + '_delete_image', 'aria-label' : ${escapejs(@common.delete)}, onclick: 'deleteElement(\'menu_element_' + id + '\');return false;'}).html('<i class="far fa-trash-alt" aria-hidden="true"></i>').appendTo('#menu_element_' + id + '_actions');

            jQuery('<div/>', {class : 'spacer'}).appendTo('#menu_element_' + id);

            jQuery('<fieldset/>', {id : 'menu_element_' + id + '_properties', style : 'display:none;'}).appendTo('#menu_element_' + id);
            jQuery('<legend/>').text(${escapejs(@form.authorizations)}).appendTo('#menu_element_' + id + '_properties');
            jQuery('<div/>', {id : 'menu_element_' + id + '_authorizations', class : 'form-element full-field'}).appendTo('#menu_element_' + id + '_properties');
            jQuery('<label/>', {for : 'menu_element_' + id + '_auth_div'}).text(${escapejs(@form.authorizations.read)} + ' ').appendTo('#menu_element_' + id + '_authorizations');
            jQuery('<div/> ', {class : 'form-field'}).html(getAuthForm(id)).appendTo('#menu_element_' + id + '_authorizations');

            // Initialise SortableJS on the parent list so the new element is draggable immediately.
            // makeSortable() is a no-op if the list was already initialised.
            makeSortable(document.getElementById(menu_element_id + '_list'));
        }

        function addSubMenu(menu_element_id)
        {
            var id = idMax++;

            jQuery('<li/>', {id : 'menu_element_' + id, 'data-id' : id, class : 'sortable-element'}).appendTo('#' + menu_element_id + '_list');
            jQuery('<div/>', {class : 'sortable-selector', 'aria-label' : ${escapejs(@common.move)}}).appendTo('#menu_element_' + id);
            jQuery('<div/>', {id : 'menu_title_' + id, class : 'sortable-title', 'aria-label' : ${escapejs(@menu.sub.menu)}}).appendTo('#menu_element_' + id);

            jQuery('<div/>', {id : 'menu_inputs_' + id, class : 'grouped-inputs inputs-with-sup large-inputs-group'}).appendTo('#menu_title_' + id);

            jQuery('<span/>', {class : 'grouped-element bgc-full notice submenu-folder-trigger', style : 'cursor:pointer;', title : ${escapejs(@menu.sub.menu)}, onclick: 'toggleSubMenuAccordion(\'menu_element_' + id + '\');return false;'}).html('<i class="fa fa-folder-open" aria-hidden="true"></i>').appendTo('#menu_inputs_' + id);

            jQuery('<label/>', {id : 'menu_label_name_' + id, for : 'menu_element_' + id + '_name', class : 'label-sup grouped-element'}).appendTo('#menu_inputs_' + id);
            jQuery('<span/>').text(${escapejs(@common.name)}).appendTo('#menu_label_name_' + id);
            jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_name', name : 'menu_element_' + id + '_name', placeholder : ${escapejs(@menu.add.sub.menu)}}).appendTo('#menu_label_name_' + id);

            jQuery('<label/>', {id : 'menu_label_url_' + id, for : 'menu_element_' + id + '_url', class : 'label-sup grouped-element'}).appendTo('#menu_inputs_' + id);
            jQuery('<span/>').text(${escapejs(@common.url)}).appendTo('#menu_label_url_' + id);
            jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_url', name : 'menu_element_' + id + '_url'}).appendTo('#menu_label_url_' + id);

            jQuery('<label/>', {id : 'menu_label_target_' + id, for : 'menu_element_' + id + '_target', class : 'label-sup label-mini grouped-element', 'aria-label' : ${escapejs(@common.open.new.window)}}).appendTo('#menu_inputs_' + id);
            jQuery('<span/>').html('<i class="far fa-share-from-square"></i>').appendTo('#menu_label_target_' + id);
            jQuery('<input/>', {type : 'checkbox', id : 'menu_element_' + id + '_target', name : 'menu_element_' + id + '_target', class : 'checkbox-mini'}).appendTo('#menu_label_target_' + id);

            jQuery('<label/>', {id : 'menu_label_image_' + id, for : 'menu_element_' + id + '_image', class : 'label-sup grouped-element'}).appendTo('#menu_inputs_' + id);
            jQuery('<span/>').text(${escapejs(@common.image)}).appendTo('#menu_label_image_' + id);
            jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_image', name : 'menu_element_' + id + '_image', onblur: "image_preview(this,menu_element_" + id + "_image_preview)"}).appendTo('#menu_label_image_' + id);

            jQuery('<label/>', {id : 'menu_label_icon_' + id, for : 'menu_element_' + id + '_icon', class : 'label-sup grouped-element'}).appendTo('#menu_inputs_' + id);
            jQuery('<span/>').text(${escapejs(@common.icon)}).appendTo('#menu_label_icon_' + id);
            jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_icon', name : 'menu_element_' + id + '_icon'}).appendTo('#menu_label_icon_' + id);

            jQuery('<span/>', {id : 'menu_element_' + id + '_image_preview_span', class : 'preview grouped-element'}).appendTo('#menu_inputs_' + id);
            jQuery('<img/>', {id : 'menu_element_' + id + '_image_preview', style : 'display:none;'}).appendTo('#menu_element_' + id + '_image_preview_span');

            jQuery('<div/>', {id : 'menu_element_' + id + '_actions', class : 'sortable-actions'}).appendTo('#menu_element_' + id);
            jQuery('<a/>', {id : 'menu_element_' + id + '_more_image', 'aria-label' : ${escapejs(@form.authorizations.management)}, onclick: 'toggleProperties(' + id + ');return false;'}).html('<i class="fa fa-cog" aria-hidden="true"></i>').appendTo('#menu_element_' + id + '_actions');
            jQuery('#menu_element_' + id + '_actions').append(' ');
            jQuery('<a/>', {id : 'menu_element_' + id + '_delete_image', 'aria-label' : ${escapejs(@common.delete)}, onclick: 'deleteElement(\'menu_element_' + id + '\');return false;'}).html('<i class="far fa-trash-alt" aria-hidden="true"></i>').appendTo('#menu_element_' + id + '_actions');

            jQuery('<div/>', {class : 'spacer'}).appendTo('#menu_element_' + id);

            jQuery('<fieldset/>', {id : 'menu_element_' + id + '_properties', style : 'display:none;'}).appendTo('#menu_element_' + id);
            jQuery('<legend/>').text(${escapejs(@form.authorizations)}).appendTo('#menu_element_' + id + '_properties');
            jQuery('<div/>', {id : 'menu_element_' + id + '_authorizations', class : 'form-element full-field'}).appendTo('#menu_element_' + id + '_properties');
            jQuery('<label/>', {for : 'menu_element_' + id + '_auth_div'}).text(${escapejs(@form.authorizations.read)} + ' ').appendTo('#menu_element_' + id + '_authorizations');
            jQuery('<div/> ', {class : 'form-field'}).html(getAuthForm(id)).appendTo('#menu_element_' + id + '_authorizations');

            // Collapsible items — direct children of the <li>, NOT wrapping the <ul>
            // so sortable.js keeps full access to the sortable-block for drag-and-drop
            jQuery('<hr/>', {class : 'submenu-collapsible'}).appendTo('#menu_element_' + id);
            jQuery('<ul/>', {id : 'menu_element_' + id + '_list', class : 'sortable-block'}).appendTo('#menu_element_' + id);

            jQuery('<fieldset/>', {id : 'menu_element_' + id + '_buttons', class : 'fieldset-submit submenu-collapsible'}).appendTo('#menu_element_' + id);
            jQuery('<button/>', {type : 'button', class : 'button', id : 'menu_element_' + id + '_add_sub_element', name : 'menu_element_' + id + '_add_sub_element', value : ${escapejs(@menu.add.item)}, onclick : 'addSubElement(\'menu_element_' + id + '\');'}).text(${escapejs(@menu.add.item)}).appendTo('#menu_element_' + id + '_buttons');
            jQuery('#menu_element_' + id + '_buttons').append(' ');

            jQuery('<button/>', {type : 'button', class : 'button', id : 'menu_element_' + id + '_add_sub_menu', name : 'menu_element_' + id + '_add_sub_menu', value : ${escapejs(@menu.add.sub.menu)}, onclick : 'addSubMenu(\'menu_element_' + id + '\');'}).text(${escapejs(@menu.add.sub.menu)}).appendTo('#menu_element_' + id + '_buttons');

            // Mark as open on creation (folder-open icon, collapsibles visible)
            jQuery('#menu_element_' + id).addClass('submenu-accordion-open');
            jQuery('#menu_element_' + id).find('> .submenu-collapsible').show();

            // Initialise SortableJS on the new nested <ul> so it joins the shared group
            makeSortable(document.getElementById('menu_element_' + id + '_list'));
            // Also ensure the parent list is initialised (no-op if already done)
            makeSortable(document.getElementById(menu_element_id + '_list'));

            addSubElement('menu_element_' + id);
        }

        function deleteElement(element_id)
        {
            if (confirm(${escapejs(@warning.confirm.delete)}))
            {
                jQuery('#' + element_id).remove();
                // SortableJS updates automatically — no re-initialisation needed
            }
        }

        /**
         * Toggle accordion state for a sub-menu sortable-element.
         * Collapses/expands: the <hr class="submenu-collapsible"> and
         * <fieldset class="submenu-collapsible"> that are direct children of the <li>.
         * The <ul class="sortable-block"> is intentionally left untouched so
         * sortable.js can always measure its dimensions and allow drag-and-drop.
         * The trigger icon switches between fa-folder-open (open) and fa-folder (closed).
         *
         * @param {string} elementId - id of the sortable-element li (e.g. 'menu_element_42')
         */
        function toggleSubMenuAccordion(elementId)
        {
            const $li       = jQuery('#' + elementId);
            const $targets  = $li.find('> .submenu-collapsible');
            const $icon     = $li.find('> .sortable-title .submenu-folder-trigger i');
            const isOpen    = $li.hasClass('submenu-accordion-open');

            if (isOpen) {
                $targets.slideUp(250);
                $li.removeClass('submenu-accordion-open');
                $icon.removeClass('fa-folder-open').addClass('fa-folder');
            } else {
                $targets.slideDown(250);
                $li.addClass('submenu-accordion-open');
                $icon.removeClass('fa-folder').addClass('fa-folder-open');
            }
        }

        /**
         * Initialise accordion on all existing sub-menu sortable-elements at page load.
         * All start open (fa-folder-open). No animation on init.
         */
        function initSubMenuAccordions()
        {
            jQuery('.menu-elements-container .sortable-element').each(function() {
                const $li      = jQuery(this);
                const $targets = $li.find('> .submenu-collapsible');

                if ($targets.length) {
                    $li.addClass('submenu-accordion-open');
                    $targets.show();
                    $li.find('> .sortable-title .submenu-folder-trigger i')
                        .removeClass('fa-folder')
                        .addClass('fa-folder-open');
                }
            });
        }

        function image_preview(input,image,is_value = false)
        {
            if (is_value == true)
                var url = input;
            else
                var url = input.value;

            if (url != '') {
                jQuery.ajax({
                    url: PATH_TO_ROOT + "/kernel/framework/ajax/dispatcher.php?url=/url_validation/",
                    type: "post",
                    dataType: "json",
                    async: false,
                    data: {url_to_check : url, token : TOKEN},
                    success: function(returnData) {
                        if (returnData.is_valid == 1) {
                            if (url.charAt(0) == '/')
                                image.src = PATH_TO_ROOT + url;
                            else
                                image.src = url;

                            jQuery('#' + image.id).show();
                        }
                        else {
                            image.src = '';
                            jQuery('#' + image.id).hide();
                        }
                    },
                    error: function(returnData) {
                        image.src = '';
                        jQuery('#' + image.id).hide();
                    }
                });
            } else {
                image.src = '';
                jQuery('#' + image.id).hide();
            }
        }

        jQuery(document).ready(function()
        {
            initSortableMenu();
            initSubMenuAccordions();

            jQuery(".push-options").hide();
            if(jQuery('.menu-type select').children('option:selected').val() == 'push')
            {
                jQuery(".push-options").show();
            };
            jQuery('.menu-type select').change(function()
            {
                var selectedType = $(this).children('option:selected').val();
                if(selectedType == 'push')
                    jQuery(".push-options").fadeIn();
                else
                    jQuery(".push-options").fadeOut();
            });
        });
    -->
</script>
<div id="admin-contents">
	<form action="links.php?action=save" method="post" class="fieldset-content" onsubmit="build_menu_elements_tree();return check_form();">
		<p class="align-center small text-italic">${escapejs(@form.required.fields)}</p>
		<fieldset>
			<legend># IF C_EDIT #{@menu.links.edit}# ELSE #{@menu.links.add}# ENDIF #</legend>
			<div class="fieldset-inset">
                <div class="form-element">
					<label for="menu_element_{ID}_name">* {@common.name}</label>
					<div class="form-field"><input type="text" name="menu_element_{ID}_name" id="menu_element_{ID}_name" value="{MENU_NAME}"></div>
				</div>
				<div class="form-element">
					<label for="menu_element_{ID}_url">{@common.url}</label>
					<div class="form-field"><input type="text" name="menu_element_{ID}_url" id="menu_element_{ID}_url" value="{MENU_URL}"></div>
				</div>
				<div class="form-element">
					<label for="menu_element_{ID}_image">{@common.image}</label>
					<div class="form-field"><input type="text" name="menu_element_{ID}_image" id="menu_element_{ID}_image" value="{MENU_IMG}"></div>
				</div>
				<div class="form-element">
					<label for="menu_element_{ID}_icon">{@common.icon}</label>
					<div class="form-field"><input type="text" name="menu_element_{ID}_icon" id="menu_element_{ID}_icon" value="{MENU_ICON}"></div>
				</div>
				<div class="form-element third-field">
					<label for="menu_element_{ID}_location">{@menu.location}</label>
					<div class="form-field">
						<select name="menu_element_{ID}_location" id="menu_element_{ID}_location">
							# START location #
								<option value="{location.VALUE}" # IF location.C_SELECTED # selected="selected"# ENDIF #>
									{location.NAME}
								</option>
							# END location #
						</select>
					</div>
				</div>
				<div class="form-element third-field">
					<label for="menu_element_{ID}_enabled">{@common.status}</label>
					<div class="form-field">
						<select name="menu_element_{ID}_enabled" id="menu_element_{ID}_enabled">
							<option value="1"# IF C_ENABLED # selected="selected"# ENDIF #>{@common.enable}</option>
							<option value="0"# IF NOT C_ENABLED # selected="selected"# ENDIF #>{@common.disable}</option>
						</select>
					</div>
				</div>
				<div class="form-element third-field menu-type">
					<label for="menu_element_{ID}_type">
						{@common.type}
						<span class="field-description">{@H|menu.automatic.clue}</span>
					</label>
					<div class="form-field">
						<select name="menu_element_{ID}_type" id="menu_element_{ID}_type">
							# START type #
								<option value="{type.NAME}"{type.SELECTED}>{type.L_NAME}</option>
							# END type #
						</select>
					</div>
				</div>
				<div class="form-element top-field custom-checkbox push-options">
					<label for="menu_element_{ID}_disabled_body">{@menu.push.disable.body}</label>
					<div class="form-field">
						<div class="form-field-checkbox">
							<label class="checkbox" for="menu_element_{ID}_disabled_body">
								<input type="checkbox" name="menu_element_{ID}_disabled_body" id="menu_element_{ID}_disabled_body"# IF C_PUSHMENU_DISABLED_BODY # checked="checked"# ENDIF #>
								<span>&nbsp;</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element top-field custom-checkbox push-options">
					<label for="menu_element_{ID}_pushed_content">{@menu.push.push.content}</label>
					<div class="form-field">
						<div class="form-field-checkbox">
							<label class="checkbox" for="menu_element_{ID}_pushed_content">
								<input type="checkbox" name="menu_element_{ID}_pushed_content" id="menu_element_{ID}_pushed_content"# IF C_PUSHMENU_PUSHED_CONTENT # checked="checked"# ENDIF #>
								<span>&nbsp;</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element push-options">
					<label for="menu_element_{ID}_open_type">{@menu.push.opening.type}</label>
					<div class="form-field">
						<select name="menu_element_{ID}_open_type" id="menu_element_{ID}_open_type">
							# START opening #
								<option value="{opening.VALUE}" # IF opening.C_SELECTED # selected="selected"# ENDIF #>
									{opening.NAME}
								</option>
							# END opening #
						</select>
					</div>
				</div>
				<div class="form-element push-options">
					<label for="menu_element_{ID}_tab_type">{@menu.push.expansion.type}</label>
					<div class="form-field">
						<select name="menu_element_{ID}_tab_type" id="menu_element_{ID}_tab_type">
							# START expanding #
								<option value="{expanding.VALUE}" # IF expanding.C_SELECTED # selected="selected"# ENDIF #>
									{expanding.NAME}
								</option>
							# END expanding #
						</select>
					</div>
				</div>
				<div class="form-element third-field top-field custom-checkbox">
					<label for="menu_element_{ID}_hidden_with_small_screens">{@menu.hidden.on.small.screens}</label>
					<div class="form-field">
						<div class="form-field-checkbox">
							<label class="checkbox" for="menu_element_{ID}_hidden_with_small_screens">
								<input type="checkbox" name="menu_element_{ID}_hidden_with_small_screens" id="menu_element_{ID}_hidden_with_small_screens"# IF C_MENU_HIDDEN_WITH_SMALL_SCREENS # checked="checked"# ENDIF #>
								<span>&nbsp;</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element half-field">
					<label>{@form.authorizations.read}</label>
					<div class="form-field">{AUTH_MENUS}</div>
				</div>
			</div>
		</fieldset>

		# INCLUDE FILTERS #

		<fieldset>
			<legend>* {@common.content}</legend>
			{MENU_TREE}
		</fieldset>

		<fieldset class="fieldset-submit">
			<div class="fieldset-inset">
				<legend>{@form.submit}</legend>
				<input type="hidden" name="id" value="{MENU_ID}">
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="menu_tree" id="menu_tree" value="">
				<button type="submit" class="button submit" name="valid" value="true">{@form.submit}</button>
			</div>
		</fieldset>
	</form>
</div>
