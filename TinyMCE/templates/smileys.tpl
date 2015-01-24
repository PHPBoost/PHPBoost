<script src="{PATH_TO_ROOT}/TinyMCE/templates/js/tinymce/tiny_mce_popup.js"></script>
<script>
var EmotionsDialog = {
	init : function(ed) {
		tinyMCEPopup.resizeToInnerSize();
	},

	insert : function(file, title) {
		var ed = tinyMCEPopup.editor, dom = ed.dom;

		tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML('img', {
			src : file,
			alt : title,
			title : title,
			border : 0
		}));

		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(EmotionsDialog.init, EmotionsDialog);
</script>
<table>
	<thead>
		<tr> 
			<th colspan="{COLSPAN}">{L_SMILEY}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			# START smiley #
				{smiley.TR_START}
					<td>
						<a href="javascript:EmotionsDialog.insert('{smiley.URL}', '{smiley.CODE}')">{smiley.IMG}</a>&nbsp;&nbsp;
					</td>
					# START smiley.td #
					{smiley.td.TD}
					# END smiley.td #
				{smiley.TR_END}
			# END smiley #
		</tr>
	</tbody>
</table>