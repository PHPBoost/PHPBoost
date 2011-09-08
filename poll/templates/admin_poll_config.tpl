		<script type="text/javascript">
			<!--
			function check_form_conf()
			{
				if(document.getElementById('poll_auth').value == "") {
					alert("{L_REQUIRE}");
					return false;
				}
				if(document.getElementById('poll_cookie_lenght').value == "") {
					alert("{L_REQUIRE}");
					return false;
				}
				if(document.getElementById('poll_cookie').value == "") {
					alert("{L_REQUIRE}");
					return false;
				}
				return true;
			}
			function check_select_multiple(id, status)
			{
				var i;
				
				if( document.getElementById('poll_mini_none') )
					document.getElementById('poll_mini_none').selected = '';
				for(i = 0; i < {NBR_MINI_POLL}; i++)
				{	
					if( document.getElementById(id + i) )
						document.getElementById(id + i).selected = status;			
				}
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
			<form action="admin_poll_config.php?token={TOKEN}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_POLL_CONFIG_MINI}</legend>
					<dl>
						<dt><label for="poll_mini">{L_POLL_MINI}</label><br /><span>{L_POLL_MINI_EXPLAIN}</span></dt>
						<dd><label>
							<select id="poll_mini" name="poll_mini[]" size="5" multiple="multiple">
								{MINI_POLL_LIST}				
							</select>
							<br />
							<a class="small_link" href="javascript:check_select_multiple('poll_mini', true);">{L_SELECT_ALL}</a>/<a class="small_link" href="javascript:check_select_multiple('poll_mini', false);">{L_SELECT_NONE}</a>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="poll_auth">* {L_RANK}</label></dt>
						<dd><label>
							<select name="poll_auth" id="poll_auth">
								# START select_auth #
									{select_auth.RANK}
								# END select_auth #
							</select>
						</label></dd>
					</dl>
				</fieldset>	
				<fieldset>
					<legend>{L_POLL_CONFIG_ADVANCED}</legend>
					<dl>
						<dt><label for="poll_cookie">* {L_COOKIE_NAME}</label></dt>
						<dd><label><input type="text" maxlength="25" size="25" name="poll_cookie" id="poll_cookie" value="{COOKIE_NAME}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="poll_cookie_lenght">* {L_COOKIE_LENGHT}</label></dt>
						<dd><label><input type="text" maxlength="11" size="6" name="poll_cookie_lenght" id="poll_cookie_lenght" value="{COOKIE_LENGHT}" class="text" /></label> {L_DAYS}</dd>
					</dl>
				</fieldset>
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>
		