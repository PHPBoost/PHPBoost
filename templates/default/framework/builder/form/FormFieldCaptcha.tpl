# IF C_IS_ENABLED #
<dl id="${escape(ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF #>
	<dt>
		<label for="${escape(ID)}">* {LABEL}</label>
		# IF DESCRIPTION # <br /><span class="text_small">{DESCRIPTION}</span> # ENDIF #
	</dt>
	<dd>
		{CAPTCHA}
		<span id="onblurContainerResponse{ID}"></span>
		<span style="font-weight:bold;display:none" id="onblurMesssageResponse{ID}"></span>
	</dd>
</dl>
# INCLUDE ADD_FIELD_JS #

# ENDIF #