<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{SITE_NAME} :: {TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="description" content="{SITE_DESCRIPTION}" />
		<meta name="keywords" content="{SITE_KEYWORD}" />
		<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />
		<meta name="Robots" content="index, follow, all" />
		<meta name="classification" content="tout public" />
		<link rel="stylesheet" href="../templates/{THEME}/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="../templates/{THEME}/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/{THEME}/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/{THEME}/content.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/{THEME}/bbcode.css" type="text/css" media="screen, print, handheld" />
		{ALTERNATIVE_CSS}
		<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
		<link rel="alternate" type="application/rss+xml" href="../news/rss.php" title="RSS {SITE_NAME}" />
		<script type="text/javascript" src="../includes/framework/js/global.js"></script>
	</head>
	<body>

	<div id="global">
	<div id="header">
		<img src="../templates/{THEME}/images/header_boost.jpg" alt="PHPBoost" />
		<h1 style="display:none;font-size:9px;">{SITE_NAME}</h1>
	</div>	
	
	# IF C_COMPTEUR #
	<div id="compteur">					
		<span class="text_strong">{L_VISIT}:</span> {COMPTEUR_TOTAL}
		<br />
		<span class="text_strong">{L_TODAY}:</span> {COMPTEUR_DAY}
	</div>
	# ENDIF #
	
	# IF C_ALERT_MAINTAIN #
	<div style="position:absolute;top:5px;width:99%;">					
		<div style="position:relative;width:400px;margin:auto;" class="warning">		
			{L_MAINTAIN_DELAY}
			<br /><br />	
			<script type="text/javascript">
				document.write('<div id="release">{L_LOADING}...</div>');
			</script>
			<noscript>				
				<strong>{DELAY}</strong>
			</noscript>
		</div>
	</div>
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
			release_seconds = (release_seconds < 10) ? '0' + release_seconds : release_seconds;
			
			document.getElementById('release').innerHTML = '<strong>' + release_days + '</strong> {L_DAYS} <strong>' + release_hours + '</strong> {L_HOURS} <strong>' + release_minutes + '</strong> {L_MIN} <strong>' + release_seconds + '</strong> {L_SEC}';
		}
	}
	release({L_RELEASE_FORMAT});
	-->
	</script>
	# ENDIF #
	
		
	<div id="sub_header">						
		<div id="sub_header_left">&nbsp;</div>
		<div id="sub_header_right">&nbsp;</div>
	