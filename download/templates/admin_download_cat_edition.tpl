		# INCLUDE admin_download_menu #
		
		<div id="admin-contents">
			<script>
			<!--
				function check_form(){
					if(document.getElementById('name').value == "")
					{
						alert("{L_REQUIRE_TITLE}");
						return false;
				    }

					return true;
				}

				function change_icon(img_path)
				{
					if( document.getElementById('icon_img') )
						document.getElementById('icon_img').innerHTML = '<img src="' + img_path + '" alt="" class="valign-middle" />';
				}
				
				var global_auth = {JS_SPECIAL_AUTH};
				function change_status_global_auth()
				{
					if( global_auth )
						hide_div("hide_special_auth");
					else
						show_div("hide_special_auth");
					global_auth = !global_auth;
				}
			-->
			</script>
			<form action="admin_download_cat.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset-content">
				<fieldset>
					<legend>{L_CATEGORY}</legend>
					<p>{L_REQUIRED_FIELDS}</p>
					<div class="form-element">
						<label for="name">* {L_NAME}</label>
						<div class="form-field">
							<input type="text" size="65" maxlength="100" id="name" name="name" value="{NAME}">
						</div>
					</div>
					<div class="form-element">
						<label for="id_parent">{L_LOCATION}</label>
						<div class="form-field">
							{CATEGORIES_TREE}
						</div>
					</div>
					<div class="form-element">
						<label for="alt_image">
							{L_IMAGE}
							<span class="field-description">{L_EXPLAIN_IMAGE}</span>
						</label>
						<div class="form-field">
							<select name="image" onchange="change_icon(this.options[this.selectedIndex].value)" onclick="change_icon(this.options[this.selectedIndex].value)">
								{IMG_LIST}
							</select>
							<input type="text" name="alt_image" value="{IMG_PATH}" onblur="if( this.value != '' )change_icon(this.value)">
							<span id="icon_img">{IMG_ICON}</span>
						</div>
					</div>
					<div class="form-element">
						<label for="visible_cat">{L_VISIBLE}</label>
						<div class="form-field">
							<input type="checkbox" name="visible_cat" id="visible_cat" {VISIBLE_CHECKED}>
						</div>
					</div>
					<div class="form-element-textarea">
						<label for="contents">{L_DESCRIPTION}</label>
						{KERNEL_EDITOR}
						<textarea id="contents" rows="15" cols="40" name="description">{DESCRIPTION}</textarea>
					</div>
				</fieldset>
				<fieldset>
					<legend>{L_SPECIAL_AUTH}</legend>
					<div class="form-element">
						<label for="special_auth">
							{L_SPECIAL_AUTH}
							<span class="field-description">{L_SPECIAL_AUTH_EXPLAIN}</span>
						</label>
						<div class="form-field">
							<input type="checkbox" name="special_auth" id="special_auth" onclick="javascript: change_status_global_auth();" {SPECIAL_CHECKED}>
						</div>					
					</div>
					<div id="hide_special_auth" style="display:{DISPLAY_SPECIAL_AUTH};">
						<div class="form-element">
							<label>{L_READ_AUTH}</label>
							<div class="form-field">
								{READ_AUTH}
							</div>					
						</div>
						<div class="form-element">
							<label>{L_WRITE_AUTH}</label>
							<div class="form-field">
								{WRITE_AUTH}
							</div>					
						</div>
						<div class="form-element">
							<label>{L_CONTRIBUTION_AUTH}</label>
							<div class="form-field">
								{CONTRIBUTION_AUTH}
							</div>					
						</div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="idcat" value="{IDCAT}">
					<button type="submit" name="submit" value="true">{L_SUBMIT}</button>
					<button type="button" name="preview" onclick="XMLHttpRequest_preview();" value="true">{L_PREVIEW}</button>
					<button type="reset" value="true">{L_RESET}</button>				
				</fieldset>
			</form>
		</div>