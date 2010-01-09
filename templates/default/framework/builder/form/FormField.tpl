		<dl>
			<dt>
				<label for="{ID}">
					# IF C_REQUIRED # * # ENDIF #
					{LABEL}
				</label>
				<br />
				<span>{DESCRIPTION}</span>
			</dt>
			<dd>
				# START fieldelements #
					{fieldelements.ELEMENT}
					&nbsp;
					<span id="onblurContainerResponse{ID}"></span>
					<span style="font-weight:bold;display:none" id="onblurMesssageResponse{ID}"></span>
				# END fieldelements #
			</dd>
		</dl>