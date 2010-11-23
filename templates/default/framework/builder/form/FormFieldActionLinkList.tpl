<div id="${escape(ID_field)}" style="text-align:center; # IF C_DISABLED # display:none; # ENDIF #">
    <ul style="margin:5px;list-style-type:none;">
# START action #
        <li style="margin:2px 10px;display:inline-table;">
            <a href="{action.U_LINK}">
                <img src="{action.U_IMG}" alt="{action.E_TITLE}" />
            </a><br />
            <a href="{action.U_LINK}">{action.E_TITLE}</a>
        </li>
# END action #
    </ul>
</div>

# INCLUDE ADD_FIELD_JS #