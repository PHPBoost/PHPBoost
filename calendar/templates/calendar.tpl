		{JAVA}


		# START show #

		<form action="calendar.php" method="get">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top">
					<strong>{L_CALENDAR} {ADMIN_CALENDAR}</strong>
				</div>
				<div class="module_contents" style="text-align:center;">
					{ADD}	
					<select name="m">
						# START show.month #
						{show.month.MONTH}
						# END show.month #	
					</select>
					&nbsp;
					<select class="nav" name="y">
					# START show.year #
						{show.year.YEAR}
					# END show.year #
					</select>	
					<input type="hidden" name="d" value="1" />
					&nbsp;
					<input type="submit" name="date" value="{L_SUBMIT}" class="submit" />	
					
					<br /><br />
					<span id="act"></span>
					<table class="module_table" style="width: auto;padding:5px;"> 
						<tr>
							<td class="row3">
								<a href="calendar{U_PREVIOUS}" title="">«</a>
							</td> 
							<td colspan="5" class="row3">
								{DATE} 
							</td> 
							<td class="row3">
								<a href="calendar{U_NEXT}" title="">»</a>
							</td> 
						</tr>
						<tr style="text-align:center;">
							# START show.day #
							{show.day.L_DAY}
							# END show.day #
						</tr>
						<tr style="text-align:center;">			
							# START show.calendar #					
							{show.calendar.DAY}
							{show.calendar.TR}
							# END show.calendar #
						</tr>
						<tr>
							<td style="width:16px;" class="row3">
								{U_PREVIOUS_EVENT}
							</td>
							<td  colspan="5" class="row3">
								{L_EVENTS} 
							</td>
							<td style="width:16px;" class="row3">
								{U_NEXT_EVENT}
							</td> 
						</tr>
					</table>
				</div>
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"></div>
			</div>
		</form>
		
		# IF C_ERROR_HANDLER #
		<br />
		<span id="errorh"></span>
		<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
			<br />	
		</div>
		<br />		
		# ENDIF #
		<br /><br />
		
		# START show.action #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<span class="text_strong" style="float:left;">{show.action.TITLE}</span>
				<span style="float:right;">{show.action.COM}{show.action.EDIT}{show.action.DEL}</span>
			</div>
			<div class="module_contents">
				{show.action.CONTENTS}
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left;padding-top:4px;padding-bottom:4px;">
					{show.action.LOGIN}
				</div>				
				<div class="text_small" style="padding:4px;text-align: right;">
					{L_ON}:&nbsp;&nbsp;{show.action.DATE}
				</div>
			</div>
		</div>
		<br /><br />

		# INCLUDE handle_com #
		
		# END show.action #

		# END show #


		# START form #

		<script type="text/javascript">
		<!--
		function check_form_cl(){
			if(document.getElementById('title').value == "") {
				alert("{L_REQUIRE_TITLE}");
				return false;
			}
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
			}
			return true;
		}

		-->
		</script>

		<script type="text/javascript" src="../templates/{THEME}/images/calendar.js"></script>
		# IF C_ERROR_HANDLER #
		<span id="errorh"></span>
		<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
			<br />	
		</div>
		# ENDIF #
		
		<form action="calendar.php{form.UPDATE}" method="post" onsubmit="return check_form_cl();" class="fieldset_content" style="width:70%">
			<fieldset>
				<legend>{L_EDIT_EVENT}</legend>
				<dl class="overflow_visible">
					<dt><label for="date">* {L_DATE_CALENDAR}</label></dt>
					<dd><label>
						{L_ON}&nbsp;
						<label><input type="text" size="8" maxlength="8" id="date" name="date" value="{form.DATE}" class="text" /></label> 
						
						<div style="position:relative;z-index:100;top:220px;left:90px;float:left;display:none;" id="calendar1">
							<div id="cl_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
							</div>
						</div>
						<a onClick="xmlhttprequest_calendar('cl_date', '?input_field=date&amp;field=cl_date&amp;d={form.DAY_DATE}&amp;m={form.MONTH_DATE}&amp;y={form.YEAR_DATE}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img class="valign_middle" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
					
						{L_AT}
						<label><input type="text" size="2" maxlength="2" name="hour" value="{form.HOUR}" class="text" /></label> H <label><input type="text" size="2" maxlength="2" name="min" value="{form.MIN}" class="text" /></label>
					</dd>
				</dl>
				<dl>
					<dt><label for="title">* {L_TITLE}</label></dt>
					<dd><label><input type="text" maxlength="50" size="50" maxlength="150" id="title" name="title" value="{form.TITLE}" class="text" /></label></dd>
				</dl>
				<br />
				<label for="contents">* {L_ACTION}</label>
				# INCLUDE handle_bbcode #
				<label><textarea type="text" rows="10" cols="60" id="contents" name="contents">{form.CONTENTS}</textarea> </label>
			</fieldset>	
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
				&nbsp;&nbsp; 
				<input type="reset" value="{L_RESET}" class="reset" />
			</fieldset>
		</form>

		# END form #
