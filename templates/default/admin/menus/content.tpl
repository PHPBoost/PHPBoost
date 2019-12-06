<script>
<!--
function CheckForm() {
	if (document.getElementById('name').value == '') {
		document.getElementById('name').select();
		window.alert({L_REQUIRE_NAME});
		return false;
	}
	if (document.getElementById('contents').value == '') {
		document.getElementById('contents').select();
		window.alert({L_REQUIRE_TEXT});
		return false;
	}
	return true;
}
-->
</script>
<div id="admin-contents">
	<form action="content.php" method="post" onsubmit="return CheckForm();" class="fieldset-content">
		<p class="align-center">{L_REQUIRE}</p>
			<fieldset>
			<legend>{L_ACTION_MENUS}</legend>
			<div class="fieldset-inset">
				<div class="form-element third-field">
					<label for="name">* {L_NAME}</label>
					<div class="form-field"><input type="text" name="name" id="name" value="{NAME}"></div>
				</div>
				<div class="form-element third-field">
					<label for="location">{L_LOCATION}</label>
					<div class="form-field"><select name="location" id="location">{LOCATIONS}</select></div>
				</div>
				<div class="form-element third-field">
					<label for="activ">{L_STATUS}</label>
					<div class="form-field">
						<select name="activ" id="activ">
							<option value="1"# IF C_ENABLED # selected="selected"# ENDIF #>{L_ENABLED}</option>
							<option value="0"# IF NOT C_ENABLED # selected="selected"# ENDIF #>{L_DISABLED}</option>
						</select>
					</div>
				</div>
				<div class="form-element third-field custom-checkbox">
					<label for="display_title">{L_DISPLAY_TITLE}</label>
					<div class="form-field">
						<div class="form-field-checkbox">
							<label class="checkbox" for="display_title">
								<input type="checkbox" id="display_title" name="display_title" value="display_title" {DISPLAY_TITLE_CHECKED} />
								<span>&nbsp;</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element third-field custom-checkbox">
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
				<div class="form-element form-element-textarea">
					<label for="contents" id="preview_description">* {L_CONTENT}</label>
					{KERNEL_EDITOR}
					<div class="form-field-textarea">
						<textarea rows="15" id="contents" name="contents">{CONTENTS}</textarea>
					</div>
					<div class="align-center"><button type="button" class="button small" onclick="XMLHttpRequest_preview(); return false;" value="true">{L_PREVIEW}</button></div>
				</div>
				<div class="form-element full-field">
					<label>{L_AUTHS}</label>
					<div class="form-field">{AUTH_MENUS}</div>
				</div>
			</div>

		</fieldset>

		# INCLUDE filters #

		<fieldset class="fieldset-submit">
			<legend>{L_ACTION}</legend>
			<input type="hidden" name="action" value="{ACTION}">
			<input type="hidden" name="id" value="{IDMENU}">
			<button type="submit" class="button submit" name="valid" value="true">{L_ACTION}</button>
			<input type="hidden" name="token" value="{TOKEN}">
		</fieldset>
	</form>
</div>
