# INCLUDE MODULE_CHOICE_FORM #
<div id="comments_list">
	# IF NOT C_NO_COMMENT #
	<div style="text-align:center;">
		{PAGINATION}
	</div>
	# INCLUDE COMMENTS #
	# ENDIF #
	# IF C_NO_COMMENT #
		<div style="text-align:center;">
			{@no_comment}
		</div>
		<div class="spacer"></div>
	# ENDIF #
	# IF NOT C_NO_COMMENT #
	<div style="text-align:center;">
		{PAGINATION}
	</div>
	# ENDIF #
</div>