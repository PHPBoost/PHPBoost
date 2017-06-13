# IF C_VERTICAL_BLOCK #
<div class="module-mini-container# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
	<div class="module-mini-top">
		# IF C_DISPLAY_TITLE #<div class="sub-title">{TITLE}</div># ENDIF #
	</div>
	<div class="module-mini-contents">
		{CONTENT}
	</div>
	<div class="module-mini-bottom">
	</div>
</div>
# ELSE #
<div class="block-container# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
	<div class="block-contents">
		# IF C_DISPLAY_TITLE #<div class="sub-title">{TITLE}</div># ENDIF #
		<div>{CONTENT}</div>
	</div>
</div>
# ENDIF #
