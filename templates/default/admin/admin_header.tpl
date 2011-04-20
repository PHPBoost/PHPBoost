<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{SITE_NAME} :: {TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />
		<!-- Default CSS -->
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_default.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen" />

		<!-- CSS -->
		{CSS}
		
		<link rel="shortcut icon" href="{PATH_TO_ROOT}/favicon.ico" type="image/x-icon" />
		
		<script type="text/javascript">
		<!--
			var PATH_TO_ROOT = "{PATH_TO_ROOT}";
			var TOKEN = "{TOKEN}";
			var THEME = "{THEME}";
		-->
		</script>
		# IF C_BBCODE_TINYMCE_MODE # <script language="javascript" type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/tinymce/tiny_mce.js"></script> # ENDIF #
		
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/prototype.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/global.js"></script>	

	</head>
	<body>
	<div id="global">
		<div id="header_admin_container">
			<div id="header_admin">&nbsp;</div>
			<div id="sub_header_admin">
				<div class="dynamic_menu">
					# INCLUDE subheader_menu #
				</div>
				<div id="admin_extend_link">
					<a href="{PATH_TO_ROOT}/admin/admin_extend.php" class="admin_extend_link">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/extendfield_mini.png" class="valign_middle" alt="" /> {L_EXTEND_MENU}
					</a>
				</div>
				<div class="spacer"></div>
			</div>
		</div>
		
		<div id="admin_main">
			