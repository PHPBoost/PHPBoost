<div id="${escape(HTML_ID)}_field"# IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element # IF C_HAS_FIELD_CLASS #{FIELD_CLASS}# ENDIF #">
	# IF C_HAS_LABEL #
		<label for="${escape(HTML_ID)}">
			{LABEL}
			# IF C_DESCRIPTION #
			<span class="field-description">{DESCRIPTION}</span>
			# ENDIF #
		</label>
	# ENDIF #

	<div class="form-field">
		<input type="text" name="${escape(NAME)}" id="${escape(HTML_ID)}" value="{VALUE}" class="field-xlarge ${escape(CLASS)}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY #readonly="readonly"# ENDIF #/>
		# IF C_AUTH_UPLOAD #
			<a title="${LangLoader::get_message('files_management', 'main')}" href="#" class="fa fa-cloud-upload fa-2x" onclick="jQuery('#' + ${escapejs(HTML_ID)}).val('');window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=${escape(NAME)}&amp;parse=true&amp;no_path=true', '', 'height=500,width=720,resizable=yes,scrollbars=yes');return false;"></a>
		# ENDIF #
		# IF C_REQUIRED #<i class="fa picture-field-required"></i># ENDIF #
		<i class="fa picture-status-constraint" id="onblurContainerResponse${escape(HTML_ID)}"></i>
		<div class="text-status-constraint" style="display:none" id="onblurMessageResponse${escape(HTML_ID)}"></div>
	</div>
</div>
<div id="${escape(HTML_ID)}_preview"# IF C_PREVIEW_HIDDEN # style="display:none;"# ENDIF # class="form-element # IF C_HAS_FIELD_CLASS #{FIELD_CLASS}# ENDIF #">
	<label for="${escape(HTML_ID)}_preview">
		${LangLoader::get_message('form.picture.preview', 'common')}
	</label>

	<div class="form-field">
		<img id="${escape(HTML_ID)}_preview_picture" src="# IF NOT C_PREVIEW_HIDDEN #{FILE_PATH}# ENDIF #" alt="" style="vertical-align:top" />
	</div>
</div>

# INCLUDE ADD_FIELD_JS #

<script>
<!--

jQuery("#" + ${escapejs(NAME)}).blur(function(){
	var fileName = HTMLForms.getField(${escapejs(ID)}).getValue();
	var extension = fileName.substring(fileName.lastIndexOf('.')+1);
	
	if ((/^(jpg|jpeg|gif|tiff|png)$/i).test(extension)) {
		jQuery('#${escape(HTML_ID)}_preview').show();
		jQuery.ajax({
			url: PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/image/preview/',
			type: "post",
			dataType: "json",
			data: {token:${escapejs(TOKEN)}, image:HTMLForms.getField(${escapejs(ID)}).getValue()},
			beforeSend: function(){
				jQuery('#${escape(HTML_ID)}_preview_picture').hide();
				jQuery('#${escape(HTML_ID)}_preview_picture').after('<i id="${escape(HTML_ID)}_preview_loading" class="fa fa-spinner fa-spin"></i>');
			},
			success: function(returnData){
				jQuery('#${escape(HTML_ID)}_preview_loading').remove();

				if (returnData.url) {
					jQuery('#${escape(HTML_ID)}_preview_picture').attr("src", returnData.url);
					jQuery('#${escape(HTML_ID)}_preview_picture').css( "display", "inline");
				} else {
					jQuery('#${escape(HTML_ID)}_preview_picture').hide();
				}
			},
			error: function(e){
				alert(e);
			}
		});
	} else {
		jQuery('#${escape(HTML_ID)}_preview').hide();
	}
});
-->
</script>