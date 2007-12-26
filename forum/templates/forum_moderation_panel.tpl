		<div class="forum_title">{L_MODERATION_FORUM} {L_MODERATION_FORUM_MENU}</div>
		<div class="module_position">
			<div class="row2">
				<span style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a>
				</span>
				<span style="float:right;">
					{U_SEARCH}
					{U_TOPIC_TRACK}
					{U_LAST_MSG_READ}
					{U_MSG_NOT_READ}
				</span>&nbsp;
			</div>
		</div>
		
		<br />
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">&bull; <a href="../member/moderation_panel.php">{L_MODERATION_PANEL}</a> :: <a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; <a href="moderation_forum.php{SID}">{L_MODERATION_FORUM}</a> {U_MODERATION_FORUM_ACTION}</div>
			<div class="module_contents">
				<table class="module_table">
					<tr>							
						<td style="text-align:center;" class="row2">
							<a href="moderation_forum.php?action=warning" title="{L_USERS_WARNING}"><img src="../templates/{THEME}/images/notice.png" alt="{L_USERS_WARNING}" /><br />{L_USERS_WARNING}</a>
						</td>
						<td style="text-align:center;" class="row2">
							<a href="moderation_forum.php?action=punish" title="{L_USERS_PUNISHMENT}"><img src="../templates/{THEME}/images/stop.png" alt="{L_USERS_PUNISHMENT}" /><br />{L_USERS_PUNISHMENT}</a>
						</td>
						<td style="text-align:center;" class="row2">
							<a href="moderation_forum.php?action=alert" title="{L_ALERT_MANAGEMENT}"><img src="../templates/{THEME}/images/important.png" alt="{L_ALERT_MANAGEMENT}" /><br />{L_ALERT_MANAGEMENT}</a>
						</td>
					</tr>
				</table>				
				<br /><br />
				
				
				# START main #
				<script type="text/javascript">
				<!--

				function Confirm_history()
				{
					return confirm("{L_DEL_HISTORY}");
				}
				-->
				</script>
				<form action="moderation_forum{main.U_ACTION_HISTORY}" method="post" onsubmit="javascript:return Confirm_history();">					
					<table class="module_table">	
						<tr>
							<th colspan="3">
								{L_HISTORY}
							</th>
						</tr>
						<tr style="text-align:center;font-weight: bold;width: 150px">
							<td class="row3">
								{L_LOGIN}
							</td>
							<td class="row3">
								{L_ACTION}
							</td>
							<td class="row3"style="width: 150px">
								{L_DATE}
							</td>
						</tr>
						
						# START list # 
						<tr style="text-align:center;">
							<td class="row2" style="width: 150px">
								<a href="../member/member{main.list.U_MEMBER_ID}">{main.list.LOGIN}</a>
							</td>
							<td class="row2">
								{main.list.ACTION}
							</td>
							<td class="row2" style="width: 150px">
								{main.list.DATE}
							</td>
						</tr>
						# END list #
						
						# START no_action #
						<tr style="text-align:center;">
							<td class="row2" colspan="3">
								{main.no_action.L_NO_ACTION}
							</td>
						</tr>
						# END no_action #
						
						<tr>
							<td class="row3" colspan="3" style="text-align:center;">
								# START admin #
								<span style="float:left"><input type="submit" name="valid" value="{L_DELETE}" class="submit" /></span> 
								# END admin #
								
								<a href="moderation_forum{main.U_MORE_ACTION}">{L_MORE_ACTION}</a>
							</td>
						</tr>
					</table>
				</form>	
				# END main #

				

				# START alert #
				<script type="text/javascript">
				<!--
				function check_alert(status)
				{
					for (i = 0; i < document.alert.length; i++)
					{
						document.alert.elements[i].checked = status;
					}
				}
				function Confirm_msg() {
					return confirm("{alert.L_DELETE_MESSAGE}");
				}
				-->
				</script>
		
				<table class="module_table">
					<tr>			
						<th style="width:31px;"><input type="checkbox" onClick="if(this.checked) {check_convers(true)} else {check_convers(false)};" /></th>
						<th style="width:20%;">{L_TITLE}</th>
						<th style="width:20%;">{L_TOPIC}</th>
						<th style="width:100px;">{L_STATUS}</th>
						<th style="width:70px;">{L_LOGIN}</th>
						<th style="width:70px;">{L_TIME}</th>
					</tr>
				</table>
				
				<form name="alert" action="moderation_forum{U_ACTION_ALERT}" method="post" onsubmit="javascript:return Confirm_alert();">
					<table class="module_table">
						# START list #
						<tr>
							<td class="row1" style="text-align:center;width:25px;">
								<input type="checkbox" name="{alert.list.ID}" />
							</td>
							<td class="row1" style="text-align:center;width:20%;">
								{alert.list.TITLE}
							</td>
							<td class="row1" style="text-align:center;width:20%;">
								{alert.list.TOPIC}
							</td>
							<td class="row1" style="text-align:center;width:100px;{alert.list.BACKGROUND_COLOR}">
								{alert.list.STATUS}
							</td>
							<td class="row1" style="text-align:center;width:70px;">
								{alert.list.LOGIN}
							</td>
							<td class="row1" style="text-align:center;width:70px;">
								{alert.list.TIME}
							</td>
						</tr>
						# END list #
											
						# START empty #		
						<tr>
							<td class="row2" colspan="6" style="text-align:center;">
								{alert.empty.NO_ALERT}
							</td>
						</tr>		
						# END empty #					
						<tr>
							<td class="row2" colspan="6">
								&nbsp;<input type="submit" value="{L_DELETE}" class="submit" />
							</td>
						</tr>
					</table>
				</form>
				# END alert #

				

				# START alert_id #
				<table class="module_table">
					<tr>
						<td class="row1" style="width:180px;">
							{alert_id.L_TITLE}
						</td>
						<td class="row2">
							{alert_id.TITLE}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{alert_id.L_TOPIC}
						</td>
						<td class="row2">
							{alert_id.TOPIC}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{alert_id.L_CAT}
						</td>
						<td class="row2">
							{alert_id.CAT}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{alert_id.L_CONTENTS}
						</td>
						<td class="row2">
							{alert_id.CONTENTS}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{alert_id.L_STATUS}
						</td>
						<td class="row2">
							<span style="float:left;">{alert_id.STATUS}</span>
							<span style="float:right;">{alert_id.CHANGE_STATUS}</span>
						</td>
					</tr>
					<tr>
						<td class="row1">
							{alert_id.L_LOGIN}
						</td>
						<td class="row2">
							{alert_id.LOGIN}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{alert_id.L_TIME}
						</td>
						<td class="row2">
							{alert_id.TIME}
						</td>
					</tr>
				</table>					
				# END alert_id #

				

				# START alert_id_not_auth #
				<table class="module_table">
					<tr>
						<th colspan="2">
							{L_MODERATION_FORUM} :: {L_ALERT_MANAGEMENT} 
						</th>
					</tr>				
					<tr>
						<td style="text-align:center;" colspan="2">
							<br /><br />
							{alert_id_not_auth.NO_ALERT}
							<br /><br />
						</td>
					</tr>
				</table>
				# END alert_id_not_auth #


				
				# START user_list #
				<script type="text/javascript">
				<!--
					function XMLHttpRequest_search()
					{
						var xhr_object = null;
						var filename = "xmlhttprequest.php?{U_XMLHTTPREQUEST}=1";
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

				<form action="moderation_forum{U_ACTION}" method="post">
				<table class="module_table">
					<tr>
						<td style="vertical-align: top;text-align: center;" class="row2">
							{L_SEARCH_MEMBER}: <input type="text" size="20" maxlenght="25" id="login" value="" name="login" class="text" />			
							<script type="text/javascript">
							<!--								
								document.write('<input value="{L_SEARCH}" onclick="XMLHttpRequest_search(this.form);" type="button" class="submit">');
							-->
							</script>
							
							<noscript>
								<input type="submit" name="search_member" value="{L_SEARCH}" class="submit" />
							</noscript>
						</td>
						<td class="row2">
							<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
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
						<td class="row1" style="text-align:center;">
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
				<form action="moderation_forum{user_info.U_ACTION_INFO}" method="post">		
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
								<label for="new_info">{L_INFO_EXPLAIN}</label>
							</td>
							<td class="row2">
								<span id="action_info">{user_info.INFO}</span>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<label><select name="new_info" id="new_info" onchange="change_textarea_level(this.options[this.selectedIndex].value, {user_info.REGEX})">
									{user_info.SELECT}
								</select></label>	
								<input type="submit" name="valid_user" value="{L_CHANGE_INFO}" class="submit" />				
							</td>
						</tr>
					</table>
				</form>				
				# END user_info #


				
			</div>	
			<div class="module_bottom_r"></div>	
			<div class="module_bottom_l"></div>
			<div class="module_bottom text_strong">
				&bull; <a href="../member/moderation_panel.php">{L_MODERATION_PANEL}</a> :: <a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; <a href="moderation_forum.php{SID}">{L_MODERATION_FORUM}</a>{U_MODERATION_FORUM_ACTION}
			</div>	
		</div>
		
		<br />
		
		<div class="module_position">
			<div class="row2">
				<span style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a>
				</span>
				<span style="float:right;">
					{U_SEARCH}
					{U_TOPIC_TRACK}
					{U_LAST_MSG_READ}
					{U_MSG_NOT_READ}
				</span>&nbsp;
			</div>
			<div class="forum_online">
				<span style="float:left;">
					{TOTAL_ONLINE} {L_USER} {L_ONLINE} :: {ADMIN} {L_ADMIN}, {MODO} {L_MODO}, {MEMBER} {L_MEMBER} {L_AND} {GUEST} {L_GUEST}
					<br />
					{L_MEMBER} {L_ONLINE}: 
					# START online #		
						{online.ONLINE}
					# END online #
				</span>
				<span style="float:right;">
					<form action="topic{U_CHANGE_CAT}" method="post">
						<select name="change_cat" onchange="document.location = {U_ONCHANGE};">
							{SELECT_CAT}
						</select>
						<noscript>
							<input type="submit" name="valid_change_cat" value="Go" class="submit" />
						</noscript>
					</form>
				</span>
				<br /><br />
			</div>
		</div>
		