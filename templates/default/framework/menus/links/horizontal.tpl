# IF C_MENU # <!-- Menu -->
    # IF C_FIRST_MENU # <!-- Title -->
        <div class="menu_horizontal">
            <ul class="menu_horizontal">
                <li>
                    # IF RELATIVE_URL #
		                <a href="{RELATIVE_URL}" title="{TITLE}"># IF C_IMG #<img src="{RELATIVE_IMG}" class="valign_middle" alt="" /> # ENDIF #{TITLE}</a>
		            # ELSE #
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
                <a href="{RELATIVE_URL}" title="{TITLE}"># IF C_IMG #<img src="{RELATIVE_IMG}" class="valign_middle" alt="" /> # ENDIF #{TITLE}</a>
            # ELSE #
                <span>{TITLE}</span>
            # ENDIF #
        </li>
        # START elements #{elements.DISPLAY}# END elements #
    # ENDIF #
# ELSE # <!-- Simple Menu Link -->
    <li><a href="{RELATIVE_URL}" title="{TITLE}"># IF C_IMG #<img src="{RELATIVE_IMG}" class="valign_middle" alt="" /> # ENDIF #{TITLE}</a></li>
# ENDIF #
