		<span id="go_top"></span>	
		
		# INCLUDE forum_top #
		
		<script type="text/javascript">
		<!--
		function check_form_msg(){
			# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
			# ENDIF #	
			
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_MESSAGE}");
				return false;
		    }
			return true;
		}
		function XMLHttpRequest_del(idmsg)
		{
			if( document.getElementById('dimg' + idmsg) )
				document.getElementById('dimg' + idmsg).src = '{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif';
			
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&del=1&idm=' + idmsg + '&token={TOKEN}');
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
						document.getElementById('dimg' + idmsg).src = '{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png';
				}
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		function XMLHttpRequest_change_statut()
		{
			var idtopic = {IDTOPIC};			
			if( document.getElementById('forum_change_img') )
				document.getElementById('forum_change_img').src = '{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif';
			
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?msg_d=' + idtopic + '&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{	
					document.getElementById('display_msg_title').innerHTML = xhr_object.responseText == '1' ? "{L_DISPLAY_MSG}" + ' ' : '';
					document.getElementById('display_msg_title2').innerHTML = xhr_object.responseText == '1' ? "{L_DISPLAY_MSG}" + ' ' : '';
					if( document.getElementById('forum_change_img') )
						document.getElementById('forum_change_img').src = xhr_object.responseText == '1' ? '{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png' : '{PATH_TO_ROOT}/templates/{THEME}/images/processed_mini.png';
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
				document.getElementById('forum_track_img').src = '{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif';
			
			xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&' + (is_track ? 'ut' : 't') + '=' + idtopic);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{	
					if( document.getElementById('forum_track_img') )
						document.getElementById('forum_track_img').src = xhr_object.responseText == '1' ? '{PICTURES_DATA_PATH}/images/untrack_mini.png' : '{PICTURES_DATA_PATH}/images/track_mini.png';
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
				document.getElementById('forum_track_pm_img').src = '{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif';
			
			xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&' + (is_track_pm ? 'utp' : 'tp') + '=' + idtopic);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{	
					if( document.getElementById('forum_track_pm_img') )
						document.getElementById('forum_track_pm_img').src = xhr_object.responseText == '1' ? '{PICTURES_DATA_PATH}/images/untrack_pm_mini.png' : '{PICTURES_DATA_PATH}/images/track_pm_mini.png';
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
				document.getElementById('forum_track_mail_img').src = '{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif';
			
			xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&' + (is_track_mail ? 'utm' : 'tm') + '=' + idtopic);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{	
					if( document.getElementById('forum_track_mail_img') )
						document.getElementById('forum_track_mail_img').src = xhr_object.responseText == '1' ? '{PICTURES_DATA_PATH}/images/untrack_mail_mini.png' : '{PICTURES_DATA_PATH}/images/track_mail_mini.png';
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
		function Confirm_del_topic() {
			return confirm("{L_ALERT_DELETE_TOPIC}");
		}		
		function Confirm_lock_topic() {
			return confirm("{L_ALERT_LOCK_TOPIC}");
		}		
		function Confirm_unlock_topic() {
			return confirm("{L_ALERT_UNLOCK_TOPIC}");
		}		
		function Confirm_move_topic() {
			return confirm("{L_ALERT_MOVE_TOPIC}");
		}
		function Confirm_cut_topic() {
			return confirm("{L_ALERT_CUT_TOPIC}");
		}
		-->
		</script>

		<div class="module_position">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
				<a href="${relative_url(SyndicationUrlBuilder::rss('forum',ID))}" title="Rss"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>
				&bull; {U_FORUM_CAT} <a href="{U_TITLE_T}"><span id="display_msg_title">{DISPLAY_MSG}</span>{TITLE_T}</a> <span class="desc_forum"><em>{DESC}</em></span>
				
				<span style="float:right;">
					{PAGINATION} 
					
					# IF C_FORUM_MODERATOR #
						# IF C_FORUM_LOCK_TOPIC #
					<a href="action{U_TOPIC_LOCK}" onclick="javascript:return Confirm_lock_topic();" title="{L_TOPIC_LOCK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/lock.png" alt="{L_TOPIC_LOCK}" title="{L_TOPIC_LOCK}" class="valign_middle" /></a>
						# ELSE #
					<a href="action{U_TOPIC_UNLOCK}" onclick="javascript:return Confirm_unlock_topic();" title="{L_TOPIC_LOCK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/unlock.png" alt="{L_TOPIC_LOCK}" title="{L_TOPIC_LOCK}" class="valign_middle" /></a>
						# ENDIF #
					
					<a href="move{U_TOPIC_MOVE}" onclick="javascript:return Confirm_move_topic();" title="{L_TOPIC_MOVE}"><img src="{PICTURES_DATA_PATH}/images/move.png" alt="{L_TOPIC_MOVE}" title="{L_TOPIC_MOVE}" class="valign_middle" /></a>
					# ENDIF #
				</span>
			</div>
		</div>	

		# IF C_POLL_EXIST #
		<div class="module_position">
			<div class="module_contents">
				<form method="post" action="action{U_POLL_ACTION}">
					<table class="module_table" style="width:75%">
						<tr>
							<th>{L_POLL}: {QUESTION}</th>
						</tr>
						# START poll_radio #
						<tr>
							<td class="row2" style="font-size:10px;">
								<label><input type="{poll_radio.TYPE}" name="forumpoll" value="{poll_radio.NAME}" /> {poll_radio.ANSWERS}</label>
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
											<img src="{PATH_TO_ROOT}/templates/{THEME}/images/poll_left.png" height="8px" width="" alt="{poll_result.PERCENT}%" title="{poll_result.PERCENT}%" /><img src="{PATH_TO_ROOT}/templates/{THEME}/images/poll.png" height="8px" width="{poll_result.WIDTH}" alt="{poll_result.PERCENT}%" title="{poll_result.PERCENT}%" /><img src="{PATH_TO_ROOT}/templates/{THEME}/images/poll_right.png" height="8px" width="" alt="{poll_result.PERCENT}%" title="{poll_result.PERCENT}%" /> {poll_result.PERCENT}% [{poll_result.NBRVOTE} {L_VOTE}]
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
						# IF msg.C_FORUM_USER_LOGIN # 
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{msg.FORUM_ONLINE_STATUT_USER}.png" alt="" class="valign_middle" />
							<a class="msg_link_pseudo" href="{msg.U_FORUM_USER_PROFILE}">{msg.FORUM_USER_LOGIN}</a>
						# ELSE # 
							<em>{L_GUEST}</em>
						# ENDIF #
					</div>
					<span style="float:left;">&nbsp;&nbsp;<a href="topic{msg.U_VARS_ANCRE}#m{msg.ID}" title=""><img src="{PATH_TO_ROOT}/templates/{THEME}/images/ancre.png" alt="" /></a> {msg.FORUM_MSG_DATE}</span>
					<span style="float:right;"><a href="topic{msg.U_VARS_QUOTE}#go_bottom" title="{L_QUOTE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/quote.png" alt="{L_QUOTE}" title="{L_QUOTE}" /></a>
					# IF msg.C_FORUM_MSG_EDIT # 
					&nbsp;&nbsp;<a href="post{msg.U_FORUM_MSG_EDIT}" title=""><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" title="{L_EDIT}" /></a>
					# ENDIF #
					
					# IF msg.C_FORUM_MSG_DEL #
					&nbsp;
						# IF msg.C_FORUM_MSG_DEL_MSG #
					<a href="action{msg.U_FORUM_MSG_DEL}" title=""><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" id="dimgnojs{msg.ID}" /></a>
					<img style="cursor:pointer;display:none" onclick="del_msg('{msg.ID}');" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" id="dimg{msg.ID}" /> 
					<script type="text/javascript">
					<!--
						document.getElementById('dimgnojs{msg.ID}').style.display = 'none';
						document.getElementById('dimg{msg.ID}').style.display = 'inline';
					-->
					</script>
						# ELSE #
					<a href="action{msg.U_FORUM_MSG_DEL}" title="" onclick="javascript:return Confirm_del_topic();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a> 
						# ENDIF #
					# ENDIF #
					
					# IF msg.C_FORUM_MSG_CUT # &nbsp;&nbsp;<a href="move{msg.U_FORUM_MSG_CUT}" title="{L_CUT_TOPIC}" onclick="javascript:return Confirm_cut_topic();"><img src="{PICTURES_DATA_PATH}/images/cut.png" alt="{L_CUT_TOPIC}" /></a> # ENDIF #
					
					&nbsp;&nbsp;<a href="{U_TITLE_T}#go_top" onclick="new Effect.ScrollTo('go_top',{duration:1.2}); return false;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" /></a> <a href="{U_TITLE_T}#go_bottom" onclick="new Effect.ScrollTo('go_bottom',{duration:1.2}); return false;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" /></a>&nbsp;&nbsp;</span>
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
							# IF msg.L_FORUM_QUOTE_LAST_MSG # <span class="text_strong">{msg.L_FORUM_QUOTE_LAST_MSG}</span><br /><br /> # ENDIF #
							
							{msg.FORUM_MSG_CONTENTS}
							
							# IF msg.C_FORUM_USER_EDITOR # 
							<br /><br /><br /><br /><span style="padding: 10px;font-size:10px;font-style:italic;">
							{L_EDIT_BY}
								# IF msg.C_FORUM_USER_EDITOR_LOGIN # 
							<a class="small_link" href="{msg.U_FORUM_USER_EDITOR_PROFILE}">{msg.FORUM_USER_EDITOR_LOGIN}</a>
								# ELSE #
							<em>{L_GUEST}</em>
								# ENDIF #
							{L_ON} {msg.FORUM_USER_EDITOR_DATE}</span>
							# ENDIF #
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
					&nbsp;
					# IF msg.C_FORUM_MODERATOR # 
					{msg.USER_WARNING}%
					<a href="moderation_forum{msg.U_FORUM_WARNING}" title="{L_WARNING_MANAGEMENT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/important.png" alt="{L_WARNING_MANAGEMENT}" class="valign_middle" /></a>
					<a href="moderation_forum{msg.U_FORUM_PUNISHEMENT}" title="{L_PUNISHEMENT_MANAGEMENT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/readonly.png" alt="{L_PUNISHEMENT_MANAGEMENT}" class="valign_middle" /></a>
					# ENDIF #
				</span>&nbsp;
			</div>	
		</div>	
		# END msg #
		<div class="module_position">
			<div class="module_bottom_l"></div>
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<a href="${relative_url(SyndicationUrlBuilder::rss('forum',ID))}" title="Rss"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>
				&bull; {U_FORUM_CAT} <a href="{U_TITLE_T}"><span id="display_msg_title2">{DISPLAY_MSG}</span>{TITLE_T}</a> <span class="desc_forum"><em>{DESC}</em></span>
				
				<span style="float:right;">
					{PAGINATION}
					
					# IF C_FORUM_MODERATOR #
						# IF C_FORUM_LOCK_TOPIC #
					<a href="action{U_TOPIC_LOCK}" onclick="javascript:return Confirm_lock_topic();" title="{L_TOPIC_LOCK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/lock.png" alt="{L_TOPIC_LOCK}" title="{L_TOPIC_LOCK}" class="valign_middle" /></a>
						# ELSE #
					<a href="action{U_TOPIC_UNLOCK}" onclick="javascript:return Confirm_unlock_topic();" title="{L_TOPIC_LOCK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/unlock.png" alt="{L_TOPIC_LOCK}" title="{L_TOPIC_LOCK}" class="valign_middle" /></a>
						# ENDIF #
						
					<a href="move{U_TOPIC_MOVE}" onclick="javascript:return Confirm_move_topic();" title="{L_TOPIC_MOVE}"><img src="{PICTURES_DATA_PATH}/images/move.png" alt="{L_TOPIC_MOVE}" title="{L_TOPIC_MOVE}" class="valign_middle" /></a>
					# ENDIF #
				</span>&nbsp;
			</div>
		</div>
		
		# INCLUDE forum_bottom #
			
		<span id="go_bottom"></span>
		# IF C_AUTH_POST #
		<div class="forum_post_form">
			<form action="post{U_FORUM_ACTION_POST}" method="post" onsubmit="return check_form_msg();">
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
						document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" type="button" class="submit" />');
						-->
						</script>
						<noscript><div><input value="{L_PREVIEW}" type="submit" name="prw" class="submit" /></div></noscript>
						&nbsp;&nbsp;
						<input type="reset" value="{L_RESET}" class="reset" />
					</fieldset>
				</div>
			</form>
        </div>
		# ENDIF #
		
		# IF C_ERROR_AUTH_WRITE #
		<div style="font-size:10px;text-align:center;padding-bottom:2px;">{L_RESPOND}</div>	
		<div class="forum_text_column" style="width:350px;margin:auto;height:auto;padding:2px;">
			{L_ERROR_AUTH_WRITE}
		</div>
		# ENDIF #
		