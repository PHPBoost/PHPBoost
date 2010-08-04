<form action="{U_VALID}" method="post">
	<fieldset>
		<legend>{EL_MENU_CONFIGURATION_EDITION}</legend>
		<dl>
			<dt><label for="menu_config_name">* {EL_MENU_CONFIGURATION_EDITION_NAME}</label></dt>
			<dd><label>
				<input type="text" id="menu_config_name"	name="menu_config_name" value="{E_NAME}" class="text" />
			</label></dd>
		</dl>
		<dl>
			<dt><label for="menu_config_match_regex">* {EL_MENU_CONFIGURATION_EDITION_MATCH_REGEX}</label></dt>
			<dd><label>
				<input type="text" id="menu_config_match_regex" name="menu_config_match_regex" value="{E_MATCH_REGEX}" class="text" />
			</label></dd>
		</dl>
	</fieldset>

	<fieldset class="fieldset_submit">
		<legend>{EL_SUBMIT}</legend>
		<input type="submit" name="valid" value="{EL_SUBMIT}" class="submit" />
		<input type="reset" value="{EL_RESET}" class="reset" />
		<input type="hidden" name="token" value="{TOKEN}" />
	</fieldset>
</form>
<span>
	<a href="{U_LIST}" title="{EL_MENU_CONFIGURATIONS_LIST}">{EL_MENU_CONFIGURATIONS_LIST}</a>
	- <a href="{U_CONFIGURE}" title="{EL_MENU_CONFIGURATION_CONFIGURE}">{EL_MENU_CONFIGURATION_CONFIGURE}</a>
</span>
