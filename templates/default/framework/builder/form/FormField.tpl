		<dl id="{E_ID}_field" # IF C_DISABLED # style="display:none;" # ENDIF #>
			<dt>
				<label for="{E_ID}">
					# IF C_REQUIRED # * # ENDIF #
					{LABEL}
				</label>
				# IF C_DESCRIPTION #
				<br />
				<span class="text_small">{DESCRIPTION}</span>
				# ENDIF #
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
		# INCLUDE ADD_FIELD_JS #
