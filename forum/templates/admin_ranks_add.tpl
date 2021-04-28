		<script>
			function check_form_rank_add()
			{
				if(document.getElementById('name').value == "") {
					alert("{@forum.require.rank.name}");
					return false;
				}
				if(document.getElementById('msg').value == "") {
					alert("{@forum.require.rank.messages.number}");
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
		</script>

		<nav id="admin-quick-menu">
			<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
				<i class="fa fa-bars" aria-hidden="true"></i> {@forum.ranks.management}
			</a>
			<ul>
				<li>
					<a href="{PATH_TO_ROOT}/forum" class="quick-link">{@common.home}</a>
				</li>
				<li>
					<a href="admin_ranks.php" class="quick-link">{@forum.ranks.management}</a>
				</li>
				<li>
					<a href="admin_ranks_add.php" class="quick-link">{@forum.rank.add}</a>
				</li>
				<li>
					<a href="${relative_url(ForumUrlBuilder::configuration())}" class="quick-link">{@form.configuration}</a>
				</li>
			</ul>
		</nav>

		<div id="admin-contents">

			# INCLUDE MESSAGE_HELPER #

			<form action="admin_ranks_add.php" method="post" enctype="multipart/form-data" class="fieldset-content">
				<fieldset>
					<legend>{@forum.rank.add}</legend>
					<div class="fieldset-inset">
						<div class="form-element full-field">
							<label for="upload_ranks">
								{@forum.upload.rank.thumbnail}
								<span class="field-description">{@forum.upload.rank.thumbnail.clue}</span>
							</label>
							<div class="form-field">
								<div class="dnd-area">
									<div class="dnd-dropzone">
										<label for="inputfiles" class="dnd-label">{@upload.drag.and.drop.files} <span class="d-block"></span></label>
										<input type="file" name="upload_ranks[]" id="inputfiles" class="ufiles" />
									</div>
									<input type="hidden" name="max_file_size" value="{MAX_FILE_SIZE}">
									<div class="ready-to-load">
										<button type="button" class="button clear-list">{@upload.clear.list}</button>
										<span class="fa-stack fa-lg">
											<i class="far fa-file fa-stack-2x "></i>
											<strong class="fa-stack-1x files-nbr"></strong>
										</span>
									</div>
									<div class="modal-container">
										<button class="button upload-help" data-modal data-target="upload-helper" aria-label="{@upload.upload.helper}"><i class="fa fa-question" aria-hidden="true"></i></button>
										<div id="upload-helper" class="modal modal-animation">
											<div class="close-modal" aria-label="{@common.close}"></div>
											<div class="content-panel">
												<h3>{@upload.upload.helper}</h3>
												<p><strong>{@upload.max.file.size} :</strong> {MAX_FILE_SIZE_TEXT}</p>
												<p><strong>{@upload.allowed.extensions} :</strong> "{ALLOWED_EXTENSIONS}"</p>
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
					<legend>{@form.upload}</legend>
					<div class="fieldset-inset">
						<button type="submit" name="upload" value="true" class="button submit">{@form.upload}</button>
						<input type="hidden" name="token" value="{TOKEN}">
					</div>
				</fieldset>
				</form>

				<form action="admin_ranks_add.php" method="post" onsubmit="return check_form_rank_add();" class="fieldset-content">
					<p class="align-center">{@form.required.fields}</p>
					<fieldset>
						<legend class="sr-only">{@forum.rank.add}</legend>
						<div class="fieldset-inset">
							<div class="form-element top-field">
								<label for="name">* {@forum.rank.name}</label>
								<div class="form-field form-field-text"><input type="text" id="name" name="name"></div>
							</div>
							<div class="form-element top-field">
								<label for="msg">* {@forum.rank.messages.number}</label>
								<div class="form-field form-field-number"><input type="number" min="0" id="msg" name="msg"></div>
							</div>
							<div class="form-element">
								<label for="icon">{@forum.rank.thumbnail}</label>
								<div class="form-field form-field-select">
									<select name="icon" id="icon" onchange="img_change('img_icon', '{PATH_TO_ROOT}/forum/templates/images/ranks/' + this.options[selectedIndex].value)">
										{RANK_OPTIONS}
									</select>
								</div>
							</div>
							<div id="img_icon" class="form-element" style="display: none;">
								<label for="icon">{@form.preview}</label>
								<div class="form-field form-field-select">
									<img src="{PATH_TO_ROOT}/forum/templates/images/ranks/rank_0.png" alt="rank_0.png" />
								</div>
							</div>
						</div>
				</fieldset>

				<fieldset class="fieldset-submit">
					<legend>{@form.submit}</legend>
					<div class="fieldset-inset">
						<input type="hidden" name="idc" value="{NEXT_ID}">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="add" value="true" class="button submit">{@form.submit}</button>
						<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
					</div>
				</fieldset>
			</form>
		</div>

		<script>
			jQuery('#inputfiles').dndfiles({
				multiple: true,
				maxFileSize: '{MAX_FILE_SIZE}',
				maxFilesSize: '-1',
				allowedExtensions: ["{ALLOWED_EXTENSIONS}"],
				warningText: ${escapejs(@H|upload.warning.disabled)},
				warningExtension: ${escapejs(@H|upload.warning.extension)},
				warningFileSize: ${escapejs(@H|upload.warning.file.size)},
				warningFilesNbr: ${escapejs(@H|upload.warning.files.number)},
			});
		</script>
