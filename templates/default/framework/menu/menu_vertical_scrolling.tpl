# IF C_MENU #
    <!-- Global Menu structure -->
	# IF C_FIRST_MENU #
		<div class="dynamic_menu">
			<ul>
				<li onmouseover="showMenu('{ID}', {DEPTH});" onmouseout="hideMenu({DEPTH});">
					<h5 class="links"><a href="{URL}" title="{TITLE}"># IF C_IMAGE #<img src="{IMG}" class="valign_middle" alt="" /> # ENDIF #{TITLE}</a></h5>
					<ul id="smenu{ID}"># START elements #{elements.DISPLAY}# END elements #</ul>
				</li>
	        </ul>
	    </div>
	# ENDIF #
	
	# IF C_NEXT_MENU #
		<li class="extend" onmouseover="showMenu('{ID}', {DEPTH});" onmouseout="hideMenu({DEPTH});">
			<a href="{URL}" style="# IF C_IMG #background-image:url({IMG});# ENDIF #">{TITLE}</a>
			 <ul id="smenu{ID}"># START elements #{elements.DISPLAY}# END elements #</ul>
		</li>
	# ENDIF #
# ELSE #
    <!-- Simple Menu Link -->
    <li><a href="{URL}" title="{TITLE}" style="# IF C_IMG #background-image:url({IMG});# ENDIF #">{TITLE}</a></li>
# ENDIF #
