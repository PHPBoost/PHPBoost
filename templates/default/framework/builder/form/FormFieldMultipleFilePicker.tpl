<script>
function MultipleFilePicker(){
	this.integer = 2;
	this.id_input = ${escapejs(HTML_ID)};
	this.max_input = ${escapejs(MAX_INPUT)};
};
MultipleFilePicker.prototype = {
	add_file_input : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

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
	<input type="file" name="${escape(HTML_ID)}_1" id="${escape(HTML_ID)}_1"# IF C_DISABLED # disabled="disabled"# ENDIF #><br />
	<input name="max_file_size" value="{MAX_FILE_SIZE}" type="hidden">
</div>
<a href="javascript:MultipleFilePicker.add_file_input();" class="fa fa-plus" id="add_${escape(HTML_ID)}" title="${LangLoader::get_message('add', 'common')}"></a>
<script>
<!--
jQuery('#input_files_list_${escape(HTML_ID)}').parents('form')[0].enctype = "multipart/form-data";
-->
</script>