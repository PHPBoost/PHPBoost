<div id="${escape(ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element">
	<label for="${escape(ID)}">
		# IF C_REQUIRED # * # ENDIF #
		{LABEL}
		# IF DESCRIPTION #<span class="field-description">{DESCRIPTION}</span> # ENDIF #
	</label>
	<div class="form-field">
		{CALENDAR}
		# IF C_HOUR #
		{L_AT}
		<input type="text" size="2" id="${escape(ID)}_hours" name="${escape(ID)}_hours" value="{HOURS}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #/> {L_H}
		<input type="text" size="2" id="${escape(ID)}_minutes" name="${escape(ID)}_minutes" value="{MINUTES}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #/>
		# ENDIF #
		<i class="fa picture-status-constraint" id="onblurContainerResponse${escape(ID)}"></i>
		<div class="text-status-constraint" style="display:none" id="onblurMessageResponse${escape(ID)}"></div>
	</div>
</div>

# INCLUDE ADD_FIELD_JS #