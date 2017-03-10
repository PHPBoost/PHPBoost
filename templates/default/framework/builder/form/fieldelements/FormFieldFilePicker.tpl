<input name="max_file_size" value="{MAX_FILE_SIZE}" type="hidden" />
<input type="file" name="${escape(NAME)}" id="${escape(HTML_ID)}" # IF C_DISABLED # disabled="disabled" # ENDIF # />
<script>
<!--
jQuery("#${escape(HTML_ID)}").parents("form:first")[0].enctype = "multipart/form-data";
-->
</script>
