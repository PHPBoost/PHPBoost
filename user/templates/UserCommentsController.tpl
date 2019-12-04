# INCLUDE MSG #
# INCLUDE MODULE_CHOICE_FORM #
<div>
	# IF C_PAGINATION #
	<div class="align-center">
		# INCLUDE PAGINATION #
	</div>
	# ENDIF #
	<form id="comments_list_form" method="post" class="fieldset-content">
	# INCLUDE COMMENTS #
	# IF C_NO_COMMENT #
		<div class="align-center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		<div class="spacer"></div>
	# ELSE #
		# IF C_MODERATOR #
		<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {COMMENTS_NUMBER});" aria-label="{@select.all.comments}"><input type="hidden" name="token" value="{TOKEN}" /><button type="submit" name="delete-selected-comments" value="true" class="submit" data-confirmation="delete-elements">${LangLoader::get_message('delete', 'common')}</button>
		# ENDIF #
		# IF C_PAGINATION #
		<div class="align-center">
			# INCLUDE PAGINATION #
		</div>
		# ENDIF #
	# ENDIF #
	</form>
</div>
