<!DOCTYPE html>
<html lang="{L_XML_LANGUAGE}">
	<head>
		<meta charset="utf-8" />
		<title>{TITLE}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Theme CSS -->
		# IF C_CSS_CACHE_ENABLED #
			<link rel="stylesheet" href="${CSSCacheManager::get_css_path('/templates/default/theme/default.css;/kernel/lib/css/font-awesome/css/font-awesome.css;/kernel/lib/css/font-awesome-animation/css/font-awesome-animation.css;/templates/default/theme/admin_lightcase.css;/templates/default/theme/admin_design.css;/templates/default/theme/admin_content.css;/templates/default/theme/admin_cssmenu.css;/templates/default/theme/admin_menus.css;/templates/default/theme/admin_table.css;/templates/default/theme/admin_form.css;/templates/default/theme/admin_global.css;/templates/default/theme/admin_plugins.css;/templates/default/theme/admin_colors.css')}" type="text/css" media="screen, print" />
		# ELSE #
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen, print" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/kernel/lib/css/font-awesome/css/font-awesome.css" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/kernel/lib/css/font-awesome-animation/css/font-awesome-animation.css" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_lightcase.css" type="text/css" media="screen" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_design.css" type="text/css" media="screen" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_content.css" type="text/css" media="screen, print" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_cssmenu.css" type="text/css" media="screen, print" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_menus.css" type="text/css" media="screen" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_table.css" type="text/css" media="screen, print" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_form.css" type="text/css" media="screen, print" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_global.css" type="text/css" media="screen, print" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_plugins.css" type="text/css" media="screen, print" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/cell-flex.css" type="text/css" media="screen, print" />
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_colors.css" type="text/css" media="screen, print" />
		# ENDIF #

		<!-- Modules CSS -->
		{MODULES_CSS}

		# IF C_FAVICON #
			<link rel="shortcut icon" href="{FAVICON}" type="{FAVICON_TYPE}" />
		# ENDIF #

		# INCLUDE JS_TOP #

	</head>
	<body>
		# INCLUDE BODY #
		# INCLUDE JS_BOTTOM #
	</body>
</html>
