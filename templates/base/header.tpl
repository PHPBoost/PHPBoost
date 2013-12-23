<!DOCTYPE html>
<html lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{TITLE}</title>
		<meta charset="iso-8859-1" />
		<meta name="description" content="{SITE_DESCRIPTION}" />
		<meta name="keywords" content="{SITE_KEYWORD}" />
		<meta name="generator" content="PHPBoost {PHPBOOST_VERSION}" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- Theme CSS -->
		# IF C_CSS_CACHE_ENABLED #
		<link rel="stylesheet" href="{PATH_TO_ROOT}/kernel/css_cache.php?name=theme-{THEME}&amp;files=/templates/default/theme/default.css;/kernel/lib/css/font-awesome/css/font-awesome.css;/templates/{THEME}/theme/design.css;/templates/{THEME}/theme/global.css;/templates/{THEME}/theme/content.css" type="text/css" media="screen, print, handheld" />
		# ELSE #
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/kernel/lib/css/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/content.css" type="text/css" media="screen, print, handheld" />
		# ENDIF #
		
		<!-- Modules CSS -->
		{MODULES_CSS}

		# IF C_FAVICON #
		<link rel="shortcut icon" href="{FAVICON}" type="{FAVICON_TYPE}" />
		# ENDIF #
		
		# INCLUDE JAVASCRIPT #
		
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
			<header id="header">
				<div id="top-header">
					<div id="logo"></div>
					<div id="site-name"><a href="{PATH_TO_ROOT}/">{SITE_NAME}</a></div>
					# IF C_MENUS_HEADER_CONTENT #
					{MENUS_HEADER_CONTENT}
					# ENDIF #
				</div>
				<div id="sub-header">
					# IF C_MENUS_SUB_HEADER_CONTENT #
					{MENUS_SUB_HEADER_CONTENT}
					# ENDIF #
				</div>
				<div class="spacer"></div>
			</header>
			
			# IF C_COMPTEUR #
			<div id="compteur">
				<span class="text-strong">{L_VISIT}:</span> {COMPTEUR_TOTAL}
				<br />
				<span class="text-strong">{L_TODAY}:</span> {COMPTEUR_DAY}
			</div>
			# ENDIF #
			
			# IF C_MENUS_LEFT_CONTENT #
			<aside id="menu-left">
				{MENUS_LEFT_CONTENT}
			</aside>
			# ENDIF #
			
			# IF C_MENUS_RIGHT_CONTENT #
			<aside id="menu-right">
				{MENUS_RIGHT_CONTENT}
			</aside>
			# ENDIF #
			
			<div id="main" role="main">
				# IF C_MENUS_TOPCENTRAL_CONTENT #
				<div id="top-contents">
					{MENUS_TOPCENTRAL_CONTENT}
				</div>
				<div class="spacer"></div>
				# ENDIF #
				<div id="main-content" itemprop="mainContentOfPage">
					# INCLUDE ACTIONS_MENU #
					<nav id="breadcrumb" itemprop="breadcrumb">
						<ol>
							<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
								<a href="{START_PAGE}" title="{L_INDEX}" itemprop="url">
									<span itemprop="title">{L_INDEX}</span>
								</a>
							</li>
							# START link_bread_crumb #
								<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" # IF link_bread_crumb.C_CURRENT # class="current" # ENDIF #>
									# IF link_bread_crumb.C_CURRENT #
									<span itemprop="title">{link_bread_crumb.TITLE}</span>
									# ELSE #
									<a href="{link_bread_crumb.URL}" title="{link_bread_crumb.TITLE}" itemprop="url">
										<span itemprop="title">{link_bread_crumb.TITLE}</span>
									</a>
									# ENDIF #
								</li>
							# END link_bread_crumb #
						</ol>
					</nav>