<script type="text/javascript">
<!--
	function PreviousMonth() 
	{
		new Ajax.Updater(
			'module_mini_contents',
			'{LINK_MINI_MODULE}',
			{
				parameters: {
					year: ${escapejs(PREVIOUS_YEAR)},
					month: ${escapejs(PREVIOUS_MONTH)},
				},
			}
		);
		return false;
	}
	
	function NextMonth() 
	{
		new Ajax.Updater(
			'module_mini_contents',
			'{LINK_MINI_MODULE}',
			{
				parameters: {
					year: ${escapejs(NEXT_YEAR)},
					month: ${escapejs(NEXT_MONTH)},
				},
			}
		);
		return false;
	}
-->
</script>

<link rel="stylesheet" href="{PATH_TO_ROOT}/calendar/templates/calendar.css" type="text/css" />
<div class="module_mini_container" # IF NOT C_VERTICAL #style="width: auto;"# ENDIF #>
	<div class="module_mini_top">
		<h5 class="sub_title">{@calendar.module_title}</h5>
	</div>
	<div class="module_mini_contents">
		<div class="mini_calendar_container">
			<div class="mini_calendar_top">
				<a onclick="PreviousMonth();" href="#" title="{PREVIOUS_MONTH_TITLE}">&laquo;</a> {DATE} <a onclick="NextMonth();" href="#" title="{NEXT_MONTH_TITLE}">&raquo;</a>
			</div>
			
			<div class="mini_calendar_content">
				<table class="module_table mini_calendar_table">
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
	<div class="module_mini_bottom">
	</div>
</div>