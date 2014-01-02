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
		
		<div id="date_select_form" class="center">
			<form method="post" class="fieldset-content">
				<div class="horizontal-fieldset" id="CalendarAjaxCalendarController_choose-date" >
					<div style="display:inline-table; vertical-align:middle;">
						<div id="CalendarAjaxCalendarController_month_field" class="form-element" >
							<div class="form-field">
								<select name="CalendarAjaxCalendarController_month" id="CalendarAjaxCalendarController_month" onchange="ChangeMonth('/phpboost/calendar/?url=/ajax_month_calendar/' + $(CalendarAjaxCalendarController_year).value + '/' + $(CalendarAjaxCalendarController_month).value + '/' + {MINI_MODULE});">
									# START months #
									<option value="{months.VALUE}"# IF months.SELECTED # selected="selected"# ENDIF #>{months.NAME}</option>
									# END months #
								</select>
							</div>
						</div>
					</div>
					<div style="display:inline-table; vertical-align:middle;">
						<div id="CalendarAjaxCalendarController_year_field" class="form-element" >
							<div class="form-field">
								<select name="CalendarAjaxCalendarController_year" id="CalendarAjaxCalendarController_year" onchange="ChangeMonth('/phpboost/calendar/?url=/ajax_month_calendar/' + $(CalendarAjaxCalendarController_year).value + '/' + $(CalendarAjaxCalendarController_month).value + '/' + {MINI_MODULE});">
									# START years #
									<option value="{years.VALUE}"# IF years.SELECTED # selected="selected"# ENDIF #>{years.NAME}</option>
									# END years #
								</select>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		
		<div class="calendar-container">
			<div class="calendar-top-container options">
				<div class="calendar-top-l">
					<a class="fa fa-angle-double-left" onclick="ChangeMonth(${escapejs(U_PREVIOUS_MONTH)});" title="{PREVIOUS_MONTH_TITLE}"></a>
				</div>
				<div class="calendar-top-r">
					<a class="fa fa-angle-double-right" onclick="ChangeMonth(${escapejs(U_NEXT_MONTH)});" title="{NEXT_MONTH_TITLE}"></a>
				</div>
				<div class="calendar-top-content">{DATE}</div>
				
			</div>
			
			<div class="calendar-content">
				<table class="# IF C_MINI_MODULE #mini # END IF #calendar-table">
					<thead>
						<tr>
							<th></th>
							<th class="text-strong">{L_MONDAY}</th>
							<th class="text-strong">{L_TUESDAY}</th>
							<th class="text-strong">{L_WEDNESDAY}</th>
							<th class="text-strong">{L_THURSDAY}</th>
							<th class="text-strong">{L_FRIDAY}</th>
							<th class="text-strong">{L_SATURDAY}</th>
							<th class="text-strong">{L_SUNDAY}</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							# START day #
							<td class="{day.CLASS}"# IF day.C_COLOR # style="background-color:{day.COLOR}"# ENDIF #>
								# IF day.C_MONTH_DAY #<a title="{day.TITLE}" href="{day.U_DAY_EVENTS}">{day.DAY}</a># ENDIF #
								# IF day.C_WEEK_LABEL #{day.DAY}# ENDIF #
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
		</div>