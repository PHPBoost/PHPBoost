<form action="{U_GALLERY_ACTION_ADD}" method="post" enctype="multipart/form-data">
	<section id="module-gallery-add">
		<header>
			<h1>{L_GALLERY} - {L_ADD_IMG}</h1>
			<div class="align-right">
				{PAGINATION}
			</div>
		</header>
		<div class="content">
			# INCLUDE message_helper #

			# START image_up #
			<strong>{image_up.L_SUCCESS_UPLOAD}</strong>
			<div class="spacer"></div>
			<strong>{image_up.NAME}</strong>
			<div class="spacer"></div>
			<a href="gallery.php?cat={image_up.ID_CAT}&amp;id={image_up.ID}#pics_max"><img src="pics/{image_up.PATH}" alt="{image_up.NAME}" /></a>
			<div class="spacer"></div>
			<a href="gallery.php?cat={image_up.ID_CAT}">{image_up.CAT_NAME}</a>
			# END image_up #

			# START image_quota #
			<div class="image-quota-container">
				<strong>{image_quota.L_IMAGE_QUOTA}</strong>
			</div>
			# END image_quota #

			{CATEGORIES_TREE}
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
			<fieldset class="fieldset-submit">
				<legend></legend><input type="hidden" name="max_file_size" value="2000000">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" name="vupload" value="true" class="button submit">{L_UPLOAD}</button>
			</fieldset>
		</div>
		<footer>
			<div class="align-right">
				{PAGINATION}
			</div>
		</footer>
	</section>
</form>
<script>
	jQuery('#gallery').dndfiles({
		multiple: true,
		maxFileSize: '{MAX_FILE_SIZE}',
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
