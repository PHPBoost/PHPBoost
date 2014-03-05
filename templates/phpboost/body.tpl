	# INCLUDE MAINTAIN #
	
	<header id="header">
		<div id="site-infos">
			<div id="site-logo" # IF C_HEADER_LOGO #style="background: url('{HEADER_LOGO}') no-repeat;"# ENDIF #></div>
			<div id="site-name-container">
				<a id="site-name" href="{PATH_TO_ROOT}/">{SITE_NAME}</a>
				<span id="site-slogan">{SITE_SLOGAN}</span>
			</div>
		</div>
		<div id="top-header">
			# IF C_MENUS_HEADER_CONTENT #
			{MENUS_HEADER_CONTENT}
			# ENDIF #
		</div>
		<div id="sub-header">
			<ul class="menu-link">
				<li # IF Url::is_current_url('/', true) # class="current" # ENDIF # # IF Url::is_current_url('/index.php', true) # class="current" # ENDIF # ><a href="{PATH_TO_ROOT}/" class="title" title="Accueil">Accueil</a></li>
				<li # IF Url::is_current_url('/news') # class="current" # ENDIF #><a href="{PATH_TO_ROOT}/news" class="title" title="Actualités de PHPBoost">Actualités</a></li>
				<li # IF Url::is_current_url('/wiki') # class="current" # ENDIF #><a href="{PATH_TO_ROOT}/wiki" class="title" title="Documentation">Documentation</a></li>
				<li # IF Url::is_current_url('/forum') # class="current" # ENDIF #><a href="{PATH_TO_ROOT}/forum" class="title" title="Aide et support">Support</a></li>
				<li # IF Url::is_current_url('/download') # class="current" # ENDIF #><a href="{PATH_TO_ROOT}/download" class="title" title="Télécharger et tester PHPBoost">Téléchargement</a></li>
			</ul>
			# IF C_MENUS_SUB_HEADER_CONTENT #
			{MENUS_SUB_HEADER_CONTENT}
			# ENDIF #
		</div>
		<div class="spacer"></div>
	</header>
	
	<div id="global">
		# IF C_COMPTEUR #
		<div id="compteur">
			<span class="text-strong">{L_VISIT}:</span> {COMPTEUR_TOTAL}
			<br />
			<span class="text-strong">{L_TODAY}:</span> {COMPTEUR_DAY}
		</div>
		# ENDIF #
		
		# IF C_MENUS_LEFT_CONTENT #
		<aside id="menu-left">
			{MENUS_LEFT_CONTENT}
		</aside>
		# ENDIF #
		
		# IF C_MENUS_RIGHT_CONTENT #
		<aside id="menu-right">
			{MENUS_RIGHT_CONTENT}
		</aside>
		# ENDIF #
		
		<div id="main" role="main">
			# IF C_MENUS_TOPCENTRAL_CONTENT #
			<div id="top-content">
				{MENUS_TOPCENTRAL_CONTENT}
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
				{CONTENT}
			</div>
			# IF C_MENUS_BOTTOM_CENTRAL_CONTENT #
	        <div id="bottom-content">
				{MENUS_BOTTOMCENTRAL_CONTENT}
			</div>
			# ENDIF #
		</div>
		# IF C_MENUS_TOP_FOOTER_CONTENT #
		<div id="top-footer">
			{MENUS_TOP_FOOTER_CONTENT}
			<div class="spacer"></div>
		</div>
		# ENDIF #
	</div>
	<footer id="footer">
		<div class="footer-content">
		<div id="footer-columns-container">
			<div class="footer-columns">
				<div class="footer-columns-title">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/footer_partners.png" align="center" />
					Les partenaires
				</div>
				<div class="footer-columns-partners">
					<a href="http://www.nuxit.com/" style="text-decoration:none;" title="Nuxit">
						<p style="font-size:9px;color:#dfa959;line-height:0px;margin-bottom: 0px;font-weight:bold;">Hébergement de site web</p>
						<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/nuxit.png" align="center" alt="Nuxit"/>
						<p style="font-size:9px;font-style:italic;color:#8bb9ff;">Qualité, fiabilité, Support</p>
					</a>
				</div>
			</div>
			<div class="footer-columns">
				<div class="footer-columns-title"> 
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/footer_phpboost.png" align="center" />
					Le projet PHPBoost
				</div>
				<ul>
					<li><a href="{PATH_TO_ROOT}/pages/fonctionnalites-de-phpboost" title="Fonctionnalités de PHPBoost">Fonctionnalités</a></li>
					<li><a href="{PATH_TO_ROOT}/download/" title="Télécharger PHPBoost">Télécharger</a></li>
					<li><a href="http://demo.phpboost.com" title="Essayer PHPBoost">Démonstration</a></li>
					<li><a href="{PATH_TO_ROOT}/pages/aider-phpboost" title="Soutenir le projet">Contribuer au projet</a></li>
				</ul>
			</div>	
			<div class="footer-columns">
				<div class="footer-columns-title">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/footer_community.png" align="center" />
					Contribuer au Projet
				</div>
				<ul>
					<li><a href="{PATH_TO_ROOT}/wiki/creer-un-theme" title="Créer un thème pour PHPBoost">Créer un Thème</a></li>
					<li><a href="{PATH_TO_ROOT}/wiki/creer-un-module" title="Créer un module pour PHPBoost">Créer un Module</a></li>
					<li><a href="{PATH_TO_ROOT}/doc/" title="Documentation du framework">A.P.I.</a></li>
					<li><a href="{PATH_TO_ROOT}/bugtracker/" title="Rapporter un bug">Rapport de bugs</a></li>
					
				</ul>
			</div>	
			<div class="footer-columns footer-columns-last">
				<div class="footer-columns-title">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/footer_support.png" align="center" />
					Support PHPBoost
				</div>
				<ul>
					<li><a href="{PATH_TO_ROOT}/faq/">Foire Aux Questions</a></li>
					<li><a href="{PATH_TO_ROOT}/forum/" title="Support">Forum</a></li>
					<li><a href="{PATH_TO_ROOT}/news/" title="Actualités de PHPBoost">News</a></li>
					<li><a href="{PATH_TO_ROOT}/wiki/" title="Documentation">Documentation</a></li>
				</ul>
			</div>	
			<div class="spacer"></div>
		</div>
		<div style="margin:auto;width:920px;height:28px;">
			<div class="footer_social footer_social_guestbook">
				<a href="{PATH_TO_ROOT}/guestbook/" title="Livre d'Or"></a>
			</div>
			<div class="footer_social footer_social_gplus">			
				<a href="https://plus.google.com/103112963627704533252" rel="publisher" title="Google +"></a>
			</div>
			<div class="footer_social footer_social_twitter">			
				<a href="http://twitter.com/PHPBoostCMS" title="Twitter"></a>
			</div>
			<div class="footer_social footer_social_facebook">			
				<a href="http://www.facebook.com/pages/PHPBoost-CMS/229132847163144" title="Facebook"></a>
			</div>
			<div class="footer_social footer_social_rss">
				<a href="{PATH_TO_ROOT}/syndication/rss/news" title="Flux RSS"></a>
			</div>
		</div>
		</div>
		# IF C_MENUS_FOOTER_CONTENT #
		<div class="footer-content">
			{MENUS_FOOTER_CONTENT}
		</div>
		# ENDIF #

		
		<div class="footer-infos">
			<span>
				{L_POWERED_BY} <a href="http://phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {L_PHPBOOST_RIGHT}
			</span>	
			# IF C_DISPLAY_BENCH #
			<span>
				&nbsp;|&nbsp;		
				{L_ACHIEVED} {BENCH}{L_UNIT_SECOND} - {REQ} {L_REQ} - {MEMORY_USED}
			</span>	
			# ENDIF #
			# IF C_DISPLAY_AUTHOR_THEME #
			<span>
				| {L_THEME} {L_THEME_NAME} {L_BY} <a href="{U_THEME_AUTHOR_LINK}">{L_THEME_AUTHOR}</a>
			</span>
			# ENDIF #
		</div>
		
	</footer>