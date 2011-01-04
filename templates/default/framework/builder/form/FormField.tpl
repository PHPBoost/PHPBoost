		<dl id="${escape(ID)}_field"# IF C_DISABLED # style="display:none;" # ENDIF # # IF C_HAS_FIELD_CLASS # class="{FIELD_CLASS}" # ENDIF #>
			# IF C_HAS_LABEL #
			<dt>
				<label for="${escape(ID)}">
					# IF C_REQUIRED # * # ENDIF #
					{LABEL}
				</label>
				# IF C_DESCRIPTION #
				<br />
				<span class="text_small">{DESCRIPTION}</span>
				# ENDIF #
			</dt>
			<dd>
			# ENDIF #
			
			# START fieldelements #
				{fieldelements.ELEMENT}
			# END fieldelements #
			# IF C_HAS_CONSTRAINTS #
				&nbsp;
				<span style="display:none" id="onblurContainerResponse${escape(ID)}"></span>
				<div style="font-weight:bold;display:none" id="onblurMesssageResponse${escape(ID)}"></div>
			# ENDIF #
			
			# IF C_HAS_LABEL #
			</dd>
			# ENDIF #
		</dl>
		
		# INCLUDE ADD_FIELD_JS #
