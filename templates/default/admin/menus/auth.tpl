<div id="admin-contents">
	<form action="auth.php" method="post" class="fieldset-content">
		<fieldset> 
			<legend>{L_ACTION_MENUS}</legend>
			<div class="form-element">
				<label>{L_NAME}</label>
				<div class="form-field"><label>{NAME}</label></div>
			</div>
			<div class="form-element">
				<label for="location">{L_LOCATION}</label>
				<div class="form-field"><select name="location" id="location">{LOCATIONS}</select></div>
			</div>
			<div class="form-element">
				<label for="activ">{L_STATUS}</label>
				<div class="form-field"><label>
					<select name="activ" id="activ">
					   # IF C_ENABLED #
							<option value="1" selected="selected">{L_ENABLED}</option>
							<option value="0">{L_DISABLED}</option>
						# ELSE #
							<option value="1">{L_ENABLED}</option>
							<option value="0" selected="selected">{L_DISABLED}</option>
						# ENDIF #
					</select>
				</label></div>
			</div>
			<div class="form-element">
				<label for="auth">{L_AUTHS}</label>
				<div class="form-field"><label>{AUTH_MENUS}</label></div>
			</div>
		</fieldset>   
		
		# INCLUDE filters #  

		<fieldset class="fieldset-submit">
			<legend>{L_ACTION}</legend>
			<input type="hidden" name="action" value="{ACTION}">
			<input type="hidden" name="id" value="{IDMENU}">
			<button type="submit" class="submit" name="valid" value="true">{L_ACTION}</button>
			<button type="reset" value="true">{L_RESET}</button>
			<input type="hidden" name="token" value="{TOKEN}">
		</fieldset> 
	</form>
</div>
