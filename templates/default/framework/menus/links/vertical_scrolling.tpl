# IF C_MENU # <!--  Menu -->
	# IF C_FIRST_MENU # <!-- First one -->
		<div class="dynamic_menu">
			<ul>
                # IF C_HAS_CHILD #
			    <li onmouseover="showMenu('gmenu{ID}', {DEPTH});" onmouseout="hideMenu({DEPTH});">
                # ELSE #
                <li>
                # ENDIF #
					<h5 class="links">
					# IF C_URL #<a href="{RELATIVE_URL}" title="{TITLE}"># ENDIF #
						# IF C_IMG #<img src="{RELATIVE_IMG}" class="valign_middle" alt="" /> # ENDIF #
						{TITLE}
					# IF C_URL #</a> # ENDIF #
					</h5>
					# IF C_HAS_CHILD #<ul id="gmenu{ID_VAR}"># START elements #{elements.DISPLAY}# END elements #</ul># ENDIF #
				</li>
	        </ul>
	    </div>
	    # IF C_VERTICAL #
	    	<div class="spacer"></div>
	    # ENDIF #
	# ENDIF #
	# IF C_NEXT_MENU # <!-- Children -->
		<li class="extend" onmouseover="showMenu('gmenu{ID}', {DEPTH});" onmouseout="hideMenu({DEPTH});">
			# IF C_URL #<a href="{RELATIVE_URL}" style="# IF C_IMG #background-image:url({RELATIVE_IMG});# ENDIF #"># ELSE #<span># ENDIF #
		    {TITLE}
		    # IF C_URL #</a># ELSE #</span># ENDIF #
			# IF C_HAS_CHILD #<ul id="gmenu{ID_VAR}"># START elements #{elements.DISPLAY}# END elements #</ul># ENDIF #
		</li>
    # ENDIF #
# ELSE # <!-- Simple Menu Link -->
    <li><a href="{RELATIVE_URL}" title="{TITLE}" style="# IF C_IMG #background-image:url({RELATIVE_IMG});# ENDIF #">{TITLE}</a></li>
# ENDIF #
