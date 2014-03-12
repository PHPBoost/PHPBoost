		# INCLUDE forum_top #
		
		<div class="module_position forum_position_cat">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top forum_top_cat">&bull; <a href="index.php{SID}">{FORUM_NAME}</a> &raquo; <a href="moderation_forum.php{SID}">{L_MODERATION_FORUM}</a> {U_MODERATION_FORUM_ACTION}</div>
			<div class="module_contents">
				<table style="margin:30px auto 0px auto;">
					<thead>
						<tr>
							<td style="text-align:center;width:20%">
								<a href="moderation_forum.php?action=warning" title="{L_USERS_WARNING}"><i class="fa fa-warning fa-2x"></i></a>
								<br />
								<a href="moderation_forum.php?action=warning" title="{L_USERS_WARNING}">{L_USERS_WARNING}</a>
							</td>
							<td style="text-align:center;width:20%">
								<a href="moderation_forum.php?action=punish" title="{L_USERS_PUNISHMENT}"><i class="fa fa-error fa-2x"></i></a>
								<br />
								<a href="moderation_forum.php?action=punish" title="{L_USERS_PUNISHMENT}">{L_USERS_PUNISHMENT}</a>
							</td>
							<td style="text-align:center;width:20%">
								<a href="moderation_forum.php?action=alert" title="{L_ALERT_MANAGEMENT}"><i class="fa fa-notice fa-2x"></i></a>
								<br />
								<a href="moderation_forum.php?action=alert" title="{L_ALERT_MANAGEMENT}">{L_ALERT_MANAGEMENT}</a>
							</td>
						</tr>
					</thead>
				</table>
				<br /><br />
				
				
				# IF C_FORUM_MODO_MAIN #
				<script>
				<!--

				function Confirm_history()
				{
					return confirm("{L_DEL_HISTORY}");
				}
				-->
				</script>
				<form action="moderation_forum{U_ACTION_HISTORY}" method="post" onsubmit="javascript:return Confirm_history();">
					<table>
						<caption>
							{L_HISTORY}
						</caption>
						<thead>
							<tr style="text-align:center;font-weight: bold;width: 150px">
								<th>
									{L_MODO}
								</th>
								<th>
									{L_ACTION}
								</th>
								<th>
									{L_USER_CONCERN}
								</th>
								<th style="width: 150px">
									{L_DATE}
								</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th colspan="4" style="text-align:center;">
									# IF C_FORUM_ADMIN #
									<span style="float:left"><button type="submit" name="valid" value="true">{L_DELETE}</button></span> 
									# ENDIF #
									# IF C_DISPLAY_LINK_MORE_ACTION #
									<a href="moderation_forum{U_MORE_ACTION}">{L_MORE_ACTION}</a>
									# ENDIF #
								</th>
							</tr>
						</tfoot>
						<tbody>
						# START action_list # 
						<tr style="text-align:center;">
							<td style="width: 150px">
								<a href="{action_list.U_USER_PROFILE}" class="{action_list.LEVEL_CLASS}" # IF action_list.C_GROUP_COLOR # style="color:{action_list.GROUP_COLOR}" # ENDIF #>{action_list.LOGIN}</a>
							</td>
							<td>
								{action_list.U_ACTION}
							</td>
							<td style="width: 150px">
								{action_list.U_USER_CONCERN}
							</td>
							<td style="width: 150px">
								{action_list.DATE}
							</td>
						</tr>
						# END action_list #
						# IF C_FORUM_NO_ACTION #
						<tr style="text-align:center;">
							<td colspan="4">
								{L_NO_ACTION}
							</td>
						</tr>
						# ENDIF #
						</tbody>
					</table>
				</form>	
				# ENDIF #


				# IF C_FORUM_ALERTS #
				<script>
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
					<table>
						<thead>
							<tr>			
								<th style="width:25px;"><input type="checkbox" onclick="if(this.checked) {check_alert(true)} else {check_alert(false)};"></th>
								<th style="width:20%;">{L_TITLE}</th>
								<th style="width:20%;">{L_TOPIC}</th>
								<th style="width:100px;">{L_STATUS}</th>
								<th style="width:70px;">{L_LOGIN}</th>
								<th style="width:70px;">{L_TIME}</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th colspan="6">
									<button type="submit" name="" value="true">{L_DELETE}</button>
								</th>
							</tr>
						</tfoot>
						<tbody>
							# START alert_list #
							<tr>
								<td style="text-align:center;width:25px;">
									<input type="checkbox" name="{alert_list.ID}">
								</td>
								<td style="text-align:center;width:20%;">
									{alert_list.TITLE} {alert_list.EDIT}
								</td>
								<td style="text-align:center;width:20%;">
									{alert_list.TOPIC}
								</td>
								<td style="text-align:center;width:100px;{alert_list.BACKGROUND_COLOR}">
									{alert_list.STATUS}
								</td>
								<td style="text-align:center;width:70px;">
									{alert_list.LOGIN}
								</td>
								<td style="text-align:center;width:70px;">
									{alert_list.TIME}
								</td>
							</tr>
							# END alert_list #
							
							# IF C_FORUM_NO_ALERT #
							<tr>
								<td colspan="6" style="text-align:center;">
									{L_NO_ALERT}
								</td>
							</tr>
							# ENDIF #
						</tbody>
					</table>
				</form>
				# ENDIF #
				

				# IF C_FORUM_ALERT_LIST #
				<table>
					<thead>
						<tr>
							<th colspan="2" style="text-align:center;">
								{TOPIC}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="width:25%;">{L_TITLE}</td>
							<td>{TITLE}</td>
						</tr>
						<tr>
							<td>{L_TOPIC}</td>
							<td>{TOPIC}</td>
						</tr>
						<tr>
							<td>{L_CAT}</td>
							<td>{CAT}</td>
						</tr>
						<tr>
							<td>{L_CONTENTS}</td>
							<td>{CONTENTS}</td>
						</tr>
						<tr>
							<td>{L_STATUS}</td>
							<td>{STATUS}</td>
						</tr>
						<tr>
							<td>{L_LOGIN}</td>
							<td>{LOGIN}</td>
						</tr>
						<tr>
							<td>{L_TIME}</td>
							<td>{TIME}</td>
						</tr>
					</tbody>
				</table>	

				<form action="{U_CHANGE_STATUS}" method="post">
					<fieldset class="fieldset-submit" style="padding-top:25px;">
						<legend></legend>
						<button type="submit" name="valid" value="true">{L_CHANGE_STATUS}</button>
					</fieldset>
				</form>
				# ENDIF #

				# IF C_FORUM_ALERT_NOT_AUTH #
				<table>
					<thead>
						<tr>
							<th colspan="2">
								{L_MODERATION_FORUM} :: {L_ALERT_MANAGEMENT} 
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:center;" colspan="2">
								<br /><br />
								{L_NO_ALERT}
								<br /><br />
							</td>
						</tr>
					</tbody>
				</table>
				# ENDIF #


				
				# IF C_FORUM_USER_LIST #
				<script>
				<!--
					function XMLHttpRequest_search()
					{
						var login = document.getElementById('login').value;
						if( login != '' )
						{
							if( document.getElementById('search_img') )
								document.getElementById('search_img').innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
							data = 'login=' + login;
							var xhr_object = xmlhttprequest_init('xmlhttprequest.php?token={TOKEN}&{U_XMLHTTPREQUEST}=1');
							xhr_object.onreadystatechange = function() 
							{
								if( xhr_object.readyState == 4 && xhr_object.status == 200 ) 
								{
									document.getElementById('xmlhttprequest-result-search').innerHTML = xhr_object.responseText;
									hide_div('xmlhttprequest-result-search');
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
				<table>
					<tr>
						<td>
							<span style="float:left;">
								{L_SEARCH_USER}: <input type="text" size="20" maxlength="25" id="login" name="login">
								<span id="search_img"></span>
							</span>
							<span style="float:left;margin-left:5px;">
								<button type="submit" id="search_member" name="search_member">{L_SEARCH}</button>
								<script>
								<!--
									document.getElementById('search_member').style.display = 'none';
									document.write('<button onclick="XMLHttpRequest_search();" type="button">{L_SEARCH}</button>');
								-->
								</script>
								<div id="xmlhttprequest-result-search" style="display:none;" class="xmlhttprequest-result-search"></div>
							</span>
						</td>
					</tr>
				</table>
				
				<table>
					<thead>
						<tr>
							<th style="width:25%;">{L_LOGIN}</th>
							<th style="width:25%;">{L_INFO}</th>
							<th style="width:25%;">{L_ACTION_USER}</th>
							<th style="width:25%;">{L_PM}</th>
						</tr>
					</thead>
					<tbody>
						# START user_list #
						<tr>
							<td style="text-align:center;width:25%;">
								<a href="{user_list.U_PROFILE}" class="{user_list.LEVEL_CLASS}" # IF user_list.C_GROUP_COLOR # style="color:{user_list.GROUP_COLOR}" # ENDIF #>{user_list.LOGIN}</a>
							</td>
							<td style="text-align:center;width:25%;">
								{user_list.INFO}
							</td>
							<td style="text-align:center;width:25%;">
								{user_list.U_ACTION_USER}
							</td>
							<td style="text-align:center;width:25%;">
								<a href="{user_list.U_PM}" class="basic-button smaller">MP</a>
							</td>
						</tr>
						# END user_list #
						
						# IF C_FORUM_NO_USER #
						<tr>
							<td style="text-align:center;" colspan="4">
								{L_NO_USER}
							</td>
						</tr>
						# ENDIF #
					</tbody>
				</table>
				</form>
				# ENDIF #


				# IF C_FORUM_USER_INFO #
				<script>
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
					<table>
						<thead>
							<tr>
								<th style="width:30%;">
									{L_LOGIN}
								</th>
								<th>
									{LOGIN}
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									{L_PM}
								</td>
								<td>
									<a href="{U_PM}" class="basic-button smaller">MP</a>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div class="question">{L_ALTERNATIVE_PM}</div>
									{KERNEL_EDITOR}
									<textarea name="action_contents" id="action_contents" rows="12" cols="15">{ALTERNATIVE_PM}</textarea>
								</td>
							</tr>
							<tr>
								<td>
									<label for="new_info">{L_INFO_EXPLAIN}</label>
								</td>
								<td>
									<span id="action_info">{INFO}</span>
									<label><select name="new_info" id="new_info" onchange="change_textarea_level(this.options[this.selectedIndex].value, {REGEX})">
										{SELECT}
									</select></label>
									<button type="submit" name="valid_user" value="true">{L_CHANGE_INFO}</button>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
				# ENDIF #


				<br /><br />
			</div>
			<div class="module_bottom_r"></div>	
			<div class="module_bottom_l"></div>
			<div class="module_bottom forum_top_cat">
				&bull; <a href="index.php{SID}">{FORUM_NAME}</a> &raquo; <a href="moderation_forum.php{SID}">{L_MODERATION_FORUM}</a> {U_MODERATION_FORUM_ACTION}
			</div>
		</div>
		
		# INCLUDE forum_bottom #
		