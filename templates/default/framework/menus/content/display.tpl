# IF C_VERTICAL_BLOCK #
<div class="module-mini-container">
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
<div class="block-container">
	<div class="block-contents">
		# IF C_DISPLAY_TITLE #<h5 class="sub-title">{TITLE}</h5># ENDIF #
		<div style="text-align:justify; padding-top:5px;">{CONTENT}</div>
		&nbsp;
	</div>
</div>
# ENDIF #
