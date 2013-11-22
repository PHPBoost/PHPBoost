# IF C_USER_NOTCONNECTED #
		<script type="text/javascript">
		<!--
		function check_connect()
		{
			return checkForms(new Array(
				'login', "{L_REQUIRE_PSEUDO}", 
				'password', "{L_REQUIRE_PASSWORD}"
			));
		}
		-->
		</script>
# ENDIF #
# IF C_VERTICAL #
	# IF C_USER_NOTCONNECTED #
		<form action="{U_CONNECT}" method="post" onsubmit="return check_connect();">
			<div class="module_mini_container">
				<div class="module_mini_top">
					<h5 class="sub_title">{L_CONNECT}</h5>
				</div>
				<div class="module_mini_contents connect_vertical">
					<p>
						<label>{L_PSEUDO}
						<br />
						<input type="text" id="login" name="login" maxlength="25"></label>
						<br />
						<label>{L_PASSWORD}
						<br />
						<input type="password" id="password" name="password" maxlength="30"></label>
						<br />
						<label>{L_AUTOCONNECT} <input checked="checked" type="checkbox" name="auto"></label>
					</p>
					<p>
						<input type="hidden" name="redirect" value="{REWRITED_SCRIPT}">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="connect" value="true">{L_CONNECT}</button>
					</p>
					<p class="connect_register">
						# IF C_USER_REGISTER # 
						<a class="small" href="${relative_url(UserUrlBuilder::registration())}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/register_mini.png" alt="" class="valign_middle" /> {L_REGISTER}</a> 
						# ENDIF #
						<br />
						<a class="small" href="${relative_url(UserUrlBuilder::forget_password())}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/forget_mini.png" alt="" class="valign_middle" /> {L_FORGOT_PASS}</a>
					</p>
				</div>
				<div class="module_mini_bottom">
				</div>
			</div>
		</form>
	# ELSE #
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_PROFIL}</h5>
			</div>
			<div class="module_mini_contents connect_content">
				<ul>
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/members_mini.png" alt="" class="valign_middle" /> <a href="${relative_url(UserUrlBuilder::home_profile())}" class="small">{L_PRIVATE_PROFIL}</a></li>
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/# IF C_HAS_PM #new_pm.gif# ELSE #pm_mini.png# ENDIF #" class="valign_middle" alt="" /> <a href="{U_USER_PM}" class="small">{L_NBR_PM}</a>&nbsp;</li>
					# IF C_ADMIN_AUTH # 
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/ranks_mini.png" alt="" class="valign_middle" /> <a href="${relative_url(UserUrlBuilder::administration())}" class="small">{L_ADMIN_PANEL}# IF C_UNREAD_ALERT # ({NUMBER_UNREAD_ALERTS})# ENDIF #</a></li> 
					# ENDIF #
					# IF C_MODERATOR_AUTH # 
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/modo_mini.png" alt="" class="valign_middle" /> <a href="${relative_url(UserUrlBuilder::moderation_panel())}" class="small">{L_MODO_PANEL}</a></li> 
					# ENDIF #
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/# IF C_UNREAD_CONTRIBUTION #contribution_panel_mini_new.gif# ELSE #contribution_panel_mini.png# ENDIF #" alt="" class="valign_middle" /> <a href="${relative_url(UserUrlBuilder::contribution_panel())}" class="small">{L_CONTRIBUTION_PANEL}# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION # ({NUM_UNREAD_CONTRIBUTIONS})# ENDIF #</a></li> 
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/home_mini.png" alt="" class="valign_middle" /> <a href="${relative_url(UserUrlBuilder::disconnect())}" class="small">{L_DISCONNECT}</a></li>
				</ul>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
	# ENDIF #
# ELSE #
	# IF C_USER_NOTCONNECTED #
	<div class="connect_align">
		<form action="{U_CONNECT}" method="post" onsubmit="return check_connect();" class="connect_align">
			<input type="text" id="login" name="login" value="{L_PSEUDO}" class="connect_form" onfocus="if( this.value == '{L_PSEUDO}' ) this.value = '';" maxlength="25">
			<input type="password" id="password" name="password" class="connect_form" value="******" onfocus="if( this.value == '******' ) this.value = '';" maxlength="30">
			<input checked="checked" type="checkbox" name="auto">
			<input type="hidden" name="redirect" value="{REWRITED_SCRIPT}">
			<button type="submit" name="connect" value="true">{L_CONNECT}</button>
		</form>
		# IF C_USER_REGISTER #
		<form action="${relative_url(UserUrlBuilder::registration())}" method="post" class="connect_align">
			<button type="submit" name="register" value="true">{L_REGISTER}</button>
		</form>
		# ENDIF #
	</div>
	# ELSE #
	<div class="connect_align">
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/members_mini.png" alt="" class="valign_middle" /> <a href="${relative_url(UserUrlBuilder::home_profile())}" class="small">{L_PRIVATE_PROFIL}</a>&nbsp;
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/# IF C_HAS_PM #new_pm.gif# ELSE #pm_mini.png# ENDIF #" class="valign_middle" alt="" /> <a href="{U_USER_PM}" class="small">{L_NBR_PM}</a>&nbsp;
		# IF C_ADMIN_AUTH #
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/ranks_mini.png" alt="" class="valign_middle" /> <a href="${relative_url(UserUrlBuilder::administration())}" class="small">{L_ADMIN_PANEL}# IF C_UNREAD_ALERT # ({NUMBER_UNREAD_ALERTS}) # ENDIF #</a>&nbsp; 
		# ENDIF #
		# IF C_MODERATOR_AUTH # 
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/modo_mini.png" alt="" class="valign_middle" /> <a href="${relative_url(UserUrlBuilder::moderation_panel())}" class="small">{L_MODO_PANEL}</a>&nbsp;
		# ENDIF #
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/# IF C_UNREAD_CONTRIBUTION #contribution_panel_mini_new.gif# ELSE #contribution_panel_mini.png# ENDIF #" alt="" class="valign_middle" /> <a href="${relative_url(UserUrlBuilder::contribution_panel())}" class="small">{L_CONTRIBUTION_PANEL}# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION # ({NUM_UNREAD_CONTRIBUTIONS})# ENDIF #</a>&nbsp;
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/home_mini.png" alt="" class="valign_middle" /> <a href="${relative_url(UserUrlBuilder::disconnect())}" class="small">{L_DISCONNECT}</a>
	</div>
	# ENDIF #
# ENDIF #