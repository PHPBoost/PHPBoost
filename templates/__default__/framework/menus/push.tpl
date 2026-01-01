# IF C_MENU #

	# IF C_FIRST_MENU #

		# IF C_MENU_CONTAINER #
			<div id="links-menu-{ID}" class="cell-mini cell-mini-vertical cell-tile cell-pushmenu cssmenu-content# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
				<div class="cell">
					<div class="cell-header menu-vertical-{DEPTH}">
		# ENDIF #
			<a class="toggle-{ID} pushmenu-toggle">
				# IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF #
				# IF C_ICON #
					# IF C_FA_ICON #<i class="{ICON}"></i> # ELSE #<span class="big">{ICON}</span># ENDIF #
				# ENDIF #
				<span>{TITLE}</span>
			</a>
			<nav id="pushmenu-{ID}" class="pushnav# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF ## IF C_MENU_WITH_SUBMENU # pushmenu-with-submenu# ENDIF #">
				<ul class=""># START elements #{elements.DISPLAY}# END elements #</ul>
			</nav>
		# IF C_MENU_CONTAINER #
				</div>
			</div>
		</div>
		# ENDIF #

<script>
    jQuery('#pushmenu-{ID}').pushmenu({
		open: 'Menu',
		customToggle: jQuery('.toggle-{ID}'), // null
		navTitle: '{TITLE}', // null
		pushContent: '{PUSHED_CONTENT}',
		position: '{PUSHMENU_OPENING}', // left, right, top, bottom
		# IF C_FALSE_EXPANDING #
			levelOpen: false,
		# ELSE #
			levelOpen: '{PUSHMENU_EXPANDING}', // 'overlap', 'expand', false
		# ENDIF #
		levelTitles: true, // overlap only
		levelSpacing: 40, // px - overlap only
		navClass: 'pushmenu-nav-{ID}',
		disableBody: {DISABLED_BODY},
		closeOnClick: true, // if disableBody is true
		insertClose: true,
		labelClose: ${escapejs(LangLoader::get_message('common.close', 'common-lang'))},
		insertBack: true,
		labelBack: ${escapejs(LangLoader::get_message('common.back', 'common-lang'))},
		ariaLabels: {
			open:    ${escapejs(LangLoader::get_message('menu.push.open', 'menu-lang'))},
			close:   ${escapejs(LangLoader::get_message('menu.push.close', 'menu-lang'))},
			submenu: ${escapejs(LangLoader::get_message('menu.push.submenu', 'menu-lang'))}
		}
    });
</script>

	# ENDIF #

	# IF C_NEXT_MENU #
		<li>
			# IF C_URL #
				<a href="{REL_URL}">
					# IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /> # ENDIF #
					# IF C_ICON #
						# IF C_FA_ICON #<i class="{ICON}"></i> # ELSE #<span class="big">{ICON}</span># ENDIF #
					# ENDIF #
					<span>{TITLE}</span>
				</a>
			# ELSE #
				<span># IF C_IMG #
					<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF #
					# IF C_ICON #
						# IF C_FA_ICON #<i class="{ICON}"></i> # ELSE #<span class="big">{ICON}</span># ENDIF #
					# ENDIF #
					<span>{TITLE}</span>
				</span>
			# ENDIF #
			# IF C_HAS_CHILD #
				<ul># START elements #{elements.DISPLAY}# END elements #</ul>
			# ENDIF #
		</li>
	# ENDIF #

# ELSE #
	<li>
		# IF C_URL #
			<a href="{REL_URL}">
				# IF C_IMG #<img src="{REL_IMG}" alt="{TITLE}" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /> # ENDIF #
				# IF C_ICON #
					# IF C_FA_ICON #<i class="{ICON}"></i> # ELSE #<span class="big">{ICON}</span># ENDIF #
				# ENDIF #
				<span>{TITLE}</span>
			</a>
		# ELSE #
			<span>
				# IF C_IMG #<img src="{REL_IMG}" alt="" height="{IMG_HEIGHT}" width="{IMG_WIDTH}" /># ENDIF #
				# IF C_ICON #
					# IF C_FA_ICON #<i class="{ICON}"></i> # ELSE #<span class="big">{ICON}</span># ENDIF #
				# ENDIF #
				<span>{TITLE}</span>
			</span>
		# ENDIF #
	</li>
# ENDIF #
