# IF C_VALIDATION_ERROR #
<div class="error">
# START validation_error_messages #
	{validation_error_messages.ERROR_MESSAGE}<br />
# END validation_error_messages #
</div>
# ENDIF #

<form action="{U_FORMACTION}" method="post" onsubmit="return check_generated_form_{EL_FORMNAME}();" class="{FORMCLASS}">
	# IF C_HAS_REQUIRED_FIELDS #
	<p style="text-align:center;">{L_REQUIRED_FIELDS}</p>
	# ENDIF #
	
	# START fieldsets #
		# INCLUDE fieldsets.FIELDSET #
	# END fieldsets #
	
	<fieldset class="fieldset_submit">
		<legend>{L_SUBMIT}</legend>
		<input type="submit" name="{EL_FORMNAME}" value="{L_SUBMIT}" class="submit" />
		# IF C_DISPLAY_PREVIEW #
		<script type="text/javascript">
		<!--				
		document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(\'{L_FIELD_CONTENT_PREVIEW}\');hide_div(\'xmlhttprequest_result\')" type="button" class="submit" />');
		-->
		</script>						
		# ENDIF #
		# IF C_DISPLAY_RESET # <input type="reset" value="{L_RESET}" class="reset" /> # ENDIF #
	</fieldset>
</form>
# IF C_JS_NOT_ALREADY_INCLUDED # 
<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/form/validator.js"></script> 
# ENDIF #

<script type="text/javascript">
<!--
	function check_generated_form_{L_FORMNAME}()
	{
		# IF C_BBCODE_TINYMCE_MODE #
		tinyMCE.triggerSave();
		# ENDIF #

		var return_value = true;
		var constraints = Array();
		# START check_constraints #
		constraints.push({check_constraints.ONSUBMIT_CONSTRAINTS});
		# END check_constraints #
		return_value = formFieldConstraintsOnsubmitValidation(constraints);
		
		# IF C_PERSONAL_SUBMIT #
		return_value = {PERSONAL_SUBMIT}();
		# ENDIF #
		return return_value;
	}
-->
</script>