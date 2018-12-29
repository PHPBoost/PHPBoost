# IF C_FIRST_MENU #
	<div id="menu_element_{ID}">
		<input type="hidden" id="menu_uid" name="menu_uid" value="{ID}">
		<ul id="menu_element_{ID}_list" class="sortable-block">
			# START elements #
				{elements.DISPLAY}
			# END elements #
		</ul>
		<fieldset class="fieldset-submit">
			<button type="button" id="menu_element_{ID}_add_sub_element" name="menu_element_{ID}_add_sub_element" onclick="addSubElement('menu_element_{ID}');">{L_ADD_SUB_ELEMENT}</button>
			<button type="button" id="menu_element_{ID}_add_sub_menu" name="menu_element_{ID}_add_sub_menu" onclick="addSubMenu('menu_element_{ID}');">{L_ADD_SUB_MENU}</button>
		</fieldset>
	</div>
# ENDIF #

# IF C_NEXT_MENU #
	<li class="sortable-element" id="menu_element_{ID}" data-id="{ID}">
		<div class="sortable-selector" aria-label="${LangLoader::get_message('position.move', 'common')}" title="${LangLoader::get_message('position.move', 'common')}"></div>
		<div class="sortable-title">
			<i class="fa fa-folder" aria-label="${LangLoader::get_message('sub.menu', 'admin')}" style="cursor:move"></i>
			<label for="menu_element_{ID}_name">{L_NAME}</label> <input type="text" value="{TITLE}" id="menu_element_{ID}_name" name="menu_element_{ID}_name">
			<label for="menu_element_{ID}_url">{L_URL}</label> <input type="text" value="{RELATIVE_URL}" id="menu_element_{ID}_url" name="menu_element_{ID}_url">
			<label for="menu_element_{ID}_image">{L_IMAGE}</label> <input type="text" value="{RELATIVE_IMG}" id="menu_element_{ID}_image" name="menu_element_{ID}_image" onblur="image_preview(this,menu_element_{ID}_image_preview)">
			<script>
			<!--
				jQuery(document).ready(function() {
					image_preview(jQuery('#menu_element_{ID}_image').val(), menu_element_{ID}_image_preview, true);
				});
			-->
			</script>
			<span class="preview"><img src="# IF C_IMG #{REL_IMG}# ENDIF #" id="menu_element_{ID}_image_preview" alt="{TITLE}" title="{TITLE}" /></span>
		</div>
		<div class="sortable-actions">
			<a href="" id="menu_element_{ID}_more_image" onclick="toggleProperties({ID});return false;" aria-label="{L_MORE}"><i class="fa fa-cog" aria-hidden="true" title="{L_MORE}"></i></a>
			<a href="" id="menu_element_{ID}_delete_image" style="cursor:pointer;" onclick="deleteElement('menu_element_{ID}');return false;" aria-label="{L_DELETE}"><i class="fa fa-delete" aria-hidden="true" title="{L_DELETE}"></i></a>
		</div>
		<div class="spacer"></div>
		<fieldset id="menu_element_{ID}_properties"# IF C_AUTH_MENU_HIDDEN # style="display: none;"# ENDIF #>
			<legend>{L_PROPERTIES}</legend>
			<div class="fieldset-inset">
				<div class="form-element full-field">
					<label>{L_AUTHORIZATIONS}</label>
					<div class="form-field">{AUTH_FORM}</div>
				</div>
			</div>
		</fieldset>
		<hr/>
		<ul class="sortable-block" id="menu_element_{ID}_list">
			# START elements #
				{elements.DISPLAY}
			# END elements #
		</ul>
		<fieldset class="fieldset-submit">
			<button type="button" id="menu_element_{ID}_add_sub_element" name="menu_element_{ID}_add_sub_element" onclick="addSubElement('menu_element_{ID}');">{L_ADD_SUB_ELEMENT}</button>
			<button type="button" id="menu_element_{ID}_add_sub_menu" name="menu_element_{ID}_add_sub_menu" onclick="addSubMenu('menu_element_{ID}');">{L_ADD_SUB_MENU}</button>
		</fieldset>
	</li>
# ENDIF #

# IF C_LINK #
	<li class="sortable-element" id="menu_element_{ID}" data-id="{ID}">
		<div class="sortable-selector" title="${LangLoader::get_message('position.move', 'common')}"></div>
		<div class="sortable-title">
			<i class="fa fa-globe" aria-label="${LangLoader::get_message('menu.element', 'admin')}"></i>
			<label for="menu_element_{ID}_name">{L_NAME}</label> <input type="text" value="{TITLE}" id="menu_element_{ID}_name" name="menu_element_{ID}_name">
			<label for="menu_element_{ID}_url">{L_URL}</label> <input type="text" value="{RELATIVE_URL}" id="menu_element_{ID}_url" name="menu_element_{ID}_url">
			<label for="menu_element_{ID}_image">{L_IMAGE}</label> <input type="text" value="{RELATIVE_IMG}" id="menu_element_{ID}_image" name="menu_element_{ID}_image" onblur="image_preview(this,menu_element_{ID}_image_preview)">
			<script>
			<!--
				jQuery(document).ready(function() {
					image_preview(jQuery('#menu_element_{ID}_image').val(), menu_element_{ID}_image_preview, true);
				});
			-->
			</script>
			<span class="preview"><img src="# IF C_IMG #{REL_IMG}# ENDIF #" id="menu_element_{ID}_image_preview" alt="{TITLE}" title="{TITLE}" /></span>
		</div>
		<div class="sortable-actions">
			<a href="" id="menu_element_{ID}_more_image" onclick="toggleProperties({ID});return false;" aria-label="{L_MORE}"><i class="fa fa-cog" aria-hidden="true" title="{L_MORE}"></i></a>
			<a href="" id="menu_element_{ID}_delete_image" style="cursor:pointer;" onclick="deleteElement('menu_element_{ID}');return false;" aria-label="{L_DELETE}"><i class="fa fa-delete" aria-hidden="true" title="{L_DELETE}"></i></a>
		</div>
		<div class="spacer"></div>
		<fieldset id="menu_element_{ID}_properties"# IF C_AUTH_MENU_HIDDEN # style="display: none;"# ENDIF #>
			<legend>{L_PROPERTIES}</legend>
			<div class="fieldset-inset">
				<div class="form-element full-field">
					<label>{L_AUTHORIZATIONS}</label>
					<div class="form-field">{AUTH_FORM}</div>
				</div>
			</div>

		</fieldset>
	</li>
# ENDIF #
