<div id="${escape(HTML_ID)}_field"# IF C_HIDDEN # style="display: none;" # ENDIF # class="form-field-thumbnail-choice# IF C_REQUIRED_AND_HAS_VALUE # constraint-status-right# ENDIF ## IF C_HAS_FIELD_CLASS # {FIELD_CLASS}# ENDIF ## IF C_HAS_CSS_CLASS # {CLASS}# ENDIF #">
	<div id="onblurContainerResponse${escape(HTML_ID)}" class="# IF C_HAS_FORM_FIELD_CLASS # {FORM_FIELD_CLASS}# ENDIF # picture-status-constraint# IF C_REQUIRED # field-required# ENDIF #">
		<div class="form-field-radio">
			<label class="radio" for="${escape(ID)}_none">
				<input id="${escape(ID)}_none" type="radio" name="${escape(NAME)}" value="none" # IF C_NONE_CHECKED # checked="checked" # ENDIF #>
				<span> {@thumbnail.none}</span>
			</label>
		</div>
		# IF C_DEFAULT_URL #
		<div class="form-field-radio">
			<label class="radio" for="${escape(ID)}_default">
				<input id="${escape(ID)}_default" type="radio" name="${escape(NAME)}" value="default" # IF C_DEFAULT_CHECKED # checked="checked" # ENDIF #>
				<span> {@thumbnail.default}</span>
			</label>
		</div>
		# ENDIF #
		<div class="form-field-radio">
			<label class="radio" for="${escape(ID)}_custom">
				<input id="${escape(ID)}_custom" type="radio" name="${escape(NAME)}" value="custom" # IF C_CUSTOM_CHECKED # checked="checked" # ENDIF #>
				<span> {@thumbnail.custom}</span>
			</label>
		</div>
	</div>
	<div id="onblurContainerResponse${escape(HTML_ID)}_custom_file"# IF NOT C_CUSTOM_CHECKED # style="display: none;"# ENDIF # class="grouped-inputs # IF C_AUTH_UPLOAD #form-field-upload-file# ENDIF # picture-status-constraint# IF C_REQUIRED # field-required # ENDIF #">
		<input type="text" name="${escape(NAME)}_custom_file" id="${escape(HTML_ID)}_custom_file" value="# IF C_CUSTOM_CHECKED #{FILE_PATH}# ENDIF #" class="grouped-element upload-input# IF C_HAS_CSS_CLASS # ${escape(CSS_CLASS)}# ENDIF #"# IF C_DISABLED # disabled="disabled"# ENDIF #/>
		<span class="text-status-constraint" style="display: none;" id="onblurMessageResponse${escape(HTML_ID)}"></span>
		# IF C_AUTH_UPLOAD #
			<a class="grouped-element" aria-label="${LangLoader::get_message('files_management', 'main')}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=${escape(HTML_ID)}_custom_file&amp;parse=true&amp;no_path=true', '', 'height=500,width=769,resizable=yes,scrollbars=yes');return false;">
				<i class="fa fa-cloud-upload-alt fa-2x" aria-hidden="true"></i>
			</a>
		# ENDIF #
	</div>
</div>

<div id="${escape(HTML_ID)}_preview"# IF C_PREVIEW_HIDDEN # style="display: none;"# ENDIF # class="form-field-thumbnail-preview# IF C_HAS_FIELD_CLASS # {FIELD_CLASS}# ENDIF #">
	<label for="${escape(HTML_ID)}_preview">
		${LangLoader::get_message('form.picture.preview', 'common')}
	</label>

	<div class="form-field-preview">
		<img id="${escape(HTML_ID)}_preview_picture" src="# IF NOT C_PREVIEW_HIDDEN #{FILE_PATH}# ENDIF #" alt="${LangLoader::get_message('form.picture.preview', 'common')}" style="vertical-align:top" />
	</div>
</div>

# INCLUDE ADD_FIELD_JS #

<script>
	jQuery('input[name="' + ${escapejs(NAME)} + '"]:radio').change(function(){
		var option = jQuery('input[name="' + ${escapejs(NAME)} + '"]:checked').val();
		if (option == 'custom') {
			jQuery("#onblurContainerResponse" + ${escapejs(HTML_ID)} + "_custom_file").show();
			jQuery('#${escape(HTML_ID)}_preview_picture').hide();
			jQuery('#${escape(HTML_ID)}_preview').hide();
			var fileName = jQuery("#" + ${escapejs(HTML_ID)} + "_custom_file").val();
			if (fileName) {
				jQuery('#${escape(HTML_ID)}_preview_picture').attr("src", fileName);
				jQuery('#${escape(HTML_ID)}_preview_picture').show();
				jQuery('#${escape(HTML_ID)}_preview').show();
			}
		} else if (option == 'default') {
			jQuery("#onblurContainerResponse" + ${escapejs(HTML_ID)} + "_custom_file").hide();
			jQuery('#${escape(HTML_ID)}_preview_picture').attr("src", ${escapejs(DEFAULT_URL)});
			jQuery('#${escape(HTML_ID)}_preview_picture').show();
			jQuery('#${escape(HTML_ID)}_preview').show();
		} else {
			jQuery("#onblurContainerResponse" + ${escapejs(HTML_ID)} + "_custom_file").hide();
			jQuery('#${escape(HTML_ID)}_preview_picture').hide();
			jQuery('#${escape(HTML_ID)}_preview').hide();
		}
	});
	jQuery("#" + ${escapejs(NAME)} + "_custom_file").blur(function(){
		var fileName = jQuery("#" + ${escapejs(HTML_ID)} + "_custom_file").val();
		var extension = fileName.substring(fileName.lastIndexOf('.')+1);

		if ((/^(png|gif|jpg|jpeg|tiff|ico|svg)$/i).test(extension)) {
			jQuery('#${escape(HTML_ID)}_preview').show();
			jQuery.ajax({
				url: PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/image/preview/',
				type: "post",
				dataType: "json",
				data: {token: ${escapejs(TOKEN)}, image: fileName},
				beforeSend: function(){
					jQuery('#${escape(HTML_ID)}_preview_picture').hide();
					jQuery('#${escape(HTML_ID)}_preview_picture').after('<i id="${escape(HTML_ID)}_preview_loading" class="fa fa-spinner fa-spin"></i>');
				},
				success: function(returnData){
					jQuery('#${escape(HTML_ID)}_preview_loading').remove();

					if (returnData.url) {
						jQuery('#${escape(HTML_ID)}_preview_picture').attr("src", returnData.url);
						jQuery('#${escape(HTML_ID)}_preview_picture').show();
					} else {
						jQuery('#${escape(HTML_ID)}_preview').hide();
					}
				},
				error: function(e){
					jQuery('#${escape(HTML_ID)}_preview_loading').remove();
					jQuery('#${escape(HTML_ID)}_preview').hide();
				}
			});
		} else {
			jQuery('#${escape(HTML_ID)}_preview').hide();
		}
	});
</script>
