# INCLUDE ADD_FIELDSET_JS #
<div id="${escape(HTML_ID)}" class="tab-content# IF CSS_CLASS # {CSS_CLASS}# ENDIF #"# IF C_DISABLED # style="display: none;"# ENDIF #>
    <div class="fieldset-inset">
        # IF C_TITLE #<h2>{L_TITLE}</h2># ENDIF #
        # IF C_DESCRIPTION #<p class="fieldset-description">{DESCRIPTION}</p># ENDIF #
        # START elements #
            # INCLUDE elements.ELEMENT #
        # END elements #
    </div>
</div>
