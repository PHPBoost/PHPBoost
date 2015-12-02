	# INCLUDE MAINTAIN #
	<header id="header">
		<div id="command-bar">
		# IF C_MENUS_LEFT_CONTENT #
			# START menus_left #
			{menus_left.MENU}
			# END menus_left #
		# ENDIF #
		</div>
		<div id="top-header">
			<div id="site-infos">
				<div id="site-logo" # IF C_HEADER_LOGO #style="background: url('{HEADER_LOGO}') no-repeat;"# ENDIF #></div>
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
		</div></header>
		<div id="sub-header">
			<div id="sub-header-content">
			# IF C_MENUS_SUB_HEADER_CONTENT #
				# START menus_sub_header #
				{menus_sub_header.MENU}
				# END menus_sub_header #
			# ENDIF #
			</div>
		</div>
		<div class="spacer"></div>
	

	<div id="global">
		# IF C_COMPTEUR #
		<div id="compteur">
			<span class="text-strong">{L_VISIT} : </span>{COMPTEUR_TOTAL}
			<br />
			<span class="text-strong">{L_TODAY} : </span>{COMPTEUR_DAY}
		</div>
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
							<a href="{START_PAGE}" title="{L_INDEX}" itemprop="url">
								<span itemprop="title">{L_INDEX}</span>
							</a>
						</li>
						# START link_bread_crumb #
						<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" # IF link_bread_crumb.C_CURRENT # class="current" # ENDIF #>
							# IF link_bread_crumb.C_CURRENT #
							<span itemprop="title">{link_bread_crumb.TITLE}</span>
							# ELSE #
							<a href="{link_bread_crumb.URL}" title="{link_bread_crumb.TITLE}" itemprop="url">
								<span itemprop="title">{link_bread_crumb.TITLE}</span>
							</a>
							# ENDIF #
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
		<aside id="menu-right">
			# START menus_right #
			{menus_right.MENU}
			# END menus_right #
		</aside>
		# ENDIF #

		<div class="spacer"></div>
	</div>

		# IF C_MENUS_TOP_FOOTER_CONTENT #
		<div id="top-footer">
			# START menus_top_footer #
			{menus_top_footer.MENU}
			# END menus_top_footer #
			<div class="spacer"></div>
		</div>
		# ENDIF #


	<footer id="footer">

		# IF C_MENUS_FOOTER_CONTENT #
		<div class="footer-content">
			<div class="block-container">
				<div class="footer-partenaires">
					<div class="footer-partners-title">
						<img src="{PATH_TO_ROOT}/templates/phpboost/theme/images/transparent.gif" class="sprite-img footer-partners-title-img" />
						Les partenaires
					</div>
					<a href="http://www.nuxit.com/" class="footer-partners-nuxit" title="Nuxit">
						<p class="partners-nuxit-top">Hébergement de site web</p>
						<p class="center">
							<img src="{PATH_TO_ROOT}/templates/phpboost/theme/images/transparent.gif" class="sprite-img footer-partners-nuxit-img" />
						</p>
						<p class="partners-nuxit-bottom">Qualité, fiabilité, Support</p>
					</a>
				</div>
			</div>
			# START menus_footer #
			{menus_footer.MENU}
			# END menus_footer #

			<div class="footer-social-container">
				<div class="footer-social footer-social-guestbook">
					<a href="{PATH_TO_ROOT}/guestbook/" title="Livre d'Or"></a>
				</div>
				<div class="footer-social footer-social-gplus">			
					<a href="https://plus.google.com/103112963627704533252" rel="publisher" title="Google +"></a>
				</div>
				<div class="footer-social footer-social-twitter">			
					<a href="http://twitter.com/PHPBoostCMS" title="Twitter"></a>
				</div>
				<div class="footer-social footer-social-facebook">			
					<a href="http://www.facebook.com/pages/PHPBoost-CMS/229132847163144" title="Facebook"></a>
				</div>
				<div class="footer-social footer-social-rss">
					<a href="{PATH_TO_ROOT}/syndication/rss/news" title="Flux RSS"></a>
				</div>
			</div>
		</div>
		# ENDIF #
		<div class="footer-infos">
			<span>
				{L_POWERED_BY} <a href="http://www.phpboost.com" title="PHPBoost">PHPBoost</a> {L_PHPBOOST_RIGHT}
			</span>
			# IF C_DISPLAY_BENCH #
			<span>
				&nbsp;|&nbsp;
				{L_ACHIEVED} {BENCH}{L_UNIT_SECOND} - {REQ} {L_REQ} - {MEMORY_USED}
			</span>
			# ENDIF #
			# IF C_DISPLAY_AUTHOR_THEME #
			<span>
				| {L_THEME} {L_THEME_NAME} {L_BY}
				<a href="{U_THEME_AUTHOR_LINK}">{L_THEME_AUTHOR}</a>
			</span>
			# ENDIF #
		</div>

	</footer>