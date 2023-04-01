<a href="#" class="error big hide-modal align-right" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></a>
<div class="cell-body date-select-container grouped-inputs">
	<a class="date-select-nav grouped-element" href="#" onclick="xmlhttprequest_calendar(${escapejs(FIELD)}, ${escapejs(INPUT_FIELD)}, ${escapejs(PREVIOUS_YEAR)}, ${escapejs(PREVIOUS_MONTH)}, 1, ${escapejs(CALENDAR_NUMBER)});return false;" aria-label="${LangLoader::get_message('common.next', 'common-lang')}">
		<i class="fa fa-caret-left fa-large" aria-hidden="true"></i>
	</a>
	<select onchange="xmlhttprequest_calendar(${escapejs(FIELD)}, ${escapejs(INPUT_FIELD)}, ${escapejs(YEAR)}, jQuery(this).val(), 1, ${escapejs(CALENDAR_NUMBER)});" class="date-select-month grouped-element">
		# START month #
			{month.MONTH}
		# END month #
	</select>
	<select onchange="xmlhttprequest_calendar(${escapejs(FIELD)}, ${escapejs(INPUT_FIELD)}, jQuery(this).val(), ${escapejs(MONTH)}, 1, ${escapejs(CALENDAR_NUMBER)});" class="date-select-year grouped-element">
		# START year #
			{year.YEAR}
		# END year #
	</select>
	<a class="date-select-nav grouped-element" href="#" onclick="xmlhttprequest_calendar(${escapejs(FIELD)}, ${escapejs(INPUT_FIELD)}, ${escapejs(NEXT_YEAR)}, ${escapejs(NEXT_MONTH)}, 1, ${escapejs(CALENDAR_NUMBER)});return false;" aria-label="${LangLoader::get_message('common.previous', 'common-lang')}">
		<i class="fa fa-caret-right fa-large" aria-hidden="true"></i>
	</a>
</div>
<div class="cell-table">
	<table class="date-picker">
		<thead>
			<tr>
				<th>{@date.monday.short}</th>
				<th>{@date.tuesday.short}</th>
				<th>{@date.wednesday.short}</th>
				<th>{@date.thursday.short}</th>
				<th>{@date.friday.short}</th>
				<th>{@date.saturday.short}</th>
				<th>{@date.sunday.short}</th>
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
