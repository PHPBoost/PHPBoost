<div id="${escape(ID)}" # IF C_HIDDEN # style="display:none;" # ENDIF #>
# START actions #
<dl id="${escape(ID)}_field_{actions.BIT}">
	<dt>
		<label>
			{actions.LABEL}
		</label>
		# IF actions.DESCRIPTION #
		<br />
		<span class="smaller">{actions.DESCRIPTION}</span>
		# ENDIF #
	</dt>
	<dd>
	{actions.AUTH_FORM}
	</dd>
</dl>
# END actions #
</div>