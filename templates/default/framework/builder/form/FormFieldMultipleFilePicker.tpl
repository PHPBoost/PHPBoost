<script>
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
<div id="input_files_list_${escape(HTML_ID)}">
	<label for="${escape(HTML_ID)}_1"><span class="sr-only">${LangLoader::get_message('form.source.name', 'common')} 1</span></label>
	<input type="file" name="${escape(HTML_ID)}_1" id="${escape(HTML_ID)}_1"# IF C_DISABLED # disabled="disabled"# ENDIF #><br />
	<input name="max_file_size" value="{MAX_FILE_SIZE}" type="hidden">
</div>
<a href="javascript:MultipleFilePicker.add_file_input();" id="add_${escape(HTML_ID)}" class="input-file-more" aria-label="${@add.files}"><i class="fa fa-plus" aria-hidden="true" title="${@add.files}"></i></a>
<script>
<!--
jQuery('#input_files_list_${escape(HTML_ID)}').parents('form')[0].enctype = "multipart/form-data";
-->
</script>
