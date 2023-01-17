<script>
	var displayed = new Array();
	displayed[${escapejs(FIELD)}] = false;
	function XMLHttpRequest_preview(field)
	{
		if( XMLHttpRequest_preview.arguments.length == 0 )
			field = ${escapejs(FIELD)};

		var contents = tinymce.activeEditor.getContent();
		var preview_field = 'xmlhttprequest-preview' + field;

		if( contents == "" )
			contents = jQuery('#' + field).val();

		if( contents != "" )
		{
			if(!displayed[field])
				jQuery("#" + preview_field).slideDown(500);

			jQuery('#loading-preview-' + field).show();

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

					jQuery('#loading-preview-' + field).hide();
				}
			});
		}
		else
			alert(${escapejs(@warning.text)});
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
</script>
<div id="loading-preview-{FIELD}" class="loading-preview-container" style="display: none;">
	<div class="loading-preview">
		<i class="fa fa-spinner fa-2x fa-spin"></i>
	</div>
</div>
<div id="xmlhttprequest-preview{FIELD}" class="xmlhttprequest-preview" style="display: none;"></div>

# IF NOT C_NOT_JS_INCLUDED #
	<script src="{PATH_TO_ROOT}/TinyMCE/templates/js/tinymce/tinymce.min.js"></script>
# ENDIF #

<script>
	tinymce.init({
		selector : "textarea\#{FIELD}",
		language : "{LANGUAGE}",
		plugins: [
			"advlist autolink autoresize autosave link image lists charmap hr anchor",
			"searchreplace wordcount visualblocks visualchars fullscreen insertdatetime media",
			"table directionality emoticons paste textpattern imagetools"
		],

		# IF C_TOOLBAR #
		toolbar: '{TOOLBAR}',
		# ENDIF #

		toolbar_sticky: true,
		toolbar_mode: 'wrap',
		menubar: false,
		branding: false,
		autoresize_max_height: '500px',
		advlist_number_styles: 'default',
		advlist_bullet_styles: 'default',
		block_formats: 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Preformatted=pre',
		fontsize_formats: '5pt 10pt 15pt 20pt 25pt 30pt 35pt 40pt 45pt',
		convert_urls: false,
		media_alt_source: false,
		media_poster: false,
		link_title: false,
		target_list: false,
		content_css: [
			"{PATH_TO_ROOT}/templates/__default__/theme/font-awesome/css/all.css",
			"{PATH_TO_ROOT}/templates/{THEME}/theme/@import.css",
			"{PATH_TO_ROOT}/TinyMCE/templates/tinymce.css"
		],
		style_formats: [
			{title: ${escapejs(@warning.success)}, inline: 'span', classes: 'success'},
			{title: ${escapejs(@warning.question)}, inline: 'span', classes: 'question'},
			{title: ${escapejs(@warning.notice)}, inline: 'span', classes: 'notice'},
			{title: ${escapejs(@warning.warning)}, inline: 'span', classes: 'warning'},
			{title: ${escapejs(@warning.error)}, inline: 'span', classes: 'error'}
		],
		setup : function(ed) {
			ed.ui.registry.addButton('insertfile', {
				icon: 'browse',
				onAction: function (field_name) {
					ed.windowManager.openUrl({ 
						title: '',
						url: '{PATH_TO_ROOT}/user/upload.php?popup=1&close_button=0&fd=# IF C_HTMLFORM #' + HTMLForms.get("{FORM_NAME}").getId() + '_{FIELD_NAME}# ELSE #{FIELD}# ENDIF #&edt=TinyMCE',
						width: 720,
						height: 500,
					}); 
				},
				tooltip: 'Insert file'
			});
			ed.on('blur', function(){
				jQuery("\#{FIELD}").val(ed.getContent());
				# IF C_HTMLFORM #
				HTMLForms.get("{FORM_NAME}").getField("{FIELD_NAME}").enableValidationMessage();
				HTMLForms.get("{FORM_NAME}").getField("{FIELD_NAME}").liveValidate();
				# ENDIF #
			})
		}
	});
</script>
