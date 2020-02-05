# IF C_IS_SUPERADMIN #
	<div id="module-mini-sandbox" class="# IF C_SLIDE_TOP #mini-sbx-top# ENDIF ## IF C_SLIDE_RIGHT #mini-sbx-right# ENDIF ## IF C_SLIDE_BOTTOM #mini-sbx-bottom# ENDIF ## IF C_SLIDE_LEFT #mini-sbx-left# ENDIF #">
		<a class="toggle-fwkboost pushmenu-toggle sbx-toggle-btn bgc-full administrator# IF C_HORIZONTAL # toggle-hor# IF C_SLIDE_RIGHT # toggle-right# ELSE # toggle-left# ENDIF ## ENDIF #">
			<i class="fa fa-wrench" aria-hidden></i> {@sandbox.module.title}
		</a>
		<nav id="pushmenu-fwkboost">
			<ul>
				<li id="mini-sandbox-infos">
					<span><i class="fa fa-info fa-fw notice"></i><span class="item-label">{@mini.infos}</span></span>
					<ul>
						<li>
							<div class="flex-between">
								<span class="smaller">{@mini.version.pbt}</span>
								<span>{PBT_VERSION}</span>
							</div>
						</li>
						<li>
							<div class="flex-between">
								<span class="smaller">{@mini.version.php}</span>
								<span>{PHP_VERSION}</span>
							</div>
						</li>
						<li>
							<div class="flex-between">
								<span class="smaller">{@mini.version.date}</span>
								<span>{INSTALL_DATE}</span>
							</div>
						</li>
						<li>
							<div class="flex-between">
								<span class="smaller">{@mini.version.sql}</span>
								<span class="align-right">{DBMS_VERSION}</span>
							</div>
						</li>
						<li>
							<div class="flex-between">
								<span class="align-center" aria-label="{@mini.viewport.h}">
									<span class="stacked">
										<i class="fa fa-tv" aria-hidden></i>
										<i class="fa fa-arrows-alt-h stack-event stack-sup stack-right" aria-hidden></i>
									</span>
									<p id="window-width"></p>
								</span>
								<span class="align-center">
									<span class="stacked" aria-label="{@mini.viewport.v}">
										<i class="fa fa-tv" aria-hidden></i>
										<i class="fa fa-arrows-alt-v stack-event stack-sup stack-right" aria-hidden></i>
									</span>
									<p id="window-height"></p>
								</span>
							</div>
						</li>
					</ul>
				</li>
				<li id="mini-sandbox-tools">
					<span class="mini-toolbox flex-between">
						<span><i class="fa fa-toolbox fa-fw error"></i> <span class="item-label">{@mini.tools}</span></span>
						# IF C_LOGGED_ERRORS #<span class="warning blink"><i class="fa fa-exclamation-triangle fa-fw"></i></span># ELSE #
						# IF C_404_ERRORS #<span class="warning blink"><i class="fa fa-exclamation-triangle fa-fw"></i></span># ENDIF ## ENDIF #
					</span>
					<ul>
						<li>
							# IF C_CSS_CACHE_ENABLED #
								# INCLUDE DISABLE_CSS_CACHE #
							# ELSE #
								# INCLUDE ENABLE_CSS_CACHE #
							# ENDIF #
						</li>
						<li># INCLUDE CLEAN_CSS_CACHE #</li>
						<li># INCLUDE CLEAN_TPL_CACHE #</li>
						<li># INCLUDE CLEAN_RSS_CACHE #</li>
						<li>
							<a class="flex-between" href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}">
								<span><i class="fa fa-fw fa-terminal# IF C_LOGGED_ERRORS # warning blink# ENDIF #" aria-hidden></i> <span class="item-label">{@mini.errors}</span></span>
								<span# IF C_LOGGED_ERRORS # class="warning blink"# ENDIF #>{ERRORS_NB}</span>
							</a>
						</li>
						<li>
							<a class="flex-between" href="${relative_url(AdminErrorsUrlBuilder::list_404_errors())}">
								<span><i class="fa fa-fw fa-unlink# IF C_404_ERRORS # warning blink# ENDIF #" aria-hidden></i> <span class="item-label">{@mini.404}</span></span>
								<span# IF C_404_ERRORS # class="warning blink"# ENDIF #>{404_NB}</span>
							</a>
						</li>
						<li>
							<a href="{PATH_TO_ROOT}/database/admin_database.php">
								<i class="fa fa-fw fa-database" aria-hidden></i> <span class="item-label">{@mini.database}</span>
							</a>
						</li>
						<li>
							<a class="flex-between" href="${relative_url(UserUrlBuilder::comments())}">
								<span><i class="fa fa-fw fa-comments" aria-hidden></i> <span class="item-label">{@mini.coms}</span></span>
								<span>{COMMENTS_NB}</span>
							</a>
						</li>
					</ul>
				</li>
				<li id="mini-sandbox-custom">
					<span><i class="fa fa-fw fa-cogs moderator"></i> <span class="item-label">{@mini.personalization}</span></span>
					<ul>
						<li>
							<a href="{PATH_TO_ROOT}/admin/menus/menus.php">
								<i class="fa fa-fw fa-bars" aria-hidden></i> {@mini.menus} <span class="smaller">{@title.config} /  {@mini.add}</span>
							</a>
						</li>
						<li>
							# IF C_LEFT_ENABLED #
								# INCLUDE DISABLE_LEFT_COL #
							# ELSE #
								# INCLUDE ENABLE_LEFT_COL #
							# ENDIF #
						</li>
						<li>
							# IF C_RIGHT_ENABLED #
								# INCLUDE DISABLE_RIGHT_COL #
							# ELSE #
								# INCLUDE ENABLE_RIGHT_COL #
							# ENDIF #
						</li>
						<li>
							<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}">
								<i class="far fa-fw fa-image" aria-hidden></i> <span class="item-label">{@mini.theme} <span class="smaller">{@mini.manage}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminThemeUrlBuilder::add_theme())}">
					 			<i class="far fa-fw fa-image" aria-hidden></i> <span class="item-label">{@mini.theme} <span class="smaller">{@mini.add}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}">
								<i class="fa fa-fw fa-cubes" aria-hidden></i> <span class="item-label">{@mini.mod} <span class="smaller">{@mini.manage}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminModulesUrlBuilder::add_module())}">
								<i class="fa fa-fw fa-cubes" aria-hidden></i> <span class="item-label">{@mini.mod} <span class="smaller">{@mini.add}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminMembersUrlBuilder::management())}">
								<i class="far fa-fw fa-user" aria-hidden></i> <span class="item-label">{@mini.user} <span class="smaller">{@mini.manage}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminMembersUrlBuilder::add())}">
								<i class="far fa-fw fa-user" aria-hidden></i> <span class="item-label">{@mini.user} <span class="smaller">{@mini.add}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminConfigUrlBuilder::general_config())}">
								<i class="fa fa-fw fa-university" aria-hidden></i> <span class="item-label">{@title.config} <span class="smaller">{@mini.general.config}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminConfigUrlBuilder::advanced_config())}">
								<i class="fa fa-fw fa-university" aria-hidden></i> <span class="item-label">{@title.config} <span class="smaller">{@mini.advanced.config}</span></span>
							</a>
						</li>
					</ul>
				</li>
				<li id="mini-sandbox-framework">
					<span><i class="fa iboost fa-iboost-phpboost fa-fw visitor"></i> <span class="item-label">{@mini.fwkboost}</span></span>
					<div><em class="small">#EffDoublevéKaBouste</em></div>
					<ul>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::form())}">
								<i class="far fa-square fa-fw" aria-hidden></i>
								<span class="item-label">{@title.form.builder}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::css())}">
								<i class="fab fa-css3 fa-fw" aria-hidden></i>
								<span class="item-label">{@title.css}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::bbcode())}">
								<i class="fa fa-code fa-fw" aria-hidden></i>
								<span class="item-label">{@title.bbcode}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::multitabs())}">
								<i class="fa fa-list fa-fw" aria-hidden></i>
								<span class="item-label">{@title.multitabs}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::plugins())}">
								<i class="fa fa-cube fa-fw" aria-hidden></i>
								<span class="item-label">{@title.plugins}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::menu())}">
								<i class="fa fa-bars fa-fw" aria-hidden></i>
								<span class="item-label">{@title.menu}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::table())}">
								<i class="fa fa-table fa-fw" aria-hidden></i>
								<span class="item-label">{@title.table.builder}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::icons())}">
								<i class="fa fa-bars fa-fw" aria-hidden></i>
								<span class="item-label">{@title.icons}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::icoboost())}">
								<i class="fa iboost fa-iboost-phpboost fa-fw" aria-hidden></i>
								<span class="item-label">icoboost</span>
							</a>
						</li>
					</ul>
				</li>
				<li id="mini-sandbox-switcher">
					<span><i class="far fa-image fa-fw alt-button"></i> <span class="item-label">{@mini.themes.switcher}</span></span>
					<ul>
						<li>
							<a href="?switchtheme={DEFAULT_THEME}">
								<i class="fa fa-sync-alt" aria-hidden></i> {@mini.default.theme}
							</a>
						</li>
						<li>
							<form class="sandbox-mini-form switchtheme" action="{REWRITED_SCRIPT}" method="get">
								<select id="switchtheme" name="switchtheme" onchange="document.location='?switchtheme='+this.options[this.selectedIndex].value;">
									# START themes #
										<option value="{themes.IDNAME}"# IF themes.C_SELECTED# selected="selected"# ENDIF #>{themes.NAME}</option>
									# END themes #
								</select>
							</form>
						</li>
					</ul>
				</li>
				<li id="mini-sandbox-config">
					<a class="flex-between" href="${relative_url(SandboxUrlBuilder::config())}">
						<span></span>
						<span><i class="fa fa-fw fa-hard-hat fa-lg warning"></i></span>
					</a>
				</li>
			</ul>
		</nav>
	</div>
	<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>
	<script>
	    $('#pushmenu-fwkboost').pushmenu({
			maxWidth: false,
			customToggle: jQuery('.toggle-fwkboost'), // null
			navTitle: '{@sandbox.module.title}', // null
			pushContent: '#push-container',
			position: # IF C_SLIDE_TOP #'right'# ENDIF ## IF C_SLIDE_RIGHT #'right'# ENDIF ## IF C_SLIDE_BOTTOM #'bottom'# ENDIF ## IF C_SLIDE_LEFT #'left'# ENDIF #, // left, right, top, bottom
			levelOpen: 'overlap', // 'overlap', 'expand', false
			levelTitles: true, // overlap only
			levelSpacing: 40, // px - overlap only
			navClass: 'fwkboost-mini-sandbox',
			disableBody: true,
			closeOnClick: true, // if disableBody is true
			insertClose: true,
			labelClose: ${escapejs(LangLoader::get_message('close', 'main'))},
			insertBack: true,
			labelBack: ${escapejs(LangLoader::get_message('back', 'main'))}
	    });
	</script>
	<script src="{PATH_TO_ROOT}/templates/default/plugins/form/validator.js"></script>
	<script src="{PATH_TO_ROOT}/templates/default/plugins/form/form.js"></script>
# ENDIF #
