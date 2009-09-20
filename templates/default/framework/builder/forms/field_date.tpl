		# IF C_NOT_ALREADY_INCLUDED #
		<script type="text/javascript">
		<!--
			var theme = '{THEME}';
		-->
		</script>
		<script type="text/javascript" src="../kernel/framework/js/calendar.js"></script>
		# ENDIF #
		<dl class="overflow_visible">
			<dt><label for="{ID}">{L_REQUIRE}{L_FIELD_TITLE}</label># IF L_EXPLAIN # <br /><span>{L_EXPLAIN}</span> # ENDIF #</dt>
			<dd>
				<label><input size="10" maxlength="10" type="text" class="text{CLASS}" id="{ID}" name="{NAME}" value="{VALUE}" onblur="{ONBLUR}" /></label>
				<div style="position:relative;z-index:100;top:26px;margin-left:25px;float:left;display:none;" id="calendar{INSTANCE}">
					<div id="calendarf{INSTANCE}" class="calendar_block" onmouseover="hide_calendar({INSTANCE}, 1);" onmouseout="hide_calendar({INSTANCE}, 0);"></div>
				</div>
				<a onmouseover="hide_calendar({INSTANCE}, 1);" onmouseout="hide_calendar({INSTANCE}, 0);" style="cursor:pointer;"
					onclick="xmlhttprequest_calendar('calendarf{INSTANCE}', '?input_field={NAME}&amp;field=calendarf{INSTANCE}&amp;lyear=1&amp;d={CALENDAR_DAY}&amp;m={CALENDAR_MONTH}&amp;y={CALENDAR_YEAR}');display_calendar({INSTANCE});">
					<img class="valign_middle" id="imgcalendarf{INSTANCE}" src="../templates/{THEME}/images/calendar.png" alt="" />
				</a>
			</dd>
		</dl>
