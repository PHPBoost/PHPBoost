# IF C_HOUR #
<script>
	jQuery(document).ready(function() {
		if (jQuery("#${escape(HTML_ID)}_hours").length)
		{
			jQuery("#${escape(HTML_ID)}_hours").blur(function() {
				HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").enableValidationMessage();
				HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").liveValidate();
			});
		}
		if (jQuery("#${escape(HTML_ID)}_minutes").length)
		{
			jQuery("#${escape(HTML_ID)}_minutes").blur(function() {
				HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").enableValidationMessage();
				HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").liveValidate();
			});
		}
	});
</script>
# ENDIF #
<div id="${escape(HTML_ID)}_field" class="form-element form-element-date# IF C_HOUR # form-element-date-time# ENDIF ## IF C_REQUIRED_AND_HAS_VALUE # constraint-status-right# ENDIF ## IF C_HAS_CSS_CLASS # {CSS_CLASS}# ENDIF #"# IF C_HIDDEN # style="display: none;"# ENDIF #>
	# IF C_HAS_LABEL #
		<label for="${escape(HTML_ID)}">
			{LABEL}
			# IF C_DESCRIPTION #
			<span class="field-description">{DESCRIPTION}</span>
			# ENDIF #
		</label>
	# ENDIF #

	<div id="onblurContainerResponse${escape(HTML_ID)}" class="form-field grouped-inputs# IF C_HAS_FORM_FIELD_CLASS # {FORM_FIELD_CLASS}# ENDIF # picture-status-constraint# IF C_REQUIRED # field-required # ENDIF #">
		<span class="text-status-constraint" style="display: none;" id="onblurMessageResponse${escape(HTML_ID)}"></span>
		{CALENDAR}
		# IF C_HOUR #
			<label for="${escape(HTML_ID)}_hours" class="label-time grouped-element" data-time="{L_H}">
				<input type="number" min="0" max="23" id="${escape(HTML_ID)}_hours" class="input-hours" name="${escape(HTML_ID)}_hours" value="{HOURS}" # IF C_DISABLED # disabled="disabled"# ENDIF ## IF C_READONLY # readonly="readonly"# ENDIF #/>
				<span class="sr-only">${LangLoader::get_message('hours', 'date-common')}</span>
			</label>
			<label for="${escape(HTML_ID)}_minutes" class="label-time grouped-element" data-time="{L_MN}">
				<input type="number" min="0" max="59"# IF C_FIVE_MINUTES_STEP # step="5"# ENDIF # id="${escape(HTML_ID)}_minutes" class="input-minutes" name="${escape(HTML_ID)}_minutes" value="{MINUTES}"# IF C_DISABLED # disabled="disabled"# ENDIF ## IF C_READONLY # readonly="readonly"# ENDIF #/>
				<span class="sr-only">${LangLoader::get_message('minutes', 'date-common')}</span>
			</label>
		# ENDIF #
	</div>
</div>

# INCLUDE ADD_FIELD_JS #
