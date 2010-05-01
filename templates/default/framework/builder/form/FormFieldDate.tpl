		<dl class="overflow_visible" id="{E_ID}_field" # IF C_HIDDEN # style="display:none;" # ENDIF #>
			<dt>
				<label for="{ID}">
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
				<input type="text" class="text" size="2" id="{E_ID}_hours" name="{E_ID}_hours" value="{HOURS}" /> {L_H}
				<input type="text" class="text" size="2" id="{E_ID}_minutes" name="{E_ID}_minutes" value="{MINUTES}" />
				# ENDIF #
				<span id="onblurContainerResponse{ID}"></span>
				<span style="font-weight:bold;display:none" id="onblurMesssageResponse{ID}"></span>
			</dd>
		</dl>
		
		# INCLUDE ADD_FIELD_JS #