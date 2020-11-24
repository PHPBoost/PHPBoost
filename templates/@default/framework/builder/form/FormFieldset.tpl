# INCLUDE ADD_FIELDSET_JS #
<fieldset id="${escape(HTML_ID)}"# IF CSS_CLASS # class="{CSS_CLASS}"# ENDIF ## IF C_DISABLED # style="display: none;"# ENDIF #>
	# IF C_TITLE #<legend># IF C_HEADING #<h1># ENDIF #{L_TITLE}# IF C_HEADING #</h1># ENDIF #</legend># ENDIF #
	<div class="fieldset-inset">
		# IF C_DESCRIPTION #<p class="fieldset-description">{DESCRIPTION}</p># ENDIF #
		# START elements #
			# INCLUDE elements.ELEMENT #
		# END elements #
	</div>
</fieldset>
