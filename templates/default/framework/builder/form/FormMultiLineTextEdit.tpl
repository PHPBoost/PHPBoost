		<label for="{ID}">
			# IF C_REQUIRED # * # ENDIF #
			{LABEL}
		</label>
		<br />
		<span class="text_small">{DESCRIPTION}</span>
		# IF C_EDITOR_ENABLED #
			{EDITOR}
		# ENDIF #
		<textarea id="{ID}" name="{ID}" rows="{ROWS}" cols="{COLS}" class="{CLASS}" onblur="{ONBLUR}">
			{VALUE}
		</textarea>
		# IF C_EDITOR_ENABLED #
			Bouton preview
		# ENDIF #
		<br />
		<span id="onblurContainerResponse{ID}"></span>
		<span style="font-weight:bold;display:none" id="onblurMesssageResponse{ID}"></span>