# IF C_ITEMS #
	<script>
		function unselect_all_pictures() {
			# START list #
				jQuery('#' + '{list.ID}activ').prop('checked', false);
			# END list #
			jQuery('#change_all_pictures_selection_top').attr('onclick', "select_all_pictures();return false;");
			jQuery('#change_all_pictures_selection_top').html('{@gallery.select.all.items} <i class="far fa-square"></i>');
			jQuery('#change_all_pictures_selection_bottom').attr('onclick', "select_all_pictures();return false;");
			jQuery('#change_all_pictures_selection_bottom').html('{@gallery.select.all.items} <i class="far fa-square"></i>');
		};

		function select_all_pictures() {
			# START list #
				jQuery('#' + '{list.ID}activ').prop('checked', 'checked');
			# END list #
			jQuery('#change_all_pictures_selection_top').attr('onclick', "unselect_all_pictures();return false;");
			jQuery('#change_all_pictures_selection_top').html('{@gallery.deselect.all.items} <i class="far fa-check-square"></i>');
			jQuery('#change_all_pictures_selection_bottom').attr('onclick', "unselect_all_pictures();return false;");
			jQuery('#change_all_pictures_selection_bottom').html('{@gallery.deselect.all.items} <i class="far fa-check-square"></i>');
		};
	</script>
# ENDIF #

<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {@gallery.add.items}
	</a>
	<ul>
		<li>
			<a href="${Url::to_rel('/gallery')}" class="quick-link">{@form.home}</a>
		</li>
		<li>
			<a href="admin_gallery.php" class="quick-link">{@gallery.management}</a>
		</li>
		<li>
			<a href="admin_gallery_add.php" class="quick-link">{@gallery.add.items}</a>
		</li>
		<li>
			<a href="admin_gallery_config.php" class="quick-link">{@form.configuration}</a>
		</li>
		<li>
			<a href="${relative_url(GalleryUrlBuilder::documentation())}" class="quick-link">{@form.documentation}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">

	# INCLUDE MESSAGE_HELPER #

	<form action="admin_gallery_add.php" method="post" enctype="multipart/form-data" class="fieldset-content">
		<fieldset>
			<legend>{@gallery.add.items}</legend>
			<div class="fieldset-inset">
				<div class="form-element full-field">
					# START image_up #
						<div class="align-center">
							<strong>{@gallery.warning.success.upload}</strong> {@common.in} <a href="{image_up.U_CATEGORY}">{image_up.CATEGORY_NAME}</a>
							<div class="spacer"></div>
							<strong>{image_up.NAME}</strong>
							<div class="spacer"></div>
							<a href="{image_up.U_ITEM}"><img src="pics/{image_up.PATH}" alt="{image_up.NAME}" /></a>
							<div class="spacer"></div>
						</div>
					# END image_up #
				</div>

				<div class="form-element">
					<label for="category">{@form.category}</label>
					<div class="form-field form-field-select">
						<select name="id_category_post" id="category">
							{CATEGORIES_LIST}
						</select>
					</div>
				</div>
				<div class="form-element full-field">
					<label for="gallery">{@gallery.upload.items}</label>
					<div class="form-field">
						<div class="dnd-area">
							<div class="dnd-dropzone">
								<label for="gallery" class="dnd-label">{@upload.drag.and.drop.files} <span class="d-block"></span></label>
								<input type="file" name="gallery[]" id="gallery" class="ufiles" />
							</div>
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
										<p><strong>{@upload.allowed.extensions} :</strong> "{ALLOWED_EXTENSIONS}"</p>
										<p><strong>{@gallery.max.width} :</strong> {MAX_WIDTH} {@common.unit.pixels}</p>
										<p><strong>{@gallery.max.height} :</strong> {MAX_HEIGHT} {@common.unit.pixels}</p>
										<p><strong>{@upload.max.file.size} :</strong> {MAX_FILE_SIZE_TEXT}</p>
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
				<input type="hidden" name="max_file_size" value="2000000">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" name="" value="true" class="button submit">{@form.upload}</button>
			</div>
		</fieldset>
	</form>

	<form action="admin_gallery_add.php" method="post">
		<article>
			<header>
				<h2>{@gallery.server.item}</h2>
			</header>
			# IF C_ITEMS #
				<div class="align-right"><a href="#" onclick="unselect_all_pictures();return false;" id="change_all_pictures_selection_top">{@gallery.deselect.all.items} <i class="far fa-check-square"></i></a></div>
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
										{@form.category}
										<select name="{list.ID}cat" id="{list.ID}cat" class="select-cat">
											{list.CATEGORIES_LIST}
										</select>
									</li>
									<li class="li-stretch mini-checkbox">
										{@common.select}
										<label class="checkbox" for="{list.ID}activ">
											<input type="checkbox" checked="checked" id="{list.ID}activ" name="{list.ID}activ" value="1">
											<span>&nbsp;</span>
										</label>
									</li>
									<li class="li-stretch mini-checkbox">
										{@common.delete}
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
				<div class="align-right"><a href="#" onclick="unselect_all_pictures();return false;" id="change_all_pictures_selection_bottom">{@gallery.deselect.all.items} <i class="far fa-check-square"></i></a></div>


				<div class="form-element half-field">
					<label for="root_cat">
						{@gallery.category.selection}
						<span class="field-description">{@gallery.category.selection.clue}</span>
					</label>
					<div class="form-field">
						<select name="root_cat" id="root_cat">
							{ROOT_CATEGORIES_LIST}
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
					<legend>{@form.submit}</legend>
					<div class="fieldset-inset">
						<input type="hidden" name="nbr_pics" value="{ITEMS_NUMBER}">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="valid" value="true" class="button submit">{@form.submit}</button>
					</div>
				</fieldset>
			# ELSE #
				<div class="message-helper bgc notice">{@gallery.no.ftp.item}</div>
			# ENDIF #
		</article>

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
		warningText: ${escapejs(@H|upload.warning.disabled)},
		warningExtension: ${escapejs(@H|upload.warning.extension)},
		warningFileSize: ${escapejs(@H|upload.warning.file.size)},
		warningFilesNbr: ${escapejs(@H|upload.warning.files.number)},
		warningFileDim: ${escapejs(@H|upload.warning.file.dim)},
	});
</script>
