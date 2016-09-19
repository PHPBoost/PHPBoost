<!DOCTYPE html>
<html lang="{L_XML_LANGUAGE}">
	<head>
		<title>{TITLE}</title>
		<meta charset="windows-1252" />
		# IF C_DESCRIPTION #<meta name="description" content="{SITE_DESCRIPTION}" /># ENDIF #
		<meta name="generator" content="PHPBoost" />
		# IF C_CANONICAL_URL #<link rel="canonical" href="{U_CANONICAL}" /># ENDIF #
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

		<!-- Theme CSS -->
		# IF C_CSS_CACHE_ENABLED #
		<link rel="stylesheet" href="${CSSCacheManager::get_css_path('/templates/default/theme/default.css;/kernel/lib/css/font-awesome/css/font-awesome.css;/templates/{THEME}/theme/design.css;/templates/{THEME}/theme/content.css;/templates/{THEME}/theme/table.css;/templates/{THEME}/theme/form.css;/templates/{THEME}/theme/global.css;/templates/{THEME}/theme/cssmenu.css')}" type="text/css" media="screen, print" />
		# ELSE #
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen, print" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/kernel/lib/css/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/content.css" type="text/css" media="screen, print" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/table.css" type="text/css" media="screen, print" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/form.css" type="text/css" media="screen, print" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/global.css" type="text/css" media="screen, print" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/cssmenu.css" type="text/css" media="screen" />
		# ENDIF #
		# IF C_CSS_LOGIN_DISPLAYED #<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/login.css" type="text/css" media="screen" /># ENDIF #

		<!-- Modules CSS -->
		{MODULES_CSS}

		# IF C_FAVICON #
		<link rel="shortcut icon" href="{FAVICON}" type="{FAVICON_TYPE}" />
		# ENDIF #

		# INCLUDE JS_TOP #
	</head>

	<body itemscope="itemscope" itemtype="http://schema.org/WebPage">
		# INCLUDE BODY #
		# INCLUDE JS_BOTTOM #
	</body>
</html>
