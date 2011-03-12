# IF C_FIRST_MENU #
<div class="dynamic_menu">
	<ul>
		<li onmouseover="show_menu({ID_MENU}, 0);" onmouseout="hide_menu(0);">
			<h5 class="links"><a href="{PATH_TO_ROOT}/{URL_MENU}" title="{TITLE_MENU}"><img src="{PATH_TO_ROOT}/{IMAGE_MENU}" class="valign_middle" alt="" /> {TITLE_MENU}</a></h5>
			{ELEMENT}
		</li>
# ENDIF #

# IF C_NEXT_MENU #
			<ul id="smenu2">
				<li class="extend" onmouseover="show_menu({ID_MENU}{ID_SUB_MENU}, {DEPTH});" onmouseout="hide_menu(1);">
					<a href="{PATH_TO_ROOT}/{URL_MENU}" style="background-image:url({PATH_TO_ROOT}/{IMAGE_MENU});">{TITLE_MENU}</a>
					{ELEMENT}
				</li>
			</ul>
# ENDIF #

# IF C_LINK #
				<li><a href="{PATH_TO_ROOT}/{URL}" title="Dossiers" style="background-image:url({PATH_TO_ROOT}/{IMAGE});">{TITLE}</a></li>
# ENDIF #

# IF C_FIRST_MENU #
	</ul>
</div>
# ENDIF #
