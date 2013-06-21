		# INCLUDE MSG #
		
		<script type="text/javascript">
		<!--
		function Confirm_del() {
			return confirm("{@calendar.actions.confirm.del_event}");
		}
		
		window.PreviousMonth = function()
		{
			new Ajax.Updater(
				'mini_calendar',
				${escapejs(LINK_PREVIOUS_MONTH)},
				{
					evalScripts: true
				}
			);
		}
		
		window.NextMonth = function()
		{
			new Ajax.Updater(
				'mini_calendar',
				${escapejs(LINK_NEXT_MONTH)},
				{
					evalScripts: true
				}
			);
		}
		-->
		</script>
		
		<div class="module_position">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
				<div class="module_top_title">
					<strong>{@calendar.module_title}</strong>
				</div>
			</div>
			<div class="module_contents">
				<div id="date_select_form" class="text_center">
				# INCLUDE FORM #
				</div>
				
				<div class="calendar_container">
					<div class="calendar_top_l">
						<a href="{LINK_PREVIOUS}" title="">&laquo;</a>
					</div>
					<div class="calendar_top_r">
						<a href="{LINK_NEXT}" title="">&raquo;</a>
					</div>
					<div class="calendar_top">
						{DATE} 
					</div>
					
					<div class="calendar_content">
						<table class="module_table calendar_table">
							<tr>
								<td></td>
								<td><span class="text_month">{L_MONDAY}</span></td>
								<td><span class="text_month">{L_TUESDAY}</span></td>
								<td><span class="text_month">{L_WEDNESDAY}</span></td>
								<td><span class="text_month">{L_THURSDAY}</span></td>
								<td><span class="text_month">{L_FRIDAY}</span></td>
								<td><span class="text_month">{L_SATURDAY}</span></td>
								<td><span class="text_month">{L_SUNDAY}</span></td>
							</tr>
							<tr>
								# START day #
								<td class="c_row {day.CLASS}">{day.CONTENT}</td>
								# IF day.CHANGE_LINE #
							</tr>
							<tr>
								# ENDIF #
								# END day #
							</tr>
							<tr>
								<td></td>
								<td class="c_row_last" colspan="7"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			
			<div class="module_bottom_l"></div>
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		<br /><br />
		
		<div id="events" class="event_container">
			<div class="event_top_title">
				<strong>{LINK_PREVIOUS_EVENT} &nbsp {@calendar.titles.events} &nbsp {LINK_NEXT_EVENT}</strong>
				<div class="module_actions">
				# IF C_ADD #
				<a href="{U_ADD}" title="{@calendar.titles.add_event}" class="img_link">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="{@calendar.titles.add_event}" />
				</a>
				# ENDIF #
				</div>
			</div>
			<div class="event_date">{DATE2}</div>
			
			# IF C_EVENT #
				# START event #
				
				<div class="module_position">
					<div class="module_top_l"></div>
					<div class="module_top_r"></div>
					<div class="module_top">
						<div class="module_top_title">
							{event.TITLE}
						</div>
						
						<div class="module_top_com">
						# IF C_COMMENTS_ENABLED #<a href="{event.U_COMMENTS}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/com_mini.png" alt="" class="valign_middle" /> {event.L_COMMENTS}</a># ENDIF #
						# IF event.C_EDIT #
						<a href="{event.U_EDIT}" title="{L_EDIT}" class="img_link">
							<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" />
						</a>
						# ENDIF #
						# IF event.C_DELETE #
						<a href="{event.U_DELETE}" title="{L_DELETE}" onclick="javascript:return Confirm_del();">
							<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" />
						</a>
						# ENDIF #
					</div>
					<div class="spacer"></div>
				</div>
					<div class="module_contents">
						{event.CONTENTS}
						<br /><br /><br />
					</div>
					<div class="module_bottom_l"></div>
					<div class="module_bottom_r"></div>
					<div class="module_bottom">
						<div class="event_display_author">
							{@calendar.labels.created_by} : # IF event.AUTHOR #<a href="{event.U_AUTHOR_PROFILE}" class="small_link {event.AUTHOR_LEVEL_CLASS}" # IF event.C_AUTHOR_GROUP_COLOR # style="color:{event.AUTHOR_GROUP_COLOR}" # ENDIF #>{event.AUTHOR}</a># ELSE #{L_GUEST}# ENDIF #
						</div>
						<div class="event_display_dates">
							{@calendar.labels.start_date} : <span class="float_right">{event.START_DATE}</span>
							<br />
							{@calendar.labels.end_date} : <span class="float_right">{event.END_DATE}</span>
						</div>
					</div>
				</div>
				<br /><br />
				
				{COMMENTS}
				
				# END event #
			# ELSE #
			
				# START event #
				<div class="module_position">
					<div class="module_contents">
						{event.CONTENTS}
						<br /><br /><br />
					</div>
				</div>
				# END event #
				
			# ENDIF #
		</div>