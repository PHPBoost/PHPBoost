<div id="${escape(ID)}_field" # IF C_DISABLED # style="display:none;" # ENDIF #>
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
	<textarea id="${escape(ID)}" name="${escape(ID)}" rows="{ROWS}" cols="{COLS}" class="# IF C_READONLY #low_opacity # ENDIF #${escape(CLASS)}" onblur="{ONBLUR}"{DISABLED}{READONLY}>{VALUE}</textarea>
	# IF C_EDITOR_ENABLED #
		<div style="text-align:center;">{PREVIEW_BUTTON}</div>
	# ENDIF #
	<br />
</div>
		
# INCLUDE ADD_FIELD_JS #