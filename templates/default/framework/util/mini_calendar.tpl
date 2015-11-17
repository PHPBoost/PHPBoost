# IF C_INCLUDE_JS #
<script>
<!--
function insert_date(field, input_field, date) 
{
	if (jQuery('\#' + input_field)) {
		if (date) {
			jQuery('\#' + input_field).val(date);
			
			var array = date.split('-');
			year = array[0];
			month = array[1];
			day = array[2];
			jQuery('\#{CALENDAR_ID}_link').attr('href', "javascript:xmlhttprequest_calendar('" + field + "', '" + input_field + "', '" + year + "', '" + month + "', '" + day + "', 1);");
			jQuery('\#{CALENDAR_ID}').attr('onclick', "javascript:xmlhttprequest_calendar('" + field + "', '" + input_field + "', '" + year + "', '" + month + "', '" + day + "');return false;");
			
			jQuery('\#' + input_field).focus();
			jQuery('\#calendar{CALENDAR_NUMBER}').hide();
		}
	}
}

function xmlhttprequest_calendar(field, input_field, year, month, day, toggle)
{
	jQuery.ajax({
		url: '{PATH_TO_ROOT}/kernel/framework/ajax/mini_calendar_xmlhttprequest.php?input_field=' + input_field + '&field=' + field + '&d=' + day + '&m=' + month + '&y=' + year,
		type: "get",
		data: { token: '{TOKEN}' },
		success: function(returnData){
			jQuery('\#' + field).html(returnData);
			
			if (toggle)
				jQuery('\#calendar{CALENDAR_NUMBER}').toggle();
			else
				jQuery('\#calendar{CALENDAR_NUMBER}').show();
		}
	});
}
-->
</script>
# ENDIF #

<input type="text" size="11" maxlength="10" id="{CALENDAR_ID}" class="input-date" name="{CALENDAR_ID}" value="{DEFAULT_DATE}" onclick="xmlhttprequest_calendar('{CALENDAR_ID}_date', ${escapejs(CALENDAR_ID)}, ${escapejs(YEAR)}, ${escapejs(MONTH)}, ${escapejs(DAY)});return false;" onfocusout="jQuery('\#calendar{CALENDAR_NUMBER}').hide();" placeholder="${LangLoader::get_message('date_format', 'date-common')}">
<div class="calendar-container">
	<a id="{CALENDAR_ID}_link" href="javascript:xmlhttprequest_calendar('{CALENDAR_ID}_date', ${escapejs(CALENDAR_ID)}, ${escapejs(YEAR)}, ${escapejs(MONTH)}, ${escapejs(DAY)}, 1);"><i class="fa fa-calendar"></i></a>
	<div id="calendar{CALENDAR_NUMBER}" style="display:none;{CALENDAR_STYLE}">
		<div id="{CALENDAR_ID}_date" class="calendar-block"></div>
	</div>
</div>
