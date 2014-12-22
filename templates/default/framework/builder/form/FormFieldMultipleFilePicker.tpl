<script>
 var MultipleFilePicker = Class.create({
	integer : 2,
	id_input : ${escapejs(HTML_ID)},
	max_input : ${escapejs(MAX_INPUT)},
	add_file_input : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;
			var input = new Element('input', {'type' : 'file', 'id' : id, 'name' : id});
			$('input_files_list_' + this.id_input).insert(input);
			$(id).form.enctype = "multipart/form-data";
			var br = new Element('br');
			$('input_files_list_' + this.id_input).insert(br);
			this.incremente_integer();
		}
		if (this.integer == this.max_input) {
			$('add_' + this.id_input).hide();
		}
	},
	incremente_integer : function () {
		this.integer = this.integer + 1;
	},
});
var MultipleFilePicker = new MultipleFilePicker();

</script>
<div id="input_files_list_${escape(HTML_ID)}">
	<input type="file" name="${escape(HTML_ID)}_1" id="${escape(HTML_ID)}_1" # IF C_DISABLED # disabled="disabled" # ENDIF #><br />
	<input name="max_file_size" value="{MAX_FILE_SIZE}" type="hidden">
</div>
<a href="javascript:MultipleFilePicker.add_file_input();" class="fa fa-plus" id="add_${escape(HTML_ID)}"></a>
<script>
<!--
$("${escape(HTML_ID)}_1").form.enctype = "multipart/form-data";
</script>