<div class="date-select-container">
	<div class="date-select-previous">
		<a href="javascript:xmlhttprequest_calendar('{FIELD}', '?{U_PREVIOUS}');"><i class="fa fa-caret-left fa-large"></i></a>
	</div>
	<div class="date-select-next">
		<a href="javascript:xmlhttprequest_calendar('{FIELD}', '?{U_NEXT}');"><i class="fa fa-caret-right fa-large"></i></a>
	</div>
	<div class="date-select-content">
		<select name="m" onchange="xmlhttprequest_calendar('{FIELD}', '?input_field={INPUT_FIELD}&amp;field={FIELD}{LYEAR}&amp;d=1&amp;m=' + this.options[thiselectedIndex].value + '&amp;y={YEAR}{TYPE}');" class="date-select-month">
			# START month #
			{month.MONTH}
			# END month #
		</select>
		<select name="y" onchange="xmlhttprequest_calendar('{FIELD}', '?input_field={INPUT_FIELD}&amp;field={FIELD}{LYEAR}&amp;d=1&amp;m={MONTH}{TYPE}&amp;y=+ this.options[this.selectedIndex].value);" class="date-select-year">
			# START year #
				{year.YEAR}
			# END year #
		</select>
		<div class="spacer"></div>
	</div>
	<div class="spacer"></div>
</div>
<table class="date-picker">
	<thead>
		<tr>
			<th><span class="smaller">{@monday_short}</span></th>
			<th><span class="smaller">{@tuesday_short}</span></th>
			<th><span class="smaller">{@wednesday_short}</span></th>
			<th><span class="smaller">{@thursday_short}</span></th>
			<th><span class="smaller">{@friday_short}</span></th>
			<th><span class="smaller">{@saturday_short}</span></th>
			<th><span class="smaller">{@sunday_short}</span></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			# START day #
			<td class="{day.CLASS}">
				<a href="javascript:insert_date(${escapejs(day.INPUT_FIELD)}, ${escapejs(day.DATE)});">{day.DAY}</a>
			</td>
			# IF day.CHANGE_LINE #
		</tr>
		<tr>
			# ENDIF #
			# END day #
		</tr>
	</tbody>
</table>