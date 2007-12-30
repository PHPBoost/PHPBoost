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
			<form action="admin_poll_config.php" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_POLL_CONFIG}</legend>
					<dl>
						<dt><label for="nbr_articles_max">{L_POLL_MINI}</label></dt>
						<dd><label>
							<select name="poll_mini">				
							# START select #				
							{select.POLL_CURRENT}				
							# END select #				
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_cat_max">* {L_RANK}</label></dt>
						<dd><label>
							<select name="poll_auth" id="poll_auth">
								# START select_auth #
									{select_auth.RANK}
								# END select_auth #
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_column">* {L_COOKIE_NAME}</label></dt>
						<dd><label><input type="text" maxlength="25" size="25" name="poll_cookie" id="poll_cookie" value="{COOKIE_NAME}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="note_max">* {L_COOKIE_LENGHT}</label></dt>
						<dd><label><input type="text" maxlength="11" size="6" name="poll_cookie_lenght" id="poll_cookie_lenght" value="{COOKIE_LENGHT}" class="text" /></label> {L_HOUR}</dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</table>
			</form>
		</div>
		