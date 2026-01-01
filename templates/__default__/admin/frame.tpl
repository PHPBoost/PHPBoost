<!DOCTYPE html>
<html lang="{@common.xml.lang}">
	<head>
		<meta charset="utf-8" />
		<title>{TITLE}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Theme CSS -->
		# IF C_CSS_CACHE_ENABLED #
			<link rel="stylesheet" href="${CSSCacheManager::get_css_path('/templates/__default__/dashboard/@import.css')}" type="text/css" media="screen, print" />
		# ELSE #
			<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/__default__/dashboard/@import.css" type="text/css" media="screen, print" />
		# ENDIF #

		<!-- Modules CSS -->
		{MODULES_CSS}

		# IF C_FAVICON #
			<link rel="shortcut icon" href="{FAVICON}" type="{FAVICON_TYPE}" />
		# ENDIF #

		# INCLUDE JS_TOP #

	</head>
	<body>
		<div id="push-container">
			# INCLUDE BODY #
		</div>

		<script>
			jQuery(window).ready(function() {
				jQuery('#preloader-status').animate({opacity: 0}, 100).animate({height: 0}, 200, function(){
					jQuery('#preloader-status').css('visibility', 'hidden');
				});
			});
		</script>
		# INCLUDE JS_BOTTOM #
	</body>
</html>
