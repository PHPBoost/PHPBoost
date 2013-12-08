# IF C_MENU #
    # IF C_FIRST_MENU #
        <nav class="dynamic-menu">
            <ul class="horizontal_scrolling_menu">
                # START elements #{elements.DISPLAY}# END elements #
            </ul>
        </nav>
    # ENDIF #
    # IF C_NEXT_MENU #
        <li class="# IF C_FIRST_LEVEL #first_level # ELSE #extend# ENDIF #">
            # IF C_URL #
                <a href="{PATH_TO_ROOT}{RELATIVE_URL}"># IF C_IMG #<img src="{PATH_TO_ROOT}{RELATIVE_IMG}"/> # ENDIF #{TITLE}</a>
            # ELSE #
                <span># IF C_IMG #<img src="{PATH_TO_ROOT}{RELATIVE_IMG}"/> # ENDIF #{TITLE}</span>
            # ENDIF #
            <ul># START elements #{elements.DISPLAY}# END elements #</ul>
        </li>
    # ENDIF #
# ELSE #
    <li# IF C_FIRST_LEVEL # class="first_level"# ENDIF #><a href="{PATH_TO_ROOT}{RELATIVE_URL}" title="{TITLE}"># IF C_IMG #<img src="{PATH_TO_ROOT}{RELATIVE_IMG}"/> # ENDIF #{TITLE}</a></li>
# ENDIF #
