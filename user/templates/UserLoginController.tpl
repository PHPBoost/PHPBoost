# IF C_USER_LOGIN #
	# INCLUDE ERROR_MESSAGE #
	# INCLUDE LOGIN_FORM #
	<div style="text-align:center;">
		# IF C_REGISTRATION_ENABLED # 
		<a href="{U_REGISTER}"><i class="fa fa-ticket"></i> {@registration}</a><br />
		# ENDIF #
		<a href="{U_FORGET_PASSWORD}"><i class="fa fa-question-circle"></i> {L_FORGET_PASSWORD}</a>
	</div>
# ELSE #

	<div id="global">
		# IF C_MAINTAIN #
			<div id="maintain" style="text-align:center;">
				{L_MAINTAIN}
							
				# IF C_DISPLAY_DELAY #
				<div class="delay">
					${LangLoader::get_message('maintain_delay', 'main')}
					<div id="release">${LangLoader::get_message('loading', 'main')}...</div>
				</div>
							
				<script>
				<!--
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
								
							document.getElementById('release').innerHTML = '<strong>' + release_days + '</strong> ${LangLoader::get_message('days', 'date-common')} <strong>' + release_hours + '</strong> ${LangLoader::get_message('hours', 'date-common')} <strong>' + release_minutes + '</strong> ${LangLoader::get_message('minutes', 'date-common')} <strong>' + release_seconds + '</strong> ${LangLoader::get_message('seconds', 'date-common')}';
					}
				}
				release({MAINTAIN_RELEASE_FORMAT});
				-->
				</script>
				# ENDIF #	
			</div>

			<p style="text-align:center;margin-bottom:40px;">
				<a href="#" id="connect" onclick="javascript:document.getElementById('loginForm').style.display='block';">${LangLoader::get_message('connect', 'user-common')}</a>
			</p>

			<style>
			<!--
				form#loginForm, form#loginForm.fieldset-content p { display:none; }
			-->
			</style>
		# ENDIF #

		# INCLUDE ERROR_MESSAGE #
		# INCLUDE LOGIN_FORM #
		
		<style>
		<!--
		.fieldset-content {
			margin: 0px auto;
			width: 450px;
		}
		
		html { height: 100%; }
		
		body {
			background: #E8EDF3;
			height: 100%;
			margin: 0;
			padding: 0;
		}
			
		div#global {
			padding:20px;
			border-spacing: 1px;
			border: 1px #bebebe solid;
			background: #ffffff;
			min-height:0;
			position:relative;
			top:30%
		}

		#maintain { margin:10px 0px; }
		
		div.delay { margin:20px 0px; }
		
		-->
		</style>
	</div>

# ENDIF #