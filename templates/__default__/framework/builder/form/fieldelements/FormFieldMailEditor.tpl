<input
    type="{TYPE}"
    size="{SIZE}"
    maxlength="{MAX_LENGTH}"
    name="${escape(NAME)}"
    id="${escape(HTML_ID)}"
    value="{VALUE}"
    # IF C_PLACEHOLDER # placeholder="{PLACEHOLDER}"# ENDIF #
    class="# IF C_READONLY #low-opacity # ENDIF #${escape(CLASS)}"
    # IF C_PATTERN # pattern="{PATTERN}"# ENDIF #
    # IF C_DISABLED # disabled="disabled"# ENDIF #
    # IF C_READONLY # readonly="readonly"# ENDIF #
    # IF C_MULTIPLE # multiple="multiple"# ENDIF # />
