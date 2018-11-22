
<footer id="forum-bottom">
	<div class="forum-links">
		# IF C_USER_CONNECTED #
		<nav class="cssmenu cssmenu-group float-right" id="cssmenu-forum-bottom-link">
			<ul>
				<li>
					<span class="cssmenu-title">
						<a href="index.php" aria-label="{L_FORUM_INDEX}"><i class="fa fa-home" aria-hidden="true" title="{L_FORUM_INDEX}"></i> <span class="hidden-large-screens">{L_FORUM_INDEX}</span></a>
					</span>
				</li>
				<li>
					<span class="cssmenu-title">
						<a href="{U_SHOW_MY_MSG}" aria-label="{L_SHOW_MY_MSG}"><i class="fa fa-showmymsg" aria-hidden="true" title="{L_SHOW_MY_MSG}"></i> <span class="hidden-large-screens">{L_SHOW_MY_MSG}</span></a>
					</span>
				</li>
				<li>
					<span class="cssmenu-title">
						<a href="{U_TOPIC_TRACK}" aria-label="{L_SHOW_TOPIC_TRACK}"><i class="fa fa-msg-track" aria-hidden="true" title="{L_SHOW_TOPIC_TRACK}"></i> <span class="hidden-large-screens">{L_SHOW_TOPIC_TRACK}</span></a>
					</span>
				</li>
				<li class="forum-index">
					<span class="cssmenu-title">
						<a href="{U_LAST_MSG_READ}" aria-label="{L_SHOW_LAST_READ}"><i class="fa fa-lastview" aria-hidden="true" title="{L_SHOW_LAST_READ}"></i> <span class="hidden-large-screens">{L_SHOW_LAST_READ}</span></a>
					</span>
				</li>
				<li>
					<span class="cssmenu-title">
						<a href="{U_MSG_NOT_READ}" aria-label="{L_SHOW_NOT_READS}"><i class="fa fa-notread" aria-hidden="true" title="{L_SHOW_NOT_READS}"></i> <span class="hidden-large-screens">{L_SHOW_NOT_READS}</span> <span id="nbr_unread_topics_bottom">{NBR_MSG_NOT_READ}</span></a>
						<div class="forum-refresh">
							<div id="forum_block_forum_unread_bottom" style="display: none;"></div>
						</div>
						<a href="" onclick="XMLHttpRequest_unread_topics('forum_unread_bottom');return false;" onmouseover="forum_hide_block('forum_unread_bottom', 1);" onmouseout="forum_hide_block('forum_unread_bottom', 0);"><i class="fa fa-refresh" id="refresh_forum_unread_bottom"></i><span class="sr-only">${LangLoader::get_message('forum.links', 'common', 'forum')}</span></a>
					</span>
				</li>
				<li>
					<span class="cssmenu-title">
						<a href="{U_MSG_SET_VIEW}" aria-label="{L_MARK_AS_READ}" onclick="javascript:return Confirm_read_topics();"><i class="fa fa-eraser" aria-hidden="true" title="{L_MARK_AS_READ}"></i> <span class="hidden-large-screens">{L_MARK_AS_READ}</span></a>
					</span>
				</li>
				# IF C_FORUM_CONNEXION #
				<li>
					<span class="cssmenu-title">
						<a href="${relative_url(UserUrlBuilder::disconnect())}" aria-label="{L_DISCONNECT}"><i class="fa fa-sign-out" aria-hidden="true" title="{L_DISCONNECT}"></i> <span class="hidden-large-screens">{L_DISCONNECT}</span></a>
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
						<a href="${relative_url(UserUrlBuilder::connect())}" aria-label="{L_CONNECT}"><i class="fa fa-sign-in" aria-hidden="true" title="{L_CONNECT}"></i> <span class="hidden-large-screens">{L_CONNECT}</span></a>
					</span>
				</li>
				<li>
					<span class="cssmenu-title">
						<a href="${relative_url(UserUrlBuilder::registration())}" aria-label="{L_REGISTER}"><i class="fa fa-ticket" aria-hidden="true" title="{L_REGISTER}"></i> <span class="hidden-large-screens">{L_REGISTER}</span></a>
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
		# IF C_FORUM_CONNEXION #jQuery("#cssmenu-sign-in-bottom-link").menumaker({ title: " ${LangLoader::get_message('forum.links', 'common', 'forum')} ", format: "multitoggle", breakpoint: 768, menu_static: false });# ENDIF #
		-->
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
			<span class="float-right forum-stats">
				<a href="{PATH_TO_ROOT}/forum/stats.php" title="{L_STATS}"><i class="fa fa-bar-chart-o" aria-hidden="true"></i> {L_STATS}</a>
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
							<a href="" onclick="XMLHttpRequest_change_statut(); return false;" id="forum_change_img"># IF C_ICON_DISPLAY_MSG #<i class="fa fa-{ICON_DISPLAY_MSG}" aria-hidden="true"></i># ENDIF #</a> <a href="" onclick="XMLHttpRequest_change_statut(); return false;"><span id="forum_change_msg">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</span></a>
						</span>
					</li>
					# ENDIF #
					<li>
						<span class="cssmenu-title">
							<a href="{PATH_TO_ROOT}/forum/alert{U_ALERT}"><i class="fa fa-warning" aria-hidden="true" title="{L_ALERT}"></i></a> <a href="{PATH_TO_ROOT}/forum/alert{U_ALERT}" title="{L_ALERT}">{L_ALERT}</a>
						</span>
					</li>
					<li>
						<span class="cssmenu-title" id="forum_track">
							<a href="" onclick="XMLHttpRequest_track(); return false;" id="forum_track_img"><i class="fa fa-{ICON_TRACK}" aria-hidden="true"></i></a> <a href="" onclick="XMLHttpRequest_track(); return false;"><span id="forum_track_msg">{L_TRACK_DEFAULT}</span></a>
						</span>
					</li>
					<li>
						<span class="cssmenu-title" id="forum_track_pm">
							<a href="" onclick="XMLHttpRequest_track_pm(); return false;" id="forum_track_pm_img"><i class="fa fa-{ICON_SUBSCRIBE_PM}" aria-hidden="true"></i></a> <a href="" onclick="XMLHttpRequest_track_pm(); return false;"><span id="forum_track_pm_msg">{L_SUBSCRIBE_PM_DEFAULT}</span></a>
						</span>
					</li>
					<li>
						<span class="cssmenu-title" id="forum_track_mail">
							<a href="" onclick="XMLHttpRequest_track_mail(); return false;" id="forum_track_mail_img"><i class="fa fa-{ICON_SUBSCRIBE}" aria-hidden="true"></i></a> <a href="" onclick="XMLHttpRequest_track_mail(); return false;"><span id="forum_track_mail_msg">{L_SUBSCRIBE_DEFAULT}</span></a>
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
