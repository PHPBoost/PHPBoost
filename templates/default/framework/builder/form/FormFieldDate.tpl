		<dl class="overflow_visible">
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
				<input type="text" class="text" size="2" id="{ID}_hours" name="{ID}_hours" value="{HOURS}" /> {L_H}
				<input type="text" class="text" size="2" id="{ID}_minutes" name="{ID}_minutes" value="{MINUTES}" />
				# ENDIF #
				<span id="onblurContainerResponse{ID}"></span>
				<span style="font-weight:bold;display:none" id="onblurMesssageResponse{ID}"></span>
			</dd>
		</dl>