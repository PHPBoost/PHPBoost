<div class="form-field-thumbnail-choice">
	<label class="radio" for="${escape(ID)}-none">
		<input id="${escape(ID)}-none" type="radio" name="${escape(NAME)}" value="none" # IF C_NONE_CHECKED # checked="checked" # ENDIF #>
		<span> {@thumbnail.none}</span>
	</label>
	<label class="radio" for="${escape(ID)}-default">
		<input id="${escape(ID)}-default" type="radio" name="${escape(NAME)}" value="default" # IF C_DEFAULT_CHECKED # checked="checked" # ENDIF #>
		<span> {@thumbnail.default}</span>
	</label>
	<label class="radio" for="${escape(ID)}-custom">
		<input id="${escape(ID)}-custom" type="radio" name="${escape(NAME)}" value="custom" # IF C_CUSTOM_CHECKED # checked="checked" # ENDIF #>
		<span> {@thumbnail.custom}</span>
	</label>
</div>