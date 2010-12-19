# IF C_MENU # <!-- Menu -->
    # IF C_FIRST_MENU # <!-- Title -->
        <div class="menu_horizontal">
            <ul class="menu_horizontal">
                <li>
                    # IF RELATIVE_URL #
		                # IF C_IMG #<a href="{ABSOLUTE_URL}" title="{TITLE}"><img src="{ABSOLUTE_IMG}" class="valign_middle" alt="" /></a> # ENDIF #		                <a href="{ABSOLUTE_URL}" title="{TITLE}">{TITLE}</a>		            # ELSE #
		                <span>{TITLE}</span>
		            # ENDIF #
                </li>
                # START elements #{elements.DISPLAY}# END elements #
            </ul>
        </div>
    # ENDIF #
    # IF C_NEXT_MENU # <!-- Children -->
        <li>
            # IF RELATIVE_URL #
                # IF C_IMG #<a href="{ABSOLUTE_URL}" title="{TITLE}"><img src="{ABSOLUTE_IMG}" class="valign_middle" alt="" /></a> # ENDIF #                <a href="{ABSOLUTE_URL}" title="{TITLE}">{TITLE}</a>            # ELSE #
                <span>{TITLE}</span>
            # ENDIF #
        </li>
        # START elements #{elements.DISPLAY}# END elements #
    # ENDIF #
# ELSE # <!-- Simple Menu Link -->
    <li># IF C_IMG #<img src="{ABSOLUTE_IMG}" class="valign_middle" alt="" /> # ENDIF #    <a href="{ABSOLUTE_URL}" title="{TITLE}">{TITLE}</a></li># ENDIF #
