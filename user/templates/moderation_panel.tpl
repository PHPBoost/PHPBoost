		<section id="module-user-moderation-panel">
			<header>
				<h1>{L_MODERATION_PANEL}</h1>
			</header>
			<div class="content">
			# IF C_MODO_PANEL_USER #
				<table class="table">
					<tbody>
						<tr>
							<td class="no-separator">
								<a class="infos-options" href="{U_WARNING}" aria-label="{L_USERS_WARNING}">
									<i class="fa fa-exclamation-triangle fa-2x warning" aria-hidden="true"></i>
								</a>
								<a href="{U_WARNING}">{L_USERS_WARNING}</a>
							</td>
							<td class="no-separator">
								<a class="infos-options" href="{U_PUNISH}" aria-label="{L_USERS_PUNISHMENT}">
									<i class="fa fa-times fa-2x error" aria-hidden="true"></i>
								</a>
								<a href="{U_PUNISH}">{L_USERS_PUNISHMENT}</a>
							</td>
							<td class="no-separator">
								<a class="infos-options" href="{U_BAN}" aria-label="{L_USERS_BAN}">
									<i class="fa fa-minus-circle fa-2x error" aria-hidden="true"></i>
								</a>
								<a href="{U_BAN}">{L_USERS_BAN}</a>
							</td>
						</tr>
					</tbody>
				</table>

				# IF C_MODO_PANEL_USER_LIST #
				<script>
				<!--
					function XMLHttpRequest_search()
					{
						var login = jQuery('#login').val();
						if( login != "" )
						{
							jQuery.ajax({
								url: '{PATH_TO_ROOT}/kernel/framework/ajax/member_xmlhttprequest.php?token={TOKEN}&{U_XMLHTTPREQUEST}=1',
								type: "post",
								dataType: "html",
								data: {'login': login},
								success: function(returnData){
									jQuery('#xmlhttprequest-result-search').html(returnData);
									jQuery('#xmlhttprequest-result-search').fadeIn();
								}
							});
						}
						else
							alert("{L_REQUIRE_LOGIN}");
					}
					-->
				</script>

				<form action="{U_ACTION}" method="post" class="fieldset-content">
					<fieldset>
						<legend>{L_SEARCH_USER}</legend>
						<div class="form-element">
							<label for="login">{L_SEARCH_USER} <span class="field-description">{L_JOKER}</span></label>
							<div class="form-field">
								<input type="text" maxlength="25" id="login" value="" name="login">
								<input type="hidden" name="token" value="{TOKEN}">
								<button class="button small" onclick="XMLHttpRequest_search(this.form);" type="button">{L_SEARCH}</button>
								<div id="xmlhttprequest-result-search" style="display: none;" class="xmlhttprequest-result-search"></div>
							</div>
						</div>
					</fieldset>
				</form>

				<table class="table">
					<thead>
						<tr>
							<th>{L_LOGIN}</th>
							<th>{L_INFO}</th>
							<th>{L_ACTION_USER}</th>
							<th>{L_PM}</th>
						</tr>
					</thead>
					<tbody>
						# START member_list #
						<tr>
							<td>
								<a href="{member_list.U_PROFILE}" class="{member_list.USER_LEVEL_CLASS}" # IF member_list.C_USER_GROUP_COLOR # style="color:{member_list.USER_GROUP_COLOR}" # ENDIF #>{member_list.LOGIN}</a>
							</td>
							<td>
								{member_list.INFO}
							</td>
							<td>
								{member_list.U_ACTION_USER}
							</td>
							<td>
								<a href="{member_list.U_PM}" class="button alt-button smaller">MP</a>
							</td>
						</tr>
						# END member_list #

						# IF C_EMPTY_LIST #
						<tr>
							<td colspan="4">
								{L_NO_USER}
							</td>
						</tr>
						# ENDIF #
					</tbody>
				</table>
				# ENDIF #



				# IF C_MODO_PANEL_USER_INFO #
				<script>
				<!--
				function change_textarea_level(replace_value, regex)
				{
					var contents = document.getElementById('action_contents').value;
					{REPLACE_VALUE}
					document.getElementById('action_contents').value = contents;

					# IF C_TINYMCE_EDITOR # setTinyMceContent(contents); # ENDIF #
				}
				-->
				</script>

				<form action="{U_ACTION_INFO}" method="post">
					<fieldset>
						<legend>{L_ACTION_INFO}</legend>

						<div class="form-element">
							<label>{L_LOGIN}</label>
							<div class="form-field">
								<a href="{U_PROFILE}" class="{USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{LOGIN}</a>
							</div>
						</div>
						<div class="form-element">
							<label>{L_PM}</label>
							<div class="form-field">
								<a href="{U_PM}" class="button alt-button smaller">MP</a>
							</div>
						</div>
						<div class="form-element form-element-textarea">
							<label for="action_contents">{L_ALTERNATIVE_PM}</label>
							{KERNEL_EDITOR}
							<textarea name="action_contents" id="action_contents" rows="12">{ALTERNATIVE_PM}</textarea>
						</div>
						<div class="form-element">
							<label>{L_INFO_EXPLAIN}</label>
							<div class="form-field">
								<span id="action_info">{INFO}</span>
								<select name="new_info" onchange="change_textarea_level(this.options[this.selectedIndex].value, {REGEX})">
									{SELECT}
								</select>
							</div>
						</div>
					</fieldset>

					<fieldset class="fieldset-submit">
						<legend></legend>
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="valid_user" value="true" class="button submit">{L_CHANGE_INFO}</button>
					</fieldset>
				</form>
				# ENDIF #

				# IF C_MODO_PANEL_USER_BAN #
				<form action="{U_ACTION_INFO}" method="post">
					<fieldset>
						<legend>{L_ACTION_INFO}</legend>

						<div class="form-element">
							<label>{L_LOGIN}</label>
							<div class="form-field">
								<a href="{U_PROFILE}" class="{USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{LOGIN}</a>
							</div>
						</div>
						<div class="form-element">
							<label>{L_PM}</label>
							<div class="form-field">
								<a href="{U_PM}" class="button alt-button smaller">MP</a>
							</div>
						</div>
						<div class="form-element">
							<label>{L_DELAY_BAN}</label>
							<div class="form-field">
								<select name="user_ban">
								# START select_ban #
									{select_ban.TIME}
								# END select_ban #
								</select>
							</div>
						</div>
					</fieldset>

					<fieldset class="fieldset-submit">
						<legend></legend>
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="valid_user" value="true" class="button submit">{L_BAN}</button>
					</fieldset>
				</form>
				# ENDIF #
			# ENDIF #
				</div>
			<footer></footer>
		</section>
