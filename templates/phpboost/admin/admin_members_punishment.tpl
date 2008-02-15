		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_MEMBERS_MANAGEMENT}</li>
				<li>
					<a href="admin_members.php"><img src="../templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php" class="quick_link">{L_MEMBERS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_members.php?add=1"><img src="../templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php?add=1" class="quick_link">{L_MEMBERS_ADD}</a>
				</li>
				<li>
					<a href="admin_members_config.php"><img src="../templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_config.php" class="quick_link">{L_MEMBERS_CONFIG}</a>
				</li>
				<li>
					<a href="admin_members_punishment.php"><img src="../templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_punishment.php" class="quick_link">{L_MEMBERS_PUNISHMENT}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			
			<table class="module_table">
				<tr>
					<th colspan="3">
						{L_INFO_MANAGEMENT}
					</th>
				</tr>
				<tr>			
					<td style="text-align:center;width:34%" class="row2">
						<a href="admin_members_punishment.php?action=warning" title="{L_USERS_WARNING}"><img src="../templates/{THEME}/images/notice.png" alt="{L_USERS_WARNING}" /><br />{L_USERS_WARNING}</a>
					</td>
					<td style="text-align:center;width:33%" class="row2">
						<a href="admin_members_punishment.php?action=punish" title="{L_USERS_PUNISHMENT}"><img src="../templates/{THEME}/images/stop.png" alt="{L_USERS_PUNISHMENT}" /><br />{L_USERS_PUNISHMENT}</a>
					</td>
					<td style="text-align:center;width:33%" class="row2">
						<a href="admin_members_punishment.php?action=ban" title="{L_USERS_WARNING}"><img src="../templates/{THEME}/images/forbidden.png" alt="{L_USERS_BAN}" /><br />{L_USERS_BAN}</a>
					</td>
				</tr>
			</table>
			
			# START user_list #
			<script type="text/javascript">
			<!--
				function XMLHttpRequest_search()
				{
					var login = document.getElementById("login").value;
					if( login != "" )
					{
						data = "login=" + login + "&admin=1";
						var xhr_object = xmlhttprequest_init('../includes/xmlhttprequest.php?{U_XMLHTTPREQUEST}=1');
						xhr_object.onreadystatechange = function() 
						{
							if( xhr_object.readyState == 4 ) 
							{
								document.getElementById("xmlhttprequest_result_search").innerHTML = xhr_object.responseText;
								hide_div("xmlhttprequest_result_search");
							}
						}
						xmlhttprequest_sender(xhr_object, data);
					}	
					else
					{
						alert("{L_REQUIRE_LOGIN}");
					}		
				}
				
				function hide_div(divID)
				{
					if( document.getElementById(divID) )
						document.getElementById(divID).style.display = 'block';
				}
				-->
			</script>
			
			<form action="admin_members_punishment{U_ACTION}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_SEARCH_MEMBER}</legend>
					<dl>
						<dt><label for="login">{L_SEARCH_MEMBER}</label><br /><span>{L_JOKER}</span></dt>
						<dd><label>
							<input type="text" size="20" maxlength="25" id="login" value="" name="login" class="text" />			
							<script type="text/javascript">
							<!--								
								document.write('<input value="{L_SEARCH}" onclick="XMLHttpRequest_search(this.form);" type="button" class="submit">');
							-->
							</script>
							<noscript>
								<input type="submit" name="search_member" value="{L_SEARCH}" class="submit" />
							</noscript>
							<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
						</label></dd>
					</dl>
				</fieldset>
			</form>
				
			<table class="module_table">
				<tr>			
					<th style="width:25%;">{L_LOGIN}</th>
					<th style="width:25%;">{L_INFO}</th>
					<th style="width:25%;">{L_ACTION_USER}</th>
					<th style="width:25%;">{L_PM}</th>
				</tr>
				# START user_list.list #
				<tr>
					<td class="row1" style="text-align:center;width:25%;">
						<a href="../member/{user_list.list.U_PROFILE}">{user_list.list.LOGIN}</a>
					</td>
					<td class="row1" style="text-align:center;width:25%;">
						{user_list.list.INFO}
					</td>
					<td class="row1" style="text-align:center;width:25%;">
						{user_list.list.U_ACTION_USER}
					</td>
					<td class="row1" style="text-align:center;width:25%;">
						<a href="../member/pm{user_list.list.U_PM}"><img src="../templates/{THEME}/images/{LANG}/pm.png" alt="" /></a>
					</td>
				</tr>
				# END user_list.list #
				
				# START user_list.empty #
				<tr>
					<td class="row1" style="text-align:center;" colspan="4">
						{user_list.empty.NO_USER}
					</td>
				</tr>		
				# END user_list.empty #
			</table>
			</form>
			# END user_list #


			# START user_info #
			<script type="text/javascript">
			<!--
			function change_textarea_level(replace_value, regex)
			{
				var contents = document.getElementById('action_contents').innerHTML;
				{user_info.REPLACE_VALUE}		
				
				document.getElementById('action_contents').innerHTML = contents;	
			}
			-->
			</script>
			
			<form action="admin_members_punishment{user_info.U_ACTION_INFO}" method="post">		
			<table class="module_table">
				<tr>
					<td class="row1" style="width:30%;">
						{L_LOGIN}
					</td>
					<td class="row2">
						{user_info.LOGIN}
					</td>
				</tr>
				<tr>
					<td class="row1">
						{L_PM}
					</td>
					<td class="row2">
						<a href="../member/pm{user_info.U_PM}"><img src="../templates/{THEME}/images/{LANG}/pm.png" alt="PM" /></a>
					</td>
				</tr>
				<tr>
					<td class="row1" style="vertical-align:top">
						<label for="action_contents">{L_ALTERNATIVE_PM}</label>
					</td>
					<td class="row2">
						# INCLUDE handle_bbcode #
						<label><textarea name="action_contents" id="action_contents" class="post" rows="12">{ALTERNATIVE_PM}</textarea></label>
					</td>
				</tr>
				<tr>
					<td class="row1">
						{L_INFO_EXPLAIN}
					</td>
					<td class="row2">
						<span id="action_info">{user_info.INFO}</span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<select name="new_info" onchange="change_textarea_level(this.options[this.selectedIndex].value, {user_info.REGEX})">
							{user_info.SELECT}
						</select>
						<input type="submit" name="valid_user" value="{L_CHANGE_INFO}" class="submit" />					
					</td>
				</tr>
			</table>
			</form>
			
			# END user_info #
			
			
			# START user_ban #
			
			<form action="admin_members_punishment{user_ban.U_ACTION_INFO}" method="post">		
			<table class="module_table">
				<tr>
					<td class="row1" style="width:30%;">
						{L_LOGIN}
					</td>
					<td class="row2">
						{user_ban.LOGIN}
					</td>
				</tr>
				<tr>
					<td class="row1">
						{L_PM}
					</td>
					<td class="row2">
						<a href="../member/pm{user_ban.U_PM}"><img src="../templates/{THEME}/images/{LANG}/pm.png" alt="" /></a>
					</td>
				</tr>
				<tr>
					<td class="row1">
						{L_DELAY_BAN}
					</td>
					<td class="row2">
					<select name="user_ban">					
							# START user_ban.select_ban #	
								{user_ban.select_ban.TIME}
							# END user_ban.select_ban #						
						</select>
					</td>
				</tr>
				<tr>
					<td class="row2" colspan="2" style="text-align:center;">
						<input type="submit" name="valid_user" value="{L_BAN}" class="submit" />					
					</td>
				</tr>
			</table>
			</form>
			
			# END user_ban #
		</div>
		