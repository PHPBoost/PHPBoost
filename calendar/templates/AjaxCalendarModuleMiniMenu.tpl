	<script type="text/javascript">
	<!--
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
	<div class="module_mini_top">
		<h5 class="sub_title">{@calendar.module_title}</h5>
	</div>
	<div class="module_mini_contents">
		<div class="mini_calendar_container">
			<div class="mini_calendar_top_l">
				<a class="change_month_link" onclick="PreviousMonth();" title="{PREVIOUS_MONTH_TITLE}">&laquo;</a>
			</div>
			<div class="mini_calendar_top_r">
				<a class="change_month_link" onclick="NextMonth();" title="{NEXT_MONTH_TITLE}">&raquo;</a>
			</div>
			<div class="mini_calendar_top">
				{DATE} 
			</div>
			
			<div class="mini_calendar_content">
				<table class="module_table mini_calendar_table">
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
						<td class="c_row {day.CLASS}"# IF day.C_BIRTHDAY # style="background-color:{BIRTHDAY_COLOR}"# ENDIF #>{day.CONTENT}</td>
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
	<div class="module_mini_bottom">
	</div>