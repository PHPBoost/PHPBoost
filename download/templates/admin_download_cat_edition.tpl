 # INCLUDE admin_download_menu #

<div id="admin_contents">
	<script type="text/javascript">
			<!--
				function check_form(){
					if(document.getElementById('name').value == "")
					{
						alert("{L_REQUIRE_TITLE}");
						return false;
				    }

					return true;
				}

				function Confirm() {
				return confirm("{L_DEL_ENTRY}");
				}
				function change_icon(img_path)
				{
					if( document.getElementById('icon_img') )
						document.getElementById('icon_img').innerHTML = '<img src="' + img_path + '" alt="" class="valign_middle" />';
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
	<form action="admin_download_cat.php?token={TOKEN}" method="post"
		onsubmit="return check_form();" class="fieldset_content">
		<fieldset>
			<legend>{L_CATEGORY}</legend>
			<p>{L_REQUIRED_FIELDS}</p>
			<dl>
				<dt>
					<label for="name"> * {L_NAME} </label>
				</dt>
				<dd>
					<input type="text" size="65" maxlength="100" id="name" name="name"
						value="{NAME}" class="text" />
				</dd>
			</dl>
			<dl>
				<dt>
					<label for="id_parent"> {L_LOCATION} </label>
				</dt>
				<dd>{CATEGORIES_TREE}</dd>
			</dl>
			<dl>
				<dt>
					<label for="alt_image"> {L_IMAGE} <br /> <span class="text_small">{L_EXPLAIN_IMAGE}</span>
					</label>
				</dt>
				<dd>
					<select name="image"
						onchange="change_icon(this.options[this.selectedIndex].value)"
						onclick="change_icon(this.options[this.selectedIndex].value)">
						{IMG_LIST}
					</select> <span id="icon_img">{IMG_ICON}</span> <input type="text"
						class="text" style="margin-left: 50px;" name="alt_image"
						value="{IMG_PATH}"
						onblur="if( this.value != '' )change_icon(this.value)" />
				</dd>
			</dl>
			<dl>
				<dt>
					<label for="visible_cat"> {L_VISIBLE} </label>
				</dt>
				<dd>
					<input type="checkbox" name="visible_cat" id="visible_cat" {VISIBLE_CHECKED} />
				</dd>
			</dl>
			<label for="contents"> {L_DESCRIPTION} </label> {KERNEL_EDITOR}
			<textarea id="contents" rows="15" cols="40" name="description">{DESCRIPTION}</textarea>
		</fieldset>
		<fieldset>
			<legend> {L_SPECIAL_AUTH} </legend>
			<dl>
				<dt>
					<label for="special_auth">{L_SPECIAL_AUTH}</label> <br /> <span
						class="text_small">{L_SPECIAL_AUTH_EXPLAIN}</span>
				</dt>
				<dd>
					<input type="checkbox" name="special_auth" id="special_auth"
						onclick="javascript: change_status_global_auth();" {SPECIAL_CHECKED} />
				</dd>
			</dl>
			<div id="hide_special_auth" style="display: { DISPLAY_SPECIAL_AUTH">
				<dl>
					<dt>
						<label> {L_READ_AUTH} </label>
					</dt>
					<dd>{READ_AUTH}</dd>
				</dl>
				<dl>
					<dt>
						<label> {L_WRITE_AUTH} </label>
					</dt>
					<dd>{WRITE_AUTH}</dd>
				</dl>
				<dl>
					<dt>
						<label> {L_CONTRIBUTION_AUTH} </label>
					</dt>
					<dd>{CONTRIBUTION_AUTH}</dd>
				</dl>
			</div>
		</fieldset>

		<fieldset class="fieldset_submit">
			<legend>{L_SUBMIT}</legend>
			<input type="hidden" name="idcat" value="{IDCAT}" /> <input
				type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
			&nbsp;&nbsp; <input type="button" name="preview" value="{L_PREVIEW}"
				onclick="XMLHttpRequest_preview();" class="submit" /> &nbsp;&nbsp; <input
				type="reset" value="{L_RESET}" class="reset" />
		</fieldset>
	</form>
</div>
