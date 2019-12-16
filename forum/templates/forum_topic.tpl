<span id="go-top"></span>

# INCLUDE forum_top #

<script>
	function check_form_msg(){
		if(document.getElementById('contents').value == "") {
			alert("{L_REQUIRE_MESSAGE}");
			return false;
	    }
		return true;
	}
	function XMLHttpRequest_del(idmsg)
	{
		var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&del=1&idm=' + idmsg);
		xhr_object.onreadystatechange = function()
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' )
			{
				if( document.getElementById('d' + idmsg) )
					document.getElementById('d' + idmsg).style.display = 'none';
			}
		}
		xmlhttprequest_sender(xhr_object, null);
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
			document.getElementById('forum_track_img').innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

		xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&' + (is_track ? 'ut' : 't') + '=' + idtopic);
		xhr_object.onreadystatechange = function()
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 )
			{
				if( document.getElementById('forum_track_img') )
					document.getElementById('forum_track_img').innerHTML = xhr_object.responseText == '1' ? '<i class="fa fa-heart-broken"></i>' : '<i class="fa fa-heartbeat error"></i>';
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
			document.getElementById('forum_track_pm_img').innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

		xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&' + (is_track_pm ? 'utp' : 'tp') + '=' + idtopic);
		xhr_object.onreadystatechange = function()
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 )
			{
				if( document.getElementById('forum_track_pm_img') )
					document.getElementById('forum_track_pm_img').innerHTML = xhr_object.responseText == '1' ? '<i class="fa fa-envelope error"></i>' : '<i class="fa fa-envelope-open-text success"></i>';
				if( document.getElementById('forum_track_pm_msg') )
					document.getElementById('forum_track_pm_msg').innerHTML = xhr_object.responseText == '1' ? "{L_UNSUBSCRIBE_PM}" : "{L_SUBSCRIBE_PM}";
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
					document.getElementById('forum_track_mail_img').innerHTML = xhr_object.responseText == '1' ? '<i class="fa fa-at error"></i>' : '<i class="fa fa-at success"></i>';
				if( document.getElementById('forum_track_mail_msg') )
					document.getElementById('forum_track_mail_msg').innerHTML = xhr_object.responseText == '1' ? "{L_UNSUBSCRIBE}" : "{L_SUBSCRIBE}";
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

	# IF C_FOCUS_CONTENT #
	jQuery(document).ready(function() {
		document.getElementById('contents').focus();
	});
	# ENDIF #
</script>

<article id="article-forum-{ID}" class="forum-contents" itemscope="itemscope" itemtype="http://schema.org/Creativework">
	<header>
		# IF C_PAGINATION #<span class="float-left"># INCLUDE PAGINATION #</span># ENDIF #
		# IF C_FORUM_MODERATOR #
			<div class="controls align-right">
				<a href="${relative_url(SyndicationUrlBuilder::rss('forum',ID))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
				# IF C_FORUM_LOCK_TOPIC #
					<a href="action{U_TOPIC_LOCK}" data-confirmation="{L_ALERT_LOCK_TOPIC}" aria-label="{L_TOPIC_LOCK}"><i class="fa fa-ban" aria-hidden="true"></i></a>
				# ELSE #
					<a href="action{U_TOPIC_UNLOCK}" data-confirmation="{L_ALERT_UNLOCK_TOPIC}" aria-label="{L_TOPIC_LOCK}"><i class="fa fa-ban" aria-hidden="true"></i></a>
				# ENDIF #
				<a href="move{U_TOPIC_MOVE}" data-confirmation="{L_ALERT_MOVE_TOPIC}" aria-label="{L_TOPIC_MOVE}"><i class="fa fa-share" aria-hidden="true"></i></a>
			</div>
		# ENDIF #
		<h2>
			{U_FORUM_CAT} <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a itemscope="name" href="{U_TITLE_T}"><span id="display_msg_title">{DISPLAY_MSG}</span>{TITLE_T}</a> <span class="desc-forum"><em>{DESC}</em></span>
		</h2>
	</header>

	# IF C_POLL_EXIST #
		<div class="content align-center">
			<form method="post" action="action{U_POLL_ACTION}">
				<table class="forum-poll-table">
					<thead>
						<tr>
							<th>{L_POLL}: {QUESTION}</th>
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

									{poll_result.PERCENT}% - [{poll_result.NBRVOTE} {L_VOTE}]
									<div class="progressbar-container" aria-label="{poll_result.PERCENT}%">
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
						<legend>{L_VOTE}</legend>
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="valid_forum_poll" value="true" class="button submit">{L_VOTE}</button>
						<div class="spacer"></div>
						<a class="small" href="topic{U_POLL_RESULT}">{L_RESULT}</a>
					</fieldset>
				# ENDIF #
			</form>
		</div>
	# ENDIF #
	# START msg #
		<div id="d{msg.ID}" class="message-container" itemscope="itemscope" itemtype="http://schema.org/Comment">
			<span id="m{msg.ID}"></span>
	        <div class="message-header-container">
				<img class="message-user-avatar" src="# IF msg.C_USER_AVATAR #{msg.U_USER_AVATAR}# ELSE #{msg.U_DEFAULT_AVATAR}# ENDIF #" alt="${LangLoader::get_message('avatar', 'user-common')}">
	            <div class="message-header-infos">
		            <div class="message-user-infos hidden-small-screens">
						<div>
							<i class="fa # IF msg.C_USER_ONLINE #fa-user-check success# ELSE #fa-user-times error# ENDIF #" aria-hidden="true"></i>
							# IF msg.C_IS_USER #${LangLoader::get_message('registered_on', 'main')} : {msg.USER_REGISTERED_DATE_FULL}# ENDIF #
							# IF msg.C_USER_MSG #
								| <a href="{msg.U_USER_MSG}">${LangLoader::get_message('message_s', 'main')}</a>: {msg.USER_MSG}
							# ELSE #
								| # IF msg.C_IS_USER # <a href="{msg.U_USER_MEMBERMG}">${LangLoader::get_message('message', 'main')}</a> : 0# ELSE #${LangLoader::get_message('message', 'main')} : 0# ENDIF #
							# ENDIF #
						</div>
		                <div class="message-user-links">
							# IF msg.C_USER_PM #
								<a href="{msg.U_USER_PM}" class="button submit smaller user-pm">${LangLoader::get_message('pm', 'main')}</a>
							# ENDIF #
							# IF msg.C_USER_MAIL #
								<a href="{msg.U_USER_MAIL}" class="button submit smaller user-mail">${LangLoader::get_message('mail', 'main')}</a>
							# ENDIF #
							# START msg.ext_fields #
								{msg.ext_fields.BUTTON}
							# END msg.ext_fields #
						</div>
					</div>
	                <div class="message-user">
	                    <h3 class="message-user-pseudo">
							# IF msg.C_FORUM_USER_LOGIN #
								<a class="msg-link-pseudo {msg.FORUM_USER_LEVEL}" href="{msg.U_FORUM_USER_PROFILE}"# IF msg.FORUM_USER_GROUP_COLOR # style="color:{msg.FORUM_USER_GROUP_COLOR}"# ENDIF #>
									{msg.FORUM_USER_LOGIN}
								</a>
								<span class="sr-only"># IF C_USER_ONLINE #${LangLoader::get_message('forum.connected.mbr.yes', 'common', 'forum')}# ELSE #${LangLoader::get_message('forum.connected.mbr.no', 'common', 'forum')}# ENDIF #</span>
							# ELSE #
								<em>{L_GUEST}</em>
							# ENDIF #
	                    </h3>
	                    <div class="message-actions">
							# IF C_AUTH_POST #<a href="topic{msg.U_VARS_QUOTE}#go-bottom" aria-label="{L_QUOTE}"><i class="fa fa-quote-right" aria-hidden="true"></i></a># ENDIF #
							# IF msg.C_FORUM_MSG_EDIT #<a href="post{msg.U_FORUM_MSG_EDIT}" aria-label="{L_EDIT}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF #

							# IF msg.C_FORUM_MSG_DEL #
								# IF msg.C_FORUM_MSG_DEL_MSG #
									<a href="action{msg.U_FORUM_MSG_DEL}" aria-label="{L_DELETE}" id="dimgnojs{msg.ID}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
									<a onclick="del_msg('{msg.ID}');" id="dimg{msg.ID}" aria-label="{L_DELETE}" class="delete-message"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
									<script>
										document.getElementById('dimgnojs{msg.ID}').style.display = 'none';
										document.getElementById('dimg{msg.ID}').style.display = 'inline';
									</script>
								# ELSE #
									<a href="action{msg.U_FORUM_MSG_DEL}" aria-label="{L_DELETE}" data-confirmation="{L_ALERT_DELETE_TOPIC}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
								# ENDIF #
							# ENDIF #

							# IF msg.C_FORUM_MSG_CUT # <a href="move{msg.U_FORUM_MSG_CUT}" aria-label="{L_CUT_TOPIC}" data-confirmation="{L_ALERT_CUT_TOPIC}"><i class="fa fa-cut" aria-hidden="true"></i></a> # ENDIF #

							<a aria-label="${LangLoader::get_message('scroll-to.top', 'user-common')}" href="{U_TITLE_T}#go-top" onclick="jQuery('html, body').animate({scrollTop:jQuery('#go-top').offset().top}, 'slow'); return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
							<a aria-label="${LangLoader::get_message('scroll-to.bottom', 'user-common')}" href="{U_TITLE_T}#go-bottom" onclick="jQuery('html, body').animate({scrollTop:jQuery('#go-bottom').offset().top}, 'slow'); return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
	                    </div>
	                </div>
	                <div class="message-infos">
	                    <time datetime="{msg.TOPIC_DATE_FULL}" itemprop="datePublished">${LangLoader::get_message('on', 'main')} {msg.TOPIC_DATE_FULL}</time>
	                    <a href="topic{msg.U_VARS_ANCRE}#m{msg.ID}" class="hidden-small-screens" aria-label="${LangLoader::get_message('link.to.anchor', 'comments-common')}">\#{msg.ID}</i></a>
	                </div>
	            </div>
	        </div>
	        <div class="message-content" >
				# IF msg.L_FORUM_QUOTE_LAST_MSG # <p class="text-strong">{msg.L_FORUM_QUOTE_LAST_MSG}</p> # ENDIF #

				{msg.FORUM_MSG_CONTENTS}

				# IF msg.C_FORUM_USER_EDITOR #
					<p class="user-editor">
						{L_EDIT_BY}
						# IF msg.C_FORUM_USER_EDITOR_LOGIN #
							<a href="{msg.U_FORUM_USER_EDITOR_PROFILE}">{msg.FORUM_USER_EDITOR_LOGIN}</a>
						# ELSE #
							{L_GUEST}
						# ENDIF #
						{L_ON} {msg.TOPIC_EDIT_DATE_FULL}
					</p>
				# ENDIF #
	        </div>
			<div class="message-user-sign# IF msg.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
				# IF msg.C_USER_SIGN #{msg.USER_SIGN}# ENDIF #
			</div>
	        <div class="message-footer-container# IF msg.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
	            <div class="message-user-assoc">
	                <div class="message-group-level">
						# IF msg.C_USER_GROUPS #
							# START msg.usergroups #
								# IF msg.usergroups.C_IMG_USERGROUP #
									<a href="{msg.usergroups.U_USERGROUP}" class="user-group user-group-img group-{msg.usergroups.USERGROUP_ID} "# IF msg.usergroups.C_USERGROUP_COLOR # style="color: {msg.usergroups.USERGROUP_COLOR}"# ENDIF #><img src="{PATH_TO_ROOT}/images/group/{msg.usergroups.U_IMG_USERGROUP}" alt="{msg.usergroups.USERGROUP_NAME}" /></a>
								# ELSE #
									{msg.usergroups.L_USER_GROUP} : <a href="{msg.usergroups.U_USERGROUP}" class="user-group group-{msg.usergroups.USERGROUP_ID}"# IF msg.usergroups.C_USERGROUP_COLOR # style="color: {msg.usergroups.USERGROUP_COLOR}"# ENDIF #>{msg.usergroups.USERGROUP_NAME}</a>
								# ENDIF #
							# END msg.usergroups #
						# ENDIF #
					</div>
					<div class="message-user-rank">
						# IF msg.C_USER_RANK #<span class="pinned {msg.FORUM_USER_LEVEL}">{msg.USER_RANK}</span># ELSE #${LangLoader::get_message('banned', 'user-common')}# ENDIF #
						# IF msg.C_USER_IMG_ASSOC #<img class="valign-middle" src="{msg.USER_IMG_ASSOC}" alt="${LangLoader::get_message('rank', 'main')}" /># ENDIF #
					</div>
	            </div>
	            <div class="message-user-management">
		            <div class="message-moderation-level">
						# IF msg.C_FORUM_MODERATOR #
							{msg.USER_WARNING}%
							<a href="moderation_forum{msg.U_FORUM_WARNING}" aria-label="{L_WARNING_MANAGEMENT}"><i class="fa fa-exclamation-triangle warning" aria-hidden="true"></i></a>
							<a href="moderation_forum{msg.U_FORUM_PUNISHEMENT}" aria-label="{L_PUNISHMENT_MANAGEMENT}"><i class="fa fa-user-lock" aria-hidden="true"></i></a>
						# ENDIF #
					</div>
	            </div>
	        </div>
		</div>
	# END msg #

	<div class="align-right">
		# IF C_PAGINATION ## INCLUDE PAGINATION ## ENDIF #
	</div>
	<footer class="footer-forum flex-between">
		<div>{U_FORUM_CAT} <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a itemscope="name" href="{U_TITLE_T}"><span id="display_msg_title">{DISPLAY_MSG}</span>{TITLE_T}</a> <span class="desc-forum"><em>{DESC}</em></span></div>
		<div class="controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('forum',ID))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF C_FORUM_MODERATOR #
				# IF C_FORUM_LOCK_TOPIC #
				<a href="action{U_TOPIC_LOCK}" aria-label="{L_TOPIC_LOCK}" data-confirmation="{L_ALERT_LOCK_TOPIC}"><i class="fa fa-ban" aria-hidden="true"></i></a>
				# ELSE #
				<a href="action{U_TOPIC_UNLOCK}" aria-label="{L_TOPIC_LOCK}" data-confirmation="{L_ALERT_UNLOCK_TOPIC}"><i class="fa fa-ban" aria-hidden="true"></i></a>
				# ENDIF #
				<a href="move{U_TOPIC_MOVE}" aria-label="{L_TOPIC_MOVE}" data-confirmation="{L_ALERT_MOVE_TOPIC}"><i class="fa fa-share" aria-hidden="true"></i></a>
			# ENDIF #
		</div>
	</footer>

	<span id="go-bottom"></span>
	# IF C_AUTH_POST #
		<div class="forum-post-form">
			<form action="post{U_FORUM_ACTION_POST}" method="post" onsubmit="return check_form_msg();">
				<div class="form-element form-element-textarea">
					<label for="contents">{L_RESPOND}</label>
					{KERNEL_EDITOR}
					<div class="form-field-textarea">
						<textarea id="contents" name="contents" rows="15" cols="40">{CONTENTS}</textarea>
					</div>
				</div>

				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="submit" class="button submit" name="valid" value="true">{L_SUBMIT}</button>
					<button type="button" class="button small" onclick="XMLHttpRequest_preview();">{L_PREVIEW}</button>
					<button type="reset" class="button reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
		</div>
	# ENDIF #

# IF C_ERROR_AUTH_WRITE #
	<div class="error-auth-write-response">{L_RESPOND}</div>
	<div class="forum-text-column error-auth-write">
		{L_ERROR_AUTH_WRITE}
	</div>
# ENDIF #
</article>


# INCLUDE forum_bottom #
