<script type="text/javascript">
<!--
var field = new FormField("{E_ID}");
var fieldset = HTMLForms.getFieldset("{E_FIELDSET_ID}");
fieldset.addField(field);

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

# IF C_DISABLED #
field.disable();
# ENDIF #

{JS_SPECIALIZATION_CODE}

Event.observe("{E_HTML_ID}", 'blur', function() {
	HTMLForms.get("{E_FORM_ID}").getField("{E_ID}").enableValidationMessage();
	HTMLForms.get("{E_FORM_ID}").getField("{E_ID}").liveValidate();
	# START related_field #
	HTMLForms.get("{E_FORM_ID}").getField("{related_field.E_ID}").liveValidate();
	# END related_field #
});

# START event_handler #
Event.observe("{E_HTML_ID}", '{event_handler.E_EVENT}', function() {
	{event_handler.HANDLER}
});
# END event_hander #
-->
</script>