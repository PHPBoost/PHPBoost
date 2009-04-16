		<script type="text/javascript">
		<!--
			var theme = '{THEME}';
		-->
		</script>
		<script type="text/javascript" src="../kernel/framework/js/calendar.js"></script>
		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('question').value == "") {
				alert("{L_REQUIRE_QUESTION}");
				return false;
		    }
			if(document.getElementById('reponses').value == "") {
				alert("{L_REQUIRE_ANSWER}");
				return false;
		    }
				if(document.getElementById('type').value == "") {
				alert("{L_REQUIRE_ANSWER_TYPE}");
				return false;
		    }

			return true;
		}

		function add_field(i, i_max) 
		{
			var i2 = i + 1;

			if( document.getElementById('a'+i) )
				document.getElementById('a'+i).innerHTML = '<input type="text" size="40" name="a'+i+'" value="" class="text" /><br /><span id="a'+i2+'"></span>';
			if( document.getElementById('v'+i) )
				document.getElementById('v'+i).innerHTML = '<input type="text" size="3" name="v'+i+'" value="" class="text" /><br /><span id="v'+i2+'"></span>';		
			if( document.getElementById('s'+i) )
				document.getElementById('s'+i).innerHTML = (i < i_max) ? '<span id="s'+i2+'"><a href="javascript:add_field('+i2+', '+i_max+')"><img src="../templates/{THEME}/images/form/plus.png" alt="+" /></a></span>' : '';
		}
		
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_POLL_MANAGEMENT}</li>
				<li>
					<a href="admin_poll.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll.php" class="quick_link">{L_POLL_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_poll_add.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll_add.php" class="quick_link">{L_POLL_ADD}</a>
				</li>
				<li>
					<a href="admin_poll_config.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll_config.php" class="quick_link">{L_POLL_CONFIG}</a>
				</li>
			</ul>
		</div> 
		
		<div id="admin_contents">
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
					<span id="errorh"></span>
					<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
						<br />	
					</div>
			</div>
			# ENDIF #
			
			<form action="admin_poll.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_POLL_MANAGEMENT}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="question">* {L_QUESTION}</label></dt>
						<dd><label><input type="text" size="45" maxlength="100" id="question" name="question" value="{QUESTIONS}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="type">* {L_ANSWER_TYPE}</label></dt>
						<dd>
							<label><input type="radio" name="type" id="type" {TYPE_UNIQUE} value="1" checked="checked" /> {L_SINGLE}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" name="type" {TYPE_MULTIPLE} value="0" /> {L_MULTIPLE}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="archive">* {L_ARCHIVED}</label></dt>
						<dd>
							<label><input type="radio" name="archive" {ARCHIVES_ENABLED} value="1" /> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" name="archive" {ARCHIVES_DISABLED} id="archive" value="0" checked="checked" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label>* {L_ANSWERS}</label></dt>
						<dd><label>
							<table style="border:none;border-spacing:0">
								<tr>
									<th style="text-align:center;">
										{L_ANSWERS}
									</th>
									<th style="text-align:center;">
										{L_NUMBER_VOTE}
									</th>
								</tr>
								<tr>
									<td class="row2" style="text-align:center;">
										# START answers #			
										<label><input type="text" size="40" name="a{answers.ID}" value="{answers.ANSWER}" class="text" /></label><br />			
										# END answers #
										<span id="a{MAX_ID}"></span>
									</td>
									<td class="row2" style="text-align:center;">
										# START votes #			
										<label><input type="text" size="3" name="v{votes.ID}" value="{votes.VOTES}" class="text" /> {votes.PERCENT}</label><br />			
										# END votes #	
										<span id="v{MAX_ID}"></span>	
									</td>	
								</tr>
								<tr>
									<td style="text-align:center;" colspan="2">
										<script type="text/javascript">
										<!--
											if( {MAX_ID} < 19 )
												document.write('<span id="s{MAX_ID}"><a href="javascript:add_field({MAX_ID}, 19)"><img src="../templates/{THEME}/images/form/plus.png" alt="+" /></a></span>');
										-->
										</script>
											
									</td>
								</tr>
							</table>
						</label></dd>
					</dl>
				</fieldset>		

				<fieldset>
					<legend>{L_DATE}</legend>
					<dl class="overflow_visible">
						<dt><label for="release_date">* {L_RELEASE_DATE}</label></dt>
						<dd>
							<div onclick="document.getElementById('start_end_date').checked = true;">
								<label><input type="radio" value="2" name="visible" id="start_end_date" {VISIBLE_WAITING} /></label>
								<input type="text" size="8" maxlength="8" id="start" name="start" value="{START}" class="text" /> 
								<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
									<div id="start_date" class="calendar_block" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
									</div>
								</div>
								<a onclick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgstart_date" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
								
								{L_UNTIL}&nbsp;
								
								<input type="text" size="8" maxlength="8" id="end" name="end" value="{END}" class="text" /> 
								<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
									<div id="end_date" class="calendar_block" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);">							
									</div>
								</div>
								<a onclick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(2);" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgend_date" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
							</div>
							<label><input type="radio" value="1" name="visible" {VISIBLE_ENABLED} id="release_date" /> {L_IMMEDIATE}</label>
							<br />
							<label><input type="radio" value="0" name="visible" {VISIBLE_UNAPROB} /> {L_UNAPROB}</label>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="current_date">* {L_POLL_DATE}</label></dt>
						<dd><label>
							<input type="text" size="8" maxlength="8" id="current_date" name="current_date" value="{CURRENT_DATE}" class="text" /> 
							<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar3">
								<div id="current" class="calendar_block" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);">							
								</div>
							</div>
							<a onclick="xmlhttprequest_calendar('current', '?input_field=current_date&amp;field=current&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(3);" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgcurrent" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
							
							{L_AT}
							<input type="text" size="2" maxlength="2" name="hour" value="{HOUR}" class="text" /> H <input type="text" size="2" maxlength="2" name="min" value="{MIN}" class="text" />
						</label></dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="id" value="{IDPOLL}" />
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>
			</form>
		</div>
