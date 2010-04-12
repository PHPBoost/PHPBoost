		# START actions #
		<dl id="{E_ID}_field" # IF C_HIDDEN # style="display:none;" # ENDIF #>
			<dt>
				<label>
					{actions.LABEL}
				</label>
				<br />
				<span class="text_small">{actions.DESCRIPTION}</span>
			</dt>
			<dd>
			{actions.AUTH_FORM}
			</dd>
		</dl>
		# END actions #