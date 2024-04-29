<script>
	function CheckForm() {
		if (jQuery('#name').val() == '') {
			jQuery('#name').focus();
			window.alert(${escapejs(@warning.title)});
			return false;
		}
		if (jQuery('#feed_url').val() == "null" || jQuery('#feed_url').val() == '') {
			jQuery('#feed_url').focus();
			window.alert(${escapejs(@menu.warning.feed)});
			return false;
		}
		if (jQuery('#items_number').val() == '' || jQuery('#items_number').val() == 0) {
			jQuery('#items_number').focus();
			window.alert(${escapejs(@warning.items.number)});
			return false;
		}
		return true;
	}
</script>
<div id="admin-contents">
	<form action="feed.php" method="post" class="fieldset-content" onsubmit="return CheckForm();">
		<p class="align-center">{@form.required.fields}</p>
		<fieldset>
			<legend># IF C_EDIT #{@menu.feed.edit}# ELSE #{@menu.feed.add}# ENDIF #</legend>
			<div class="fieldset-inset">
				<div class="form-element">
					<label for="name">* {@common.title}</label>
					<div class="form-field"><input type="text" name="name" id="name" value="{NAME}"></div>
				</div>
				<div class="form-element">
					<label for="location">{@menu.location}</label>
					<div class="form-field"><select name="location" id="location">{LOCATIONS}</select></div>
				</div>
				<div class="form-element">
					<label for="feed_url">* {@menu.module.feed}</label>
					<div class="form-field"><select name="feed_url" id="feed_url">
						<option value="null" class="feed-option-title"# IF C_NEW # selected="selected"# ENDIF #>
							{@menu.available.feeds}
						</option>
						<option value="null" class="align-center"></option>
						# START modules #
							<optgroup label="{modules.NAME}">
							# START modules.feeds_urls #
								<option value="{modules.feeds_urls.URL}"{modules.feeds_urls.SELECTED}>
									{modules.feeds_urls.SPACE} {modules.feeds_urls.NAME}
									# IF modules.feeds_urls.FEED_NAME #({modules.feeds_urls.FEED_NAME})# ENDIF #
								</option>
							# END modules.feeds_urls #
							</optgroup>
							<option value="null" class="align-center">-----------------------------</option>
						# END modules #
					</select></div>
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
				<div class="form-element">
					<label for="items_number">* {@common.items.number}</label>
					<div class="form-field"><input type="number" min="1" max="500" name="items_number" id="items_number" value="{ITEMS_NUMBER}"></div>
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
					<label>{@form.authorizations.read}</label>
					<div class="form-field">{AUTH_MENUS}</div>
				</div>
			</div>

		</fieldset>

		# INCLUDE FILTERS #

		<fieldset class="fieldset-submit">
			<legend>{@form.submit}</legend>
			<input type="hidden" name="action" value="{ACTION}">
			# IF C_EDIT #<input type="hidden" name="id" value="{MENU_ID}"># ENDIF #
			<button type="submit" class="button submit" name="valid" value="true">{@form.submit}</button>
			<input type="hidden" name="token" value="{TOKEN}">
		</fieldset>
	</form>
</div>
