		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/notation.js"></script>
		<script type="text/javascript">
		<!--
			var Note = new Note('{ID_IN_MODULE}', '{NOTATION_SCALE}');
			Note.set_default_note('{AVERAGE_NOTES}');
			Note.set_already_post(${escapejs(ALREADY_VOTE)});
			Note.set_user_connected(${escapejs(IS_USER_CONNECTED)});
			Note.set_current_url(${escapejs(CURRENT_URL)});
			Note.add_lang('auth_error', ${escapejs(L_AUTH_ERROR)});
			Note.add_lang('already_vote', ${escapejs(L_ALREADY_VOTE)});
			
			Event.observe(window, 'load', function() {
				Event.observe($('note_pictures{ID_IN_MODULE}'), 'mouseover', function() {  
					Note.over_event();
				});
				
				Event.observe($('note_pictures{ID_IN_MODULE}'), 'mouseout', function() {  
					Note.out_event();
				});
				
				$('note_value{ID_IN_MODULE}').hide();
				$('note_select{ID_IN_MODULE}').hide();
				$('valid_note{ID_IN_MODULE}').hide();
				$('note_pictures{ID_IN_MODULE}').style.display = 'inline';
			});
		-->
		</script>
		
		<div>
			<div style="width:{NUMBER_PIXEL}px;margin:auto;display:none" id="note_pictures{ID_IN_MODULE}" >
				# START notation #
					<a href="javascript:Note.send_request({notation.I})" onmouseover="Note.change_picture_status({notation.I});">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{notation.PICTURE}" alt="" class="valign_middle" id="n{ID_IN_MODULE}_stars{notation.I}" />
					</a>
				# END notation #
				<span id="noteloading{ID_IN_MODULE}"></span>
			</div>
		</div>
		<form action="" method="post" class="text_small">
			<span id="note_value{ID_IN_MODULE}">
				# IF C_VOTES #
					<strong>{NUMBER_VOTES} {L_NOTES}</strong> : {AVERAGE_NOTES}
				# ELSE #
					{L_NO_NOTE}
				# ENDIF #
			</span>
			<select id="note_select{ID_IN_MODULE}" name="note">
				<option value="-1">{L_NOTE}</option>
				# START notation_no_js #
				<option value="{notation_no_js.I}">{notation_no_js.I}</option>
				# END notation_no_js #
			</select>
			<input type="hidden" name="token" value="{TOKEN}" />
			<input type="submit" name="valid" id="valid_note{ID_IN_MODULE}" value="{L_VALID_NOTE}" class="submit" style="padding:1px 2px;" />
		</form>
		