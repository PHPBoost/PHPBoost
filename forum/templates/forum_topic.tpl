		<span id="go_top"></span>	
		
		# INCLUDE forum_top #
		
		<script type="text/javascript">
		<!--
		function check_form_msg(){
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_MESSAGE}");
				return false;
		    }
			return true;
		}
		function Confirm_msg() {
			return confirm('{L_DELETE_MESSAGE}');
		}
		function XMLHttpRequest_del(idmsg)
		{
			if( document.getElementById('dimg' + idmsg) )
				document.getElementById('dimg' + idmsg).src = '../templates/{THEME}/images/loading_mini.gif';
			
			var xhr_object = xmlhttprequest_init('../forum/xmlhttprequest.php?del=1&idm=' + idmsg);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' )
				{	
					if( document.getElementById('d' + idmsg) )
						document.getElementById('d' + idmsg).style.display = 'none';
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '-1' )
				{	
					if( document.getElementById('dimg' + idmsg) )
						document.getElementById('dimg' + idmsg).src = '../templates/{THEME}/images/{LANG}/delete.png';
				}
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		function XMLHttpRequest_change_statut()
		{
			var idtopic = {IDTOPIC};			
			if( document.getElementById('forum_change_img') )
				document.getElementById('forum_change_img').src = '../templates/{THEME}/images/loading.gif';
			
			var xhr_object = xmlhttprequest_init('../forum/xmlhttprequest.php?msg_d=' + idtopic);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{	
					if( document.getElementById('forum_change_img') )
						document.getElementById('forum_change_img').src = xhr_object.responseText == '1' ? '{MODULE_DATA_PATH}/images/msg_display2.png' : '{MODULE_DATA_PATH}/images/msg_display.png';
					if( document.getElementById('forum_change_msg') )
						document.getElementById('forum_change_msg').innerHTML = xhr_object.responseText == '1' ? "{L_EXPLAIN_DISPLAY_MSG_BIS}" : "{L_EXPLAIN_DISPLAY_MSG}";
				}
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		var is_favorite = {IS_FAVORITE};
		function XMLHttpRequest_favorite()
		{
			var idtopic = {IDTOPIC};
			if( document.getElementById('forum_favorite_img') )
				document.getElementById('forum_favorite_img').src = '../templates/{THEME}/images/loading.gif';
			
			xhr_object = xmlhttprequest_init('../forum/xmlhttprequest.php?' + (is_favorite ? 'ut' : 't') + '=' + idtopic);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{	
					if( document.getElementById('forum_favorite_img') )
						document.getElementById('forum_favorite_img').src = '{MODULE_DATA_PATH}/images/favorite.png';
					if( document.getElementById('forum_favorite_msg') )
						document.getElementById('forum_favorite_msg').innerHTML = xhr_object.responseText == '1' ? "{L_UNSUSCRIBE}" : "{L_SUSCRIBE}";
					is_favorite = xhr_object.responseText == '1' ? true : false;
				}
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		function del_msg(idmsg)
		{
			if( confirm('{L_DELETE_MESSAGE}') )
				XMLHttpRequest_del(idmsg);
		}
		function Confirm_topic() {
			return confirm("{L_ALERT_DELETE_TOPIC}");
		}		
		function Confirm_lock() {
			return confirm("{L_ALERT_LOCK_TOPIC}");
		}		
		function Confirm_unlock() {
			return confirm("{L_ALERT_UNLOCK_TOPIC}");
		}		
		function Confirm_move() {
			return confirm("{L_ALERT_MOVE_TOPIC}");
		}
		function Confirm_cut() {
			return confirm("{L_ALERT_CUT_TOPIC}");
		}
		-->
		</script>

		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<span style="float:left;">
					<a href="rss.php?cat={ID}" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a>
					&bull; {U_FORUM_CAT} {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span>
				</span>
				<span style="float:right;">{PAGINATION} {LOCK} {MOVE}</span>&nbsp;
			</div>
		</div>	

		# IF C_POLL_EXIST #
		<div class="module_position">
			<div class="module_contents">
				<form method="post" action="action{U_POLL_ACTION}">
					<table class="module_table" style="width:70%">
						<tr>
							<th>{L_POLL}: {QUESTION}</th>
						</tr>							
						# START poll_radio #
						<tr>
							<td class="row2" style="font-size:10px;">
								<label><input type="{poll_radio.TYPE}" name="radio" value="{poll_radio.NAME}" /> {poll_radio.ANSWERS}</label>
							</td>
						</tr>
						# END poll_radio #							
						# START poll_checkbox #
						<tr>
							<td class="row2">
								<label><input type="{poll_checkbox.TYPE}" name="{poll_checkbox.NAME}" value="{poll_checkbox.NAME}" /> {poll_checkbox.ANSWERS}</label>
							</td>
						</tr>
						# END poll_checkbox #					
						# START poll_result #
						<tr>
							<td class="row2" style="font-size:10px;">
								{poll_result.ANSWERS}
								<table width="95%">
									<tr>
										<td>
											<img src="../templates/{THEME}/images/poll_left.png" height="8px" width="" alt="{poll_result.PERCENT}%" title="{poll_result.PERCENT}%" /><img src="../templates/{THEME}/images/poll.png" height="8px" width="{poll_result.WIDTH}" alt="{poll_result.PERCENT}%" title="{poll_result.PERCENT}%" /><img src="../templates/{THEME}/images/poll_right.png" height="8px" width="" alt="{poll_result.PERCENT}%" title="{poll_result.PERCENT}%" /> {poll_result.PERCENT}% [{poll_result.NBRVOTE} {L_VOTE}]
										</td>
									</tr>
								</table>
							</td>
						</tr>
						# END poll_result #										
					</table>					
					<br />
					
					# IF C_POLL_QUESTION #
					<fieldset class="fieldset_submit">
						<legend>{L_VOTE}</legend>
						<input class="submit" name="valid_forum_poll" type="submit" value="{L_VOTE}" /><br />
						<a class="small_link" href="topic{U_POLL_RESULT}">{L_RESULT}</a>
					</fieldset>						
					# ENDIF #
				</form>
			</div>
		</div>
		# ENDIF #

		# START msg #		
		<div class="msg_position" id="d{msg.ID}">
			<div class="msg_container{msg.CLASS_COLOR}">
				<span id="m{msg.ID}">
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{msg.USER_ONLINE} {msg.USER_PSEUDO}
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="topic{msg.U_VARS_ANCRE}#m{msg.ID}" title=""><img src="../templates/{THEME}/images/ancre.png" alt="" /></a> {msg.DATE}</div>
					<div style="float:right;"><a href="topic{msg.U_VARS_QUOTE}#go_bottom" title="{L_QUOTE}"><img src="../templates/{THEME}/images/{LANG}/quote.png" alt="{L_QUOTE}" title="{L_QUOTE}" /></a>{msg.EDIT}{msg.DEL}{msg.CUT}&nbsp;&nbsp;<a href="#go_top"><img src="../templates/{THEME}/images/top.png" alt="" /></a> <a href="#go_bottom"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{msg.USER_RANK}</p>
						<p style="text-align:center;">{msg.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{msg.USER_AVATAR}</p>
						<p style="text-align:center;">{msg.USER_GROUP}</p>	
						{msg.USER_SEX}
						{msg.USER_DATE}<br />
						{msg.USER_MSG}<br />
						{msg.USER_LOCAL}		
					</div>
					<div class="msg_contents{msg.CLASS_COLOR}">
						<div class="msg_contents_overflow">
							{msg.CONTENTS}
							{msg.USER_EDIT}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign{msg.CLASS_COLOR}">				
				<div class="msg_sign_overflow">
					{msg.USER_SIGN}
				</div>			
				<hr />
				<span style="float:left;">
					{msg.USER_PM} {msg.USER_MAIL} {msg.USER_MSN} {msg.USER_YAHOO} {msg.USER_WEB}
				</span>
				<span style="float:right;font-size:10px;">
					{msg.WARNING} {msg.PUNISHMENT}
				</span>&nbsp;
			</div>	
		</div>	
		# END msg #
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">
				<span style="float:left;">
					<a href="rss.php?cat={ID}" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a>
					&bull; {U_FORUM_CAT} {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span>
				</span>
				<span style="float:right;">{PAGINATION} {LOCK} {MOVE}</span>&nbsp;
			</div>
		</div>
		
		# INCLUDE forum_bottom #
			
			
		# IF C_AUTH_POST #		
		<form action="post{U_FORUM_ACTION_POST}" method="post" onsubmit="return check_form_msg();" style="width:80%;margin:auto;margin-top:15px;" id="go_bottom">		
			<div style="font-size:11px;text-align:center;"><label for="contents">{L_RESPOND}</label></div>	
			# INCLUDE handle_bbcode #		
			<label><textarea class="post" rows="15" cols="66" id="contents" name="contents">{CONTENTS}</textarea></label>
			<fieldset class="fieldset_submit" style="padding-top:17px;">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
				&nbsp;&nbsp; 									
				<script type="text/javascript">
				<!--				
				document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
				-->
				</script>						
				<noscript><input value="{L_PREVIEW}" type="submit" name="prw" class="submit" /></noscript>
				&nbsp;&nbsp;
				<input type="reset" value="{L_RESET}" class="reset" />				
			</fieldset>			
		</form>
		<table class="forum_action">
			<tr>
				# IF C_DISPLAY_MSG #
				<td>
					<span id="forum_change_statut">
						<a href="action{U_ACTION_MSG_DISPLAY}#go_bottom">{ICON_DISPLAY_MSG}</a>	<a href="action{U_ACTION_MSG_DISPLAY}#go_bottom">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</a>
					</span>
					<script type="text/javascript">
					<!--				
					document.getElementById('forum_change_statut').style.display = 'none';
					document.write('<a href="javascript:XMLHttpRequest_change_statut()">{ICON_DISPLAY_MSG2}</a> <a href="javascript:XMLHttpRequest_change_statut()"><span id="forum_change_msg">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</span></a>');
					-->
					</script>
				</td>	
				# ENDIF #			
				<td>				
					<span id="forum_favorite">
						<a href="action{U_SUSCRIBE}#go_bottom"><img class="valign_middle" src="{MODULE_DATA_PATH}/images/favorite.png" alt="" /></a> <a href="action{U_SUSCRIBE}#go_bottom">{L_SUSCRIBE_DEFAULT}</a>
					</span>
					<script type="text/javascript">
					<!--				
					document.getElementById('forum_favorite').style.display = 'none';
					document.write('<a href="javascript:XMLHttpRequest_favorite()"><img id="forum_favorite_img" class="valign_middle" src="{MODULE_DATA_PATH}/images/favorite.png" alt="" /></a> <a href="javascript:XMLHttpRequest_favorite()"><span id="forum_favorite_msg">{L_SUSCRIBE_DEFAULT}</span></a>');
					-->
					</script>
				</td>
				<td>
					<a href="alert{U_ALERT}#go_bottom"><img class="valign_middle" src="../templates/{THEME}/images/important.png" alt="" /> <a href="alert{U_ALERT}#go_bottom">{L_ALERT}</a>
				</td>
			</tr>
		</table>
		# ENDIF #
		
		# IF C_ERROR_AUTH_WRITE #
		<div style="font-size:10px;text-align:center;padding-bottom:2px;">{L_RESPOND}</div>	
		<div class="forum_text_column" style="width:350px;margin:auto;height:auto;padding:2px;">
			{L_ERROR_AUTH_WRITE}			
		</div>
		# ENDIF #
		