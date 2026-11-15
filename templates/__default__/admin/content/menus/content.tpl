<script>
	function CheckForm() {
		if (document.getElementById('name').value == '') {
			document.getElementById('name').select();
			window.alert(${escapejs(@warning.title)});
			return false;
		}
		if (document.getElementById('contents').value == '') {
			document.getElementById('contents').select();
			window.alert(${escapejs(@warning.text)});
			return false;
		}
		return true;
	}
</script>
<div id="admin-contents">
	<form action="content.php" method="post" onsubmit="return CheckForm();" class="fieldset-content">
		<p class="align-center text-italic small">{@form.required.fields}</p>
			<fieldset>
			<legend># IF C_EDIT #{@menu.content.edit}# ELSE #{@menu.content.add}# ENDIF #</legend>
			<div class="fieldset-inset">
				<div class="form-element third-field">
					<label for="name">* {@common.title}</label>
					<div class="form-field"><input type="text" name="name" id="name" value="{TITLE}"></div>
				</div>
				<div class="form-element third-field">
					<label for="location">{@menu.location}</label>
					<div class="form-field"><select name="location" id="location">{LOCATIONS}</select></div>
				</div>
				<div class="form-element third-field">
					<label for="activ">{@common.status}</label>
					<div class="form-field">
						<select name="activ" id="activ">
							<option value="1"# IF C_ENABLED # selected="selected"# ENDIF #>{@common.enabled}</option>
							<option value="0"# IF NOT C_ENABLED # selected="selected"# ENDIF #>{@common.disabled}</option>
						</select>
					</div>
				</div>
				<div class="form-element third-field custom-checkbox">
					<label for="display_title">{@menu.display.title}</label>
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
				<div class="form-element form-element-textarea">
					<label for="contents" id="preview_description">* {@common.content}</label>
					<div class="form-field form-field-textarea bbcode-sidebar">
						{KERNEL_EDITOR}
						<textarea class="auto-resize" id="contents" name="contents">{CONTENTS}</textarea>
					</div>
					<button type="button" class="button preview-button" onclick="XMLHttpRequest_preview(); return false;" value="true">{@form.preview}</button>
				</div>
				<div class="form-element full-field">
					<label>{@form.authorizations.read}</label>
					<div class="form-field">{AUTH_MENUS}</div>
				</div>
			</div>

		</fieldset>

		# INCLUDE FILTERS #

		<fieldset class="fieldset-submit">
			<legend>{@form.submit}</legend>
			<input type="hidden" name="action" value="{ACTION}">
			<input type="hidden" name="id" value="{MENU_ID}">
			<button type="submit" class="button submit" name="valid" value="true">{@form.submit}</button>
			<input type="hidden" name="token" value="{TOKEN}">
		</fieldset>
	</form>
</div>
