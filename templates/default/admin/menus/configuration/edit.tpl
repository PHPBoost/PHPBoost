<form action="{U_VALID}" method="post">
	<fieldset>
		<legend>{EL_MENU_CONFIGURATION_EDITION}</legend>
		<div class="fieldset-inset">
			<div class="form-element">
				<label for="menu_config_name">* {EL_MENU_CONFIGURATION_EDITION_NAME}</label>
				<div class="form-field">
					<label><input type="text" id="menu_config_name" name="menu_config_name" value="${escape(NAME)}" /></label>
				</div>
			</div>
			<div class="form-element">
				<label for="menu_config_match_regex">* {EL_MENU_CONFIGURATION_EDITION_MATCH_REGEX}</label>
				<div class="form-field">
					<label><input type="text" id="menu_config_match_regex" name="menu_config_match_regex" value="${escape(MATCH_REGEX)}" /></label>
				</div>
			</div>
		</div>
	</fieldset>

	<fieldset class="fieldset-submit">
		<legend>{EL_SUBMIT}</legend>
		<button type="submit" class="button submit" name="valid" value="true">{EL_SUBMIT}</button>
		<button type="reset" class="button reset" value="true">{EL_RESET}</button>
		<input type="hidden" name="token" value="{TOKEN}">
	</fieldset>
</form>
<span>
	<a href="{U_LIST}">{EL_MENU_CONFIGURATIONS_LIST}</a>
	- <a href="{U_CONFIGURE}">{EL_MENU_CONFIGURATION_CONFIGURE}</a>
</span>
