<section id="module-user-moderation-panel">
	<header class="section-header">
		<h1>{@user.moderation.panel}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			# IF C_MODO_PANEL_USER #
				<div class="cell-flex cell-columns-3">
					<div class="cell">
						<div class="cell-body">
							<div class="cell-content align-center">
								<a href="{U_WARNING}">
									<i class="fa fa-exclamation-triangle fa-2x warning" aria-hidden="true"></i>
									<span class="d-block">{@user.warning.management}</span>
								</a>
							</div>
						</div>
					</div>
					<div class="cell">
						<div class="cell-body">
							<div class="cell-content align-center">
								<a href="{U_PUNISH}">
									<i class="fa fa-times fa-2x error" aria-hidden="true"></i>
									<span class="d-block">{@user.punishment.management}</span>
								</a>
							</div>
						</div>
					</div>
					<div class="cell">
						<div class="cell-body">
							<div class="cell-content align-center">
								<a href="{U_BAN}">
									<i class="fa fa-minus-circle fa-2x error" aria-hidden="true"></i>
									<span class="d-block">{@user.ban.management}</span>
								</a>
							</div>
						</div>
					</div>
				</div>
				# IF C_MODO_PANEL_USER_LIST #
					<script>
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
								alert("{@warning.username}");
						}
					</script>

					<form action="{U_ACTION}" method="post" class="fieldset-content">
						<fieldset>
							<legend>{L_TITLE}</legend>
							<div class="fieldset-inset">
								<div class="form-element">
									<label for="login">{@user.search.member}</label>
									<div class="form-field grouped-inputs">
										<input type="text" maxlength="25" id="login" value="" name="login">
										<input type="hidden" name="token" value="{TOKEN}">
										<button class="button submit" onclick="XMLHttpRequest_search(this.form);" type="button">{@user.search}</button>
									</div>
								</div>
								<div id="xmlhttprequest-result-search" style="display: none;" class="xmlhttprequest-result-search"></div>
							</div>
						</fieldset>
					</form>

					<table class="table">
						<thead>
							<tr>
								<th>{@user.display.name}</th>
								<th>{L_INFO}</th>
								<th>{L_ACTION_USER}</th>
								<th>{@user.contact.pm}</th>
							</tr>
						</thead>
						<tbody>
							# IF C_EMPTY_LIST #
								<tr>
									<td colspan="4">
										{L_NO_USER}
									</td>
								</tr>
							# ELSE #
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
							# ENDIF #
						</tbody>
					</table>
				# ENDIF #

				# IF C_MODO_PANEL_USER_INFO #
					<script>
						function change_textarea_level(replace_value, regex)
						{
							var contents = document.getElementById('action_contents').value;
							{REPLACE_VALUE}
							document.getElementById('action_contents').value = contents;

							# IF C_TINYMCE_EDITOR # setTinyMceContent(contents); # ENDIF #
						}
					</script>

					<form action="{U_ACTION_INFO}" method="post">
						<fieldset>
							<legend>{L_ACTION_INFO}</legend>
							<div class="fieldset-inset">
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
									<label for="action_contents">{@H|user.alternative.pm}</label>
									{KERNEL_EDITOR}
									<textarea name="action_contents" id="action_contents" rows="12">{ALTERNATIVE_PM}</textarea>
								</div>
								<div class="form-element">
									<label>{@user.readonly.clue}</label>
									<div class="form-field">
										<span id="action_info" class="hidden">{INFO}</span>
										<select name="new_info" onchange="change_textarea_level(this.options[this.selectedIndex].value, {REGEX})">
											{SELECT}
										</select>
									</div>
								</div>
							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" name="valid_user" value="true" class="button submit">{@user.validate}</button>
						</fieldset>
					</form>
				# ENDIF #

				# IF C_MODO_PANEL_USER_BAN #
					<form action="{U_ACTION_INFO}" method="post">
						<fieldset>
							<legend>{L_ACTION_INFO}</legend>
							<div class="fieldset-inset">
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
									<label>{@user.ban.delay}</label>
									<div class="form-field">
										<select name="user_ban">
										# START select_ban #
											{select_ban.TIME}
										# END select_ban #
										</select>
									</div>
								</div>
							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" name="valid_user" value="true" class="button submit">{@user.validate}</button>
						</fieldset>
					</form>
				# ENDIF #
			# ENDIF #
		</div>
	</div>
	<footer></footer>
</section>
