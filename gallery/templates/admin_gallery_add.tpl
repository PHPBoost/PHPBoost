		# IF C_IMG #
			<script>
				function unselect_all_pictures() {
					# START list #
					jQuery('#' + '{list.ID}activ').prop('checked', false);
					# END list #
					jQuery('#change_all_pictures_selection_top').attr('onclick', "select_all_pictures();return false;");
					jQuery('#change_all_pictures_selection_top').html('{L_SELECT_ALL_PICTURES} <i class="far fa-square"></i>');
					jQuery('#change_all_pictures_selection_bottom').attr('onclick', "select_all_pictures();return false;");
					jQuery('#change_all_pictures_selection_bottom').html('{L_SELECT_ALL_PICTURES} <i class="far fa-square"></i>');
				};

				function select_all_pictures() {
					# START list #
					jQuery('#' + '{list.ID}activ').prop('checked', 'checked');
					# END list #
					jQuery('#change_all_pictures_selection_top').attr('onclick', "unselect_all_pictures();return false;");
					jQuery('#change_all_pictures_selection_top').html('{L_UNSELECT_ALL_PICTURES} <i class="far fa-check-square"></i>');
					jQuery('#change_all_pictures_selection_bottom').attr('onclick', "unselect_all_pictures();return false;");
					jQuery('#change_all_pictures_selection_bottom').html('{L_UNSELECT_ALL_PICTURES} <i class="far fa-check-square"></i>');
				};
			</script>
		# ENDIF #

		<nav id="admin-quick-menu">
			<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
				<i class="fa fa-bars" aria-hidden="true"></i> {L_GALLERY_MANAGEMENT}
			</a>
			<ul>
				<li>
					<a href="${Url::to_rel('/gallery')}" class="quick-link">${LangLoader::get_message('home', 'main')}</a>
				</li>
				<li>
					<a href="admin_gallery.php" class="quick-link">{L_GALLERY_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_gallery_add.php" class="quick-link">{L_GALLERY_PICS_ADD}</a>
				</li>
				<li>
					<a href="admin_gallery_config.php" class="quick-link">{L_GALLERY_CONFIG}</a>
				</li>
				<li>
					<a href="${relative_url(GalleryUrlBuilder::documentation())}" class="quick-link">${LangLoader::get_message('module.documentation', 'admin-modules-common')}</a>
				</li>
			</ul>
		</nav>

		<div id="admin-contents">

			# INCLUDE message_helper #

			<form action="admin_gallery_add.php" method="post" enctype="multipart/form-data" class="fieldset-content">
				<fieldset>
					<legend>{L_ADD_IMG}</legend>
					<div class="fieldset-inset">
						<div class="form-element full-field">
							# START image_up #
								<div class="align-center">
									<strong>{image_up.L_SUCCESS_UPLOAD}</strong> ${LangLoader::get_message('in', 'common')} <a href="{image_up.U_CAT}">{image_up.CATNAME}</a>
									<div class="spacer"></div>
									<strong>{image_up.NAME}</strong>
									<div class="spacer"></div>
									<a href="{image_up.U_IMG}"><img src="pics/{image_up.PATH}" alt="{image_up.NAME}" /></a>
									<div class="spacer"></div>
								</div>
							# END image_up #
						</div>

						<div class="form-element half-field">
							<label for="category">${LangLoader::get_message('form.category', 'common')}</label>
							<div class="form-field">
								<select name="idcat_post" id="category">
									{CATEGORIES}
								</select>
							</div>
						</div>
						<div class="form-element full-field">
							<label for="gallery">{L_UPLOAD_IMG}</label>
							<div class="form-field">
								<div class="dnd-area">
									<div class="dnd-dropzone">
										<label for="gallery" class="dnd-label">${LangLoader::get_message('drag.and.drop.files', 'main')} <p></p></label>
										<input type="file" name="gallery[]" id="gallery" class="ufiles" />
									</div>
									<div class="ready-to-load">
										<button type="button" class="button clear-list">${LangLoader::get_message('clear.list', 'main')}</button>
										<span class="fa-stack fa-lg">
											<i class="far fa-file fa-stack-2x "></i>
											<strong class="fa-stack-1x files-nbr"></strong>
										</span>
									</div>
									<div class="modal-container">
										<button class="button upload-help" data-modal data-target="upload-helper"><i class="fa fa-question"></i></button>
										<div id="upload-helper" class="modal modal-animation">
											<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
											<div class="content-panel">
												<h3>${LangLoader::get_message('upload.helper', 'main')}</h3>
												<p><strong>${LangLoader::get_message('allowed.extensions', 'main')} :</strong> "{ALLOWED_EXTENSIONS}"</p>
												<p><strong>{L_WIDTH_MAX} :</strong> {MAX_WIDTH} {L_UNIT_PX}</p>
												<p><strong>{L_HEIGHT_MAX} :</strong> {MAX_HEIGHT} {L_UNIT_PX}</p>
												<p><strong>${LangLoader::get_message('max.file.size', 'main')} :</strong> {MAX_FILE_SIZE_TEXT}</p>
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
					<legend>{L_UPLOAD_IMG}</legend>
					<div class="fieldset-inset">
						<input type="hidden" name="max_file_size" value="2000000">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="" value="true" class="button submit">{L_UPLOAD_IMG}</button>
					</div>
				</fieldset>
			</form>

			<form action="admin_gallery_add.php" method="post">
				# IF C_IMG #
					<article>
						<header>
							<div class="align-right"><a href="" onclick="unselect_all_pictures();return false;" id="change_all_pictures_selection_top" class="smaller">{L_UNSELECT_ALL_PICTURES} <i class="far fa-check-square"></i></a></div>
							<h2>{L_IMG_DISPO_GALLERY}</h2>
						</header>
						<div class="cell-flex cell-columns-4 cell-tile">
							# START list #
								<div class="cell">
									<div class="cell-header">
										<input type="text" name="{list.ID}name" value="{list.NAME}">
										<input type="hidden" name="{list.ID}uniq" value="{list.UNIQ_NAME}">
									</div>
									<div class="cell-body">
										<div class="cell-thumbnail cell-landscape">
											<img src="pics/{list.NAME}" alt="{list.NAME}" />
										</div>
									</div>
									<div class="cell-list">
										<ul>
											<li>
												${LangLoader::get_message('form.category', 'common')}
												<select name="{list.ID}cat" id="{list.ID}cat" class="select-cat">
													{list.CATEGORIES}
												</select>
											</li>
											<li class="li-stretch mini-checkbox">
												{L_SELECT}
												<label class="checkbox" for="{list.ID}activ">
													<input type="checkbox" checked="checked" id="{list.ID}activ" name="{list.ID}activ" value="1">
													<span>&nbsp;</span>
												</label>

											</li>
											<li class="li-stretch mini-checkbox">
												{L_DELETE}
												<label class="checkbox" for="{list.ID}del">
													<input type="checkbox" id="{list.ID}del" name="{list.ID}del" value="1">
													<span>&nbsp;</span>
												</label>

											</li>
										</ul>
									</div>
								</div>
							# END list #
						</div>
						<div class="align-right"><a href="" onclick="unselect_all_pictures();return false;" id="change_all_pictures_selection_bottom" class="smaller">{L_UNSELECT_ALL_PICTURES} <i class="far fa-check-square"></i></a></div>
					</article>


					<div class="form-element half-field">
						<label for="root_cat">{L_GLOBAL_CAT_SELECTION} <span class="field-description">{L_GLOBAL_CAT_SELECTION_EXPLAIN}</span></label>
						<div class="form-field">
							<select name="root_cat" id="root_cat">
								{ROOT_CATEGORIES}
							</select>
							<script>
							jQuery('#root_cat').on('change', function() {
								root_value = jQuery('#root_cat').val();
								# START list #
									jQuery('#' + '{list.ID}cat').val(root_value);
								# END list #
							});
							</script>
						</div>
					</div>

					<fieldset class="fieldset-submit">
						<legend>{L_SUBMIT}</legend>
						<div class="fieldset-inset">
							<input type="hidden" name="nbr_pics" value="{NBR_PICS}">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" name="valid" value="true" class="button submit">{L_SUBMIT}</button>
						</div>
					</fieldset>
				# ELSE #
					<div class="message-helper bgc notice">{L_NO_IMG}</div>
				# ENDIF #
			</form>
		</div>
		<script>
			jQuery('#gallery').dndfiles({
				multiple: true,
				maxFileSize: '{MAX_FILE_SIZE}',
				maxFilesSize: '-1',
				maxWidth: '{MAX_WIDTH}',
				maxHeight: '{MAX_HEIGHT}',
				allowedExtensions: ["{ALLOWED_EXTENSIONS}"],
				warningText: ${escapejs(LangLoader::get_message('warning.upload.disabled', 'main'))},
				warningExtension: ${escapejs(LangLoader::get_message('warning.upload.extension', 'main'))},
				warningFileSize: ${escapejs(LangLoader::get_message('warning.upload.file.size', 'main'))},
				warningFilesNbr: ${escapejs(LangLoader::get_message('warning.upload.files.nbr', 'main'))},
				warningFileDim: ${escapejs(LangLoader::get_message('warning.upload.file.dim', 'main'))},
			});
		</script>
