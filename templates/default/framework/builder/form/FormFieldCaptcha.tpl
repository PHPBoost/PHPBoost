# IF C_IS_ENABLED #
	<div id="${escape(HTML_ID)}_field"# IF C_HIDDEN # style="display: none;"# ENDIF # class="form-element">
		# IF C_IS_VISIBLE #<label for="${escape(HTML_ID)}">* {LABEL} # IF DESCRIPTION #<span class="field-description">{DESCRIPTION}</span> # ENDIF #</label># ENDIF #
		<div class="form-field">
			{CAPTCHA}
		</div>
	</div>
	# INCLUDE ADD_FIELD_JS #
# ENDIF #