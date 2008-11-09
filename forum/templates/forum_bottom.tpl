		<div class="module_position" style="margin-top:15px;">
			<div class="forum_links" style="border-bottom:none;">
				<div style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a> 
				</div>
				<div style="float:right;">
					<img src="{MODULE_DATA_PATH}/images/track_mini.png" alt="" class="valign_middle" /> {U_TOPIC_TRACK} &bull;
					<img src="{MODULE_DATA_PATH}/images/last_mini.png" alt="" class="valign_middle" /> {U_LAST_MSG_READ} &bull;
					<img src="{MODULE_DATA_PATH}/images/new_mini.png" alt="" class="valign_middle" /> <span id="nbr_unread_topics2">{U_MSG_NOT_READ}</span>
					
					<div style="position:relative;float:left;">
						<div style="position:absolute;z-index:100;float:left;margin-left:130px;display:none;" id="forum_blockforum_unread2">
						</div>
					</div>
					<a href="javascript:XMLHttpRequest_unread_topics('2');" onmouseover="forum_hide_block('forum_unread2', 1);" onmouseout="forum_hide_block('forum_unread2', 0);"><img src="../templates/{THEME}/images/refresh_mini.png" alt="" id="refresh_unread2" class="valign_middle" /></a>
					
					&bull;
					<img src="../templates/{THEME}/images/read_mini.png" alt="" class="valign_middle" /> {U_MSG_SET_VIEW}
				</div>
				<div class="spacer"></div>
			</div>
			
			<div class="forum_online">
				# IF C_FORUM_CONNEXION #
					# IF C_MEMBER_NOTCONNECTED #
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
					{L_MEMBER} {L_ONLINE}: {USERS_ONLINE}
				</span>
				<div style="float:right;text-align:right">
					# IF SELECT_CAT #
					<form action="{U_CHANGE_CAT}" method="post">
                        <div>
                            <select name="change_cat" onchange="document.location = {U_ONCHANGE};">
                                {SELECT_CAT}
                            </select>
                            <noscript>
                                <div><input type="submit" name="valid_change_cat" value="Go" class="submit" /></div>
                            </noscript>
                        </div>
					</form>
					# ENDIF #
						
					# IF C_MASS_MODO_CHECK #
					<form action="action.php{SID}">
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
						<a href="stats.php{SID}">{L_STATS}</a> <a href="stats.php{SID}"><img src="{MODULE_DATA_PATH}/images/stats.png" alt="" class="valign_middle" /></a>
					</span>
					<div class="spacer"></div>
				</div>
				# ENDIF #
			</div>
		</div>