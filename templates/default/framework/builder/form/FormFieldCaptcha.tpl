
<div id="${escape(ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF # class="field">
	<label for="${escape(ID)}">* {LABEL} # IF DESCRIPTION #<span class="field-description">{DESCRIPTION}</span> # ENDIF #</label>
	<div class="field-value">
		{CAPTCHA}
		<span id="onblurContainerResponse{ID}"></span>
		<span style="font-weight:bold;display:none" id="onblurMesssageResponse{ID}"></span>
	</div>
</div>
# INCLUDE ADD_FIELD_JS #

