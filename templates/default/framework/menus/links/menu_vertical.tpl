# IF C_MENU # <!-- Menu -->
    # IF C_FIRST_MENU # <!-- Title -->
        <div class="module_mini_container">
			<div class="module_mini_top">
				<h3 class="menu_vertical_{DEPTH} menu_vertical">
	                <a href="{RELATIVE_URL}" title="{TITLE}"># IF C_IMG #<img src="{RELATIVE_IMG}" class="valign_middle" alt="" /> # ENDIF #{TITLE}</a>
	            </h3>
			</div>
			<div class="module_mini_contents">
				# IF C_HAS_CHILD #<ul class="menu_vertical_{DEPTH} menu_vertical"># START elements #{elements.DISPLAY}# END elements #</ul># ENDIF #
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
    # ENDIF #
    # IF C_NEXT_MENU # <!-- Children -->
        <li>
            <h3 class="menu_vertical_{DEPTH} menu_vertical">
                <a href="{RELATIVE_URL}" title="{TITLE}"># IF C_IMG #<img src="{RELATIVE_IMG}" class="valign_middle" alt="" /> # ENDIF #{TITLE}</a>
            </h3>
            # IF C_HAS_CHILD #<ul class="menu_vertical_{DEPTH} menu_vertical"># START elements #{elements.DISPLAY}# END elements #</ul># ENDIF #
        </li>
    # ENDIF #
# ELSE # <!-- Simple Menu Link -->
    <li><a href="{RELATIVE_URL}" title="{TITLE}"# IF C_IMG # style="background-image:url({RELATIVE_IMG});"# ENDIF #>{TITLE}</a></li>
# ENDIF #
