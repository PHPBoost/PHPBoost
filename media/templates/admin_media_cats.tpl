		# INCLUDE admin_media_menu #
		<div id="admin_contents">
			# IF C_ERROR_HANDLER #
					<span id="errorh"></span>
					<div id="error_msg">
						<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
							<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
						</div>
					<br />
					</div>
					<script type="text/javascript">
					<!--
						//Javascript timeout to hide this message
						setTimeout('Effect.Fade("error_msg");', 4000);
					-->
					</script>
			# ENDIF #

			# START categories_management #
				<table class="module_table" style="width:99%;">
					<tr>
						<th colspan="3">
							{L_MANAGEMENT_CATS}
						</th>
					</tr>
					<tr>
						<td style="padding-left:20px;" class="row2">
							<br />
							{categories_management.CATEGORIES}
							<br />
						</td>
					</tr>
				</table>
			# END categories_management #

			# START removing_interface #
			<form action="admin_media_cats.php?token={TOKEN}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_REMOVING_CATEGORY}</legend>
					<p>{L_EXPLAIN_REMOVING}</p>

					<label>
						<input type="radio" name="action" value="delete" /> {L_DELETE_CATEGORY_AND_CONTENT}
					</label>
					<br /> <br />
					<label>
						<input type="radio" name="action" value="move" checked="checked" /> {L_MOVE_CONTENT}
					</label>
					&nbsp;
					{removing_interface.CATEGORY_TREE}
				</fieldset>

				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="cat_to_del" value="{removing_interface.IDCAT}" />
					<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
				</fieldset>
			</form>
			# END removing_interface #

			# START edition_interface #
			<script type="text/javascript">
			<!--
			function check_form(){
				# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
				# ENDIF #
			
				if(document.getElementById('name').value == "")
				{
					alert("{L_REQUIRE_TITLE}");
					return false;
			    }
				return true;
			}
			-->
			</script>
			<form action="admin_media_cats.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_CATEGORY}</legend>
					<p>{L_REQUIRED_FIELDS}</p>
					<dl>
						<dt>
							<label for="name">
								* {L_CAT_NAME}
							</label>
						</dt>
						<dd>
							<input type="text" size="65" maxlength="100" id="name" name="name" value="{edition_interface.NAME}" class="text" />
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="id_parent">
								* {L_CAT_LOCATION}
							</label>
						</dt>
						<dd>
							{edition_interface.CATEGORIES_TREE}
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="image">
								{L_CAT_IMAGE}
							</label>
						</dt>
						<dd>
							<input type="text" size="65" maxlength="100" id="image" name="image" value="{edition_interface.IMAGE}" class="text" />
						</dd>
					</dl>
					<label for="description" id="preview_description">
						* {L_CAT_DESCRIPTION}
					</label>
					{KERNEL_EDITOR}
					<textarea id="contents" rows="15" cols="40" name="description">{edition_interface.DESCRIPTION}</textarea>
					<br />
					<dl>
						<dt><label for="activ_com">{L_MIME_TYPE}</label></dt>
						<dd>
							<label><input type="radio" name="mime_type" value="0"{edition_interface.TYPE_BOTH} /> {L_TYPE_BOTH}</label>
							&nbsp;&nbsp;
							<label><input type="radio" name="mime_type" value="1"{edition_interface.TYPE_MUSIC} /> {L_TYPE_MUSIC}</label>
							&nbsp;&nbsp;
							<label><input type="radio" name="mime_type" value="2"{edition_interface.TYPE_VIDEO} /> {L_TYPE_VIDEO}</label>
						</dd>
					</dl>
				</fieldset>
				<fieldset>
					<legend>{L_DISPLAY}</legend>
					<dl>
						<dt><label for="activ_com">{L_DISPLAY_COM}</label></dt>
						<dd>
							<label><input type="checkbox" name="activ[]" value="1" {edition_interface.COM_LIST} /> {L_IN_LIST}</label>
							&nbsp;&nbsp;
							<label><input type="checkbox" name="activ[]" value="2" {edition_interface.COM_MEDIA} /> {L_IN_MEDIA}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_note">{L_DISPLAY_NOTE}</label></dt>
						<dd>
							<label><input type="checkbox" name="activ[]" value="4" {edition_interface.NOTE_LIST} /> {L_IN_LIST}</label>
							&nbsp;&nbsp;
							<label><input type="checkbox" name="activ[]" value="8" {edition_interface.NOTE_MEDIA} /> {L_IN_MEDIA}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_user">{L_DISPLAY_USER}</label></dt>
						<dd>
							<label><input type="checkbox" name="activ[]" value="16" {edition_interface.USER_LIST} /> {L_IN_LIST}</label>
							&nbsp;&nbsp;
							<label><input type="checkbox" name="activ[]" value="32" {edition_interface.USER_MEDIA} /> {L_IN_MEDIA}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_counter">{L_DISPLAY_COUNTER}</label></dt>
						<dd>
							<label><input type="checkbox" name="activ[]" value="64" {edition_interface.COUNTER_LIST} /> {L_IN_LIST}</label>
							&nbsp;&nbsp;
							<label><input type="checkbox" name="activ[]" value="128" {edition_interface.COUNTER_MEDIA} /> {L_IN_MEDIA}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_date">{L_DISPLAY_DATE}</label></dt>
						<dd>
							<label><input type="checkbox" name="activ[]" value="256" {edition_interface.DATE_LIST} /> {L_IN_LIST}</label>
							&nbsp;&nbsp;
							<label><input type="checkbox" name="activ[]" value="512" {edition_interface.DATE_MEDIA} /> {L_IN_MEDIA}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_desc">{L_DISPLAY_DESC}</label></dt>
						<dd>
							<label><input type="checkbox" name="activ[]" value="1024" {edition_interface.DESC_LIST} /> {L_IN_LIST}</label>
							&nbsp;&nbsp;
							<label><input type="checkbox" name="activ[]" value="2048" {edition_interface.DESC_MEDIA} /> {L_IN_MEDIA}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_nbr">{L_DISPLAY_NBR}</label></dt>
						<dd>
							<label><input type="checkbox" name="activ[]" value="4096" {edition_interface.NBR} /></label>
						</dd>
					</dl>
				</fieldset>
				<fieldset>
					<legend>{L_SPECIAL_AUTH}</legend>
					<dl>
						<dt>
							<label>
								{L_READ_AUTH}
							</label>
						</dt>
						<dd>
							{edition_interface.READ_AUTH}
						</dd>
					</dl>
					<dl>
						<dt>
							<label>
								{L_CONTRIBUTE_AUTH}
							</label>
						</dt>
						<dd>
							{edition_interface.CONTRIBUTE_AUTH}
						</dd>
					</dl>
					<dl>
						<dt>
							<label>
								{L_WRITE_AUTH}
							</label>
						</dt>
						<dd>
							{edition_interface.WRITE_AUTH}
						</dd>
					</dl>
				</fieldset>

				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="idcat" value="{edition_interface.IDCAT}" />
					<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
					&nbsp;&nbsp;
					<input type="button" name="preview" value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(); new Effect.ScrollTo('preview_description',{duration:1.2}); return false;"" class="submit" />
					&nbsp;&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>
			# END edition_interface #
		</div>
