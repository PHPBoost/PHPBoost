# INCLUDE MSG #
# INCLUDE MODULE_CHOICE_FORM #
<div>
	# IF C_COMMENTS #
	<form method="post" class="fieldset-content">
		# IF C_PAGINATION #
		<div class="align-center">
			# INCLUDE PAGINATION #
		</div>
		# ENDIF #
		# INCLUDE COMMENTS #
		# IF C_DISPLAY_DELETE_BUTTON #
		<label for="delete-all-checkbox" class="checkbox">
			<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {COMMENTS_NUMBER});" aria-label="{@select.all.comments}">
		</label>
		<input type="hidden" name="token" value="{TOKEN}" />
		<button type="submit" id="delete-all-button" name="delete-selected-comments" value="true" class="button submit" data-confirmation="delete-element" disabled="disabled">${LangLoader::get_message('delete', 'common')}</button>
		# ENDIF #
		# IF C_PAGINATION #
		<div class="align-center">
			# INCLUDE PAGINATION #
		</div>
		# ENDIF #
	</form>
	# ELSE #
		<div class="align-center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
	# ENDIF #
</div>
