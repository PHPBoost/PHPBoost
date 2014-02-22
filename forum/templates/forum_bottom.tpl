		<div class="module_position" style="margin-top:15px;">
			<div class="forum_links" style="border-bottom:none;">
				<div style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a> 
				</div>
				# IF C_USER_CONNECTED #
					<div style="float:right;">
						<img src="{PICTURES_DATA_PATH}/images/track_mini.png" alt="" class="valign_middle" /> {U_TOPIC_TRACK} &bull;
						<img src="{PICTURES_DATA_PATH}/images/last_mini.png" alt="" class="valign_middle" /> {U_LAST_MSG_READ} &bull;
						<img src="{PICTURES_DATA_PATH}/images/new_mini.png" alt="" class="valign_middle" /> <span id="nbr_unread_topics2">{U_MSG_NOT_READ}</span>
						
						<div style="position:relative;float:left;">
							<div style="position:absolute;z-index:100;float:left;margin-left:130px;display:none;" id="forum_blockforum_unread2">
							</div>
						</div>
						<a href="javascript:XMLHttpRequest_unread_topics('2');" onmouseover="forum_hide_block('forum_unread2', 1);" onmouseout="forum_hide_block('forum_unread2', 0);"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/refresh_mini.png" alt="" id="refresh_unread2" class="valign_middle" /></a>
						
						&bull;
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/read_mini.png" alt="" class="valign_middle" /> {U_MSG_SET_VIEW}
					</div>
				# ENDIF #
				<div class="spacer"></div>
			</div>
			
			<div class="forum_online">
				# IF C_FORUM_CONNEXION #
					# IF C_USER_NOTCONNECTED #
					<form action="" method="post">
						<p style="margin-bottom:8px;" class="text_small"><label>{L_PSEUDO} <input size="15" type="text" class="text" name="login" maxlength="25" /></label>
						<label>{L_PASSWORD}	<input size="15" type="password" name="password" class="text" maxlength="30" /></label>
						&nbsp;| <label>{L_AUTOCONNECT} <input type="checkbox" name="auto" checked="checked" /></label>
						&nbsp;| <input type="submit" name="connect" value="{L_CONNECT}" class="submit" /></p>
					</form>
					# ENDIF #	
				# ENDIF #	
					
				# IF USERS_ONLINE #
				<span style="float:left;">
					{TOTAL_ONLINE} {L_USER} {L_ONLINE} :: {ADMIN} {L_ADMIN}, {MODO} {L_MODO}, {MEMBER} {L_MEMBER} {L_AND} {GUEST} {L_GUEST}
					<br />
					{L_USER} {L_ONLINE}: {USERS_ONLINE}
				</span>
				<div class="forum_online_select_cat">
					# IF SELECT_CAT #
					<form action="{U_CHANGE_CAT}" method="post">
                        <div>
                            <select name="change_cat" onchange="if(this.options[this.selectedIndex].text.substring(0, 4) == '----') document.location = 'forum{U_ONCHANGE}'; else document.location = '{U_ONCHANGE_CAT}';" class="forum_online_select">
                                {SELECT_CAT}
                            </select>
                            <noscript>
                                <div><input type="submit" name="valid_change_cat" value="Go" class="submit" /></div>
                            </noscript>
                        </div>
					</form>
					# ENDIF #
						
					# IF C_MASS_MODO_CHECK #
					<form action="action.php?token={TOKEN}">
                        <div>
                            {L_FOR_SELECTION}: 
                            <select name="massive_action_type">
                                <option value="change">{L_CHANGE_STATUT_TO}</option>
                                <option value="changebis">{L_CHANGE_STATUT_TO_DEFAULT}</option>
                                <option value="move">{L_MOVE_TO}</option>
                                <option value="lock">{L_LOCK}</option>
                                <option value="unlock">{L_UNLOCK}</option>
                                <option value="del">{L_DELETE}</option>
                            </select>
                            <input type="submit" value="{L_GO}" name="valid" class="submit" />
                        </div>
					</form>
					# ENDIF #
				</div>
				<div class="spacer"></div>
				# ENDIF #
			
				# IF C_TOTAL_POST #
				<div style="margin-top:6px;">
					<span style="float:left;">
						{L_TOTAL_POST}: <strong>{NBR_MSG}</strong> {L_MESSAGE} {L_DISTRIBUTED} <strong>{NBR_TOPIC}</strong> {L_TOPIC}
					</span>
					<span style="float:right;">
						<a href="{PATH_TO_ROOT}/forum/stats.php{SID}">{L_STATS}</a> <a href="{PATH_TO_ROOT}/forum/stats.php{SID}"><img src="{PICTURES_DATA_PATH}/images/stats.png" alt="" class="valign_middle" /></a>
					</span>
					<div class="spacer"></div>
				</div>
				# ENDIF #
				
				# IF C_AUTH_POST #
				<div class="forum_action">
					# IF C_DISPLAY_MSG #
					<span id="forum_change_statut">
						<a href="{PATH_TO_ROOT}/forum/action{U_ACTION_MSG_DISPLAY}#go_bottom">{ICON_DISPLAY_MSG}</a>	<a href="{PATH_TO_ROOT}/forum/action{U_ACTION_MSG_DISPLAY}#go_bottom" class="small_link">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</a>
					</span>
					<script type="text/javascript">
					<!--				
					document.getElementById('forum_change_statut').style.display = 'none';
					document.write('<a href="javascript:XMLHttpRequest_change_statut()" class="small_link">{ICON_DISPLAY_MSG2}</a> <a href="javascript:XMLHttpRequest_change_statut()" class="small_link"><span id="forum_change_msg">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</span></a>');
					-->
					</script>
					&bull;
					# ENDIF #
					<a href="{PATH_TO_ROOT}/forum/alert{U_ALERT}#go_bottom" class="small_link"><img class="valign_middle" src="{PICTURES_DATA_PATH}/images/important_mini.png" alt="" /></a> <a href="alert{U_ALERT}#go_bottom" class="small_link">{L_ALERT}</a>
					<span id="forum_track">
						<a href="{PATH_TO_ROOT}/forum/action{U_SUSCRIBE}#go_bottom">{ICON_TRACK}</a> <a href="{PATH_TO_ROOT}/forum/action{U_SUSCRIBE}#go_bottom" class="small_link">{L_TRACK_DEFAULT}</a>
					</span>
					<script type="text/javascript">
					<!--				
					document.getElementById('forum_track').style.display = 'none';
					document.write('<a href="javascript:XMLHttpRequest_track()" class="small_link">{ICON_TRACK2}</a> <a href="javascript:XMLHttpRequest_track()" class="small_link"><span id="forum_track_msg">{L_TRACK_DEFAULT}</span></a>');
					-->
					</script>
					&bull;
					<span id="forum_track_pm">
						<a href="{PATH_TO_ROOT}/forum/action{U_SUSCRIBE_PM}#go_bottom">{ICON_SUSCRIBE_PM}</a> <a href="{PATH_TO_ROOT}/forum/action{U_SUSCRIBE_PM}#go_bottom" class="small_link">{L_SUSCRIBE_PM_DEFAULT}</a>
					</span>
					<script type="text/javascript">
					<!--				
					document.getElementById('forum_track_pm').style.display = 'none';
					document.write('<a href="javascript:XMLHttpRequest_track_pm()" class="small_link">{ICON_SUSCRIBE_PM2}</a> <a href="javascript:XMLHttpRequest_track_pm()" class="small_link"><span id="forum_track_pm_msg">{L_SUSCRIBE_PM_DEFAULT}</span></a>');
					-->
					</script>
					&bull;
					<span id="forum_track_mail">
						<a href="{PATH_TO_ROOT}/forum/action{U_SUSCRIBE_MAIL}#go_bottom">{ICON_SUSCRIBE}</a> <a href="{PATH_TO_ROOT}/forum/action{U_SUSCRIBE_MAIL}#go_bottom" class="small_link">{L_SUSCRIBE_DEFAULT}</a>
					</span>
					<script type="text/javascript">
					<!--				
					document.getElementById('forum_track_mail').style.display = 'none';
					document.write('<a href="javascript:XMLHttpRequest_track_mail()" class="small_link">{ICON_SUSCRIBE2}</a> <a href="javascript:XMLHttpRequest_track_mail()" class="small_link"><span id="forum_track_mail_msg">{L_SUSCRIBE_DEFAULT}</span></a>');
					-->
					</script>
				</div>
				# ENDIF #
			</div>
		</div>