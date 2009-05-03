<script type="text/javascript">
<!--
	function check_generated_form_{L_FORMNAME}(){
		# START check_form #
		if (document.getElementById('{check_form.FIELD_ID}').value == "") {
			alert("{check_form.FIELD_REQUIRED_ALERT}");
			return false;
		}
		# END check_form #
		
		return true;
	}
-->
</script>

<form action="{U_FORMACTION}" name="{L_FORMNAME}" method="post" onsubmit="return check_generated_form_{L_FORMNAME}();" class="{FORMCLASS}">
	# START fieldsets #
		{fieldsets.FIELDSET}
	# END fieldsets #
	
	<fieldset class="fieldset_submit">
		<legend>{L_SUBMIT}</legend>
		<input type="submit" name="{L_FORMNAME}" value="{L_SUBMIT}" class="submit" />
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