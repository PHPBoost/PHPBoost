<script>
<!--
var idMax = {ID_MAX};

function initSortableMenu() {
	jQuery("ul#menu_element_{ID}_list").sortable({
		handle: '.sortable-selector',
		placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>'
	}); 
}

function get_sortable_sequence() {
	var sequence = jQuery("ul#menu_element_{ID}_list").sortable("serialize").get();
	return sequence[0];
}

function build_menu_elements_tree() {
	jQuery('#menu_tree').val(JSON.stringify(get_sortable_sequence()));
}

function toggleProperties(id) {
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
function getAuthForm(id) {
	return authForm.replace(/##UID##/g, id);
}

function addSubElement(menu_element_id) {
	var id = idMax++;
	
	jQuery('<li/>', {id : 'menu_element_' + id, 'data-id' : id, class : 'sortable-element'}).appendTo('#' + menu_element_id + '_list');
	jQuery('<div/>', {class : 'sortable-selector', title : ${escapejs(LangLoader::get_message('position.move', 'common'))}}).appendTo('#menu_element_' + id);
	jQuery('<div/>', {id : 'menu_title_' + id, class : 'sortable-title'}).appendTo('#menu_element_' + id);
	
	jQuery('<i/>', {class : 'fa fa-globe'}).appendTo('#menu_title_' + id);
	jQuery('#menu_title_' + id).append(' ');
	
	jQuery('<label/>', {for : 'menu_element_' + id + '_name'}).text({JL_NAME} + ' ').appendTo('#menu_title_' + id);
	jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_name', name : 'menu_element_' + id + '_name', placeholder : {JL_ADD_SUB_ELEMENT}}).appendTo('#menu_title_' + id);
	jQuery('#menu_title_' + id).append(' ');
	
	jQuery('<label/>', {for : 'menu_element_' + id + '_url'}).text({JL_URL} + ' ').appendTo('#menu_title_' + id);
	jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_url', name : 'menu_element_' + id + '_url'}).appendTo('#menu_title_' + id);
	jQuery('#menu_title_' + id).append(' ');
	
	jQuery('<label/>', {for : 'menu_element_' + id + '_image'}).text({JL_IMAGE} + ' ').appendTo('#menu_title_' + id);
	jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_image', name : 'menu_element_' + id + '_image', onblur: "image_preview(this,menu_element_" + id + "_image_preview)"}).appendTo('#menu_title_' + id);
	jQuery('<span/>', {id : 'menu_element_' + id + '_image_preview_span', class : 'preview'}).appendTo('#menu_title_' + id);
	jQuery('<img/>', {id : 'menu_element_' + id + '_image_preview'}).appendTo('#menu_element_' + id + '_image_preview_span');
	
	jQuery('<div/>', {id : 'menu_element_' + id + '_actions', class : 'sortable-actions'}).appendTo('#menu_title_' + id);
	jQuery('<a/>', {id : 'menu_element_' + id + '_more_image', title : {JL_MORE}, onclick: 'toggleProperties(' + id + ');return false;'}).html('<i class="fa fa-cog"></i>').appendTo('#menu_element_' + id + '_actions');
	jQuery('#menu_element_' + id + '_actions').append(' ');
	jQuery('<a/>', {id : 'menu_element_' + id + '_delete_image', title : {JL_DELETE}, onclick: 'deleteElement(\'menu_element_' + id + '\');return false;'}).html('<i class="fa fa-delete"></i>').appendTo('#menu_element_' + id + '_actions');
	
	jQuery('<div/>', {class : 'spacer'}).appendTo('#menu_element_' + id);
	
	jQuery('<fieldset/>', {id : 'menu_element_' + id + '_properties', style : 'display:none;'}).appendTo('#menu_element_' + id);
	jQuery('<legend/>').text({JL_PROPERTIES}).appendTo('#menu_element_' + id + '_properties');
	jQuery('<div/>', {id : 'menu_element_' + id + '_authorizations', class : 'form-element'}).appendTo('#menu_element_' + id + '_properties');
	jQuery('<label/>', {for : 'menu_element_' + id + '_auth_div'}).text({JL_AUTHORIZATIONS} + ' ').appendTo('#menu_element_' + id + '_authorizations');
	jQuery('<div/> ', {class : 'form-field'}).html(getAuthForm(id)).appendTo('#menu_element_' + id + '_authorizations');
	
	initSortableMenu();
}

function addSubMenu(menu_element_id) {
	var id = idMax++;
	
	jQuery('<li/>', {id : 'menu_element_' + id, 'data-id' : id, class : 'sortable-element'}).appendTo('#' + menu_element_id + '_list');
	jQuery('<div/>', {class : 'sortable-selector', title : ${escapejs(LangLoader::get_message('position.move', 'common'))}}).appendTo('#menu_element_' + id);
	jQuery('<div/>', {id : 'menu_title_' + id, class : 'sortable-title'}).appendTo('#menu_element_' + id);
	
	jQuery('<i/>', {class : 'fa fa-folder'}).appendTo('#menu_title_' + id);
	jQuery('#menu_title_' + id).append(' ');
	
	jQuery('<label/>', {for : 'menu_element_' + id + '_name'}).text({JL_NAME} + ' ').appendTo('#menu_title_' + id);
	jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_name', name : 'menu_element_' + id + '_name', placeholder : {JL_ADD_SUB_MENU}}).appendTo('#menu_title_' + id);
	jQuery('#menu_title_' + id).append(' ');
	
	jQuery('<label/>', {for : 'menu_element_' + id + '_url'}).text({JL_URL} + ' ').appendTo('#menu_title_' + id);
	jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_url', name : 'menu_element_' + id + '_url'}).appendTo('#menu_title_' + id);
	jQuery('#menu_title_' + id).append(' ');
	
	jQuery('<label/>', {for : 'menu_element_' + id + '_image'}).text({JL_IMAGE} + ' ').appendTo('#menu_title_' + id);
	jQuery('<input/>', {type : 'text', id : 'menu_element_' + id + '_image', name : 'menu_element_' + id + '_image', onblur: "image_preview(this,menu_element_" + id + "_image_preview)"}).appendTo('#menu_title_' + id);
	jQuery('<span/>', {id : 'menu_element_' + id + '_image_preview_span', class : 'preview'}).appendTo('#menu_title_' + id);
	jQuery('<img/>', {id : 'menu_element_' + id + '_image_preview'}).appendTo('#menu_element_' + id + '_image_preview_span');
	
	jQuery('<div/>', {id : 'menu_element_' + id + '_actions', class : 'sortable-actions'}).appendTo('#menu_title_' + id);
	jQuery('<a/>', {id : 'menu_element_' + id + '_more_image', title : {JL_MORE}, onclick: 'toggleProperties(' + id + ');return false;'}).html('<i class="fa fa-cog"></i>').appendTo('#menu_element_' + id + '_actions');
	jQuery('#menu_element_' + id + '_actions').append(' ');
	jQuery('<a/>', {id : 'menu_element_' + id + '_delete_image', title : {JL_DELETE}, onclick: 'deleteElement(\'menu_element_' + id + '\');return false;'}).html('<i class="fa fa-delete"></i>').appendTo('#menu_element_' + id + '_actions');
	
	jQuery('<div/>', {class : 'spacer'}).appendTo('#menu_element_' + id);
	
	jQuery('<fieldset/>', {id : 'menu_element_' + id + '_properties', style : 'display:none;'}).appendTo('#menu_element_' + id);
	jQuery('<legend/>').text({JL_PROPERTIES}).appendTo('#menu_element_' + id + '_properties');
	jQuery('<div/>', {id : 'menu_element_' + id + '_authorizations', class : 'form-element'}).appendTo('#menu_element_' + id + '_properties');
	jQuery('<label/>', {for : 'menu_element_' + id + '_auth_div'}).text({JL_AUTHORIZATIONS} + ' ').appendTo('#menu_element_' + id + '_authorizations');
	jQuery('<div/> ', {class : 'form-field'}).html(getAuthForm(id)).appendTo('#menu_element_' + id + '_authorizations');
	
	jQuery('<hr/>').appendTo('#menu_element_' + id);
	jQuery('<ul/>', {id : 'menu_element_' + id + '_list', class : 'sortable-block'}).appendTo('#menu_element_' + id);
	
	jQuery('<fieldset/>', {id : 'menu_element_' + id + '_buttons', class : 'fieldset-submit'}).appendTo('#menu_element_' + id);
	jQuery('<button/>', {type : 'button', id : 'menu_element_' + id + '_add_sub_element', name : 'menu_element_' + id + '_add_sub_element', value : {JL_ADD_SUB_ELEMENT}, onclick : 'addSubElement(\'menu_element_' + id + '\');'}).text({JL_ADD_SUB_ELEMENT}).appendTo('#menu_element_' + id + '_buttons');
	jQuery('#menu_element_' + id + '_buttons').append(' ');
	
	jQuery('<button/>', {type : 'button', id : 'menu_element_' + id + '_add_sub_menu', name : 'menu_element_' + id + '_add_sub_menu', value : {JL_ADD_SUB_MENU}, onclick : 'addSubMenu(\'menu_element_' + id + '\');'}).text({JL_ADD_SUB_MENU}).appendTo('#menu_element_' + id + '_buttons');
	
	addSubElement('menu_element_' + id);
}

function deleteElement(element_id)
{
	if (confirm({JL_DELETE_ELEMENT}))
	{
		jQuery('#' + element_id).remove();
		initSortableMenu();
	}
}

function image_preview(input,image)
{
	var url = input.value;
	var reg = /^https?:\/\//i;
	if (reg.test(url)) image.src = url;
	else image.src = '{PATH_TO_ROOT}' + url;
}

jQuery(document).ready(function() {
	initSortableMenu();
});
-->
</script>
<div id="admin-contents">
	<form action="links.php?action=save" method="post" class="fieldset-content" onsubmit="build_menu_elements_tree();">
		<fieldset> 
			<legend>{L_ACTION_MENUS}</legend>
			<div class="form-element">
				<label for="menu_element_{ID}_name">{L_NAME}</label>
				<div class="form-field"><input type="text" name="menu_element_{ID}_name" id="menu_element_{ID}_name" value="{MENU_NAME}"></div>
			</div>
			<div class="form-element">
				<label for="menu_element_{ID}_url">{L_URL}</label>
				<div class="form-field"><input type="text" name="menu_element_{ID}_url" id="menu_element_{ID}_url" value="{MENU_URL}"></div>
			</div>
			<div class="form-element">
				<label for="menu_element_{ID}_image">{L_IMAGE}</label>
				<div class="form-field"><input type="text" name="menu_element_{ID}_image" id="menu_element_{ID}_image" value="{MENU_IMG}"></div>
			</div>
			<div class="form-element">
				<label for="menu_element_{ID}_type">{L_TYPE}</label>
				<div class="form-field">
					<label>
						<select name="menu_element_{ID}_type" id="menu_element_{ID}_type">
							# START type #
								<option value="{type.NAME}"{type.SELECTED}>{type.L_NAME}</option>
							# END type #
						</select>
					</label>
				</div>
			</div>
			<div class="form-element">
				<label for="menu_element_{ID}_location">{L_LOCATION}</label>
				<div class="form-field"><label>
					<select name="menu_element_{ID}_location" id="menu_element_{ID}_location">
						# START location #
							<option value="{location.VALUE}" # IF location.C_SELECTED # selected="selected"# ENDIF #>
								{location.NAME}
							</option>
						# END location #
					</select>
				</label></div>
			</div>
			<div class="form-element">
				<label for="menu_element_{ID}_enabled">{L_STATUS}</label>
				<div class="form-field"><label>
					<select name="menu_element_{ID}_enabled" id="menu_element_{ID}_enabled">
					   # IF C_ENABLED #
							<option value="1" selected="selected">{L_ENABLED}</option>
							<option value="0">{L_DISABLED}</option>
						# ELSE #
							<option value="1">{L_ENABLED}</option>
							<option value="0" selected="selected">{L_DISABLED}</option>
						# ENDIF #
					</select>
				</label></div>
			</div>
			<div class="form-element">
				<label>{L_AUTHS}</label>
				<div class="form-field">{AUTH_MENUS}</div>
			</div>
		</fieldset>
		
		# INCLUDE filters #
		
		<fieldset>
			<legend>* {L_CONTENT}</legend>
			{MENU_TREE}
		</fieldset>
	
		<fieldset class="fieldset-submit">
			<legend>{L_ACTION}</legend>
			<input type="hidden" name="id" value="{MENU_ID}">
			<input type="hidden" name="token" value="{TOKEN}">
			<input type="hidden" name="menu_tree" id="menu_tree" value="">
			<button type="submit" class="submit" name="valid" value="true">{L_ACTION}</button>
		</fieldset>
	</form>
</div>