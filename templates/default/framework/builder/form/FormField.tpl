		<dl>
			<dt>
				<label for="{E_ID}">
					# IF C_REQUIRED # * # ENDIF #
					{LABEL}
				</label>
				<br />
				<span class="text_small">{DESCRIPTION}</span>
			</dt>
			<dd>
			# START fieldelements #
				{fieldelements.ELEMENT}
			# END fieldelements #
			# IF C_HAS_CONSTRAINTS #
				&nbsp;
				<span style="display:none" id="onblurContainerResponse{E_ID}"></span>
				<div style="font-weight:bold;display:none" id="onblurMesssageResponse{E_ID}"></div>
			# ENDIF #
			</dd>
		</dl>