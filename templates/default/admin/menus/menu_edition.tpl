# IF C_FIRST_MENU #
    <div id="menu_element_{ID}">
        <input type="hidden" id="menu_uid" name="menu_uid" value="{ID}">
    	<ul id="menu_element_{ID}_list" class="sortable-block">
    		# START elements #
    			{elements.DISPLAY}
    		# END elements #
    	</ul>
        <fieldset class="fieldset_submit">
            <button type="button" id="menu_element_{ID}_add_sub_element" name="menu_element_{ID}_add_sub_element" onclick="addSubElement('menu_element_{ID}');">{L_ADD_SUB_ELEMENT}</button>
            <button type="button" id="menu_element_{ID}_add_sub_menu" name="menu_element_{ID}_add_sub_menu" onclick="addSubMenu('menu_element_{ID}');">{L_ADD_SUB_MENU}</button>
        </fieldset>
    </div>
# ENDIF #

# IF C_NEXT_MENU #
	<li class="sortable-element" id="menu_element_{ID}">
		<div class="sortable-title">
			<i class="icon-arrows" title="${LangLoader::get_message('move', 'admin')}"></i>
			<img src="{PATH_TO_ROOT}/templates/default/images/admin/upload/folder.png" alt="plus" class="valign_middle" style="cursor:move" />
			<label for="menu_element_{ID}_name">{L_NAME}</label> <input type="text" value="{TITLE}" id="menu_element_{ID}_name" name="menu_element_{ID}_name">
			<label for="menu_element_{ID}_url">{L_URL}</label> <input type="text" value="{RELATIVE_URL}" id="menu_element_{ID}_url" name="menu_element_{ID}_url">
			<label for="menu_element_{ID}_image">{L_IMAGE}</label> <input type="text" value="{RELATIVE_IMG}" id="menu_element_{ID}_image" name="menu_element_{ID}_image" onblur="image_preview(this,menu_element_{ID}_image_preview)">
			<div class="sortable-options preview">
				<img src="{PATH_TO_ROOT}/{RELATIVE_IMG}" id="menu_element_{ID}_image_preview"/>
			</div>
			<div class="sortable-actions">
				<i class="icon-plus" title="More..." id="menu_element_{ID}_more_image" onclick="toggleProperties({ID});"></i>
				<i class="icon-delete" title="{L_DELETE}" id="menu_element_{ID}_delete_image" style="cursor:pointer;" onclick="deleteElement('menu_element_{ID}');"></i>
			</div>
		</div>
		<div class="spacer"></div>
		<fieldset id="menu_element_{ID}_properties" style="display:none;">
			<legend>{L_PROPERTIES}</legend>
			<div class="form-element">
				{L_AUTHORIZATIONS}
				<div class="form-field">{AUTH_FORM}</div>
			</div>
		</fieldset>
        <hr/>
		<ul class="sortable-block" id="menu_element_{ID}_list">
    		# START elements #
    			{elements.DISPLAY}
    		# END elements #
		</ul>
		<fieldset class="fieldset_submit">
			<button type="button" id="menu_element_{ID}_add_sub_element" name="menu_element_{ID}_add_sub_element" onclick="addSubElement('menu_element_{ID}');">{L_ADD_SUB_ELEMENT}</button>
            <button type="button" id="menu_element_{ID}_add_sub_menu" name="menu_element_{ID}_add_sub_menu" onclick="addSubMenu('menu_element_{ID}');">{L_ADD_SUB_MENU}</button>
		</fieldset>
	</li>
# ENDIF #

# IF C_LINK #
    <li class="sortable-element" id="menu_element_{ID}">
   		<div class="sortable-title">
			<i class="icon-arrows" title="${LangLoader::get_message('move', 'admin')}"></i>
   			<i class="icon-globe"></i>
			<label for="menu_element_{ID}_name">{L_NAME}</label> <input type="text" value="{TITLE}" id="menu_element_{ID}_name" name="menu_element_{ID}_name">
			<label for="menu_element_{ID}_url">{L_URL}</label> <input type="text" value="{RELATIVE_URL}" id="menu_element_{ID}_url" name="menu_element_{ID}_url">
			<label for="menu_element_{ID}_image">{L_IMAGE}</label> <input type="text" value="{RELATIVE_IMG}" id="menu_element_{ID}_image" name="menu_element_{ID}_image" onblur="image_preview(this,menu_element_{ID}_image_preview)">
			<div class="sortable-options preview">
				<img src="{PATH_TO_ROOT}/{RELATIVE_IMG}" id="menu_element_{ID}_image_preview"/>
			</div>
			<div class="sortable-actions">
				<i class="icon-plus" title="{L_MORE}" id="menu_element_{ID}_more_image" onclick="toggleProperties({ID});"></i>
				<i class="icon-delete" title="{L_DELETE}" id="menu_element_{ID}_delete_image" style="cursor:pointer;" onclick="deleteElement('menu_element_{ID}');"></i>
			</div>
		</div>
		<div class="spacer"></div>
		<fieldset id="menu_element_{ID}_properties" style="display:none;">
			<legend>{L_PROPERTIES}</legend>
			<div class="form-element">
				{L_AUTHORIZATIONS}
				<div class="form-field">{AUTH_FORM}</div>
			</div>
		</fieldset>
    </li>
# ENDIF #
