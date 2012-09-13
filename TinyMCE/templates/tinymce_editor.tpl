<script type="text/javascript">
<!--
var displayed = new Array();
displayed[${escapejs(FIELD)}] = false;
function XMLHttpRequest_preview(field)
{
	if( XMLHttpRequest_preview.arguments.length == 0 )
		field = ${escapejs(FIELD)};

	{TINYMCE_TRIGGER}
	var contents = $(field).value;
	var preview_field = 'xmlhttprequest_preview' + field;
	
	if( contents != "" )
	{
		if( !displayed[field] ) 
			Effect.BlindDown(preview_field, { duration: 0.5 });
		
		var loading = $('loading_preview' + field);
		if( loading )
			loading.style.display = 'block';
		displayed[field] = true;
	
		new Ajax.Request(
			'{PATH_TO_ROOT}/kernel/framework/ajax/content_xmlhttprequest.php',
			{
				method: 'post',
				parameters: {
					token: '{TOKEN}',
					path_to_root: '{PHP_PATH_TO_ROOT}',
					editor: '{EDITOR_NAME}',
					page_path: '{PAGE_PATH}',  
						contents: contents,
						ftags: '{FORBIDDEN_TAGS}'
					},
					onSuccess: function(response)
					{
						$(preview_field).update(response.responseText);
						if( loading )
							loading.style.display = 'none';
					}
			}
		);
	}	
	else
		alert("{L_REQUIRE_TEXT}");
}
		
function insertTinyMceContent(content)
{
	tinyMCE.execCommand('mceInsertContent', false, content, {skip_undo : 1});
}

-->
</script>
<div style="position:relative;display:none;" id="loading_preview{FIELD}"><div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading.gif" alt="" /></div></div>
<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_preview{FIELD}"></div>

# IF NOT C_NOT_JS_INCLUDED #
	<script type="text/javascript" src="{PATH_TO_ROOT}/TinyMCE/templates/js/tinymce/tiny_mce.js"></script>
# ENDIF #
	
<script type="text/javascript">
<!--
tinyMCE.init({
	mode : "exact",
	elements : "{FIELD}", 
	theme : "advanced",
	language : "fr",
	content_css : "{PATH_TO_ROOT}/templates/{THEME}/theme/tinymce.css",
	theme_advanced_buttons1 : "{THEME_ADVANCED_BUTTONS1}", 
	theme_advanced_buttons2 : "{THEME_ADVANCED_BUTTONS2}", 
	theme_advanced_buttons3 : "{THEME_ADVANCED_BUTTONS3}",
	theme_advanced_toolbar_location : "top", 
	theme_advanced_toolbar_align : "center", 
	theme_advanced_statusbar_location : "bottom",
	plugins : "table,searchreplace,inlinepopups,fullscreen,emotions",
	extended_valid_elements : "font[face|size|color|style],span[class|align|style],a[href|name]",
	theme_advanced_resize_horizontal : false, 
	theme_advanced_resizing : true
});
-->
</script>

# IF C_UPLOAD_MANAGEMENT #
	<div style="float:right;margin-left:5px;">
<a style="font-size: 10px;" title="{L_BB_UPLOAD}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd={IDENTIFIER}&amp;edt={EDITOR_NAME}', '', 'height=500,width=720,resizable=yes,scrollbars=yes');return false;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" alt="" /></a>
	</div>
# ENDIF #