# INCLUDE MAINTAIN #
<a href="#global" aria-label="{@common.go.to.content}"></a>
<header id="header">
	<div id="top-header">
		<div id="site-infos" role="banner">
			<div id="site-logo" # IF C_HEADER_LOGO #style="background-image: url({U_HEADER_LOGO});"# ENDIF #></div>
			<div id="site-name-container">
				<a class="offload" id="site-name" href="{PATH_TO_ROOT}/">{SITE_NAME}</a>
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
				<div class="visit-counter-total flex-between">
					<span class="text-strong">{@user.guests} &nbsp;</span>
					<span class="pinned visitor small">{VISIT_COUNTER_TOTAL}</span>
				</div>
				<div class="visit-counter-today flex-between">
					<span class="text-strong">{@date.today}</span>
					<span class="pinned visitor small">{VISIT_COUNTER_DAY}</span>
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

<div id="global" class="content-preloader" role="main">
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
				<ol itemscope itemtype="https://schema.org/BreadcrumbList">
					<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
						<a class="offload" href="{START_PAGE}" itemprop="item">
							<span itemprop="name">{@common.home}</span>
    						<meta itemprop="position" content="1" />
						</a>
					</li>
					# START link_bread_crumb #
						<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" # IF link_bread_crumb.C_CURRENT # class="current" # ENDIF #>
							<a class="offload" href="{link_bread_crumb.URL}" itemprop="item">
								<span itemprop="name">{link_bread_crumb.TITLE}</span>
	    						<meta itemprop="position" content="{link_bread_crumb.POSITION}" />
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
	<div id="footer-content">
		# START menus_footer #
		{menus_footer.MENU}
		# END menus_footer #
	</div>
	# ENDIF #

	<div role="contentinfo" class="footer-infos">
		<span class="footer-infos-powered-by">{@common.powered.by} <i class="fa iboost fa-iboost-logo" aria-hidden="true"></i> <a class="offload" href="https://www.phpboost.com" aria-label="{@common.phpboost.link}">PHPBoost</a></span> | <span aria-label="{@common.phpboost.right}"><i class="fab fa-osi" aria-hidden="true"></i></span>
		# IF C_DISPLAY_BENCH #
			| <span class="footer-infos-benchmark">{@common.achieved} {BENCH}{@date.unit.seconds} - {REQ} {@common.sql.request} - {MEMORY_USED}</span>
		# ENDIF #
		# IF C_DISPLAY_AUTHOR_THEME #
			| <span class="footer-infos-template-author">{@common.theme} {L_THEME_NAME} ${TextHelper::lcfirst(@common.by)} <a class="offload" href="{U_THEME_AUTHOR_LINK}">{L_THEME_AUTHOR}</a></span>
		# ENDIF #
	</div>
</footer>

<span id="scroll-to-top" class="scroll-to" role="button" aria-label="{@common.scroll.to.top}"><i class="fa fa-chevron-up" aria-hidden="true"></i></span>
