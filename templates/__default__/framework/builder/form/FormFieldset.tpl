# INCLUDE ADD_FIELDSET_JS #
<fieldset id="${escape(HTML_ID)}"# IF CSS_CLASS # class="{CSS_CLASS}"# ENDIF ## IF C_DISABLED # style="display: none;"# ENDIF #>
	# IF C_TITLE #<legend>{L_TITLE}</legend># ENDIF #
    # IF C_DESCRIPTION #<div class="fieldset-description">{DESCRIPTION}</div># ENDIF #
	<div class="fieldset-inset">
		# START elements #
			# INCLUDE elements.ELEMENT #
		# END elements #
	</div>
</fieldset>
