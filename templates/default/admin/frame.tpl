<!DOCTYPE html>
<html lang="{L_XML_LANGUAGE}">
	<head>
		<title>{TITLE}</title>
		<meta charset=windows-1252" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

		<!-- Theme CSS -->
		# IF C_CSS_CACHE_ENABLED #
		<link rel="stylesheet" href="${CSSCacheManager::get_css_path('/templates/default/theme/default.css;/kernel/lib/css/font-awesome/css/font-awesome.css;/templates/default/theme/admin_design.css;/templates/default/theme/admin_global.css;/templates/default/theme/admin_content.css')}" type="text/css" media="screen, print" />
		# ELSE #
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen, print" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/kernel/lib/css/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_global.css" type="text/css" media="screen, print" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_content.css" type="text/css" media="screen, print" />
		# ENDIF #
		
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
		
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
		<script>
		<!--
			function open_submenu(myid)
			{
				jQuery('#' + myid).toggleClass('active');
			}
		-->
		</script>
	</body>
</html>