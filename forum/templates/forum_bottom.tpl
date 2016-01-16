		
		<footer id="forum-bottom">
			<div class="forum-links">
				<nav class="cssmenu cssmenu-group float-left forum-index">
					<ul>
						<li>
							<span class="cssmenu-title">
								<i class="fa fa-globe"></i> <a class="small" href="index.php" title="{L_FORUM_INDEX}">{L_FORUM_INDEX}</a>
							</span>
						</li>
					</ul>
				</nav>
			# IF C_USER_CONNECTED #
				<nav class="cssmenu cssmenu-group float-right" id="cssmenu-forum-bottom-link">
					<ul>
						<li>
							<span class="cssmenu-title">
								<i class="fa fa-msg-track"></i> {U_TOPIC_TRACK}
							</span>
						</li>
						<li>
							<span class="cssmenu-title">
								<i class="fa fa-lastview"></i> {U_LAST_MSG_READ}
							</span>
						</li>
						<li>
							<span class="cssmenu-title">
								<i class="fa fa-notread"></i> <span id="nbr_unread_topics2">{U_MSG_NOT_READ}</span>
								<div class="forum-refresh">
									<div style="display:none;" id="forum_blockforum_unread2">
									</div>
								</div>
								<a href="" onclick="XMLHttpRequest_unread_topics('2');return false;" onmouseover="forum_hide_block('forum_unread2', 1);" onmouseout="forum_hide_block('forum_unread2', 0);"><i class="fa fa-refresh" id="refresh_unread2"></i></a>
							</span>
						</li>
						<li>
							<span class="cssmenu-title">
								<i class="fa fa-eraser"></i> {U_MSG_SET_VIEW}
							</span>
						</li>
				# IF C_FORUM_CONNEXION #
						<li>
							<span class="cssmenu-title">
								<i class="fa fa-sign-out"></i> <a title="{L_DISCONNECT}" class="small" href="${relative_url(UserUrlBuilder::disconnect())}" title="{L_DISCONNECT}">{L_DISCONNECT}</a>
							</span>
						</li>
				# ENDIF #
					</ul>
				</nav>
			# ELSE #
				# IF C_FORUM_CONNEXION #
				<nav class="cssmenu cssmenu-group float-right" id="cssmenu-forum-top-link">
					<ul>
						<li>
							<span class="cssmenu-title">
								<i class="fa fa-sign-in"></i> <a title="{L_CONNECT}" class="small" href="${relative_url(UserUrlBuilder::connect())}" title="{L_CONNECT}">{L_CONNECT}</a>
							</span>
						</li>
						<li>
							<span class="cssmenu-title">
								<i class="fa fa-ticket"></i> <a title="{L_REGISTER}" class="small" href="${relative_url(UserUrlBuilder::registration())}" title="{L_REGISTER}">{L_REGISTER}</a>
							</span>
						</li>
					</ul>
				</nav>
				# ENDIF #
			# ENDIF #
				
				<div class="spacer"></div>
			</div>
			<script>
				<!--
				jQuery("#cssmenu-forum-bottom-link").menumaker({ title: " ${LangLoader::get_message('forum.links', 'common', 'forum')} ", format: "multitoggle", breakpoint: 768, menu_static: false });
				-->
			</script>
			
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
							<select name="change_cat" onchange="if(this.options[this.selectedIndex].text.substring(0, 3) == '-- ') document.location = '{U_ONCHANGE_CAT}'; else document.location = '{U_ONCHANGE}';" class="forum-online-select">
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
				<div>
					<span class="float-left">
						{L_TOTAL_POST}: <strong>{NBR_MSG}</strong> {L_MESSAGE} {L_DISTRIBUTED} <strong>{NBR_TOPIC}</strong> {L_TOPIC}
					</span>
					<span class="float-right">
						<a href="{PATH_TO_ROOT}/forum/stats.php" title="{L_STATS}"><i class="fa fa-bar-chart-o"></i> {L_STATS}</a>
					</span>
					<div class="spacer"></div>
				</div>
				# ENDIF #
				
				# IF C_AUTH_POST #
				<div class="forum-links forum-message-options">
					<nav id="cssmenu-forum-action" class="cssmenu cssmenu-group">
						<ul>
						# IF C_DISPLAY_MSG #
							<li>
								<span class="cssmenu-title" id="forum_change_statut">
									<a href="" onclick="XMLHttpRequest_change_statut();return false;" id="forum_change_img">{ICON_DISPLAY_MSG}</a> <a href="" onclick="XMLHttpRequest_change_statut();return false;"><span id="forum_change_msg">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</span></a>
								</span>
							</li>
						# ENDIF #
							<li>
								<span class="cssmenu-title">
									<a href="{PATH_TO_ROOT}/forum/alert{U_ALERT}" title="{L_ALERT}"><i class="fa fa-warning"></i></a> <a href="{PATH_TO_ROOT}/forum/alert{U_ALERT}" title="{L_ALERT}">{L_ALERT}</a>
								</span>
							</li>
							<li>
								<span class="cssmenu-title" id="forum_track">
									<a href="" onclick="XMLHttpRequest_track();return false;" id="forum_track_img">{ICON_TRACK}</a> <a href="" onclick="XMLHttpRequest_track();return false;"><span id="forum_track_msg">{L_TRACK_DEFAULT}</span></a>
								</span>
							</li>
							<li>
								<span class="cssmenu-title" id="forum_track_pm">
									<a href="" onclick="XMLHttpRequest_track_pm();return false;" id="forum_track_pm_img">{ICON_SUSCRIBE_PM}</a> <a href="" onclick="XMLHttpRequest_track_pm();return false;"><span id="forum_track_pm_msg">{L_SUSCRIBE_PM_DEFAULT}</span></a>
								</span>
							</li>
							<li>
								<span class="cssmenu-title" id="forum_track_mail">
									<a href="" onclick="XMLHttpRequest_track_mail();return false;" id="forum_track_mail_img">{ICON_SUSCRIBE}</a> <a href="" onclick="XMLHttpRequest_track_mail();return false;"><span id="forum_track_mail_msg">{L_SUSCRIBE_DEFAULT}</span></a>
								</span>
							</li>
						</ul>
					</nav>
				</div>
			<script>
				<!--
				jQuery("#cssmenu-forum-action").menumaker({ title: " ${LangLoader::get_message('forum.message_options', 'common', 'forum')} ", format: "multitoggle", breakpoint: 768, menu_static: false });
				-->
			</script>
				#  ENDIF #
			</div>
		</footer>
</section>