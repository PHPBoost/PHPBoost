<div class="auth-setter" id="${escape(HTML_ID)}"# IF C_HIDDEN # style="display: none;"# ENDIF #>
# START actions #
    <div class="form-element form-element-auth">
    	<label>
    		{actions.LABEL} # IF actions.DESCRIPTION #<span class="field-description">{actions.DESCRIPTION}</span># ENDIF #
    	</label>
    	<div class="form-field">
    		{actions.AUTH_FORM}
        </div>
    </div>
# END actions #
</div>
