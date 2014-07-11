<div id="${escape(HTML_ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element-textarea">
	<label for="${escape(HTML_ID)}">
		# IF C_REQUIRED # * # ENDIF #
		{LABEL}
		# IF C_DESCRIPTION #<span class="field-description">{DESCRIPTION}</span># ENDIF #
	</label>
	<i class="fa picture-status-constraint" id="onblurContainerResponse${escape(HTML_ID)}"></i>
	<div class="text-status-constraint" style="display:none" id="onblurMessageResponse${escape(HTML_ID)}"></div>
	# IF C_EDITOR_ENABLED #
		{EDITOR}
	# ENDIF #
	<textarea id="${escape(HTML_ID)}" name="${escape(HTML_ID)}" rows="{ROWS}" cols="{COLS}" class="# IF C_READONLY #low-opacity # ENDIF #${escape(CLASS)}" onblur="{ONBLUR}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #>{VALUE}</textarea>
	# IF C_EDITOR_ENABLED #
		<div class="center" style="margin-top:5px;">{PREVIEW_BUTTON}</div>
	# ENDIF #
</div>
		
# INCLUDE ADD_FIELD_JS #