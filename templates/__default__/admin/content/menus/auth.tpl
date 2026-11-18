<div id="admin-contents">
	<form action="auth.php" method="post" class="fieldset-content">
		<fieldset>
			<legend>{@menu.edit.mini}</legend>
			<div class="fieldset-inset">
				<div class="form-element">
					<label>{@common.name}</label>
					<div class="form-field"><label>{NAME}</label></div>
				</div>
				<div class="form-element">
					<label for="location">{@menu.location}</label>
					<div class="form-field"><select name="location" id="location">{LOCATIONS}</select></div>
				</div>
				<div class="form-element">
					<label for="activ">{@common.status}</label>
					<div class="form-field">
						<select name="activ" id="activ">
							<option value="1"# IF C_ENABLED # selected="selected"# ENDIF #>{@common.enabled}</option>
							<option value="0"# IF NOT C_ENABLED # selected="selected"# ENDIF #>{@common.disabled}</option>
						</select>
					</div>
				</div>
				<div class="form-element custom-checkbox">
					<label for="hidden_with_small_screens">{@menu.hidden.on.small.screens}</label>
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
					<label for="auth">{@form.authorizations.read}</label>
					<div class="form-field"><label>{AUTH_MENUS}</label></div>
				</div>
			</div>

		</fieldset>

		# INCLUDE FILTERS #

		<fieldset class="fieldset-submit">
			<legend>{@form.submit}</legend>
			<input type="hidden" name="action" value="{ACTION}">
			<input type="hidden" name="id" value="{MENU_ID}">
			<button type="submit" class="button submit" name="valid" value="true">{@form.submit}</button>
			<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
			<input type="hidden" name="token" value="{TOKEN}">
		</fieldset>
	</form>
</div>
