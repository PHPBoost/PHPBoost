# IF C_MENU #
	# IF C_FIRST_MENU #
	    <div class="block">&nbsp;
		<div class="dynamic_menu">
			<ul class="vertical_scrolling_menu"># START elements #{elements.DISPLAY}# END elements #</ul>
	    </div>
	    </div>
	# ENDIF #
	# IF C_NEXT_MENU #
		<li class="# IF C_FIRST_LEVEL ## ELSE #extend# ENDIF #" onmouseover="showMenu('gmenu{ID}', {PARENT_DEPTH});" onmouseout="hideMenu({PARENT_DEPTH});">
            # IF C_IMG #<img src="{RELATIVE_IMG}" alt="" /># ENDIF #<a href="{RELATIVE_URL}">{TITLE}</a>
             # IF C_HAS_CHILD #<ul id="gmenu{ID_VAR}"># START elements #{elements.DISPLAY}# END elements #</ul># ENDIF #
        </li>
    # ENDIF #
# ELSE #
    <li# IF C_FIRST_LEVEL # class="first_level"# ENDIF #># IF C_IMG #<img src="{RELATIVE_IMG}" alt="" /># ENDIF #<a href="{RELATIVE_URL}" title="{TITLE}">{TITLE}</a></li>
# ENDIF #