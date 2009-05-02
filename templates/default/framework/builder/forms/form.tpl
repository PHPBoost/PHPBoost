<form action="{U_FORMACTION}" name="{L_FORMNAME}" method="post" onsubmit="{FORMONSUBMIT}" class="{FORMCLASS}">
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