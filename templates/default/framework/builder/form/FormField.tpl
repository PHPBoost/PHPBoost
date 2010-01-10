		<dl>
			<dt>
				<label for="{ID}">
					# IF C_REQUIRED # * # ENDIF #
					{LABEL}
				</label>
				<br />
				<span class="text_small">{DESCRIPTION}</span>
			</dt>
			# START fieldelements #
				<dd>
					{fieldelements.ELEMENT}
					&nbsp;
					<span id="onblurContainerResponse{ID}"></span>
					<span style="font-weight:bold;display:none" id="onblurMesssageResponse{ID}"></span>
				</dd>
			# END fieldelements #
		</dl>