
	<header id="header-admin">
			<div class="header-admin-container">		
				<div id="top-header-admin">
					<div id="site-name-container">
						<a id="site-name" href="{PATH_TO_ROOT}/">{SITE_NAME}</a>
					</div>
				</div>
				<div id="sub-header-admin">
					<div id="admin-link"># INCLUDE subheader_menu #</div>					
				</div>
				<div id="support-pbt">
					<div id="site-logo" # IF C_HEADER_LOGO #style="background: url('{HEADER_LOGO}') no-repeat;"# ENDIF #></div>
					<nav>
						<ul>
							<li>
								<a href="http://www.phpboost.com/forum" title="Forum">
									<i class="fa fa-fw fa-globe"></i> Support</a>
							</li>
							<li>
								<a href="http://www.phpboost.com/faq" title="F.A.Q.">
									<i class="fa fa-fw fa-question-circle"></i> F.A.Q.
								</a>
							</li>
							<li>
								<a href="http://www.phpboost.com/wiki" title="Documentation">
									<i class="fa fa-fw fa-search"></i> Documentation
								</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
	</header>
	
	<div id="global">
		
		<div id="admin-main">
			{CONTENT}
		</div>
		
		<footer id="footer">
			<span>
				{L_POWERED_BY} <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost</a> {L_PHPBOOST_RIGHT}
			</span>	
			# IF C_DISPLAY_BENCH #
				<span>
				{L_ACHIEVED} {BENCH}{L_UNIT_SECOND} - {REQ} {L_REQ} - {MEMORY_USED}
				</span>	
			# ENDIF #
			# IF C_DISPLAY_AUTHOR_THEME #
				<span>
				| {L_THEME} {L_THEME_NAME} {L_BY} <a href="{U_THEME_AUTHOR_LINK}">{L_THEME_AUTHOR}</a>
				</span>
			# ENDIF #
		</footer>
		
	</div>
