	# INCLUDE ADD_FIELDSET_JS #
	<fieldset id="${escape(ID)}" # IF C_DISABLED # style="display:none;" # ENDIF # # IF CSS_CLASS # class="{CSS_CLASS}" # ENDIF #>
		<legend>{L_FORMTITLE}</legend>
        # IF C_DESCRIPTION #<p style="margin-bottom:15px">${escape(DESCRIPTION)}</p># ENDIF #
		# START elements #
			# INCLUDE elements.ELEMENT #
		# END elements #
	</fieldset>
