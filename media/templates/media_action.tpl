# IF C_ADD_MEDIA #
	<script>
		function check_form()
		{
			if (document.getElementById('name').value == "")
			{
				alert ("{@media.require.title}");
				return false;
		    }
			if (document.getElementById('u_media').value == "" || document.getElementById('u_media').value == "http://")
			{
				alert ("{@media.require.file.url}");
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
				if (in_array(jQuery('#id_category').val(), id_music))
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
			jQuery('#id_category').change(function() {
				hide_width_height();
			});
		});
	</script>

	<section id="module-media-action">
		<header class="section-header">
			<h1>
				# IF C_EDIT #
					{@media.edit.item}
				# ELSE #
					# IF C_CONTRIBUTION #
						{@media.contribution}
					# ELSE #
						{@media.add.item}
					# ENDIF #
				# ENDIF #
			</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<form action="media_action.php" method="post" onsubmit="return check_form();" class="fieldset-content">
						<p class="align-center">${LangLoader::get_message('form.explain_required_fields', 'status-messages-common')}</p>
						<fieldset>
							<legend>${LangLoader::get_message('form.parameters', 'common')}</legend>
							<div class="form-element">
								<label for="title">* {@media.title}</label>
								<div class="form-field"><input type="text" id="title" name="title" value="{TITLE}" /></div>
							</div>
							# IF C_CATEGORIES #
								<div class="form-element">
									<label for="category">${LangLoader::get_message('form.category', 'common')}</label>
									<div class="form-field">
										<select name="id_category" id="id_category">
											{CATEGORIES}
										</select>
									</div>
								</div>
							# ENDIF #
							<div class="form-element" id="width_dl">
								<label for="width">{@media.width}</label>
								<div class="form-field"><input type="number" min="10" max="5000" maxlength="4" id="width" name="width" value="{WIDTH}" /></div>
							</div>
							<div class="form-element" id="height_dl">
								<label for="height">{@media.height}</label>
								<div class="form-field"><input type="number" min="10" max="5000" id="height" name="height" value="{HEIGHT}" /></div>
							</div>
							<div class="form-element form-element-upload-file">
								<label for="u_media">* {@media.file.url}</label>
								<div class="form-field# IF C_AUTH_UPLOAD #  grouped-inputs form-field-upload-file# ENDIF #">
									<input class="grouped-element upload-input" type="text" id="u_media" name="u_media" value="{U_MEDIA}" />
									# IF C_AUTH_UPLOAD #
										<a class="grouped-element" aria-label="${LangLoader::get_message('files_management', 'main')}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=u_media&amp;parse=true&amp;no_path=true', '', 'height=500,width=769,resizable=yes,scrollbars=yes');return false;">
											<i class="fa fa-cloud-upload-alt fa-2x" aria-hidden="true"></i>
										</a>
									# ENDIF #
								</div>
							</div>
							<div class="form-element form-element-upload-file">
								<label for="thumbnail">{@media.poster}</label>
								<div class="form-field# IF C_AUTH_UPLOAD # grouped-inputs form-field-upload-file# ENDIF #">
									<input class="grouped-element upload-input" type="text" id="thumbnail" name="thumbnail" value="{POSTER}" />
									# IF C_AUTH_UPLOAD #
										<a class="grouped-element" aria-label="${LangLoader::get_message('files_management', 'main')}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=poster&amp;parse=true&amp;no_path=true', '', 'height=500,width=769,resizable=yes,scrollbars=yes');return false;">
											<i class="fa fa-cloud-upload-alt fa-2x" aria-hidden="true"></i>
										</a>
									# ENDIF #
								</div>
							</div>
							<div class="form-element form-element-textarea">
								<label for="content" id="preview_content">{@media.description}</label>
								{KERNEL_EDITOR}
								<div class="form-field-textarea">
									<textarea rows="10" cols="90" id="content" name="content">{CONTENT}</textarea>
								</div>
							</div>
							# IF C_APPROVAL #
								<div class="form-element">
									<label>{@media.approval}</label>
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
								<legend>${LangLoader::get_message('contribution', 'main')}</legend>
								<div class="message-helper bgc warning">{@H|media.contribution.notice}</div>
								<div class="form-element form-element-textarea">
									<label>{@media.additional.contribution} <p class="field-description">{@media.additional.contribution.description}</p></label>
									{CONTRIBUTION_EDITOR}
									<div class="form-field-textarea">
										<textarea rows="20" cols="40" id="counterpart" name="counterpart"></textarea>
									</div>
								</div>
							</fieldset>
						# ENDIF #

						<fieldset class="fieldset-submit">
							<legend>
								# IF C_EDIT #
									${LangLoader::get_message('update', 'main')}
								# ELSE #
									${LangLoader::get_message('submit', 'main')}
								# ENDIF #
							</legend>
							<div class="fieldset-inset">
								<input type="hidden" name="idedit" value="{ITEM_ID}" />
								<input type="hidden" name="contrib" value="{C_CONTRIBUTION}" />
								<input type="hidden" name="token" value="{TOKEN}" />
								<button type="submit" class="button submit" name="submit" value="true">
									# IF C_EDIT #
										${LangLoader::get_message('update', 'main')}
									# ELSE #
										${LangLoader::get_message('submit', 'main')}
									# ENDIF #
								</button>
								<button type="button" class="button preview-button" onclick="XMLHttpRequest_preview(); return false;">${LangLoader::get_message('preview', 'main')}</button>
								<button type="reset" class="button reset-button" value="true">${LangLoader::get_message('reset', 'main')}</button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# ENDIF #
