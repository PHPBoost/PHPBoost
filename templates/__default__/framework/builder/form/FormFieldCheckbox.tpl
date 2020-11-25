<script>
<!--
jQuery(document).ready(function() {
	jQuery("#${escape(NAME)}").on('click', function() {
		HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").enableValidationMessage();
		HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").liveValidate();
	});
});
-->
</script>
<div id="${escape(HTML_ID)}_field" class="form-element form-element-checkbox# IF C_REQUIRED_AND_HAS_VALUE # constraint-status-right# ENDIF ## IF C_HAS_CSS_CLASS # {CSS_CLASS}# ENDIF #"# IF C_HIDDEN # style="display: none;"# ENDIF #>
	# IF C_HAS_LABEL #
		<label for="${escape(HTML_ID)}">
			{LABEL}
			# IF C_DESCRIPTION #
			<span class="field-description">{DESCRIPTION}</span>
			# ENDIF #
		</label>
	# ENDIF #

	<div id="onblurContainerResponse${escape(HTML_ID)}" class="form-field# IF C_HAS_FORM_FIELD_CLASS # {FORM_FIELD_CLASS}# ENDIF # picture-status-constraint# IF C_REQUIRED # field-required # ENDIF #">
		<label class="checkbox" for="${escape(HTML_ID)}">
			<input type="checkbox" name="${escape(NAME)}" id="${escape(HTML_ID)}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_CHECKED # checked="checked" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #/>
			<span>&nbsp;<span class="sr-only">${LangLoader::get_message('select', 'common')}</span></span>
		</label>
		<span class="text-status-constraint" style="display: none;" id="onblurMessageResponse${escape(HTML_ID)}"></span>
	</div>
</div>

# INCLUDE ADD_FIELD_JS #
