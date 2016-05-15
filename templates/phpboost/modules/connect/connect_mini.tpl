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
<script>
	<!--
	function open_submenu(myid)
	{
		jQuery('#' + myid).toggleClass('active');
	}
	-->
</script>
<div id="command-bar">
# IF C_USER_NOTCONNECTED #
<div id="connect-menu" class="disconnected">
	<div class="horizontal-fieldset">
		<a href="" class="js-menu-button" onclick="open_submenu('connect-menu');return false;" title="{L_CONNECT}"><i class="fa fa-sign-in"></i> {L_CONNECT}</a>
		<div class="connect-content">
			<form action="{U_CONNECT}" method="post" onsubmit="return check_connect();">
				<input type="text" id="login" name="login" title="{L_PSEUDO}" placeholder="{L_PSEUDO}" class="connect-form">
				<input type="password" id="password" name="password" class="connect-form" title="{L_PASSWORD}" placeholder="{L_PASSWORD}">
				<input checked="checked" id="autoconnect" type="checkbox" name="autoconnect" title="{L_AUTOCONNECT}"><span class="hidden-large-screens">{L_AUTOCONNECT}</span>
				<input type="hidden" name="redirect" value="{SITE_REWRITED_SCRIPT}">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" id="btn-connect" name="authenticate" value="internal" class="submit">{L_CONNECT}</button>
			</form>
			# IF C_USER_REGISTER #
				<form action="${relative_url(UserUrlBuilder::registration())}" method="post">
					<button type="submit" id="btn-register" name="register" value="true" class="submit">{L_REGISTER}</button>
					<input type="hidden" name="token" value="{TOKEN}">
				</form>
				<div class="social-connect-container">
					# IF C_FB_AUTH_ENABLED #
					<a class="social-connect fb" href="${relative_url(UserUrlBuilder::connect('fb'))}" title="${LangLoader::get_message('facebook-connect', 'user-common')}"><i class="fa fa-facebook"></i><span>${LangLoader::get_message('facebook-connect', 'user-common')}</span></a>
					# ENDIF #
					# IF C_GOOGLE_AUTH_ENABLED #
					<a class="social-connect google" href="${relative_url(UserUrlBuilder::connect('google'))}" title="${LangLoader::get_message('google-connect', 'user-common')}"><i class="fa fa-google-plus"></i><span>${LangLoader::get_message('google-connect', 'user-common')}</span></a>
					# ENDIF #
				</div>
			# ENDIF #
			<a class="forgot-pass small" href="${relative_url(UserUrlBuilder::forget_password())}">{L_FORGOT_PASS}</a>
		</div>
	</div>
</div>
# ELSE #
<div id="connect-menu">
	<div class="horizontal-fieldset">
		<span class="connect-welcome hidden-large-screens">${LangLoader::get_message('welcome', 'user-common')}, <a href="{U_USER_PROFILE}" class="{USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a></span>
		<a href="" class="js-menu-button" onclick="open_submenu('connect-menu');return false;" title="{L_PROFIL}">{L_PROFIL} <i class="fa fa-bars # IF NUMBER_TOTAL_ALERT # blink alert# ENDIF #"></i></a>
		<ul class="connect-content">
			<li class="connect-welcome">${LangLoader::get_message('welcome', 'user-common')}, <a href="{U_USER_PROFILE}" class="{USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a>
			</li>
			<li class="connect-menu-container">
				<i class="fa fa-connect# IF NUMBER_TOTAL_ALERT # blink alert# ENDIF #"></i>

				<ul class="connect-connected">
					<span class="connect-avatar">
						<img src="{U_AVATAR_IMG}" alt="avatar" title="Avatar" />
					</span>
					<li>
						<a href="${relative_url(UserUrlBuilder::home_profile())}" class="small"><i class="fa fa-user"></i> {L_PRIVATE_PROFIL}</a>
					</li>
					<li>
						<a href="{U_USER_PM}" class="small"><i class="fa fa-envelope# IF C_HAS_PM # blink alert# ENDIF #"></i> {L_NBR_PM}</a>
					</li>
					# IF C_ADMIN_AUTH #
					<li>
						<a href="${relative_url(UserUrlBuilder::administration())}" class="small"><i class="fa fa-wrench# IF C_UNREAD_ALERT # blink alert# ENDIF #"></i> {L_ADMIN_PANEL}# IF C_UNREAD_ALERT # ({NUMBER_UNREAD_ALERTS})# ENDIF #</a>
					</li>
					# ENDIF #
					# IF C_MODERATOR_AUTH #
					<li>
						<a href="${relative_url(UserUrlBuilder::moderation_panel())}" class="small"><i class="fa fa-legal"></i> {L_MODO_PANEL}</a>
					</li>
					# ENDIF #
					<li>
						<a href="${relative_url(UserUrlBuilder::contribution_panel())}" class="small"><i class="fa fa-file-text# IF C_UNREAD_CONTRIBUTION # blink alert# ENDIF #"></i> {L_CONTRIBUTION_PANEL}# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION # ({NUMBER_UNREAD_CONTRIBUTIONS})# ENDIF #</a>
					</li>
				</ul>
			</li>
			<li class="connect-disconnect">
				<a href="${relative_url(UserUrlBuilder::disconnect())}" class="small" title="{L_DISCONNECT}"><i class="fa fa-sign-out"></i> <span class="hidden-large-screens"><span>{L_DISCONNECT}</span></a>
			</li>
		</ul>

	</div>
</div>
# ENDIF #
</div>
