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
		<script type="text/javascript" src="../kernel/framework/js/scriptaculous/prototype.js"></script>
		<script type="text/javascript" src="../kernel/framework/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="../kernel/framework/js/global.js"></script>
	</head>
	<body>

<span id="scroll_top_page"></span>
<div id="global">
	<div id="header">
		<h1 style="display:none;font-size:9px;">{SITE_NAME}</h1>
		{MODULES_MINI_HEADER_CONTENT}
		<div class="dynamic_menu" style="left:190px;top:113px">
			<ul>
				<li>
					<h5 class="links"><a href="../news/news.php" title="Accueil"><img src="../templates/phpboost/images/admin/admin_mini.png" class="valign_middle" alt="" /> Accueil</a></h5>
				</li>
				<li onmouseover="show_menu('l1', 0);" onmouseout="hide_menu(0);">
					<h5 class="links"><a href="../download/download-2-52+phpboost-2-0.php" title="PHPBoost"><img src="../download/download_mini.png" class="valign_middle" alt="" /> PHPBoost</a></h5>
					<ul id="smenul1">
						<li><a href="../download/download-2-52+phpboost-2-0.php" style="background-image:url(../download/download_mini.png);">PHPBoost 2</a></li>
						<li><a href="http://demo.phpboost.com" title="Démonstration PHPBoost" style="background-image:url(../templates/phpboost/images/admin/com_mini.png);">Démonstration</a></li>
						<li><a href="http://themes.phpboost.com" style="background-image:url(../templates/phpboost/images/admin/themes_mini.png);">Thèmes</a></li>
						<li><a href="../phpboost/modules.php" style="background-image:url(../templates/phpboost/images/admin/modules_mini.png);">Modules</a></li>
					</ul>
				</li>
				<li onmouseover="show_menu('l2', 0);" onmouseout="hide_menu(0);">
					<h5 class="links"><a href="../wiki/wiki.php" title="Documentation PHPBoost"><img src="../wiki/wiki_mini.png" class="valign_middle" alt="" /> Documentation</a></h5>
					<ul id="smenul2"><li><a href="../wiki/presentation-de-phpboost" style="background-image:url(../images/doc/presentation_mini.png);">Présentation</a></li>
						<li><a href="../wiki/installation" style="background-image:url(../images/doc/installation_mini.png);">Installation</a></li>
						<li><a href="../wiki/utilisation" style="background-image:url(../images/doc/utilisation_mini.png);">Utilisation</a></li>
						<li><a href="../wiki/modules" style="background-image:url(../images/doc/modules_mini.png);">Modules</a></li>
						<li><a href="../wiki/personnalisation-de-phpboost" style="background-image:url(../images/doc/personnalisation_mini.png);">Personnalisation</a></li><li><a href="../wiki/developpement" style="background-image:url(../images/doc/developpement_mini.png);">Développement</a></li>
						<li><a href="../pages/videos-de-demonstration" style="background-image:url(../images/doc/videos_mini.png);">Vidéos</a></li>
						<li><a href="../faq/faq.php" style="background-image:url(../faq/faq_mini.png);">FAQ</a></li>
					</ul>
				</li>
				<li>
					<h5 class="links"><a href="../articles/articles.php" title="Dossiers"><img src="../articles/articles_mini.png" class="valign_middle" alt="" /> Dossiers</a></h5>
				</li>
				<li onmouseover="show_menu('l4', 0);" onmouseout="hide_menu(0);">
					<h5 class="links"><a href="../forum/index.php" title="Communauté"><img src="../forum/forum_mini.png" class="valign_middle" alt="" /> Communauté</a></h5>
					<ul id="smenul4">
						<li><a href="../forum/index.php" style="background-image:url(../forum/forum_mini.png);">Forum</a></li>						
						<li><a href="../shoutbox/shoutbox.php" style="background-image:url(../shoutbox/shoutbox_mini.png);">Discussion</a></li>						
						<li><a href="../newsletter/newsletter.php" style="background-image:url(../newsletter/newsletter_mini.png);">Newsletter</a></li>						
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<div id="sub_header">
		{MODULES_MINI_SUB_HEADER_CONTENT}
	</div>
	<div id="links_vertical">
		<p style="margin:0px;"><img onclick="new Effect.ScrollTo('scroll_top_page',{duration:1.2}); return false;" style="cursor:pointer;" src="../templates/{THEME}/images/top.png" alt="" /></p>
		<hr style="width:50%;margin:auto;" />
		<p style="margin:0;margin-top:5px;"><a href="../news/news.php"><img src="../templates/{THEME}/images/admin/admin_mini.png" alt="" /></a></p>
	# IF C_MEMBER_CONNECTED #		
		<p style="margin:0"><a href="../member/member{U_MEMBER_ID}" class="small_link" title="{L_PRIVATE_PROFIL}"><img src="../templates/{THEME}/images/admin/members_mini.png" alt="" class="valign_middle" /></a>	</p>		
		<p style="margin:0"><a href="{U_MEMBER_PM}" class="small_link" title="{L_NBR_PM}"><img src="../templates/{THEME}/images/{IMG_PM}" class="valign_middle" alt="" /></a></p>			
		# IF C_ADMIN_AUTH # 
		<p style="margin:0"><a href="../admin/admin_index.php" class="small_link" title="{L_ADMIN_PANEL}"><img src="../templates/{THEME}/images/admin/ranks_mini.png" alt="" class="valign_middle" /></a></p>
		# ENDIF #
		# IF C_MODO_AUTH # 
		<p><a href="../member/moderation_panel.php" class="small_link" title="{L_MODO_PANEL}"><img src="../templates/{THEME}/images/admin/modo_mini.png" alt="" class="valign_middle" /></a></p>
		# ENDIF #
	# END ENDIF #
		<hr style="width:50%;margin:auto;" />
		<p style="margin:0;margin-top:5px;"><img onclick="new Effect.ScrollTo('scroll_bottom_page',{duration:1.2}); return false;" style="cursor:pointer;" src="../templates/{THEME}/images/bottom.png" alt="" /></p>
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
	
	
	# IF C_START_LEFT #
	<div id="left_menu">
		<p class="text_center">
			<a href="../download/download-2-52+phpboost-2-0.php"><img src="../templates/{THEME}/images/theme/download_phpboost.png" alt="" /></a>
		</p>		
		<hr style="width:90%;margin:auto" />
		{MODULES_MINI_LEFT_CONTENT}
	</div>
	# ENDIF #
	
	# IF C_START_RIGHT #
	<div id="right_menu">
		{MODULES_MINI_RIGHT_CONTENT}
	</div>
	# ENDIF #
	
	<div id="main">
		<div id="links">
			&nbsp;&nbsp;<a class="small_link" href="{START_PAGE}" title="{L_INDEX}">{L_INDEX}</a>
			# START link_bread_crumb #
			<img src="../templates/{THEME}/images/breadcrumb.png" alt="" class="valign_middle" /> <a class="small_link" href="{link_bread_crumb.URL}" title="{link_bread_crumb.TITLE}">{link_bread_crumb.TITLE}</a>
			# END link_bread_crumb #
		</div>	
		<div id="top_contents">
			{MODULES_MINI_TOPCENTRAL_CONTENT}
		</div>
