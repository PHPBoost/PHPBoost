# IF C_VALIDATION_ERROR #
<div class="error">{VALIDATION_ERROR}</div>
# ENDIF #
<form action="{U_FORMACTION}" method="post" onsubmit="return check_generated_form_{EL_FORMNAME}();" class="{FORMCLASS}">
	# START fieldsets #
		{fieldsets.FIELDSET}
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

		# START check_constraints #
		if (!{check_constraints.ONSUBMIT_CONSTRAINTS})
		{
			return false;
		}
		# END check_constraints #
		# IF C_PERSONAL_SUBMIT #
		return {PERSONAL_SUBMIT}();
		# ENDIF #
		return true;
	}
-->
</script>