<table class="date-picker">
	<thead>
		<tr>
			<th>
				<a href="javascript:xmlhttprequest_calendar('{FIELD}', '?{U_PREVIOUS}');"><i class="fa fa-caret-left fa-large"></i></a>
			</th>
			<th colspan="5">
				<select name="m" onchange="xmlhttprequest_calendar('{FIELD}', '?input_field={INPUT_FIELD}&amp;field={FIELD}{LYEAR}&amp;d=1&amp;m=' + this.options[this.selectedIndex].value + '&amp;y={YEAR}{TYPE}');">
					# START month #
					{month.MONTH}
					# END month #	
				</select>
				<select name="y" onchange="xmlhttprequest_calendar('{FIELD}', '?input_field={INPUT_FIELD}&amp;field={FIELD}{LYEAR}&amp;d=1&amp;m={MONTH}{TYPE}&amp;y=' + this.options[this.selectedIndex].value);">
					# START year #
						{year.YEAR}
					# END year #
				</select>
			</th> 
			<th>
				<a href="javascript:xmlhttprequest_calendar('{FIELD}', '?{U_NEXT}');"><i class="fa fa-caret-right fa-large"></i></a>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr style="text-align:center;">
			# START day #
			{day.L_DAY}
			# END day #
		</tr>
		<tr style="text-align:center;">			
			# START calendar #					
			{calendar.DAY}
			{calendar.TR}
			# END calendar #
		</tr>
	</tbody>
</table>