<div id="{E_ID}_field">
	<label for="{E_ID}">
		# IF C_REQUIRED # * # ENDIF #
		{LABEL}
	</label>
	<span id="onblurContainerResponse{E_ID}"></span>
	<span style="font-weight:bold;display:none" id="onblurMesssageResponse{E_ID}"></span>
	# IF C_DESCRIPTION #
	<br />
	<span class="text_small">{DESCRIPTION}</span>
	# ENDIF #
	# IF C_EDITOR_ENABLED #
		{EDITOR}
	# ENDIF #
	<textarea id="{E_ID}" name="{E_ID}" rows="{ROWS}" cols="{COLS}" class="{E_CLASS}" onblur="{ONBLUR}"{DISABLED}>{VALUE}</textarea>
	# IF C_EDITOR_ENABLED #
		<div style="text-align:center;">{PREVIEW_BUTTON}</div>
		<br />
	# ENDIF #
</div>
		
# INCLUDE ADD_FIELD_JS #