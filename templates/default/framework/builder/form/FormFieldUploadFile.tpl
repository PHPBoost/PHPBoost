<div id="${escape(HTML_ID)}_field"# IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element # IF C_HAS_FIELD_CLASS #{FIELD_CLASS}# ENDIF #">
	# IF C_HAS_LABEL #
		<label for="${escape(HTML_ID)}">
			# IF C_REQUIRED # * # ENDIF #
			{LABEL}
			# IF C_DESCRIPTION #
			<span class="field-description">{DESCRIPTION}</span>
			# ENDIF #
		</label>
	# ENDIF #
	
	<div class="form-field">
		<input type="text" name="${escape(NAME)}" id="${escape(HTML_ID)}" value="{VALUE}" class="field-xlarge ${escape(CLASS)}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY #readonly="readonly"# ENDIF #/>
		# IF C_AUTH_UPLOAD #
			<a title="${LangLoader::get_message('bb_upload', 'editor-common')}" href="#" class="fa fa-cloud-upload fa-2x" onclick="$(${escapejs(HTML_ID)}).value='';window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=${escape(NAME)}&amp;parse=true&amp;no_path=true', '', 'height=500,width=720,resizable=yes,scrollbars=yes');return false;"></a>
		# ENDIF #
		&nbsp;
		<i class="fa picture-status-constraint" id="onblurContainerResponse${escape(HTML_ID)}"></i>
		<div class="text-status-constraint" style="display:none" id="onblurMessageResponse${escape(HTML_ID)}"></div>
	</div>
</div>
<div id="${escape(HTML_ID)}_preview"# IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element # IF C_HAS_FIELD_CLASS #{FIELD_CLASS}# ENDIF #">
	<label for="${escape(HTML_ID)}_preview">
		${LangLoader::get_message('form.picture.preview', 'common')}
	</label>
	
	<div class="form-field">
		<img id="${escape(HTML_ID)}_preview_picture" src="{FILE_PATH}" alt="" style="vertical-align:top" />
	</div>
</div>

# INCLUDE ADD_FIELD_JS #

<script>
<!--
Event.observe(${escapejs(NAME)}, 'blur', function() {
	new Ajax.Request(PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/image/preview/',{method:'post',parameters:{image:HTMLForms.getField(${escapejs(ID)}).getValue()},onSuccess:function(response){
		$('${escape(HTML_ID)}_preview_picture').style.display = "none";
		$('${escape(HTML_ID)}_preview_loading').remove();
	if (response.responseJSON.url) {
		$('${escape(HTML_ID)}_preview_picture').src = response.responseJSON.url;
		$('${escape(HTML_ID)}_preview_picture').style.display = "inline";
	} else {
		$('${escape(HTML_ID)}_preview_picture').style.display = "none";
	}},onFailure:function() {alert('ajax failure');},onCreate:function(response){ $('${escape(HTML_ID)}_preview_picture').insert({after: '<i id="${escape(HTML_ID)}_preview_loading" class="fa fa-spinner fa-spin"></i>'}); },});
});
-->
</script>