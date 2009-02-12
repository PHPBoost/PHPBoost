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
				document.getElementById('forum_change_img').src = '../templates/{THEME}/images/loading_mini.gif';
			
			var xhr_object = xmlhttprequest_init('../forum/xmlhttprequest.php?msg_d=' + idtopic);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{	
					document.getElementById('display_msg_title').innerHTML = xhr_object.responseText == '1' ? "{L_DISPLAY_MSG}" + ' ' : '';
					document.getElementById('display_msg_title2').innerHTML = xhr_object.responseText == '1' ? "{L_DISPLAY_MSG}" + ' ' : '';
					if( document.getElementById('forum_change_img') )
						document.getElementById('forum_change_img').src = xhr_object.responseText == '1' ? '../templates/{THEME}/images/not_processed_mini.png' : '../templates/{THEME}/images/processed_mini.png';
					if( document.getElementById('forum_change_msg') )
						document.getElementById('forum_change_msg').innerHTML = xhr_object.responseText == '1' ? "{L_EXPLAIN_DISPLAY_MSG_BIS}" : "{L_EXPLAIN_DISPLAY_MSG}";
				}
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		var is_track = {IS_TRACK};
		function XMLHttpRequest_track()
		{
			var idtopic = {IDTOPIC};
			if( document.getElementById('forum_track_img') )
				document.getElementById('forum_track_img').src = '../templates/{THEME}/images/loading_mini.gif';
			
			xhr_object = xmlhttprequest_init('../forum/xmlhttprequest.php?' + (is_track ? 'ut' : 't') + '=' + idtopic);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{	
					if( document.getElementById('forum_track_img') )
						document.getElementById('forum_track_img').src = xhr_object.responseText == '1' ? '{MODULE_DATA_PATH}/images/untrack_mini.png' : '{MODULE_DATA_PATH}/images/track_mini.png';
					if( document.getElementById('forum_track_msg') )
						document.getElementById('forum_track_msg').innerHTML = xhr_object.responseText == '1' ? "{L_UNTRACK}" : "{L_TRACK}";
					is_track = xhr_object.responseText == '1' ? true : false;
				}
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		var is_track_pm = {IS_TRACK_PM};
		function XMLHttpRequest_track_pm()
		{
			var idtopic = {IDTOPIC};
			if( document.getElementById('forum_track_pm_img') )
				document.getElementById('forum_track_pm_img').src = '../templates/{THEME}/images/loading_mini.gif';
			
			xhr_object = xmlhttprequest_init('../forum/xmlhttprequest.php?' + (is_track_pm ? 'utp' : 'tp') + '=' + idtopic);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{	
					if( document.getElementById('forum_track_pm_img') )
						document.getElementById('forum_track_pm_img').src = xhr_object.responseText == '1' ? '{MODULE_DATA_PATH}/images/untrack_pm_mini.png' : '{MODULE_DATA_PATH}/images/track_pm_mini.png';
					if( document.getElementById('forum_track_pm_msg') )
						document.getElementById('forum_track_pm_msg').innerHTML = xhr_object.responseText == '1' ? "{L_UNSUSCRIBE_PM}" : "{L_SUSCRIBE_PM}";
					is_track_pm = xhr_object.responseText == '1' ? true : false;
				}
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		var is_track_mail = {IS_TRACK_MAIL};
		function XMLHttpRequest_track_mail()
		{
			var idtopic = {IDTOPIC};
			if( document.getElementById('forum_track_mail_img') )
				document.getElementById('forum_track_mail_img').src = '../templates/{THEME}/images/loading_mini.gif';
			
			xhr_object = xmlhttprequest_init('../forum/xmlhttprequest.php?' + (is_track_mail ? 'utm' : 'tm') + '=' + idtopic);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{	
					if( document.getElementById('forum_track_mail_img') )
						document.getElementById('forum_track_mail_img').src = xhr_object.responseText == '1' ? '{MODULE_DATA_PATH}/images/untrack_mail_mini.png' : '{MODULE_DATA_PATH}/images/track_mail_mini.png';
					if( document.getElementById('forum_track_mail_msg') )
						document.getElementById('forum_track_mail_msg').innerHTML = xhr_object.responseText == '1' ? "{L_UNSUSCRIBE}" : "{L_SUSCRIBE}";
					is_track_mail = xhr_object.responseText == '1' ? true : false;
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
			
			<div class="module_top">
				<span style="float:left;">
					<a href="syndication.php?idcat={ID}" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>
					&bull; {U_FORUM_CAT} <a href="{U_TITLE_T}"><span id="display_msg_title">{DISPLAY_MSG}</span>{TITLE_T}</a> <span style="font-weight:normal"><em>{DESC}</em></span>
				</span>
				<span style="float:right;">
					{PAGINATION} {LOCK} {MOVE}
				</span>
			</div>
		</div>	<br />

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
				<span id="m{msg.ID}" />
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{msg.USER_ONLINE} {msg.USER_PSEUDO}
					</div>
					<span style="float:left;">&nbsp;&nbsp;<a href="topic{msg.U_VARS_ANCRE}#m{msg.ID}" title=""><img src="../templates/{THEME}/images/ancre.png" alt="" /></a> {msg.DATE}</span>
					<span style="float:right;"><a href="topic{msg.U_VARS_QUOTE}#go_bottom" title="{L_QUOTE}"><img src="../templates/{THEME}/images/{LANG}/quote.png" alt="{L_QUOTE}" title="{L_QUOTE}" /></a>{msg.EDIT}{msg.DEL}{msg.CUT}&nbsp;&nbsp;<a href="#go_top"><img src="../templates/{THEME}/images/top.png" alt="" /></a> <a href="#go_bottom"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>&nbsp;&nbsp;</span>
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
					<a href="syndication.php?idcat={ID}" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>
					&bull; {U_FORUM_CAT} <a href="{U_TITLE_T}"><span id="display_msg_title2">{DISPLAY_MSG}</span>{TITLE_T}</a> <span style="font-weight:normal"><em>{DESC}</em></span>
				</span>
				<span style="float:right;">{PAGINATION} {LOCK} {MOVE}</span>&nbsp;
			</div>
		</div>
		
		# INCLUDE forum_bottom #
			
		# IF C_AUTH_POST #
		<form action="post{U_FORUM_ACTION_POST}" method="post" onsubmit="return check_form_msg();" style="width:80%;margin:auto;margin-top:15px;" id="go_bottom">
			<div>
                <div style="font-size:10px;text-align:center;"><label for="contents">{L_RESPOND}</label></div>	
                {KERNEL_EDITOR}
                <label><textarea class="post" rows="15" cols="66" id="contents" name="contents">{CONTENTS}</textarea></label>
                <fieldset class="fieldset_submit" style="padding-top:17px;margin-bottom:0px;">
                    <legend>{L_SUBMIT}</legend>
                    <input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
                    &nbsp;&nbsp;
                    <script type="text/javascript">
                    <!--				
                    document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
                    -->
                    </script>
                    <noscript><div><input value="{L_PREVIEW}" type="submit" name="prw" class="submit" /></div></noscript>
                    &nbsp;&nbsp;
                    <input type="reset" value="{L_RESET}" class="reset" />
                </fieldset>
            </div>
		</form>
		<div class="forum_action">
			# IF C_DISPLAY_MSG #
			<span id="forum_change_statut">
				<a href="action{U_ACTION_MSG_DISPLAY}#go_bottom">{ICON_DISPLAY_MSG}</a>	<a href="action{U_ACTION_MSG_DISPLAY}#go_bottom" class="small_link">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</a>
			</span>
			<script type="text/javascript">
			<!--				
			document.getElementById('forum_change_statut').style.display = 'none';
			document.write('<a href="javascript:XMLHttpRequest_change_statut()" class="small_link">{ICON_DISPLAY_MSG2}</a> <a href="javascript:XMLHttpRequest_change_statut()" class="small_link"><span id="forum_change_msg">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</span></a>');
			-->
			</script>
			&bull;
			# ENDIF #
			<a href="alert{U_ALERT}#go_bottom" class="small_link"><img class="valign_middle" src="{MODULE_DATA_PATH}/images/important_mini.png" alt="" /> {L_ALERT}</a>
			<br />
			
			<span id="forum_track">
				<a href="action{U_SUSCRIBE}#go_bottom">{ICON_TRACK}</a> <a href="action{U_SUSCRIBE}#go_bottom" class="small_link">{L_TRACK_DEFAULT}</a>
			</span>
			<script type="text/javascript">
			<!--				
			document.getElementById('forum_track').style.display = 'none';
			document.write('<a href="javascript:XMLHttpRequest_track()" class="small_link">{ICON_TRACK2}</a> <a href="javascript:XMLHttpRequest_track()" class="small_link"><span id="forum_track_msg">{L_TRACK_DEFAULT}</span></a>');
			-->
			</script>
			&bull;
			<span id="forum_track_pm">
				<a href="action{U_SUSCRIBE}#go_bottom">{ICON_SUSCRIBE_PM}</a> <a href="action{U_SUSCRIBE_PM}#go_bottom" class="small_link">{L_SUSCRIBE_PM_DEFAULT}</a>
			</span>
			<script type="text/javascript">
			<!--				
			document.getElementById('forum_track_pm').style.display = 'none';
			document.write('<a href="javascript:XMLHttpRequest_track_pm()" class="small_link">{ICON_SUSCRIBE_PM2}</a> <a href="javascript:XMLHttpRequest_track_pm()" class="small_link"><span id="forum_track_pm_msg">{L_SUSCRIBE_PM_DEFAULT}</span></a>');
			-->
			</script>
			&bull;
			<span id="forum_track_mail">
				<a href="action{U_SUSCRIBE}#go_bottom">{ICON_SUSCRIBE}</a> <a href="action{U_SUSCRIBE}#go_bottom" class="small_link">{L_SUSCRIBE_DEFAULT}</a>
			</span>
			<script type="text/javascript">
			<!--				
			document.getElementById('forum_track_mail').style.display = 'none';
			document.write('<a href="javascript:XMLHttpRequest_track_mail()" class="small_link">{ICON_SUSCRIBE2}</a> <a href="javascript:XMLHttpRequest_track_mail()" class="small_link"><span id="forum_track_mail_msg">{L_SUSCRIBE_DEFAULT}</span></a>');
			-->
			</script>
			
		</div>
		# ENDIF #
		
		# IF C_ERROR_AUTH_WRITE #
		<div style="font-size:10px;text-align:center;padding-bottom:2px;">{L_RESPOND}</div>	
		<div class="forum_text_column" style="width:350px;margin:auto;height:auto;padding:2px;">
			{L_ERROR_AUTH_WRITE}
		</div>
		# ENDIF #
		
