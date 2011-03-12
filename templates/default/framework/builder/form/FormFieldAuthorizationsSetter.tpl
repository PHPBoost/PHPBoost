		<div id="${escape(ID)}">
		# START actions #
		<dl id="${escape(ID)}_field_{actions.BIT}" # IF C_DISABLED # style="display:none;" # ENDIF #>
			<dt>
				<label>
					{actions.LABEL}
				</label>
				# IF C_DESCRIPTION #
				<br />
				<span class="text_small">{actions.DESCRIPTION}</span>
				# ENDIF #
			</dt>
			<dd>
			{actions.AUTH_FORM}
			</dd>
		</dl>
		# END actions #
		</div>