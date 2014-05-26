# INCLUDE ADD_FIELDSET_JS #
<fieldset id="${escape(HTML_ID)}" # IF C_DISABLED # style="display:none;" # ENDIF # # IF CSS_CLASS # class="{CSS_CLASS}" # ENDIF #>
		# IF C_TITLE #<legend>{L_TITLE}</legend># ENDIF #
        # IF C_DESCRIPTION #<p class="fieldset-description">{DESCRIPTION}</p># ENDIF #
	# START elements #
		# INCLUDE elements.ELEMENT #
	# END elements #
</fieldset>
