# IF C_VERTICAL_BLOCK #
<div class="module-mini-container# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
	<div class="module-mini-top">
		# IF C_DISPLAY_TITLE #<h5 class="sub-title">{TITLE}</h5># ENDIF #
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
		# IF C_DISPLAY_TITLE #<h5 class="sub-title">{TITLE}</h5># ENDIF #
		<div>{CONTENT}</div>
		&nbsp;
	</div>
</div>
# ENDIF #
