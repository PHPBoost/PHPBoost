		# IF C_INCLUDE_JS #
			<script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/calendar.js"></script>
		# ENDIF #
		
		<input type="text" size="11" maxlength="10" id="{CALENDAR_ID}" name="{CALENDAR_ID}" value="{DEFAULT_DATE}" placeholder="${LangLoader::get_message('date_format', 'date-common')}">
		<div style="position:absolute;z-index:100;display:none;{CALENDAR_STYLE}" id="calendar{CALENDAR_NUMBER}">
			<div id="{CALENDAR_ID}_date" class="calendar-block" onmouseover="hide_calendar({CALENDAR_NUMBER}, 1);" onmouseout="hide_calendar({CALENDAR_NUMBER}, 0);">
			</div>
		</div>
		<a onclick="xmlhttprequest_calendar('{CALENDAR_ID}_date', '?input_field={CALENDAR_ID}&amp;field={CALENDAR_ID}_date&amp;d={DAY}&amp;m={MONTH}&amp;y={YEAR}');display_calendar({CALENDAR_NUMBER});" onmouseover="hide_calendar({CALENDAR_NUMBER}, 1);" onmouseout="hide_calendar({CALENDAR_NUMBER}, 0);" style="cursor:pointer;" class="fa fa-calendar"></a>

		<script>
		<!--
			association_name_id['{FORM_NAME}'] = '{CALENDAR_ID}';
		-->
		</script>