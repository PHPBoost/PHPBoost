<div id="maintain" style="text-align:center;">
	<br />			
	{L_MAINTAIN}
	
	# IF C_DISPLAY_DELAY #
	<br /><br /><br />		
	<div class="delay">
		{L_MAINTAIN_DELAY}
		<br /><br />	
		<script type="text/javascript">
		<!--
			document.write('<div id="release">{L_LOADING}...</div>');
		-->
		</script>
		<noscript>				
			<p style="display:inline;font-weight:bold;">{DELAY}</p>
		</noscript>
	</div>
	
	<script type="text/javascript">
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
</br></br>
<p style="text-align:center;"><a href="{U_CONNECT}">{L_CONNECT}</a></p>