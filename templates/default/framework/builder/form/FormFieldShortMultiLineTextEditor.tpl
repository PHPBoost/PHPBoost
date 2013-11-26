<div id="${escape(ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element">
	<label for="${escape(ID)}">
		# IF C_REQUIRED # * # ENDIF #
		{LABEL}
		# IF C_DESCRIPTION #<span class="field-description">{DESCRIPTION}</span># ENDIF #
		# IF C_HAS_CONSTRAINTS #
		&nbsp;
		<span style="display:none" id="onblurContainerResponse${escape(ID)}"></span>
		<div style="font-weight:bold;display:none" id="onblurMessageResponse${escape(ID)}"></div>
		# ENDIF #
	</label>
	<div class="form-field">
		# START fieldelements #
			{fieldelements.ELEMENT}
		# END fieldelements #
	</div>
</div>
# INCLUDE ADD_FIELD_JS #