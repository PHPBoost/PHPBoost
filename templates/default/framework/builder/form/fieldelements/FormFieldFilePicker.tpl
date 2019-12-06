<div class="dnd-area">
	<div class="dnd-dropzone">
		<label for="inputfiles" class="dnd-label"># IF C_MULTIPLE #{@drag.and.drop.files}# ELSE #{@drag.and.drop.file}# ENDIF #<p></p></label>
		<input type="file" name="${escape(NAME)}# IF C_MULTIPLE #[]# ENDIF #" id="${escape(HTML_ID)}" class="ufiles"# IF C_DISABLED # disabled="disabled" # ENDIF # />
	</div>
	<input type="hidden" name="max_file_size" value="{MAX_FILE_SIZE}">
	<div class="ready-to-load">
		<button type="button" class="button clear-list">{@clear.list}</button>
		# IF C_MULTIPLE #
			<span class="fa-stack fa-lg">
				<i class="far fa-file fa-stack-2x "></i>
				<strong class="fa-stack-1x files-nbr"></strong>
			</span>
		# ENDIF #
	</div>
	<div class="modal-container">
		<button class="button upload-help" data-modal data-target="upload-helper" aria-label="{@upload.helper}"><i class="fa fa-question"></i></button>
		<div id="upload-helper" class="modal modal-animation">
			<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
			<div class="content-panel">
				<h3>{@upload.helper}</h3>
				# IF IS_ADMIN #
					<p><strong>{@max.file.size} :</strong> {MAX_FILE_SIZE_TEXT}</p>
				# ELSE #
					<p><strong>{@max.files.size} :</strong> {MAX_FILES_SIZE_TEXT}</p>
				# ENDIF #
				<p><strong>{@allowed.extensions} :</strong> "{ALLOWED_EXTENSIONS}"</p>
			</div>
		</div>
	</div>
</div>
<ul class="ulist"></ul>

<script>
	jQuery('#${escape(HTML_ID)}').parents('form').first()[0].enctype = "multipart/form-data";
	jQuery('#${escape(HTML_ID)}').dndfiles({
		multiple:# IF C_MULTIPLE # true# ELSE # false# ENDIF #,
		maxFileSize: '{MAX_FILE_SIZE}',
		maxFilesSize: '{MAX_FILES_SIZE}',
		allowedExtensions: ["{ALLOWED_EXTENSIONS}"],
		warningText: ${escapejs(LangLoader::get_message('warning.upload.disabled', 'main'))},
		warningExtension: ${escapejs(LangLoader::get_message('warning.upload.extension', 'main'))},
		warningFileSize: ${escapejs(LangLoader::get_message('warning.upload.file.size', 'main'))},
		warningFilesNbr: ${escapejs(LangLoader::get_message('warning.upload.files.nbr', 'main'))},
	});
</script>
