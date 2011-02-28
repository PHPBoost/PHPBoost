		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/notation.js"></script>
		<script type="text/javascript">
		<!--
			var Note = new Note('{MODULE_ID}', '{NOTATION_SCALE}');
			Note.set_default_note('{AVERAGE_NOTES}');
			Note.set_already_post(${escapejs(ALREADY_VOTE)});
			Note.set_user_connected(${escapejs(IS_USER_CONNECTED)});
			Note.set_current_url(${escapejs(CURRENT_URL)});
			Note.add_lang('auth_error', ${escapejs(L_AUTH_ERROR)});
			Note.add_lang('already_vote', ${escapejs(L_ALREADY_VOTE)});
			
			Event.observe(window, 'load', function() {
				Event.observe($('note_pictures{MODULE_ID}'), 'mouseover', function() {  
					Note.over_event();
				});
				
				Event.observe($('note_pictures{MODULE_ID}'), 'mouseout', function() {  
					Note.out_event(Note.get_default_note());
				});
				
				$('note_value{MODULE_ID}').hide();
				$('note_select{MODULE_ID}').hide();
				$('valid_note{MODULE_ID}').hide();
				$('note_pictures{MODULE_ID}').style.display = 'inline';
			});
		-->
		</script>
		
		<div>
			<div style="width:{NUMBER_PIXEL}px;margin:auto;display:none" id="note_pictures{MODULE_ID}" >
				# START notation #
					<a href="javascript:Note.send_request({notation.I})" onmouseover="Note.change_picture_status({notation.I});">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{notation.PICTURE}" alt="" class="valign_middle" id="n{MODULE_ID}_stars{notation.I}" />
					</a>
				# END notation #
				<span id="noteloading{MODULE_ID}"></span>
			</div>
		</div>
		<form action="" method="post" class="text_small">
			<span id="note_value{MODULE_ID}">
				# IF C_VOTES #
					<strong>{NUMBER_VOTES} {L_NOTES}</strong> : {AVERAGE_NOTES}
				# ELSE #
					{L_NO_NOTE}
				# ENDIF #
			</span>
			<select id="note_select{MODULE_ID}" name="note">
				<option value="-1">{L_NOTE}</option>
				# START notation_no_js #
				<option value="{notation_no_js.I}">{notation_no_js.I}</option>
				# END notation_no_js #
			</select>
			<input type="hidden" name="token" value="{TOKEN}" />
			<input type="hidden" name="valid_note" value="true" /> 
			<input type="submit" name="valid" id="valid_note{MODULE_ID}" value="{L_VALID_NOTE}" class="submit" style="padding:1px 2px;" />
		</form>
		