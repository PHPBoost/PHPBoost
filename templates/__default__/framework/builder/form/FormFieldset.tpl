# INCLUDE ADD_FIELDSET_JS #
<fieldset id="${escape(HTML_ID)}"class="sub-section# IF CSS_CLASS # {CSS_CLASS}# ENDIF #"# IF C_DISABLED # style="display: none;"# ENDIF #>
	# IF C_TITLE #<legend>{L_TITLE}</legend># ENDIF #
	<div class="fieldset-inset">
		# IF C_DESCRIPTION #<p class="fieldset-description">{DESCRIPTION}</p># ENDIF #
		# START elements #
			# INCLUDE elements.ELEMENT #
		# END elements #
	</div>
</fieldset>
