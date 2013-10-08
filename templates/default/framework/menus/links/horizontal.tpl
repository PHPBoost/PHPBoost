# IF C_MENU # <!-- Menu -->
    # IF C_FIRST_MENU # <!-- Title -->
        <nav class="menu_horizontal">
            <ul>
                <li>
                    # IF RELATIVE_URL #
		                <a href="{ABSOLUTE_URL}" title="{TITLE}"># IF C_IMG #<img src="{ABSOLUTE_IMG}" alt="" /># ENDIF #<a href="{ABSOLUTE_URL}" title="{TITLE}">{TITLE}</a>
					# ELSE #
		                <span># IF C_IMG #<img src="{ABSOLUTE_IMG}" alt="" /># ENDIF #{TITLE}</span>
		            # ENDIF #
                </li>
                # START elements #{elements.DISPLAY}# END elements #
            </ul>
        </nav>
    # ENDIF #
    # IF C_NEXT_MENU # <!-- Children -->
        <li>
            # IF RELATIVE_URL #
				<a href="{ABSOLUTE_URL}" title="{TITLE}"># IF C_IMG #<img src="{ABSOLUTE_IMG}" alt="{TITLE}" /> # ENDIF #<a href="{ABSOLUTE_URL}" title="{TITLE}">{TITLE}</a>
			# ELSE #
				<span># IF C_IMG #<img src="{ABSOLUTE_IMG}" alt="" /># ENDIF #{TITLE}</span>
			# ENDIF #
        </li>
        # START elements #{elements.DISPLAY}# END elements #
    # ENDIF #
# ELSE # <!-- Simple Menu Link -->
    <li><a href="{ABSOLUTE_URL}" title="{TITLE}"># IF C_IMG #<img src="{ABSOLUTE_IMG}" alt="" /># ENDIF #{TITLE}</a></li># ENDIF #
