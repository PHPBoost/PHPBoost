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

		function XMLHttpRequest_del(id)
		{
			var xhr_object = null;
			var data = null;
			var filename = "xmlhttprequest.php?del=1&id=" + id;
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
				
			xhr_object.open("GET", filename, false);
			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(filename); 
		}

		function del_msg(divID)
		{
			if( confirm('{L_DELETE_MESSAGE}') )
			{
				XMLHttpRequest_del(divID);
				
				divID = 'd' + divID;
				if( document.getElementById(divID) && document.getElementById(divID) ) //Pour les navigateurs récents
				{
					document.getElementById(divID).style.display = 'none';
					document.getElementById(divID + '2').style.display = 'none';
					document.getElementById(divID + '3').style.display = 'none';
				}
			}
		}

		{JAVA}

		-->
		</script>

		<span id="go_top"></span>	

		<div class="forum_title">{FORUM_NAME}</div>
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
			<div class="module_top">
				<span style="float:left;">
					<a href="rss.php?cat={ID}" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a>
					&bull; {U_FORUM_CAT} {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span>
				</span>
				<span style="float:right;">{PAGINATION} {LOCK} {MOVE}</span>&nbsp;
			</div>
		</div>	

		# START poll #
		<div class="module_position">
			<div class="module_contents">
				<form method="post" action="action{poll.U_POLL_ACTION}">
					<table class="module_table" style="width:70%">
						<tr>
							<th>{poll.L_POLL}: {poll.QUESTION}</th>
						</tr>							
						# START radio #
						<tr>
							<td class="row2" style="font-size:10px;">
								<label><input type="{poll.radio.TYPE}" name="radio" value="{poll.radio.NAME}" /> {poll.radio.ANSWERS}</label>
							</td>
						</tr>
						# END radio #		
						
						# START checkbox #
						<tr>
							<td class="row2">
								<label><input type="{poll.checkbox.TYPE}" name="{poll.checkbox.NAME}" value="{poll.checkbox.NAME}" /> {poll.checkbox.ANSWERS}</label>
							</td>
						</tr>
						# END checkbox #
						
						# START result #
						<tr>
							<td class="row2" style="font-size:10px;">
								{poll.result.ANSWERS}
								<table width="95%">
									<tr>
										<td>
											<img src="../templates/{THEME}/images/poll_left.png" height="8px" width="" alt="{poll.result.PERCENT}%" title="{poll.result.PERCENT}%" /><img src="../templates/{THEME}/images/poll.png" height="8px" width="{poll.result.WIDTH}" alt="{poll.result.PERCENT}%" title="{poll.result.PERCENT}%" /><img src="../templates/{THEME}/images/poll_right.png" height="8px" width="" alt="{poll.result.PERCENT}%" title="{poll.result.PERCENT}%" /> {poll.result.PERCENT}% [{poll.result.NBRVOTE} {poll.L_VOTE}]
										</td>
									</tr>
								</table>
							</td>
						</tr>
						# END result #										
					</table>
					
					# START question #
					<br />
					<fieldset class="fieldset_submit">
						<legend>{poll.L_VOTE}</legend>
						<input class="submit" name="valid_forum_poll" type="submit" value="{poll.L_VOTE}" /><br />
						<a class="small_link" href="topic{poll.U_POLL_RESULT}">{poll.L_RESULT}</a>
					</fieldset>						
					# END question #
				</form>
			</div>
		</div>
		# END poll #

		# START msg #		
		<div class="msg_position" id="d{msg.ID}">
			<div class="msg_container">
				<span id="m{msg.ID}">
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{msg.USER_ONLINE} {msg.USER_PSEUDO}
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="topic{msg.U_VARS_ANCRE}#m{msg.ID}" title=""><img src="../templates/{THEME}/images/ancre.png" alt="" /></a> {msg.DATE}</div>
					<div style="float:right;"><a href="topic{msg.U_VARS_QUOTE}#go_bottom" title="{L_QUOTE}"><img src="../templates/{THEME}/images/{LANG}/quote.png" alt="{L_QUOTE}" title="{L_QUOTE}" /></a>{msg.EDIT}{msg.DEL}{msg.CUT} <a href="topic{msg.U_VARS_ANCRE}#go_top"><img src="../templates/{THEME}/images/top.png" alt="" /></a> <a href="topic{msg.U_VARS_ANCRE}#go_bottom"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>&nbsp;&nbsp;</div>
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
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							{msg.CONTENTS}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				{msg.USER_SIGN}				
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
				<span style="float:left;" class="text_strong">
					<a href="rss.php?cat={ID}" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a>
					&bull; {U_FORUM_CAT} {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span>
				</span>
				<span style="float:right;">{PAGINATION} {LOCK} {MOVE}</span>&nbsp;
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
		
		<br />
			
		# START post #			

		<span id="go_bottom"></span>
		<div style="font-size:11px;text-align:center;padding-bottom:2px;"><label for="contents">{L_RESPOND}</label></div>	
		<form action="post{post.U_FORUM_ACTION_POST}" method="post" onsubmit="return check_form_msg();" style="width:80%;margin:auto">		
			{BBCODE}		
			<label><textarea type="text" class="post" rows="15" cols="66" id="contents" name="contents">{post.CONTENTS}</textarea></label>
			<div style="padding:17px;">					
				<fieldset class="fieldset_submit">
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
			</div>	
			
			<br />
			
			<table class="forum_action">
				<tr>
					# START display_msg #
					<td>
						<a href="action{post.display_msg.U_ACTION_MSG_DISPLAY}#go_bottom">{post.display_msg.ICON_DISPLAY_MSG}</a>
						<a href="action{post.display_msg.U_ACTION_MSG_DISPLAY}#go_bottom">{post.display_msg.L_EXPLAIN_DISPLAY_MSG}</a>
					</td>	
					# END display_msg #			
					<td>
						<a href="action{U_SUSCRIBE}#go_bottom"><img class="valign_middle" src="{MODULE_DATA_PATH}/images/favorite.png" alt="" /></a> <a href="action{U_SUSCRIBE}#go_bottom">{L_SUSCRIBE}</a>
					</td>
					<td>
						<a href="alert{U_ALERT}#go_bottom"><img class="valign_middle" src="../templates/{THEME}/images/important.png" alt="" /> <a href="alert{U_ALERT}#go_bottom">{L_ALERT}</a>
					</td>
				</tr>
			</table>
		</form>
			
		# END post #
		
		# START error_auth_write #
		<div style="font-size: 10px;text-align:center;padding-bottom: 2px;">{L_RESPOND}</div>	
		<div class="forum_text_column" style="width:350px;margin:auto;height:auto;padding:2px;">
			{error_auth_write.L_ERROR_AUTH_WRITE}			
		</div>
		<br />
		# END error_auth_write #
		
				