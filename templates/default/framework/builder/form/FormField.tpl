		<dl>
			<dt>
				<label for="{ID}">
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
			# IF C_HAS_CONSTRAINT #
				&nbsp;
				<span id="onblurContainerResponse{ID}"></span>
				<div style="font-weight:bold;display:none" id="onblurMesssageResponse{ID}"></div>
			# ENDIF #
			</dd>
		</dl>