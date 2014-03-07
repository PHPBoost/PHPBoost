		# IF C_ADD_MEDIA #
		<script>
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

		<section>
			<header>
				<h1>{L_PAGE_TITLE}</h1>
			</header>
			<div class="content">
			<form action="media_action.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset-content">
				<fieldset>
					<legend>{L_PAGE_TITLE}</legend>
					<div class="form-element">
						<label for="name">* {L_TITLE}</label>
						<div class="form-field"><input type="text" size="50" maxlength="100" id="name" name="name" value="{NAME}"></div>
					</div>
					<div class="form-element">
						<label for="idcat">{L_CATEGORY}</label>
						<div class="form-field"><label>
							{CATEGORIES_TREE}
						</label></div>
					</div>
					<div class="form-element" id="width_dl">
						<label for="width">{L_WIDTH}</label>
						<div class="form-field"><input type="text" size="10" maxlength="4" id="width" name="width" value="{WIDTH}"></div>
					</div>
					<div class="form-element" id="height_dl">
						<label for="height">{L_HEIGHT}</label>
						<div class="form-field"><input type="text" size="10" maxlength="4" id="height" name="height" value="{HEIGHT}"></div>
					</div>
					<div class="form-element">
						<label for="u_media">* {L_U_MEDIA}</label>
						<div class="form-field"><input type="text" size="50" maxlength="500" id="u_media" name="u_media" value="{U_MEDIA}"></div>
					</div>
					<div class="form-element-textarea">
						<label for="contents" id="preview_content">{L_CONTENTS}</label>
						{KERNEL_EDITOR}
						<textarea rows="10" cols="90" id="contents" name="contents">{DESCRIPTION}</textarea>
					</div>
					# IF C_APROB #
					<div class="form-element">
						<label>{L_APPROVED}</label>
						<div class="form-field">
							<input type="checkbox" name="approved" id="approved"{APPROVED}>
						</div>
					</div>
					# ENDIF #
				</fieldset>
				# IF C_CONTRIBUTION #
				<fieldset>
					<legend>{L_CONTRIBUTION_LEGEND}</legend>
					<div class="notice">{L_NOTICE_CONTRIBUTION}</div>
					<div class="form-element-textarea">
						<label>{L_CONTRIBUTION_COUNTERPART} <p class="field-description">{L_CONTRIBUTION_COUNTERPART_EXPLAIN}</p></label>
						{CONTRIBUTION_COUNTERPART_EDITOR}
						<textarea rows="20" cols="40" id="counterpart" name="counterpart">{CONTRIBUTION_COUNTERPART}</textarea>
					</div>
				</fieldset>
				# ENDIF #

				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="idedit" value="{IDEDIT}">
					<input type="hidden" name="contrib" value="{C_CONTRIBUTION}">
					<button type="submit" name="submit" value="true">{L_SUBMIT}</button>
					<button type="button" onclick="XMLHttpRequest_preview();new Effect.ScrollTo(\'preview_content\',{duration:1.2});return false;">{L_PREVIEW}</button>
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
			</div>
			<footer></footer>
		</section>
		# ENDIF #