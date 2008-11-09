# IF C_MENU #
    <!-- Global Menu structure -->
	# IF C_FIRST_MENU #
		<div class="dynamic_menu">
			<ul>
				<li onmouseover="show_menu('l{ID}', 0);" onmouseout="hide_menu(0);">
					<h5 class="links"><a href="{URL}" title="{TITLE}"># IF C_IMAGE #<img src="{IMG}" class="valign_middle" alt="" /> # ENDIF #{TITLE}</a></h5>
					<ul id="smenu{ID}"># START elements #{elements.DISPLAY}# END elements #</ul>
				</li>
	        </ul>
	    </div>
	# ENDIF #
	
	# IF C_NEXT_MENU #
		<ul id="smenu{ID}">
			<li class="extend" onmouseover="show_menu({ID}{ID_SUB}, {DEPTH});" onmouseout="hide_menu(1);">
				<a href="{URL}" style="# IF C_IMG #background-image:url({IMG});# ENDIF #">{TITLE}</a>
				 <ul id="smenu{ID}"># START elements #{elements.DISPLAY}# END elements #</ul>
			</li>
		</ul>
	# ENDIF #
# ELSE #
    <!-- Simple Menu Link -->
    <li><a href="{URL}" title="{TITLE}" style="# IF C_IMG #background-image:url({IMG});# ENDIF #">{TITLE}</a></li>
# ENDIF #
