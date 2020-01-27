<!DOCTYPE html>
<html lang="{L_XML_LANGUAGE}">
	<head>
		<meta charset="utf-8" />
		<title>{TITLE}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Theme CSS -->
		# IF C_CSS_CACHE_ENABLED #
			<link rel="stylesheet" href="${CSSCacheManager::get_css_path('/templates/default/theme/@import.css')}" type="text/css" media="screen, print" />
		# ELSE #
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/@import.css" type="text/css" media="screen, print" />
		# ENDIF #

		<!-- Modules CSS -->
		{MODULES_CSS}

		# IF C_FAVICON #
			<link rel="shortcut icon" href="{FAVICON}" type="{FAVICON_TYPE}" />
		# ENDIF #

		# INCLUDE JS_TOP #

	</head>
	<body>
		<div id="push-container" class="body-wrapper">
			# INCLUDE BODY #
		</div>
		# INCLUDE JS_BOTTOM #
	</body>
</html>
