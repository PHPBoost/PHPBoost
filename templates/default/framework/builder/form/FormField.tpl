<div id="${escape(HTML_ID)}_field"# IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element # IF C_REQUIRED_AND_HAS_VALUE # constraint-status-right # ENDIF # # IF C_HAS_FIELD_CLASS #{FIELD_CLASS}# ENDIF #">
	# IF C_HAS_LABEL #
		<label for="${escape(HTML_ID)}">
			{LABEL}
			# IF C_DESCRIPTION #
			<span class="field-description">{DESCRIPTION}</span>
			# ENDIF #
		</label>
	# ENDIF #

	<div id="onblurContainerResponse${escape(HTML_ID)}" class="form-field picture-status-constraint# IF C_REQUIRED # field-required # ENDIF #">
		# START fieldelements #
		{fieldelements.ELEMENT}
		# END fieldelements #
		<span class="text-status-constraint" style="display:none" id="onblurMessageResponse${escape(HTML_ID)}"></span>
	</div>

</div>
# INCLUDE ADD_FIELD_JS #