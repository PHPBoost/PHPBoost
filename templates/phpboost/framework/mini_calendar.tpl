		# IF C_INCLUDE_JS #
			<script type="text/javascript" src="../kernel/framework/js/calendar.js"></script>
			<script type="text/javascript">
			<!--
				function check_form(form_name)
				{
					reg_exp = new RegExp(\"[0-9]{2}/[0-9]{2}/[0-9]{2}\", \"g\");");
					return document.getElementById(form_name).value.match(reg_exp);
				}
			-->
			</script>
		# ENDIF #
		
		<input type="text" size="8" maxlength="8" id="{CALENDAR_ID}" name="creation" value="{DEFAULT_DATE}" class="text" />
		<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar{CALENDAR_NUMBER}">
			<div id="{CALENDAR_ID}_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar({CALENDAR_NUMBER}, 1);" onmouseout="hide_calendar({CALENDAR_NUMBER}, 0);">							
			</div>
		</div>
		<a onClick="xmlhttprequest_calendar('{CALENDAR_ID}_date', '?input_field={CALENDAR_ID}&amp;field={CALENDAR_ID}_date&amp;d={DAY}&amp;m={MONTH}&amp;y={YEAR}');display_calendar({CALENDAR_NUMBER});" onmouseover="hide_calendar({CALENDAR_NUMBER}, 1);" onmouseout="hide_calendar({CALENDAR_NUMBER}, 0);" style="cursor:pointer;"><img class="valign_middle" src="../templates/{THEME}/images/calendar.png" alt="" /></a>