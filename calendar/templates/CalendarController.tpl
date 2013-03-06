		# INCLUDE MSG #
		
		<script type="text/javascript">
		<!--
		function Confirm_del() {
			return confirm("{@calendar.actions.confirm.del_event}");
		}
		
		var PreviousMonth = new Ajax.Updater('calendar_container', ${escapejs(LINK_HOME)}, {
			parameters: { year: ${escapejs(PREVIOUS_YEAR)}; month: ${escapejs(PREVIOUS_MONTH)}; }
		});
		
		var NextMonth = new Ajax.Updater('calendar_container', ${escapejs(LINK_HOME)}, {
			parameters: { year: ${escapejs(NEXT_YEAR)}; month: ${escapejs(NEXT_MONTH)}; }
		});
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
			<div class="module_contents" style="text-align:center;">
				# INCLUDE FORM #
				
				<span id="act"></span>
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
								# START day #
								{day.L_DAY}
								# END day #
							</tr>
							<tr>
								# START calendar #
								{calendar.DAY}
								{calendar.TR}
								# END calendar #
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
		
		<div class="event_container">
			<div class="event_top_title">
				<strong>{LINK_PREVIOUS_EVENT} &nbsp {@calendar.titles.events} &nbsp {LINK_NEXT_EVENT}</strong>
				<div class="module_actions">{ADD}</div>
			</div>
			<div class="event_date">{DATE2}</div>
			
			# IF C_ACTION #
				# START action #
				
				<div class="module_position">
					<div class="module_top_l"></div>
					<div class="module_top_r"></div>
					<div class="module_top">
						<span class="text_strong" style="float:left;">{action.TITLE}</span>
						<span style="float:right;">{action.COM}{action.EDIT}{action.DEL}</span>
					</div>
					<div class="module_contents">
						{action.CONTENTS}
						<br /><br /><br />
					</div>
					<div class="module_bottom_l"></div>
					<div class="module_bottom_r"></div>
					<div class="module_bottom">
						<div style="float:left;padding-top:4px;padding-bottom:4px;">
							{@calendar.labels.created_by}:&nbsp;&nbsp;{action.LOGIN}
						</div>
						<div class="text_small" style="padding:4px;float: right;">
							{@calendar.labels.start_date}:&nbsp;&nbsp;{action.START_DATE}
							<br />
							{@calendar.labels.end_date}:&nbsp;&nbsp;{action.END_DATE}
						</div>
					</div>
				</div>
				<br /><br />
				
				{COMMENTS}
				
				# END action #
			# ELSE #
			
				# START action #
				<div class="module_position">
					<div class="module_contents">
						{action.CONTENTS}
						<br /><br /><br />
					</div>
				</div>
				# END action #
				
			# ENDIF #
		</div>