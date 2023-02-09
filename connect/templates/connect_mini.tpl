# IF NOT IS_USER_CONNECTED #
	<script>
		function check_connect()
		{
			if( document.getElementById('login').value == "" )
			{
				alert("{@warning.username}");
				return false;
			}
			if( document.getElementById('password').value == "" )
			{
				alert("{@warning.password}");
				return false;
			}
		}
	</script>
# ENDIF #

<div id="module-connect" class="cell-mini# IF C_VERTICAL # cell-tile cell-mini-vertical# ENDIF ## IF IS_USER_CONNECTED # user-connected# ELSE # user-not-connected# ENDIF #">
	<div class="cell">
		# IF NOT IS_USER_CONNECTED #
			# IF C_VERTICAL #
				<div class="cell-header">
					<h6 class="cell-name">{@user.welcome} {@user.rank.visitor}</h6>
				</div>
				<div class="cell-list connect-contents">
			# ELSE #
				<div class="cell-list cell-list-inline connect-contents">
					<a href="#" class="js-menu-button" onclick="open_submenu('module-connect', 'active-connect');return false;"><i class="fa fa-sign-in-alt" aria-hidden="true"></i> <span>{@user.sign.in}</span></a>
			# ENDIF #
					<ul class="connect-container# IF C_HORIZONTAL # connect-container-horizontal# ENDIF #">
						<form action="{U_SIGN_IN}" method="post" onsubmit="return check_connect();">
							<li>
								<label for="login">
									<input type="text" id="login" name="login" aria-label="{@user.username.tooltip}" placeholder="{@user.username}">
									<span class="sr-only">{@user.username}</span>
								</label>
							</li>
							<li>
								<label for="password">
									<input type="password" id="password" name="password" placeholder="{@user.password}">
									<span class="sr-only">{@user.password}</span>
								</label>
							</li>
							<li class="align-center">
								<label class="checkbox autoconnect small" for="autoconnect">
									<span>{@user.auto.connect}</span>
									<input checked="checked" type="checkbox" id="autoconnect" name="autoconnect" aria-label="{@user.auto.connect}">
								</label>
							</li>
							<li class="align-center">
								<input type="hidden" name="redirect" value="{SITE_REWRITED_SCRIPT}">
								<input type="hidden" name="token" value="{TOKEN}">
								<button type="submit" name="authenticate" value="internal" class="button submit small">{@user.sign.in}</button>
							</li>
						</form>

						# IF C_REGISTRATION_DISPLAYED #
							# IF C_REGISTRATION_ENABLED #
								<li class="align-center">
									<a href="${relative_url(UserUrlBuilder::registration())}" class="button submit small offload">{@user.sign.up}</a>
								</li>
							# ENDIF #
							<li# IF C_VERTICAL # class="li-stretch"# ENDIF #>
								# START external_auth #
									<a class="{external_auth.CSS_CLASS} offload" href="{external_auth.U_SIGN_IN}" aria-label="{external_auth.NAME}">{external_auth.IMAGE_HTML}</a>
								# END external_auth #
							</li>
						# ENDIF #
						<li class="align-center">
							<a class="button smaller offload forget" href="${relative_url(UserUrlBuilder::forget_password())}">
								<i class="fa fa-question-circle" aria-hidden="true"></i> <span>{@user.forgotten.password}</span>
							</a>
						</li>
					</ul>
				</div>

		# ELSE # <!-- User Connected -->

			# IF C_VERTICAL #
				<div class="cell-header">
					<h6 class="cell-name {USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}"# ENDIF #>{@user.welcome} {USER_DISPLAYED_NAME}</h6>
					# IF C_USER_AVATAR #
						<img src="{U_USER_AVATAR}" class="valign-middle" width="22px" height="22px" alt="{USER_DISPLAYED_NAME}">
					# ELSE #
						# IF IS_MODERATOR #
							<span><i class="fa fa-fw fa-user-tie" aria-hidden="true"></i></span>
						# ELSE #
							<span><i class="fa fa-fw fa-user" aria-hidden="true"></i></span>
						# ENDIF #
					# ENDIF #
				</div>
				<div class="cell-list connected-contents">
			# ELSE #
				<div class="cell-list cell-list-inline connected-contents">
					<a href="#" class="js-menu-button" onclick="open_submenu('module-connect', 'active-connect');return false;">
						# IF C_USER_AVATAR #
							<img src="{U_USER_AVATAR}" class="valign-middle" width="22px" height="22px" alt="{USER_DISPLAYED_NAME}">
						# ELSE #
							# IF IS_MODERATOR #
								<i class="fa fa-user-tie" aria-hidden="true"></i>
							# ELSE #
								<i class="fa fa-user" aria-hidden="true"></i>
							# ENDIF #
						# ENDIF #
						<span aria-label="{@user.my.profile}" class="{USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}"# ENDIF #>{USER_DISPLAYED_NAME}</span>
						# IF C_HAS_PM #
							<span class="stacked member">
								<i class="fa fa-fw fa-lg fa-people-arrows" aria-hidden="true"></i>
								<span class="stack-event stack-circle stack-top-right bgc member blink">{PM_NUMBER}</span>
							</span>
						# ENDIF #
						# IF IS_ADMIN #
							# IF C_UNREAD_ALERTS #
								<span class="stacked administrator">
									<i class="fa fa-fw fa-lg fa-wrench" aria-hidden="true"></i>
									<span class="stack-event stack-circle stack-top-right bgc administrator blink">{UNREAD_ALERTS_NUMBER}</span>
								</span>
							# ENDIF #
						# ENDIF #
						# IF C_UNREAD_CONTRIBUTIONS #
							<span class="stacked moderator">
								<i class="fa fa-fw fa-lg fa-file-alt" aria-hidden="true"></i>
								<span class="stack-event stack-circle stack-top-right bgc moderator blink">{UNREAD_CONTRIBUTIONS_NUMBER}</span>
							</span>
						# ENDIF #
						# START additional_menus #
							# IF additional_menus.C_DISPLAY #
								# IF additional_menus.C_UNREAD_ELEMENTS #
									<span class="stacked {additional_menus.LEVEL_CLASS}">
										<i class="fa-fw fa-lg# IF additional_menus.C_ICON # {additional_menus.ICON}# ELSE # far fa-file-alt# ENDIF #" aria-hidden="true"></i>
										<span class="stack-event stack-circle stack-top-right bgc {additional_menus.LEVEL_CLASS} blink">{additional_menus.UNREAD_ELEMENTS_NUMBER}</span>
									</span>
								# ENDIF #
							# ENDIF #
						# END additional_menus #
					</a>
			# ENDIF #
					<ul class="connect-container# IF C_HORIZONTAL # connect-container-horizontal# ENDIF #">
						<li class="# IF C_VERTICAL #li-stretch # ELSE #li-spaced # ENDIF #connect-profile">
							# IF C_VERTICAL #
								# IF IS_MODERATOR #
									<i class="fa fa-user-tie" aria-hidden="true"></i>
								# ELSE #
									<i class="fa fa-user" aria-hidden="true"></i>
								# ENDIF #
								<a href="${relative_url(UserUrlBuilder::home_profile())}" class="offload">
									<span>{@user.my.account}</span>
								</a>
							# ELSE #
								# IF C_USER_AVATAR #
									<img src="{U_USER_AVATAR}" class="valign-middle" width="22px" height="22px" alt="{USER_DISPLAYED_NAME}">
								# ELSE #
									# IF IS_MODERATOR #
										<i class="fa fa-user-tie" aria-hidden="true"></i>
									# ELSE #
										<i class="fa fa-user" aria-hidden="true"></i>
									# ENDIF #
								# ENDIF #
								<a href="${relative_url(UserUrlBuilder::home_profile())}" class="offload">
									<span class="hidden-small-screens {USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}"# ENDIF # aria-label="{@user.my.account}">{USER_DISPLAYED_NAME}</span>
									<span class="hidden-large-screens">{@user.my.account}</span>
								</a>
							# ENDIF #
						</li>
						<li class="# IF C_VERTICAL #li-stretch # ELSE #li-spaced # ENDIF #connect-pm">
							<span # IF C_HAS_PM #class="stacked member"# ENDIF #>
								<i class="fa fa-fw fa-lg fa-people-arrows" aria-hidden="true"></i>
								# IF C_HAS_PM #<span class="stack-event stack-circle stack-top-right bgc member blink">{PM_NUMBER}</span> # ENDIF #
							</span>
							<a href="{U_USER_PM}" class="offload">
								<span>{@user.private.messaging}</span>
							</a>
						</li>
						# IF IS_ADMIN #
							<li class="# IF C_VERTICAL #li-stretch # ELSE #li-spaced # ENDIF #connect-admin">
								<span # IF C_UNREAD_ALERTS #class="stacked administrator"# ENDIF #>
									<i class="fa fa-fw fa-lg fa-wrench" aria-hidden="true"></i>
									# IF C_UNREAD_ALERTS # <span class="stack-event stack-circle stack-top-right bgc administrator blink">{UNREAD_ALERTS_NUMBER}</span> # ENDIF #
								</span>
								<a href="${relative_url(UserUrlBuilder::administration())}" class="offload">
									<span>{@user.admin.panel}</span>
								</a>
							</li>
						# ENDIF #
						# IF IS_MODERATOR #
							<li class="# IF C_VERTICAL #li-stretch # ELSE #li-spaced # ENDIF #connect-moderation">
								<i class="fa fa-fw fa-lg fa-gavel" aria-hidden="true"></i>
								<a href="${relative_url(UserUrlBuilder::moderation_panel())}" class="offload">
									<span>{@user.moderation.panel}</span>
								</a>
							</li>
						# ENDIF #
						<li class="# IF C_VERTICAL #li-stretch # ELSE #li-spaced # ENDIF #connect-contribution">
							<span # IF C_UNREAD_CONTRIBUTIONS #class="stacked moderator"# ENDIF #>
								<i class="fa fa-fw fa-lg fa-file-alt" aria-hidden="true"></i>
								# IF C_UNREAD_CONTRIBUTIONS #<span class="stack-event stack-circle stack-top-right bgc moderator blink">{UNREAD_CONTRIBUTIONS_NUMBER}</span># ENDIF #
							</span>
							<a href="${relative_url(UserUrlBuilder::contribution_panel())}" class="offload">
								<span>{@user.contribution.panel}</span>
							</a>
						</li>
						# START additional_menus #
							# IF additional_menus.C_DISPLAY #
								<li class="# IF C_VERTICAL #li-stretch # ELSE #li-spaced # ENDIF #connect-{additional_menus.MENU_NAME}">
									<span # IF additional_menus.C_UNREAD_ELEMENTS #class="stacked {additional_menus.LEVEL_CLASS}"# ENDIF #>
										# IF additional_menus.C_ICON #
											<i class="fa fa-fw fa-lg {additional_menus.ICON}" aria-hidden="true"></i>
										# ELSE #
											<a href="{additional_menus.URL}" class="offload">
												<span>{additional_menus.LABEL}</span>
											</a>
										# ENDIF #
										# IF additional_menus.C_UNREAD_ELEMENTS #<span class="stack-event stack-circle stack-top-right bgc {additional_menus.LEVEL_CLASS} blink">{additional_menus.UNREAD_ELEMENTS_NUMBER}</span># ENDIF #
									</span>
									# IF additional_menus.C_ICON #
										<a href="{additional_menus.URL}" class="offload">
											<span>{additional_menus.LABEL}</span>
										</a>
									# ENDIF #
								</li>
							# ENDIF #
						# END additional_menus #
						<li class="# IF C_VERTICAL #li-stretch # ELSE #li-spaced # ENDIF #connect-sign-out">
							<i class="fa fa-fw fa-lg fa-sign-out-alt" aria-hidden="true"></i>
							<a href="${relative_url(UserUrlBuilder::disconnect())}" class="offload">
								<span>{@user.sign.out}</span>
							</a>
						</li>
					</ul>
				</div>
		# ENDIF #
	</div>
</div>
