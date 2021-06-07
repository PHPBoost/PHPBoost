<input
    type="color"
    name="${escape(NAME)}" id="${escape(HTML_ID)}"
    value="${escape(VALUE)}"
    pattern="#[A-Fa-f0-9]{6}"
    placeholder="#000000"
    # IF C_DISABLED # disabled="disabled" # ENDIF #
    # IF C_HIDDEN # style="display: none;" # ENDIF # />
