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
		<div class="module-mini-container">
			<div class="module-mini-top">
				<h5 class="sub-title">{L_CONNECT}</h5>
			</div>
			<div class="module-mini-contents vertical-fieldset">
				<form action="{U_CONNECT}" method="post" onsubmit="return check_connect();" class="form-element">
					<label>{L_PSEUDO}<br /><input type="text" id="login" name="login" maxlength="25"></label>
					<label>{L_PASSWORD}<br /><input type="password" id="password" name="password" maxlength="30"></label>
					<label>{L_AUTOCONNECT} <input checked="checked" type="checkbox" name="auto"></label>
					<input type="hidden" name="redirect" value="{REWRITED_SCRIPT}">
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="submit" name="connect" value="true">{L_CONNECT}</button>
				</form>
				<div class="connect-register">
					# IF C_USER_REGISTER # 
					<a class="small" href="${relative_url(UserUrlBuilder::registration())}"><i class="icon-ticket"></i> {L_REGISTER}</a> 
					# ENDIF #
					<br />
					<a class="small" href="${relative_url(UserUrlBuilder::forget_password())}"><i class="icon-question-circle"></i> {L_FORGOT_PASS}</a>
				</div>
			</div>
			<div class="module-mini-bottom">
			</div>
		</div>
	# ELSE #
		<div class="module-mini-container">
			<div class="module-mini-top">
				<h5 class="sub-title">{L_PROFIL}</h5>
			</div>
			<div class="module-mini-contents vertical-fieldset">
				<ul class="connect-content">
					<li>
						<i class="icon-user"></i>
						<a href="${relative_url(UserUrlBuilder::home_profile())}" class="small"> {L_PRIVATE_PROFIL}</a>
					</li>
					<li>
						<i class="icon-envelope# IF C_HAS_PM # blink# ENDIF #"></i>
						<a href="{U_USER_PM}" class="small"> {L_NBR_PM}</a>
					</li>
					# IF C_ADMIN_AUTH #
					<li>
						<i class="icon-wrench# IF C_UNREAD_ALERT # blink# ENDIF #"></i> 
						<a href="${relative_url(UserUrlBuilder::administration())}" class="small"> {L_ADMIN_PANEL}</a> 
					</li>
					# ENDIF #
					# IF C_MODERATOR_AUTH #
					<li>
						<i class="icon-legal"></i>
						<a href="${relative_url(UserUrlBuilder::moderation_panel())}" class="small"> {L_MODO_PANEL}</a>
					</li>
					# ENDIF #	
					<li>
						<i class="icon-file-text# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION # blink# ENDIF #"></i>
						<a href="${relative_url(UserUrlBuilder::contribution_panel())}" class="small"> {L_CONTRIBUTION_PANEL}</a>
					</li>
					<li>
						<i class="icon-sign-out"></i>
						<a href="${relative_url(UserUrlBuilder::disconnect())}" class="small"> {L_DISCONNECT}</a>
					</li>
				</ul>
			</div>
			<div class="module-mini-bottom">
			</div>
		</div>
	# ENDIF #
# ELSE #
	# IF C_USER_NOTCONNECTED #
	<div class="horizontal-fieldset">
		<form action="{U_CONNECT}" method="post" onsubmit="return check_connect();">
			<input type="text" id="login" name="login" value="{L_PSEUDO}" class="connect_form" onfocus="if( this.value == '{L_PSEUDO}' ) this.value = '';" maxlength="25">
			<input type="password" id="password" name="password" class="connect_form" value="******" onfocus="if( this.value == '******' ) this.value = '';" maxlength="30">
			<input checked="checked" type="checkbox" name="auto">
			<input type="hidden" name="redirect" value="{REWRITED_SCRIPT}">
			<button type="submit" name="connect" value="true">{L_CONNECT}</button>
		</form>
		# IF C_USER_REGISTER #
		<form action="${relative_url(UserUrlBuilder::registration())}" method="post">
			<button type="submit" name="register" value="true">{L_REGISTER}</button>
		</form>
		# ENDIF #
	</div>
	# ELSE #
	<div class="horizontal-fieldset">
		<ul class="connect-content">
			<li>
				<i class="icon-user"></i> 
				<a href="${relative_url(UserUrlBuilder::home_profile())}" class="small"> {L_PRIVATE_PROFIL}</a>
			</li>
			<li>
				<i class="icon-envelope# IF C_HAS_PM # blink# ENDIF #"></i>
				<a href="{U_USER_PM}" class="small"> {L_NBR_PM}</a>
			</li>
			# IF C_ADMIN_AUTH #
			<li>
				<i class="icon-wrench# IF C_UNREAD_ALERT # blink# ENDIF #"></i> 
				<a href="${relative_url(UserUrlBuilder::administration())}" class="small"> {L_ADMIN_PANEL}# IF C_UNREAD_ALERT # ({NUMBER_UNREAD_ALERTS})# ENDIF #</a> 
			</li>
			# ENDIF #
			# IF C_MODERATOR_AUTH #
			<li>
				<i class="icon-legal"></i>
				<a href="${relative_url(UserUrlBuilder::moderation_panel())}" class="small"> {L_MODO_PANEL}</a>
			</li>
			# ENDIF #	
			<li>
				<i class="icon-file-text# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION # blink# ENDIF #"></i>
				<a href="${relative_url(UserUrlBuilder::contribution_panel())}" class="small"> {L_CONTRIBUTION_PANEL}# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION # ({NUMBER_UNREAD_CONTRIBUTIONS})# ENDIF #</a>
			</li>
			<li>
				<i class="icon-sign-out"></i>
				<a href="${relative_url(UserUrlBuilder::disconnect())}" class="small"> {L_DISCONNECT}</a>
			</li>
		</ul>
	</div>
	# ENDIF #
# ENDIF #