		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_USERS_MANAGEMENT}</li>
				<li>
					<a href="admin_members.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php" class="quick_link">{L_USERS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/admin/member/index.php?url=/members/add/"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/member/index.php?url=/members/add/" class="quick_link">{L_USERS_ADD}</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/admin/member/index.php?url=/members/config/"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/member/index.php?url=/members/config/" class="quick_link">{L_USERS_CONFIG}</a>
				</li>
				<li>
					<a href="admin_members_punishment.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_punishment.php" class="quick_link">{L_USERS_PUNISHMENT}</a>
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
						<a href="admin_members_punishment.php?action=warning" title="{L_USERS_WARNING}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/notice.png" alt="{L_USERS_WARNING}" /><br />{L_USERS_WARNING}</a>
					</td>
					<td style="text-align:center;width:33%" class="row2">
						<a href="admin_members_punishment.php?action=punish" title="{L_USERS_PUNISHMENT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/stop.png" alt="{L_USERS_PUNISHMENT}" /><br />{L_USERS_PUNISHMENT}</a>
					</td>
					<td style="text-align:center;width:33%" class="row2">
						<a href="admin_members_punishment.php?action=ban" title="{L_USERS_WARNING}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/forbidden.png" alt="{L_USERS_BAN}" /><br />{L_USERS_BAN}</a>
					</td>
				</tr>
			</table>
			
			# IF C_USER_LIST #
			<script type="text/javascript">
			<!--
				function XMLHttpRequest_search()
				{
					var login = document.getElementById("login").value;
					if( login != "" )
					{
						data = "login=" + login + "&admin=1";
						var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/kernel/framework/ajax/member_xmlhttprequest.php?token={TOKEN}&{U_XMLHTTPREQUEST}=1');
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
					<legend>{L_SEARCH_USER}</legend>
					<dl>
						<dt><label for="login">{L_SEARCH_USER}</label><br /><span>{L_JOKER}</span></dt>
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
				<input type="hidden" name="token" value="{TOKEN}" />
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
						<a href="{PATH_TO_ROOT}/member/{list.U_PROFILE}">{list.LOGIN}</a>
					</td>
					<td class="row1" style="text-align:center;width:25%;">
						{list.INFO}
					</td>
					<td class="row1" style="text-align:center;width:25%;">
						{list.U_ACTION_USER}
					</td>
					<td class="row1" style="text-align:center;width:25%;">
						<a href="{PATH_TO_ROOT}/member/pm{list.U_PM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/pm.png" alt="" /></a>
					</td>
				</tr>
				# END list #
				
				# IF C_NO_USER #
				<tr>
					<td class="row1" style="text-align:center;" colspan="4">
						{L_NO_USER}
					</td>
				</tr>		
				# ENDIF #
			</table>
			# ENDIF #


			# IF C_USER_INFO #
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
			
			<form action="admin_members_punishment{U_ACTION_INFO}" method="post">		
			<table class="module_table">
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
						<a href="{PATH_TO_ROOT}/member/pm{U_PM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/pm.png" alt="PM" /></a>
					</td>
				</tr>
				<tr>
					<td class="row2" colspan="2">
						<div class="question">{L_ALTERNATIVE_PM}</div>
						<br />
						{KERNEL_EDITOR}
						<label><textarea name="action_contents" id="action_contents" class="post" rows="12" cols="15">{ALTERNATIVE_PM}</textarea></label>
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
			<input type="hidden" name="token" value="{TOKEN}" />
			</form>
			# ENDIF #
			
			
			# IF C_USER_BAN #
			<form action="admin_members_punishment{U_ACTION_INFO}" method="post">		
			<table class="module_table">
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
						<a href="{PATH_TO_ROOT}/member/pm{U_PM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/pm.png" alt="" /></a>
					</td>
				</tr>
				<tr>
					<td class="row1">
						{L_DELAY_BAN}
					</td>
					<td class="row2">
						<select name="user_ban">					
							{BAN_OPTIONS}				
						</select>
					</td>
				</tr>
				<tr>
					<td class="row2" colspan="2" style="text-align:center;">
						<input type="submit" name="valid_user" value="{L_BAN}" class="submit" />					
					</td>
				</tr>
			</table>
			<input type="hidden" name="token" value="{TOKEN}" />
			</form>
			# ENDIF #
		</div>
		