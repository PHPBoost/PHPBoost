		<div class="module_position">
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{L_MODERATION_PANEL}</div>
			<div class="module_contents" style="padding-bottom:75px;">	
			# IF C_MODO_PANEL_USER #
				<table class="module_table">
					<tr>
						<th colspan="3">
							{L_INFO_MANAGEMENT}
						</th>
					</tr>
					<tr>							
						<td style="text-align:center;width:34%" class="row2">
							<a href="moderation_panel{U_WARNING}" title="{L_USERS_WARNING}"><img src="../templates/{THEME}/images/notice.png" alt="{L_USERS_WARNING}" /></a>
							<br />
							<a href="moderation_panel{U_WARNING}" title="{L_USERS_WARNING}">{L_USERS_WARNING}</a>
						</td>
						<td style="text-align:center;width:33%" class="row2">
							<a href="moderation_panel{U_PUNISH}" title="{L_USERS_PUNISHMENT}"><img src="../templates/{THEME}/images/stop.png" alt="{L_USERS_PUNISHMENT}" /></a>
							<br />
							<a href="moderation_panel{U_PUNISH}" title="{L_USERS_PUNISHMENT}">{L_USERS_PUNISHMENT}</a>
						</td>
						<td style="text-align:center;width:33%" class="row2">
							<a href="moderation_panel{U_BAN}" title="{L_USERS_BAN}"><img src="../templates/{THEME}/images/forbidden.png" alt="{L_USERS_BAN}" /></a>
							<br />
							<a href="moderation_panel{U_BAN}" title="{L_USERS_BAN}">{L_USERS_BAN}</a>
						</td>
					</tr>
				</table>
				
				# IF C_MODO_PANEL_USER_LIST #
				<script type="text/javascript">
				<!--
					function XMLHttpRequest_search()
					{
						var login = document.getElementById("login").value;
						if( login != "" )
						{
							data = "login=" + login;
							var xhr_object = xmlhttprequest_init('../kernel/framework/ajax/member_xmlhttprequest.php?token={TOKEN}&{U_XMLHTTPREQUEST}=1');
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
						<legend>{L_SEARCH_USER}</legend>
						<dl>
							<dt><label for="login">{L_SEARCH_USER}</label><br /><span>{L_JOKER}</span></dt>
							<dd>
								<input type="text" size="20" maxlength="25" id="login" value="" name="login" class="text" />						
								<input type="submit" name="search_member" id="search_member" value="{L_SEARCH}" class="submit" />
								<script type="text/javascript">
								<!--	
									document.getElementById("search_member").style.display = "none";
									document.write('<input value="{L_SEARCH}" onclick="XMLHttpRequest_search(this.form);" type="button" class="submit">');
								-->
								</script>
								<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
							</dd>
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
					# START member_list #
					<tr>
						<td class="row1" style="text-align:center;width:25%;">
							<a href="../member/{member_list.U_PROFILE}">{member_list.LOGIN}</a>
						</td>
						<td class="row1" style="text-align:center;width:25%;">
							{member_list.INFO}
						</td>
						<td class="row1" style="text-align:center;width:25%;">
							{member_list.U_ACTION_USER}
						</td>
						<td class="row1" style="text-align:center;width:25%;">
							<a href="../member/pm{member_list.U_PM}"><img src="../templates/{THEME}/images/{LANG}/pm.png" alt="" /></a>
						</td>
					</tr>
					# END member_list #
					
					# IF C_EMPTY_LIST #
					<tr>
						<td class="row1" style="text-align:center;" colspan="4">
							{L_NO_USER}
						</td>
					</tr>		
					# ENDIF #
				</table>
				# ENDIF #

				

				# IF C_MODO_PANEL_USER_INFO #
				<script type="text/javascript">
				<!--
				function change_textarea_level(replace_value, regex)
				{
					# IF C_BBCODE_TINYMCE_MODE #  tinyMCE.activeEditor.save(); # ENDIF #
					
					var contents = document.getElementById('action_contents').value;
					{REPLACE_VALUE}		
					document.getElementById('action_contents').value = contents;	
					
					# IF C_BBCODE_TINYMCE_MODE #  tinyMCE.getInstanceById('action_contents').getBody().innerHTML = contents;  # ENDIF #
				}
				-->
				</script>
				
				<br /><br />
				<form action="moderation_panel{U_ACTION_INFO}" method="post">		
					<table class="module_table">
						<tr>
							<th colspan="2">{L_ACTION_INFO}</th>
						</tr>
						<tr>
							<td class="row1" style="width:30%;">
								{L_LOGIN}
							</td>
							<td class="row2">
								{LOGIN}
							</td>
						</tr>
						<tr>
							<td class="row1">
								{L_PM}
							</td>
							<td class="row2">
								<a href="../member/pm{U_PM}"><img src="../templates/{THEME}/images/{LANG}/pm.png" alt="PM" /></a>
							</td>
						</tr>
						<tr>
							<td class="row1" style="vertical-align:top">
								<label for="action_contents">{L_ALTERNATIVE_PM}</label>
							</td>
							<td class="row2">
								{KERNEL_EDITOR}
								<label><textarea name="action_contents" id="action_contents" class="post" rows="12">{ALTERNATIVE_PM}</textarea></label>
							</td>
						</tr>
						<tr>
							<td class="row1">
								{L_INFO_EXPLAIN}
							</td>
							<td class="row2">
								<span id="action_info">{INFO}</span>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<select name="new_info" onchange="change_textarea_level(this.options[this.selectedIndex].value, {REGEX})">
									{SELECT}
								</select>
								<input type="submit" name="valid_user" value="{L_CHANGE_INFO}" class="submit" />					
							</td>
						</tr>
					</table>
				</form>					
				# ENDIF #
				
				
				
				# IF C_MODO_PANEL_USER_BAN #					
				<br /><br />
				<form action="moderation_panel{U_ACTION_INFO}" method="post">		
					<table class="module_table">
						<tr>
							<th colspan="2">{L_ACTION_INFO}</th>
						</tr>
						<tr>
							<td class="row1" style="width:30%;">
								{L_LOGIN}
							</td>
							<td class="row2">
								{LOGIN}
							</td>
						</tr>
						<tr>
							<td class="row1">
								{L_PM}
							</td>
							<td class="row2">
								<a href="../member/pm{U_PM}"><img src="../templates/{THEME}/images/{LANG}/pm.png" alt="" /></a>
							</td>
						</tr>
						<tr>
							<td class="row1">
								{L_DELAY_BAN}
							</td>
							<td class="row2">
								<select name="user_ban">					
									# START select_ban #	
										{select_ban.TIME}
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
				# ENDIF #
			# ENDIF #
				</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
			</div>
		</div>
