# INCLUDE MODULE_CHOICE_FORM #
<div id="comments_list">
	# IF C_PAGINATION #
	<div style="text-align:center;">
		# INCLUDE PAGINATION #
	</div>
	# ENDIF #
	# INCLUDE COMMENTS #
	# IF C_NO_COMMENT #
		<div style="text-align:center;">
			{@no_comment}
		</div>
		<div class="spacer"></div>
	# ELSE #
		# IF C_PAGINATION #
		<div style="text-align:center;">
			# INCLUDE PAGINATION #
		</div>
		# ENDIF #
	# ENDIF #
</div>