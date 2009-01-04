		<script type="text/javascript">
		<!--
		function check_form_conf(o)
		{
			if(o.quotes_auth.value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			return true;
		}	
		-->
		</script>
				
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_QUOTES}</li>
				<li>
					<a href="admin_quotes.php"><img src="quotes.png" alt="" /></a>
					<br />
					<a href="admin_quotes.php" class="quick_link">{L_QUOTES_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<form action="admin_quotes.php" method="post" onsubmit="return check_form_conf(this);" class="fieldset_content">
				<fieldset>
					<legend>{L_QUOTES_CONFIG}</legend>
					<dl>
						<dt><label for="quotes_auth">* {L_RANK}</label></dt>
						<dd><select name="quotes_auth" id="quotes_auth">
								# START select_auth #
									{select_auth.RANK}
								# END select_auth #
							</select>
						</dd>
					</dl>
					<dl>
						<dt><label for="quotes_limit">* {L_LIMIT}</label></dt>
						<dd><input type="text" id="quotes_limit" name="quotes_limit" value="0" />
						</dd>
					</dl>
					<dl>
						<dt><label for="quotes_display_list">* {L_DISPLAY_LIST}</label></dt>
						<dd><input type="text" id="quotes_display_list" name="quotes_display_list" value="0" />
						</dd>
					</dl>					
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />			
				</fieldset>
			</form>
		</div>
		