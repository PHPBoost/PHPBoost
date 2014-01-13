		<script type="text/javascript">
		<!--
		function check_form()
		{
			# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
			# ENDIF #
			
			if(document.getElementById('media_name').value == "") {
				new Effect.ScrollTo('media_name',{duration:1.2});
				alert("{L_REQUIRE}{L_MODULE_NAME}");
				return false;
			}
			if(document.getElementById('num_cols').value == "") {
				new Effect.ScrollTo('num_cols',{duration:1.2});
				alert("{L_REQUIRE}{L_NBR_COLS}");
				return false;
			}
			if(document.getElementById('pagin').value == "") {
				new Effect.ScrollTo('pagin',{duration:1.2});
				alert("{L_REQUIRE}{L_PAGINATION}");
				return false;
			}
			if(document.getElementById('note').value == "") {
				new Effect.ScrollTo('note',{duration:1.2});
				alert("{L_REQUIRE}{L_NOTE}");
				return false;
			}
			if(document.getElementById('width').value == "") {
				new Effect.ScrollTo('width',{duration:1.2});
				alert("{L_REQUIRE}{L_WIDTH_MAX}");
				return false;
			}
			if(document.getElementById('height').value == "") {
				new Effect.ScrollTo('height',{duration:1.2});
				alert("{L_REQUIRE}{L_HEIGHT_MAX}");
				return false;
			}
			return true;
		}
		-->
		</script>

		# INCLUDE admin_media_menu #
		<div id="admin-contents">
			<form action="admin_media_config.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset-content">
					<fieldset>
						<legend>{L_CONFIG_GENERAL}</legend>
						<div class="form-element">
							<label for="media_name">{L_MODULE_NAME} <span class="field-description">{L_MODULE_NAME_EXPLAIN}</span></label>
							<div class="form-field">
								<input type="text" size="65" maxlength="100" id="media_name" name="media_name" value="{MODULE_NAME}">
							</div>
						</div>
						<div class="form-element-textarea">
							<label for="contents" id="preview_description">{L_MODULE_DESC}</label>
							{KERNEL_EDITOR}
							<textarea rows="10" cols="90" id="contents" name="contents">{CONTENTS}</textarea>
						</div>
						<div class="form-element">
							<label for="activ_com">{L_MIME_TYPE}</label>
							<div class="form-field">
								<label><input type="radio" name="mime_type" value="0"{TYPE_BOTH}> {L_TYPE_BOTH}</label>
								<label><input type="radio" name="mime_type" value="1"{TYPE_MUSIC}> {L_TYPE_MUSIC}</label>
								<label><input type="radio" name="mime_type" value="2"{TYPE_VIDEO}> {L_TYPE_VIDEO}</label>
							</div>
						</div>
					</fieldset>

					<fieldset>
						<legend>{L_CONFIG_DISPLAY}</legend>
						<div class="form-element">
							<label for="num_cols">{L_NBR_COLS}</label>
							<div class="form-field"><input type="text" size="4" maxlength="1" id="num_cols" name="num_cols" value="{NBR_COLS}"></div>
						</div>
						<div class="form-element">
							<label for="pagin">{L_PAGINATION}</label>
							<div class="form-field"><input type="text" size="4" maxlength="3" id="pagin" name="pagin" value="{PAGINATION}"></div>
						</div>
						<div class="form-element">
							<label for="note">{L_NOTE}</label>
							<div class="form-field"><input type="text" size="4" maxlength="3" id="note" name="note" value="{NOTE}"></div>
						</div>
						<div class="form-element">
							<label for="width">{L_WIDTH_MAX}</label>
							<div class="form-field"><input type="text" size="4" maxlength="4" id="width" name="width" value="{WIDTH_MAX}"></div>
						</div>
						<div class="form-element">
							<label for="height">{L_HEIGHT_MAX}</label>
							<div class="form-field"><input type="text" size="4" maxlength="4" id="height" name="height" value="{HEIGHT_MAX}"></div>
						</div>
						<div class="form-element">
							<label for="activ_com">{L_DISPLAY_COM}</label>
							<div class="form-field">
								<label><input type="checkbox" name="activ[]" value="1" {COM_LIST}> {L_IN_LIST}</label>
								<label><input type="checkbox" name="activ[]" value="2" {COM_MEDIA}> {L_IN_MEDIA}</label>
							</div>
						</div>
						<div class="form-element">
							<label for="activ_note">{L_DISPLAY_NOTE}</label>
							<div class="form-field">
								<label><input type="checkbox" name="activ[]" value="4" {NOTE_LIST}> {L_IN_LIST}</label>
								<label><input type="checkbox" name="activ[]" value="8" {NOTE_MEDIA}> {L_IN_MEDIA}</label>
							</div>
						</div>
						<div class="form-element">
							<label for="activ_user">{L_DISPLAY_USER}</label>
							<div class="form-field">
								<label><input type="checkbox" name="activ[]" value="16" {USER_LIST}> {L_IN_LIST}</label>
								<label><input type="checkbox" name="activ[]" value="32" {USER_MEDIA}> {L_IN_MEDIA}</label>
							</div>
						</div>
						<div class="form-element">
							<label for="activ_counter">{L_DISPLAY_COUNTER}</label>
							<div class="form-field">
								<label><input type="checkbox" name="activ[]" value="64" {COUNTER_LIST}> {L_IN_LIST}</label>
								<label><input type="checkbox" name="activ[]" value="128" {COUNTER_MEDIA}> {L_IN_MEDIA}</label>
							</div>
						</div>
						<div class="form-element">
							<label for="activ_date">{L_DISPLAY_DATE}</label>
							<div class="form-field">
								<label><input type="checkbox" name="activ[]" value="256" {DATE_LIST}> {L_IN_LIST}</label>
								<label><input type="checkbox" name="activ[]" value="512" {DATE_MEDIA}> {L_IN_MEDIA}</label>
							</div>
						</div>
						<div class="form-element">
							<label for="activ_desc">{L_DISPLAY_DESC}</label>
							<div class="form-field">
								<label><input type="checkbox" name="activ[]" value="1024" {DESC_LIST}> {L_IN_LIST}</label>
								<label><input type="checkbox" name="activ[]" value="2048" {DESC_MEDIA}> {L_IN_MEDIA}</label>
							</div>
						</div>
					</fieldset>

					<fieldset>
						<legend>{L_CONFIG_AUTH}</legend>
						<p>{L_CONFIG_AUTH_EXPLAIN}</p>
						<div class="form-element">
							<label for="auth_read">{L_AUTH_READ}</label>
							<div class="form-field">
								{AUTH_READ}
							</div>
						</div>
						<div class="form-element">
							<label for="auth_contribute">{L_AUTH_CONTRIBUTE}</label>
							<div class="form-field">
								{AUTH_CONTRIBUTE}
							</div>
						</div>
						<div class="form-element">
							<label for="auth_write">{L_AUTH_WRITE}</label>
							<div class="form-field">
								{AUTH_WRITE}
							</div>
						</div>
					</fieldset>

					<fieldset class="fieldset-submit">
						<legend>{L_UPDATE}</legend>
						<button type="submit" name="valid" value="true">{L_UPDATE}</button>
						<button type="button" name="valid" onclick="XMLHttpRequest_preview(); new Effect.ScrollTo('preview_description',{duration:1.2}); return false;" value="true">{L_PREVIEW}</button>
						<button type="reset" value="true">{L_RESET}</button>
					</fieldset>
				</form>
			</div>