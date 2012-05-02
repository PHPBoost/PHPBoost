<dl class="overflow_visible" id="${escape(ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF # >
	<dt>
		<label for="${escape(ID)}">
			# IF C_REQUIRED # * # ENDIF #
			{LABEL}
		</label>
		<br />
		<span class="text_small">{DESCRIPTION}</span>
	</dt>
	<dd>
		{CALENDAR}
		# IF C_HOUR #
		{L_AT}
		<input type="text" class="text" size="2" id="${escape(ID)}_hours" name="${escape(ID)}_hours" value="{HOURS}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #/> {L_H}
		<input type="text" class="text" size="2" id="${escape(ID)}_minutes" name="${escape(ID)}_minutes" value="{MINUTES}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #/>
		# ENDIF #
		<span id="onblurContainerResponse{ID}"></span>
		<span style="font-weight:bold;display:none" id="onblurMesssageResponse{ID}"></span>
	</dd>
</dl>

# INCLUDE ADD_FIELD_JS #