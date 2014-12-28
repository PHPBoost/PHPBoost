<div id="${escape(HTML_ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element">
	<label for="${escape(HTML_ID)}">
		{LABEL}
		# IF C_DESCRIPTION #<span class="field-description">{DESCRIPTION}</span># ENDIF #
		<br/>

	</label>
	<div id="onblurContainerResponse${escape(HTML_ID)}" class="form-field-textarea# IF C_REQUIRED # field-required # ENDIF #">
		# START fieldelements #
		{fieldelements.ELEMENT}
		# END fieldelements #
		<span class="text-status-constraint" style="display:none" id="onblurMessageResponse${escape(HTML_ID)}"></span>
	</div>
</div>
# INCLUDE ADD_FIELD_JS #