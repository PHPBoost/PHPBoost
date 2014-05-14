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

# IF C_VERTICAL #

# ELSE #
	# IF C_USER_NOTCONNECTED #
	<div id="connect-menu">
		<div class="horizontal-fieldset">
			<ul>
				<li class="connect-login"><a href='/user/?url=/connect'>{L_CONNECT}</a>
					<form action="{U_CONNECT}" method="post" onsubmit="return check_connect();" class="sub-menu">
						<input type="text" id="login" name="login" value="{L_PSEUDO}" class="connect-form" onfocus="if( this.value == '{L_PSEUDO}' ) this.value = '';" maxlength="25">
						<input type="password" id="password" name="password" class="connect-form" value="******" onfocus="if( this.value == '******' ) this.value = '';" maxlength="30">
						<input checked="checked" type="checkbox" name="auto">
						<input type="hidden" name="redirect" value="{REWRITED_SCRIPT}">
						<button type="submit" name="connect" value="true">{L_CONNECT}</button>
					</form>
				</li>
				<li class="connect-subscribe">
					<a href='${relative_url(UserUrlBuilder::registration())}'>{L_REGISTER}</a>
				</li>
			</ul>
		</div>
	</div>
	# ELSE #
	<div id="connect-menu">
		<div class="horizontal-fieldset">
			<ul>
				# IF NUMBER_TOTAL_ALERT #	
				<li class="connect-login connect-alert"><a href="${relative_url(UserUrlBuilder::home_profile())}">{L_PRIVATE_PROFIL}</a> <span style="font-size:11px;margin-right:12px;">({NUMBER_TOTAL_ALERT})</span> 
				# ELSE #
				<li class="connect-login"><a href="${relative_url(UserUrlBuilder::home_profile())}">{L_PRIVATE_PROFIL}</a>
				# ENDIF #
					<ul class="connect-connected">
						<img src="{U_AVATAR_IMG}" alt="avatar" title="Avatar" width="90px" class="connect-avatar"/>
						<li>
							<i class="fa fa-user"></i> 
							<a href="${relative_url(UserUrlBuilder::home_profile())}" class="small"> {L_PRIVATE_PROFIL}</a>
						</li>
						<li>
							<i class="fa fa-envelope# IF C_HAS_PM # blink# ENDIF #"></i>
							<a href="{U_USER_PM}" class="small"> {L_NBR_PM}</a>
						</li>
						# IF C_ADMIN_AUTH #
						<li>
							<i class="fa fa-wrench# IF C_UNREAD_ALERT # blink# ENDIF #"></i> 
							<a href="${relative_url(UserUrlBuilder::administration())}" class="small"> {L_ADMIN_PANEL}# IF C_UNREAD_ALERT # ({NUMBER_UNREAD_ALERTS})# ENDIF #</a> 
						</li>
						# ENDIF #
						# IF C_MODERATOR_AUTH #
						<li>
							<i class="fa fa-legal"></i>
							<a href="${relative_url(UserUrlBuilder::moderation_panel())}" class="small"> {L_MODO_PANEL}</a>
						</li>
						# ENDIF #	
						<li>
							<i class="fa fa-file-text# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION # blink# ENDIF #"></i>
							<a href="${relative_url(UserUrlBuilder::contribution_panel())}" class="small"> {L_CONTRIBUTION_PANEL}# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION # ({NUMBER_UNREAD_CONTRIBUTIONS})# ENDIF #</a>
						</li>
	
					</ul>
				</li>
				<li class="connect-disconnect">
					<a href="${relative_url(UserUrlBuilder::disconnect())}" class="small"><i class="fa fa-sign-out"></i> {L_DISCONNECT}</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="connect-welcome">Bienvenue, <a href="{U_USER_PROFILE}" class="{USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a></div>
	# ENDIF #
# ENDIF #