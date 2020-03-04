# IF C_IS_SUPERADMIN #
	<div id="module-mini-sandbox" class="mini-sandbox">
		<a class="fwkboost-toggle pushmenu-toggle bgc-full administrator">
			<i class="fa fa-wrench" aria-hidden="true"></i> <span>{@sandbox.module.title}</span>
		</a>
		<nav id="pushmenu-fwkboost" class="pushnav">
			<ul>
				<li class="mini-sandbox-tools">
					<span class="mini-toolbox flex-between">
						<span><i class="fa fa-toolbox fa-fw error"></i> <span>{@mini.tools}</span></span>
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
								<span><i class="fa fa-fw fa-terminal# IF C_LOGGED_ERRORS # warning blink# ENDIF #" aria-hidden="true"></i> <span>{@mini.errors}</span></span>
								<span# IF C_LOGGED_ERRORS # class="warning blink"# ENDIF #>{ERRORS_NB}</span>
							</a>
						</li>
						<li>
							<a class="flex-between" href="${relative_url(AdminErrorsUrlBuilder::list_404_errors())}">
								<span><i class="fa fa-fw fa-unlink# IF C_404_ERRORS # warning blink# ENDIF #" aria-hidden="true"></i> <span>{@mini.404}</span></span>
								<span# IF C_404_ERRORS # class="warning blink"# ENDIF #>{404_NB}</span>
							</a>
						</li>
						<li>
							<a href="{PATH_TO_ROOT}/database/admin_database.php">
								<i class="fa fa-fw fa-database" aria-hidden="true"></i> <span>{@mini.database}</span>
							</a>
						</li>
						<li>
							<a class="flex-between" href="${relative_url(UserUrlBuilder::comments())}">
								<span><i class="fa fa-fw fa-comments" aria-hidden="true"></i> <span>{@mini.coms}</span></span>
								<span>{COMMENTS_NB}</span>
							</a>
						</li>
					</ul>
				</li>
				<li class="mini-sandbox-custom">
					<span><i class="fa fa-fw fa-cogs moderator"></i> <span>{@mini.personalization}</span></span>
					<ul>
						<li>
							<a href="{PATH_TO_ROOT}/admin/menus/menus.php">
								<span class="stacked">
									<i class="fa fa-fw fa-bars" aria-hidden="true"></i>
									<i class="fa fa-cog stack-event stack-sup stack-right notice" aria-hidden="true"></i>
								</span> <span>{@mini.menus} <span class="smaller">{@title.config} /  {@mini.add}</span></span>
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
								<span class="stacked">
									<i class="fa fa-cog stack-event stack-sup stack-right notice" aria-hidden="true"></i>
									<i class="far fa-fw fa-image" aria-hidden="true"></i>
								</span> <span>{@mini.theme} <span class="smaller">{@mini.manage}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminThemeUrlBuilder::add_theme())}">
								<span class="stacked">
									<i class="far fa-fw fa-image" aria-hidden="true"></i>
									<i class="fa fa-plus stack-event stack-sup stack-right success" aria-hidden="true"></i>
								</span> <span>{@mini.theme} <span class="smaller">{@mini.add}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}">
								<span class="stacked">
									<i class="fa fa-fw fa-cubes" aria-hidden="true"></i>
									<i class="fa fa-cog stack-event stack-sup stack-right notice" aria-hidden="true"></i>
								</span> <span>{@mini.mod} <span class="smaller">{@mini.manage}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminModulesUrlBuilder::add_module())}">
								<span class="stacked">
									<i class="fa fa-fw fa-cubes" aria-hidden="true"></i>
									<i class="fa fa-plus stack-event stack-sup stack-right success" aria-hidden="true"></i>
								</span> <span>{@mini.mod} <span class="smaller">{@mini.add}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminMembersUrlBuilder::management())}">
								<span class="stacked">
									<i class="far fa-fw fa-user" aria-hidden="true"></i>
									<i class="fa fa-cog stack-event stack-sup stack-right notice" aria-hidden="true"></i>
								</span> <span>{@mini.user} <span class="smaller">{@mini.manage}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminMembersUrlBuilder::add())}">
								<span class="stacked">
									<i class="far fa-fw fa-image" aria-hidden="true"></i>
									<i class="fa fa-plus stack-event stack-sup stack-right success" aria-hidden="true"></i>
								</span> <span>{@mini.user} <span class="smaller">{@mini.add}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminConfigUrlBuilder::general_config())}">
								<span class="stacked">
									<i class="fa fa-fw fa-university" aria-hidden="true"></i>
									<i class="fa fa-cog stack-event stack-sup stack-right notice" aria-hidden="true"></i>
								</span> <span>{@title.config} <span class="smaller">{@mini.general.config}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminConfigUrlBuilder::advanced_config())}">
								<span class="stacked">
									<i class="fa fa-fw fa-university" aria-hidden="true"></i>
									<i class="fa fa-plus stack-event stack-sup stack-right success" aria-hidden="true"></i>
								</span> <span>{@title.config} <span class="smaller">{@mini.advanced.config}</span></span>
							</a>
						</li>
					</ul>
				</li>
				<li class="mini-sandbox-fwkboost">
					<span><i class="fa iboost fa-iboost-phpboost fa-fw visitor"></i> <span>{@mini.fwkboost}</span></span>
					<ul>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::home())}">
								<i class="fa fa-hard-hat fa-fw" aria-hidden="true"></i>
								<span>{@mini.home}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::builder())}">
								<i class="far fa-square fa-fw" aria-hidden="true"></i>
								<span>{@title.builder}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::component())}">
								<i class="fab fa-css3 fa-fw" aria-hidden="true"></i>
								<span>{@title.component}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::layout())}">
								<i class="fab fa-css3 fa-fw" aria-hidden="true"></i>
								<span>{@title.layout}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::bbcode())}">
								<i class="fa fa-code fa-fw" aria-hidden="true"></i>
								<span>{@title.bbcode}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::multitabs())}">
								<i class="fa fa-list fa-fw" aria-hidden="true"></i>
								<span>{@title.multitabs}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::plugins())}">
								<i class="fa fa-cube fa-fw" aria-hidden="true"></i>
								<span>{@title.plugins}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::menus())}">
								<i class="fa fa-bars fa-fw" aria-hidden="true"></i>
								<span>{@title.menu}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::table())}">
								<i class="fa fa-table fa-fw" aria-hidden="true"></i>
								<span>{@title.table}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::icons())}">
								<i class="fa iboost fa-iboost-phpboost fa-fw" aria-hidden="true"></i>
								<span>{@title.icons}</span>
							</a>
						</li>
					</ul>
				</li>
				<li class="mini-sandbox-switcher">
					<span><i class="far fa-image fa-fw link-color-alt"></i> <span>{@mini.themes.switcher}</span></span>
					<ul>
						<li>
							<a href="?switchtheme={DEFAULT_THEME}">
								<i class="fa fa-sync-alt" aria-hidden="true"></i> <span>{@mini.default.theme}</span>
							</a>
						</li>
						<li>
							<form class="sandbox-mini-form switchtheme" action="{REWRITED_SCRIPT}" method="get">
								<label for="sandbox-switchtheme">{@mini.themes.switcher}</label>
								<select id="sandbox-switchtheme" name="sandbox-switchtheme-select" onchange="document.location='?switchtheme='+this.options[this.selectedIndex].value;">
									# START themes #
										<option value="{themes.IDNAME}"# IF themes.C_SELECTED# selected="selected"# ENDIF #>{themes.NAME}</option>
									# END themes #
								</select>
							</form>
						</li>
					</ul>
				</li>
				<li class="mini-sandbox-infos">
					<span><i class="fa fa-info fa-fw notice"></i> <span>{@mini.infos}</span></span>
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
								<div class="align-center" role="contentinfo" aria-label="{@mini.viewport.h}">
									<span class="stacked">
										<i class="fa fa-tv" aria-hidden="true"></i>
										<i class="fa fa-arrows-alt-h stack-event stack-sup stack-right" aria-hidden="true"></i>
									</span>
									<p id="window-width"></p>
								</div>
								<div class="align-center">
									<span class="stacked" role="contentinfo" aria-label="{@mini.viewport.v}">
										<i class="fa fa-tv" aria-hidden="true"></i>
										<i class="fa fa-arrows-alt-v stack-event stack-sup stack-right" aria-hidden="true"></i>
									</span>
									<p id="window-height"></p>
								</div>
							</div>
						</li>
					</ul>
				</li>
			</ul>
			<ul class="sandbox-controls bottom-nav">
				<li>
					<a href="${relative_url(SandboxUrlBuilder::home())}" aria-label="">
						<span class="stacked">
							<i class="fa fa-fw fa-hard-hat" aria-hidden="true"></i>
							<i class="fa fa-home stack-event stack-sup stack-right notice" aria-hidden="true"></i>
						</span>
					</a>
				</li>
				<li class="align-right">
					<a href="${relative_url(SandboxUrlBuilder::config())}" aria-label="">
						<span class="stacked">
							<i class="fa fa-fw fa-hard-hat" aria-hidden="true"></i>
							<i class="fa fa-cog stack-event stack-sup stack-left notice" aria-hidden="true"></i>
						</span>
					</a>
				</li>
			</ul>
		</nav>
	</div>
	<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>
	<script>
	    $('#pushmenu-fwkboost').pushmenu({
			maxWidth: false,
			customToggle: jQuery('.fwkboost-toggle'), // null
			navTitle: '{@sandbox.module.title}', // null
			pushContent: '{PUSHED_CONTENT}',
			position: '{OPENING_TYPE}', // left, right, top, bottom
			# IF C_NO_EXPANSION #
				levelOpen: false,
			# ELSE #
				levelOpen: '{EXPANSION_TYPE}', // 'overlap', 'expand', false
			# ENDIF #
			levelTitles: true, // overlap only
			levelSpacing: 40, // px - overlap only
			navClass: 'fwkboost-mini-sandbox',
			disableBody: {DISABLED_BODY},
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
