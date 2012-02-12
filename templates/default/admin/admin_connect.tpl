<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{SITE_NAME} :: {TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />
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
		
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/scriptaculous/prototype.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/global.js"></script>	
	</head>
	<body>
		<script type="text/javascript">
		<!--
		function check_connect(){
			if(document.getElementById('login').value == "") {
				alert("{L_REQUIRE_PSEUDO}");
				return false;
			}
			if(document.getElementById('password').value == "") {
				alert("{L_REQUIRE_PASSWORD}");
				return false;
			}
			return true;
		}

		-->
		</script>
		
		<form action="{PATH_TO_ROOT}/admin/admin_index.php?token={TOKEN}" method="post" onsubmit="return check_connect();" class="fieldset_content" style="width:550px;margin:auto;margin-top:10%">
			<fieldset>
				<legend>{L_ADMIN}</legend>
				<dl>
					<dt><label for="login">{L_PSEUDO}</label></dt>
					<dd><label><input size="15" type="text" class="text" id="login" name="login" maxlength="25" /></label></dd>
				</dl>
				<dl>
					<dt><label for="password">{L_PASSWORD}</label></dt>
					<dd><label><input size="15" type="password" id="password" name="password" class="text" maxlength="30" /></label></dd>
				</dl>
				# START unlock #
				<dl>
					<dt><label for="unlock">{L_UNLOCK}</label></dt>
					<dd><label><input size="15" type="password" name="unlock" id="unlock" class="text" maxlength="30" /></label></dd>
				</dl>
				# END unlock #
				<dl>
					<dt><label for="auto">{L_AUTOCONNECT}</label></dt>
					<dd><label><input type="checkbox" checked="checked" name="auto" id="auto" /></label></dd>
				</dl>
			</fieldset>			
			<fieldset class="fieldset_submit">
				<legend>{L_DELETE}</legend>
				<input type="submit" name="connect" value="{L_CONNECT}" class="submit" />		
			</fieldset>	
		</form>
	</body>
</html>
