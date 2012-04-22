		<script type="text/javascript">
		<!--
			var theme = '{THEME}';
		-->
		</script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/calendar.js"></script>
		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_MAINTAIN}</li>
				<li>
					<a href="admin_maintain.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/maintain.png" alt="" /></a>
					<br />
					<a href="admin_maintain.php" class="quick_link">{L_MAINTAIN}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			<form action="admin_maintain.php" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_SET_MAINTAIN}</legend>
					<dl class="overflow_visible">
						<dt><label for="maintain">{L_SET_MAINTAIN}</label></dt>
						<dd>
							<label>
								<input type="radio" name="maintain_check" value="0"{MAINTAIN_CHECK_NO}> {L_NO}
							</label>
							<br />	
							<label for="maintain" onclick="document.getElementById('maintain_check1').checked = 'checked';">
								<input type="radio" name="maintain_check" id="maintain_check1" value="1"{MAINTAIN_CHECK_DELAY}>
								{L_DURING}
								<select name="maintain" id="maintain">				
									{DELAY_MAINTAIN_OPTION}				
								</select>
							</label>
							<br />							
							<label>
								<input type="radio" name="maintain_check" id="maintain_check2" value="2"{MAINTAIN_CHECK_UNTIL}>
								{L_UNTIL}&nbsp;
								
								<input type="text" size="8" maxlength="8" id="end" name="end" value="{DATE_UNTIL}" class="text" />
								<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
									<div id="end_date" class="calendar_block" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);"></div>
								</div>
								<a onclick="document.getElementById('maintain_check2').checked = 'checked';xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date');display_calendar(2, 'end_date');" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgend_date" src="{PATH_TO_ROOT}/templates/{THEME}/images/calendar.png" alt="" /></a>
							</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="display_delay">{L_MAINTAIN_DELAY}</label></dt>
						<dd><label><input type="radio" {DISPLAY_DELAY_ENABLED} name="display_delay" id="display_delay" value="1" /> {L_YES}</label>
						&nbsp;&nbsp; 
						<label><input type="radio" {DISPLAY_DELAY_DISABLED} name="display_delay" value="0" /> {L_NO}</label></dd>
					</dl>
					<dl>
						<dt><label for="maintain_display_admin">{L_MAINTAIN_DISPLAY_ADMIN}</label></dt>
						<dd><label><input type="radio" {DISPLAY_ADMIN_ENABLED} name="maintain_display_admin" id="maintain_display_admin" value="1" /> {L_YES}</label>
						&nbsp;&nbsp; 
						<label><input type="radio" {DISPLAY_ADMIN_DISABLED} name="maintain_display_admin" value="0" /> {L_NO}</label></dd>
					</dl>
					<label for="contents">{L_MAINTAIN_TEXT}</label>
					<label>
						{KERNEL_EDITOR}	
						<textarea rows="14" cols="20" name="contents" id="contents">{MAINTAIN_CONTENTS}</textarea>
					</label>
					<br /><br />
					<dl>
						<dt><label for="auth_read">{L_AUTH_WEBSITE}</label></dt>
						<dd>{AUTH_WEBSITE}</dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					<script type="text/javascript">
					<!--				
					document.write('&nbsp;&nbsp;<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" type="button" class="submit" />');
					-->
					</script>
					&nbsp;&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />	
					<input type="hidden" name="token" value="{TOKEN}" />
				</fieldset>	
			</form>	
		</div>
		