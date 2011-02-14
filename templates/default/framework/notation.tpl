		<script type="text/javascript">
		<!--
		
		function send_note(id, note)
		{
			document.getElementById('noteloading' + id).innerHTML = '<img src="' + PATH_TO_ROOT + '/templates/' + theme + '/images/loading_mini.gif" alt="" class="valign_middle" />';
			
			new Ajax.Request(
			CURRENT_URL,
			{
				method: 'post',
				parameters: {'note': note, 'valid_note': true},
				onSuccess: function() {
					document.getElementById('noteloading' + id).innerHTML = ''; 
				},
			});
		}
		
		array_note = new Array();
		var l_already_voted = '{L_ALERT_ALREADY_VOTE}';
		var l_auth_error = '{L_AUTH_ERROR}';
		var l_notes = '{L_NOTES}';
		var l_note = '{L_NOTE}';
		var note_max = {NOTATION_SCALE};
		{ARRAY_NOTE}
		note_timeout = null;
		on_img = 0;		
		-->
		</script> 
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/note.js"></script>
		
		
		
		<form action="" method="post" class="text_small">
			<div>
				<span id="note_value{MODULE_ID}">
					# IF C_VOTES #
						<strong>{NUMBER_VOTES}</strong>
					# ELSE #
						{L_NO_NOTE}
					# ENDIF #
				</span>
				<div style="width:{NUMBER_PIXEL}px;margin:auto;display:none" id="note_stars{MODULE_ID}" onmouseout="out_div({MODULE_ID}, array_note[{MODULE_ID}])" onmouseover="over_div()">
					# START notation #
						<a href="javascript:send_note2({MODULE_ID}, {notation.I})" onmouseover="select_stars({MODULE_ID}, {notation.I});">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{notation.IMAGE}" alt="" class="valign_middle" id="n{MODULE_ID}_stars{notation.I}" />
						</a>
					# END notation #
					</div><span id="noteloading{MODULE_ID}"></span>
				<select id="note_select{MODULE_ID}" name="note">
					<option value="-1">{L_NOTE}</option>
					# START notation_no_js #
						<option value="{notation_no_js.I}">{notation_no_js.I}</option>
					# END notation_no_js #
				</select>
				<input type="hidden" name="token" value="{TOKEN}" />
				<input type="submit" name="valid_note" id="valid_note{MODULE_ID}" value="{L_VALID_NOTE}" class="submit" style="padding:1px 2px;" />
			</div>
			<script type="text/javascript">
			<!--				
			document.getElementById('note_value{MODULE_ID}').style.display = 'none';
			document.getElementById('note_select{MODULE_ID}').style.display = 'none';
			document.getElementById('valid_note{MODULE_ID}').style.display = 'none';
			document.getElementById('note_stars{MODULE_ID}').style.display = '{DISPLAY}';
			-->
			</script>
		</form>
		