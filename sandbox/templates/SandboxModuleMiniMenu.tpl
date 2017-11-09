
	# IF C_IS_SUPERADMIN #
	<div id="# IF C_SLIDE_RIGHT #mini-sandbox-right# ELSE #mini-sandbox-left# ENDIF #">
		<a href="" class="sandbox-toggle-btn# IF C_HORIZONTAL # toggle-hor# IF C_SLIDE_RIGHT # toggle-right# ELSE # toggle-left# ENDIF ## ENDIF #" onclick="openSandboxMenu('# IF C_SLIDE_RIGHT #mini-sandbox-right# ELSE #mini-sandbox-left# ENDIF #');return false;">
			<i class="fa fa-wrench"></i> {@module.title}
		</a>
		<div class="sandbox-menu">
			<a href="" class="close-btn" onclick="openSandboxMenu('# IF C_SLIDE_RIGHT #mini-sandbox-right# ELSE #mini-sandbox-left# ENDIF #');return false;"><i class="fa fa-window-close-o"></i> {@mini.close}</a>
			<div class="sandbox-inset">
				<div class="item-menu sandbox-text">
					<div class="item-2x small" title="{@mini.version.pbt}">
						{PBT_VERSION}
					</div>
					<div class="item-2x small" title="{@mini.version.php}">
						{PHP_VERSION}
					</div>
					<div class="item-2x small" title="{@mini.version.date}">
						{INSTALL_DATE}
					</div>
					<div class="item-2x small" title="{@mini.version.sql}">
						{DBMS_VERSION}
					</div>
					<div class="item-2x small" title="{@mini.viewport.h}">
						<span class="icon-stack">
			                <i class="fa fa-tv icon-main"></i>
			                <i class="fa fa-arrows-h icon-sup"></i>
			            </span>
						<span class="item-number">
							<span id="window-width"></span>
						</span>
					</div>
					<div class="item-2x small" title="{@mini.viewport.v}">
						<span class="icon-stack">
			                <i class="fa fa-tv icon-main"></i>
			                <i class="fa fa-arrows-v icon-sup"></i>
			            </span>
						<span class="item-number">
							<span id="window-height"></span>
						</span>
					</div>
				</div>
				<div class="item-menu">
					<div class="item-title">{@mini.tools}</div>
					# IF C_CSS_CACHE_ENABLED #
						<div class="item-form item-2x" title="{@mini.disable.css.cache}"># INCLUDE DISABLE_CSS_CACHE #</div>
					# ELSE #
						<div class="item-form item-2x" title="{@mini.enable.css.cache}"># INCLUDE ENABLE_CSS_CACHE #</div>
					# ENDIF #
					<div class="item-form item-2x" title="{@mini.clean.css.cache}"># INCLUDE CLEAN_CSS_CACHE #</div>
					<div class="item-form item-2x" title="{@mini.clean.tpl.cache}"># INCLUDE CLEAN_TPL_CACHE #</div>
					<div class="item-form item-2x" title="{@mini.clean.rss.cache}"># INCLUDE CLEAN_SYNDICATION_CACHE #</div>
					<div class="item-2x# IF C_LOGGED_ERRORS # blink# ENDIF #" title="{@mini.errors}">
						<a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}">
							<span class="icon-stack">
				                <i class="fa fa-terminal icon-main"></i>
				                <i class="fa fa-warning icon-sup"></i>
				            </span>
							<span class="item-number">
								({ERRORS_NB})
							</span>
						</a>
					</div>
					<div class="item-2x# IF C_404_ERRORS # blink# ENDIF #" title="{@mini.404}">
						<a href="${relative_url(AdminErrorsUrlBuilder::list_404_errors())}">
							<span class="icon-stack">
				                <i class="fa fa-chain-broken icon-main"></i>
				                <i class="fa fa-warning icon-sup"></i>
				            </span>
							<span class="item-number">
								({404_NB})
							</span>
						</a>
					</div>
					<div class="item-2x" title="{@mini.database}">
						<a href="{PATH_TO_ROOT}/database/admin_database.php">
							<span class="icon-stack">
				                <i class="fa fa-database icon-main"></i>
				                <i class="fa fa-cog icon-sup"></i>
				            </span>
						</a>
					</div>
					<div class="item-2x" title="{@mini.coms}">
						<a href="${relative_url(UserUrlBuilder::comments())}">
							<span class="icon-stack">
				                <i class="fa fa-comments icon-main"></i>
				                <i class="fa fa-list icon-sup"></i>
				            </span>
							<span class="item-number">
								({COMMENTS_NB})
							</span>
						</a>
					</div>
				</div>
				<div class="item-menu">
					<div class="item-title">{@mini.personalization}</div>
					<div class="item-3x" title="{@mini.menus}/{@mini.config}">
						<a href="{PATH_TO_ROOT}/admin/menus/menus.php">
							<span class="icon-stack">
				                <i class="fa fa-bars icon-main"></i>
				                <i class="fa fa-cog icon-sup"></i>
				            </span>
						</a>
					</div>
					<div class="item-form item-3x">
						# IF C_LEFT_ENABLED #
							<div title="{@mini.disable.left.col}"># INCLUDE DISABLE_LEFT_COL #</div>
						# ELSE #
							<div title="{@mini.enable.left.col}"># INCLUDE ENABLE_LEFT_COL #</div>
						# ENDIF #
					</div>
					<div class="item-form item-3x">
						# IF C_RIGHT_ENABLED #
							<div title="{@mini.disable.right.col}"># INCLUDE DISABLE_RIGHT_COL #</div>
						# ELSE #
							<div title="{@mini.enable.right.col}"># INCLUDE ENABLE_RIGHT_COL #</div>
						# ENDIF #
					</div>
					<div class="item-4x" title="{@mini.theme}/{@mini.manage}">
						<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}">
							<span class="icon-stack">
								<i class="fa fa-image icon-main"></i>
								<i class="fa fa-list icon-sup"></i>
							</span>
						</a>
					</div>
					<div class="item-4x" title="{@mini.theme}/{@mini.add}">
						<a href="${relative_url(AdminThemeUrlBuilder::add_theme())}">
							<span class="icon-stack">
								<i class="fa fa-image icon-main"></i>
								<i class="fa fa-plus icon-sup"></i>
							</span>
						</a>
					</div>
					<div class="item-4x" title="{@mini.mod}/{@mini.manage}">
						<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}">
							<span class="icon-stack">
								<i class="fa fa-cube icon-main"></i>
								<i class="fa fa-list icon-sup"></i>
							</span>
						</a>
					</div>
					<div class="item-4x" title="{@mini.mod}/{@mini.add}">
						<a href="${relative_url(AdminModulesUrlBuilder::add_module())}">
							<span class="icon-stack">
								<i class="fa fa-cube icon-main"></i>
								<i class="fa fa-plus icon-sup"></i>
							</span>
						</a>
					</div>
					<div class="item-4x" title="{@mini.user}/{@mini.manage}">
						<a href="${relative_url(AdminMembersUrlBuilder::management())}">
							<span class="icon-stack">
								<i class="fa fa-user-o icon-main"></i>
								<i class="fa fa-list icon-sup"></i>
							</span>
						</a>
					</div>
					<div class="item-4x" title="{@mini.user}/{@mini.add}">
						<a href="${relative_url(AdminMembersUrlBuilder::add())}">
							<span class="icon-stack">
								<i class="fa fa-user-o icon-main"></i>
								<i class="fa fa-plus icon-sup"></i>
							</span>
						</a>
					</div>
					<div class="item-4x" title="{@mini.config}/{@mini.general.config}">
						<a href="${relative_url(AdminConfigUrlBuilder::general_config())}">
							<span class="icon-stack">
								<i class="fa fa-institution icon-main"></i>
								<i class="fa fa-cog icon-sup"></i>
							</span>
						</a>
					</div>
					<div class="item-4x" title="{@mini.config}/{@mini.advanced.config}">
						<a href="${relative_url(AdminConfigUrlBuilder::advanced_config())}">
							<span class="icon-stack">
								<i class="fa fa-institution icon-main"></i>
								<i class="fa fa-cogs icon-sup"></i>
							</span>
						</a>
					</div>
				</div>
				<div class="item-menu">
					<div class="item-title">{@mini.sandbox.mod}</div>
					<div class="item-3x"><a href="{PATH_TO_ROOT}/sandbox/form" title="{@mini.sandbox.form}"><i class="fa fa-square-o fa-2x"></i></a></div>
					<div class="item-3x"><a href="{PATH_TO_ROOT}/sandbox/css" title="{@mini.sandbox.css}"><i class="fa fa-css3 fa-2x"></i></a></div>
					<div class="item-3x"><a href="{PATH_TO_ROOT}/sandbox/bbcode" title="{@mini.sandbox.bbcode}"><i class="fa fa-code fa-2x"></i></a></div>
					<div class="item-3x"><a href="{PATH_TO_ROOT}/sandbox/menu" title="{@mini.sandbox.menu}"><i class="fa fa-bars fa-2x"></i></a></div>
					<div class="item-3x"><a href="{PATH_TO_ROOT}/sandbox/table" title="{@mini.sandbox.table}"><i class="fa fa-table fa-2x"></i></a></div>
					<div class="item-3x"><a href="${relative_url(SandboxUrlBuilder::config())}" title="{@mini.config}"><i class="fa fa-cogs fa-2x"></i></a></div>
				</div>
			</div>
		</div>
	</div>
	<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>

# ENDIF #
