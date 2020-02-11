# IF C_MENU # <!-- Menu -->

	# IF C_FIRST_MENU # <!-- Menu container NAV -->

		# IF C_MENU_CONTAINER # <!-- Open mini-module-container -->
			<div id="links-menu-{ID}" class="cell-mini cell-mini-vertical cell-tile cssmenu-content# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
				<div class="cell">
					<div class="cell-header menu-vertical-{DEPTH} hidden-small-screens">
						# IF RELATIVE_URL #
							<h6 class="cell-name"><a href="{REL_URL}">{TITLE}</a></h6>
							# IF C_IMG #<a href="{REL_URL}"><img src="{REL_IMG}" alt="" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /></a># ENDIF #
						# ELSE #
							<h6 class="cell-name">{TITLE}</h6>
							# IF C_IMG #<img src="{REL_IMG}" alt="" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF #
						# ENDIF #
					</div>
					<div class="cell-body">
		# ENDIF #

		<nav id="cssmenu-{ID}" class="cssmenu# IF C_MENU_HORIZONTAL # cssmenu-horizontal# ENDIF ## IF C_MENU_VERTICAL # cssmenu-vertical# ENDIF ## IF C_MENU_STATIC # cssmenu-static# ENDIF ## IF C_MENU_LEFT # cssmenu-left# ENDIF ## IF C_MENU_RIGHT # cssmenu-right# ENDIF ## IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF ## IF C_MENU_WITH_SUBMENU # cssmenu-with-submenu# ENDIF #">
			# IF NOT C_MENU_CONTAINER #
				# IF RELATIVE_URL #
				<a href="{REL_URL}" class="cssmenu-img cssmenu-img-level-{DEPTH}">
					# IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF #
				</a>
				# ELSE #
					# IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" class="cssmenu-img cssmenu-img-level-{DEPTH}" /># ENDIF #
				# ENDIF #
			# ENDIF #
			<ul class="level-{DEPTH}# IF NOT C_MENU_CONTAINER ## IF C_IMG # menu-with-img# ENDIF ## ENDIF #"># START elements #{elements.DISPLAY}# END elements #</ul>
		</nav>

		# IF C_MENU_CONTAINER # <!-- Close mini-module-container -->
				</div>
			</div>
		</div>
		# ENDIF #
		<script>jQuery("#cssmenu-${escape(ID)}").menumaker({ title: "{TITLE}", format: "multitoggle", breakpoint: 768# IF C_MENU_STATIC #, static: true# ENDIF # }); </script>
	# ENDIF #

	# IF C_NEXT_MENU # <!-- Sub Element for Menu -->
	<li # IF C_HAS_CHILD #class="has-sub" # ENDIF #>
		# IF C_URL #
			<a href="{REL_URL}" class="cssmenu-title"># IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /> # ENDIF #<span>{TITLE}</span></a>
		# ELSE #
			<span class="cssmenu-title"># IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF#<span>{TITLE}</span></span>
		# ENDIF #
		# IF C_HAS_CHILD # <!-- Add Sub-Menu Element -->
			<ul class="level-{DEPTH}"># START elements #{elements.DISPLAY}# END elements #</ul>
		# ENDIF #
	</li>
	# ENDIF #

# ELSE # <!-- Menu Element -->
<li>
	# IF C_URL #
		<a href="{REL_URL}" class="cssmenu-title"># IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /> # ENDIF #<span>{TITLE}</span></a>
	# ELSE #
		<span class="cssmenu-title"># IF C_IMG #<img src="{REL_IMG}" alt="" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF#<span>{TITLE}</span></span>
	# ENDIF #
</li>
# ENDIF #
