# IF C_IS_SUPERADMIN #
	<div id="module-mini-sandbox" class="# IF C_SLIDE_RIGHT #mini-sbx-right# ELSE #mini-sbx-left# ENDIF #">
		<span class="sbx-toggle-btn bgc-full administrator# IF C_HORIZONTAL # toggle-hor# IF C_SLIDE_RIGHT # toggle-right# ELSE # toggle-left# ENDIF ## ENDIF #">
			<i class="fa fa-wrench" aria-hidden="true"></i> {@sandbox.module.title}
		</span>
		<div class="sbx-menu bgc-main">
			<span class="close-btn bkgd-sub"><i class="far fa-window-close" aria-hidden="true" aria-hidden="true"></i> {@mini.close}</span>
			<div class="sbx-inset">
				<div class="sbx-menu-item sbx-text">
					<div class="item-2x small" aria-label="{@mini.version.pbt}">
						<span class="sr-only">{@mini.version.pbt} : </span>{PBT_VERSION}
					</div>
					<div class="item-2x small" aria-label="{@mini.version.php}">
						<span class="sr-only">{@mini.version.php} : </span>{PHP_VERSION}
					</div>
					<div class="item-2x small" aria-label="{@mini.version.date}">
						<span class="sr-only">{@mini.version.date} : </span>{INSTALL_DATE}
					</div>
					<div class="item-2x small" aria-label="{@mini.version.sql}">
						<span class="sr-only">{@mini.version.sql} : </span>{DBMS_VERSION}
					</div>
					<div class="item-2x small" aria-label="{@mini.viewport.h}">
						<span class="icon-stack">
							<i class="fa fa-tv icon-main" aria-hidden="true"></i>
							<i class="fa fa-arrows-alt-h icon-sup" aria-hidden="true"></i>
						</span> <span class="sr-only">{@mini.viewport.h}</span>
						<span class="item-number">
							<span id="window-width"></span>
						</span>
					</div>
					<div class="item-2x small" aria-label="{@mini.viewport.v}">
						<span class="icon-stack">
							<i class="fa fa-tv icon-main" aria-hidden="true"></i>
							<i class="fa fa-arrows-alt-v icon-sup" aria-hidden="true"></i>
						</span> <span class="sr-only">{@mini.viewport.v}</span>
						<span class="item-number">
							<span id="window-height"></span>
						</span>
					</div>
				</div>
				<div class="sbx-menu-item">
					<div class="sbx-item-title bgc-title">{@mini.tools}</div>
					# IF C_CSS_CACHE_ENABLED #
						<div class="item-form item-2x" aria-label="{@mini.disable.css.cache}"># INCLUDE DISABLE_CSS_CACHE #</div>
					# ELSE #
						<div class="item-form item-2x" aria-label="{@mini.enable.css.cache}"># INCLUDE ENABLE_CSS_CACHE #</div>
					# ENDIF #
					<div class="item-form item-2x" aria-label="{@mini.clean.css.cache}"># INCLUDE CLEAN_CSS_CACHE #</div>
					<div class="item-form item-2x" aria-label="{@mini.clean.tpl.cache}"># INCLUDE CLEAN_TPL_CACHE #</div>
					<div class="item-form item-2x" aria-label="{@mini.clean.rss.cache}"># INCLUDE CLEAN_RSS_CACHE #</div>
					<div class="item-2x# IF C_LOGGED_ERRORS # blink# ENDIF #" aria-label="{@mini.errors}">
						<a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}">
							<span class="icon-stack">
								<i class="fa fa-terminal icon-main" aria-hidden="true"></i>
								<i class="fa fa-exclamation-triangle icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@mini.errors}</span>
							<span class="item-number">
								({ERRORS_NB})
							</span>
						</a>
					</div>
					<div class="item-2x# IF C_404_ERRORS # blink# ENDIF #" aria-label="{@mini.404}">
						<a href="${relative_url(AdminErrorsUrlBuilder::list_404_errors())}">
							<span class="icon-stack">
								<i class="fa fa-unlink icon-main" aria-hidden="true"></i>
								<i class="fa fa-exclamation-triangle icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@mini.404}</span>
							<span class="item-number">
								({404_NB})
							</span>
						</a>
					</div>
					<div class="item-2x" aria-label="{@mini.database}">
						<a href="{PATH_TO_ROOT}/database/admin_database.php">
							<span class="icon-stack">
								<i class="fa fa-database icon-main" aria-hidden="true"></i>
								<i class="fa fa-cog icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@mini.database}</span>
						</a>
					</div>
					<div class="item-2x" aria-label="{@mini.coms}">
						<a href="${relative_url(UserUrlBuilder::comments())}">
							<span class="icon-stack">
								<i class="fa fa-comments icon-main" aria-hidden="true"></i>
								<i class="fa fa-list icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@mini.coms}</span>
							<span class="item-number">
								({COMMENTS_NB})
							</span>
						</a>
					</div>
				</div>
				<div class="sbx-menu-item">
					<div class="sbx-item-title bgc-title">{@mini.personalization}</div>
					<div class="item-3x" aria-label="{@mini.menus}/{@title.config}">
						<a href="{PATH_TO_ROOT}/admin/menus/menus.php">
							<span class="icon-stack">
								<i class="fa fa-bars icon-main" aria-hidden="true"></i>
								<i class="fa fa-cog icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@mini.menus}/{@title.config}</span>
						</a>
					</div>
					<div class="item-form item-3x"# IF C_LEFT_ENABLED # aria-label="{@mini.disable.left.col}"# ELSE # aria-label="{@mini.enable.left.col}"# ENDIF #>
						# IF C_LEFT_ENABLED #
							<div># INCLUDE DISABLE_LEFT_COL #</div>
						# ELSE #
							<div># INCLUDE ENABLE_LEFT_COL #</div>
						# ENDIF #
					</div>
					<div class="item-form item-3x"# IF C_RIGHT_ENABLED # aria-label="{@mini.disable.right.col}"# ELSE # aria-label="{@mini.enable.right.col}"# ENDIF #>
						# IF C_RIGHT_ENABLED #
							<div># INCLUDE DISABLE_RIGHT_COL #</div>
						# ELSE #
							<div># INCLUDE ENABLE_RIGHT_COL #</div>
						# ENDIF #
					</div>
					<div class="item-4x" aria-label="{@mini.theme}/{@mini.manage}">
						<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}">
							<span class="icon-stack">
								<i class="far fa-image icon-main" aria-hidden="true"></i>
								<i class="fa fa-list icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@mini.theme}/{@mini.manage}</span>
						</a>
					</div>
					<div class="item-4x" aria-label="{@mini.theme}/{@mini.add}">
						<a href="${relative_url(AdminThemeUrlBuilder::add_theme())}">
							<span class="icon-stack">
								<i class="far fa-image icon-main" aria-hidden="true"></i>
								<i class="fa fa-plus icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@mini.theme}/{@mini.add}</span>
						</a>
					</div>
					<div class="item-4x" aria-label="{@mini.mod}/{@mini.manage}">
						<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}">
							<span class="icon-stack">
								<i class="fa fa-cubes icon-main" aria-hidden="true"></i>
								<i class="fa fa-list icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@mini.mod}/{@mini.manage}</span>
						</a>
					</div>
					<div class="item-4x" aria-label="{@mini.mod}/{@mini.add}">
						<a href="${relative_url(AdminModulesUrlBuilder::add_module())}">
							<span class="icon-stack">
								<i class="fa fa-cubes icon-main" aria-hidden="true"></i>
								<i class="fa fa-plus icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@mini.mod}/{@mini.add}</span>
						</a>
					</div>
					<div class="item-4x" aria-label="{@mini.user}/{@mini.manage}">
						<a href="${relative_url(AdminMembersUrlBuilder::management())}">
							<span class="icon-stack">
								<i class="far fa-user icon-main" aria-hidden="true"></i>
								<i class="fa fa-list icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@mini.user}/{@mini.manage}</span>
						</a>
					</div>
					<div class="item-4x" aria-label="{@mini.user}/{@mini.add}">
						<a href="${relative_url(AdminMembersUrlBuilder::add())}">
							<span class="icon-stack">
								<i class="far fa-user icon-main" aria-hidden="true"></i>
								<i class="fa fa-plus icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@mini.user}/{@mini.add}</span>
						</a>
					</div>
					<div class="item-4x" aria-label="{@title.config}/{@mini.general.config}">
						<a href="${relative_url(AdminConfigUrlBuilder::general_config())}">
							<span class="icon-stack">
								<i class="fa fa-university icon-main" aria-hidden="true"></i>
								<i class="fa fa-cog icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@title.config}/{@mini.general.config}</span>
						</a>
					</div>
					<div class="item-4x" aria-label="{@title.config}/{@mini.advanced.config}">
						<a href="${relative_url(AdminConfigUrlBuilder::advanced_config())}">
							<span class="icon-stack">
								<i class="fa fa-university icon-main" aria-hidden="true"></i>
								<i class="fa fa-cogs icon-sup" aria-hidden="true"></i>
							</span> <span class="sr-only">{@title.config}/{@mini.advanced.config}</span>
						</a>
					</div>
				</div>
				<div class="sbx-menu-item">
					<div class="sbx-item-title bgc-title">{@sandbox.module.title}</div>
					<div class="item-4x" aria-label="{@title.form.builder}"><a href="{PATH_TO_ROOT}/sandbox/form"><i class="far fa-square fa-2x" aria-hidden="true"></i> <span class="sr-only">{@title.form.builder}</span></a></div>
					<div class="item-4x" aria-label="{@title.css}"><a href="{PATH_TO_ROOT}/sandbox/css"><i class="fab fa-css3 fa-2x" aria-hidden="true"></i> <span class="sr-only">{@title.css}</span></a></div>
					<div class="item-4x" aria-label="{@title.bbcode}"><a href="{PATH_TO_ROOT}/sandbox/bbcode"><i class="fa fa-code fa-2x" aria-hidden="true"></i> <span class="sr-only">{@title.bbcode}</span></a></div>
					<div class="item-4x" aria-label="{@title.multitabs}"><a href="{PATH_TO_ROOT}/sandbox/multitabs"><i class="fa fa-list fa-2x" aria-hidden="true"></i> <span class="sr-only">{@title.multitabs}</span></a></div>
					<div class="item-4x" aria-label="{@title.plugins}"><a href="{PATH_TO_ROOT}/sandbox/plugins"><i class="fa fa-cube fa-2x" aria-hidden="true"></i> <span class="sr-only">{@title.plugins}</span></a></div>
					<div class="item-4x" aria-label="{@title.menu}"><a href="{PATH_TO_ROOT}/sandbox/menu"><i class="fa fa-bars fa-2x" aria-hidden="true"></i> <span class="sr-only">{@title.menu}</span></a></div>
					<div class="item-4x" aria-label="{@title.table.builder}"><a href="{PATH_TO_ROOT}/sandbox/table"><i class="fa fa-table fa-2x" aria-hidden="true"></i> <span class="sr-only">{@title.table.builder}</span></a></div>
					<div class="item-4x" aria-label="{@title.config}"><a href="${relative_url(SandboxUrlBuilder::config())}"><i class="fa fa-cogs fa-2x" aria-hidden="true"></i> <span class="sr-only">{@title.config}</span></a></div>
				</div>
				<div class="sbx-menu-item">
					<div class="sbx-item-title bgc-title">{@mini.themes.switcher}</div>
					<div class="item-form item-2x3">
						<form action="{REWRITED_SCRIPT}" method="get">
							<label for="switchtheme">
								<select id="switchtheme" name="switchtheme" onchange="document.location = '?switchtheme=' + this.options[this.selectedIndex].value;">
								# START themes #
									<option value="{themes.IDNAME}"# IF themes.C_SELECTED# selected="selected"# ENDIF #>{themes.NAME}</option>
								# END themes #
								</select>
							</label>
						</form>
					</div>
					<div class="item-3x">
						<a href="?switchtheme={DEFAULT_THEME}">{@mini.default.theme}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>
	<script src="{PATH_TO_ROOT}/templates/default/plugins/form/validator.js"></script>
	<script src="{PATH_TO_ROOT}/templates/default/plugins/form/form.js"></script>

# ENDIF #
