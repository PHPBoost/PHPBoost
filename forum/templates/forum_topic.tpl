# INCLUDE FORUM_TOP #

<script>
	function check_form_msg(){
		if(document.getElementById('content').value == "") {
			alert("{@warning.text}");
			return false;
		}
		return true;
	}
	function XMLHttpRequest_change_statut()
	{
		var idtopic = {IDTOPIC};
		if( document.getElementById('forum_change_img') )
			document.getElementById('forum_change_img').innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

		var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?msg_d=' + idtopic + '&token={TOKEN}');
		xhr_object.onreadystatechange = function()
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 )
			{
				if( document.getElementById('forum_change_img') )
					document.getElementById('forum_change_img').innerHTML = xhr_object.responseText == '1' ? '<i class="fa fa-times error"></i>' : '<i class="fa fa-check success"></i>';
				if( document.getElementById('forum_change_msg') )
					document.getElementById('forum_change_msg').innerHTML = xhr_object.responseText == '1' ? "{L_SOLVED_TOPIC}" : "{L_UNSOLVED_TOPIC}";
			}
		}
		xmlhttprequest_sender(xhr_object, null);
	}
	var is_track = {IS_TRACK};
	function XMLHttpRequest_track()
	{
		var idtopic = {IDTOPIC};
		if( document.getElementById('forum_track_img') )
			document.getElementById('forum_track_img').innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

		xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&' + (is_track ? 'ut' : 't') + '=' + idtopic);
		xhr_object.onreadystatechange = function()
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 )
			{
				if( document.getElementById('forum_track_img') )
					document.getElementById('forum_track_img').innerHTML = xhr_object.responseText == '1' ? '<i class="fa fa-heart-broken moderator"></i>' : '<i class="fa fa-heartbeat error"></i>';
				if( document.getElementById('forum_track_msg') )
					document.getElementById('forum_track_msg').innerHTML = xhr_object.responseText == '1' ? "{@forum.untrack.topic}" : "{@forum.track.topic}";
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
			document.getElementById('forum_track_pm_img').innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

		xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&' + (is_track_pm ? 'utp' : 'tp') + '=' + idtopic);
		xhr_object.onreadystatechange = function()
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 )
			{
				if( document.getElementById('forum_track_pm_img') )
					document.getElementById('forum_track_pm_img').innerHTML = xhr_object.responseText == '1' ? '<i class="fa fa-people-arrows error"></i>' : '<i class="fa fa-people-arrows success"></i>';
				if( document.getElementById('forum_track_pm_msg') )
					document.getElementById('forum_track_pm_msg').innerHTML = xhr_object.responseText == '1' ? "{@forum.untrack.topic.pm}" : "{@forum.track.topic.pm}";
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
			document.getElementById('forum_track_mail_img').innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

		xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&' + (is_track_mail ? 'utm' : 'tm') + '=' + idtopic);
		xhr_object.onreadystatechange = function()
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 )
			{
				if( document.getElementById('forum_track_mail_img') )
					document.getElementById('forum_track_mail_img').innerHTML = xhr_object.responseText == '1' ? '<i class="fa iboost fa-iboost-email error"></i>' : '<i class="fa iboost fa-iboost-email success"></i>';
				if( document.getElementById('forum_track_mail_msg') )
					document.getElementById('forum_track_mail_msg').innerHTML = xhr_object.responseText == '1' ? "{@forum.untrack.topic.email}" : "{@forum.track.topic.email}";
				is_track_mail = xhr_object.responseText == '1' ? true : false;
			}
		}
		xmlhttprequest_sender(xhr_object, null);
	}
	# IF C_FOCUS_CONTENT #
		jQuery(document).ready(function() {
			document.getElementById('content').focus();
		});
	# ENDIF #
</script>

<span id="go-top"></span>
<article id="article-forum-{ID}" class="forum-item forum-content category-{CATEGORY_ID}" itemscope="itemscope" itemtype="https://schema.org/Creativework">
	<header>
		<div class="controls align-right">
			<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('forum',ID))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CONTROLS #
				# IF C_FORUM_LOCK_TOPIC #
					<a href="action{U_TOPIC_LOCK}" data-confirmation="{@forum.alert.lock.topic}" aria-label="{@forum.lock}"><i class="fa fa-ban error" aria-hidden="true"></i></a>
				# ELSE #
					<a href="action{U_TOPIC_UNLOCK}" data-confirmation="{@forum.alert.unlock.topic}" aria-label="{@forum.unlock}"><i class="fa fa-ban success" aria-hidden="true"></i></a>
				# ENDIF #
				<a href="move{U_TOPIC_MOVE}" data-confirmation="{@forum.alert.move.topic}" aria-label="{@forum.move.topic}"><i class="fa fa-share" aria-hidden="true"></i></a>
			# ENDIF #
		</div>
		<h2 class="flex-between flex-between-large">
			{TITLE_T}
			<span id="display_msg_title" class="smaller">{DISPLAY_ISSUE_STATUS}</span>
		</h2>
		<div class="flex-between flex-between-large">
			<span class="desc-forum small"><em>{DESCRIPTION}</em></span>
			<span class="small">{U_CATEGORY}</span>
		</div>
		# IF C_PAGINATION ## INCLUDE PAGINATION ## ENDIF #
	</header>

	# IF C_POLL_EXISTS #
		<div class="content align-center">
			<form method="post" action="action{U_POLL_ACTION}">
				<table class="forum-poll-table">
					<thead>
						<tr>
							<th>{@forum.poll}: {QUESTION}</th>
						</tr>
					</thead>
					<tbody>
						# START poll_radio #
							<tr>
								<td>
									<label class="radio" for="{poll_radio.NAME}">
										<input id="{poll_radio.NAME}" type="{poll_radio.TYPE}" name="forumpoll" value="{poll_radio.NAME}">
										<sapn>{poll_radio.ANSWERS}</sapn>
									</label>
								</td>
							</tr>
						# END poll_radio #
						# START poll_checkbox #
							<tr>
								<td>
									<label class="checkbox" for="{poll_checkbox.NAME}">
										<input id="{poll_checkbox.NAME}" type="{poll_checkbox.TYPE}" name="{poll_checkbox.NAME}" value="{poll_checkbox.NAME}">
										<span>{poll_checkbox.ANSWERS}</span>
									</label>
								</td>
							</tr>
						# END poll_checkbox #
						# START poll_result #
							<tr>
								<td>
									{poll_result.ANSWERS}

									{poll_result.PERCENT}% - [{poll_result.VOTES_NUMBER} {poll_result.L_VOTES}]
									<div class="progressbar-container" role="progressbar" aria-valuenow="{poll_result.PERCENT}" aria-valuemin="0" aria-valuemax="100">
										<div class="progressbar-infos">{poll_result.PERCENT}%</div>
										<div class="progressbar" style="width:{poll_result.PERCENT}%"></div>
									</div>
								</td>
							</tr>
						# END poll_result #
					</tbody>
				</table>

				# IF C_POLL_QUESTION #
					<fieldset class="fieldset-submit">
						<legend>{@forum.poll.cast.vote}</legend>
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="valid_forum_poll" value="true" class="button submit">{@forum.poll.cast.vote}</button>
						<div class="spacer"></div>
						<a class="small offload" href="topic{U_POLL_RESULT}">{@forum.poll.results}</a>
					</fieldset>
				# ENDIF #
			</form>
		</div>
	# ENDIF #
	# START msg #
		<div id="d{msg.ID}" class="message-container cell-tile cell-modal modal-container" itemscope="itemscope" itemtype="https://schema.org/Comment">
			<span id="m{msg.ID}"></span>
			<div class="message-header-container">
				# IF msg.C_USER_AVATAR #<img class="message-user-avatar" src="{msg.U_USER_AVATAR}" alt="{@common.avatar}"># ENDIF #
				<div class="message-header-infos">
					<div class="message-user-container">
						<h3 class="message-user-name">
							# IF msg.C_FORUM_USER_LOGIN #
								<span class="{msg.FORUM_USER_LEVEL}" # IF msg.FORUM_USER_GROUP_COLOR # style="color:{msg.FORUM_USER_GROUP_COLOR}"# ENDIF #>
									{msg.FORUM_USER_LOGIN}
								</span>
								<span class="smaller" aria-label="{@common.see.profile.datas}" data-modal data-target="message-user-datas-{msg.ID}">
									<i class="far fa-eye" aria-hidden="true"></i>
								</span>
								<span class="sr-only"># IF C_USER_ONLINE #{@forum.connected.member}# ELSE #{@forum.not.connected.member}# ENDIF #</span>
							# ELSE #
								<span>{@user.guest}</span>
							# ENDIF #
						</h3>
						<div class="controls message-user-infos-preview">
							# IF msg.C_USER_GROUPS #
								# START msg.usergroups #
									<a href="{msg.usergroups.U_USERGROUP}" class="user-group small group-{msg.usergroups.USERGROUP_ID} offload"# IF msg.usergroups.C_USERGROUP_COLOR # style="color: {msg.usergroups.USERGROUP_COLOR}"# ENDIF #>{msg.usergroups.USERGROUP_NAME}</a>
								# END msg.usergroups #
							# ENDIF #
							# IF msg.C_USER_RANK #<span class="pinned {msg.FORUM_USER_LEVEL} small">{msg.USER_RANK}</span># ELSE #<span class="error">{@user.banned}</span># ENDIF #
							<span aria-label="# IF msg.C_USER_ONLINE #{@user.online}# ELSE #{@user.offline}# ENDIF #">
								<i class="fa # IF msg.C_USER_ONLINE #fa-user-check success# ELSE #fa-user-times error# ENDIF #" aria-hidden="true"></i>
							</span>
						</div>
					</div>
					<div class="message-infos">
						<div class="message-date small">
							<time datetime="{msg.TOPIC_DATE_FULL}" itemprop="datePublished">{@common.on.date} {msg.TOPIC_DATE_FULL}</time>
						</div>
						<div class="message-actions">
							<div class="message-actions-container-{msg.ID}">
								<a href="" class="message-actions-toggle-{msg.ID}" aria-label="{@forum.message.controls}">
									<i class="fa fa-ellipsis-v"></i>
								</a>
								<div class="message-actions-content-{msg.ID} controls">
									# IF C_AUTH_POST #<a class="offload" href="topic{msg.U_QUOTE}#go-bottom" aria-label="{@forum.quote.message}"><i class="fa fa-quote-right" aria-hidden="true"></i></a># ENDIF #
									# IF msg.C_FORUM_MSG_EDIT #<a class="offload" href="post{msg.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #

									# IF msg.C_AUTHORIZE_SELECTED #
										# IF msg.C_IS_SELECTED #
											<a href="action{msg.U_SET_MSG_AS_UNSELECTED}"><i class="fa fa-check-circle success" aria-hidden="true" aria-label="{@forum.set.as.unselected}"></i></a>
										# ELSE #
											<a href="action{msg.U_SET_MSG_AS_SELECTED}"><i class="fa fa-check" aria-hidden="true" aria-label="{@forum.set.as.selected}"></i></a>
										# END IF #
									# END IF #

									# IF msg.C_DELETE #
										<a href="action{msg.U_DELETE}" aria-label="{@common.delete}" class="delete-message" data-confirmation="# IF msg.C_DELETE_MESSAGE #{@forum.alert.delete.message}# ELSE #{@forum.alert.delete.topic}# ENDIF #"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
									# ENDIF #

									# IF msg.C_CUT # <a href="move{msg.U_CUT_TOPIC}" aria-label="{@forum.cut.topic}" data-confirmation="{@forum.alert.cut.topic}"><i class="fa fa-cut" aria-hidden="true"></i></a> # ENDIF #

									<a aria-label="{@common.scroll.to.top}" href="{U_TITLE_T}#go-top" onclick="jQuery('html, body').animate({scrollTop:jQuery('#go-top').offset().top}, 'slow'); return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
									<a aria-label="{@common.scroll.to.bottom}" href="{U_TITLE_T}#go-bottom" onclick="jQuery('html, body').animate({scrollTop:jQuery('#go-bottom').offset().top}, 'slow'); return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
									<a href="{U_SITE}{msg.U_VARS_ANCHOR}#m{msg.ID}" class="copy-link-to-clipboard" aria-label="{@common.copy.link.to.clipboard}">\#{msg.ID}</i></a>
								</div>
							</div>
							<script>
								jQuery('.message-actions-toggle-{msg.ID}').opensubmenu({
									osmTarget: '.message-actions-container-{msg.ID}',
									osmCloseExcept : '.message-actions-content-{msg.ID} *'
								});
							</script>
						</div>
					</div>
				</div>
			</div>
			<div id="message-user-datas-{msg.ID}" class="modal modal-animation">
				<div class="close-modal" aria-label="{@common.close}"></div>
				<div class="content-panel cell">
					<div class="align-right"><a href="#" class="error hide-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></a></div>
					<div class="cell-list">
						<ul>
							<li class="li-stretch">
								# IF msg.C_USER_RANK #<span class="pinned {msg.FORUM_USER_LEVEL}">{msg.USER_RANK}</span># ELSE #<span class="error">{@user.banned}</span># ENDIF #
								# IF msg.C_USER_RANK_ICON #<img class="valign-middle" src="{msg.USER_RANK_ICON}" alt="{@user.rank}" /># ENDIF #
							</li>
							<li class="li-stretch">
								<span>{@common.see.profile}</span>
								<a href="{msg.U_FORUM_USER_PROFILE}" class="msg-link-pseudo {msg.FORUM_USER_LEVEL} offload"# IF msg.FORUM_USER_GROUP_COLOR # style="color:{msg.FORUM_USER_GROUP_COLOR}"# ENDIF #><i class="fa fa-link fa-fw"></i> {msg.FORUM_USER_LOGIN}</a>
							</li>
							<li class="li-stretch">
								<span>{@forum.registred.on} :</span>
								<span>{msg.USER_REGISTERED_DATE}</span>
							</li>
							# IF IS_USER_CONNECTED #
								# IF msg.C_USER_HAS_MESSAGE #
									<li class="li-stretch">
										<span>{@forum.messages} :</span>
										<a href="{msg.U_USER_MEMBERMSG}" class="button submit smaller offload" aria-label="{@forum.show.member.messages}">{msg.USER_MSG}</a>
									</li>
								# ENDIF #
							# ENDIF #
							# IF msg.C_USER_PM #
								<li class="li-stretch">
									<span>{@user.pm} :</span>
									<a href="{msg.U_USER_PM}" class="button submit smaller user-pm offload" aria-label="{@user.contact.pm}"><i class="fa fa-people-arrows fa-fw"></i></a>
								</li>
							# ENDIF #
							# IF msg.C_USER_EMAIL #
								<li class="li-stretch">
									<span>{@user.email}</span>
									<a href="{msg.U_USER_MAIL}" class="button submit smaller user-mail offload" aria-label="{@user.contact.email}"><i class="fa iboost fa-iboost-email fa-fw"></i></a>
								</li>
							# ENDIF #
							# START msg.ext_fields #
								<li>
									{msg.ext_fields.BUTTON}
								</li>
							# END msg.ext_fields #
							# IF msg.C_USER_GROUPS #
								<li>
									<span>{@user.groups} :</span>
								</li>
								# START msg.usergroups #
									<li# IF msg.usergroups.C_IMG_USERGROUP # class="li-stretch"# ENDIF #>
										<a href="{msg.usergroups.U_USERGROUP}" class="user-group group-{msg.usergroups.USERGROUP_ID} offload"# IF msg.usergroups.C_USERGROUP_COLOR # style="color: {msg.usergroups.USERGROUP_COLOR}"# ENDIF #>{msg.usergroups.USERGROUP_NAME}</a>
										# IF msg.usergroups.C_IMG_USERGROUP #
											<a href="{msg.usergroups.U_USERGROUP}" class="user-group user-group-img group-{msg.usergroups.USERGROUP_ID} offload"# IF msg.usergroups.C_USERGROUP_COLOR # style="color: {msg.usergroups.USERGROUP_COLOR}"# ENDIF #><img src="{PATH_TO_ROOT}/images/group/{msg.usergroups.U_IMG_USERGROUP}" alt="{msg.usergroups.USERGROUP_NAME}" /></a>
										# ENDIF #
									</li>
								# END msg.usergroups #
							# ENDIF #
							# IF msg.C_CONTROLS #
								<li class="li-stretch">
									<span>{@user.punishments} : {msg.USER_WARNING}%</span>
									<span class="controls">
										<a class="offload" href="moderation_forum{msg.U_FORUM_WARNING}" aria-label="{@user.warnings.management}"><i class="fa fa-exclamation-triangle warning" aria-hidden="true"></i></a>
										<a class="offload" href="moderation_forum{msg.U_FORUM_PUNISHEMENT}" aria-label="{@user.punishments.management}"><i class="fa fa-user-lock" aria-hidden="true"></i></a>
									</span>
								</li>
							# ENDIF #
						</ul>
					</div>
				</div>
			</div>
			<div class="message-content# IF msg.C_IS_SELECTED # selected-message# ENDIF #">
				<div class="float-right badges">
					# START msg.additional_informations #
						<span>{msg.additional_informations.VALUE}</span>
					# END msg.additional_informations #
				</div>

				# IF msg.C_QUOTE_LAST_MESSAGE # <p class="message-helper bgc notice">{@forum.quote.last.message}</p> # ENDIF #

				{msg.FORUM_MSG_CONTENT}

				# IF msg.C_FORUM_USER_EDITOR #
					<p class="message-edition">
						{@forum.edited.by}
						# IF msg.C_FORUM_USER_EDITOR_LOGIN #
							<a class="offload" href="{msg.U_FORUM_USER_EDITOR_PROFILE}">{msg.FORUM_USER_EDITOR_LOGIN}</a>
						# ELSE #
							{@user.guest}
						# ENDIF #
						{@common.on.date} {msg.TOPIC_EDIT_DATE_FULL}
					</p>
				# ENDIF #
			</div>
			# IF msg.C_USER_SIGN #<div class="message-footer-container">{msg.USER_SIGN}</div># ENDIF #
		</div>
	# END msg #

	<div class="align-right">
		# IF C_PAGINATION ## INCLUDE PAGINATION ## ENDIF #
	</div>
	<footer class="footer-forum flex-between">
		<div class="small">{U_CATEGORY}</div>
		<div class="controls">
			<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('forum',ID))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CONTROLS #
				# IF C_FORUM_LOCK_TOPIC #
					<a href="action{U_TOPIC_LOCK}" aria-label="{@forum.lock}" data-confirmation="{@forum.alert.lock.topic}"><i class="fa fa-ban error" aria-hidden="true"></i></a>
				# ELSE #
					<a href="action{U_TOPIC_UNLOCK}" aria-label="{@forum.unlock}" data-confirmation="{@forum.alert.unlock.topic}"><i class="fa fa-ban success" aria-hidden="true"></i></a>
				# ENDIF #
				<a href="move{U_TOPIC_MOVE}" aria-label="{@forum.move.topic}" data-confirmation="{@forum.alert.move.topic}"><i class="fa fa-share" aria-hidden="true"></i></a>
			# ENDIF #
		</div>
	</footer>

	<span id="go-bottom"></span>
	# IF C_AUTH_POST #
		<div class="forum-post-form">
			<form action="post{U_FORUM_ACTION_POST}" method="post" onsubmit="return check_form_msg();">
				<div class="form-element form-element-textarea">
					<label for="content" class="text-strong bigger">{@forum.reply}</label>
					<div class="form-field form-field-textarea bbcode-sidebar">
						{KERNEL_EDITOR}
						<textarea id="content" name="content" rows="15" cols="40">{CONTENT}</textarea>
					</div>
					<button type="button" class="button preview-button" onclick="XMLHttpRequest_preview();">{@form.preview}</button>
				</div>

				<fieldset class="fieldset-submit">
					<legend>{@form.submit}</legend>
					<button type="submit" class="button submit" name="valid" value="true">{@form.submit}</button>
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
				</fieldset>
			</form>
		</div>
	# ENDIF #

	# IF C_ERROR_AUTH_WRITE #
		<div class="error-auth-write-response">{@forum.reply}</div>
		<div class="forum-text-column error-auth-write">
			{L_ERROR_AUTH_WRITE}
		</div>
	# ENDIF #
</article>

# INCLUDE FORUM_BOTTOM #
