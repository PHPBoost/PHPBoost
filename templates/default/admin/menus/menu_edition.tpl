# IF C_FIRST_MENU #
    <div id="menu_element_{ID}">
        <input type="hidden" id="menu_uid" name="menu_uid" value="{ID}" />
    	<ul id="menu_element_{ID}_list" class="menu_link_list">
    		# START elements #
    			{elements.DISPLAY}
    		# END elements #
    	</ul>
        <fieldset class="fieldset_submit" style="margin-bottom:0px;padding-bottom:0px;">
            <input type="button" id="menu_element_{ID}_add" name="menu_element_{ID}_add" value="{L_ADD}" onclick="addSubElement('menu_element_{ID}');" class="submit" />
        </fieldset>
    </div>
# ENDIF #

# IF C_NEXT_MENU #
	<li class="row1 menu_link_element" id="menu_element_{ID}">
			<div style="float:left;">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/folder.png" alt="plus" class="valign_middle" />
			<label for="menu_element_{ID}_name">{L_NAME}</label> <input type="text" value="{TITLE}" id="menu_element_{ID}_name" name="menu_element_{ID}_name" />
			<label for="menu_element_{ID}_url">{L_URL}</label> <input type="text" value="{URL}" id="menu_element_{ID}_url" name="menu_element_{ID}_url" />
			<label for="menu_element_{ID}_image">{L_IMAGE}</label> <input type="text" value="{IMG}" id="menu_element_{ID}_image" name="menu_element_{ID}_image" />
		</div>
		<div style="float:right;">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" alt="More..." id="menu_element_{ID}_more_image" class="valign_middle" onclick="toggleProperties({ID});" />
            <img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" id="menu_element_{ID}_delete_image" class="valign_middle" onclick="deleteElement('menu_element_{ID}');" />
		</div>
		<div class="spacer"></div>
		<fieldset id="menu_element_{ID}_properties" style="display:none;">
			<legend>{L_PROPERTIES}</legend>
			<dl>
				<dt><label>{L_AUTHORIZATIONS}</label></dt>
				<dd>{AUTH_FORM}</dd>
			</dl>
		</fieldset>
        <hr style="background-color:#999999;margin-top:5px;" />
		<ul class="menu_link_list" id="menu_element_{ID}_list">
    		# START elements #
    			{elements.DISPLAY}
    		# END elements #
		</ul>
		<fieldset class="fieldset_submit" style="margin-bottom:0px;padding-bottom:0px;">
			<input type="button" id="menu_element_{ID}_add" name="menu_element_{ID}_add" value="{L_ADD}" onclick="addSubElement('menu_element_{ID}');" class="submit" />
		</fieldset>
	</li>
# ENDIF #

# IF C_LINK #
    <li class="row2 menu_link_element" id="menu_element_{ID}">
   		<div style="float:left;">
   			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/url.png" alt="plus" class="valign_middle" />
			<label for="menu_element_{ID}_name">{L_NAME}</label> <input type="text" value="{TITLE}" id="menu_element_{ID}_name" name="menu_element_{ID}_name" />
			<label for="menu_element_{ID}_url">{L_URL}</label> <input type="text" value="{URL}" id="menu_element_{ID}_url" name="menu_element_{ID}_url" />
			<label for="menu_element_{ID}_image">{L_IMAGE}</label> <input type="text" value="{IMG}" id="menu_element_{ID}_image" name="menu_element_{ID}_image" />
		</div>
		<div style="float:right;">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" alt="{L_MORE}" id="menu_element_{ID}_more_image" class="valign_middle" onclick="toggleProperties({ID});" />
            <img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" id="menu_element_{ID}_delete_image" class="valign_middle" onclick="deleteElement('menu_element_{ID}');" />
		</div>
		<div class="spacer"></div>
		<fieldset id="menu_element_{ID}_properties" style="display:none;">
			<legend>{L_PROPERTIES}</legend>
			<dl>
				<dt><label for="menu_element_{ID}_auth">{L_AUTHORIZATIONS}</label></dt>
				<dd>{AUTH_FORM}</dd>
			</dl>
		</fieldset>
    </li>
# ENDIF #