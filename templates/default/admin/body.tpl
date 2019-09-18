
	<header id="header-admin">
		<nav class="admin-index">
			<ul>
				<li>
					<a href="{PATH_TO_ROOT}/" aria-label="{L_INDEX_SITE}">
						<i class="fa fa-fw fa-home" aria-hidden="true"></i> <span>{L_INDEX_SITE}</span>
					</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/admin/admin_index.php" aria-label="{L_ADMINISTRATION}">
						<i class="fa fa-fw fa-cogs" aria-hidden="true"></i> <span>{L_ADMINISTRATION}</span>
					</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/admin/admin_extend.php" aria-label="{L_EXTEND_MENU}">
						<i class="fa fa-fw fa-th" aria-hidden="true"></i> <span>{L_EXTEND_MENU}</span>
					</a>
				</li>
				<li>
					<a href="${relative_url(UserUrlBuilder::disconnect())}" aria-label="{L_DISCONNECT}">
						<i class="fa fa-fw fa-sign-out" aria-hidden="true"></i> <span>{L_DISCONNECT}</span>
					</a>
				</li>
			</ul>
		</nav>
		<div class="header-admin-container">
			<div id="top-header-admin">
				<div id="site-name-container">
					<a id="site-name" href="{PATH_TO_ROOT}/" aria-label="{SITE_NAME}">{SITE_NAME}</a>
				</div>
			</div>
			<div id="sub-header-admin">
				<div id="admin-link">
					<h3 class="menu-title">
						<div class="site-logo" # IF C_HEADER_LOGO #style="background-image: url({HEADER_LOGO});"# ENDIF #></div>
						<span>{L_ADMIN_MAIN_MENU}</span>
					</h3>
					# INCLUDE subheader_menu #
				</div>

				<div id="support-pbt">
					<nav class="admin-menu">
						<h3 class="menu-title">
							<div class="pbt-logo"></div>
							<span>{L_NEED_HELP}</span>
						</h3>
						<ul>
							<li class="admin-li">
								<a href="https://www.phpboost.com/forum" aria-label="{L_INDEX_SUPPORT}">
									<i class="fa fa-fw fa-globe" aria-hidden="true"></i> {L_INDEX_SUPPORT}
								</a>
							</li>
							<li class="admin-li">
								<a href="https://www.phpboost.com/faq" aria-label="{L_INDEX_FAQ}">
									<i class="fa fa-fw fa-question-circle" aria-hidden="true"></i> {L_INDEX_FAQ}
								</a>
							</li>
							<li class="admin-li">
								<a href="https://www.phpboost.com/wiki" aria-label="{L_INDEX_DOCUMENTATION}">
									<i class="fa fa-fw fa-book" aria-hidden="true"></i> {L_INDEX_DOCUMENTATION}
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
				{L_POWERED_BY} <a class="powered-by" href="https://www.phpboost.com" aria-label="PHPBoost">PHPBoost</a> {L_PHPBOOST_RIGHT}
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
