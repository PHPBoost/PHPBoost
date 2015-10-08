		<div id="forum-bottom">
			<div class="forum-links">
				<div class="float-left">
					&bull; <a href="index.php">{L_FORUM_INDEX}</a> 
				</div>
				# IF C_USER_CONNECTED #
					<div class="right">
						<i class="fa fa-msg-track"></i> {U_TOPIC_TRACK} &bull;
						<i class="fa fa-lastview"></i> {U_LAST_MSG_READ} &bull;
						<i class="fa fa-notread"></i> <span id="nbr_unread_topics2">{U_MSG_NOT_READ}</span>
						
						<div style="position:relative;float:left;">
							<div style="position:absolute;z-index:100;float:left;margin-left:130px;display:none;" id="forum_blockforum_unread2">
							</div>
						</div>
						<a href="javascript:XMLHttpRequest_unread_topics('2');" onmouseover="forum_hide_block('forum_unread2', 1);" onmouseout="forum_hide_block('forum_unread2', 0);"><i class="fa fa-refresh" id="refresh_unread2"></i></a>
						
						&bull;
						<i class="fa fa-eraser"></i> {U_MSG_SET_VIEW}
					</div>
				# ENDIF #
				<div class="spacer"></div>
			</div>
			# IF C_FORUM_CONNEXION #
				# IF C_USER_NOTCONNECTED #
				<div class="forum-title">
					<a class="small" href="${relative_url(UserUrlBuilder::connect())}"><i class="fa fa-sign-in"></i> {L_CONNECT}</a> <span style="color:#000000;">&bull;</span> <a class="small" href="${relative_url(UserUrlBuilder::registration())}"><i class="fa fa-ticket"></i> {L_REGISTER}</a>
				</div>
				# ENDIF #
			# ENDIF #
			
			<div class="forum-online">
				# IF USERS_ONLINE #
				<span class="float-left">
					{TOTAL_ONLINE} {L_USER} {L_ONLINE} : {ADMIN} {L_ADMIN}, {MODO} {L_MODO}, {MEMBER} {L_MEMBER} {L_AND} {GUEST} {L_GUEST}
					<br />
					{L_USER} {L_ONLINE} : {USERS_ONLINE}
				</span>
				<div class="forum-online-select-cat">
					# IF SELECT_CAT #
					<form action="{U_CHANGE_CAT}" method="post">
						<div>
							<select name="change_cat" onchange="if(this.options[this.selectedIndex].text.substring(0, 4) == '----') document.location = 'forum{U_ONCHANGE}'; else document.location = '{U_ONCHANGE_CAT}';" class="forum-online-select">
								{SELECT_CAT}
							</select>
						</div>
						<input type="hidden" name="token" value="{TOKEN}">
					</form>
					# ENDIF #
						
					# IF C_MASS_MODO_CHECK #
					<form action="action.php">
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
							<button type="submit" value="true" name="valid" class="submit">{L_GO}</button>
							<input type="hidden" name="token" value="{TOKEN}">
						</div>
					</form>
					# ENDIF #
				</div>
				<div class="spacer"></div>
				# ENDIF #
			
				# IF C_TOTAL_POST #
				<div style="margin-top:6px;">
					<span class="float-left">
						{L_TOTAL_POST}: <strong>{NBR_MSG}</strong> {L_MESSAGE} {L_DISTRIBUTED} <strong>{NBR_TOPIC}</strong> {L_TOPIC}
					</span>
					<span class="float-right">
						<a href="{PATH_TO_ROOT}/forum/stats.php"><i class="fa fa-bar-chart-o"></i> {L_STATS}</a>
					</span>
					<div class="spacer"></div>
				</div>
				# ENDIF #
				
				# IF C_AUTH_POST #
				<div class="forum-action">
					# IF C_DISPLAY_MSG #
					<span id="forum_change_statut">
						<a href="{PATH_TO_ROOT}/forum/action{U_ACTION_MSG_DISPLAY}#go_bottom">{ICON_DISPLAY_MSG}</a>	<a href="{PATH_TO_ROOT}/forum/action{U_ACTION_MSG_DISPLAY}#go_bottom" class="small">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</a>
					</span>
					&bull;
					# ENDIF #
					<a href="{PATH_TO_ROOT}/forum/alert{U_ALERT}#go_bottom" class="fa fa-warning"></a> <a href="alert{U_ALERT}#go_bottom" class="small">{L_ALERT}</a>
					<span id="forum_track">
						<a href="{PATH_TO_ROOT}/forum/action{U_SUSCRIBE}#go_bottom">{ICON_TRACK}</a> <a href="{PATH_TO_ROOT}/forum/action{U_SUSCRIBE}#go_bottom" class="small">{L_TRACK_DEFAULT}</a>
					</span>
					&bull;
					<span id="forum_track_pm">
						<a href="{PATH_TO_ROOT}/forum/action{U_SUSCRIBE_PM}#go_bottom">{ICON_SUSCRIBE_PM}</a> <a href="{PATH_TO_ROOT}/forum/action{U_SUSCRIBE_PM}#go_bottom" class="small">{L_SUSCRIBE_PM_DEFAULT}</a>
					</span>
					&bull;
					<span id="forum_track_mail">
						<a href="{PATH_TO_ROOT}/forum/action{U_SUSCRIBE_MAIL}#go_bottom">{ICON_SUSCRIBE}</a> <a href="{PATH_TO_ROOT}/forum/action{U_SUSCRIBE_MAIL}#go_bottom" class="small">{L_SUSCRIBE_DEFAULT}</a>
					</span>
				</div>
				# ENDIF #
			</div>
		</div>