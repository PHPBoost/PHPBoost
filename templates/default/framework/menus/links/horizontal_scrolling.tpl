# IF C_MENU #
    # IF C_FIRST_MENU #
        <div class="dynamic_menu">
            <ul class="horizontal_scrolling_menu">
                # START elements #{elements.DISPLAY}# END elements #
            </ul>
        </div>
    # ENDIF #
    # IF C_NEXT_MENU #
        <li class="# IF C_FIRST_LEVEL #first_level dynamic_menu# ELSE #extend# ENDIF #" onmouseover="showMenu('gmenu{ID}', {PARENT_DEPTH});" onmouseout="hideMenu({PARENT_DEPTH});">
            # IF C_IMG #<img src="{ABSOLUTE_IMG}" alt="" /> # ENDIF #
            # IF C_URL #
                <a href="{ABSOLUTE_URL}">{TITLE}</a>
            # ELSE #
                <span>{TITLE}</span>
            # ENDIF #
            <ul id="gmenu{ID_VAR}"># START elements #{elements.DISPLAY}# END elements #</ul>
        </li>
    # ENDIF #
# ELSE #
    <li# IF C_FIRST_LEVEL # class="first_level"# ENDIF #># IF C_IMG #<img src="{ABSOLUTE_IMG}" alt="" /> # ENDIF #<a href="{ABSOLUTE_URL}" title="{TITLE}">{TITLE}</a></li>
# ENDIF #
