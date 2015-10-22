# IF C_FIRST_MENU # <!-- Menu container NAV -->

	# IF C_MENU_CONTAINER # <!-- Open mini-module-container -->
	<div class="module-mini-container# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
		<div class="module-mini-top">
			<h3 class="menu-vertical-{DEPTH}">
				# IF RELATIVE_URL #
					<a href="{REL_URL}" title="{TITLE}" class="cssmenu-title">
					# IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF #{TITLE}</a>
				# ELSE #
					# IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF #{TITLE}
				# ENDIF #
			</h3>
		</div>
		<div class="module-mini-contents">
	# ENDIF #

	<nav id="cssmenu-{ID}" class="cssmenu# IF C_MENU_HORIZONTAL # cssmenu-horizontal# ENDIF ## IF C_MENU_VERTICAL # cssmenu-vertical# ENDIF ## IF C_MENU_STATIC # cssmenu-static# ENDIF ## IF C_MENU_LEFT # cssmenu-left# ENDIF ## IF C_MENU_RIGHT # cssmenu-right# ENDIF ## IF C_HIDDEN_WITH_SMALL_SCREENS #hidden-small-screens# ENDIF #"">
		<ul class="level-{DEPTH}"># START elements #{elements.DISPLAY}# END elements #</ul>
	</nav>

	# IF C_MENU_CONTAINER # <!-- Close mini-module-container -->
		</div>
		<div class="module-mini-bottom"></div>
	</div>
	# ENDIF #

	# IF C_MENU_STATIC # <!-- Javascript for Responsive Design -->
		<script>jQuery("#cssmenu-${escape(ID)}").menumaker({ title: "{TITLE}", format: "multitoggle", breakpoint: 768, menu_static: true });</script>
	# ELSE #
		<script>jQuery("#cssmenu-${escape(ID)}").menumaker({ title: "{TITLE}", format: "multitoggle", breakpoint: 768 }); </script>
	# ENDIF #

# ENDIF #

# IF C_NEXT_MENU # <!-- Element for Menu -->

	<li # IF C_HAS_CHILD #class="has-sub" # ENDIF #>
		# IF C_URL #
			<a href="{REL_URL}" title="{TITLE}" class="cssmenu-title"># IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" idth="{IMG_WIDTH}" /> # ENDIF #{TITLE}</a>
		# ELSE #
			<span class="cssmenu-title"># IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF #{TITLE}</span>
		# ENDIF #

		# IF C_HAS_CHILD # <!-- Add Sub-Menu Element -->
		<ul class="level-{DEPTH}"># START elements #{elements.DISPLAY}# END elements #</ul>
		# ENDIF #
	</li>

# ENDIF #
