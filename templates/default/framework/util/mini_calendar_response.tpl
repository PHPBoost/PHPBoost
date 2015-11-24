<div class="date-select-container">
	<div class="date-select-previous">
		<a href="" onclick="xmlhttprequest_calendar(${escapejs(FIELD)}, ${escapejs(INPUT_FIELD)}, ${escapejs(PREVIOUS_YEAR)}, ${escapejs(PREVIOUS_MONTH)}, 1, ${escapejs(CALENDAR_NUMBER)});return false;"><i class="fa fa-caret-left fa-large"></i></a>
	</div>
	<div class="date-select-next">
		<a href="" onclick="xmlhttprequest_calendar(${escapejs(FIELD)}, ${escapejs(INPUT_FIELD)}, ${escapejs(NEXT_YEAR)}, ${escapejs(NEXT_MONTH)}, 1, ${escapejs(CALENDAR_NUMBER)});return false;"><i class="fa fa-caret-right fa-large"></i></a>
	</div>
	<div class="date-select-content">
		<select onchange="xmlhttprequest_calendar(${escapejs(FIELD)}, ${escapejs(INPUT_FIELD)}, ${escapejs(YEAR)}, jQuery(this).val(), 1, ${escapejs(CALENDAR_NUMBER)});" class="date-select-month">
			# START month #
			{month.MONTH}
			# END month #
		</select>
		<select onchange="xmlhttprequest_calendar(${escapejs(FIELD)}, ${escapejs(INPUT_FIELD)}, jQuery(this).val(), ${escapejs(MONTH)}, 1, ${escapejs(CALENDAR_NUMBER)});" class="date-select-year">
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
				<a href="" onclick="insert_date(${escapejs(FIELD)}, ${escapejs(INPUT_FIELD)}, ${escapejs(day.DATE)}, ${escapejs(CALENDAR_NUMBER)});return false;">{day.DAY}</a>
			</td>
			# IF day.CHANGE_LINE #
		</tr>
		<tr>
			# ENDIF #
			# END day #
		</tr>
	</tbody>
</table>
