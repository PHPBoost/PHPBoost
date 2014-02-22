<script type="text/javascript">
<!--
var idMax = {ID_MAX};

function destroySortableMenu() {
    Sortable.destroy('menu_element_{ID}_list');   
}

function createSortableMenu() {
    Sortable.create('menu_element_{ID}_list', {tree:true,scroll:window,format: /^menu_element_([0-9]+)$/});   
}

function toggleProperties(id) {
    if (document.getElementById("menu_element_" + id + "_properties").style.display == "none")
    {   //Si les propriétés sont repliées, on les affiche
        Effect.Appear("menu_element_" + id + "_properties");
        document.getElementById("menu_element_" + id + "_more_image").src = "{PATH_TO_ROOT}/templates/{THEME}/images/form/minus.png";
    }
    else
    {   //Sinon, on les cache
        Effect.Fade("menu_element_" + id + "_properties");
        document.getElementById("menu_element_" + id + "_more_image").src = "{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png";
    }
}

function build_menu_elements_tree() {
    document.getElementById("menu_tree").value = Sortable.serialize('menu_element_{ID}_list');
}

function select(element_id, execute) {
    if (execute) {
        document.getElementById(element_id).select();
    } else {
        setTimeout('select(\'' + element_id + '\', true)', 100);
    }
}

var authForm = new String('<div>' + {J_AUTH_FORM} + '</div>');
function getAuthForm(id) {
    return Builder.build(authForm.replace(/##UID##/g, id));
}

function addSubElement(menu_element_id) {
    var id = idMax++;
    var newDiv = Builder.node('li', {id: 'menu_element_' + id, className: 'menu_link_element', style: 'display:none;' }, [
        Builder.node('div', {style: 'float:left;'}, [
			Builder.node('img', {src: '{PATH_TO_ROOT}/templates/default/images/drag.png', alt: 'plus', className: 'valign_middle', style: 'padding-left:5px;padding-right:5px;cursor:move'}),
            ' ',
            Builder.node('img', {src: '{PATH_TO_ROOT}/templates/{THEME}/images/form/url.png', alt: 'plus', className: 'valign_middle', style: 'cursor:move'}),
            ' ',
            Builder.node('label', {htmlFor: 'menu_element_' + id + '_name'}, {JL_NAME}),
            ' ',
            Builder.node('input', {type: 'text', value: {JL_ADD_SUB_ELEMENT}, onclick: "if(this.value=={JL_ADD_SUB_ELEMENT})this.value=''", onblur: "if(this.value=='')this.value={JL_ADD_SUB_ELEMENT};", id: 'menu_element_' + id + '_name', name: 'menu_element_' + id + '_name'}),
            ' ',
            Builder.node('label', {htmlFor: 'menu_element_' + id + '_url'}, {JL_URL}),
            ' ',
            Builder.node('input', {type: 'text', value: '', id: 'menu_element_' + id + '_url', name: 'menu_element_' + id + '_url'}),
            ' ',
            Builder.node('label', {htmlFor: 'menu_element_' + id + '_image'}, {JL_IMAGE}),
            ' ',
            Builder.node('input', {type: 'text', value: '', id: 'menu_element_' + id + '_image', name: 'menu_element_' + id + '_image'})
        ]),
        Builder.node('div', {style: 'float:right;'}, [
            Builder.node('img', {src: '{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png', alt: {JL_MORE}, id: 'menu_element_' + id + '_more_image', className: 'valign_middle', onclick: 'toggleProperties(' + id + ');'}),
            ' ',
            Builder.node('img', {src: '{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png', alt: {JL_DELETE}, id: 'menu_element_' + id + '_delete_image', className: 'valign_middle', onclick: 'deleteElement(\'menu_element_' + id + '\');'})
        ]),
        Builder.node('div', {className: 'spacer'}),
        Builder.node('fieldset', {id: 'menu_element_' + id + '_properties', style: 'display:none;'}, [
            Builder.node('legend', {JL_PROPERTIES}),
            Builder.node('dl', [
                Builder.node('dt', [
                    Builder.node('label', {htmlFor: 'menu_element_' + id + '_auth'}, {JL_AUTHORIZATIONS})
                ]),
                Builder.node('dd', getAuthForm(id)),
            ]),
        ])
    ]);

    $(menu_element_id + '_list').appendChild(newDiv);
    Effect.Appear(newDiv.id);
    destroySortableMenu();
    createSortableMenu();
    select('menu_element_' + id + '_name');
}

function addSubMenu(menu_element_id) {
    var id = idMax++;
    var newDiv = Builder.node('li', {id: 'menu_element_' + id, className: 'menu_link_element menu_link_menu', style: 'display:none;' }, [
        Builder.node('div', {style: 'float:left;'}, [
			Builder.node('img', {src: '{PATH_TO_ROOT}/templates/default/images/drag.png', alt: 'plus', className: 'valign_middle', style: 'padding-left:5px;padding-right:5px;cursor:move'}),
            ' ',
            Builder.node('img', {src: '{PATH_TO_ROOT}/templates/{THEME}/images/upload/folder.png', alt: 'folder', className: 'valign_middle', style: 'cursor:move'}),
            ' ',
            Builder.node('label', {htmlFor: 'menu_element_' + id + '_name'}, {JL_NAME}),
            ' ',
            Builder.node('input', {type: 'text', value: {JL_ADD_SUB_MENU}, onclick: "this.value=''", onblur: "if(this.value=='')this.value={JL_ADD_SUB_MENU};", id: 'menu_element_' + id + '_name', name: 'menu_element_' + id + '_name'}),
            ' ',
            Builder.node('label', {htmlFor: 'menu_element_' + id + '_url'}, {JL_URL}),
            ' ',
            Builder.node('input', {type: 'text', value: '', id: 'menu_element_' + id + '_url', name: 'menu_element_' + id + '_url'}),
            ' ',
            Builder.node('label', {htmlFor: 'menu_element_' + id + '_image'}, {JL_IMAGE}),
            ' ',
            Builder.node('input', {type: 'text', value: '', id: 'menu_element_' + id + '_image', name: 'menu_element_' + id + '_image'})
        ]),
        Builder.node('div', {style: 'float:right;'}, [
            Builder.node('img', {src: '{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png', alt: {JL_MORE}, id: 'menu_element_' + id + '_more_image', className: 'valign_middle', onclick: 'toggleProperties(' + id + ');'}),
            ' ',
            Builder.node('img', {src: '{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png', alt: {JL_DELETE}, id: 'menu_element_' + id + '_delete_image', className: 'valign_middle', onclick: 'deleteElement(\'menu_element_' + id + '\');'})
        ]),
        Builder.node('div', {className: 'spacer'}),
        Builder.node('fieldset', {id: 'menu_element_' + id + '_properties', style: 'display:none;'}, [
            Builder.node('legend', {JL_PROPERTIES}),
            Builder.node('dl', [
                Builder.node('dt', [
                  Builder.node('label', {htmlFor: 'menu_element_' + id + '_auth'}, {JL_AUTHORIZATIONS})
                ]),
                Builder.node('dd', getAuthForm(id)),
            ]),
        ]),
        Builder.node('hr', {style: 'background-color:#999999;margin-top:5px;'}),
        Builder.node('ul', {id: 'menu_element_' + id + '_list', className: 'menu_link_list'}),
        Builder.node('fieldset', {className: 'fieldset_submit', style: 'margin-bottom:7px;margin-top:0px;padding-bottom:0px;'}, [
            Builder.node('input', {type: 'button', id: 'menu_element_' + id + '_add_sub_element', name: 'menu_element_' + id + '_add_sub_element', value: {JL_ADD_SUB_ELEMENT}, onclick: 'addSubElement(\'menu_element_' + id + '\');', className: 'submit'}),
            ' ',
            Builder.node('input', {type: 'button', id: 'menu_element_' + id + '_add_sub_menu', name: 'menu_element_' + id + '_add_sub_menu', value: {JL_ADD_SUB_MENU}, onclick: 'addSubMenu(\'menu_element_' + id + '\');', className: 'submit'}),
        ]),
    ]);

    $(menu_element_id + '_list').appendChild(newDiv);
    Effect.Appear(newDiv.id);
    addSubElement('menu_element_' + id);
    select('menu_element_' + id + '_name');
}

function deleteElement(element_id)
{
    if (confirm({JL_DELETE_ELEMENT}))
    {
        var elementToDelete = document.getElementById(element_id);
        elementToDelete.parentNode.removeChild(elementToDelete);
        destroySortableMenu();
        createSortableMenu();
    }
}

-->
</script>
<div id="admin_contents">
	<form action="links.php?action=save" method="post" class="fieldset_content" onsubmit="build_menu_elements_tree();">
		<fieldset> 
			<legend>{L_ACTION_MENUS}</legend>
			<dl>
				<dt><label for="menu_element_{ID}_name">* {L_NAME}</label></dt>
				<dd><input type="text" name="menu_element_{ID}_name" id="menu_element_{ID}_name" value="{MENU_NAME}" /></dd>
			</dl>
			<dl>
				<dt><label for="menu_element_{ID}_url">{L_URL}</label></dt>
				<dd><input type="text" name="menu_element_{ID}_url" id="menu_element_{ID}_url" value="{MENU_URL}" /></dd>
			</dl>
			<dl>
				<dt><label for="menu_element_{ID}_image">{L_IMAGE}</label></dt>
				<dd><input type="text" name="menu_element_{ID}_image" id="menu_element_{ID}_image" value="{MENU_IMG}" /></dd>
			</dl>
			<dl>
				<dt><label for="menu_element_{ID}_type">* {L_TYPE}</label></dt>
				<dd>
					<label>
						<select name="menu_element_{ID}_type" id="menu_element_{ID}_type">
							# START type #
								<option value="{type.NAME}"{type.SELECTED}>{type.L_NAME}</option>
							# END type #
						</select>
					</label>
				</dd>
			</dl>
			<dl>
				<dt><label for="menu_element_{ID}_location">* {L_LOCATION}</label></dt>
				<dd><label>
                    <select name="menu_element_{ID}_location" id="menu_element_{ID}_location">
                        # START location #
                            <option value="{location.VALUE}" # IF location.C_SELECTED # selected="selected"# ENDIF #>
                                {location.NAME}
                            </option>
                        # END location #
                    </select>
                </label></dd>
			</dl>
			<dl>
				<dt><label for="menu_element_{ID}_enabled">{L_STATUS}</label></dt>
				<dd><label>
					<select name="menu_element_{ID}_enabled" id="menu_element_{ID}_enabled">
					   # IF C_ENABLED #
							<option value="1" selected="selected">{L_ENABLED}</option>
							<option value="0">{L_DISABLED}</option>
						# ELSE #
                            <option value="1">{L_ENABLED}</option>
                            <option value="0" selected="selected">{L_DISABLED}</option>
						# ENDIF #					
					</select>
				</label></dd>
			</dl>
			<dl>
				<dt><label>{L_AUTHS}</label></dt>
				<dd>{AUTH_MENUS}</dd>
			</dl>
		</fieldset>
		
		# INCLUDE filters #
	    
		<fieldset>
			<legend>* {L_CONTENT}</legend>
			{MENU_TREE}
		    <script type="text/javascript">
		    <!--
		    createSortableMenu();
            -->
            </script>
			<br />
	    </fieldset>			
	
		<fieldset class="fieldset_submit">
			<legend>{L_ACTION}</legend>
			<input type="hidden" name="id" value="{MENU_ID}" />
			<input type="hidden" name="token" value="{TOKEN}" />
			<input type="hidden" name="menu_tree" id="menu_tree" value="" />
			<input type="submit" name="valid" value="{L_ACTION}" class="submit" />					
		</fieldset>
	</form>
</div>