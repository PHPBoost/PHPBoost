<input
	type="{TYPE}"
	# IF C_MIN # min="{MIN}"# ENDIF #
	# IF C_MAX # max="{MAX}"# ENDIF #
	# IF C_STEP # step="{STEP}"# ENDIF #
	name="${escape(NAME)}"
	id="${escape(HTML_ID)}"
	value="{VALUE}"
	class="# IF C_READONLY #low-opacity # ENDIF #${escape(CLASS)}"
	# IF C_VERTICAL # orient="vertical" style="width: 20px; height: 200px; -webkit-appearance: slider-vertical; writing-mode: bt-lr;"# ENDIF #
	# IF C_PATTERN # pattern="{PATTERN}" # ENDIF #
	# IF C_DISABLED # disabled="disabled" # ENDIF #
	# IF C_READONLY # readonly="readonly" # ENDIF # />
