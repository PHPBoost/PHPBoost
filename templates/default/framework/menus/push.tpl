# IF C_MENU #

	# IF C_FIRST_MENU #

		# IF C_MENU_CONTAINER #
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
			<a class="toggle-{ID}">
	            <i class="fa fa-bars"></i>
	            {TITLE}
          	</a>
			<nav role="navigation" id="pushmenu-{ID}" class="pushnav# IF C_MENU_HORIZONTAL # # ENDIF ## IF C_MENU_VERTICAL # # ENDIF ## IF C_MENU_STATIC # # ENDIF ## IF C_MENU_LEFT # # ENDIF ## IF C_MENU_RIGHT # # ENDIF ## IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF ## IF C_MENU_WITH_SUBMENU # pushmenu-with-submenu# ENDIF #">
				# IF NOT C_MENU_CONTAINER #
					# IF RELATIVE_URL #
						<a href="{REL_URL}" class="">
							# IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF #
						</a>
					# ELSE #
						# IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" class="" /># ENDIF #
					# ENDIF #
				# ENDIF #
				<ul class=""># START elements #{elements.DISPLAY}# END elements #</ul>
			</nav>


		# IF C_MENU_CONTAINER # <!-- Close mini-module-container -->
				</div>
			</div>
		</div>
		# ENDIF #

<script>
    $('#pushmenu-{ID}').pushmenu({
		maxWidth: false,
		customToggle: jQuery('.toggle-{ID}'), // null
		navTitle: '{TITLE}', // null
		levelTitles: true,
		pushContent: '#push-container',
		insertClose: true,
		closeLevels: false,
		position: "left", // left, right, top, bottom
		levelOpen: "overlap", // overlap, expand, false
		levelSpacing: 40,
		navClass: '',
		disableBody: !0,
		closeOnClick: !0,
		insertBack: !0,
		labelClose: ${escapejs(LangLoader::get_message('close', 'main'))},
		labelBack: ${escapejs(LangLoader::get_message('back', 'main'))}
    });
</script>

	# ENDIF #

	# IF C_NEXT_MENU # <!-- Sub Element for Menu -->
		<li # IF C_HAS_CHILD #class="has-sub" # ENDIF #>
			# IF C_URL #
				<a href="{REL_URL}"># IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /> # ENDIF #<span>{TITLE}</span></a>
			# ELSE #
				<span># IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF#<span>{TITLE}</span></span>
			# ENDIF #
			# IF C_HAS_CHILD # <!-- Add Sub-Menu Element -->
				<ul class="level-{DEPTH}"># START elements #{elements.DISPLAY}# END elements #</ul>
			# ENDIF #
		</li>
	# ENDIF #

# ELSE #
	<li>
		# IF C_URL #
			<a href="{REL_URL}"># IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /> # ENDIF #<span>{TITLE}</span></a>
		# ELSE #
			<span># IF C_IMG #<img src="{REL_IMG}" alt="" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF#<span>{TITLE}</span></span>
		# ENDIF #
	</li>
# ENDIF #
