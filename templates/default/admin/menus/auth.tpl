<div id="admin-contents">
	<form action="auth.php" method="post" class="fieldset-content">
		<fieldset>
			<legend>{L_ACTION_MENUS}</legend>
			<div class="fieldset-inset">
				<div class="form-element">
					<label>{L_NAME}</label>
					<div class="form-field"><label>{NAME}</label></div>
				</div>
				<div class="form-element">
					<label for="location">{L_LOCATION}</label>
					<div class="form-field"><select name="location" id="location">{LOCATIONS}</select></div>
				</div>
				<div class="form-element">
					<label for="activ">{L_STATUS}</label>
					<div class="form-field"><label>
						<select name="activ" id="activ">
							<option value="1"# IF C_ENABLED # selected="selected"# ENDIF #>{L_ENABLED}</option>
							<option value="0"# IF NOT C_ENABLED # selected="selected"# ENDIF #>{L_DISABLED}</option>
						</select>
					</label></div>
				</div>
				<div class="form-element custom-checkbox">
					<label for="hidden_with_small_screens">{L_HIDDEN_WITH_SMALL_SCREENS}</label>
					<div class="form-field">
						<div class="form-field-checkbox">
							<label class="checkbox" for="hidden_with_small_screens">
								<input type="checkbox" name="hidden_with_small_screens" id="hidden_with_small_screens"# IF C_MENU_HIDDEN_WITH_SMALL_SCREENS # checked="checked"# ENDIF # />
								<span>&nbsp;</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element full-field">
					<label for="auth">{L_AUTHS}</label>
					<div class="form-field"><label>{AUTH_MENUS}</label></div>
				</div>
			</div>

		</fieldset>

		# INCLUDE filters #

		<fieldset class="fieldset-submit">
			<legend>{L_ACTION}</legend>
			<input type="hidden" name="action" value="{ACTION}">
			<input type="hidden" name="id" value="{IDMENU}">
			<button type="submit" class="button submit" name="valid" value="true">{L_ACTION}</button>
			<button type="reset" class="button reset" value="true">{L_RESET}</button>
			<input type="hidden" name="token" value="{TOKEN}">
		</fieldset>
	</form>
</div>
