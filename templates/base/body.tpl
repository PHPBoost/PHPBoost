# INCLUDE MAINTAIN #
<a href="#global" aria-label="${LangLoader::get_message('go.to.content', 'main')}"></a>
<header id="header">
	<div id="top-header">
		<div id="site-infos" role="banner">
			<div id="site-logo" # IF C_HEADER_LOGO #style="background-image: url({HEADER_LOGO});"# ENDIF #></div>
			<div id="site-name-container">
				<a id="site-name" href="{PATH_TO_ROOT}/">{SITE_NAME}</a>
				<span id="site-slogan">{SITE_SLOGAN}</span>
			</div>
		</div>
		<div id="top-header-content">
			# IF C_MENUS_HEADER_CONTENT #
				# START menus_header #
					{menus_header.MENU}
				# END menus_header #
			# ENDIF #
		</div>

		# IF C_VISIT_COUNTER #
			<div id="visit-counter" class="hidden-small-screens">
				<div class="visit-counter-total">
					<span class="text-strong">{L_VISIT} : </span>
					{VISIT_COUNTER_TOTAL}
				</div>
				<div class="visit-counter-today">
					<span class="text-strong">{L_TODAY} : </span>
					{VISIT_COUNTER_DAY}
				</div>
			</div>
		# ENDIF #

	</div>
	<div id="sub-header">
		<div id="sub-header-content">
			# IF C_MENUS_SUB_HEADER_CONTENT #
				# START menus_sub_header #
					{menus_sub_header.MENU}
				# END menus_sub_header #
			# ENDIF #
		</div>
		<div class="spacer"></div>
	</div>
	<div class="spacer"></div>
</header>

<div id="global" role="main">
	# IF C_MENUS_LEFT_CONTENT #
		<aside id="menu-left"# IF C_MENUS_RIGHT_CONTENT # class="narrow-menu-left"# ENDIF #>
			# START menus_left #
				{menus_left.MENU}
			# END menus_left #
		</aside>
	# ENDIF #

	<div id="main" class="# IF C_MENUS_LEFT_CONTENT #main-with-left# ENDIF ## IF C_MENUS_RIGHT_CONTENT # main-with-right# ENDIF #" role="main">
		# IF C_MENUS_TOPCENTRAL_CONTENT #
			<div id="top-content">
				# START menus_top_central #
					{menus_top_central.MENU}
				# END menus_top_central #
			</div>
			<div class="spacer"></div>
		# ENDIF #

		<div id="main-content" itemprop="mainContentOfPage">
			# INCLUDE ACTIONS_MENU #
			<nav id="breadcrumb" itemprop="breadcrumb">
				<ol>
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
						<a href="{START_PAGE}" itemprop="url">
							<span itemprop="title">{L_INDEX}</span>
						</a>
					</li>
					# START link_bread_crumb #
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" # IF link_bread_crumb.C_CURRENT # class="current" # ENDIF #>
						<a href="{link_bread_crumb.URL}" itemprop="url">
							<span itemprop="title">{link_bread_crumb.TITLE}</span>
						</a>
					</li>
					# END link_bread_crumb #
				</ol>
			</nav>
			# INCLUDE KERNEL_MESSAGE #
			{CONTENT}
		</div>

		# IF C_MENUS_BOTTOM_CENTRAL_CONTENT #
			<div id="bottom-content">
				# START menus_bottom_central #
					{menus_bottom_central.MENU}
				# END menus_bottom_central #
			</div>
		# ENDIF #
	</div>

	# IF C_MENUS_RIGHT_CONTENT #
		<aside id="menu-right"# IF C_MENUS_LEFT_CONTENT # class="narrow-menu-right"# ENDIF #>
			# START menus_right #
				{menus_right.MENU}
			# END menus_right #
		</aside>
	# ENDIF #

	<div class="spacer"></div>
</div>

<footer id="footer">

	# IF C_MENUS_TOP_FOOTER_CONTENT #
	<div id="top-footer">
		# START menus_top_footer #
		{menus_top_footer.MENU}
		# END menus_top_footer #
		<div class="spacer"></div>
	</div>
	# ENDIF #

	# IF C_MENUS_FOOTER_CONTENT #
	<div class="footer-content">
		# START menus_footer #
		{menus_footer.MENU}
		# END menus_footer #
	</div>
	# ENDIF #

	<div role="contentinfo" class="footer-infos">
		<span class="footer-infos-powered-by">{L_POWERED_BY} <a href="https://www.phpboost.com" aria-label="{L_PHPBOOST_LINK}">PHPBoost</a> {L_PHPBOOST_RIGHT}</span>
		# IF C_DISPLAY_BENCH #
		<span class="footer-infos-separator"> | </span>
		<span class="footer-infos-benchmark">{L_ACHIEVED} {BENCH}{L_UNIT_SECOND} - {REQ} {L_REQ} - {MEMORY_USED}</span>
		# ENDIF #
		# IF C_DISPLAY_AUTHOR_THEME #
		<span class="footer-infos-separator"> | </span>
		<span class="footer-infos-template-author">{L_THEME} {L_THEME_NAME} {L_BY} <a href="{U_THEME_AUTHOR_LINK}">{L_THEME_AUTHOR}</a></span>
		# ENDIF #
	</div>
</footer>

<span id="scroll-to-top" class="scroll-to"><i class="fa fa-chevron-up" aria-hidden="true"></i><span class="sr-only">${LangLoader::get_message('scroll-to.top', 'user-common')}</span></span>
