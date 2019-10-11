# INCLUDE ADD_FIELDSET_JS #
<div id="${escape(HTML_ID)}"# IF CSS_CLASS # class="{CSS_CLASS}"# ENDIF ## IF C_DISABLED # style="display: none;"# ENDIF #>
	# IF C_TITLE #<h2>{L_TITLE}</h2># ENDIF #
	<div class="content-panel fieldset-inset">
		# IF C_DESCRIPTION #<p class="fieldset-description">{DESCRIPTION}</p># ENDIF #
		# START elements #
			# INCLUDE elements.ELEMENT #
		# END elements #
	</div>
</div>
