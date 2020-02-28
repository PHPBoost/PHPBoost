
<footer id="forum-bottom">
	<div class="forum-links">
		# IF C_USER_CONNECTED #
			<nav class="cssmenu cssmenu-group float-right" id="cssmenu-forum-bottom-link">
				<ul>
					<li>
						<span class="cssmenu-title">
							<a href="index.php" aria-label="{L_FORUM_INDEX}"><i class="fa fa-fw fa-home" aria-hidden="true"></i> <span class="hidden-large-screens">{L_FORUM_INDEX}</span></a>
						</span>
					</li>
					<li>
						<span class="cssmenu-title">
							<a href="{U_SHOW_MY_MSG}" aria-label="{L_SHOW_MY_MSG}"><i class="far fa-fw fa-user-circle" aria-hidden="true"></i> <span class="hidden-large-screens">{L_SHOW_MY_MSG}</span></a>
						</span>
					</li>
					<li>
						<span class="cssmenu-title">
							<a href="{U_TOPIC_TRACK}" aria-label="{L_SHOW_TOPIC_TRACK}"><i class="fa fa-fw fa-heart error" aria-hidden="true"></i> <span class="hidden-large-screens">{L_SHOW_TOPIC_TRACK}</span></a>
						</span>
					</li>
					<li class="forum-index">
						<span class="cssmenu-title">
							<a href="{U_LAST_MSG_READ}" aria-label="{L_SHOW_LAST_READ}"><i class="far fa-fw fa-clock" aria-hidden="true"></i> <span class="hidden-large-screens">{L_SHOW_LAST_READ}</span></a>
						</span>
					</li>
					<li>
						<div class="cssmenu-title">
							<a href="{U_MSG_NOT_READ}" aria-label="{L_SHOW_NOT_READS}"><i class="far fa-fw fa-file-alt" aria-hidden="true"></i><span id="nbr_unread_topics_bottom">{NBR_MSG_NOT_READ}</span><span class="hidden-large-screens">{L_SHOW_NOT_READS}</span></a>
							<div class="forum-refresh">
								<div id="forum_block_forum_unread_bottom" style="display: none;"></div>
							</div>
							<a href="#" class="reload-unread" onclick="XMLHttpRequest_unread_topics('forum_unread_bottom');return false;" onmouseover="forum_hide_block('forum_unread_bottom', 1);" onmouseout="forum_hide_block('forum_unread_bottom', 0);"><i class="fa fa-fw fa-sync" id="refresh_forum_unread_bottom"></i><span class="sr-only">${LangLoader::get_message('forum.links', 'common', 'forum')}</span></a>
						</div>
					</li>
					<li>
						<span class="cssmenu-title">
							<a href="{U_MSG_SET_VIEW}" aria-label="{L_MARK_AS_READ}" onclick="javascript:return Confirm_read_topics();"><i class="fa fa-fw fa-eraser" aria-hidden="true"></i> <span class="hidden-large-screens">{L_MARK_AS_READ}</span></a>
						</span>
					</li>
					# IF C_FORUM_CONNEXION #
						<li>
							<span class="cssmenu-title">
								<a href="${relative_url(UserUrlBuilder::disconnect())}" aria-label="{L_DISCONNECT}"><i class="fa fa-fw fa-sign-out-alt" aria-hidden="true"></i> <span class="hidden-large-screens">{L_DISCONNECT}</span></a>
							</span>
						</li>
					# ENDIF #
				</ul>
			</nav>
		# ELSE #
			# IF C_FORUM_CONNEXION #
				<nav class="cssmenu cssmenu-group float-right" id="cssmenu-sign-in-bottom-link">
					<ul>
						<li>
							<span class="cssmenu-title">
								<a href="${relative_url(UserUrlBuilder::connect())}" aria-label="{L_CONNECT}"><i class="fa fa-fw fa-sign-in-alt" aria-hidden="true"></i> <span class="hidden-large-screens">{L_CONNECT}</span></a>
							</span>
						</li>
						<li>
							<span class="cssmenu-title">
								<a href="${relative_url(UserUrlBuilder::registration())}" aria-label="{L_REGISTER}"><i class="fa fa-fw fa-ticket-alt" aria-hidden="true"></i> <span class="hidden-large-screens">{L_REGISTER}</span></a>
							</span>
						</li>
					</ul>
				</nav>
			# ENDIF #
		# ENDIF #

		<div class="spacer"></div>
	</div>
	<script>
		jQuery("#cssmenu-forum-bottom-link").menumaker({ title: " ${LangLoader::get_message('forum.links', 'common', 'forum')} ", format: "multitoggle", breakpoint: 768, menu_static: false });
		# IF C_FORUM_CONNEXION #jQuery("#cssmenu-sign-in-bottom-link").menumaker({ title: " ${LangLoader::get_message('forum.links', 'common', 'forum')} ", format: "multitoggle", breakpoint: 768, menu_static: false });# ENDIF #
	</script>


	<div class="forum-online">
		# IF USERS_ONLINE #
			<span class="float-left">
				{TOTAL_ONLINE} {L_USER} {L_ONLINE} : {ADMIN} {L_ADMIN}, {MODO} {L_MODO}, {MEMBER} {L_MEMBER} {L_AND} {GUEST} {L_GUEST}
				<span class="spacer"></span>
				{L_USER} {L_ONLINE} : # IF C_NO_USER_ONLINE #<em>${LangLoader::get_message('no_member_online', 'main')}</em># ELSE #{USERS_ONLINE}# ENDIF #
			</span>

			<div class="forum-online-select-cat">
				# IF SELECT_CAT #
					<form action="{U_CHANGE_CAT}" method="post">
						<div>
							<select name="change_cat" onchange="if (this.options[this.selectedIndex].text.substring(0, 3) == '-- ') document.location = '{U_ONCHANGE_CAT}'; else document.location = '{U_ONCHANGE}';" class="forum-online-select">
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
							<button type="submit" class="button submit" value="true" name="valid">{L_GO}</button>
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
				<span class="float-right forum-stats">
					<a href="{PATH_TO_ROOT}/forum/stats.php"><i class="fa fa-fw fa-chart-bar" aria-hidden="true"></i> {L_STATS}</a>
				</span>
				<div class="spacer"></div>
			</div>
		# ENDIF #

		# IF C_AUTH_POST #
			<div class="forum-links forum-message-options">
				<nav id="cssmenu-forum-action" class="cssmenu cssmenu-group">
					<ul>
						# IF C_DISPLAY_MSG #
							<li id="forum_change_statut">
								<a class="cssmenu-title" href="#" onclick="XMLHttpRequest_change_statut(); return false;">
									<span id="forum_change_img"># IF C_ICON_DISPLAY_MSG #<i class="fa fa-fw fa-{ICON_DISPLAY_MSG}" aria-hidden="true"></i># ENDIF #</span>
									<span id="forum_change_msg">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</span>
								</a>
							</li>
						# ENDIF #
						<li id="forum_modo_alert">
							<a href="{PATH_TO_ROOT}/forum/alert{U_ALERT}" class="cssmenu-title"><i class="fa fa-fw fa-exclamation-triangle warning" aria-hidden="true"></i><span>{L_ALERT}</span></a>
						</li>
						<li id="forum_track">
							<a class="cssmenu-title" href="#" onclick="XMLHttpRequest_track(); return false;">
								<span id="forum_track_img"><i class="fa fa-fw fa-{ICON_TRACK}" aria-hidden="true"></i></span>
								<span id="forum_track_msg">{L_TRACK_DEFAULT}</span>
							</a>
						</li>
						<li id="forum_track_pm">
							<a class="cssmenu-title" href="#" onclick="XMLHttpRequest_track_pm(); return false;">
								<span id="forum_track_pm_img"><i class="fa fa-fw fa-{ICON_SUBSCRIBE_PM}" aria-hidden="true"></i></span>
								<span id="forum_track_pm_msg">{L_SUBSCRIBE_PM_DEFAULT}</span>
							</a>
						</li>
						<li id="forum_track_mail">
							<a class="cssmenu-title" href="#" onclick="XMLHttpRequest_track_mail(); return false;">
								<span id="forum_track_mail_img"><i class="fa fa-fw fa-{ICON_SUBSCRIBE}" aria-hidden="true"></i></span>
								<span id="forum_track_mail_msg">{L_SUBSCRIBE_DEFAULT}</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
			<script>
				jQuery("#cssmenu-forum-action").menumaker({ title: "${LangLoader::get_message('forum.message.options', 'common', 'forum')}-b", format: "multitoggle", breakpoint: 768, menu_static: false });
			</script>
		#  ENDIF #
	</div>
</footer>
</section>
