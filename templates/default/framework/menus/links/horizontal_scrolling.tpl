# IF C_MENU #
    # IF C_FIRST_MENU #
        <nav id="cssmenu-{ID}" class="cssmenu menu-horizontal">
            <ul >
                # START elements #{elements.DISPLAY}# END elements #
            </ul>
        </nav>
        <script type="text/javascript">
            $("#cssmenu-${escape(ID)}").menumaker({
                title: "{TITLE}",
                format: "multitoggle",
                breakpoint: 980
            });
        </script>
    # ENDIF #
    # IF C_NEXT_MENU #
        <li>
            # IF C_URL #
                <a href="{REL_URL}"># IF C_IMG #<img src="{REL_IMG}"/> # ENDIF #{TITLE}</a>
            # ELSE #
                <span># IF C_IMG #<img src="{REL_IMG}"/> # ENDIF #{TITLE}</span>
            # ENDIF #
            <ul># START elements #{elements.DISPLAY}# END elements #</ul>
        </li>
    # ENDIF #
# ELSE #
    <li><a href="{REL_URL}" title="{TITLE}"># IF C_IMG #<img src="{REL_IMG}"/> # ENDIF #{TITLE}</a></li>
# ENDIF #
