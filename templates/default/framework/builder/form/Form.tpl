# IF C_VALIDATION_ERROR #
	<div class="message-helper bgc error">
		<p class="text-strong">{TITLE_VALIDATION_ERROR_MESSAGE} : </p>
		# START validation_error_messages #
			- {validation_error_messages.ERROR_MESSAGE}<br />
		# END validation_error_messages #
	</div>
# ENDIF #

# IF C_JS_NOT_ALREADY_INCLUDED #
	<script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/form/validator.js"></script>
	<script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/form/form.js"></script>
# ENDIF #

<script>
	jQuery(document).ready(function() {
		var form = new HTMLForm("${escape(HTML_ID)}");
		HTMLForms.add(form);
	});
</script>

<form id="${HTML_ID}" # IF C_TARGET #action="${TARGET}"# ENDIF # method="${METHOD}" onsubmit="return HTMLForms.get('${HTML_ID}').validate();" class="${FORMCLASS}">
	# IF C_HAS_REQUIRED_FIELDS #
		<p class="required-fields-text">{L_REQUIRED_FIELDS}</p>
	# ENDIF #

	# START fieldsets #
		# INCLUDE fieldsets.FIELDSET #
	# END fieldsets #

	<input type="hidden" id="${HTML_ID}_token" name="token" value="{TOKEN}">
	<input type="hidden" id="${HTML_ID}_disabled_fields" name="${HTML_ID}_disabled_fields" value="">
	<input type="hidden" id="${HTML_ID}_disabled_fieldsets" name="${HTML_ID}_disabled_fieldsets" value="">
</form>
