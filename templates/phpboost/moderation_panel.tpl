		
		
		<table class="module_table">
			<tr>
				<th colspan="2">
					{L_MODERATION_PANEL}
				</th>
			</tr>
			<tr>
				<td class="row3" colspan="2">
					&bull; <a href="moderation_panel.php">{L_MODERATION_PANEL}</a>
				</td>			
			</tr>
			<tr>
				<td class="row2" style="width:150px;vertical-align:top;">
					<div id="dynamic_menu">
						<div onmouseover="show_menu(1);" onmouseout="hide_menu();">
							<h5 onclick="temporise_menu(1)">{L_MEMBERS}</h5>
							<div class="vertical_block" id="smenu1">								
								<ul>
									<li><a href="moderation_panel{U_WARNING}" style="background-image:url(../templates/{THEME}/images/admin/important.png);background-repeat:no-repeat ;background-position:5px;">{L_WARNING}</a></li>
									<li><a href="moderation_panel{U_PUNISH}" style="background-image:url(../templates/{THEME}/images/admin/stop.png);background-repeat:no-repeat;background-position:5px;">{L_PUNISHMENT}</a></li>
									<li><a href="moderation_panel{U_BAN}" style="background-image:url(../templates/{THEME}/images/admin/forbidden.png);background-repeat:no-repeat;background-position:5px;">{L_BAN}</a></li>
								</ul>
								<span class="dm_bottom"></span>
							</div>	
						</div>
						<br />
						<hr />
						<br />
						<div onmouseover="show_menu(2);" onmouseout="hide_menu();">
							<h5 onclick="temporise_menu(2)">{L_MODULES}</h5>
							<div class="vertical_block" id="smenu2">								
								<ul>
									# START list_modules #
									<li><a href="../{list_modules.MOD_NAME}/{list_modules.U_LINK}" {list_modules.DM_A_CLASS}>{list_modules.NAME}</a></li>
									# END list_modules #
								</ul>
								<span class="dm_bottom"></span>
							</div>	
						</div>						
					</div>
					<br /><br /><br /><br /><br /><br />
				</td>
				<td class="row2" style="vertical-align:top;padding:6px;">
					# START all_action #
					<table class="module_table">
						<tr>
							<th colspan="3">
								{L_INFO_MANAGEMENT}
							</th>
						</tr>
						<tr>							
							<td style="text-align:center;width:34%" class="row2">
								<a href="moderation_panel{U_WARNING}" title="{L_USERS_WARNING}"><img src="../templates/{THEME}/images/notice.png" alt="{L_USERS_WARNING}" /><br />{L_USERS_WARNING}</a>
							</td>
							<td style="text-align:center;width:33%" class="row2">
								<a href="moderation_panel{U_PUNISH}" title="{L_USERS_PUNISHMENT}"><img src="../templates/{THEME}/images/stop.png" alt="{L_USERS_PUNISHMENT}" /><br />{L_USERS_PUNISHMENT}</a>
							</td>
							<td style="text-align:center;width:33%" class="row2">
								<a href="moderation_panel{U_BAN}" title="{L_USERS_BAN}"><img src="../templates/{THEME}/images/forbidden.png" alt="{L_USERS_BAN}" /><br />{L_USERS_BAN}</a>
							</td>
						</tr>
					</table>
					# END all_action #
					
					# START user_list #
					<script type="text/javascript">
					<!--
						function XMLHttpRequest_search()
						{
							var xhr_object = null;
							var data = null;
							var filename = "../includes/xmlhttprequest.php?{U_XMLHTTPREQUEST}=1";
							var login = document.getElementById("login").value;
							var data = null;
							
							if(window.XMLHttpRequest) // Firefox
							   xhr_object = new XMLHttpRequest();
							else if(window.ActiveXObject) // Internet Explorer
							   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
							else // XMLHttpRequest non supporté par le navigateur
								return;
							
							if( login != "" )
							{
								data = "login=" + login;
							   
								xhr_object.open("POST", filename, true);

								xhr_object.onreadystatechange = function() 
								{
									if( xhr_object.readyState == 4 ) 
									{
										document.getElementById("xmlhttprequest_result_search").innerHTML = xhr_object.responseText;
										hide_div("xmlhttprequest_result_search");
									}
								}

								xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

								xhr_object.send(data);
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
					
					<form action="moderation_panel{U_ACTION}" method="post" class="fieldset_content">
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
						# START list #
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
						# END list #
						
						# START empty #
						<tr>
							<td class="row1" style="text-align:center;" colspan="4">
								{user_list.empty.NO_USER}
							</td>
						</tr>		
						# END empty #
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
					
					<form action="moderation_panel{user_info.U_ACTION_INFO}" method="post">		
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
									{BBCODE}
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
					<form action="moderation_panel{user_ban.U_ACTION_INFO}" method="post">		
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
										# START select_ban #	
											{user_ban.select_ban.TIME}
										# END select_ban #						
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
					
					<br /><br /><br />
				</td>			
			</tr>
		</table>
		
		