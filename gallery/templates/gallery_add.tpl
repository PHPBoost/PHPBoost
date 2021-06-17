<form action="{U_GALLERY_ACTION_ADD}" method="post" enctype="multipart/form-data">
	<section id="module-gallery-add">
		<header class="section-header">
			<h1>{@gallery.module.title} - {@gallery.add.items}</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<article class="gallery-item several-items">
					<div class="content">
						# INCLUDE MESSAGE_HELPER #

						# START image_up #
							<strong>{@gallery.warning.success.upload}</strong>
							<div class="spacer"></div>
							<strong>{image_up.NAME}</strong>
							<div class="spacer"></div>
							<a class="offload" href="gallery.php?cat={image_up.ID_CATEGORY}&amp;id={image_up.ID}#pics_max"><img src="pics/{image_up.PATH}" alt="{image_up.NAME}" /></a>
							<div class="spacer"></div>
							<a class="offload" href="gallery.php?cat={image_up.ID_CATEGORY}">{image_up.CATEGORY_NAME}</a>
						# END image_up #

						<div class="image-quota-container">
							<span>{@gallery.upload.limit}</span>
							<strong>{CURRENT_ITEMS_NUMBER}/{MAX_ITEMS_NUMBER} {@gallery.items}</strong>
						</div>

						{CATEGORIES_TREE}
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
						<fieldset class="fieldset-submit">
							<legend>{@form.upload}</legend>
							<input type="hidden" name="max_file_size" value="2000000">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" name="vupload" value="true" class="button submit">{@form.upload}</button>
						</fieldset>
					</div>
				</article>
			</div>
		</div>
		<footer></footer>
	</section>
</form>
<script>
	jQuery('#gallery').dndfiles({
		multiple: true,
		maxFileSize: '{MAX_FILE_SIZE}',
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
