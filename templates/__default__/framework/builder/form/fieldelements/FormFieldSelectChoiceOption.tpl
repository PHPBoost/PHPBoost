<option
    value="${escape(VALUE)}"
    # IF C_SELECTED # selected="selected" # ENDIF #
    # IF C_DISABLE # disabled="disabled" # ENDIF #
    label="{PROTECTED_LABEL}"
    # IF C_OPTION_PICTURE # data-option-img="{U_OPTION_PICTURE}"# ENDIF #
    # IF C_OPTION_ICON # data-option-icon="{OPTION_ICON}"# ENDIF #
    # IF C_OPTION_CLASS # data-option-class="{OPTION_CLASS}"# ENDIF #>
    {LABEL}
</option>
