<div class="cell-body date-select-container grouped-inputs">
	<a class="date-select-nav" href="" onclick="xmlhttprequest_calendar(${escapejs(FIELD)}, ${escapejs(INPUT_FIELD)}, ${escapejs(PREVIOUS_YEAR)}, ${escapejs(PREVIOUS_MONTH)}, 1, ${escapejs(CALENDAR_NUMBER)});return false;" aria-label="${LangLoader::get_message('next', 'common')}"><i class="fa fa-caret-left fa-large" aria-hidden="true"></i></a>
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
	<a class="date-select-nav" href="" onclick="xmlhttprequest_calendar(${escapejs(FIELD)}, ${escapejs(INPUT_FIELD)}, ${escapejs(NEXT_YEAR)}, ${escapejs(NEXT_MONTH)}, 1, ${escapejs(CALENDAR_NUMBER)});return false;" aria-label="${LangLoader::get_message('previous', 'common')}"><i class="fa fa-caret-right fa-large" aria-hidden="true"></i></a>
</div>
<div class="cell-table">
	<table class="date-picker">
		<thead>
			<tr>
				<th>{@monday_short}</th>
				<th>{@tuesday_short}</th>
				<th>{@wednesday_short}</th>
				<th>{@thursday_short}</th>
				<th>{@friday_short}</th>
				<th>{@saturday_short}</th>
				<th>{@sunday_short}</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				# START day #
				<td class="{day.CLASS} square-cell">
					<span class="hide-modal calendar-day" onclick="insert_date(${escapejs(FIELD)}, ${escapejs(INPUT_FIELD)}, ${escapejs(day.DATE)}, ${escapejs(CALENDAR_NUMBER)});return false;">{day.DAY}</span>
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
