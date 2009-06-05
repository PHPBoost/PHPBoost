		# IF C_ADD_MEDIA #
		<script type="text/javascript">
		<!--
		function check_form ()
		{
			# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
			# ENDIF #
			
			if (document.getElementById('name').value == "")
			{
				alert ("{L_REQUIRE_NAME}");
				return false;
		    }
			if (document.getElementById('u_media').value == "" || document.getElementById('u_media').value == "http://")
			{
				alert ("{L_REQUIRE_URL}");
				return false;
		    }
			return true;
		}
	
		function in_array (needle, haystack)
		{
			for (var i=0; i < haystack.length; i++)	
				if (haystack[i] == needle)
					return true;

			return false;
		}
		
		function hide_width_height ()
		{
			var id_music = new Array({JS_ID_MUSIC});

			if (id_music.length > 0)
				if (in_array (document.getElementById('idcat').value, id_music))
				{
					document.getElementById('width_dl').style.display = 'none';
					document.getElementById('height_dl').style.display = 'none';
				}
				else
				{
					document.getElementById('width_dl').style.display = 'block';
					document.getElementById('height_dl').style.display = 'block';
				}
		}
		
		# IF C_MUSIC #
		window.onload = function () {
			document.getElementById('width_dl').style.display = 'none';
			document.getElementById('height_dl').style.display = 'none';
		};
		# ENDIF #
		-->
		</script>

		<div class="module_position">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
				{L_PAGE_TITLE}
			</div>
			<div class="module_contents">
			<form action="media_action.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_PAGE_TITLE}</legend>
					<dl>
						<dt><label for="name">* {L_TITLE}</label></dt>
						<dd><input type="text" size="50" maxlength="100" id="name" name="name" value="{NAME}" class="text" /></dd>
					</dl>
					<dl>
						<dt><label for="idcat">{L_CATEGORY}</label></dt>
						<dd><label>
							{CATEGORIES_TREE}
						</label></dd>
					</dl>
					<dl id="width_dl">
						<dt><label for="width">{L_WIDTH}</label></dt>
						<dd><input type="text" size="10" maxlength="4" id="width" name="width" value="{WIDTH}" class="text" /></dd>
					</dl>
					<dl id="height_dl">
						<dt><label for="height">{L_HEIGHT}</label></dt>
						<dd><input type="text" size="10" maxlength="4" id="height" name="height" value="{HEIGHT}" class="text" /></dd>
					</dl>
					<dl>
						<dt><label for="u_media">* {L_U_MEDIA}</label></dt>
						<dd><input type="text" size="50" maxlength="500" id="u_media" name="u_media" value="{U_MEDIA}" class="text" /></dd>
					</dl>
					<br />
					<label for="contents" id="preview_content">{L_CONTENTS}</label>
					{KERNEL_EDITOR}
					<textarea rows="10" cols="90" id="contents" name="contents">{DESCRIPTION}</textarea>
					<br />
					# IF C_APROB #
					<dl>
						<dt><label>{L_APPROVED}</label></dt>
						<dd>
							<input type="checkbox" name="approved" id="approved"{APPROVED} />
						</dd>
					</dl>
					# ENDIF #
				</fieldset>
				# IF C_CONTRIBUTION #
				<fieldset>
					<legend>{L_CONTRIBUTION_LEGEND}</legend>
					<div class="notice">
						{L_NOTICE_CONTRIBUTION}
					</div>
					<p><label>{L_CONTRIBUTION_COUNTERPART}</label></p>
					<p class="text_small">{L_CONTRIBUTION_COUNTERPART_EXPLAIN}</p>
					{CONTRIBUTION_COUNTERPART_EDITOR}
					<textarea rows="20" cols="40" id="counterpart" name="counterpart">{CONTRIBUTION_COUNTERPART}</textarea>
				</fieldset>
				# ENDIF #

				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="idedit" value="{IDEDIT}" />
					<input type="hidden" name="contrib" value="{C_CONTRIBUTION}" />
					<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
					&nbsp;&nbsp;
                    <script type="text/javascript">
                    <!--
                    document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();new Effect.ScrollTo(\'preview_content\',{duration:1.2});return false;" type="button" class="submit" />');
                    -->
                    </script>
					&nbsp;&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>
			</div>
			<div class="module_bottom_l"></div>
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		# ENDIF #