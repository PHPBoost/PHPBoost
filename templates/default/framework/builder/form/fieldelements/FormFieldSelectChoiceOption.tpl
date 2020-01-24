<option
    value="${escape(VALUE)}"
    # IF C_SELECTED # selected="selected" # ENDIF #
    # IF C_DISABLE # disabled="disabled" # ENDIF #
    label="{LABEL}"
    # IF C_OPTION_PICTURE #data-option-img="{U_OPTION_PICTURE}"# ENDIF #
    # IF C_OPTION_ICON #data-option-icon="{OPTION_ICON}"# ENDIF #>
    {LABEL}
</option>
