# IF C_INCLUDE_JS #
	<script>
		function insert_date(field, input_field, date, calendar_number)
		{
			if (jQuery('\#' + input_field)) {
				if (date) {
					jQuery('\#' + input_field).val(date);

					var array = date.split('-');
					year = array[0];
					month = array[1];
					day = array[2];
					jQuery('\#' + input_field + '_link').attr('onclick', "xmlhttprequest_calendar('" + field + "', '" + input_field + "', '" + year + "', '" + month + "', '" + day + "', '" + calendar_number + "', 1);return false;");
					jQuery('\#' + input_field).attr('onclick', "xmlhttprequest_calendar('" + field + "', '" + input_field + "', '" + year + "', '" + month + "', '" + day + "', '" + calendar_number + "');return false;");

					jQuery('\#' + input_field).focus();
				}
			}
		}

		function xmlhttprequest_calendar(field, input_field, year, month, day, calendar_number, toggle)
		{
			jQuery.ajax({
				url: '{PATH_TO_ROOT}/kernel/framework/ajax/mini_calendar_xmlhttprequest.php?input_field=' + input_field + '&field=' + field + '&calendar_number=' + calendar_number + '&d=' + day + '&m=' + month + '&y=' + year,
				type: "get",
				data: { token: '{TOKEN}' },
				success: function(returnData){
					jQuery('\#' + field).html(returnData);
				}
			});
		}
	</script>
# ENDIF #

<input type="text" size="11" maxlength="10" id="{CALENDAR_ID}" class="input-date grouped-element" name="{CALENDAR_ID}" value="{DEFAULT_DATE}" onclick="xmlhttprequest_calendar('{CALENDAR_ID}_date', ${escapejs(CALENDAR_ID)}, ${escapejs(YEAR)}, ${escapejs(MONTH)}, ${escapejs(DAY)}, ${escapejs(CALENDAR_NUMBER)});return false;"  onchange="hide_mini_calendar(${escapejs(CALENDAR_NUMBER)});return false;" placeholder="${LangLoader::get_message('date.format', 'date-lang')}">
<div class="calendar-container modal-container cell-modal cell-tile grouped-element">
	<a class="bgc-full link-color" data-modal data-target="calendar{CALENDAR_NUMBER}" id="{CALENDAR_ID}_link" href="#" onclick="xmlhttprequest_calendar('{CALENDAR_ID}_date', ${escapejs(CALENDAR_ID)}, ${escapejs(YEAR)}, ${escapejs(MONTH)}, ${escapejs(DAY)}, ${escapejs(CALENDAR_NUMBER)}, 1);return false;" aria-label="{@form.date.selector}"><i class="fa fa-calendar-alt" aria-hidden="true"></i></a>
	<div id="calendar{CALENDAR_NUMBER}" class="modal modal-animation">
		<div class="close-modal"></div>
		<div id="{CALENDAR_ID}_date" class="content-panel cell calendar-block"></div>
	</div>
</div>
