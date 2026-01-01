		</div>
	</div>
	<footer id="forum-bottom">
		<div class="sub-section">
			<div class="content-container">
				<div class="forum-links">
					# IF C_USER_CONNECTED #
						<nav class="cssmenu cssmenu-group float-right" id="cssmenu-forum-bottom-link">
							<ul>
								<li>
									<span class="cssmenu-title">
										<a class="offload" href="index.php" aria-label="{@forum.index}"><i class="fa fa-fw fa-home" aria-hidden="true"></i> <span class="hidden-large-screens">{@forum.index}</span></a>
									</span>
								</li>
								<li>
									<span class="cssmenu-title">
										<a class="offload" href="{U_UNANSWERED_TOPICS}" aria-label="{@forum.unanswered.topics}"><i class="fa fa-fw fa-comment-slash" aria-hidden="true"></i> <span class="hidden-large-screens">{@forum.unanswered.topics}</span></a>
									</span>
								</li>
								<li>
									<span class="cssmenu-title">
										<a class="offload" href="{U_TRACKED_TOPICS}" aria-label="{@forum.tracked.topics}"><i class="fa fa-fw fa-heartbeat error" aria-hidden="true"></i> <span class="hidden-large-screens">{@forum.tracked.topics}</span></a>
									</span>
								</li>
								<li class="forum-index">
									<span class="cssmenu-title">
										<a class="offload" href="{U_LAST_MESSAGE_READ}" aria-label="{@forum.last.read.messages}"><i class="far fa-fw fa-clock" aria-hidden="true"></i> <span class="hidden-large-screens">{@forum.last.read.messages}</span></a>
									</span>
								</li>
								<li>
									<div class="cssmenu-title">
										<a class="offload" href="{U_UNREAD_MESSAGES}" aria-label="{@forum.unread.messages}"><i class="far fa-fw fa-file-alt" aria-hidden="true"></i> <span id="nbr_unread_messages_bottom">{UNREAD_MESSAGES_NUMBER}</span> <span class="hidden-large-screens"> {@forum.unread.messages}</span></a>
										<div class="forum-refresh">
											<div id="forum_block_forum_unread_bottom" style="display: none;" onmouseout="forum_hide_block('forum_unread_bottom', 0);"></div>
										</div>
										<a href="#" class="reload-unread" onclick="XMLHttpRequest_unread_topics('forum_unread_bottom');return false;" onmouseover="forum_hide_block('forum_unread_bottom', 1);" onmouseout="forum_hide_block('forum_unread_bottom', 0);" aria-label="{@forum.reload.unread.messages}"><i class="fa fa-fw fa-sync" id="refresh_forum_unread_bottom"></i><span class="sr-only">{@forum.reload.unread.messages}</span></a>
									</div>
								</li>
								<li>
									<span class="cssmenu-title">
										<a class="offload" href="{U_MARK_AS_READ}" aria-label="{@forum.mark.topics.as.read}" onclick="javascript:return Confirm_read_topics();"><i class="fa fa-fw fa-eraser" aria-hidden="true"></i> <span class="hidden-large-screens">{@forum.mark.topics.as.read}</span></a>
									</span>
								</li>
								# IF C_FORUM_CONNEXION #
									<li>
										<span class="cssmenu-title">
											<a class="offload" href="${relative_url(UserUrlBuilder::disconnect())}" aria-label="{@user.sign.out}"><i class="fa fa-fw fa-sign-out-alt" aria-hidden="true"></i> <span class="hidden-large-screens">{@user.sign.out}</span></a>
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
											<a class="offload" href="${relative_url(UserUrlBuilder::connect())}" aria-label="{@user.sign.in}"><i class="fa fa-fw fa-sign-in-alt" aria-hidden="true"></i> <span class="hidden-large-screens">{@user.sign.in}</span></a>
										</span>
									</li>
									<li>
										<span class="cssmenu-title">
											<a class="offload" href="${relative_url(UserUrlBuilder::registration())}" aria-label="{@user.sign.up}"><i class="fa fa-fw fa-ticket-alt" aria-hidden="true"></i> <span class="hidden-large-screens">{@user.sign.up}</span></a>
										</span>
									</li>
								</ul>
							</nav>
						# ENDIF #
					# ENDIF #

					<div class="spacer"></div>
				</div>
				<script>
					jQuery("#cssmenu-forum-bottom-link").menumaker({ title: " {@forum.links} ", format: "multitoggle", breakpoint: 768, menu_static: false });
					# IF C_FORUM_CONNEXION #jQuery("#cssmenu-sign-in-bottom-link").menumaker({ title: " {@forum.links} ", format: "multitoggle", breakpoint: 768, menu_static: false });# ENDIF #
				</script>

				<!-- # IF C_CONTROLS #
					<h6>{@forum.moderation.forum}</h6>
					<form action="action.php" class="grouped-inputs">
						<span class="grouped-element">{@forum.for.selection}</span>
						<select class="grouped-element" name="massive_action_type" placeholder="">
							<option value="change">{L_CHANGE_STATUT_TO}</option>
							<option value="changebis">{@forum.default.issue.status}</option>
							<option value="lock">{@forum.lock}</option>
							<option value="unlock">{@forum.unlock}</option>
							<option value="del">{@common.delete}</option>
						</select>
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" class="button submit small grouped-element" value="true" name="valid">{@form.submit}</button>
					</form>
				# ENDIF # -->

				<div class="forum-online">
					# IF ONLINE_USERS_LIST #
						<div class="flex-between flex-between-large">
							<div class="forum-online-users">
								{TOTAL_ONLINE} {L_USER} ${TextHelper::lcfirst(@user.online)} : {ADMINISTRATORS_NUMBER} {L_ADMIN}, {MODERATORS_NUMBER} {L_MODO}, {MEMBERS_NUMBER} {L_MEMBER} {L_AND} {GUESTS_NUMBER} {L_GUEST}
								<div class="spacer"></div>
								{L_USER} ${TextHelper::lcfirst(@user.online)} : # IF C_NO_USER_ONLINE #<em>{@user.no.user.online}</em># ELSE #{ONLINE_USERS_LIST}# ENDIF #
							</div>

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
							</div>
						</div>
					# ENDIF #

					# IF C_TOTAL_POST #
						<div>
							<span class="float-left">
								{@forum.messages.number}: <strong>{MESSAGES_NUMBER}</strong> # IF C_SEVERAL_MESSAGES #{@forum.messages}# ELSE #{@forum.message}# ENDIF # {L_DISTRIBUTED} / <strong>{TOPICS_NUMBER}</strong> # IF C_SEVERAL_TOPICS #{@forum.topics}# ELSE #{@forum.topic}# ENDIF #
							</span>
							<span class="float-right forum-stats">
								<a class="offload" href="{PATH_TO_ROOT}/forum/stats.php"><i class="fa fa-fw fa-chart-bar" aria-hidden="true"></i> {@forum.statistics}</a>
							</span>
							<div class="spacer"></div>
						</div>
					# ENDIF #

					# IF C_AUTH_POST #
						<div class="forum-links forum-message-options">
							<nav id="cssmenu-forum-action" class="cssmenu cssmenu-group">
								<ul>
									# IF C_DISPLAY_ISSUE_STATUS #
										<li id="forum_change_statut">
											<a class="cssmenu-title" href="#" onclick="XMLHttpRequest_change_statut(); return false;">
												<span id="forum_change_img"># IF C_DISPLAY_ISSUE_ICON #<i class="fa fa-fw fa-{ISSUE_ICON}" aria-hidden="true"></i># ENDIF #</span>
												<span id="forum_change_msg">{L_DEFAULT_ISSUE_STATUS}</span>
											</a>
										</li>
									# ENDIF #
									<li id="forum_modo_alert">
										<a href="{PATH_TO_ROOT}/forum/alert{U_ALERT}" class="cssmenu-title"><i class="fa fa-fw fa-exclamation-triangle warning" aria-hidden="true"></i><span>{@forum.report.topic}</span></a>
									</li>
									<li id="forum_track">
										<a class="cssmenu-title" href="#" onclick="XMLHttpRequest_track(); return false;">
											<span id="forum_track_img"><i class="fa fa-fw fa-{TRACK_ICON}" aria-hidden="true"></i></span>
											<span id="forum_track_msg">{L_DEFAULT_TRACK}</span>
										</a>
									</li>
									<li id="forum_track_pm">
										<a class="cssmenu-title" href="#" onclick="XMLHttpRequest_track_pm(); return false;">
											<span id="forum_track_pm_img"><i class="fa fa-fw fa-{PM_SUBSCRIPTION_ICON}" aria-hidden="true"></i></span>
											<span id="forum_track_pm_msg">{L_DEFAULT_PM_SUBSCRIPTION}</span>
										</a>
									</li>
									<li id="forum_track_mail">
										<a class="cssmenu-title" href="#" onclick="XMLHttpRequest_track_mail(); return false;">
											<span id="forum_track_mail_img"><i class="fa fa-fw fa-{EMAIL_SUBSCRIPTION_ICON}" aria-hidden="true"></i></span>
											<span id="forum_track_mail_msg">{L_DEFAULT_EMAIL_SUBSCRIPTION}</span>
										</a>
									</li>
								</ul>
							</nav>
						</div>
						<script>
							jQuery("#cssmenu-forum-action").menumaker({ title: "{@forum.topic.options}", format: "multitoggle", breakpoint: 768, menu_static: false });
						</script>
					#  ENDIF #
				</div>

			</div>
		</div>
	</footer>
</section>
