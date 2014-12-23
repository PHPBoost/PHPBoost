<div id="${escape(HTML_ID)}_field"# IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element # IF C_HAS_FIELD_CLASS #{FIELD_CLASS}# ENDIF #">
	# IF C_HAS_LABEL #
		<label for="${escape(HTML_ID)}">
			{LABEL}
			# IF C_DESCRIPTION #
			<span class="field-description">{DESCRIPTION}</span>
			# ENDIF #
		</label>
	# ENDIF #

	<div class="form-field">
		# START fieldelements #
			{fieldelements.ELEMENT}
		# END fieldelements #
		<i class="fa # IF C_REQUIRED # picture-field-required # ENDIF # picture-status-constraint" id="onblurContainerResponse${escape(HTML_ID)}"></i>
		<span class="text-status-constraint" style="display:none" id="onblurMessageResponse${escape(HTML_ID)}"></span>
	</div>
</div>
# INCLUDE ADD_FIELD_JS #