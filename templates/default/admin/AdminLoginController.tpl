<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{@xml_lang}" >
	<head>
		<title>${escape(SITE_NAME)} :: {@connect}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_global.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_content.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/admin_generic.css" type="text/css" media="screen" />
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
				alert('{@require_pseudo}');
				return false;
			}
			if(document.getElementById('password').value == "") {
				alert('{@require_password}');
				return false;
			}
			return true;
		}

		-->
		</script>
		
		<form action="{PATH_TO_ROOT}/admin/admin_index.php?token={TOKEN}" method="post" onsubmit="return check_connect();" class="fieldset_content" style="width:550px;margin:auto;margin-top:10%">
			<fieldset>
				<legend>{@admin}</legend>
				<div class="form-element">
					<label for="login">{@pseudo}</label>
					<div class="form-field"><label><input size="15" type="text" id="login" name="login" maxlength="25"></label></div>
				</div>
				<div class="form-element">
					<label for="password">{@password}</label>
					<div class="form-field"><label><input size="15" type="password" id="password" name="password" maxlength="30"></label></div>
				</div>
				# IF C_UNLOCK #
				<div class="form-element">
					<label for="unlock">{@unlock_admin_panel}</label>
					<div class="form-field"><label><input size="15" type="password" name="unlock" id="unlock" maxlength="30"></label></div>
				</div>
				# ENDIF #
				<div class="form-element">
					<label for="auto">{@autoconnect}</label>
					<div class="form-field"><label><input type="checkbox" checked="checked" name="auto" id="auto"></label></div>
				</div>
			</fieldset>
			<input type="hidden" name="redirect" value="{REWRITED_SCRIPT}">
			<fieldset class="fieldset_submit">
				<button type="submit" name="connect" value="true">{@connect}</button>
			</fieldset>	
		</form>
	</body>
</html>
