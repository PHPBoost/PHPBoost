# INCLUDE ADD_FIELDSET_JS #
<div id="${escape(HTML_ID)}" class="horizontal-fieldset# IF CSS_CLASS # {CSS_CLASS}# ENDIF #"# IF C_DISABLED # style="display: none;"# ENDIF #>
    # IF C_DESCRIPTION #<span class="horizontal-fieldset-desc">${escape(DESCRIPTION)}</span># ENDIF #
    # START elements #<div class="horizontal-fieldset-element"># INCLUDE elements.ELEMENT #</div># END elements #
</div>
<div class="spacer"></div>
