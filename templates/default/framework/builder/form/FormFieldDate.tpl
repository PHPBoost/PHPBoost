		<dl class="overflow_visible" id="{E_ID}_field" # IF C_HIDDEN # style="display:none;" # ENDIF #>
			<dt>
				<label for="{ID}">
					# IF C_REQUIRED # * # ENDIF #
					{LABEL}
				</label>
				<br />
				<span class="text_small">{DESCRIPTION}</span>
			</dt>
			<dd>
				{CALENDAR}
				# IF C_HOUR #
				{L_AT}
				<input type="text" class="text" size="2" id="{E_ID}_hours" name="{E_ID}_hours" value="{HOURS}" /> {L_H}
				<input type="text" class="text" size="2" id="{E_ID}_minutes" name="{E_ID}_minutes" value="{MINUTES}" />
				# ENDIF #
				<span id="onblurContainerResponse{ID}"></span>
				<span style="font-weight:bold;display:none" id="onblurMesssageResponse{ID}"></span>
			</dd>
		</dl>
		
		<script type="text/javascript">
		<!--
		var field = new FormField("{E_ID}");
		HTMLForms.get("{E_FORM_ID}").addField(field);
		
		field.doValidate = function() {
			var result = "";
			# START constraint #
				result = {constraint.CONSTRAINT};
				if (result != "") {
					return result;
				}
			# END constraint #
			return result;
		}
		
		# IF C_DISABLED #
		field.disable();
		# ENDIF #
		
		{JS_SPECIALIZATION_CODE}
		
		Event.observe("{E_ID}", 'blur', function() {
			HTMLForms.get("{E_FORM_ID}").getField("{E_ID}").enableValidationMessage();
			HTMLForms.get("{E_FORM_ID}").getField("{E_ID}").liveValidate();
			# START related_field #
			HTMLForms.get("{E_FORM_ID}").getField("{related_field.E_ID}").liveValidate();
			# END related_field #
		});
		-->
		</script>