#{resources('admin-login')}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>Administration</title>
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

		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/prototype.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/global.js"></script>
	</head>
	<body>
		<script type="text/javascript">
		<!--
		function check_connect(){
			if(document.getElementById('login').value == "") {
				alert(${escapejs(@require_pseudo)});
				return false;
			}
			if(document.getElementById('password').value == "") {
				alert({${escapejs(@require_password)});
				return false;
			}
			return true;
		}

		-->
		</script>

		<form action="{POST_URL}" method="post" onsubmit="return check_connect();" class="fieldset_content" style="width:550px;margin:auto;margin-top:10%">
			<fieldset>
				<legend>{@admin}</legend>
				<dl>
					<dt><label for="login">{@pseudo}</label></dt>
					<dd><label><input size="15" type="text" class="text" id="login" name="login" maxlength="25" /></label></dd>
				</dl>
				<dl>
					<dt><label for="password">{@password}</label></dt>
					<dd><label><input size="15" type="password" id="password" name="password" class="text" maxlength="30" /></label></dd>
				</dl>
				# IF C_UNLOCK #
				<dl>
					<dt><label for="unlock">{@unlock_admin_panel}</label></dt>
					<dd><label><input size="15" type="password" name="unlock" id="unlock" class="text" maxlength="30" /></label></dd>
				</dl>
				# ENDIF #
				<dl>
					<dt><label for="auto">{@autoconnect}</label></dt>
					<dd><label><input type="checkbox" checked="checked" name="auto" id="auto" /></label></dd>
				</dl>
			</fieldset>
			<fieldset class="fieldset_submit">
				<input type="submit" name="connect" value="{@connect}" class="submit" />
			</fieldset>
		</form>
	</body>
</html>
