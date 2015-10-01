# IF C_MENU # <!-- Menu -->
    # IF C_FIRST_MENU # <!-- Title -->
        <nav id="cssmenu-{ID}" class="cssmenu">
            <ul># START elements #{elements.DISPLAY}# END elements #</ul>
        </nav>
        <script>
            jQuery("#cssmenu-${escape(ID)}").menumaker({
                title: "{TITLE}",
                format: "multitoggle",
                breakpoint: 980
            });
        </script>
    # ENDIF #
    # IF C_NEXT_MENU # <!-- Children -->
        <li>
            # IF RELATIVE_URL #
				<a href="{REL_URL}" title="{TITLE}"># IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" /> # ENDIF #{TITLE}</a>
			# ELSE #
				<span># IF C_IMG #<img src="{REL_IMG}" alt="" /># ENDIF #{TITLE}</span>
			# ENDIF #
            <ul># START elements #{elements.DISPLAY}# END elements #</ul>
        </li>
    # ENDIF #
# ELSE # <!-- Simple Menu Link -->
    <li><a href="{REL_URL}" title="{TITLE}"># IF C_IMG #<img src="{REL_IMG}" alt="" /># ENDIF #{TITLE}</a></li># ENDIF #
