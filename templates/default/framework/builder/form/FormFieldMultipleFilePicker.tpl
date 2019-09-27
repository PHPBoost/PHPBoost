<!-- <script>
function MultipleFilePicker(){
	this.integer = 2;
	this.id_input = ${escapejs(HTML_ID)};
	this.max_input = {MAX_INPUT};
};
MultipleFilePicker.prototype = {
	add_file_input : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;
			var label = ${escapejs(LangLoader::get_message('form.source.name', 'common'))} + " " + this.integer;

			jQuery('<label for="${escape(HTML_ID)}_' + this.integer + '"><span class="sr-only">' + label + '</span></label>').appendTo('#input_files_list_' + this.id_input);
			jQuery('<input/>', {'type' : 'file', 'id' : id, 'name' : id}).appendTo('#input_files_list_' + this.id_input);

			jQuery('<br/>').appendTo('#input_files_list_' + this.id_input);

			this.integer = this.integer + 1;
		}
		if (this.integer == this.max_input) {
			jQuery('#add_' + this.id_input).hide();
		}
	}
};

var MultipleFilePicker = new MultipleFilePicker();

</script>
	<label for="${escape(HTML_ID)}_1"><span class="sr-only">${LangLoader::get_message('form.source.name', 'common')} 1</span></label>
	<input type="file" name="${escape(HTML_ID)}_1" id="${escape(HTML_ID)}_1"# IF C_DISABLED # disabled="disabled"# ENDIF #><br />
	<input name="max_file_size" value="{MAX_FILE_SIZE}" type="hidden">
</div>
<a href="javascript:MultipleFilePicker.add_file_input();" id="add_${escape(HTML_ID)}" class="input-file-more" aria-label="${@add.files}"><i class="fa fa-plus" aria-hidden="true"></i></a> -->

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
					<p><strong>{@max.files.size} :</strong> {MAX_FILE_SIZE}</p>
					<p><strong>{@allowed.extensions} :</strong> "{ALLOWED_EXTENSIONS}"</p>
				</div>
			</div>
		</div>
	</div>
	<ul class="ulist"></ul>
</div>

<script>
<!--
jQuery('#input_files_list_${escape(HTML_ID)}').parents('form')[0].enctype = "multipart/form-data";
-->
</script>

<script>
	$('#inputfiles_${escape(HTML_ID)}').dndfiles({
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
