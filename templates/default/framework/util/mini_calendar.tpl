		# IF C_INCLUDE_JS #
			<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/calendar.js"></script>
		# ENDIF #
		
		<input type="text" size="8" maxlength="8" id="{CALENDAR_ID}" name="{FORM_NAME}" value="{DEFAULT_DATE}" class="text" />
		<div style="position:relative;z-index:100;top:26px;margin-left:25px;float:left;display:none;{CALENDAR_STYLE}" id="calendar{CALENDAR_NUMBER}">
			<div id="{CALENDAR_ID}_date" class="calendar_block" onmouseover="hide_calendar({CALENDAR_NUMBER}, 1);" onmouseout="hide_calendar({CALENDAR_NUMBER}, 0);">							
			</div>
		</div>
		<a onclick="xmlhttprequest_calendar('{CALENDAR_ID}_date', '?input_field={CALENDAR_ID}&amp;field={CALENDAR_ID}_date&amp;d={DAY}&amp;m={MONTH}&amp;y={YEAR}');display_calendar({CALENDAR_NUMBER});" onmouseover="hide_calendar({CALENDAR_NUMBER}, 1);" onmouseout="hide_calendar({CALENDAR_NUMBER}, 0);" style="cursor:pointer;"><img class="valign_middle" id="img{CALENDAR_ID}_date" src="../templates/{THEME}/images/calendar.png" alt="" /></a>					

		<script type="text/javascript">
		<!--
			association_name_id['{FORM_NAME}'] = '{CALENDAR_ID}';
		-->
		</script>