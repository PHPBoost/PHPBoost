<div id="${escape(HTML_ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element form-element-date">
	<label for="${escape(HTML_ID)}">
		{LABEL}
		# IF DESCRIPTION #<span class="field-description">{DESCRIPTION}</span> # ENDIF #
	</label>
	<div id="onblurContainerResponse${escape(HTML_ID)}" class="form-field picture-status-constraint# IF C_REQUIRED # field-required # ENDIF #">
		{CALENDAR}
		<span class="text-status-constraint" style="display:none" id="onblurMessageResponse${escape(HTML_ID)}"></span>
		# IF C_HOUR #
		{L_AT}
		<input type="text" size="2" id="${escape(HTML_ID)}_hours" name="${escape(HTML_ID)}_hours" value="{HOURS}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #/> {L_H}
		<input type="text" size="2" id="${escape(HTML_ID)}_minutes" name="${escape(HTML_ID)}_minutes" value="{MINUTES}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #/>
		# ENDIF #
	</div>
</div>

# INCLUDE ADD_FIELD_JS #
<script>
<!--
jQuery('#${escape(HTML_ID)}_hours, #${escape(HTML_ID)}_minutes').keyup(function(){
	regex = new RegExp('^[0-9]{2}$', 'i');
	if (!regex.test(this.value))
	{
		this.value = '';
	}
}); 
-->
</script>