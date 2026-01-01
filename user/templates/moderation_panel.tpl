<section id="module-user-moderation-panel">
	<header class="section-header">
		<h1>{@user.moderation.panel}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			# IF C_USER #
				<div class="cell-flex cell-columns-3">
					<div class="cell">
						<div class="cell-body">
							<div class="cell-content align-center">
								<a class="offload" href="{U_WARNING}">
									<i class="fa fa-exclamation-triangle fa-2x warning" aria-hidden="true"></i>
									<span class="d-block">{@user.warnings.management}</span>
								</a>
							</div>
						</div>
					</div>
					<div class="cell">
						<div class="cell-body">
							<div class="cell-content align-center">
								<a class="offload" href="{U_PUNISH}">
									<i class="fa fa-times fa-2x error" aria-hidden="true"></i>
									<span class="d-block">{@user.punishments.management}</span>
								</a>
							</div>
						</div>
					</div>
					<div class="cell">
						<div class="cell-body">
							<div class="cell-content align-center">
								<a class="offload" href="{U_BAN}">
									<i class="fa fa-minus-circle fa-2x error" aria-hidden="true"></i>
									<span class="d-block">{@user.bans.management}</span>
								</a>
							</div>
						</div>
					</div>
				</div>
				# IF C_USER_LIST #
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
									<label for="login">{@user.search.member} <span class="field-description">{@user.search.joker}</span></label>
									<div class="form-field grouped-inputs">
										<input type="text" maxlength="25" id="login" value="" name="login">
										<input type="hidden" name="token" value="{TOKEN}">
										<button class="button submit" onclick="XMLHttpRequest_search(this.form);" type="button">{@form.search}</button>
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
								<th>{@user.contact.pm}</th>
								<th><span class="sr-only">{@common.modify}</span></th>
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
											<a href="{member_list.U_PROFILE}" class="{member_list.USER_LEVEL_CLASS} offload" # IF member_list.C_USER_GROUP_COLOR # style="color:{member_list.USER_GROUP_COLOR}" # ENDIF #>{member_list.LOGIN}</a>
										</td>
										<td>
											{member_list.INFO}
										</td>
										<td>
											<a href="{member_list.U_PM}" class="button alt-button smaller offload">MP</a>
										</td>
										<td>
											<a href="{member_list.U_ACTION_USER}" class="offload" aria-label="{@common.modify}">
												<i class="fa fa-edit" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
								# END member_list #
							# ENDIF #
						</tbody>
					</table>
				# ENDIF #

				# IF C_USER_INFO #
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
									<label>{@user.display.name}</label>
									<div class="form-field">
										<a href="{U_PROFILE}" class="{USER_LEVEL_CLASS} offload" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{LOGIN}</a>
									</div>
								</div>
								<div class="form-element">
									<label>{@user.private.message}</label>
									<div class="form-field">
										<a href="{U_PM}" class="button alt-button smaller offload">{@user.pm}</a>
									</div>
								</div>
								<div class="form-element form-element-textarea">
									<label for="action_contents">
										{@user.alternative.pm}
										<span class="field-description">{@H|user.alternative.pm.clue}</span>
									</label>
									<div class="form-field form-field-textarea bbcode-sidebar">
										{KERNEL_EDITOR}
										<textarea name="action_contents" id="action_contents" rows="12">{ALTERNATIVE_PM}</textarea>
									</div>
								</div>
								<div class="form-element">
									<label>
										{@user.readonly}
										<span class="field-description">{@user.readonly.clue}</span>
									</label>
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
							<button type="submit" name="valid_user" value="true" class="button submit">{@form.submit}</button>
						</fieldset>
					</form>
				# ENDIF #

				# IF C_USER_BAN #
					<form action="{U_ACTION_INFO}" method="post">
						<fieldset>
							<legend>{L_ACTION_INFO}</legend>
							<div class="fieldset-inset">
								<div class="form-element">
									<label>{@user.display.name}</label>
									<div class="form-field">
										<a href="{U_PROFILE}" class="{USER_LEVEL_CLASS} offload" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{LOGIN}</a>
									</div>
								</div>
								<div class="form-element">
									<label>{@user.private.message}</label>
									<div class="form-field">
										<a href="{U_PM}" class="button alt-button smaller offload">MP</a>
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
							<button type="submit" name="valid_user" value="true" class="button submit">{@form.submit}</button>
						</fieldset>
					</form>
				# ENDIF #
			# ENDIF #
		</div>
	</div>
	<footer></footer>
</section>
