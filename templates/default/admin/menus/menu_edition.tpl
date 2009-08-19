# IF C_FIRST_MENU #
    <div id="menu_element_{ID}">
        <input type="hidden" id="menu_uid" name="menu_uid" value="{ID}" />
    	<ul id="menu_element_{ID}_list" class="menu_link_list">
    		# START elements #
    			{elements.DISPLAY}
    		# END elements #
    	</ul>
        <fieldset class="fieldset_submit" style="padding:0;margin-bottom:4px;margin-top:15px;">
            <input type="button" id="menu_element_{ID}_add_sub_element" name="menu_element_{ID}_add_sub_element" value="{L_ADD_SUB_ELEMENT}" onclick="addSubElement('menu_element_{ID}');" class="submit" />
            <input type="button" id="menu_element_{ID}_add_sub_menu" name="menu_element_{ID}_add_sub_menu" value="{L_ADD_SUB_MENU}" onclick="addSubMenu('menu_element_{ID}');" class="submit" />
        </fieldset>
    </div>
# ENDIF #

# IF C_NEXT_MENU #
	<li class="menu_link_element" id="menu_element_{ID}">
		<div style="float:left;">
			<img src="{PATH_TO_ROOT}/templates/default/images/drag.png" alt="" class="valign_middle" style="padding-left:5px;padding-right:5px;cursor:move" />
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/folder.png" alt="plus" class="valign_middle" style="cursor:move" />
			<label for="menu_element_{ID}_name">{L_NAME}</label> <input type="text" value="{TITLE}" id="menu_element_{ID}_name" name="menu_element_{ID}_name" />
			<label for="menu_element_{ID}_url">{L_URL}</label> <input type="text" value="{ABSOLUTE_URL}" id="menu_element_{ID}_url" name="menu_element_{ID}_url" />
			<label for="menu_element_{ID}_image">{L_IMAGE}</label> <input type="text" value="{ABSOLUTE_IMG}" id="menu_element_{ID}_image" name="menu_element_{ID}_image" />
		</div>
		<div style="float:right;">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" alt="More..." id="menu_element_{ID}_more_image" class="valign_middle" onclick="toggleProperties({ID});" />
            <img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" id="menu_element_{ID}_delete_image" class="valign_middle" onclick="deleteElement('menu_element_{ID}');" />
		</div>
		<div class="spacer"></div>
		<fieldset id="menu_element_{ID}_properties" style="display:none;">
			<legend>{L_PROPERTIES}</legend>
			<dl>
				<dt>{L_AUTHORIZATIONS}</dt>
				<dd>{AUTH_FORM}</dd>
			</dl>
		</fieldset>
        <hr style="background-color:#999999;margin-top:5px;" />
		<ul class="menu_link_list" id="menu_element_{ID}_list">
    		# START elements #
    			{elements.DISPLAY}
    		# END elements #
		</ul>
		<fieldset class="fieldset_submit" style="padding:0;margin-bottom:4px;margin-top:15px;">
			<input type="button" id="menu_element_{ID}_add_sub_element" name="menu_element_{ID}_add_sub_element" value="{L_ADD_SUB_ELEMENT}" onclick="addSubElement('menu_element_{ID}');" class="submit" />
            <input type="button" id="menu_element_{ID}_add_sub_menu" name="menu_element_{ID}_add_sub_menu" value="{L_ADD_SUB_MENU}" onclick="addSubMenu('menu_element_{ID}');" class="submit" />
		</fieldset>
	</li>
# ENDIF #

# IF C_LINK #
    <li class="menu_link_element" id="menu_element_{ID}">
   		<div style="float:left;">
			<img src="{PATH_TO_ROOT}/templates/default/images/drag.png" alt="" class="valign_middle" style="padding-left:5px;padding-right:5px;cursor:move" />
   			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/url.png" alt="plus" class="valign_middle" style="cursor:move" />
			<label for="menu_element_{ID}_name">{L_NAME}</label> <input type="text" value="{TITLE}" id="menu_element_{ID}_name" name="menu_element_{ID}_name" />
			<label for="menu_element_{ID}_url">{L_URL}</label> <input type="text" value="{ABSOLUTE_URL}" id="menu_element_{ID}_url" name="menu_element_{ID}_url" />
			<label for="menu_element_{ID}_image">{L_IMAGE}</label> <input type="text" value="{ABSOLUTE_IMG}" id="menu_element_{ID}_image" name="menu_element_{ID}_image" />
		</div>
		<div style="float:right;">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" alt="{L_MORE}" id="menu_element_{ID}_more_image" class="valign_middle" onclick="toggleProperties({ID});" />
            <img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" id="menu_element_{ID}_delete_image" class="valign_middle" onclick="deleteElement('menu_element_{ID}');" />
		</div>
		<div class="spacer"></div>
		<fieldset id="menu_element_{ID}_properties" style="display:none;">
			<legend>{L_PROPERTIES}</legend>
			<dl>
				<dt>{L_AUTHORIZATIONS}</dt>
				<dd>{AUTH_FORM}</dd>
			</dl>
		</fieldset>
    </li>
# ENDIF #
