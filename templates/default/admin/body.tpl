<span id="scroll-to-bottom" class="scroll-to" role="button" aria-label="${LangLoader::get_message('scroll-to.bottom', 'user-common')}"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>

<header id="header-admin">
	<nav class="admin-index">
		<ul>
			<li>
				<a href="{PATH_TO_ROOT}/" aria-label="{L_INDEX_SITE}">
					<i class="fa fa-fw fa-home" aria-hidden="true"></i>
				</a>
			</li>
			<li>
				<a href="{PATH_TO_ROOT}/admin/admin_index.php" aria-label="{L_INDEX_DASHBOARD}">
					<i class="fa fa-fw fa-cogs" aria-hidden="true"></i>
				</a>
			</li>
			<li>
				<a href="{PATH_TO_ROOT}/admin/admin_extend.php" aria-label="{L_EXTEND_MENU}">
					<i class="fa fa-fw fa-th" aria-hidden="true"></i>
				</a>
			</li>
			<li>
				<a href="${relative_url(UserUrlBuilder::disconnect())}" aria-label="{L_DISCONNECT}">
					<i class="fa fa-fw fa-sign-out-alt" aria-hidden="true"></i>
				</a>
			</li>
		</ul>
	</nav>
	<div class="header-admin-container">
		<div id="top-header-admin">
			<div id="site-name-container">
				<a id="site-name" href="{PATH_TO_ROOT}/">{SITE_NAME}</a>
			</div>
		</div>
		<div id="sub-header-admin">
			<div id="admin-link">
				<div class="menu-title">
					<div class="site-logo" # IF C_HEADER_LOGO #style="background-image: url({HEADER_LOGO});"# ENDIF #></div>
					<h5>{L_ADMIN_MAIN_MENU}</h5>
				</div>
				# INCLUDE subheader_menu #
			</div>

			<div id="support-pbt">
				<div class="menu-title">
					<div class="pbt-logo"></div>
					<h5>{L_NEED_HELP}</h5>
				</div>
				<nav class="admin-menu">
					<ul>
						<li class="admin-li">
							<a href="https://www.phpboost.com/forum">
								<i class="fa fa-fw fa-globe" aria-hidden="true"></i> {L_INDEX_SUPPORT}
							</a>
						</li>
						<li class="admin-li">
							<a href="https://www.phpboost.com/faq">
								<i class="fa fa-fw fa-question-circle" aria-hidden="true"></i> {L_INDEX_FAQ}
							</a>
						</li>
						<li class="admin-li">
							<a href="https://www.phpboost.com/wiki">
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

	<span id="scroll-to-top" class="scroll-to" role="button" aria-label="${LangLoader::get_message('scroll-to.top', 'user-common')}"><i class="fa fa-chevron-up" aria-hidden="true"></i></span>
</div>
