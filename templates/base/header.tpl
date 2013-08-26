<!DOCTYPE html>
<html lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{TITLE}</title>
		<meta charset="iso-8859-1" />
		<meta name="description" content="{SITE_DESCRIPTION}" />
		<meta name="keywords" content="{SITE_KEYWORD}" />
		<meta name="generator" content="PHPBoost {PHPBOOST_VERSION}" />
		<!-- Default CSS -->
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen, print, handheld" />
		
		<!-- Theme CSS -->
		# IF C_CSS_CACHE_ENABLED #
		<link rel="stylesheet" href="{PATH_TO_ROOT}/kernel/css_cache.php?name=theme-{THEME}&amp;files=/templates/{THEME}/theme/design.css;/templates/{THEME}/theme/global.css;/templates/{THEME}/theme/generic.css;/templates/{THEME}/theme/content.css" type="text/css" media="screen, print, handheld" />
		# ELSE #
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/content.css" type="text/css" media="screen, print, handheld" />
		# ENDIF #
		
		<!-- Modules CSS -->
		{MODULES_CSS}

		# IF C_FAVICON #
		<link rel="shortcut icon" href="{FAVICON}" type="{FAVICON_TYPE}" />
		# ENDIF #
		
		<script type="text/javascript">
		<!--
			var PATH_TO_ROOT = "{PATH_TO_ROOT}";
			var TOKEN = "{TOKEN}";
			var THEME = "{THEME}";
			var LANG = "{LANG}";
		-->
		</script>
		
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/prototype.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/global.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/lightbox/lightbox.js"></script>
		
		# IF C_HEADER_LOGO #
			<style type="text/css">
				div#logo {
	   				background: url('{HEADER_LOGO}') no-repeat;
				}
			</style>
		# ENDIF #
	</head>

	<body itemscope="itemscope" itemtype="http://schema.org/WebPage">
	
		# INCLUDE MAINTAIN #
		
		<div id="global">
			<header>
				<div id="top_header">
					<div id="logo"></div>
					<div id="site_name"><a href="{PATH_TO_ROOT}/">{SITE_NAME}</a></div>
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
			</header>
			
			# IF C_COMPTEUR #
			<div id="compteur">
				<span class="text_strong">{L_VISIT}:</span> {COMPTEUR_TOTAL}
				<br />
				<span class="text_strong">{L_TODAY}:</span> {COMPTEUR_DAY}
			</div>
			# ENDIF #
			
			# IF C_MENUS_LEFT_CONTENT #
			<aside id="menu_left">
				{MENUS_LEFT_CONTENT}
			</aside>
			# ENDIF #
			
			# IF C_MENUS_RIGHT_CONTENT #
			<aside id="menu_right">
				{MENUS_RIGHT_CONTENT}
			</aside>
			# ENDIF #
			
			<section id="main">
				# IF C_MENUS_TOPCENTRAL_CONTENT #
				<div id="top_contents">
					{MENUS_TOPCENTRAL_CONTENT}
				</div>
				<div class="spacer"></div>
				# ENDIF #
				<div id="main_content" itemprop="mainContentOfPage">
					<nav id="breadcrumb" itemprop="breadcrumb">
						<a class="small_link" href="{START_PAGE}" title="{L_INDEX}">{L_INDEX}</a>
						# START link_bread_crumb #
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/breadcrumb.png" alt="" class="valign_middle" /> <a class="small_link" href="{link_bread_crumb.URL}" title="{link_bread_crumb.TITLE}">{link_bread_crumb.TITLE}</a>
						# END link_bread_crumb #
					</nav>