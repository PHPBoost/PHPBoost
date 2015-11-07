# IF C_INCLUDE_JS #
<script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/calendar.js"></script>
# ENDIF #

<input type="text" size="11" maxlength="10" id="{CALENDAR_ID}" class="input-date" name="{CALENDAR_ID}" value="{DEFAULT_DATE}" placeholder="${LangLoader::get_message('date_format', 'date-common')}">
<div style="display: inline-block;">
	<a onclick="xmlhttprequest_calendar('{CALENDAR_ID}_date', '?input_field={CALENDAR_ID}&amp;field={CALENDAR_ID}_date&amp;d={DAY}&amp;m={MONTH}&amp;y={YEAR}');display_calendar({CALENDAR_NUMBER});" onmouseover="hide_calendar({CALENDAR_NUMBER}, 1);" onmouseout="hide_calendar({CALENDAR_NUMBER}, 0);" style="cursor:pointer;"><i class="fa fa-calendar"></i></a>
	<div id="calendar{CALENDAR_NUMBER}" style="display:none;{CALENDAR_STYLE}" >
		<div id="{CALENDAR_ID}_date" class="calendar-block" onmouseover="hide_calendar({CALENDAR_NUMBER}, 1);" onmouseout="hide_calendar({CALENDAR_NUMBER}, 0);"></div>
	</div>
</div>
<script>
<!--
	association_name_id['{FORM_NAME}'] = '{CALENDAR_ID}';
-->
</script>