# IF C_FIRST_MENU #
<div class="dynamic-menu">
	<ul>
		<li>
			<h5 class="links"><a href="{PATH_TO_ROOT}/{URL_MENU}" title="{TITLE_MENU}"><img src="{PATH_TO_ROOT}/{IMAGE_MENU}" class="valign-middle" alt="" /> {TITLE_MENU}</a></h5>
			{ELEMENT}
		</li>
# ENDIF #

# IF C_NEXT_MENU #
			<ul>
				<li class="extend">
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
