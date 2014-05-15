<script>
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

<form action="{PATH_TO_ROOT}/admin/admin_index.php?token={TOKEN}" method="post" onsubmit="return check_connect();" class="fieldset-content" style="width:550px;margin:auto;margin-top:180px;">
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
	<fieldset class="fieldset-submit">
		<button type="submit" class="submit" name="connect" value="true">{@connect}</button>
	</fieldset>
</form>