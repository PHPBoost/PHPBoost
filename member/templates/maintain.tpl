<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{SITE_NAME} : {L_MAINTAIN_TITLE}</title>
		<link rel="stylesheet" href="../templates/{THEME}/theme/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="../templates/{THEME}/theme/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/{THEME}/theme/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/{THEME}/theme/content.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/{THEME}/theme/bbcode.css" type="text/css" media="screen, print, handheld" />
		<link rel="shortcut" href="../favicon.ico" />
		
		<style type="text/css">
			#content{
				height:750px;
			}
			html>body #content {
				height:auto;
				min-height:750px;
			}
			#maintain{
				text-align:center;
				padding:30px;
				margin-top:150px;
				margin-bottom:100px;
			}
			.delay{
				width:280px;				
				border:1px solid black;
				padding:15px;
				margin:auto;			
			}
		</style>					
	</head>
	<body>		
		<div id="content">
			<div id="maintain">					
				{L_MAINTAIN}
				
				# IF C_DISPLAY_DELAY #
				<br /><br /><br /><br />				
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
					if( document.getElementById('release') )
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
			<p style="text-align:center;">{U_INDEX}</p>	
		</div>	
		<div id="footer" style="position:relative;bottom:0px">
			<span>
				<!-- This mention must figured on the website ! -->
				<!-- Cette mention dois figurer sur le site ! -->
				{L_POWERED_BY} <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {L_PHPBOOST_RIGHT}
			</span>	
			# IF C_DISPLAY_BENCH #
			<br />
			<span>
				{L_ACHIEVED} {BENCH}{L_UNIT_SECOND} - {REQ} {L_REQ}
			</span>	
			# ENDIF #
			# IF C_DISPLAY_AUTHOR_THEME #
			<br />
			<span>
				{L_THEME} {L_THEME_NAME} {L_BY} <a href="{U_THEME_AUTHOR_LINK}" style="font-size:10px;">{L_THEME_AUTHOR}</a>
			</span>
			# ENDIF #
		</div>	
	</body>
</html>