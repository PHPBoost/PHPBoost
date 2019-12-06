# IF C_MAINTAIN_DELAY #
	# IF C_ALERT_MAINTAIN #
	<div id="message-maintain" class="floating-message-container">
		<div id="message-helper-maintain" class="message-helper bgc warning message-helper-small">
			<a id="message-helper-button-maintain" class="bgc-full warning {MESSAGE_CSS_CLASS}" aria-label="${LangLoader::get_message('message.close_ephemeral_message', 'status-messages-common')}"><i class="fa fa-close-message" aria-hidden="true"></i></a>
			{L_MAINTAIN_DELAY}
			<div id="release">{L_LOADING}...</div>
		</div>
	</div>
	# ENDIF #
	<script>
	<!--
		jQuery(function(){
			jQuery("#message-helper-maintain").fadeTo("fast", 1);

			$('#message-helper-button-maintain').on('click',function() {
				jQuery('#message-helper-maintain').fadeTo('slow', 0);
				setTimeout('jQuery("#message-helper-maintain").hide();', 400);
			});
		});
	-->
	</script>
	<script>
	<!--
		var release_timeout_seconds = 0;
		function release(year, month, day, hour, minute, second)
		{
			if (document.getElementById('release'))
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
		if ({UNSPECIFIED})
			release({MAINTAIN_RELEASE_FORMAT});
		else
		{
			if (document.getElementById('release'))
				document.getElementById('release').innerHTML = '<strong>{DELAY}</strong>';
		}
	-->
	</script>
# ENDIF #
