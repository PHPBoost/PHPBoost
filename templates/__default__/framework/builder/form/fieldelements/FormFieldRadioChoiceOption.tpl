<div class="form-field-radio">
	<label class="radio" for="${escape(ID)}">
		<input id="${escape(ID)}" type="radio" name="${escape(NAME)}" value="${escape(VALUE)}" # IF C_CHECKED # checked="checked" # ENDIF # # IF C_DISABLE # disabled="disabled" # ENDIF #>
		<span> {LABEL}</span>
	</label>
</div>
