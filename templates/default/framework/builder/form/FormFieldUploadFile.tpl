<input type="text" name="${escape(NAME)}" id="${escape(ID)}" value="{VALUE}" class="field-xlarge ${escape(CLASS)}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY #readonly="readonly"# ENDIF #/>
<a title="{L_FILE_ADD}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=${escape(NAME)}&amp;parse=true&amp;no_path=true', '', 'height=500,width=720,resizable=yes,scrollbars=yes');return false;">
	<i class="fa fa-cloud-upload fa-2x"></i>
</a>