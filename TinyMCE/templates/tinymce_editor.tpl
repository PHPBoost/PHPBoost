<script>
<!--
var displayed = new Array();
displayed[${escapejs(FIELD)}] = false;
function XMLHttpRequest_preview(field)
{
	if( XMLHttpRequest_preview.arguments.length == 0 )
		field = ${escapejs(FIELD)};

	var contents = tinymce.activeEditor.getContent();
	var preview_field = 'xmlhttprequest-preview' + field;
	
	if( contents != "" )
	{
		if(!displayed[field])
			jQuery("#" + preview_field).slideDown(500);

		jQuery('#loading_preview' + field).show();

		displayed[field] = true;

		jQuery.ajax({
			url: PATH_TO_ROOT + "/kernel/framework/ajax/content_xmlhttprequest.php",
			type: "post",
			data: {
				token: '{TOKEN}',
				path_to_root: '{PHP_PATH_TO_ROOT}',
				editor: 'TinyMCE',
				page_path: '{PAGE_PATH}',
				contents: contents,
				ftags: '{FORBIDDEN_TAGS}'
			},
			success: function(returnData){
				jQuery('#' + preview_field).html(returnData);

				jQuery('#loading_preview' + field).hide();
			},
			error: function(e){
				alert(e);
			}
		});
	}
	else
		alert("{L_REQUIRE_TEXT}");
}

function insertTinyMceContent(content)
{
	var ed = tinymce.activeEditor;
	ed.insertContent(content);
	ed.windowManager.close();
}

function setTinyMceContent(content)
{
	tinymce.activeEditor.setContent(content);
}

-->
</script>
<div style="position:relative;display:none;" id="loading_preview{FIELD}">
	<div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;">
		<i class="fa fa-spinner fa-2x fa-spin"></i>
	</div>
</div>
<div style="display:none;" class="xmlhttprequest-preview" id="xmlhttprequest-preview{FIELD}"></div>

# IF NOT C_NOT_JS_INCLUDED #
	<script src="{PATH_TO_ROOT}/TinyMCE/templates/js/tinymce/tinymce.min.js"></script>
# ENDIF #

<script>
<!--
var field = "{FIELD}";
tinymce.init({
	selector : "textarea#" + field,
	language : "{LANGUAGE}",
	plugins: [
		"advlist autolink autosave link image lists charmap hr anchor",
		"searchreplace wordcount visualblocks visualchars fullscreen insertdatetime media",
		"table contextmenu directionality smileys paste textcolor colorpicker textpattern"
	],
	external_plugins: {"nanospell": '{PATH_TO_ROOT}/TinyMCE/templates/js/tinymce/plugins/nanospell/plugin.js'},
	nanospell_server: "php",
	nanospell_dictionary: "fr, en",
	
	# IF C_TOOLBAR1 #toolbar1: "{TOOLBAR1}",# ENDIF #
	# IF C_TOOLBAR2 #toolbar2: "{TOOLBAR2}",# ENDIF #
	# IF C_TOOLBAR3 #toolbar3: "{TOOLBAR3}",# ENDIF #

	menubar: false,
	toolbar_items_size: 'small',
	advlist_number_styles: 'default',
    advlist_bullet_styles: 'default',
	setup : function(ed) {
		ed.addButton('insertfile', {
			icon: 'browse',
			onclick: function (field_name) {
				ed.windowManager.open({ 
					title: '',         
					url: '{PATH_TO_ROOT}/user/upload.php?popup=1&amp;close_button=0&amp;fd=' + field_name + '&amp;edt=TinyMCE',
					width: 720,
					height: 500,            
				}); 
			},
			tooltip: 'Insert file'
		});
	},

	smileys: [
		# START smiley #
			# IF smiley.C_NEW_ROW #[# ENDIF #
				{ shortcut: '{smiley.CODE}', url: '{smiley.URL}', title: '{smiley.CODE}' }# IF NOT smiley.C_LAST_OF_THE_ROW #,# ENDIF #
			# IF smiley.C_END_ROW #]# IF NOT smiley.C_LAST_ROW #,# ENDIF ## ENDIF #
		# END smiley #
	]
});
-->
</script>