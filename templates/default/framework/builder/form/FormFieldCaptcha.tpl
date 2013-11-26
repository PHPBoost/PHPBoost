# IF C_IS_ENABLED #
	<div id="${escape(ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element">
		<label for="${escape(ID)}">* {LABEL} # IF DESCRIPTION #<span class="field-description">{DESCRIPTION}</span> # ENDIF #</label>
		<div class="form-field">
			{CAPTCHA}
			<span id="onblurContainerResponse{ID}"></span>
			<span style="font-weight:bold;display:none" id="onblurMessageResponse{ID}"></span>
		</div>
	</div>
	# INCLUDE ADD_FIELD_JS #
# ENDIF #