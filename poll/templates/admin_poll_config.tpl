		<script>
			<!--
			function check_form_conf()
			{
				if(document.getElementById('cookie_lenght').value == "") {
					alert("{L_REQUIRE}");
					return false;
				}
				if(document.getElementById('cookie_name').value == "") {
					alert("{L_REQUIRE}");
					return false;
				}
				return true;
			}
			function select_displayed_polls_in_mini(id, status)
			{
				var i;

				for(i = 0; i < {NBR_POLL}; i++)
				{
					if( document.getElementById(id + i) )
						document.getElementById(id + i).selected = status;
				}
			}
			-->
			</script>

		<nav id="admin-quick-menu">
			<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
				<i class="fa fa-bars" aria-hidden="true"></i> {L_POLL_MANAGEMENT}
			</a>
			<ul>
				<li>
					<a href="${Url::to_rel('/poll')}" class="quick-link">${LangLoader::get_message('home', 'main')}</a>
				</li>
				<li>
					<a href="admin_poll.php" class="quick-link">{L_POLL_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_poll_add.php" class="quick-link">{L_POLL_ADD}</a>
				</li>
				<li>
					<a href="admin_poll_config.php" class="quick-link">{L_POLL_CONFIG}</a>
				</li>
				<li>
					<a href="${relative_url(PollUrlBuilder::documentation())}" class="quick-link">${LangLoader::get_message('module.documentation', 'admin-modules-common')}</a>
				</li>
			</ul>
		</nav>

		<div class="admin-module-poll" id="admin-contents">
			# INCLUDE MSG #
			<form action="admin_poll_config.php" method="post" class="fieldset-content" onsubmit="check_form_conf()">
				<p class="align-center">{L_REQUIRE}</p>

				<fieldset>
					<legend>{L_POLL_CONFIG_MINI}</legend>
					<div class="fieldset-inset">
						<div class="form-element">
							<label for="displayed_in_mini_module_list">{L_DISPLAYED_IN_MINI_MODULE_LIST} <span class="field-description">{L_DISPLAYED_IN_MINI_MODULE_LIST_EXPLAIN}</span></label>
							<div class="form-field">
								<select id="displayed_in_mini_module_list" name="displayed_in_mini_module_list[]" multiple="multiple">
									{POLL_LIST}
								</select>
								<span class="spacer"></span>
								<a class="small" href="javascript:select_displayed_polls_in_mini('displayed_in_mini_module_list', true);">{L_SELECT_ALL}</a>/<a class="small" href="javascript:select_displayed_polls_in_mini('displayed_in_mini_module_list', false);">{L_SELECT_NONE}</a>
							</div>
						</div>
					</div>
				</fieldset>

				<fieldset>
					<legend>{L_POLL_CONFIG_ADVANCED}</legend>
					<div class="fieldset-inset">
						<div class="form-element">
							<label for="cookie_name">* {L_COOKIE_NAME}</label>
							<div class="form-field"><input type="text" name="cookie_name" id="cookie_name" value="{COOKIE_NAME}"></div>
						</div>
						<div class="form-element">
							<label for="cookie_lenght">* {L_COOKIE_LENGHT} ({L_DAYS})</label>
							<div class="form-field"><input type="text" name="cookie_lenght" id="cookie_lenght" value="{COOKIE_LENGHT}"> </div>
						</div>
						<div class="form-element custom-checkbox">
							<label for="display_results_before_polls_end">{L_DISPLAY_RESULTS_BEFORE_POLLS_END}</label>
							<div class="form-field form-field-checkbox">
								<label class="checkbox" for="poll-results">
									<input id="poll-results" type="checkbox" name="display_results_before_polls_end"# IF C_DISPLAY_RESULTS_BEFORE_POLLS_END # checked="checked"# ENDIF #>
									<span>&nbsp;</span>
								</label>
							</div>
						</div>
					</div>
				</fieldset>

				<fieldset>
					<legend>{L_AUTHORIZATIONS}</legend>
					<div class="fieldset-inset">
						<div class="auth-setter">
							<div class="form-element form-element-auth">
								<label>
									{L_READ_AUTHORIZATION}
								</label>
								<div class="form-field">
									{READ_AUTHORIZATION}
								</div>
							</div>
							<div class="form-element form-element-auth">
								<label>
									{L_WRITE_AUTHORIZATION}
								</label>
								<div class="form-field">
									{WRITE_AUTHORIZATION}
								</div>
							</div>
						</div>

					</div>
				</fieldset>

				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<div class="fieldset-inset">
						<button type="submit" name="valid" value="true" class="button submit">{L_UPDATE}</button>
						<button type="reset" class="button reset-button" value="true">{L_RESET}</button>
						<input type="hidden" name="token" value="{TOKEN}">
					</div>
				</fieldset>
			</form>
		</div>
