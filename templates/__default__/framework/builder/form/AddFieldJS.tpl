<script>
	jQuery(document).ready(function() {
		var field = new FormField("${escape(ID)}");
		var fieldset = HTMLForms.getFieldset("${escape(FIELDSET_ID)}");
		fieldset.addField(field);
		# IF C_HAS_CONSTRAINTS #field.hasConstraints = true;# ENDIF #
		field.doValidate = function() {
			var result = "";
			# START constraint #
				result = {constraint.CONSTRAINT};
				if (result != "") {
					return result;
				}
			# END constraint #
			return result;
		}
		if (jQuery("#${escape(HTML_ID)}").length)
		{
			jQuery("#${escape(HTML_ID)}").blur(function() {
				HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").enableValidationMessage();
				HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").liveValidate();
				# START related_field #
				HTMLForms.get("${escape(FORM_ID)}").getField("{related_field.ID}").liveValidate();
				# END related_field #
			});

			# START event_handler #
			jQuery("#${escape(HTML_ID)}").on('{event_handler.EVENT}', function() {
				{event_handler.HANDLER}
			});
			# END event_hander #
		}

		{JS_SPECIALIZATION_CODE}
	});
</script>
