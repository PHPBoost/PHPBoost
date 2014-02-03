<div id="global">
	<div id="maintain" style="text-align:center;">
		{L_MAINTAIN}
					
		# IF C_DISPLAY_DELAY #
		<div class="delay">
			{L_MAINTAIN_DELAY}
			<div id="release">{L_LOADING}...</div>
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
						
					document.getElementById('release').innerHTML = '<strong>' + release_days + '</strong> {L_DAYS} <strong>' + release_hours + '</strong> {L_HOURS} <strong>' + release_minutes + '</strong> {L_MIN} <strong>' + release_seconds + '</strong> {L_SEC}';
			}
		}
		release({MAINTAIN_RELEASE_FORMAT});
		-->
		</script>
		# ENDIF #	
	</div>

	<p style="text-align:center;margin-bottom:40px;">
		<a href="#" id="connect" onclick="javascript:document.getElementById('loginForm').style.display='block';">{L_CONNECT}</a>
	</p>		
	# INCLUDE ERROR_MESSAGE #
	# INCLUDE LOGIN_FORM #
	
	<style>
	<!--
	.fieldset-content {
		margin: 0px auto;
		width: 450px;
	}

	form#loginForm, 
	form#loginForm.fieldset-content p { display:none; }
	
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
		top:40%
	}

	#maintain { margin:10px 0px; }
	
	div.delay { margin:20px 0px; }
	
	-->
	</style>
</div>