# INCLUDE forum_top #

<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-moderation-panel" class="forum-contents">
	<header>
		<h2>
			<a href="index.php">{FORUM_NAME}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
			<a href="moderation_forum.php">{L_MODERATION_FORUM}</a># IF NOT C_HOME # <i class="fa fa-angle-double-right" aria-hidden="true"></i>
			<a href="{U_MODERATION_FORUM_ACTION}">{L_ALERT}</a># ENDIF #
		</h2>
	</header>
	<div class="content">
		<div class="cell-flex cell-columns-3">
			<div class="cell">
				<div class="cell-body">
					<div class="cell-content align-center">
						<a href="moderation_forum.php?action=warning" class="moderation-type-block">
							<i class="fa fa-exclamation-triangle fa-2x warning" aria-hidden="true"></i>
							<span class="d-block">{L_USERS_WARNING}</span>
						</a>
					</div>
				</div>
			</div>
			<div class="cell">
				<div class="cell-body">
					<div class="cell-content align-center">
						<a href="moderation_forum.php?action=punish" class="moderation-type-block">
							<i class="fa fa-times fa-2x error" aria-hidden="true"></i>
							<span class="d-block">{L_USERS_PUNISHMENT}</span>
						</a>
					</div>
				</div>
			</div>
			<div class="cell">
				<div class="cell-body">
					<div class="cell-content align-center">
						<a href="moderation_forum.php?action=alert" class="moderation-type-block">
							<i class="fa fa-minus-circle fa-2x error" aria-hidden="true"></i>
							<span class="d-block">{L_ALERT_MANAGEMENT}</span>
						</a>
					</div>
				</div>
			</div>
		</div>
		# IF C_FORUM_MODO_MAIN #
			<script>
				function Confirm_history()
				{
					return confirm("{L_DEL_HISTORY}");
				}
			</script>
			<form action="moderation_forum{U_ACTION_HISTORY}" method="post" onsubmit="javascript:return Confirm_history();">
				<table class="table">
					<caption>
						{L_HISTORY}
					</caption>
					<thead>
						<tr>
							<th>
								{L_MODO}
							</th>
							<th>
								{L_ACTION}
							</th>
							<th>
								{L_USER_CONCERN}
							</th>
							<th class="forum-last-topic">
								{L_DATE}
							</th>
						</tr>
					</thead>
					<tbody>
					# START action_list #
						<tr>
							<td class="forum-last-topic">
								<a href="{action_list.U_USER_PROFILE}" class="{action_list.LEVEL_CLASS}" # IF action_list.C_GROUP_COLOR # style="color: {action_list.GROUP_COLOR};"# ENDIF #>{action_list.LOGIN}</a>
							</td>
							<td>
								# IF action_list.C_ACTION #
									<a href="{action_list.U_ACTION}">{action_list.L_ACTION}</a>
								# ELSE #
									{action_list.L_ACTION}
								# ENDIF #
							</td>
							<td class="forum-last-topic">
								# IF action_list.C_USER_CONCERN #
									<a href="{action_list.U_USER_CONCERN}" class="{action_list.USER_CONCERN_CSSCLASS}"# IF action_list.C_USER_CONCERN_GROUP_COLOR # style="color: {action_list.USER_CONCERN_GROUP_COLOR};"# ENDIF #>{action_list.USER_LOGIN}</a>
								# ELSE #
									-
								# ENDIF #
							</td>
							<td class="forum-last-topic">
								{action_list.DATE_FULL}
							</td>
						</tr>
					# END action_list #
					# IF C_FORUM_NO_ACTION #
						<tr>
							<td colspan="4">
								{L_NO_ACTION}
							</td>
						</tr>
					# ENDIF #
					</tbody>
					<tfoot>
						<tr>
							<td colspan="4">
								# IF C_FORUM_ADMIN #
									<span class="float-left"><button type="submit" name="valid" value="true" class="button submit">{L_DELETE}</button></span>
								# ENDIF #
								# IF C_DISPLAY_LINK_MORE_ACTION #
									<a href="moderation_forum{U_MORE_ACTION}">{L_MORE_ACTION}</a>
								# ENDIF #
							</td>
						</tr>
					</tfoot>
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
							<th class="td20P">{L_TITLE}</th>
							<th class="td20P">{L_TOPIC}</th>
							<th class="td100">{L_STATUS}</th>
							<th class="td70">{L_LOGIN}</th>
							<th class="td70">{L_TIME}</th>
						</tr>
					</thead>
					<tbody>
						# START alert_list #
							<tr>
								<td class="td25">
									<input type="checkbox" name="a{alert_list.ID}">
								</td>
								<td class="td20P">
									<a href="{alert_list.U_TITLE}">{alert_list.TITLE}</a> <a href="{alert_list.U_TITLE}"><i class="far fa-edit"></i></a>
								</td>
								<td class="td20P">
									<a href="{alert_list.U_TOPIC}">{alert_list.TOPIC}</a>
								</td>
								<td class="td100 {alert_list.BACKGROUND_COLOR}">
									# IF alert_list.C_STATUS #
										{L_ALERT_SOLVED}<a href="{alert_list.U_IDMODO_REL}" class="{alert_list.MODO_CSSCLASS}" # IF alert_list.C_MODO_GROUP_COLOR # style="color: {alert_list.MODO_GROUP_COLOR};"# ENDIF #>{alert_list.LOGIN_MODO}</a>
									# ELSE #
										{L_ALERT_NOTSOLVED}
									# ENDIF #
								</td>
								<td class="td70">
									<a href="{alert_list.USER_ID}" class="{alert_list.USER_CSSCLASS}"# IF alert_list.C_USER_GROUP_COLOR # style="color:{alert_list.USER_GROUP_COLOR};"# ENDIF #>{alert_list.LOGIN_USER}</a>
								</td>
								<td class="td70">
									{alert_list.TIME_FULL}
								</td>
							</tr>
						# END alert_list #

						# IF C_FORUM_NO_ALERT #
							<tr>
								<td colspan="6">
									{L_NO_ALERT}
								</td>
							</tr>
						# ENDIF #
					</tbody>
					<tfoot>
						<tr>
							<td colspan="6">
								<button type="submit" name="" value="true" class="button submit">{L_DELETE}</button>
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
						<th colspan="2">
							{TOPIC}
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="td25P">{L_TITLE}</td>
						<td>{TITLE}</td>
					</tr>
					<tr>
						<td>{L_TOPIC}</td>
						<td><a href="{U_TOPIC}">{TOPIC}</a></td>
					</tr>
					<tr>
						<td>{L_CAT}</td>
						<td><a href="{U_CAT}">{CAT_NAME}</a></td>
					</tr>
					<tr>
						<td>{L_CONTENTS}</td>
						<td>{CONTENTS}</td>
					</tr>
					<tr>
						<td>{L_STATUS}</td>
						<td>
							# IF C_STATUS #
								{L_ALERT_SOLVED}<a href="{U_IDMODO_REL}" class="{MODO_CSSCLASS}" # IF C_MODO_GROUP_COLOR # style="color: {MODO_GROUP_COLOR};"# ENDIF #>{LOGIN_MODO}</a>
							# ELSE #
								{L_ALERT_NOTSOLVED}
							# ENDIF #
						</td>
					</tr>
					<tr>
						<td>{L_LOGIN}</td>
						<td>
							<a href="{USER_ID}" class="{USER_CSSCLASS}"# IF C_USER_GROUP_COLOR # style="color: {USER_GROUP_COLOR};"# ENDIF #>{LOGIN_USER}</a>
						</td>
					</tr>
					<tr>
						<td>{L_TIME}</td>
						<td>{TIME_FULL}</td>
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
							{L_MODERATION_FORUM} : {L_ALERT_MANAGEMENT}
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="2">
							{L_NO_ALERT}
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
						alert("{L_REQUIRE_LOGIN}");
				}

				function hide_div(divID)
				{
					if( document.getElementById(divID) )
						document.getElementById(divID).style.display = 'block';
				}
			</script>

			<form action="moderation_forum{U_ACTION}" method="post">
				<fieldset>
					<legend>{L_SEARCH_USER}</legend>
					<div class="form-element">
						<label for="login">{L_SEARCH_USER} <span class="field-description">{L_JOKER}</span></label>
						<div class="form-field grouped-inputs">
							<input type="text" maxlength="25" id="login" value="" name="login">
							<input type="hidden" name="token" value="{TOKEN}">
							<button class="button submit" onclick="XMLHttpRequest_search(this.form);" type="button">{L_SEARCH}</button>
						</div>
					</div>
						<div id="xmlhttprequest-result-search" style="display: none;" class="xmlhttprequest-result-search"></div>
				</fieldset>

			</form>
			<table class="table">
				<thead>
					<tr>
						<th class="td25P">{L_LOGIN}</th>
						<th class="td25P">{L_INFO}</th>
						<th class="td25P">{L_ACTION_USER}</th>
						<th class="td25P">{L_PM}</th>
					</tr>
				</thead>
				<tbody>
					# START user_list #
					<tr>
						<td class="td25P">
							<a href="{user_list.U_PROFILE}" class="{user_list.LEVEL_CLASS}" # IF user_list.C_GROUP_COLOR # style="color: {user_list.GROUP_COLOR};"# ENDIF #>{user_list.LOGIN}</a>
						</td>
						<td class="td25P">
							{user_list.INFO}
						</td>
						<td class="td25P">
							<a href="{user_list.U_ACTION_USER}"><i class="fa fa-lock"></i></a>
						</td>
						<td class="td25P">
							<a href="{user_list.U_PM}" class="button alt-button smaller">MP</a>
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
					var contents = document.getElementById('action_contents').value;
					{REPLACE_VALUE}
					document.getElementById('action_contents').value = contents;

					# IF C_TINYMCE_EDITOR # setTinyMceContent(contents); # ENDIF #
				}
			</script>
			<form action="moderation_forum{U_ACTION_INFO}" method="post">
				<table class="table">
					<thead>
						<tr>
							<th>
								${LangLoader::get_message('description', 'main')}
							</th>
							<th>
								${LangLoader::get_message('display_name', 'user-common')}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="td33P">
								{L_LOGIN}
							</td>
							<td>
								<a href="{USER_ID}" class="{USER_CSSCLASS}"# IF C_USER_GROUP_COLOR # style="color: {USER_GROUP_COLOR};"# ENDIF #>{LOGIN_USER}</a>
							</td>
						</tr>
						<tr>
							<td class="td33P">
								{L_PM}
							</td>
							<td>
								<a href="{U_PM}" class="button submit smaller">MP</a>
							</td>
						</tr>
						<tr>
							<td class="td33P">
								<div class="message-helper bgc question">{L_ALTERNATIVE_PM}</div>
							</td>
							<td>
								{KERNEL_EDITOR}
								<textarea class="forum-textarea" name="action_contents" id="action_contents">{ALTERNATIVE_PM}</textarea>
							</td>
						</tr>
						<tr>
							<td class="td33P">
								<label for="new_info">{L_INFO_EXPLAIN}</label>
							</td>
							<td>
								<span class="forum-display-block" id="action_info">{INFO}</span>
								<select name="new_info" id="new_info" onchange="change_textarea_level(this.options[this.selectedIndex].value, {REGEX})">
									{SELECT}
								</select>
								<button type="submit" name="valid_user" value="true" class="button submit">{L_CHANGE_INFO}</button>
								<input type="hidden" name="token" value="{TOKEN}">
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		# ENDIF #

	</div>
	<footer>
		<a href="index.php">{FORUM_NAME}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
		<a href="moderation_forum.php">{L_MODERATION_FORUM}</a>
		# IF NOT C_HOME # <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="{U_MODERATION_FORUM_ACTION}">{L_ALERT}</a># ENDIF #
	</footer>
</article>


# INCLUDE forum_bottom #
