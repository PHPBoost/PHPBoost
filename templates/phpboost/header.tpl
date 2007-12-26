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
		<link rel="alternate" type="application/rss+xml" href="../index/rss.php" title="RSS {SITE_NAME}" />
		<link rel="alternate" type="application/rss+xml" href="../forum/rss.php" title="RSS Forum" />
		<script type="text/javascript" src="../templates/{THEME}/images/global.js"></script>	
	</head>
	<body>

	<div id="header">
		<img src="../templates/{THEME}/images/header_boost.jpg" alt="PHPBoost" />
	</div>	
	
	<div id="sub_header">						
		<div id="sub_header_left">
			<h1 style="display:none;font-size:9px;">{SITE_NAME}</h1>
			<a href="http://www.phpboost.com" title="Accueil PHPBoost" class="button">Accueil</a>
			<a href="http://www.phpboost.com/forum/index.php" title="Forum PHPBoost" class="button">Forum</a>
			<a href="http://www.phpboost.com/wiki/wiki.php" title="Documentation PHPBoost" class="button">Documentation</a>
			<a href="http://www.phpboost.com/download/download-2-52+phpboost-2-0.php" title="Télécharger PHPBoost" class="button">Télécharger</a>
			<a href="http://www.phpboost.com/phpboost/modules.php" title="Modules PHPBoost" class="button">Modules</a>
		</div>
		<div id="sub_header_right"></div>
	</div>
	
	# START compteur #
	<div id="compteur">					
		<span class="text_strong">{L_VISIT}:</span> {compteur.COMPTEUR_TOTAL}
		<br />
		<span class="text_strong">{L_TODAY}:</span> {compteur.COMPTEUR_DAY}
	</div>
	# END compteur #
	
	# START start_left #
	<div id="left_menu">
	# END start_left #
	