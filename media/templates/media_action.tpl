		# IF C_ADD_MEDIA #
		<script>
		<!--
		function check_form()
		{
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

		function in_array(needle, haystack)
		{
			for (var i=0; i < haystack.length; i++)
				if (haystack[i] == needle)
					return true;

			return false;
		}

		function hide_width_height()
		{
			var id_music = new Array({JS_ID_MUSIC});

			if (id_music.length > 0)
				if (in_array(jQuery('#idcat').val(), id_music))
				{
					jQuery('#width_dl').hide();
					jQuery('#height_dl').hide();
				}
				else
				{
					jQuery('#width_dl').show();
					jQuery('#height_dl').show();
				}
		}

		jQuery(document).ready(function() {
			# IF C_MUSIC #
			jQuery('#width_dl').hide();
			jQuery('#height_dl').hide();
			# ENDIF #
			jQuery('#idcat').change(function() {
				hide_width_height();
			});
		});
		-->
		</script>

		<section id="module-media-action">
			<header>
				<h1>{L_PAGE_TITLE}</h1>
			</header>
			<div class="content">
			<form action="media_action.php" method="post" onsubmit="return check_form();" class="fieldset-content">
				<p class="align-center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_PAGE_TITLE}</legend>
					<div class="form-element">
						<label for="name">* {L_TITLE}</label>
						<div class="form-field"><input type="text" maxlength="100" class="field-large" id="name" name="name" value="{NAME}" /></div>
					</div>
					# IF C_CATEGORIES #
					<div class="form-element">
						<label for="category">${LangLoader::get_message('form.category', 'common')}</label>
						<div class="form-field">
							<select name="idcat" id="idcat">
								{CATEGORIES}
							</select>
						</div>
					</div>
					# ENDIF #
					<div class="form-element" id="width_dl">
						<label for="width">{L_WIDTH}</label>
						<div class="form-field"><input type="number" min="10" max="5000" maxlength="4" id="width" name="width" value="{WIDTH}" /></div>
					</div>
					<div class="form-element" id="height_dl">
						<label for="height">{L_HEIGHT}</label>
						<div class="form-field"><input type="number" min="10" max="5000" id="height" name="height" value="{HEIGHT}" /></div>
					</div>
					<div class="form-element">
						<label for="u_media">* {L_U_MEDIA}</label>
						<div class="form-field">
							<input type="text" maxlength="255" class="field-large" id="u_media" name="u_media" value="{U_MEDIA}" />
							# IF C_AUTH_UPLOAD #
								<a aria-label="${LangLoader::get_message('files_management', 'main')}" href="" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=u_media&amp;parse=true&amp;no_path=true', '', 'height=500,width=769,resizable=yes,scrollbars=yes');return false;">
									<i class="fa fa-cloud-upload-alt fa-2x" aria-hidden="true"></i>
								</a>
							# ENDIF #
						</div>
					</div>
					<div class="form-element">
						<label for="poster">{L_POSTER}</label>
						<div class="form-field# IF C_AUTH_UPLOAD # form-field-upload-file# ENDIF #">
							<input type="text" maxlength="255" class="field-large" id="poster" name="poster" value="{POSTER}" />
							# IF C_AUTH_UPLOAD #
								<a aria-label="${LangLoader::get_message('files_management', 'main')}" href="" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=poster&amp;parse=true&amp;no_path=true', '', 'height=500,width=769,resizable=yes,scrollbars=yes');return false;">
									<i class="fa fa-cloud-upload-alt fa-2x" aria-hidden="true"></i>
								</a>
							# ENDIF #
						</div>
					</div>
					<div class="form-element form-element-textarea">
						<label for="contents" id="preview_content">{L_CONTENTS}</label>
						{KERNEL_EDITOR}
						<div class="form-field-textarea">
							<textarea rows="10" cols="90" id="contents" name="contents">{DESCRIPTION}</textarea>
						</div>
					</div>
					# IF C_APROB #
					<div class="form-element">
						<label>{L_APPROVED}</label>
						<div class="form-field">
							<label for="approved" class="checkbox">
								<input type="checkbox" name="approved" id="approved"{APPROVED} />
								<span>&nbsp</span>
							</label>
						</div>
					</div>
					# ENDIF #
				</fieldset>
				# IF C_CONTRIBUTION #
				<fieldset>
					<legend>{L_CONTRIBUTION_LEGEND}</legend>
					<div class="message-helper bgc notice">{L_NOTICE_CONTRIBUTION}</div>
					<div class="form-element form-element-textarea">
						<label>{L_CONTRIBUTION_COUNTERPART} <p class="field-description">{L_CONTRIBUTION_COUNTERPART_EXPLAIN}</p></label>
						{CONTRIBUTION_COUNTERPART_EDITOR}
						<div class="form-field-textarea">
							<textarea rows="20" cols="40" id="counterpart" name="counterpart">{CONTRIBUTION_COUNTERPART}</textarea>
						</div>
					</div>
				</fieldset>
				# ENDIF #

				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="idedit" value="{IDEDIT}" />
					<input type="hidden" name="contrib" value="{C_CONTRIBUTION}" />
					<input type="hidden" name="token" value="{TOKEN}" />
					<button type="submit" class="button submit" name="submit" value="true">{L_SUBMIT}</button>
					<button type="button" class="button small" onclick="XMLHttpRequest_preview(); return false;">{L_PREVIEW}</button>
					<button type="reset" class="button reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
			</div>
			<footer></footer>
		</section>
		# ENDIF #
