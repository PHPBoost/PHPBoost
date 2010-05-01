	# INCLUDE ADD_FIELDSET_JS #
	<fieldset id="{E_ID}" # IF C_DISABLED # style="display:none;" # ENDIF #>
		<legend>{L_FORMTITLE}</legend>
        # IF C_DESCRIPTION #<p>{E_DESCRIPTION}</p># ENDIF #
		# START fields #
			# INCLUDE fields.FIELD #
		# END fields #
	</fieldset>
