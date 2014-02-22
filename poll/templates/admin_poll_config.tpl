		<script type="text/javascript">
			<!--
			function check_form_conf()
			{
				if(document.getElementById('authorizations').value == "") {
					alert("{L_REQUIRE}");
					return false;
				}
				if(document.getElementById('cookie_lenght').value == "") {
					alert("{L_REQUIRE}");
					return false;
				}
				if(document.getElementById('cookie_name').value == "") {
					alert("{L_REQUIRE}");
					return false;
				}
				return true;
			}
			function check_select_multiple(id, status)
			{
				var i;
				
				for(i = 0; i < {NBR_POLL}; i++)
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
						<dt><label for="displayed_in_mini_module_list">{L_DISPLAYED_IN_MINI_MODULE_LIST}</label><br /><span>{L_DISPLAYED_IN_MINI_MODULE_LIST_EXPLAIN}</span></dt>
						<dd><label>
							<select id="displayed_in_mini_module_list" name="displayed_in_mini_module_list[]" size="5" multiple="multiple">
								{POLL_LIST}				
							</select>
							<br />
							<a class="small_link" href="javascript:check_select_multiple('displayed_in_mini_module_list', true);">{L_SELECT_ALL}</a>/<a class="small_link" href="javascript:check_select_multiple('displayed_in_mini_module_list', false);">{L_SELECT_NONE}</a>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="authorizations">* {L_RANK}</label></dt>
						<dd><label>
							<select name="authorizations" id="authorizations">
								# START select_authorizations #
									{select_authorizations.RANK}
								# END select_authorizations #
							</select>
						</label></dd>
					</dl>
				</fieldset>	
				<fieldset>
					<legend>{L_POLL_CONFIG_ADVANCED}</legend>
					<dl>
						<dt><label for="cookie_name">* {L_COOKIE_NAME}</label></dt>
						<dd><label><input type="text" maxlength="25" size="25" name="cookie_name" id="cookie_name" value="{COOKIE_NAME}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="cookie_lenght">* {L_COOKIE_LENGHT}</label></dt>
						<dd><label><input type="text" maxlength="11" size="6" name="cookie_lenght" id="cookie_lenght" value="{COOKIE_LENGHT}" class="text" /></label> {L_DAYS}</dd>
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
		