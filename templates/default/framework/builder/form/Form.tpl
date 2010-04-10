# IF C_VALIDATION_ERROR #
<div class="error">
# START validation_error_messages #
	{validation_error_messages.ERROR_MESSAGE}<br />
# END validation_error_messages #
</div>
# ENDIF #

# IF C_JS_NOT_ALREADY_INCLUDED # 
<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/form/validator.js"></script>
<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/form/form.js"></script>  
# ENDIF #

<script type="text/javascript">
<!--
	var form = new HTMLForm("{E_HTML_ID}");
	HTMLForms.add(form);
-->
</script>


<form id="{E_HTML_ID}" action="{E_TARGET}" method="{E_METHOD}" onsubmit="return HTMLForms.get('{E_HTML_ID}').validate();" class="{E_FORMCLASS}">
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
	</fieldset>
	
	<input type="hidden" name="{E_HTML_ID}_disabled_fields" value="" />
</form>