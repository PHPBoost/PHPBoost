# INCLUDE ADD_FIELDSET_JS #
<div class="vertical-fieldset# IF CSS_CLASS # {CSS_CLASS}# ENDIF #" id="${escape(HTML_ID)}" # IF C_DISABLED # style="display: none;" # ENDIF #>
    # IF C_DESCRIPTION #<p>${escape(DESCRIPTION)}</p># ENDIF #
    # START elements #
        # INCLUDE elements.ELEMENT #
    # END elements #
</div>
