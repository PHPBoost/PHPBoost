<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{SITE_NAME} : {TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="description" content="{SITE_DESCRIPTION} {TITLE}" />
		<meta name="keywords" content="{SITE_KEYWORD}" />
		<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />

		<!-- Theme CSS -->
		# IF C_CSS_CACHE_ENABLED #
		<link rel="stylesheet" href="{PATH_TO_ROOT}/kernel/css_cache.php?name=theme-{THEME}&files=
		/templates/{THEME}/theme/design.css;
		/templates/{THEME}/theme/global.css;
		/templates/{THEME}/theme/generic.css;
		/templates/{THEME}/theme/content.css;
		/templates/{THEME}/theme/bbcode.css" type="text/css" media="screen, print, handheld" />
		# ELSE #
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/content.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/bbcode.css" type="text/css" media="screen, print, handheld" />
		# ENDIF #

		# IF C_FAVICON #
		<link rel="shortcut icon" href="{FAVICON}" type="{FAVICON_TYPE}" />
		# ENDIF #
		
		<script type="text/javascript">
		<!--
			var PATH_TO_ROOT = "{PATH_TO_ROOT}";
			var TOKEN = "{TOKEN}";
			var THEME = "{THEME}";
		-->
		</script>
	</head>
	<body>
	
	# IF C_HEADER_LOGO #
		<style type="text/css">
			div#header_container {
   				background: url('{HEADER_LOGO}') no-repeat;
			}
		</style>
	# ENDIF #

<div id="global">
	<div id="header_container">
		<div id="header">
		</div>
		<div class="spacer"></div>
	</div>
	<div id="main">
		<div id="main_content">