# START choice #
	<div class="form-field-checkbox">
		<label class="checkbox" for="${escape(choice.HTML_ID)}">
			<input type="checkbox" name="${escape(choice.HTML_ID)}" id="${escape(choice.HTML_ID)}" # IF choice.C_CHECKED # checked="checked"# ENDIF # />
			<span>${escape(choice.NAME)}</span>
		</label>
	</div>
# END choice #
