		<script type="text/javascript">
		<!--
			function ChangeMonth(link)
			{
				new Ajax.Updater(
					'calendar',
					link,
					{
						evalScripts: true
					}
				);
			}
		-->
		</script>
		# IF C_FORM #
		<div id="date_select_form" class="center">
			# INCLUDE FORM #
		</div>
		# ENDIF #
		<div class="calendar_container">
			<div class="calendar_top options">
				<div class="calendar_top_l">
					<a class="change_month_link" onclick="ChangeMonth(${escapejs(U_PREVIOUS_MONTH)});" title="{PREVIOUS_MONTH_TITLE}">&laquo;</a>
				</div>
				<div class="calendar_top_r">
					<a class="change_month_link" onclick="ChangeMonth(${escapejs(U_NEXT_MONTH)});" title="{NEXT_MONTH_TITLE}">&raquo;</a>
				</div>
				{DATE} 
			</div>
			
			<div class="calendar_content">
				<table class="mini calendar_table">
					<thead>
						<tr>
							<th></th>
							<th><span class="text_month">{L_MONDAY}</span></th>
							<th><span class="text_month">{L_TUESDAY}</span></th>
							<th><span class="text_month">{L_WEDNESDAY}</span></th>
							<th><span class="text_month">{L_THURSDAY}</span></th>
							<th><span class="text_month">{L_FRIDAY}</span></th>
							<th><span class="text_month">{L_SATURDAY}</span></th>
							<th><span class="text_month">{L_SUNDAY}</span></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							# START day #
							<td class="{day.CLASS}"# IF day.C_COLOR # style="background-color:{day.COLOR}"# ENDIF #>
								# IF day.C_MONTH_DAY #<a title="{day.TITLE}" href="{day.U_DAY_EVENTS}">{day.DAY}</a># ENDIF #
							</td>
							# IF day.CHANGE_LINE #
						</tr>
						<tr>
							# ENDIF #
							# END day #
						</tr>
					</tbody>
				</table>
			</div>
		</div>