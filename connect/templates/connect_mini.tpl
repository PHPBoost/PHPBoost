# IF C_USER_NOTCONNECTED #
<script>
<!--
	function check_connect()
	{
		if( document.getElementById('login').value == "" )
		{
			alert("{L_REQUIRE_PSEUDO}");
			return false;
		}
		if( document.getElementById('password').value == "" )
		{
			alert("{L_REQUIRE_PASSWORD}");
			return false;
		}
	}
-->
</script>
# ENDIF #

<div id="module-connect" class="cell-mini# IF C_VERTICAL # cell-tile cell-mini-vertical# ENDIF #">
	<div class="cell">
		# IF C_USER_NOTCONNECTED #

			# IF C_VERTICAL #
			<div class="cell-header">
				<div class="cell-name">{@connection}</div>
			</div>
			<div class="cell-list connect-contents">
			# ELSE #
			<div class="cell-list cell-list-inline connect-contents">
				<a href="" class="js-menu-button" onclick="open_submenu('module-connect', 'active-connect');return false;"><i class="fa fa-sign-in-alt" aria-hidden="true"></i><span>{@connection}</span></a>
			# ENDIF #
				<ul class="connect-container">
					<form action="{U_CONNECT}" method="post" onsubmit="return check_connect();">
						<li>
							<label for="login">
								<input type="text" id="login" name="login" aria-label="{@login} - {@login.tooltip}" placeholder="{@login}">
							</label>
						</li>
						<li>
							<label for="password">
								<input type="password" id="password" name="password" placeholder="{@password}">
							</label>
						</li>
						<li class="align-center">
							<label class="checkbox" for="autoconnect">
								<span>{@autoconnect}</span>
								<input checked="checked" type="checkbox" id="autoconnect" name="autoconnect" aria-label="{@autoconnect}">
							</label>
						</li>
						<input type="hidden" name="redirect" value="{SITE_REWRITED_SCRIPT}">
						<input type="hidden" name="token" value="{TOKEN}">
						<li class="align-center">
							<button type="submit" name="authenticate" value="internal" class="button submit">{@connection}</button>
						</li>
					</form>

					# IF C_DISPLAY_REGISTER_CONTAINER #
						# IF C_USER_REGISTER #
							<form action="${relative_url(UserUrlBuilder::registration())}" method="post">
								<li class="align-center">
									<button type="submit" name="register" value="true" class="button submit">{@register}</button>
								</li>
								<input type="hidden" name="token" value="{TOKEN}">
							</form>
						# ENDIF #
						<li>
							# START external_auth #
								<a class="{external_auth.CSS_CLASS}" href="{external_auth.U_CONNECT}" aria-label="{external_auth.NAME}">{external_auth.IMAGE_HTML}</a>
							# END external_auth #
						</li>
					# ENDIF #
					<li class="align-center">
						<a class="button small" href="${relative_url(UserUrlBuilder::forget_password())}">
							<i class="fa fa-question-circle" aria-hidden="true"></i> <span>${LangLoader::get_message('forget-password', 'user-common')}</span>
						</a>
					</li>
					<li></li>
				</div>
			</ul>

		# ELSE # <!-- User Connected -->

			# IF C_VERTICAL #
				<div class="cell-header">
					<div class="cell-name">{L_PRIVATE_PROFIL}</div>
				</div>
				<div class="cell-list connect-contents">
			# ELSE #
				<div class="cell-list cell-list-inline">
					<a href="" class="js-menu-button" onclick="open_submenu('module-connect', 'active-connect');return false;">
						<i class="fa fa-fw fa-bars # IF NUMBER_TOTAL_ALERT # blink alert# ENDIF #" aria-hidden="true"></i>
						<span>{L_PRIVATE_PROFIL}</span>
						# IF C_HAS_PM #
							<span class="stacked blink member">
								<i class="fa fa-fw fa-envelope" aria-hidden="true"></i>
								<span class="stack-event stack-circle stack-left bgc member">{NUMBER_PM}</span>
							</span>
						# ENDIF #
						# IF C_ADMIN_AUTH #
							# IF C_UNREAD_ALERT #
								<span class="stacked blink administrator">
									<i class="fa fa-fw fa-wrench" aria-hidden="true"></i>
									<span class="stack-event stack-circle stack-left bgc administrator">{NUMBER_UNREAD_ALERTS}</span>
								</span>
							# ENDIF #
						# ENDIF #
						# IF C_UNREAD_CONTRIBUTION #
							<span class="stacked blink moderator">
								<i class="fa fa-fw fa-file-alt" aria-hidden="true"></i>
								<span class="stack-event stack-circle stack-left bgc moderator">{NUMBER_UNREAD_CONTRIBUTIONS}</span>
							</span>
						# ENDIF #
					</a>
			# ENDIF #
					<ul class="connect-container">
						<li class="# IF C_VERTICAL #li-stretch # ENDIF #connect-profil">
							<a href="${relative_url(UserUrlBuilder::home_profile())}">
								 <span class="pbt-small-screen">{@dashboard}</span>
							</a>
							<i class="fa fa-fw fa-tachometer-alt" aria-hidden="true"></i>
						</li>
						<li class="# IF C_VERTICAL #li-stretch # ENDIF #connect-pm">
							<a href="{U_USER_PM}">
								 <span>{L_PM_PANEL}</span>
							</a>
							<span # IF C_HAS_PM #class="stacked blink member"# ENDIF #>
								<i class="fa fa-fw fa-envelope" aria-hidden="true"></i>
								# IF C_HAS_PM #<span class="stack-event stack-circle stack-left bgc member">{NUMBER_PM}</span> # ENDIF #
							</span>
						</li>
						# IF C_ADMIN_AUTH #
							<li class="# IF C_VERTICAL #li-stretch # ENDIF #connect-admin">
								<a href="${relative_url(UserUrlBuilder::administration())}">
									 <span>{L_ADMIN_PANEL}</span>
								</a>
								<span # IF C_UNREAD_ALERT #class="stacked blink administrator"# ENDIF #>
									<i class="fa fa-fw fa-wrench" aria-hidden="true"></i>
									# IF C_UNREAD_ALERT # <span class="stack-event stack-circle stack-left bgc administrator">{NUMBER_UNREAD_ALERTS}</span> # ENDIF #
								</span>
							</li>
						# ENDIF #
						# IF C_MODERATOR_AUTH #
							<li class="# IF C_VERTICAL #li-stretch # ENDIF #connect-modo">
								<a href="${relative_url(UserUrlBuilder::moderation_panel())}">
									 <span>{L_MODO_PANEL}</span>
								</a>
								<i class="fa fa-fw fa-gavel" aria-hidden="true"></i>
							</li>
						# ENDIF #
						<li class="# IF C_VERTICAL #li-stretch # ENDIF #connect-contribution">
							<a href="${relative_url(UserUrlBuilder::contribution_panel())}">
								 <span>{L_CONTRIBUTION_PANEL}</span>
							</a>
							<span # IF C_UNREAD_CONTRIBUTION #class="stacked blink moderator"# ENDIF #>
								<i class="fa fa-fw fa-file-alt" aria-hidden="true"></i>
								# IF C_UNREAD_CONTRIBUTION #<span class="stack-event stack-circle stack-left bgc moderator">{NUMBER_UNREAD_CONTRIBUTIONS}</span># ENDIF #
							</span>
						</li>
						<li class="# IF C_VERTICAL #li-stretch # ENDIF #connect-disconnect">
							<a href="${relative_url(UserUrlBuilder::disconnect())}">
								 <span>{@disconnect}</span>
							</a>
							<i class="fa fa-fw fa-sign-out-alt" aria-hidden="true"></i>
						</li>
					</ul>
				</div>
		# ENDIF #
	</div>
</div>
