<span class="password-container">
    <input
        type="password"
        size="{SIZE}"
        maxlength="{MAX_LENGTH}"
        name="${escape(NAME)}" id="${escape(HTML_ID)}"
        value="${escape(VALUE)}"
        class="${escape(CLASS)}"
        # IF C_DISABLED # disabled="disabled" # ENDIF #
        # IF NOT C_AUTOCOMPLETE # autocomplete="off" # ENDIF # />
    <span class="toggle-password" aria-label="{@user.password.see}"><i class="fa fa-eye" aria-hidden="true"></i></span>
</span>
