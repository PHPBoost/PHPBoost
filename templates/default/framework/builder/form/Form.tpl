# IF C_VALIDATION_ERROR #
<div class="error">
# START validation_error_messages #
	{validation_error_messages.ERROR_MESSAGE}<br />
# END validation_error_messages #
</div>
# ENDIF #

# IF C_JS_NOT_ALREADY_INCLUDED # 
<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/form/validator.js"></script>
<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/form/form.js"></script>  
# ENDIF #

<script type="text/javascript">
<!--
	var form = new HTMLForm("${escape(HTML_ID)}");
	HTMLForms.add(form);
-->
</script>


<form id="${escape(HTML_ID)}" action="${escape(TARGET)}" method="${escape(METHOD)}" onsubmit="return HTMLForms.get('${escape(HTML_ID)}').validate();" class="${escape(FORMCLASS)}">
	# IF C_HAS_REQUIRED_FIELDS #
	<p style="text-align:center;">{L_REQUIRED_FIELDS}</p>
	# ENDIF #
	
	# START fieldsets #
		# INCLUDE fieldsets.FIELDSET #
	# END fieldsets #
	
	<fieldset class="fieldset_submit">
		<legend>{L_SUBMIT}</legend>
		# START buttons #
			# INCLUDE buttons.BUTTON #
		# END buttons #
		<input type="hidden" id="${escape(HTML_ID)}_disabled_fields" name="${escape(HTML_ID)}_disabled_fields" value="" />
		<input type="hidden" id="${escape(HTML_ID)}_disabled_fieldsets" name="${escape(HTML_ID)}_disabled_fieldsets" value="" />
	</fieldset>
</form>