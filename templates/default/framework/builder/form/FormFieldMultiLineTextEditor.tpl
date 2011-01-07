<div id="${escape(ID)}_field">
	<label for="${escape(ID)}">
		# IF C_REQUIRED # * # ENDIF #
		{LABEL}
	</label>
	<span id="onblurContainerResponse${escape(ID)}"></span>
	<span style="font-weight:bold;display:none" id="onblurMesssageResponse${escape(ID)}"></span>
	# IF C_DESCRIPTION #
	<br />
	<span class="text_small">{DESCRIPTION}</span>
	# ENDIF #
	# IF C_EDITOR_ENABLED #
		{EDITOR}
	# ENDIF #
	<textarea id="${escape(ID)}" name="${escape(ID)}" rows="{ROWS}" cols="{COLS}" class="${escape(CLASS)}" onblur="{ONBLUR}"{DISABLED}>{VALUE}</textarea>
	# IF C_EDITOR_ENABLED #
		<div style="text-align:center;">{PREVIEW_BUTTON}</div>
	# ENDIF #
	<br />
</div>
		
# INCLUDE ADD_FIELD_JS #