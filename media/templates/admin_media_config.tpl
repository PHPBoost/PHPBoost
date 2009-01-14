		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('nbr_web_max').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('nbr_cat_max').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('nbr_column').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('note_max').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			return true;
		}

		var displayedcontents = false;
		function XMLHttpRequest_preview()
		{
			var contents = document.getElementById('contents').value;

			if( contents != "" )
			{
				if( !displayedcontents )
					Effect.BlindDown('xmlhttprequest_previewcontents', { duration: 0.5 });

				if( document.getElementById('loading_previewcontents') )
					document.getElementById('loading_previewcontents').style.display = 'block';
				displayedcontents = true;

				contents = escape_xmlhttprequest(contents);
				data = "contents=" + contents + "&ftags=swf";

				var xhr_object = xmlhttprequest_init('../kernel/framework/ajax/content_xmlhttprequest.php?preview=1&PATH_TO_ROOT=..');
				xhr_object.onreadystatechange = function()
				{
					if( xhr_object.readyState == 4 )
					{
						document.getElementById('xmlhttprequest_previewcontents').innerHTML = xhr_object.responseText;
						if( document.getElementById('loading_previewcontents') )
							document.getElementById('loading_previewcontents').style.display = 'none';
					}
				}
				xmlhttprequest_sender(xhr_object, data);
			}
			else
				alert("Veuillez entrer un texte !");
		}
		-->
		</script>

		# INCLUDE admin_media_menu #
		<div id="admin_contents">
			<form action="admin_media_config.php" method="post" class="fieldset_content" id="top_config">
					<fieldset>
						<legend>{L_CONFIG_GENERAL}</legend>
						<dl>
							<dt>
								<label for="name">{L_MODULE_NAME}</label>
								<br />
								<span class="text_small">{L_MODULE_NAME_EXPLAIN}</span>
							</dt>
							<dd>
								<input type="text" size="65" maxlength="100" id="media_name" name="media_name" value="{MODULE_NAME}" class="text" />
							</dd>
						</dl>
						<br />
						<label for="contents">{L_MODULE_DESC}</label>
						<label>
							{KERNEL_EDITOR}
							<textarea type="text" rows="10" cols="90" id="contents" name="desc">{CONTENTS}</textarea>
						</label>
						<br />
						<dl>
							<dt><label for="activ_com">{L_MIME_TYPE}</label></dt>
							<dd>
								<label><input type="radio" name="mime_type" value="0"{TYPE_BOTH} /> {L_TYPE_BOTH}</label>
								&nbsp;&nbsp;
								<label><input type="radio" name="mime_type" value="1"{TYPE_MUSIC} /> {L_TYPE_MUSIC}</label>
								&nbsp;&nbsp;
								<label><input type="radio" name="mime_type" value="2"{TYPE_VIDEO} /> {L_TYPE_VIDEO}</label>
							</dd>
						</dl>
					</fieldset>

					<fieldset>
						<legend>{L_CONFIG_DISPLAY}</legend>
						<dl>
							<dt><label for="num_cols">{L_NBR_COLS}</label></dt>
							<dd><input type="text" size="4" maxlength="1" id="num_cols" name="num_cols" value="{NBR_COLS}" class="text" /></dd>
						</dl>
						<dl>
							<dt><label for="pagin">{L_PAGINATION}</label></dt>
							<dd><input type="text" size="4" maxlength="3" id="pagin" name="pagin" value="{PAGINATION}" class="text" /></dd>
						</dl>
						<dl>
							<dt><label for="note">{L_NOTE}</label></dt>
							<dd><input type="text" size="4" maxlength="3" id="note" name="note" value="{NOTE}" class="text" /></dd>
						</dl>
						<dl>
							<dt><label for="width">{L_WIDTH_MAX}</label></dt>
							<dd><input type="text" size="4" maxlength="4" id="width" name="width" value="{WIDTH_MAX}" class="text" /></dd>
						</dl>
						<dl>
							<dt><label for="height">{L_HEIGHT_MAX}</label></dt>
							<dd><input type="text" size="4" maxlength="4" id="height" name="height" value="{HEIGHT_MAX}" class="text" /></dd>
						</dl>
						<dl>
							<dt><label for="activ_com">{L_DISPLAY_COM}</label></dt>
							<dd>
								<label><input type="checkbox" name="activ[]" value="1" {COM_LIST} /> {L_IN_LIST}</label>
								&nbsp;&nbsp;
								<label><input type="checkbox" name="activ[]" value="2" {COM_MEDIA} /> {L_IN_MEDIA}</label>
							</dd>
						</dl>
						<dl>
							<dt><label for="activ_note">{L_DISPLAY_NOTE}</label></dt>
							<dd>
								<label><input type="checkbox" name="activ[]" value="4" {NOTE_LIST} /> {L_IN_LIST}</label>
								&nbsp;&nbsp;
								<label><input type="checkbox" name="activ[]" value="8" {NOTE_MEDIA} /> {L_IN_MEDIA}</label>
							</dd>
						</dl>
						<dl>
							<dt><label for="activ_user">{L_DISPLAY_USER}</label></dt>
							<dd>
								<label><input type="checkbox" name="activ[]" value="16" {USER_LIST} /> {L_IN_LIST}</label>
								&nbsp;&nbsp;
								<label><input type="checkbox" name="activ[]" value="32" {USER_MEDIA} /> {L_IN_MEDIA}</label>
							</dd>
						</dl>
						<dl>
							<dt><label for="activ_counter">{L_DISPLAY_COUNTER}</label></dt>
							<dd>
								<label><input type="checkbox" name="activ[]" value="64" {COUNTER_LIST} /> {L_IN_LIST}</label>
								&nbsp;&nbsp;
								<label><input type="checkbox" name="activ[]" value="128" {COUNTER_MEDIA} /> {L_IN_MEDIA}</label>
							</dd>
						</dl>
						<dl>
							<dt><label for="activ_date">{L_DISPLAY_DATE}</label></dt>
							<dd>
								<label><input type="checkbox" name="activ[]" value="256" {DATE_LIST} /> {L_IN_LIST}</label>
								&nbsp;&nbsp;
								<label><input type="checkbox" name="activ[]" value="512" {DATE_MEDIA} /> {L_IN_MEDIA}</label>
							</dd>
						</dl>
						<dl>
							<dt><label for="activ_desc">{L_DISPLAY_DESC}</label></dt>
							<dd>
								<label><input type="checkbox" name="activ[]" value="1024" {DESC_LIST} /> {L_IN_LIST}</label>
								&nbsp;&nbsp;
								<label><input type="checkbox" name="activ[]" value="2048" {DESC_MEDIA} /> {L_IN_MEDIA}</label>
							</dd>
						</dl>
					</fieldset>

					<fieldset>
						<legend>{L_CONFIG_AUTH}</legend>
						<p>{L_CONFIG_AUTH_EXPLAIN}</p>
						<dl>
							<dt>
								<label for="auth_read">{L_AUTH_READ}</label>
							</dt>
							<dd>
								{AUTH_READ}
							</dd>
						</dl>
						<dl>
							<dt>
								<label for="auth_contribute">{L_AUTH_CONTRIBUTE}</label>
							</dt>
							<dd>
								{AUTH_CONTRIBUTE}
							</dd>
						</dl>
						<dl>
							<dt>
								<label for="auth_write">{L_AUTH_WRITE}</label>
							</dt>
							<dd>
								{AUTH_WRITE}
							</dd>
						</dl>
					</fieldset>

					<fieldset class="fieldset_submit">
						<legend>{L_UPDATE}</legend>
						<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
						&nbsp;&nbsp;
						<input value="{L_PREVIEW}" type="button" name="valid" class="submit" onclick="XMLHttpRequest_preview(this.form); new Effect.ScrollTo('top_config',{duration:1.2}); return false;" />
						&nbsp;&nbsp;
						<input type="reset" value="{L_RESET}" class="reset" />
					</fieldset>
				</form>
			</div>
		</div>