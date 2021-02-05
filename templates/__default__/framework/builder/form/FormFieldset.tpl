# INCLUDE ADD_FIELDSET_JS #
# IF C_TITLE ## IF C_HEADING #<header class="section-header"><h1>{L_TITLE}</h1></header># ENDIF ## ENDIF #
<fieldset id="${escape(HTML_ID)}"class="sub-section# IF CSS_CLASS # {CSS_CLASS}# ENDIF #"# IF C_DISABLED # style="display: none;"# ENDIF #>
	# IF C_TITLE ## IF NOT C_HEADING #<legend>{L_TITLE}</legend># ENDIF ## ENDIF #
	<div class="fieldset-inset">
		# IF C_DESCRIPTION #<p class="fieldset-description">{DESCRIPTION}</p># ENDIF #
		# START elements #
			# INCLUDE elements.ELEMENT #
		# END elements #
	</div>
</fieldset>
