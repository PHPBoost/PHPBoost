# IF C_INCLUDE_JS #
<script>
<!--
function insert_date(field, date) 
{
	if (jQuery('\#' + field)) {
		jQuery('\#' + field).val(date);
		jQuery('\#' + field).focus();
	}
}

function xmlhttprequest_calendar(field, vars, id, toggle)
{
	jQuery.ajax({
		url: '{PATH_TO_ROOT}/kernel/framework/ajax/mini_calendar_xmlhttprequest.php' + vars,
		type: "get",
		data: { token: '{TOKEN}' },
		success: function(returnData){
			jQuery('\#' + field).html(returnData);
			
			if (toggle)
				jQuery('\#calendar' + id).toggle();
		}
	});
}
-->
</script>
# ENDIF #

<input type="text" size="11" maxlength="10" id="{CALENDAR_ID}" class="input-date" name="{CALENDAR_ID}" value="{DEFAULT_DATE}" onfocus="xmlhttprequest_calendar('{CALENDAR_ID}_date', '?input_field={CALENDAR_ID}&amp;field={CALENDAR_ID}_date&amp;d={DAY}&amp;m={MONTH}&amp;y={YEAR}', {CALENDAR_NUMBER}, 1);return false;" onblur="jQuery('#calendar{CALENDAR_NUMBER}').hide();" placeholder="${LangLoader::get_message('date_format', 'date-common')}">
<div class="calendar-container">
	<a href="" onclick="xmlhttprequest_calendar('{CALENDAR_ID}_date', '?input_field={CALENDAR_ID}&amp;field={CALENDAR_ID}_date&amp;d={DAY}&amp;m={MONTH}&amp;y={YEAR}', {CALENDAR_NUMBER}, 1);return false;"><i class="fa fa-calendar"></i></a>
	<div id="calendar{CALENDAR_NUMBER}" style="display:none;{CALENDAR_STYLE}">
		<div id="{CALENDAR_ID}_date" class="calendar-block"></div>
	</div>
</div>
