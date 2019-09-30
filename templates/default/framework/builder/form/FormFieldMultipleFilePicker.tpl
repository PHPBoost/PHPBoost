<div id="input_files_list_${escape(HTML_ID)}">
	<div class="dnd-area">
		<div class="dnd-dropzone">
			<label for="inputfiles" class="dnd-label">{@drag.and.drop.files}<p></p></label>
			<input type="file" name="upload_file[]" id="inputfiles_${escape(HTML_ID)}" class="ufiles" />
		</div>
		<input type="hidden" name="max_file_size" value="{MAX_WEIGHT}">
		<div class="ready-to-load">
			<button type="button" class="clear-list">{@clear.list}</button>
			<span class="fa-stack fa-lg">
				<i class="far fa-file fa-stack-2x "></i>
				<strong class="fa-stack-1x files-nbr"></strong>
			</span>
		</div>
		<div class="modal-container">
			<button class="upload-help" data-trigger data-target="upload-helper" aria-label="{@upload.helper}"><i class="fa fa-question"></i></button>
			<div id="upload-helper" class="modal modal-animation">
				<div class="close-modal" aria-label="close"></div>
				<div class="content-panel">
					<h3>{@upload.helper}</h3>
					<p><strong>{@max.files.size} :</strong> {MAX_FILES_SIZE_TEXT}</p>
					<p><strong>{@allowed.extensions} :</strong> "{ALLOWED_EXTENSIONS}"</p>
				</div>
			</div>
		</div>
	</div>
	<ul class="ulist"></ul>
</div>

<script>
	jQuery('#inputfiles_${escape(HTML_ID)}').parents('form')[0].enctype = "multipart/form-data";
	jQuery('#inputfiles_${escape(HTML_ID)}').dndfiles({
		multiple: true,
		maxFileSize: '{MAX_WEIGHT}',
		maxFilesSize: '{MAX_FILE_SIZE}',
		allowedExtensions: ["{ALLOWED_EXTENSIONS}"],
		warningText: ${escapejs(LangLoader::get_message('warning.upload.disabled', 'main'))},
		warningExtension: ${escapejs(LangLoader::get_message('warning.upload.extension', 'main'))},
		warningFileSize: ${escapejs(LangLoader::get_message('warning.upload.file.size', 'main'))},
		warningFilesNbr: ${escapejs(LangLoader::get_message('warning.upload.files.nbr', 'main'))},
	});
</script>
