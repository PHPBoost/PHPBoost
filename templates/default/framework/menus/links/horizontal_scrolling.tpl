# IF C_MENU # <!--  Menu -->
    # IF C_FIRST_MENU # <!-- First one -->
        <div class="dynamic_menu">
            <ul class="horizontal_scrolling_menu">
                # START elements #{elements.DISPLAY}# END elements #
            </ul>
        </div>
    # ENDIF #
    # IF C_NEXT_MENU # <!-- Children -->
        <li class="# IF C_FIRST_LEVEL #first_level# ELSE #extend# ENDIF #" onmouseover="showMenu('gmenu{ID}', {DEPTH});" onmouseout="hideMenu({DEPTH});">
            # IF C_IMG #<img src="{RELATIVE_IMG}" alt="" /># ENDIF #<a href="{RELATIVE_URL}">{TITLE}</a>
             # IF C_HAS_CHILD #<ul id="gmenu{ID_VAR}"># START elements #{elements.DISPLAY}# END elements #</ul># ENDIF #
        </li>
    # ENDIF #
# ELSE # <!-- Simple Menu Link -->
    <li# IF C_FIRST_LEVEL # class="first_level"# ENDIF #># IF C_IMG #<img src="{RELATIVE_IMG}" alt="" /># ENDIF #<a href="{RELATIVE_URL}" title="{TITLE}">{TITLE}</a></li>
# ENDIF #
