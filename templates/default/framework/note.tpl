		<script type="text/javascript">
		<!--
		# IF C_JS_NOTE #
		array_note = new Array();
		# ENDIF #
		
		var theme = '{THEME}';
		var l_already_voted = '{L_ALERT_ALREADY_VOTE}';
		var l_auth_error = '{L_AUTH_ERROR}';
		var l_notes = '{L_NOTES}';
		var l_note = '{L_NOTE}';
		var note_max = {NOTE_MAX};
		{ARRAY_NOTE}
		note_timeout = null;
		on_img = 0;		
		-->
		</script> 
		# IF C_JS_NOTE #
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/note.js"></script>
		# ENDIF #
		
		<form action="" method="post" class="text_small">
			<div>
				{NOTE}
				<select id="note_select{ID}" name="note">
					{SELECT}
				</select>
				<input type="hidden" name="token" value="{TOKEN}" />
				<input type="submit" name="valid_note" id="valid_note{ID}" value="{L_VALID_NOTE}" class="submit" style="padding:1px 2px;" />
			</div>
			<script type="text/javascript">
			<!--				
			document.getElementById('note_value{ID}').style.display = 'none';
			document.getElementById('note_select{ID}').style.display = 'none';
			document.getElementById('valid_note{ID}').style.display = 'none';
			document.getElementById('note_stars{ID}').style.display = '{DISPLAY}';
			-->
			</script>
		</form>
		