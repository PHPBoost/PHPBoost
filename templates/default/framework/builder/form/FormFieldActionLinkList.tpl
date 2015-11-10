<div id="${escape(HTML_ID)}" class="form-field-action-link" style="# IF C_HIDDEN # display:none; # ENDIF #">
    <ul>
		# START action #
        <li>
            <a href="{action.U_LINK}">
                <img src="{action.U_IMG}" alt="{action.TITLE}" />
            </a><br />
            <a href="{action.U_LINK}">{action.TITLE}</a>
        </li>
		# END action #
    </ul>
</div>

# INCLUDE ADD_FIELD_JS #