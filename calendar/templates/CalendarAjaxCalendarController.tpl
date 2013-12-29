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
		# IF C_FORM #
		<div id="date_select_form" class="center">
			# INCLUDE FORM #
		</div>
		# ENDIF #
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