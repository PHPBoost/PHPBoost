		<script>
		<!--
			function check_form_rank_add()
			{
				if(document.getElementById('name').value == "") {
					alert("{L_REQUIRE_RANK_NAME}");
					return false;
				}
				if(document.getElementById('msg').value == "") {
					alert("{L_REQUIRE_NBR_MSG_RANK}");
					return false;
				}
				return true;
			}

			function img_change(id, url)
			{
				if( document.getElementById(id) && url != '' )
				{
					document.getElementById(id).style.display = 'inline';
					document.getElementById(id).src = url;
				}
				else
					document.getElementById(id).style.display = 'none';
			}
		-->
		</script>

		<nav id="admin-quick-menu">
			<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
				<i class="fa fa-bars" aria-hidden="true"></i> {L_FORUM_MANAGEMENT}
			</a>
			<ul>
				<li>
					<a href="{PATH_TO_ROOT}/forum" class="quick-link">${LangLoader::get_message('home', 'main')}</a>
				</li>
				<li>
					<a href="admin_ranks.php" class="quick-link">{L_FORUM_RANKS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_ranks_add.php" class="quick-link">{L_FORUM_ADD_RANKS}</a>
				</li>
				<li>
					<a href="${relative_url(ForumUrlBuilder::configuration())}" class="quick-link">${LangLoader::get_message('configuration', 'admin-common')}</a>
				</li>
			</ul>
		</nav>

		<div id="admin-contents">

			# INCLUDE message_helper #

			<form action="admin_ranks_add.php" method="post" enctype="multipart/form-data" class="fieldset-content">
				<fieldset>
					<legend>{L_UPLOAD_RANKS}</legend>
					<div class="fieldset-inset">
						<div class="form-element full-field">
							<label for="upload_ranks">
								{L_UPLOAD_RANKS}
								<span class="field-description">{L_UPLOAD_FORMAT}</span>
							</label>
							<div class="form-field">
								<div class="dnd-area">
									<div class="dnd-dropzone">
										<label for="inputfiles" class="dnd-label">${LangLoader::get_message('drag.and.drop.files', 'upload-common')} <span class="d-block"></span></label>
										<input type="file" name="upload_ranks[]" id="inputfiles" class="ufiles" />
									</div>
									<input type="hidden" name="max_file_size" value="{MAX_FILE_SIZE}">
									<div class="ready-to-load">
										<button type="button" class="button clear-list">${LangLoader::get_message('clear.list', 'upload-common')}</button>
										<span class="fa-stack fa-lg">
											<i class="far fa-file fa-stack-2x "></i>
											<strong class="fa-stack-1x files-nbr"></strong>
										</span>
									</div>
									<div class="modal-container">
										<button class="button upload-help" data-modal data-target="upload-helper" aria-label="${LangLoader::get_message('upload.helper', 'upload-common')}"><i class="fa fa-question" aria-hidden="true"></i></button>
										<div id="upload-helper" class="modal modal-animation">
											<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
											<div class="content-panel">
												<h3>${LangLoader::get_message('upload.helper', 'upload-common')}</h3>
												<p><strong>${LangLoader::get_message('max.file.size', 'upload-common')} :</strong> {MAX_FILE_SIZE_TEXT}</p>
												<p><strong>${LangLoader::get_message('allowed.extensions', 'upload-common')} :</strong> "{ALLOWED_EXTENSIONS}"</p>
											</div>
										</div>
									</div>
								</div>
								<ul class="ulist"></ul>
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset class="fieldset-submit">
					<legend>{L_UPLOAD}</legend>
					<div class="fieldset-inset">
						<button type="submit" name="upload" value="true" class="button submit">{L_UPLOAD}</button>
						<input type="hidden" name="token" value="{TOKEN}">
					</div>
				</fieldset>
				</form>

				<form action="admin_ranks_add.php" method="post" onsubmit="return check_form_rank_add();" class="fieldset-content">
					<p class="align-center">{L_REQUIRE}</p>
					<fieldset>
						<legend>{L_ADD_RANKS}</legend>
						<div class="fieldset-inset">
							<div class="form-element top-field">
								<label for="name">* {L_RANK_NAME}</label>
								<div class="form-field"><input type="text" id="name" name="name"></div>
							</div>
							<div class="form-element top-field">
								<label for="msg">* {L_NBR_MSG}</label>
								<div class="form-field"><input type="number" min="0" id="msg" name="msg"></div>
							</div>
							<div class="form-element">
								<label for="icon">{L_IMG_ASSOC}</label>
								<div class="form-field">
									<select name="icon" id="icon" onchange="img_change('img_icon', '{PATH_TO_ROOT}/forum/templates/images/ranks/' + this.options[selectedIndex].value)">
										{RANK_OPTIONS}
									</select>
									<img src="{PATH_TO_ROOT}/forum/templates/images/ranks/rank_0.png" id="img_icon" alt="rank_0.png" style="display: none;" />
								</div>
							</div>
						</div>
				</fieldset>

				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<div class="fieldset-inset">
						<input type="hidden" name="idc" value="{NEXT_ID}">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="add" value="true" class="button submit">{L_ADD}</button>
						<button type="reset" class="button reset-button" value="true">{L_RESET}</button>
					</div>
				</fieldset>
			</form>
		</div>
		<!--  -->
		<script>
			jQuery('#inputfiles').dndfiles({
				multiple: true,
				maxFileSize: '{MAX_FILE_SIZE}',
				maxFilesSize: '-1',
				allowedExtensions: ["{ALLOWED_EXTENSIONS}"],
				warningText: ${escapejs(LangLoader::get_message('warning.upload.disabled', 'upload-common'))},
				warningExtension: ${escapejs(LangLoader::get_message('warning.upload.extension', 'upload-common'))},
				warningFileSize: ${escapejs(LangLoader::get_message('warning.upload.file.size', 'upload-common'))},
				warningFilesNbr: ${escapejs(LangLoader::get_message('warning.upload.files.number', 'upload-common'))},
			});
		</script>
