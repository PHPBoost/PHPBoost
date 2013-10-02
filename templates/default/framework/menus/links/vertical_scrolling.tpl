# IF C_MENU # <!--  Menu -->
	# IF C_FIRST_MENU # <!-- First one -->
		<nav class="dynamic_menu">
			<ul>
                # IF C_HAS_CHILD #
			    <li>
                # ELSE #
                <li>
                # ENDIF #
					<h5 class="links">
					# IF C_URL #<a href="{ABSOLUTE_URL}" title="{TITLE}"># ENDIF #
						# IF C_IMG #<img src="{ABSOLUTE_IMG}" class="valign_middle" alt="" /> # ENDIF #
						{TITLE}
					# IF C_URL #</a> # ENDIF #
					</h5>
					# IF C_HAS_CHILD #<ul># START elements #{elements.DISPLAY}# END elements #</ul># ENDIF #
				</li>
	        </ul>
	    </nav>
	    # IF C_VERTICAL #
	    	<div class="spacer"></div>
	    # ENDIF #
	# ENDIF #
	# IF C_NEXT_MENU # <!-- Children -->
		<li class="extend">
			# IF C_URL #<a href="{ABSOLUTE_URL}"># IF C_IMG #<img src="{ABSOLUTE_IMG}"/># ENDIF # # ELSE #<span># IF C_IMG #<img src="{ABSOLUTE_IMG}"/># ENDIF # # ENDIF #
		    {TITLE}
		    # IF C_URL #</a># ELSE #</span># ENDIF #
			# IF C_HAS_CHILD #<ul># START elements #{elements.DISPLAY}# END elements #</ul># ENDIF #
		</li>
    # ENDIF #
# ELSE # <!-- Simple Menu Link -->
    <li><a href="{ABSOLUTE_URL}" title="{TITLE}"># IF C_IMG #<img src="{ABSOLUTE_IMG}"/># ENDIF # {TITLE}</a></li>
# ENDIF #
