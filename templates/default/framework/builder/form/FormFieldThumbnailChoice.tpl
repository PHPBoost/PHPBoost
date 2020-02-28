<div id="${escape(HTML_ID)}_field"# IF C_HIDDEN # style="display: none;" # ENDIF # class="form-element form-field-thumbnail-choice# IF C_REQUIRED_AND_HAS_VALUE # constraint-status-right# ENDIF ## IF C_HAS_FIELD_CLASS # {FIELD_CLASS}# ENDIF ## IF C_HAS_CSS_CLASS # {CLASS}# ENDIF #">
	# IF C_HAS_LABEL #
		<label# IF NOT C_HIDE_FOR_ATTRIBUTE # for="${escape(HTML_ID)}"# ELSE # for="onblurContainerResponse${escape(HTML_ID)}"# ENDIF #>
			{LABEL}
			# IF C_DESCRIPTION #
			<span class="field-description">{DESCRIPTION}</span>
			# ENDIF #
		</label>
	# ENDIF #
	<div id="onblurContainerResponse${escape(HTML_ID)}" class="form-field# IF C_HAS_FORM_FIELD_CLASS # {FORM_FIELD_CLASS}# ENDIF # picture-status-constraint# IF C_REQUIRED # field-required# ENDIF #">
		<div class="form-field-radio">
			<label class="radio" for="${escape(ID)}-none">
				<input id="${escape(ID)}_none" type="radio" name="${escape(NAME)}" value="none" # IF C_NONE_CHECKED # checked="checked" # ENDIF #>
				<span> none</span>
			</label>
		</div>
		<div class="form-field-radio">
			<label class="radio" for="${escape(ID)}-default">
				<input id="${escape(ID)}_default" type="radio" name="${escape(NAME)}" value="default" # IF C_DEFAULT_CHECKED # checked="checked" # ENDIF #>
				<span> default</span>
			</label>
		</div>
		<div class="form-field-radio">
			<label class="radio" for="${escape(ID)}-custom">
				<input id="${escape(ID)}_custom" type="radio" name="${escape(NAME)}" value="custom" # IF C_CUSTOM_CHECKED # checked="checked" # ENDIF #>
				<span> custom</span>
			</label>
		</div>
	</div>
	# IF NOT C_NONE_CHECKED #
		<div id="onblurContainerResponse${escape(HTML_ID)}" class="form-field grouped-inputs # IF C_AUTH_UPLOAD #form-field-upload-file# ENDIF # picture-status-constraint# IF C_REQUIRED # field-required # ENDIF #">
			<input type="text" name="${escape(NAME)}_custom_file" id="${escape(ID)}_custom_file" value="# IF C_DEFAULT_CHECKED #{DEFAULT_URL}# ELSE #{VALUE}# ENDIF #" class="grouped-element upload-input# IF C_HAS_CSS_CLASS # ${escape(CSS_CLASS)}# ENDIF #"# IF C_DISABLED # disabled="disabled"# ENDIF ## IF C_DEFAULT_CHECKED # readonly="readonly"# ENDIF #/>
			<span class="text-status-constraint" style="display: none;" id="onblurMessageResponse${escape(HTML_ID)}"></span>
			# IF C_AUTH_UPLOAD #
				# IF C_CUSTOM_CHECKED #
					<a class="grouped-element" aria-label="${LangLoader::get_message('files_management', 'main')}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=${escape(NAME)}_custom_file&amp;parse=true&amp;no_path=true', '', 'height=500,width=769,resizable=yes,scrollbars=yes');return false;">
						<i class="fa fa-cloud-upload-alt fa-2x" aria-hidden="true"></i>
					</a>
				# ENDIF #
			# ENDIF #
		</div>
	# ENDIF #
</div>

<div id="${escape(HTML_ID)}_preview"# IF C_PREVIEW_HIDDEN # style="display: none;"# ENDIF # class="form-element # IF C_HAS_FIELD_CLASS #{FIELD_CLASS}# ENDIF #">
	<label for="${escape(HTML_ID)}_preview">
		${LangLoader::get_message('form.picture.preview', 'common')}
	</label>

	<div class="form-field">
		<img id="${escape(HTML_ID)}_preview_picture" src="# IF NOT C_PREVIEW_HIDDEN #{FILE_PATH}# ENDIF #" alt="${LangLoader::get_message('form.picture.preview', 'common')}" style="vertical-align:top" />
	</div>
</div>

# INCLUDE ADD_FIELD_JS #

# IF NOT C_NONE_CHECKED #
	<script>
		jQuery("#" + ${escapejs(NAME)} + "_custom_file").blur(function(){
			var fileName = HTMLForms.getField(${escapejs(ID)} + "_custom_file").getValue();
			var extension = fileName.substring(fileName.lastIndexOf('.')+1);

			if ((/^(png|gif|jpg|jpeg|tiff|ico|svg)$/i).test(extension)) {
				jQuery('#${escape(HTML_ID)}_preview').show();
				jQuery.ajax({
					url: PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/image/preview/',
					type: "post",
					dataType: "json",
					data: {token: ${escapejs(TOKEN)}, image: HTMLForms.getField(${escapejs(ID)} + "_custom_file").getValue()},
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
# ENDIF #
