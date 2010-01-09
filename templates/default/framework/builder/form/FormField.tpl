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
					&nbsp;<span id="onblurContainerResponse{ID}"></span><div id="onblurMesssageResponse{ID}"></div>
				# END fieldelements #
			</dd>
		</dl>