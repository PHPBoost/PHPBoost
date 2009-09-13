		<script type="text/javascript">
		<!--
		var theme = '{THEME}';

		function check_form()
		{
			# IF C_BBCODE_TINYMCE_MODE #
			tinyMCE.triggerSave();
			# ENDIF #

			if (document.getElementById('title').value == "")
			{
				alert("{L_REQUIRE_TITLE}");
				new Effect.ScrollTo('title',{duration:1.2});
				return false;
			}
			if (document.getElementById('idcat').value == 0)
			{
				alert("{L_REQUIRE_CAT}");
				new Effect.ScrollTo('idcat',{duration:1.2});
				return false;
			}
			if (document.getElementById('contents').value == "")
			{
				alert("{L_REQUIRE_TEXT}");
				new Effect.ScrollTo('scroll_contents',{duration:1.2});
				return false;
			}
			return true;
		}

		function ajax_img_preview()
		{
 			document.getElementById('img_preview').innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
			var img = document.getElementById('img_field').value;
			var xhr_object_url = xmlhttprequest_init('xmlhttprequest.php?img_url=' + img + '&token={TOKEN}');

			xhr_object_url.onreadystatechange = function()
			{
				//Transfert finished and successful
				if( xhr_object_url.readyState == 4 && xhr_object_url.status == 200 && xhr_object_url.responseText != '' )
				{
					document.getElementById('img_field').value = xhr_object_url.responseText;
					var xhr_object = xmlhttprequest_init('xmlhttprequest.php?img_preview=' + xhr_object_url.responseText + '&token={TOKEN}');

					xhr_object.onreadystatechange = function()
					{
						//Transfert finished and successful
						if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
						{
							document.getElementById('img_preview').innerHTML = '<img src="' + xhr_object.responseText + '" alt="" class="valign_middle" />';
						}
						else
						{
							document.getElementById('img_preview').innerHTML = "";
						}
					}
					xmlhttprequest_sender(xhr_object, null);
				}
				else
				{
					document.getElementById('img_preview').innerHTML = "";
				}
			}
			xmlhttprequest_sender(xhr_object_url, null);
		}

		function ajax_preview()
		{
			if({JS_CONTRIBUTION} && document.getElementById('counterpart').value != '')
			{
				XMLHttpRequest_preview('counterpart');
			}

			if( check_form() )
			{
				document.getElementById('preview').innerHTML = '<img src="{PHP_PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';

				new Ajax.Request(
					'xmlhttprequest.php',
					{
						method: 'post',
						parameters: {
							preview: true,
							token: '{TOKEN}',
							id: document.getElementById('id').value,
							title: document.getElementById('title').value,
							idcat: document.getElementById('idcat').value,
							desc: document.getElementById('contents').value,
							extend_desc: document.getElementById('extend_contents').value,
							user_id: document.getElementById('user_id').value,
							date: document.getElementById('calendar_{JS_INSTANCE_RELEASE}').value,
							hour: document.getElementById('release_hour').value,
							min: document.getElementById('release_min').value,
							img: document.getElementById('img_field').value,
							alt: document.getElementById('alt').value
						 },
						onSuccess: function(response)
						{
							document.getElementById('preview').innerHTML = response.responseText;
						}
					}
				);
			}
			return false;
		}

		# IF C_ADD #
		function value_now(id_date, id_hour, id_min)
		{
			var date = "{NOW_DATE}";
			var hour = "{NOW_HOUR}";
			var min = "{NOW_MIN}";

			document.getElementById(id_date).value = date;
			document.getElementById(id_hour).value = hour;
			document.getElementById(id_min).value = min;

			return true;
		}
		# ENDIF #
		-->
		</script>

		<form action="management.php?token={TOKEN}" name="form" method="post" onsubmit="return check_form();" class="fieldset_content" id="form">
			<fieldset>
				<legend>{L_ADD_NEWS}</legend>
				<p>{L_REQUIRE}</p>
				<dl>
					<dt><label for="title">* {L_TITLE_NEWS}</label></dt>
					<dd><label><input type="text" size="65" maxlength="100" id="title" name="title" value="{TITLE}" class="text" /></label></dd>
				</dl>
				<dl>
					<dt><label for="idcat">* {L_CATEGORY}</label></dt>
					<dd><label>
						<select id="{FORM_ID}" name="{FORM_NAME}">
							<option value="0" disabled="disabled">{L_ROOT}</option>
						# START options #
							<option value="{options.ID}" {options.SELECTED_OPTION}>{options.PREFIX} {options.NAME}</option>
						# END options #
						</select>
					</label></dd>
				</dl>
				<label for="contents" id="scroll_contents">* {L_DESC}</label>
				{KERNEL_EDITOR}
				<label><textarea rows="20" cols="86" id="contents" name="contents">{CONTENTS}</textarea></label>
				<br /><br />
				<label for="extend_contents">{L_DESC_EXTEND}</label>
				{KERNEL_EDITOR_EXTEND}
				<label><textarea rows="20" cols="86" id="extend_contents" name="extend_contents">{EXTEND_CONTENTS}</textarea></label>
				<br />
				# IF NOT C_CONTRIBUTION #
				<dl class="overflow_visible">
					<dt><label for="release_date">* {L_RELEASE_DATE}</label></dt>
					<dd>
						<div onclick="document.getElementById('start_end_date').checked = true;">
							<input type="radio" value="2" name="visible" id="start_end_date"# IF VISIBLE_WAITING # checked="checked"# ENDIF # />
							{L_FROM_DATE}
							{START_CALENDAR}
							{L_AT}
							<input type="text" size="2" maxlength="2" name="start_hour" value="{START_HOUR}" class="text" />
							&nbsp;{L_UNIT_HOUR}&nbsp;
							<input type="text" size="2" maxlength="2" name="start_min" value="{START_MIN}" class="text" />
							&nbsp;{L_TO_DATE}&nbsp;
							{END_CALENDAR}
							{L_AT}
							<input type="text" size="2" maxlength="2" name="end_hour" value="{END_HOUR}" class="text" />
							&nbsp;{L_UNIT_HOUR}&nbsp;
							<input type="text" size="2" maxlength="2" name="end_min" value="{END_MIN}" class="text" />
						</div>
						<input type="radio" value="1" name="visible"# IF VISIBLE_ENABLED # checked="checked"# ENDIF # id="release_date" /> {L_IMMEDIATE}
						<br />
						<input type="radio" value="0" name="visible"# IF VISIBLE_UNAPROB # checked="checked"# ENDIF # /> {L_UNAPROB}
					</dd>
				</dl>
				# ENDIF #
				<dl class="overflow_visible">
					<dt><label for="current_date">* {L_NEWS_DATE}</label></dt>
					<dd>
						{RELEASE_CALENDAR}
						{L_AT}
						<input type="text" size="2" maxlength="2" id="release_hour" name="release_hour" value="{RELEASE_HOUR}" class="text" />
						&nbsp;{L_UNIT_HOUR}&nbsp;
						<input type="text" size="2" maxlength="2" id="release_min" name="release_min" value="{RELEASE_MIN}" class="text" />
						# IF C_ADD #
						&nbsp;
						<input type="button" id="button_new" value="{L_IMMEDIATE}" class="submit" onclick="javascript:value_now('calendar_{JS_INSTANCE_RELEASE}', 'release_hour', 'release_min')" />
						# ENDIF #
					</dd>
				</dl>
			</fieldset>

			<fieldset>
				<legend>{L_IMG_MANAGEMENT}</legend>
				<dl>
					<dt><label>{L_PREVIEW_IMG}</label><br /><span>{L_PREVIEW_IMG_EXPLAIN}</span></dt>
					<dd id="img_preview"># IF IMG #<img src="{IMG_PREVIEW}" alt="{ALT}" /># ENDIF #</dd>
				</dl>
				<dl>
					<dt><label for="img_field">{L_IMG_LINK}</label></dt>
					<dd><label><input type="text" size="60" id="img_field" name="img" value="{IMG}" class="text" onblur="javascript:ajax_img_preview();" /> &nbsp;&nbsp;<a title="{L_BB_UPLOAD}" href="#" onclick="window.open('{PATH_TO_ROOT}/member/upload.php?popup=1&amp;fd=img_field', '', 'height=500,width=720,resizable=yes,scrollbars=yes');return false;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" alt="{L_BB_UPLOAD}" /></a></label></dd>
				</dl>
				<dl>
					<dt><label for="alt">{L_IMG_DESC}</label></dt>
					<dd><label><input type="text" size="60" name="alt" id="alt" value="{ALT}" class="text" /></label></dd>
				</dl>
			</fieldset>

			# IF C_CONTRIBUTION #
			<fieldset>
				<legend>{L_CONTRIBUTION_LEGEND}</legend>
				<div class="notice">
					{L_NOTICE_CONTRIBUTION}
				</div>
				<p><label for="counterpart">{L_CONTRIBUTION_COUNTERPART}</label></p>
				<p class="text_small">{L_CONTRIBUTION_COUNTERPART_EXPLAIN}</p>
				{CONTRIBUTION_COUNTERPART_EDITOR}
				<textarea rows="20" cols="40" id="counterpart" name="counterpart">{CONTRIBUTION_COUNTERPART}</textarea>
				<br />
			</fieldset>
			# ENDIF #

			<div id="preview"></div>

			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" id="id" name="id" value="{IDNEWS}" class="submit" />
				<input type="hidden" id="user_id" name="user_id" value="{USER_ID}" class="submit" />
				<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
				<script type="text/javascript">
				<!--
				document.write('&nbsp;&nbsp;<input value="{L_PREVIEW}" onclick="ajax_preview();" type="button" class="submit" />');
				-->
				</script>
				&nbsp;&nbsp;
				<input type="reset" value="{L_RESET}" class="reset" />
			</fieldset>
		</form>