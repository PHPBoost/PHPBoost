<div id="${escape(HTML_ID)}" style="text-align:center; # IF C_HIDDEN # display:none; # ENDIF #">
    <ul style="margin:5px;list-style-type:none;">
		# START action #
        <li style="margin:2px 10px;display:inline-table;">
            <a style="text-decoration:none;" href="{action.U_LINK}">
                <img src="{action.U_IMG}" alt="{action.TITLE}" />
            </a><br />
            <a href="{action.U_LINK}">{action.TITLE}</a>
        </li>
		# END action #
    </ul>
</div>

# INCLUDE ADD_FIELD_JS #