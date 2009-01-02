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

function getAuthForm() {
    var authForm = new String({J_AUTH_FORM});
    authForm = new String(authForm.replace(/##UID##/g, idMax));
    return Builder.build(authForm);
}

function addSubElement(menu_element_id) {
    idMax++;
    
    var newDiv = Builder.node('li', {id: 'menu_element_' + idMax, className: 'row2 menu_link_element', style: 'display:none;' }, [
        Builder.node('div', {style: 'float:left;'}, [
            Builder.node('img', {src: '{PATH_TO_ROOT}/templates/{THEME}/images/form/url.png', alt: 'plus', className: 'valign_middle'}),
            ' ',
            Builder.node('label', {htmlFor: 'menu_element_' + idMax + '_name'}, {JL_NAME}),
            ' ',
            Builder.node('input', {type: 'text', value: {JL_NEW_SUB_ELEMENT}, id: 'menu_element_' + idMax + '_name', name: 'menu_element_' + idMax + '_name'}),
            ' ',
            Builder.node('label', {htmlFor: 'menu_element_' + idMax + '_url'}, {JL_URL}),
            ' ',
            Builder.node('input', {type: 'text', value: '', id: 'menu_element_' + idMax + '_url', name: 'menu_element_' + idMax + '_url'}),
            ' ',
            Builder.node('label', {htmlFor: 'menu_element_' + idMax + '_name'}, {JL_IMAGE}),
            ' ',
            Builder.node('input', {type: 'text', value: '', id: 'menu_element_' + idMax + '_image', name: 'menu_element_' + idMax + '_image'})
        ]),
        Builder.node('div', {style: 'float:right;'}, [
            Builder.node('img', {src: '{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png', alt: {JL_MORE}, id: 'menu_element_' + idMax + '_more_image', className: 'valign_middle', onclick: 'toggleProperties(' + idMax + ');'}),
            ' ',
            Builder.node('img', {src: '{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png', alt: {JL_DELETE}, id: 'menu_element_' + idMax + '_delete_image', className: 'valign_middle', onclick: 'deleteElement(\'menu_element_' + idMax + '\');'})
        ]),
        Builder.node('div', {className: 'spacer'}),
        Builder.node('fieldset', {id: 'menu_element_' + idMax + '_properties', style: 'display:none;'}, [
            Builder.node('legend', {JL_PROPERTIES}),
            Builder.node('dl', [
                Builder.node('dt', [
                    Builder.node('label', {htmlFor: 'menu_element_' + idMax + '_auth'}, {JL_AUTHORIZATIONS})
                ]),
                Builder.node('dd', getAuthForm()),
            ]),
        ])
    ]);

    $(menu_element_id + '_list').appendChild(newDiv);
    Effect.Appear(newDiv.id);
    document.getElementById('menu_element_' + idMax + '_name').focus();
    destroySortableMenu();
    createSortableMenu();
}

function addSubMenu(menu_element_id) {
    idMax++;
    var id = idMax;
    var newDiv = Builder.node('li', {id: 'menu_element_' + id, className: 'row1 menu_link_element', style: 'display:none;' }, [
        Builder.node('div', {style: 'float:left;'}, [
            Builder.node('img', {src: '{PATH_TO_ROOT}/templates/{THEME}/images/form/url.png', alt: 'plus', className: 'valign_middle'}),
            ' ',
            Builder.node('label', {htmlFor: 'menu_element_' + id + '_name'}, {JL_NAME}),
            ' ',
            Builder.node('input', {type: 'text', value: {JL_NEW_SUB_MENU}, id: 'menu_element_' + id + '_name', name: 'menu_element_' + id + '_name'}),
            ' ',
            Builder.node('label', {htmlFor: 'menu_element_' + id + '_url'}, {JL_URL}),
            ' ',
            Builder.node('input', {type: 'text', value: '', id: 'menu_element_' + id + '_url', name: 'menu_element_' + id + '_url'}),
            ' ',
            Builder.node('label', {htmlFor: 'menu_element_' + id + '_name'}, {JL_IMAGE}),
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
                Builder.node('dd', getAuthForm()),
            ]),
        ]),
        Builder.node('hr', {style: 'background-color:#999999;margin-top:5px;'}),
        Builder.node('ul', {id: 'menu_element_' + id + '_list', className: 'menu_link_list'}),
        Builder.node('fieldset', {className: 'fieldset_submit', style: 'margin-bottom:0px;padding-bottom:0px;'}, [
            Builder.node('input', {type: 'button', id: 'menu_element_' + id + '_add_sub_element', name: 'menu_element_' + id + '_add_sub_element', value: {JL_ADD_SUB_ELEMENT}, onclick: 'addSubElement(\'menu_element_' + id + '\');', className: 'submit'}),
            ' ',
            Builder.node('input', {type: 'button', id: 'menu_element_' + id + '_add_sub_menu', name: 'menu_element_' + id + '_add_sub_menu', value: {JL_ADD_SUB_MENU}, onclick: 'addSubMenu(\'menu_element_' + id + '\');', className: 'submit'}),
        ]),
    ]);

    $(menu_element_id + '_list').appendChild(newDiv);
    Effect.Appear(newDiv.id);
    addSubElement('menu_element_' + id);
    document.getElementById('menu_element_' + id + '_name').focus();
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
				<dt><label for="menu_element_{ID}_name">{L_NAME}</label></dt>
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
				<dt><label for="menu_element_{ID}_type">{L_TYPE}</label></dt>
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
				<dt><label for="menu_element_{ID}_location">{L_LOCATION}</label></dt>
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
				<dt><label for="menu_element_{ID}_auth">{L_AUTHS}</label></dt>
				<dd><label>{AUTH_MENUS}</label></dd>
			</dl>
		</fieldset>
		
		<fieldset>
			<legend>{L_CONTENT}</legend>
			{MENU_TREE}
		    <script type="text/javascript">
		    <!--
		    createSortableMenu();
            -->
            </script>
	    </fieldset>			
	
		<fieldset class="fieldset_submit">
			<legend>{L_ACTION}</legend>
			<input type="hidden" name="id" value="{MENU_ID}" />
			<input type="hidden" name="menu_tree" id="menu_tree" value="" />
			<input type="submit" name="valid" value="{L_ACTION}" class="submit" />
			<input type="reset" value="{L_RESET}" class="reset" />					
		</fieldset>
	</form>
</div>