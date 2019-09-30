<div id="input_files_list_${escape(HTML_ID)}">
	<div class="dnd-area">
		<div class="dnd-dropzone">
			<label for="inputfiles" class="dnd-label">
                {@drag.and.drop.file}
                <p></p>
            </label>
			<input type="file" name="${escape(NAME)}" id="inputfile_${escape(HTML_ID)}" class="ufiles" # IF C_DISABLED # disabled="disabled" # ENDIF # />
		</div>
		<input type="hidden" name="max_file_size" value="{MAX_FILE_SIZE}">
	</div>
	<ul class="ulist"></ul>
</div>
<script>
	jQuery("#inputfile_${escape(HTML_ID)}").parents("form:first")[0].enctype = "multipart/form-data";
	jQuery('#inputfile_${escape(HTML_ID)}').dndfiles({
		multiple: false,
		maxFileSize: '{MAX_FILE_SIZE}',
		maxFilesSize: '-1',
		allowedExtensions: ["zip", "gz"],
		warningText: ${escapejs(LangLoader::get_message('warning.upload.disabled', 'main'))},
		warningExtension: ${escapejs(LangLoader::get_message('warning.upload.extension', 'main'))},
		warningFileSize: ${escapejs(LangLoader::get_message('warning.upload.file.size', 'main'))},
		warningFilesNbr: ${escapejs(LangLoader::get_message('warning.upload.files.nbr', 'main'))},
	});
</script>
