<script>
	function ChangeMonth(year, month)
	{
		jQuery.ajax({
			url: ${escapejs(U_AJAX_CALENDAR)} + year + '/' + month + '/' + {MINI_MODULE},
			success: function(returnData){
				jQuery('#calendar').html(returnData);
			}
		});

		# IF NOT C_MINI_MODULE #
			jQuery.ajax({
				url: ${escapejs(U_AJAX_EVENTS)} + year + '/' + month,
				success: function(returnData){
					jQuery('#events').html(returnData);
				}
			});
		# ENDIF #
	}
</script>

<div id="date_select_form" # IF C_MINI_MODULE #class="cell-form"# ENDIF #>
	<form method="post">
		<div class="grouped-inputs">
			<a class="grouped-element bgc link-color" href="#" onclick="ChangeMonth(${escapejs(PREVIOUS_YEAR)}, ${escapejs(PREVIOUS_MONTH)});return false;" aria-label="{PREVIOUS_MONTH_TITLE}"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
			<label for="CalendarAjaxCalendarController_month" class="sr-only">{@month}</label>
			<select# IF C_MINI_MODULE # class="small"# ENDIF # name="CalendarAjaxCalendarController_month" id="CalendarAjaxCalendarController_month" onchange="ChangeMonth(jQuery('#CalendarAjaxCalendarController_year').val(), jQuery('#CalendarAjaxCalendarController_month').val());">
				# START months #
					<option value="{months.VALUE}"# IF months.SELECTED # selected="selected"# ENDIF #>{months.NAME}</option>
				# END months #
			</select>
			<label for="CalendarAjaxCalendarController_year" class="sr-only">{@year}</label>
			<select# IF C_MINI_MODULE # class="small"# ENDIF # name="CalendarAjaxCalendarController_year" id="CalendarAjaxCalendarController_year" onchange="ChangeMonth(jQuery('#CalendarAjaxCalendarController_year').val(), jQuery('#CalendarAjaxCalendarController_month').val());">
				# START years #
					<option value="{years.VALUE}"# IF years.SELECTED # selected="selected"# ENDIF #>{years.NAME}</option>
				# END years #
			</select>
			<a class="grouped-element bgc link-color" href="#" onclick="ChangeMonth(${escapejs(NEXT_YEAR)}, ${escapejs(NEXT_MONTH)});return false;" aria-label="{NEXT_MONTH_TITLE}"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
		</div>
	</form>
</div>

<div# IF C_MINI_MODULE # class="cell-table"# ENDIF #>
	<table class="# IF C_MINI_MODULE #mini-calendar # ENDIF #calendar-table">
		<thead>
			<tr>
				<th><span class="sr-only">{@week_mini}</span></th>
				<th class="text-strong">{@monday_mini}</th>
				<th class="text-strong">{@tuesday_mini}</th>
				<th class="text-strong">{@wednesday_mini}</th>
				<th class="text-strong">{@thursday_mini}</th>
				<th class="text-strong">{@friday_mini}</th>
				<th class="text-strong">{@saturday_mini}</th>
				<th class="text-strong">{@sunday_mini}</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				# START day #
				<td class="{day.CLASS}"# IF day.C_COLOR # style="background-color:{day.COLOR}"# ENDIF #>
					# IF day.C_MONTH_DAY #<a href="{day.U_DAY_EVENTS}">{day.DAY}</a># ENDIF #
					# IF day.C_WEEK_LABEL #{day.DAY}# ENDIF #
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
