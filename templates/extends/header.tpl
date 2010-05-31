<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{SITE_NAME} : {TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="description" content="{SITE_DESCRIPTION} {TITLE}" />
		<meta name="keywords" content="{SITE_KEYWORD}" />
		<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />
		<!-- Default CSS -->
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/framework/content/syndication/syndication.css" type="text/css" media="screen, print, handheld" />
		<!-- Theme CSS -->
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/content.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/bbcode.css" type="text/css" media="screen, print, handheld" />
		<!-- Alternative CSS -->
		{ALTERNATIVE_CSS}

		<link rel="shortcut icon" href="{PATH_TO_ROOT}/favicon.ico" type="image/x-icon" />
		<link rel="alternate" href="{PATH_TO_ROOT}/syndication.php?m=news" type="application/rss+xml" title="RSS {SITE_NAME}" />
		
		<script type="text/javascript">
		<!--
			var PATH_TO_ROOT = "{PATH_TO_ROOT}";
			var TOKEN = "{TOKEN}";
		-->
		</script>
		# IF C_BBCODE_TINYMCE_MODE # <script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/content/tinymce/tiny_mce.js"></script> # ENDIF #

		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/prototype.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/global.js"></script>
	</head>
	<body>

# IF C_MAINTAIN_DELAY #
# IF C_ALERT_MAINTAIN #
<div style="position:absolute;top:5px;width:99%;">
	<div style="position:relative;width:400px;margin:auto;margin-top:30px;" class="warning">
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
# ENDIF #

<script type="text/javascript">
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

<div id="global">
	<div id="header_container">
		<div id="header">
			<h1 style="display:none;font-size:9px;">{SITE_NAME}</h1>
			# IF C_MENUS_HEADER_CONTENT #
			{MENUS_HEADER_CONTENT}
			# ENDIF #
		</div>
		<div id="sub_header">
			# IF C_MENUS_SUB_HEADER_CONTENT #
			{MENUS_SUB_HEADER_CONTENT}
			# ENDIF #
		</div>
		<div class="spacer"></div>
	</div>
	
	# IF C_COMPTEUR #
	<div id="compteur">
		<span class="text_strong">{L_VISIT}:</span> {COMPTEUR_TOTAL}
		<br />
		<span class="text_strong">{L_TODAY}:</span> {COMPTEUR_DAY}
	</div>
	# ENDIF #
	
	# IF C_MENUS_LEFT_CONTENT #
	<div id="left_menu">
		{MENUS_LEFT_CONTENT}
	</div>
	# ENDIF #
	
	# IF C_MENUS_RIGHT_CONTENT #
	<div id="right_menu">
		{MENUS_RIGHT_CONTENT}
	</div>
	# ENDIF #
	
	<div id="main">
		# IF C_MENUS_TOPCENTRAL_CONTENT #
		<div id="top_contents">
			{MENUS_TOPCENTRAL_CONTENT}
		</div>
		# ENDIF #
		<div id="main_content">&nbsp;
			<div id="links">
				&nbsp;&nbsp;<a class="small_link" href="{START_PAGE}" title="{L_INDEX}">{L_INDEX}</a>
				# START link_bread_crumb #
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/breadcrumb.png" alt="" class="valign_middle" /> <a class="small_link" href="{link_bread_crumb.URL}" title="{link_bread_crumb.TITLE}">{link_bread_crumb.TITLE}</a>
				# END link_bread_crumb #
			</div>