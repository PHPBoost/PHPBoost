
	<header id="header-admin">
		<nav class="admin-index">
			<ul>
				<li>
					<a href="{PATH_TO_ROOT}/" title="{L_INDEX_SITE}">
						<i class="fa fa-fw fa-home"></i> <span>{L_INDEX_SITE}</span>
					</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/admin/admin_index.php" title="{L_ADMINISTRATION}">
						<i class="fa fa-fw fa-cogs"></i> <span>{L_ADMINISTRATION}</span>
					</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/admin/admin_extend.php" title="{L_EXTEND_MENU}">
						<i class="fa fa-fw fa-th"></i> <span>{L_EXTEND_MENU}</span>
					</a>
				</li>
				<li>
					<a href="${relative_url(UserUrlBuilder::disconnect())}" title="{L_DISCONNECT}">
						<i class="fa fa-fw fa-sign-out"></i> <span>{L_DISCONNECT}</span>
					</a>
				</li>
			</ul>
		</nav>
		<div class="header-admin-container">
			<div id="top-header-admin">
				<div id="site-name-container">
					<a id="site-name" href="{PATH_TO_ROOT}/" title="{SITE_NAME}">{SITE_NAME}</a>
				</div>
			</div>
			<div id="sub-header-admin">
				<div id="admin-link">
					<h3 class="menu-title">
						<div class="site-logo" # IF C_HEADER_LOGO #style="background-image: url('{HEADER_LOGO}');"# ENDIF #></div>
						<span>{L_ADMIN_MAIN_MENU}</span>
					</h3>
					# INCLUDE subheader_menu #
				</div>	

				<div id="support-pbt">
					<nav>
						<h3 class="menu-title">
							<div class="pbt-logo"></div>
							<span>{L_NEED_HELP}</span>
						</h3>
						<ul>
							<li>
								<a href="http://www.phpboost.com/forum" title="{L_INDEX_SUPPORT}">
									<i class="fa fa-fw fa-globe"></i> {L_INDEX_SUPPORT}
								</a>
							</li>
							<li>
								<a href="http://www.phpboost.com/faq" title="{L_INDEX_FAQ}">
									<i class="fa fa-fw fa-question-circle"></i> {L_INDEX_FAQ}
								</a>
							</li>
							<li>
								<a href="http://www.phpboost.com/wiki" title="{L_INDEX_DOCUMENTATION}">
									<i class="fa fa-fw fa-book"></i> {L_INDEX_DOCUMENTATION}
								</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</header>
	
	<div id="global">
		
		<div id="admin-main">
			# INCLUDE KERNEL_MESSAGE #
			{CONTENT}
		</div>
		
		<footer id="footer">
			<span>
				{L_POWERED_BY} <a class="powered-by" href="http://www.phpboost.com" title="PHPBoost">PHPBoost</a> {L_PHPBOOST_RIGHT}
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
