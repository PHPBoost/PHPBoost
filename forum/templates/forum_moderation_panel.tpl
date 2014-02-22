		# INCLUDE forum_top #
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">&bull; <a href="index.php{SID}">{FORUM_NAME}</a> &raquo; <a href="moderation_forum.php{SID}">{L_MODERATION_FORUM}</a> {U_MODERATION_FORUM_ACTION}</div>
			<div class="module_contents">
				<table class="module_table">
					<tr>							
						<td style="text-align:center;" class="row2">
							<a href="moderation_forum.php?action=warning" title="{L_USERS_WARNING}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/notice.png" alt="{L_USERS_WARNING}" /><br />{L_USERS_WARNING}</a>
						</td>
						<td style="text-align:center;" class="row2">
							<a href="moderation_forum.php?action=punish" title="{L_USERS_PUNISHMENT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/stop.png" alt="{L_USERS_PUNISHMENT}" /><br />{L_USERS_PUNISHMENT}</a>
						</td>
						<td style="text-align:center;" class="row2">
							<a href="moderation_forum.php?action=alert" title="{L_ALERT_MANAGEMENT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/important.png" alt="{L_ALERT_MANAGEMENT}" /><br />{L_ALERT_MANAGEMENT}</a>
						</td>
					</tr>
				</table>				
				<br /><br />
				
				
				# IF C_FORUM_MODO_MAIN #
				<script type="text/javascript">
				<!--

				function Confirm_history()
				{
					return confirm("{L_DEL_HISTORY}");
				}
				-->
				</script>
				<form action="moderation_forum{U_ACTION_HISTORY}" method="post" onsubmit="javascript:return Confirm_history();">					
					<table class="module_table">	
						<tr>
							<th colspan="4">
								{L_HISTORY}
							</th>
						</tr>
						<tr style="text-align:center;font-weight: bold;width: 150px">
							<td class="row3">
								{L_MODO}
							</td>
							<td class="row3">
								{L_ACTION}
							</td>
							<td class="row3">
								{L_USER_CONCERN}
							</td>
							<td class="row3" style="width: 150px">
								{L_DATE}
							</td>
						</tr>
						
						# START action_list # 
						<tr style="text-align:center;">
							<td class="row2" style="width: 150px">
								<a href="{action_list.U_USER_PROFILE}" class="{action_list.LEVEL_CLASS}" # IF action_list.C_GROUP_COLOR # style="color:{action_list.GROUP_COLOR}" # ENDIF #>{action_list.LOGIN}</a>
							</td>
							<td class="row2">
								{action_list.U_ACTION}
							</td>
							<td class="row2" style="width: 150px">
								{action_list.U_USER_CONCERN}
							</td>
							<td class="row2" style="width: 150px">
								{action_list.DATE}
							</td>
						</tr>
						# END action_list #
						
						# IF C_FORUM_NO_ACTION #
						<tr style="text-align:center;">
							<td class="row2" colspan="4">
								{L_NO_ACTION}
							</td>
						</tr>
						# ENDIF #
						
						<tr>
							<td class="row3" colspan="4" style="text-align:center;">
								# IF C_FORUM_ADMIN #
								<span style="float:left"><input type="submit" name="valid" value="{L_DELETE}" class="submit" /></span> 
								# ENDIF #
								# IF C_DISPLAY_LINK_MORE_ACTION #
								<a href="moderation_forum{U_MORE_ACTION}">{L_MORE_ACTION}</a>
								# ENDIF #
							</td>
						</tr>
					</table>
				</form>	
				# ENDIF #


				# IF C_FORUM_ALERTS #
				<script type="text/javascript">
				<!--
				function check_alert(status)
				{
					for (i = 0; i < document.alert.length; i++)
						document.alert.elements[i].checked = status;
				}
				function Confirm_msg() {
					return confirm("{L_DELETE_MESSAGE}");
				}
				-->
				</script>
		
				<form name="alert" action="moderation_forum{U_ACTION_ALERT}" method="post" onsubmit="javascript:return Confirm_alert();">
					<table class="module_table">
						<tr>			
							<th style="width:25px;"><input type="checkbox" onclick="if(this.checked) {check_alert(true)} else {check_alert(false)};" /></th>
							<th style="width:20%;">{L_TITLE}</th>
							<th style="width:20%;">{L_TOPIC}</th>
							<th style="width:100px;">{L_STATUS}</th>
							<th style="width:70px;">{L_LOGIN}</th>
							<th style="width:70px;">{L_TIME}</th>
						</tr>
					</table>
				
					<table class="module_table">
						# START alert_list #
						<tr>
							<td class="row1" style="text-align:center;width:25px;">
								<input type="checkbox" name="{alert_list.ID}" />
							</td>
							<td class="row1" style="text-align:center;width:20%;">
								{alert_list.TITLE} {alert_list.EDIT}
							</td>
							<td class="row1" style="text-align:center;width:20%;">
								{alert_list.TOPIC}
							</td>
							<td class="row1" style="text-align:center;width:100px;{alert_list.BACKGROUND_COLOR}">
								{alert_list.STATUS}
							</td>
							<td class="row1" style="text-align:center;width:70px;">
								{alert_list.LOGIN}
							</td>
							<td class="row1" style="text-align:center;width:70px;">
								{alert_list.TIME}
							</td>
						</tr>
						# END alert_list #
						
						# IF C_FORUM_NO_ALERT #		
						<tr>
							<td class="row2" colspan="6" style="text-align:center;">
								{L_NO_ALERT}
							</td>
						</tr>		
						# ENDIF #				
						<tr>
							<td class="row2" colspan="6">
								&nbsp;<input type="submit" value="{L_DELETE}" class="submit" />
							</td>
						</tr>
					</table>
				</form>
				# ENDIF #
				

				# IF C_FORUM_ALERT_LIST #
				<table class="module_table">
					<tr>
						<th colspan="2" style="text-align:center;">
							{TOPIC}
						</th>
					</tr>
					<tr>
						<td class="row1" style="width:25%;">
							{L_TITLE}
						</td>
						<td class="row2">
							{TITLE}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_TOPIC}
						</td>
						<td class="row2">
							{TOPIC}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_CAT}
						</td>
						<td class="row2">
							{CAT}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_CONTENTS}
						</td>
						<td class="row2">
							{CONTENTS}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_STATUS}
						</td>
						<td class="row2">
							{STATUS}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_LOGIN}
						</td>
						<td class="row2">
							{LOGIN}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_TIME}
						</td>
						<td class="row2">
							{TIME}
						</td>
					</tr>
				</table>	

				<form action="{U_CHANGE_STATUS}" method="post">
					<fieldset class="fieldset_submit" style="padding-top:25px;">
						<legend></legend>
						<input type="submit" name="valid" value="{L_CHANGE_STATUS}" class="submit" />
					</fieldset>		
				</form>
				# ENDIF #

				# IF C_FORUM_ALERT_NOT_AUTH #
				<table class="module_table">
					<tr>
						<th colspan="2">
							{L_MODERATION_FORUM} :: {L_ALERT_MANAGEMENT} 
						</th>
					</tr>				
					<tr>
						<td style="text-align:center;" colspan="2">
							<br /><br />
							{L_NO_ALERT}
							<br /><br />
						</td>
					</tr>
				</table>
				# ENDIF #


				
				# IF C_FORUM_USER_LIST #
				<script type="text/javascript">
				<!--
					function XMLHttpRequest_search()
					{
						var login = document.getElementById('login').value;
						if( login != '' )
						{
							if( document.getElementById('search_img') )
								document.getElementById('search_img').innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
							data = 'login=' + login;
							var xhr_object = xmlhttprequest_init('xmlhttprequest.php?token={TOKEN}&{U_XMLHTTPREQUEST}=1');
							xhr_object.onreadystatechange = function() 
							{
								if( xhr_object.readyState == 4 && xhr_object.status == 200 ) 
								{
									document.getElementById('xmlhttprequest_result_search').innerHTML = xhr_object.responseText;
									hide_div('xmlhttprequest_result_search');
									if( document.getElementById('search_img') )
										document.getElementById('search_img').innerHTML = '';
								}
								else if( xhr_object.readyState == 4 ) 
								{
									if( document.getElementById('search_img') )
										document.getElementById('search_img').innerHTML = '';
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

				<form action="moderation_forum{U_ACTION}" method="post">
				<table class="module_table">
					<tr>
						<td class="row2">
							<span style="float:left;">
								{L_SEARCH_USER}: <input type="text" size="20" maxlength="25" id="login" name="login" class="text" />
								<span id="search_img"></span>
							</span>
							<span style="float:left;margin-left:5px;">
								<input type="submit" id="search_member" name="search_member" value="{L_SEARCH}" class="submit" />
								<script type="text/javascript">
								<!--								
									document.getElementById('search_member').style.display = 'none';
									document.write('<input value="{L_SEARCH}" onclick="XMLHttpRequest_search();" type="button" class="submit">');
								-->
								</script>									
								<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
							</span>
						</td>
					</tr>
				</table>
				<table class="module_table">
					<tr>			
						<th style="width:25%;">{L_LOGIN}</th>
						<th style="width:25%;">{L_INFO}</th>
						<th style="width:25%;">{L_ACTION_USER}</th>
						<th style="width:25%;">{L_PM}</th>
					</tr>
				</table>
				<table class="module_table">	
					# START user_list #
					<tr>
						<td class="row1" style="text-align:center;width:25%;">
							<a href="{user_list.U_PROFILE}" class="{user_list.LEVEL_CLASS}" # IF user_list.C_GROUP_COLOR # style="color:{user_list.GROUP_COLOR}" # ENDIF #>{user_list.LOGIN}</a>
						</td>
						<td class="row1" style="text-align:center;width:25%;">
							{user_list.INFO}
						</td>
						<td class="row1" style="text-align:center;width:25%;">
							{user_list.U_ACTION_USER}
						</td>
						<td class="row1" style="text-align:center;width:25%;">
							<a href="{user_list.U_PM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/pm.png" alt="" /></a>
						</td>
					</tr>
					# END user_list #
					
					# IF C_FORUM_NO_USER #
					<tr>
						<td class="row1" style="text-align:center;">
							{L_NO_USER}
						</td>
					</tr>		
					# ENDIF #
				</table>
				</form>
				# ENDIF #


				# IF C_FORUM_USER_INFO #
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
				<form action="moderation_forum{U_ACTION_INFO}" method="post">		
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
								<a href="{U_PM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/pm.png" alt="PM" /></a>
							</td>
						</tr>
						<tr>
							<td class="row2" colspan="2">
								<div class="question">{L_ALTERNATIVE_PM}</div>
								{KERNEL_EDITOR}
								<label><textarea name="action_contents" id="action_contents" class="post" rows="12" cols="15">{ALTERNATIVE_PM}</textarea></label>
							</td>
						</tr>
						<tr>
							<td class="row1">
								<label for="new_info">{L_INFO_EXPLAIN}</label>
							</td>
							<td class="row2">
								<span id="action_info">{INFO}</span>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<label><select name="new_info" id="new_info" onchange="change_textarea_level(this.options[this.selectedIndex].value, {REGEX})">
									{SELECT}
								</select></label>	
								<input type="submit" name="valid_user" value="{L_CHANGE_INFO}" class="submit" />				
							</td>
						</tr>
					</table>
				</form>				
				# ENDIF #


				<br /><br />
			</div>	
			<div class="module_bottom_r"></div>	
			<div class="module_bottom_l"></div>
			<div class="module_bottom text_strong">
				&bull; <a href="index.php{SID}">{FORUM_NAME}</a> &raquo; <a href="moderation_forum.php{SID}">{L_MODERATION_FORUM}</a> {U_MODERATION_FORUM_ACTION}
			</div>	
		</div>
		
		# INCLUDE forum_bottom #
		