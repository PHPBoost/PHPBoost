<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />

		<!-- Theme CSS -->
		# IF C_CSS_CACHE_ENABLED #
		<link rel="stylesheet" href="{PATH_TO_ROOT}/kernel/css_cache.php?name=admin-theme-{THEME}&files=
		/templates/default/theme/default.css;
		/kernel/lib/css/font-awesome/css/font-awesome.css;
		/templates/default/theme/admin_design.css;
		/templates/default/theme/admin_global.css;
		/templates/default/theme/admin_content.css" type="text/css" media="screen, print, handheld" />
		# ELSE #
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/kernel/lib/css/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_global.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_content.css" type="text/css" media="screen" />
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
	</head>
	<body>
	
	# IF C_HEADER_LOGO #
		<style type="text/css">
			div#logo {
   				background: url('{HEADER_LOGO}') no-repeat;
			}
		</style>
	# ENDIF #
	
	<div id="global">
		<header id="header-admin-container">
			<div id="header-admin">
				<div id="logo"></div>
				<div id="site-name">
					<a href="{PATH_TO_ROOT}/">{SITE_NAME}</a>
					<span id="site-name-desc">{SITE_NAME_DESC}</span>
				</div>
			</div>
			<div id="sub-header-admin">
				<nav class="dynamic-menu">
					# INCLUDE subheader_menu #
				</nav>
				<div id="admin-extend-link">
					<a href="{PATH_TO_ROOT}/admin/admin_extend.php">
						<i class="fa fa-plus"></i> {L_EXTEND_MENU}
					</a>
				</div>
				<div class="spacer"></div>
			</div>
		</header>
		
		<div id="admin-main">
			