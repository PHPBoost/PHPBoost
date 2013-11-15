<div id="${escape(ID)}" # IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element">
# START actions #
	<label>
		{actions.LABEL} # IF actions.DESCRIPTION #<span class="field-description">{actions.DESCRIPTION}</span># ENDIF #
	</label>
	<div class="form-field">
		{actions.AUTH_FORM}
	</div>
# END actions #
</div>