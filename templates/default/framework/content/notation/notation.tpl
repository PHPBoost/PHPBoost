		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/notation.js"></script>
		<script type="text/javascript">
		<!--
			var Note{ID_IN_MODULE} = new Note('{ID_IN_MODULE}', '{NOTATION_SCALE}', {NUMBER_VOTES});
			Note{ID_IN_MODULE}.set_default_note('{AVERAGE_NOTES}');
			Note{ID_IN_MODULE}.set_already_post(${escapejs(ALREADY_VOTE)});
			Note{ID_IN_MODULE}.set_user_connected(${escapejs(IS_USER_CONNECTED)});
			Note{ID_IN_MODULE}.set_current_url(${escapejs(CURRENT_URL)});
			Note{ID_IN_MODULE}.add_lang('auth_error', ${escapejs(L_AUTH_ERROR)});
			Note{ID_IN_MODULE}.add_lang('already_vote', ${escapejs(L_ALREADY_VOTE)});
			Note{ID_IN_MODULE}.add_lang('note', ${escapejs(L_NOTE)});
			Note{ID_IN_MODULE}.add_lang('notes', ${escapejs(L_NOTES)});
			
			Event.observe(window, 'load', function() {
				Event.observe($('note_pictures{ID_IN_MODULE}'), 'mouseover', function() {  
					Note{ID_IN_MODULE}.over_event();
				});
				
				Event.observe($('note_pictures{ID_IN_MODULE}'), 'mouseout', function() {  
					Note{ID_IN_MODULE}.out_event();
				});
				
				$('note_select{ID_IN_MODULE}').hide();
				$('valid_note{ID_IN_MODULE}').hide();
				$('note_pictures{ID_IN_MODULE}').style.display = 'inline';
			});
		-->
		</script>
		
		<div>
			<div style="width:{NUMBER_PIXEL}px;margin:auto;display:none" id="note_pictures{ID_IN_MODULE}" >
				# START notation #
					<a href="javascript:Note{ID_IN_MODULE}.send_request({notation.I})" onmouseover="javascript:Note{ID_IN_MODULE}.change_picture_status({notation.I});" class="img_link">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{notation.PICTURE}" alt="" class="valign_middle" id="n{ID_IN_MODULE}_stars{notation.I}" />
					</a>
				# END notation #
				<span id="noteloading{ID_IN_MODULE}"></span>
			</div>
		</div>
		<form action="" method="post" class="text_small">
			<span id="note_value{ID_IN_MODULE}">
				# IF C_VOTES #
					{NUMBER_VOTES} 
					# IF C_MORE_1_VOTES #
						{L_NOTES}
					# ELSE #
						{L_NOTE}
					# ENDIF #
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