# IF C_IS_SUPERADMIN #
	<div id="module-mini-sandbox" class="mini-sandbox">
		<a class="fwkboost-toggle pushmenu-toggle bgc-full administrator" data-tooltip="{@sandbox.module.title}">
			<i class="fa fa-screwdriver-wrench" aria-hidden="true"></i>
			<span class="sr-only">{@sandbox.module.title}</span>
		</a>
		<nav id="pushmenu-fwkboost" class="pushnav">
			<ul>
				<li class="mini-sandbox-tools has-sub">
					<span class="mini-toolbox flex-between">
						<span><i class="fa fa-toolbox fa-fw error"></i> <span>{@sandbox.mini.tools}</span></span>
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
						# IF C_HISTORY #
							<li>
								<a href="{PATH_TO_ROOT}/history/">
									<span><i class="fa fa-fw fa-clock-rotate-left" aria-hidden="true"></i> <span>{@sandbox.mini.history}</span></span>
								</a>
							</li>
						# ENDIF #
						<li>
							<a class="flex-between" href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}">
								<span><i class="fa fa-fw fa-terminal# IF C_LOGGED_ERRORS # warning blink# ENDIF #" aria-hidden="true"></i> <span>{@sandbox.mini.errors}</span></span>
								<span# IF C_LOGGED_ERRORS # class="warning blink"# ENDIF #>{ERRORS_NB}</span>
							</a>
						</li>
						<li>
							<a class="flex-between" href="${relative_url(AdminErrorsUrlBuilder::list_404_errors())}">
								<span><i class="fa fa-fw fa-unlink# IF C_404_ERRORS # warning blink# ENDIF #" aria-hidden="true"></i> <span>{@sandbox.mini.404}</span></span>
								<span# IF C_404_ERRORS # class="warning blink"# ENDIF #>{404_NB}</span>
							</a>
						</li>
						# IF C_DATABASE #
							<li>
								<a href="{PATH_TO_ROOT}/database/admin_database.php">
									<i class="fa fa-fw fa-database" aria-hidden="true"></i> <span>{@sandbox.mini.database}</span>
								</a>
							</li>
						# ENDIF #
						# IF C_REVIEW #
							<li>
								<a href="{PATH_TO_ROOT}/review/index.php?url=/home/">
									<i class="fa fa-fw fa-magnifying-glass-location" aria-hidden="true"></i> <span>{@sandbox.mini.review}</span>
								</a>
							</li>
						# ENDIF #
						<li>
							<a class="flex-between" href="${relative_url(UserUrlBuilder::comments())}">
								<span><i class="fa fa-fw fa-comments" aria-hidden="true"></i> <span>{@sandbox.mini.coms}</span></span>
								<span>{COMMENTS_NB}</span>
							</a>
						</li>
					</ul>
				</li>
				<li class="mini-sandbox-custom has-sub">
					<span><i class="fa fa-fw fa-cogs moderator"></i> <span>{@sandbox.mini.personalization}</span></span>
					<ul>
						<li>
							<a href="{PATH_TO_ROOT}/admin/menus/menus.php">
								<span class="stacked">
									<i class="fa fa-fw fa-bars" aria-hidden="true"></i>
									<i class="fa fa-cog stack-event stack-top-right notice" aria-hidden="true"></i>
								</span> <span>{@sandbox.mini.menus} <span class="smaller">{@form.configuration} /  {@sandbox.mini.add}</span></span>
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
									<i class="fa fa-cog stack-event stack-top-right notice" aria-hidden="true"></i>
									<i class="far fa-fw fa-image" aria-hidden="true"></i>
								</span> <span>{@sandbox.mini.theme} <span class="smaller">{@sandbox.mini.manage}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminThemeUrlBuilder::add_theme())}">
								<span class="stacked">
									<i class="far fa-fw fa-image" aria-hidden="true"></i>
									<i class="fa fa-plus stack-event stack-top-right success" aria-hidden="true"></i>
								</span> <span>{@sandbox.mini.theme} <span class="smaller">{@sandbox.mini.add}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}">
								<span class="stacked">
									<i class="fa fa-fw fa-cubes" aria-hidden="true"></i>
									<i class="fa fa-cog stack-event stack-top-right notice" aria-hidden="true"></i>
								</span> <span>{@sandbox.mini.mod} <span class="smaller">{@sandbox.mini.manage}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminModulesUrlBuilder::add_module())}">
								<span class="stacked">
									<i class="fa fa-fw fa-cubes" aria-hidden="true"></i>
									<i class="fa fa-plus stack-event stack-top-right success" aria-hidden="true"></i>
								</span> <span>{@sandbox.mini.mod} <span class="smaller">{@sandbox.mini.add}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminMembersUrlBuilder::management())}">
								<span class="stacked">
									<i class="far fa-fw fa-user" aria-hidden="true"></i>
									<i class="fa fa-cog stack-event stack-top-right notice" aria-hidden="true"></i>
								</span> <span>{@sandbox.mini.user} <span class="smaller">{@sandbox.mini.manage}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminMembersUrlBuilder::add())}">
								<span class="stacked">
									<i class="far fa-fw fa-user" aria-hidden="true"></i>
									<i class="fa fa-plus stack-event stack-top-right success" aria-hidden="true"></i>
								</span> <span>{@sandbox.mini.user} <span class="smaller">{@sandbox.mini.add}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminConfigUrlBuilder::general_config())}">
								<span class="stacked">
									<i class="fa fa-fw fa-university" aria-hidden="true"></i>
									<i class="fa fa-cog stack-event stack-top-right notice" aria-hidden="true"></i>
								</span> <span>{@form.configuration} <span class="smaller">{@sandbox.mini.general.config}</span></span>
							</a>
						</li>
						<li>
							<a href="${relative_url(AdminConfigUrlBuilder::advanced_config())}">
								<span class="stacked">
									<i class="fa fa-fw fa-university" aria-hidden="true"></i>
									<i class="fa fa-plus stack-event stack-top-right success" aria-hidden="true"></i>
								</span> <span>{@form.configuration} <span class="smaller">{@sandbox.mini.advanced.config}</span></span>
							</a>
						</li>
					</ul>
				</li>
				<li class="mini-sandbox-fwkboost has-sub">
					<a href="${relative_url(SandboxUrlBuilder::home())}"><i class="fa iboost fa-iboost-phpboost fa-fw visitor"></i> <span>{@sandbox.mini.fwkboost}</span></a>
					<ul>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::builder())}">
								<i class="far fa-square fa-fw" aria-hidden="true"></i>
								<span>{@sandbox.forms}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::component())}">
								<i class="fab fa-css3 fa-fw" aria-hidden="true"></i>
								<span>{@sandbox.components}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::layout())}">
								<i class="fab fa-html5 fa-fw" aria-hidden="true"></i>
								<span>{@sandbox.layout}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(SandboxUrlBuilder::bbcode())}">
								<i class="fa fa-code fa-fw" aria-hidden="true"></i>
								<span>{@sandbox.bbcode}</span>
							</a>
						</li>
						<li class="has-sub">
							<span>
								<i class="fa fa-bars fa-fw" aria-hidden="true"></i>
								<span>{@sandbox.menus}</span>
							</span>
							<ul>
								<li>
									<a href="${relative_url(SandboxUrlBuilder::menus_nav())}">
										<i class="fa fa-bars fa-fw" aria-hidden="true"></i>
										<span>{@sandbox.menus.nav}</span>
									</a>
								</li>
								<li>
									<a href="${relative_url(SandboxUrlBuilder::menus_content())}">
										<i class="fa fa-list fa-fw" aria-hidden="true"></i>
										<span>{@sandbox.menus.content}</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="has-sub">
							<span>
								<i class="fa fa-terminal fa-fw" aria-hidden="true"></i>
								<span>{@sandbox.php}</span>
							</span>
							<ul>
								<li>
									<a href="${relative_url(SandboxUrlBuilder::lang())}">
										<i class="fa fa-language fa-fw" aria-hidden="true"></i>
										<span>{@sandbox.lang.title}</span>
									</a>
								</li>
								<li>
									<a href="${relative_url(SandboxUrlBuilder::table())}">
										<i class="fa fa-table fa-fw" aria-hidden="true"></i>
										<span>{@sandbox.table}</span>
									</a>
								</li>
								<li>
									<a href="${relative_url(SandboxUrlBuilder::email())}">
										<i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i>
										<span>{@sandbox.email}</span>
									</a>
								</li>
								<li>
									<a href="${relative_url(SandboxUrlBuilder::template())}">
										<i class="fa fa-terminal fa-fw" aria-hidden="true"></i>
										<span>{@sandbox.template}</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="has-sub">
							<span>
								<i class="fa fa-cog fa-fw" aria-hidden="true"></i>
								<span>{@sandbox.admin.render}</span>
							</span>
							<ul>
								<li>
									<a href="${relative_url(SandboxUrlBuilder::admin_builder())}">
										<i class="far fa-square fa-fw" aria-hidden="true"></i>
										<span>{@sandbox.forms}</span>
									</a>
								</li>
								<li>
									<a href="${relative_url(SandboxUrlBuilder::admin_component())}">
										<i class="fab fa-html5 fa-fw" aria-hidden="true"></i>
										<span>{@sandbox.components}</span>
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li class="mini-sandbox-switcher has-sub">
					<span><i class="far fa-image fa-fw link-color-alt"></i> <span>{@sandbox.mini.themes.switcher}</span></span>
					<ul>
						<li>
							<a href="?switchtheme={DEFAULT_THEME}">
								<i class="fa fa-sync-alt" aria-hidden="true"></i> <span>{@sandbox.mini.default.theme}</span>
							</a>
						</li>
						<li>
							<form class="sandbox-mini-form switchtheme grouped-inputs inputs-with-sup" action="{REWRITED_SCRIPT}" method="get">
								<label class="label-sup grouped-element" for="sandbox-switchtheme">
									<span>{@sandbox.mini.themes.switcher}</span>
									<select id="sandbox-switchtheme" name="sandbox-switchtheme-select" onchange="document.location='?switchtheme='+this.options[this.selectedIndex].value;">
										# START themes #
											<option value="{themes.IDNAME}"# IF themes.C_SELECTED# selected="selected"# ENDIF #>{themes.NAME}</option>
										# END themes #
									</select>
								</label>
							</form>
						</li>
					</ul>
				</li>
				<li class="mini-sandbox-infos has-sub">
					<span><i class="fa fa-info fa-fw notice"></i> <span>{@sandbox.mini.infos}</span></span>
					<ul>
						<li>
							<div class="flex-between">
								<span class="smaller">{@sandbox.mini.version.pbt}</span>
								<span>{PBT_VERSION}</span>
							</div>
						</li>
						<li>
							<div class="flex-between">
								<span class="smaller">{@sandbox.mini.version.php}</span>
								<span>{PHP_VERSION}</span>
							</div>
						</li>
						<li>
							<div class="flex-between">
								<span class="smaller">{@sandbox.mini.version.date}</span>
								<span>{INSTALL_DATE}</span>
							</div>
						</li>
						<li>
							<div class="flex-between">
								<span class="smaller">{@sandbox.mini.version.sql}</span>
								<span class="align-right">{DBMS_VERSION}</span>
							</div>
						</li>
						<li>
							<div class="flex-between">
								<div class="align-center" role="contentinfo" aria-label="{@sandbox.mini.viewport.h}">
									<span class="stacked">
										<i class="fa fa-tv" aria-hidden="true"></i>
										<i class="fa fa-arrows-alt-h stack-event stack-top-right" aria-hidden="true"></i>
									</span>
									<p id="window-width"></p>
								</div>
								<div class="align-center">
									<span class="stacked" role="contentinfo" aria-label="{@sandbox.mini.viewport.v}">
										<i class="fa fa-tv" aria-hidden="true"></i>
										<i class="fa fa-arrows-alt-v stack-event stack-top-right" aria-hidden="true"></i>
									</span>
									<p id="window-height"></p>
								</div>
							</div>
						</li>
					</ul>
				</li>
				<li class="mini-sandbox-admin-menu has-sub">
					<span><i class="fa fa-university fa-fw administrator"></i> <span>{@sandbox.mini.admin.menu}</span></span>
					<ul>
						<li>
							<a href="${relative_url(UserUrlBuilder::administration())}#openmodal-administration">
								<i class="fa fa-fw fa-cog" aria-hidden="true"></i>
								<span>{@sandbox.mini.admin.menu.administration}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(UserUrlBuilder::administration())}#openmodal-tools">
								<i class="fa fa-fw fa-wrench" aria-hidden="true"></i>
								<span>{@sandbox.mini.admin.menu.tools}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(UserUrlBuilder::administration())}#openmodal-users">
								<i class="fa fa-fw fa-user" aria-hidden="true"></i>
								<span>{@sandbox.mini.admin.menu.users}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(UserUrlBuilder::administration())}#openmodal-content">
								<i class="far fa-fw fa-square" aria-hidden="true"></i>
								<span>{@sandbox.mini.admin.menu.content}</span>
							</a>
						</li>
						<li>
							<a href="${relative_url(UserUrlBuilder::administration())}#openmodal-modules">
								<i class="fa fa-fw fa-cubes" aria-hidden="true"></i>
								<span>{@sandbox.mini.admin.menu.modules}</span>
							</a>
						</li>
					</ul>
				</li>
				<li class="reload-nav-cache">
					<a href="#" onclick="location.reload(true);return false">
						<i class="fa fa-fw fa-arrows-rotate" aria-hidden="true"></i>
						<span>{@sandbox.refrech.nav.cache}</span>
					</a>
				</li>
			</ul>
			<ul class="sandbox-controls bottom-nav">
				<li>
					<a href="${relative_url(SandboxUrlBuilder::home())}" aria-label="{@sandbox.mini.home}">
						<span class="stacked">
							<i class="fa fa-fw fa-hard-hat" aria-hidden="true"></i>
							<i class="fa fa-home stack-event stack-top-right notice" aria-hidden="true"></i>
						</span>
					</a>
				</li>
				<li class="align-right">
					<a href="${relative_url(SandboxUrlBuilder::config())}" aria-label="{@sandbox.mini.admin}">
						<span class="stacked">
							<i class="fa fa-fw fa-hard-hat" aria-hidden="true"></i>
							<i class="fa fa-cog stack-event stack-sup stack-left notice" aria-hidden="true"></i>
						</span>
					</a>
				</li>
			</ul>
		</nav>
	</div>
	<script>
	    jQuery('#pushmenu-fwkboost').pushmenu({
			width: 291,
			customToggle: jQuery('.fwkboost-toggle'), // null
			navTitle: '{@sandbox.module.title}', // null
			pushContent: '{PUSHED_CONTENT}',
			position: '{OPENING_TYPE}', // left, right, top, bottom
			# IF C_NO_EXPANSION #
				levelOpen: false,
			# ELSE #
				levelOpen: '{EXPANSION_TYPE}', // 'overlap', 'expand'
			# ENDIF #
			levelTitles: true, // overlap only
			levelSpacing: 40, // px - overlap only
			navClass: 'fwkboost-mini-sandbox',
			disableBody: {DISABLED_BODY},
			closeOnClick: true, // if disableBody is true
			insertClose: true,
			labelClose: ${escapejs(@common.close)},
			insertBack: true,
			labelBack: ${escapejs(@common.back)}
		});

		// get window sizes on resize
		jQuery('#window-width').append(jQuery(window).innerWidth() + 'px');
		jQuery('#window-height').append(jQuery(window).innerHeight() + 'px');
		jQuery(window).on('resize', function() {
			jQuery('#window-width').empty();
			jQuery('#window-width').append(jQuery(window).innerWidth() + 'px');
			jQuery('#window-height').empty();
			jQuery('#window-height').append(jQuery(window).innerHeight() + 'px');
		});
	</script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/form/validator# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/form/form# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
# ENDIF #
