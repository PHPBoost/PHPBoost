#{resources('login')}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>${escape(SITE_NAME)} :: {@login.form}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Language" content="{@xml_lang}" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/global.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/content.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/generic.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/bbcode.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/admin.css" type="text/css" media="screen" />
		<link rel="shortcut icon" href="{PATH_TO_ROOT}/favicon.ico" type="image/x-icon" />

		<script type="text/javascript">
		<!--
			var PATH_TO_ROOT = "{PATH_TO_ROOT}";
			var TOKEN = "{TOKEN}";
		-->
		</script>
		<style>
			form#loginform { width:550px;margin:auto;margin-top:10% }
			span.errorMsg { font-weight:bold;color:#cc0000;margin:auto; }
		</style>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/prototype.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/global.js"></script>
	</head>
	<body>
		# INCLUDE LOGIN_FORM #
	</body>
</html>
