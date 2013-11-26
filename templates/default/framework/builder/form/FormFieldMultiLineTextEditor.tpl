<div id="${escape(ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element-textarea">
	<label for="${escape(ID)}">
		# IF C_REQUIRED # * # ENDIF #
		{LABEL}
		# IF C_DESCRIPTION #<span class="field-description">{DESCRIPTION}</span># ENDIF #
	</label>
	<span id="onblurContainerResponse${escape(ID)}"></span>
	<span style="font-weight:bold;display:none" id="onblurMessageResponse${escape(ID)}"></span>
	# IF C_EDITOR_ENABLED #
		{EDITOR}
	# ENDIF #
	<textarea id="${escape(ID)}" name="${escape(ID)}" rows="{ROWS}" cols="{COLS}" class="# IF C_READONLY #low_opacity # ENDIF #${escape(CLASS)}" onblur="{ONBLUR}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #>{VALUE}</textarea>
	# IF C_EDITOR_ENABLED #
		<div style="text-align:center;">{PREVIEW_BUTTON}</div>
	# ENDIF #
	<br />
</div>
		
# INCLUDE ADD_FIELD_JS #