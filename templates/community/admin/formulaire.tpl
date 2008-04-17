<script type="text/javascript">
<!--
function check_conect(){
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
			
<form action="../admin/admin_index.php" method="post" onsubmit="return check_conect();" class="fieldset_content">
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
