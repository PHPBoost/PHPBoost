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
		<div id="date_select_form" class="text_center">
			# INCLUDE FORM #
		</div>
		# ENDIF #
		<div class="calendar_container">
			<div class="calendar_top_l">
				<a class="change_month_link" onclick="ChangeMonth(${escapejs(LINK_PREVIOUS_MONTH)});" title="{PREVIOUS_MONTH_TITLE}">&laquo;</a>
			</div>
			<div class="calendar_top_r">
				<a class="change_month_link" onclick="ChangeMonth(${escapejs(LINK_NEXT_MONTH)});" title="{NEXT_MONTH_TITLE}">&raquo;</a>
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
						<td class="c_row {day.CLASS}"# IF day.C_COLOR # style="background-color:{day.COLOR}"# ENDIF #>
							# IF day.C_MONTH_DAY #<a title="{day.TITLE}" href="{day.U_DAY_EVENTS}">{day.DAY}</a># ENDIF #
						</td>
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