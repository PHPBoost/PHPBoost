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
				# END fieldelements #
			</dd>
		</dl>