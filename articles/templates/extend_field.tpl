<dl>
	<dt>
		<label>{L_MODELS_DESCRIPTION} : </label>
	</dt>
	<dd>
		<span id="model_desc">{MODEL_DESCRIPTION}</span>
	</dd>
</dl>
# IF C_EXTEND_FIELD # # START extend_field #
<dl>
	<dt>
		<label for="extend_cat">{extend_field.NAME}</label>
	</dt>
	<dd>
		<label><input type="text" size="65" id="field_{extend_field.NAME}"
			name="field_{extend_field.NAME}" value="{extend_field.CONTENTS}"
			class="text" /> </label>
	</dd>
</dl>
# END extend_field # # ENDIF #
