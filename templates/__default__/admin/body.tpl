<span id="scroll-to-bottom" class="scroll-to" role="button" aria-label="{@common.scroll.to.bottom}"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>

<header id="header-admin">
	<nav class="admin-index">
		<ul>
			<li>
				<a href="{PATH_TO_ROOT}/" aria-label="{@menu.site}">
					<i class="fa fa-fw fa-home" aria-hidden="true"></i>
				</a>
			</li>
			<li>
				<a href="{PATH_TO_ROOT}/admin/admin_index.php" aria-label="{@menu.dashboard}">
					<i class="fa fa-fw fa-cogs" aria-hidden="true"></i>
				</a>
			</li>
			<li>
				<a href="{PATH_TO_ROOT}/admin/admin_extend.php" aria-label="{@menu.extended}">
					<i class="fa fa-fw fa-th" aria-hidden="true"></i>
				</a>
			</li>
			<li>
				<a href="${relative_url(UserUrlBuilder::disconnect())}" aria-label="{@menu.sign.out}">
					<i class="fa fa-fw fa-sign-out-alt" aria-hidden="true"></i>
				</a>
			</li>
		</ul>
	</nav>
	<div class="header-admin-container">
		<div id="top-header-admin">
			<h1 id="site-name-container">
				<a id="site-name" href="{PATH_TO_ROOT}/">{SITE_NAME}</a>
			</h1>
		</div>
		<div id="sub-header-admin">
			<div id="admin-link">
				<div class="menu-title">
					<div class="site-logo" # IF C_HEADER_LOGO #style="background-image: url({U_HEADER_LOGO});"# ENDIF #></div>
					<h5>{@menu.menu}</h5>
				</div>
				# INCLUDE SUBHEADER_MENU #
			</div>

			<div id="support-pbt">
				<div class="menu-title">
					<div class="pbt-logo"></div>
					<h5>{@menu.need.help}</h5>
				</div>
				<nav class="admin-menu">
					<ul>
						<li class="admin-li">
							<a href="https://www.phpboost.com/forum">
								<i class="fa fa-fw fa-globe" aria-hidden="true"></i> {@menu.support}
							</a>
						</li>
						<li class="admin-li">
							<a href="https://www.phpboost.com/faq">
								<i class="fa fa-fw fa-question-circle" aria-hidden="true"></i> {@menu.faq}
							</a>
						</li>
						<li class="admin-li">
							<a href="https://www.phpboost.com/wiki">
								<i class="fa fa-fw fa-book" aria-hidden="true"></i> {@menu.documentation}
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</header>

<div id="preloader-status">
	<i class="fa iboost fa-iboost-logo fa-5x blink link-color"></i>
</div>
<div id="global" class="body-wrapper content-preloader">
	<div id="admin-main">
		# INCLUDE KERNEL_MESSAGE #
		{CONTENT}
	</div>

	<footer id="footer">
		<span>
			{@common.powered.by} <i class="fa iboost fa-iboost-logo" aria-hidden="true"></i> <a class="powered-by" href="https://www.phpboost.com" aria-label="{@common.phpboost.link}"> PHPBoost </a> | <span aria-label="{@common.phpboost.right}"><i class="fab fa-osi" aria-hidden="true"></i></span>
		</span>
		# IF C_DISPLAY_BENCH #
			<span>
			| {@common.achieved} {BENCH}{@date.unit.seconds} - {REQ} {@common.sql.request} - {MEMORY_USED}
			</span>
		# ENDIF #
		# IF C_DISPLAY_AUTHOR_THEME #
			<span>
			| {@common.theme} {L_THEME_NAME} ${TextHelper::strtolower(@common.by)} <a href="{U_THEME_AUTHOR_LINK}">{L_THEME_AUTHOR}</a>
			</span>
		# ENDIF #
	</footer>

	<span id="scroll-to-top" class="scroll-to" role="button" aria-label="{@common.scroll.to.top}"><i class="fa fa-chevron-up" aria-hidden="true"></i></span>
</div>
