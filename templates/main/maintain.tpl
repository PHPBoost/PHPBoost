<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{SITE_NAME} :: {TITLE}</title>
		<link rel="stylesheet" href="../templates/{THEME}/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="../templates/{THEME}/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/{THEME}/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/{THEME}/content.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/{THEME}/bbcode.css" type="text/css" media="screen, print, handheld" />
		<link rel="shortcut" href="../favicon.ico" />
		
		<style type="text/css">
			#content{
				height: 750px;
			}
			html>body #content {
				height: auto;
				min-height: 750px;
			}
			#maintain{
				text-align:center;
				padding: 10px;
			}
			.delay{
				width: 280px;				
				border: 1px solid black;
				padding: 15px;
				margin-left: auto;
				margin-right: auto;			
			}
		</style>		
		
		<script type="text/javascript">
		<!--		
		function release(year, month, day, hour, minute, second)
		{
			if( document.getElementById('release') )
			{
				var sp_day = 86400;
				var sp_hour = 3600;
				var sp_minute = 60;
				
				now = new Date();
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
		
				document.getElementById('release').innerHTML = '<strong>' + release_days + '</strong> {L_DAYS} <strong>' + release_hours + '</strong> {L_HOURS} <strong>' + release_minutes + '</strong> {L_MIN} <strong>' + release_seconds + '</strong> {L_SEC}';
			}
		}
		-->
		</script>		
	</head>
	<body onload="release({L_REALEASE_FORMAT});">		
		<div id="content">
			<br />
			<p style="text-align:center"><img src="../templates/{THEME}/images/mascotte.jpg" alt="" /></p>
			<br /><br /><br /><br />
			
			<hr />
			<div id="maintain">					
				{L_MAINTAIN}
				
				# START delay #
				<br /><br /><br /><br />
				
				<div class="delay">
					{delay.L_MAINTAIN_DELAY}
					<br /><br />	
					<script type="text/javascript">
						document.write('<div id="release">{L_LOADING}</div>');
					</script>
					<noscript>				
						<strong>{delay.DELAY}</strong>
					</noscript>
				</div>
				# END delay #
			</div>	
			<hr />
			
			<br /><br />			
			<p style="text-align:center;">{U_INDEX}</p>	
		</div>	
		<div id="footer" style="position:relative;bottom:0px">
			<span class="footer">
				<!-- This mention must figured on the website ! -->
				<!-- Cette mention dois figurer sur le site ! -->
				{L_POWERED_BY} <a href="http://www.phpboost.com" title="PHPBoost">PHPBoost {VERSION}</a> &copy; <a href="http://www.phpboost.com" title="PHPBoost">PHPBoost</a>
				{L_PHPBOOST_RIGHT}
				<br /><br />
			</span>		
				<a href="http://www.phpboost.com" title="PHPBoost"><img src="../templates/{THEME}/images/power_phpboost.jpg" alt="PHPBoost" title="PHPBoost" /></a>
				<a href="http://validator.w3.org/check?uri=referer" title="Xhtml 1.0 Strict"><img src="../templates/{THEME}/images/xhtml.jpg" alt="Xhtml 1.0 Strict" title="Xhtml 1.0 Strict" /></a>
				<a href="http://jigsaw.w3.org/css-validator/validator?uri={HOST}{DIR}/templates/{THEME}/{THEME}.css" title="Css3"><img src="../templates/{THEME}/images/css3.jpg" alt="Css3" title="Css3" /></a>
		</div>	
	</body>
</html>