# IF C_USER_LOGIN #
	<section id="user-login">
		<header></header>
		<article class="">
			# INCLUDE ERROR_MESSAGE #
			# INCLUDE LOGIN_FORM #
			<div class="align-center">
				# IF C_REGISTRATION_ENABLED #
					<a class="offload" href="{U_REGISTER}"><i class="fa fa-ticket-alt" aria-hidden="true"></i> {@user.sign.up}</a>
				# ENDIF #
				# IF C_DISPLAY_EXTERNAL_AUTHENTICATION #
					# START external_auth #
						<a class="{external_auth.CSS_CLASS} login-page" href="{external_auth.U_CONNECT}">{external_auth.IMAGE_HTML}</a>
					# END external_auth #
				# ENDIF #
				<div class="spacer"></div>
				<a class="offload" href="{U_FORGOTTEN_PASSWORD}"><i class="fa fa-question-circle" aria-hidden="true"></i> {@user.forgotten.password}</a>
			</div>
		</article>
		<footer></footer>
	</section>
# ELSE #
	<div id="header">
		<div id="top-header">
			<div id="site-infos" role="banner">
				<div id="site-logo" # IF C_HEADER_LOGO #style="background-image: url({HEADER_LOGO});"# ENDIF #></div>
				<div id="site-name-container">
					<a class="offload" id="site-name" href="{PATH_TO_ROOT}/">{SITE_NAME}</a>
					<span id="site-slogan">{SITE_SLOGAN}</span>
				</div>
			</div>
		</div>
	</div>
	<div id="global" class="global-maintain">
		# IF C_MAINTAIN #
			<div id="maintain" class="align-center">
				{L_MAINTAIN}

				# IF C_DISPLAY_DELAY #
					<div class="delay">
						${LangLoader::get_message('admin.maintenance.delay', 'admin-lang')}
						<div id="release">${LangLoader::get_message('common.loading', 'common-lang')}...</div>
					</div>

					<script>
						var release_timeout_seconds = 0;
						function release(year, month, day, hour, minute, second)
						{
							if(document.getElementById('release'))
							{
								var sp_day = 86400;
								var sp_hour = 3600;
								var sp_minute = 60;

								now = new Date({MAINTAIN_NOW_FORMAT}+release_timeout_seconds++);
								end = new Date(year, month, day, hour, minute, second);

								release_time = (end.getTime() - now.getTime())/1000;
								if( release_time <= 0 )
								{
									document.location.reload();
									release_time = '0';
								}
								else
									timeout = setTimeout('release('+year+', '+month+', '+day+', '+hour+', '+minute+', '+second+')', 1000);

									release_days = Math.floor(release_time/sp_day);
									release_time -= (release_days * sp_day);

									release_hours = Math.floor(release_time/sp_hour);
									release_time -= (release_hours * sp_hour);

									release_minutes = Math.floor(release_time/sp_minute);
									release_time -= (release_minutes * sp_minute);

									release_seconds = Math.floor(release_time);
									release_seconds = (release_seconds < 10) ? '0' + release_seconds : release_seconds;

									document.getElementById('release').innerHTML = '<strong>' + release_days + '</strong> ' + ${escapejs(LangLoader::get_message('date.days', 'date-lang'))} + '<strong> ' + release_hours + '</strong> ' + ${escapejs(LangLoader::get_message('date.hours', 'date-lang'))} + '<strong> ' + release_minutes + '</strong> ' + ${escapejs(LangLoader::get_message('date.minutes', 'date-lang'))} + '<strong> ' + release_seconds + '</strong> ' + ${escapejs(LangLoader::get_message('date.seconds', 'date-lang'))};
							}
						}
						release({MAINTAIN_RELEASE_FORMAT});
					</script>
				# ENDIF #
			</div>

			# IF NOT C_HAS_ERROR #
				<p class="align-center">
					<a href="#" id="connect" onclick="jQuery('#loginForm').toggle();jQuery('#externalAuthForm').toggle();return false;">{@user.sign.in}</a>
				</p>

				<script>
					jQuery(document).ready(function() {
						jQuery('#loginForm').hide();
						jQuery('#externalAuthForm').hide();
					});
				</script>
			# ENDIF #
		# ENDIF #

		# INCLUDE ERROR_MESSAGE #
		# INCLUDE LOGIN_FORM #
		<div id="externalAuthForm" class="align-center">
			# IF C_DISPLAY_EXTERNAL_AUTHENTICATION #
				# START external_auth #
					<a class="{external_auth.CSS_CLASS} login-page" href="{external_auth.U_CONNECT}">{external_auth.IMAGE_HTML}</a>
				# END external_auth #
			# ENDIF #
		</div>
	</div>

# ENDIF #
