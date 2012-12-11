<script type="text/javascript">
<!--
function CheckForm() {
	if (document.getElementById('name').value == '') {
		document.getElementById('name').select();
		window.alert({JL_REQUIRE_TITLE});
		return false;
	}
	if (document.getElementById('contents').value == '') {
		document.getElementById('contents').select();
		window.alert({JL_REQUIRE_TEXT});
		return false;
	}
	return true;
}
-->
</script>
<div id="admin_contents">
	<form action="content.php" method="post" onsubmit="return CheckForm();" class="fieldset_content">
		<fieldset> 
			<legend>{L_ACTION_MENUS}</legend>
            <dl>
                <dt><label for="name">* {L_NAME}</label></dt>
                <dd><input type="text" size="18" name="name" id="name" class="text" value="{NAME}" /></dd>
            </dl>
            <dl>
                <dt><label for="name">* {L_DISPLAY_TITLE}</label></dt>
                <dd><input type="checkbox" name="display_title[]" value="display_title" {DISPLAY_TITLE_CHECKED} /></dd>
            </dl>
			<dl>
				<dt><label for="location">* {L_LOCATION}</label></dt>
				<dd><select name="location" id="location">{LOCATIONS}</select></dd>
			</dl>
			{KERNEL_EDITOR}
			<textarea rows="15" cols="5" id="contents" name="contents">{CONTENTS}</textarea>
			<br />
			<dl>
				<dt><label for="activ">{L_STATUS}</label></dt>
				<dd>
					<select name="activ" id="activ">
					   # IF C_ENABLED #
							<option value="1" selected="selected">{L_ENABLED}</option>
							<option value="0">{L_DISABLED}</option>
						# ELSE #
                            <option value="1">{L_ENABLED}</option>
                            <option value="0" selected="selected">{L_DISABLED}</option>
						# ENDIF #					
					</select>
				</dd>
			</dl>
			<dl>
				<dt>{L_AUTHS}</dt>
				<dd>{AUTH_MENUS}</dd>
			</dl>
		</fieldset>		

		# INCLUDE filters #
	    
		<fieldset class="fieldset_submit">
			<legend>{L_ACTION}</legend>
			<input type="hidden" name="action" value="{ACTION}" />
			<input type="hidden" name="id" value="{IDMENU}" />
			<input type="submit" name="valid" value="{L_ACTION}" class="submit" />
			<input type="hidden" name="token" value="{TOKEN}" />			
		</fieldset>	
	</form>
</div>
