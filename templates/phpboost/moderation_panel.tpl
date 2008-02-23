		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{L_MODERATION_PANEL}</div>
			<div class="module_contents">
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
								<ul>
									<li class="vertical" onmouseover="show_menu(1, 0);" onmouseout="hide_menu(0);">
										<h5><img src="../templates/{THEME}/images/admin/members_mini.png" class="valign_middle" alt="" /> {L_MEMBERS}</h5>
										<ul id="smenu1">
											<li><a href="moderation_panel{U_WARNING}" style="background-image:url(../templates/{THEME}/images/admin/important.png);">{L_WARNING}</a></li>
											<li><a href="moderation_panel{U_PUNISH}" style="background-image:url(../templates/{THEME}/images/admin/stop.png);background-repeat:no-repeat;background-position:5px;">{L_PUNISHMENT}</a></li>
											<li><a href="moderation_panel{U_BAN}" style="background-image:url(../templates/{THEME}/images/admin/forbidden.png);background-repeat:no-repeat;background-position:5px;">{L_BAN}</a></li>
										</ul>
									</li>
									<li class="vertical" onmouseover="show_menu(2, 0);" onmouseout="hide_menu(0);">
										<h5><img src="../templates/{THEME}/images/admin/modules_mini.png" class="valign_middle" alt="" /> {L_MODULES}</h5>
										<ul id="smenu2">
											# START list_modules #
											<li><a href="../{list_modules.MOD_NAME}/{list_modules.U_LINK}" {list_modules.DM_A_CLASS}>{list_modules.NAME}</a></li>
											# END list_modules #
										</ul>
									</li>
								</ul>
							</div>
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
									var login = document.getElementById("login").value;
									if( login != "" )
									{
										data = "login=" + login;
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
										alert("{L_REQUIRE_LOGIN}");
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
						</td>
					</tr>
				</table>
				<br />				
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left" class="text_strong">
					<a href="gallery.php{SID}">{L_GALLERY}</a> &raquo; {U_GALLERY_CAT_LINKS} {ADD_PICS}
				</div>
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
		</div>
		
		