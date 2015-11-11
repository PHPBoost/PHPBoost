# INCLUDE MODULE_CHOICE_FORM #
<div>
	# IF C_PAGINATION #
	<div class="center">
		# INCLUDE PAGINATION #
	</div>
	# ENDIF #
	# INCLUDE COMMENTS #
	# IF C_NO_COMMENT #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		<div class="spacer"></div>
	# ELSE #
		# IF C_PAGINATION #
		<div class="center">
			# INCLUDE PAGINATION #
		</div>
		# ENDIF #
	# ENDIF #
</div>
