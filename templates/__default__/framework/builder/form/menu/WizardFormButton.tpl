<Fieldset id="last-wizard-step" class="wizard-step">
    <button
        type="${TYPE}"
        id="${HTML_NAME}"
        name="${HTML_NAME}"
        class="button ${CSS_CLASS}"
        onclick="${escape(ONCLICK_ACTION)}"
        # IF C_DATA_CONFIRMATION # data-confirmation="${escape(DATA_CONFIRMATION)}"# ENDIF #
        value="true">
        {LABEL}
    </button>
</Fieldset>
