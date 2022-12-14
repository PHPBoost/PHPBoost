# INCLUDE FORUM_TOP #

<article itemscope="itemscope" itemtype="https://schema.org/Creativework" id="article-forum-moderation-panel" class="forum-content">
	<header>
		<h2>
			<a class="offload" href="index.php">{FORUM_NAME}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
			<a class="offload" href="moderation_forum.php">{@forum.moderation.forum}</a>
			# IF NOT C_HOME # <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a class="offload" href="{U_MODERATION_FORUM_ACTION}">{L_ALERT}</a># ENDIF #
		</h2>
	</header>
	<div class="content">
		<div class="cell-flex cell-columns-3">
			<div class="cell">
				<div class="cell-body">
					<div class="cell-content align-center">
						<a href="moderation_forum.php?action=warning" class="moderation-type-block offload">
							<i class="fa fa-exclamation-triangle fa-2x warning" aria-hidden="true"></i>
							<span class="d-block">{@user.warnings.management}</span>
						</a>
					</div>
				</div>
			</div>
			<div class="cell">
				<div class="cell-body">
					<div class="cell-content align-center">
						<a href="moderation_forum.php?action=punish" class="moderation-type-block offload">
							<i class="fa fa-times fa-2x error" aria-hidden="true"></i>
							<span class="d-block">{@user.punishments.management}</span>
						</a>
					</div>
				</div>
			</div>
			<div class="cell">
				<div class="cell-body">
					<div class="cell-content align-center">
						<a href="moderation_forum.php?action=alert" class="moderation-type-block offload">
							<i class="fa fa-minus-circle fa-2x error" aria-hidden="true"></i>
							<span class="d-block">{@forum.reports.management}</span>
						</a>
					</div>
				</div>
			</div>
		</div>
		# IF C_FORUM_MODO_MAIN #
			<script>
				function Confirm_history()
				{
					return confirm("{@forum.alert.history}");
				}
			</script>
			<form action="moderation_forum{U_ACTION_HISTORY}" method="post" onsubmit="javascript:return Confirm_history();">
				<table class="table">
					<caption>
						{@forum.history}
					</caption>
					<thead>
						<tr>
							<th>
								{@user.moderator}
							</th>
							<th>
								{@common.actions}
							</th>
							<th>
								{@forum.concerned.user}
							</th>
							<th class="forum-last-topic">
								{@date.date}
							</th>
						</tr>
					</thead>
					<tbody>
					# START action_list #
						<tr>
							<td>
								<a href="{action_list.U_USER_PROFILE}" class="{action_list.LEVEL_CLASS} offload" # IF action_list.C_GROUP_COLOR # style="color: {action_list.GROUP_COLOR};"# ENDIF #>{action_list.LOGIN}</a>
							</td>
							<td>
								# IF action_list.C_ACTION #
									<a class="offload" href="{action_list.U_ACTION}">{action_list.L_ACTION}</a>
								# ELSE #
									{action_list.L_ACTION}
								# ENDIF #
							</td>
							<td>
								# IF action_list.C_USER_CONCERN #
									<a href="{action_list.U_USER_CONCERN}" class="{action_list.USER_CONCERN_CSSCLASS} offload"# IF action_list.C_USER_CONCERN_GROUP_COLOR # style="color: {action_list.USER_CONCERN_GROUP_COLOR};"# ENDIF #>{action_list.USER_LOGIN}</a>
								# ELSE #
									-
								# ENDIF #
							</td>
							<td>
								{action_list.DATE_FULL}
							</td>
						</tr>
					# END action_list #
					# IF C_FORUM_NO_ACTION #
						<tr>
							<td colspan="4">
								<span class="message-helper bgc notice">{@common.no.item.now}</span>
							</td>
						</tr>
					# ENDIF #
					</tbody>
					# IF NOT C_FORUM_NO_ACTION #
						<tfoot>
							<tr>
								<td colspan="4">
									# IF C_FORUM_ADMIN #
										<span class="float-left"><button type="submit" name="valid" value="true" class="button submit">{@common.delete}</button></span>
									# ENDIF #
									# IF C_DISPLAY_LINK_MORE_ACTION #
										<a class="offload" href="moderation_forum{U_MORE_ACTION}">{@more_action}</a>
									# ENDIF #
								</td>
							</tr>
						</tfoot>
					# ENDIF #
				</table>
				<input type="hidden" name="token" value="{TOKEN}">
			</form>
		# ENDIF #


		# IF C_FORUM_ALERTS #
			<script>
				function check_alert(status)
				{
					for (i = 0; i < document.alert.length; i++)
						document.alert.elements[i].checked = status;
				}
			</script>

			<form name="alert" action="moderation_forum{U_ACTION_ALERT}" method="post" onsubmit="javascript:return Confirm_alert();">
				<table class="table">
					<thead>
						<tr>
							<th class="td25"><input type="checkbox" onclick="if(this.checked) {check_alert(true)} else {check_alert(false)};"></th>
							<th class="td20P">{@common.title}</th>
							<th class="td20P">{@forum.report.concerned.topic}</th>
							<th class="td100">{@common.status}</th>
							<th class="td70">{@forum.report.author}</th>
							<th class="td70">{@date.date}</th>
						</tr>
					</thead>
					<tbody>
						# START alert_list #
							<tr>
								<td class="td25">
									<input type="checkbox" name="a{alert_list.ID}">
								</td>
								<td class="td20P">
									<a class="offload flex-between" href="{alert_list.U_TITLE}"> {alert_list.TITLE} <i class="far fa-edit"></i></a>
								</td>
								<td class="td20P">
									<a class="offload" href="{alert_list.U_TOPIC}">{alert_list.TOPIC}</a>
								</td>
								<td class="td100 {alert_list.BACKGROUND_COLOR}">
									# IF alert_list.C_STATUS #
										{@forum.report.solved}<a href="{alert_list.U_IDMODO_REL}" class="{alert_list.MODO_CSSCLASS} offload" # IF alert_list.C_MODO_GROUP_COLOR # style="color: {alert_list.MODO_GROUP_COLOR};"# ENDIF #>{alert_list.LOGIN_MODO}</a>
									# ELSE #
										{@forum.report.unsolved}
									# ENDIF #
								</td>
								<td class="td70">
									<a href="{alert_list.USER_ID}" class="{alert_list.USER_CSSCLASS} offload"# IF alert_list.C_USER_GROUP_COLOR # style="color:{alert_list.USER_GROUP_COLOR};"# ENDIF #>{alert_list.LOGIN_USER}</a>
								</td>
								<td class="td70">
									{alert_list.DATE}
								</td>
							</tr>
						# END alert_list #

						# IF C_FORUM_NO_ALERT #
							<tr>
								<td colspan="6">
									<div class="message-helper bgc notice">{@common.no.item.now}</div>
								</td>
							</tr>
						# ENDIF #
					</tbody>
					<tfoot>
						<tr>
							<td colspan="6">
								<button type="submit" name="" value="true" class="button submit">{@common.delete}</button>
							</td>
						</tr>
					</tfoot>
				</table>
				<input type="hidden" name="token" value="{TOKEN}">
			</form>
		# ENDIF #

		# IF C_FORUM_ALERT_LIST #
			<table class="table">
				<thead>
					<tr>
						<th>{@common.description}</th>
						<th>{@common.content}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="td25P">{@common.title}</td>
						<td>{TITLE}</td>
					</tr>
					<tr>
						<td>{@forum.report.concerned.topic}</td>
						<td><a class="offload" href="{U_TOPIC}">{TOPIC}</a></td>
					</tr>
					<tr>
						<td>{@forum.report.concerned.category}</td>
						<td><a class="offload" href="{U_CAT}">{CAT_NAME}</a></td>
					</tr>
					<tr>
						<td>{@forum.report.message}</td>
						<td>{CONTENT}</td>
					</tr>
					<tr>
						<td>{@common.status}</td>
						<td>
							# IF C_STATUS #
								{@forum.report.solved}<a href="{U_IDMODO_REL}" class="{MODO_CSSCLASS} offload" # IF C_MODO_GROUP_COLOR # style="color: {MODO_GROUP_COLOR};"# ENDIF #>{LOGIN_MODO}</a>
							# ELSE #
								{@forum.report.unsolved}
							# ENDIF #
						</td>
					</tr>
					<tr>
						<td>{@forum.report.author}</td>
						<td>
							<a href="{USER_ID}" class="{USER_CSSCLASS} offload"# IF C_USER_GROUP_COLOR # style="color: {USER_GROUP_COLOR};"# ENDIF #>{LOGIN_USER}</a>
						</td>
					</tr>
					<tr>
						<td>{@date.date}</td>
						<td>{DATE}</td>
					</tr>
				</tbody>
			</table>

			<form action="{U_CHANGE_STATUS}" method="post">
				<fieldset class="fieldset-submit">
					<legend></legend>
					<button type="submit" name="valid" value="true" class="button submit">{L_CHANGE_STATUS}</button>
					<input type="hidden" name="token" value="{TOKEN}">
				</fieldset>
			</form>
		# ENDIF #

		# IF C_FORUM_ALERT_NOT_AUTH #
			<table class="table-no-header">
				<thead>
					<tr>
						<th colspan="2">
							{@forum.moderation.forum} : {@forum.reports.management}
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="2">
							<div class="message-helper bgc notice">{@common.no.item.now}</div>
						</td>
					</tr>
				</tbody>
			</table>
		# ENDIF #



		# IF C_FORUM_USER_LIST #
			<script>
				function XMLHttpRequest_search()
				{
					var login = document.getElementById('login').value;
					if( login != '' )
					{
						if( document.getElementById('search_img') )
							document.getElementById('search_img').innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
						data = 'login=' + login;
						var xhr_object = xmlhttprequest_init('xmlhttprequest.php?token={TOKEN}&{U_XMLHTTPREQUEST}=1');
						xhr_object.onreadystatechange = function()
						{
							if( xhr_object.readyState == 4 && xhr_object.status == 200 )
							{
								document.getElementById('xmlhttprequest-result-search').innerHTML = xhr_object.responseText;
								hide_div('xmlhttprequest-result-search');
								if( document.getElementById('search_img') )
									document.getElementById('search_img').innerHTML = '';
							}
							else if( xhr_object.readyState == 4 )
							{
								if( document.getElementById('search_img') )
									document.getElementById('search_img').innerHTML = '';
							}
						}
						xmlhttprequest_sender(xhr_object, data);
					}
					else
						alert("{@warning.username}");
				}

				function hide_div(divID)
				{
					if( document.getElementById(divID) )
						document.getElementById(divID).style.display = 'block';
				}
			</script>

			<form action="moderation_forum{U_ACTION}" method="post">
				<fieldset>
					<legend>{@user.search.member}</legend>
					<div class="form-element">
						<label for="login">{@user.search.member} <span class="field-description">{@user.search.joker}</span></label>
						<div class="form-field grouped-inputs">
							<input type="text" maxlength="25" id="login" value="" name="login">
							<input type="hidden" name="token" value="{TOKEN}">
							<button class="button submit" onclick="XMLHttpRequest_search(this.form);" type="button">{@form.search}</button>
						</div>
					</div>
					<div id="xmlhttprequest-result-search" style="display: none;" class="xmlhttprequest-result-search"></div>
				</fieldset>

			</form>
			<table class="table">
				<thead>
					<tr>
						<th class="td25P">{@user.display.name}</th>
						<th class="td25P">{L_INFO_TYPE}</th>
						<th class="td25P">{L_ACTION_USER}</th>
						<th class="td25P">{@user.contact.pm}</th>
					</tr>
				</thead>
				<tbody>
					# START user_list #
					<tr>
						<td class="td25P">
							<a href="{user_list.U_PROFILE}" class="{user_list.LEVEL_CLASS} offload" # IF user_list.C_GROUP_COLOR # style="color: {user_list.GROUP_COLOR};"# ENDIF #>{user_list.LOGIN}</a>
						</td>
						<td class="td25P">
							{user_list.INFO}
						</td>
						<td class="td25P">
							<a class="offload" href="{user_list.U_ACTION_USER}"><i class="fa fa-lock"></i></a>
						</td>
						<td class="td25P">
							<a href="{user_list.U_PM}" class="button alt-button smaller offload">MP</a>
						</td>
					</tr>
					# END user_list #

					# IF C_FORUM_NO_USER #
						<tr>
							<td colspan="4">
								{L_NO_USER}
							</td>
						</tr>
					# ENDIF #
				</tbody>
			</table>
		# ENDIF #


		# IF C_FORUM_USER_INFO #
			<script>
				function change_textarea_level(replace_value, regex)
				{
					var content = document.getElementById('action_content').value;
					{REPLACE_VALUE}
					document.getElementById('action_content').value = content;

					# IF C_TINYMCE_EDITOR # setTinyMceContent(content); # ENDIF #
				}
			</script>
			<form action="moderation_forum{U_ACTION_INFO}" method="post">
				<table class="table">
					<thead>
						<tr>
							<th>
								{@common.description}
							</th>
							<th>
								{@user.member}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="td33P">
								{@user.display.name}
							</td>
							<td>
								<a href="{USER_ID}" class="{USER_CSSCLASS} offload"# IF C_USER_GROUP_COLOR # style="color: {USER_GROUP_COLOR};"# ENDIF #>{LOGIN_USER}</a>
							</td>
						</tr>
						<tr>
							<td class="td33P">
								{@user.contact.pm}
							</td>
							<td>
								<a href="{U_PM}" class="button submit smaller offload">{@user.pm}</a>
							</td>
						</tr>
						<tr>
							<td class="td33P">
								<div class="message-helper bgc question">{@H|forum.report.alternative.pm}</div>
							</td>
							<td>
								<div class="form-field form-field-textarea bbcode-sidebar">
									{KERNEL_EDITOR}
									<textarea class="auto-resize forum-textarea" name="action_content" id="action_content">{ALTERNATIVE_PM}</textarea>
								</div>
							</td>
						</tr>
						<tr>
							<td class="td33P">
								<label for="new_info">{@user.warning.clue}</label>
							</td>
							<td>
								<span class="hidden" id="action_info">{INFO}</span>
								<select name="new_info" id="new_info" onchange="change_textarea_level(this.options[this.selectedIndex].value, {REGEX})">
									{INFO_SELECT}
								</select>
								<button type="submit" name="valid_user" value="true" class="button submit">{@form.submit}</button>
								<input type="hidden" name="token" value="{TOKEN}">
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		# ENDIF #

	</div>
	<footer>
		<a class="offload" href="index.php">{FORUM_NAME}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
		<a class="offload" href="moderation_forum.php">{@forum.moderation.forum}</a>
		# IF NOT C_HOME # <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a class="offload" href="{U_MODERATION_FORUM_ACTION}">{L_ALERT}</a># ENDIF #
	</footer>
</article>

# INCLUDE FORUM_BOTTOM #
