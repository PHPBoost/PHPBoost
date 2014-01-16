# IF C_IS_ENABLED #
	<div id="${escape(ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element">
		<label for="${escape(ID)}">* {LABEL} # IF DESCRIPTION #<span class="field-description">{DESCRIPTION}</span> # ENDIF #</label>
		<div class="form-field">
			{CAPTCHA}
			<i class="fa picture-status-constraint" id="onblurContainerResponse${escape(ID)}"></i>
			<div class="text-status-constraint" style="display:none" id="onblurMessageResponse${escape(ID)}"></div>
		</div>
	</div>
	# INCLUDE ADD_FIELD_JS #
# ENDIF #