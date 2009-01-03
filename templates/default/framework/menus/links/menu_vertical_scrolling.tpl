# IF C_MENU # <!--  Menu -->
	# IF C_FIRST_MENU # <!-- First one -->
		<div class="dynamic_menu">
			<ul>
                # IF C_HAS_CHILD #
			    <li onmouseover="showMenu('gmenu{ID}', {DEPTH});" onmouseout="hideMenu({DEPTH});">
                # ELSE #
                <li>
                # ENDIF #
					<h5 class="links"><a href="{RELATIVE_URL}" title="{TITLE}"># IF C_IMG #<img src="{RELATIVE_IMG}" class="valign_middle" alt="" /> # ENDIF #{TITLE}</a></h5>
					# IF C_HAS_CHILD #<ul id="gmenu{ID_VAR}"># START elements #{elements.DISPLAY}# END elements #</ul># ENDIF #
				</li>
	        </ul>
	    </div>
	# ENDIF #
	# IF C_NEXT_MENU # <!-- Children -->
		<li class="extend" onmouseover="showMenu('gmenu{ID}', {DEPTH});" onmouseout="hideMenu({DEPTH});">
			<a href="{RELATIVE_URL}" style="# IF C_IMG #background-image:url({RELATIVE_IMG});# ENDIF #">{TITLE}</a>
			 # IF C_HAS_CHILD #<ul id="gmenu{ID_VAR}"># START elements #{elements.DISPLAY}# END elements #</ul># ENDIF #
		</li>
    # ENDIF #
# ELSE # <!-- Simple Menu Link -->
    <li><a href="{RELATIVE_URL}" title="{TITLE}" style="# IF C_IMG #background-image:url({RELATIVE_IMG});# ENDIF #">{TITLE}</a></li>
# ENDIF #
