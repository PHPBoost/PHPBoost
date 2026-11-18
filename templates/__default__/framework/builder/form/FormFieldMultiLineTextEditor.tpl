<div
	id="${escape(HTML_ID)}_field"
	class="form-element form-element-textarea# IF C_REQUIRED_AND_HAS_VALUE # constraint-status-right# ENDIF ## IF C_EDITOR_ENABLED # editor-{EDITOR_NAME}# ENDIF ## IF C_HAS_CSS_CLASS # {CSS_CLASS}# ENDIF #"
	# IF C_HIDDEN # style="display: none;"# ENDIF #>
	<label for="${escape(HTML_ID)}">
		{LABEL}
		# IF C_DESCRIPTION #<span class="field-description">{DESCRIPTION}</span># ENDIF #
	</label>

	<div id="onblurContainerResponse${escape(HTML_ID)}" class="form-field form-field-textarea picture-status-constraint# IF C_REQUIRED # field-required # ENDIF ## IF C_EDITOR_ENABLED # bbcode-sidebar# ENDIF #">
		# IF C_EDITOR_ENABLED #
			{EDITOR}
		# ENDIF #
		<textarea
				id="${escape(HTML_ID)}"
				name="${escape(HTML_ID)}"
				rows="{ROWS}"
				cols="{COLS}"
				class="auto-resize # IF C_READONLY #low-opacity # ENDIF #${escape(CLASS)}"
				onblur="{ONBLUR}"
				# IF C_DISABLED # disabled="disabled"# ENDIF #
				# IF C_READONLY #readonly="readonly" # ENDIF #
				# IF C_PLACEHOLDER # placeholder="{PLACEHOLDER}"# ENDIF #>{VALUE}</textarea>
		<span class="text-status-constraint" style="display: none;" id="onblurMessageResponse${escape(HTML_ID)}"></span>
	</div>

	# IF C_EDITOR_ENABLED #
		<div class="align-center">
			<div class="form-element-preview">{PREVIEW_BUTTON}</div>
			# IF C_RESET_BUTTON_ENABLED #
				<div class="form-element-reset">{RESET_BUTTON}</div>
			# ENDIF #
		</div>
	# ENDIF #

</div>

# INCLUDE ADD_FIELD_JS #
