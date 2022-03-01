<script>
	function ChangeMonth(year, month)
	{
		jQuery.ajax({
			url: ${escapejs(U_AJAX_CALENDAR)} + year + '/' + month + '/' + {ID_CATEGORY} + '/' + {MINI_MODULE},
			success: function(returnData){
				jQuery('#calendar').html(returnData);
				jQuery('[data-color-surround]').colorSurround();
			}
		});

		# IF NOT C_MINI_MODULE #
			jQuery.ajax({
				url: ${escapejs(U_AJAX_EVENTS)} + year + '/' + month + '/0/' + {ID_CATEGORY},
				success: function(returnData){
					jQuery('#events').html(returnData);
					jQuery('[data-color-surround]').colorSurround();
				}
			});
		# ENDIF #
	}
</script>

<div id="date-select-form"# IF C_MINI_MODULE # class="cell-form"# ENDIF #>
	<form method="post">
		<div class="grouped-inputs">
			<a class="grouped-element bgc link-color" href="#" onclick="ChangeMonth(${escapejs(PREVIOUS_YEAR)}, ${escapejs(PREVIOUS_MONTH)});return false;" aria-label="{PREVIOUS_MONTH_TITLE}">
				<i class="fa fa-caret-left" aria-hidden="true"></i>
			</a>
			<label for="CalendarAjaxCalendarController_month" class="sr-only">{@date.month}</label>
			<select class="grouped-element# IF C_MINI_MODULE # small# ENDIF #" name="CalendarAjaxCalendarController_month" id="CalendarAjaxCalendarController_month" onchange="ChangeMonth(jQuery('#CalendarAjaxCalendarController_year').val(), jQuery('#CalendarAjaxCalendarController_month').val());">
				# START months #
					<option value="{months.VALUE}"# IF months.SELECTED # selected="selected"# ENDIF #>{months.NAME}</option>
				# END months #
			</select>
			<label for="CalendarAjaxCalendarController_year" class="sr-only">{@date.year}</label>
			<select class="grouped-element# IF C_MINI_MODULE # small# ENDIF #" name="CalendarAjaxCalendarController_year" id="CalendarAjaxCalendarController_year" onchange="ChangeMonth(jQuery('#CalendarAjaxCalendarController_year').val(), jQuery('#CalendarAjaxCalendarController_month').val());">
				# START years #
					<option value="{years.VALUE}"# IF years.SELECTED # selected="selected"# ENDIF #>{years.NAME}</option>
				# END years #
			</select>
			<a class="grouped-element bgc link-color" href="#" onclick="ChangeMonth(${escapejs(NEXT_YEAR)}, ${escapejs(NEXT_MONTH)});return false;" aria-label="{NEXT_MONTH_TITLE}">
				<i class="fa fa-caret-right" aria-hidden="true"></i>
			</a>
		</div>
	</form>
</div>

<div# IF C_MINI_MODULE # class="cell-table"# ENDIF #>
	<table class="# IF C_MINI_MODULE #mini-calendar # ENDIF #calendar-table">
		<thead>
			<tr>
				<th class="calendar-cell"><span class="sr-only">{@date.week.mini}</span></th>
				<th class="text-strong calendar-cell">{@date.monday.mini}</th>
				<th class="text-strong calendar-cell">{@date.tuesday.mini}</th>
				<th class="text-strong calendar-cell">{@date.wednesday.mini}</th>
				<th class="text-strong calendar-cell">{@date.thursday.mini}</th>
				<th class="text-strong calendar-cell">{@date.friday.mini}</th>
				<th class="text-strong calendar-cell">{@date.saturday.mini}</th>
				<th class="text-strong calendar-cell">{@date.sunday.mini}</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				# START day #
					<td class="{day.CLASS}">
						# IF day.C_MONTH_DAY #
							<a# IF day.C_HAS_TITLE # aria-label="{day.TITLE}"# ENDIF # href="{day.U_DAY_EVENTS}" class="offload calendar-cell">
								{day.DAY}
								# IF day.C_COLOR #
									<div class="event-container">
										# START day.colors #
											<span class="event-spot" style="background-color: {day.colors.COLOR}"></span>
										# END day.colors #
									</div>
								# ENDIF #
							</a>
						# ENDIF #
						# IF day.C_WEEK_LABEL #<span class="calendar-cell">{day.DAY}</span># ENDIF #
					</td>
				# IF day.CHANGE_LINE #
			</tr>
			<tr>
				# ENDIF #
				# END day #
			</tr>
			# IF C_DISPLAY_LEGEND #
				<tr>
					<td colspan="8" class="legend-line"># INCLUDE LEGEND #</td>
				</tr>
			# ENDIF #
		</tbody>
	</table>
</div>
