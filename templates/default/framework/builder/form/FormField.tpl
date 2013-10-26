<div id="${escape(ID)}_field"# IF C_HIDDEN # style="display:none;" # ENDIF # class="field # IF C_HAS_FIELD_CLASS #{FIELD_CLASS}# ENDIF #" >
	# IF C_HAS_LABEL #
		<label for="${escape(ID)}">
			# IF C_REQUIRED # * # ENDIF #
			{LABEL}
			# IF C_DESCRIPTION #
			<span class="field-description">{DESCRIPTION}</span>
			# ENDIF #
		</label>
	# ENDIF #
	
	<div class="field-value">
		# START fieldelements #
			{fieldelements.ELEMENT}
		# END fieldelements #
		# IF C_HAS_CONSTRAINTS #
			&nbsp;
			<span style="display:none" id="onblurContainerResponse${escape(ID)}"></span>
			<div style="font-weight:bold;display:none" id="onblurMesssageResponse${escape(ID)}"></div>
		# ENDIF #
	</div>
</div>
# INCLUDE ADD_FIELD_JS #