<div id="admin-subheader" class="modal-container" role="navigation">
	<nav class="admin-menu">
		<ul>
			<li class="admin-li">
				<a data-modal data-target="openmodal-{L_ADMINISTRATION}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i><span>{L_ADMINISTRATION}</span></a>
			</li>
			<li class="admin-li">
				<a data-modal data-target="openmodal-{L_TOOLS}"><i aria-hidden="true" class="fa fa-fw fa-wrench"></i><span>{L_TOOLS}</span></a>
			</li>
			<li class="admin-li">
				<a data-modal data-target="openmodal-{L_USER}"><i aria-hidden="true" class="fa fa-fw fa-user"></i><span>{L_USER}</span></a>
			</li>
			<li class="admin-li">
				<a data-modal data-target="openmodal-{L_CONTENT}"><i aria-hidden="true" class="far fa-fw fa-square"></i><span>{L_CONTENT}</span></a>
			</li>
			<li class="admin-li">
				<a data-modal data-target="openmodal-{L_MODULES}"><i aria-hidden="true" class="fa fa-fw fa-cube"></i><span>{L_MODULES}</span></a>
			</li>
		</ul>
	</nav>

	<div class="panel-container">
		<div id="openmodal-{L_ADMINISTRATION}" class="modal modal-animation">
			<div class="close-modal" aria-label="${LangLoader::get_message('close_menu', 'admin')}"></div>
			<div class="content-panel">
				<div class="next-menu">
					<a data-modal data-target="openmodal-{L_MODULES}"><i aria-hidden="true" class="fa fa-arrow-left"></i> {L_MODULES}</a>
					<a data-modal data-target="openmodal-{L_TOOLS}">{L_TOOLS} <i aria-hidden="true" class="fa fa-arrow-right"></i></a>
				</div>
				<ul class="modal-menu">
					<li>
						<a href="${relative_url(AdminConfigUrlBuilder::general_config())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{L_CONFIGURATION}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminConfigUrlBuilder::general_config())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{L_CONFIG_GENERAL}</a></li>
							<li><a href="${relative_url(AdminConfigUrlBuilder::advanced_config())}"><i aria-hidden="true" class="fa fa-fw fa-cogs"></i>{L_CONFIG_ADVANCED}</a></li>
							<li><a href="${relative_url(AdminConfigUrlBuilder::mail_config())}"><i aria-hidden="true" class="fa fa-fw fa-envelope"></i>{L_MAIL_CONFIG}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}"><i aria-hidden="true" class="fa fa-fw fa-image"></i>{L_THEMES}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminThemeUrlBuilder::add_theme())}"><i aria-hidden="true" class="fa fa-fw fa-plus"></i>{L_ADD}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}"><i aria-hidden="true" class="fa fa-fw fa-language"></i>{L_LANGS}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminLangsUrlBuilder::install())}"><i aria-hidden="true" class="fa fa-fw fa-plus"></i> {L_ADD}</a></li>
						</ul>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/updates/updates.php"><i aria-hidden="true" class="fa fa-fw fa-download"></i>{L_UPDATES}</a>
						<ul class="level-2">
							<li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=kernel"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{L_KERNEL}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=module"><i aria-hidden="true" class="fa fa-fw fa-cubes"></i>{L_MODULES}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=template"><i aria-hidden="true" class="fa fa-fw fa-image"></i>{L_THEMES}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminMaintainUrlBuilder::maintain())}"><i aria-hidden="true" class="far fa-fw fa-clock"></i>{L_MAINTAIN}</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/admin_alerts.php"><i aria-hidden="true" class="fa fa-fw fa-bell"></i> {L_ADMINISTRATOR_ALERTS}</a>
					</li>
					# IF C_ADMIN_LINKS_2 #
						# START admin_links_2 #
							# INCLUDE admin_links_2.MODULE_MENU #
						# END admin_links_2 #
					# ENDIF #
				</ul>
			</div>
		</div>

		<div id="openmodal-{L_TOOLS}" class="modal modal-animation">
			<div class="close-modal" aria-label="${LangLoader::get_message('close_menu', 'admin')}"></div>
			<div class="content-panel">
				<div class="next-menu">
					<a data-modal data-target="openmodal-{L_ADMINISTRATION}"><i aria-hidden="true" class="fa fa-arrow-left"></i> {L_ADMINISTRATION}</a>
					<a data-modal data-target="openmodal-{L_USER}">{L_USER} <i aria-hidden="true" class="fa fa-arrow-right"></i></a>
				</div>
				<ul class="modal-menu">
					<li>
						<a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}"><i aria-hidden="true" class="fa fa-fw fa-sync"></i>{L_CACHE}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}"><i aria-hidden="true" class="fa fa-fw fa-sync"></i>{L_CACHE}</a></li>
							<li><a href="${relative_url(AdminCacheUrlBuilder::clear_syndication_cache())}"><i aria-hidden="true" class="fa fa-fw fa-rss"></i>{L_SYNDICATION_CACHE}</a></li>
							<li><a href="${relative_url(AdminCacheUrlBuilder::clear_css_cache())}"><i aria-hidden="true" class="fab fa-fw fa-css3"></i>{L_CSS_CACHE_CONFIG}</a></li>
							<li><a href="${relative_url(AdminCacheUrlBuilder::configuration())}"><i aria-hidden="true" class="fa fa-fw fa-cogs"></i>{L_CONFIGURATION}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}"><i aria-hidden="true" class="fa fa-fw fa-exclamation-triangle"></i>{L_ERRORS}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}"><i aria-hidden="true" class="fa fa-fw fa-exclamation-circle"></i>{L_LOGGED_ERRORS}</a></li>
							<li><a href="${relative_url(AdminErrorsUrlBuilder::list_404_errors())}"><i aria-hidden="true" class="fa fa-fw fa-ban"></i>{L_404_ERRORS}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminServerUrlBuilder::system_report())}"><i aria-hidden="true" class="fa fa-fw fa-building"></i>{L_SERVER}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminServerUrlBuilder::phpinfo())}"><i aria-hidden="true" class="fa fa-fw fa-info"></i>{L_PHPINFO}</a></a></li>
							<li><a href="${relative_url(AdminServerUrlBuilder::system_report())}"><i aria-hidden="true" class="fa fa-fw fa-info-circle"></i>{L_SYSTEM_REPORT}</a></li>
						</ul>
					</li>
					# IF C_ADMIN_LINKS_3 #
						# START admin_links_3 #
							# INCLUDE admin_links_3.MODULE_MENU #
						# END admin_links_3 #
					# ENDIF #
				</ul>
			</div>
		</div>

		<div id="openmodal-{L_USER}" class="modal modal-animation">
			<div class="close-modal" aria-label="${LangLoader::get_message('close_menu', 'admin')}"></div>
			<div class="content-panel">
				<div class="next-menu">
					<a data-modal data-target="openmodal-{L_TOOLS}"><i aria-hidden="true" class="fa fa-arrow-left"></i> {L_TOOLS}</a>
					<a data-modal data-target="openmodal-{L_CONTENT}">{L_CONTENT} <i aria-hidden="true" class="fa fa-arrow-right"></i></a>
				</div>
				<ul class="modal-menu">
					<li>
						<a href="${relative_url(AdminMembersUrlBuilder::management())}"><i aria-hidden="true" class="fa fa-fw fa-user"></i>{L_USER}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminMembersUrlBuilder::management())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminMembersUrlBuilder::add())}"><i aria-hidden="true" class="fa fa-fw fa-plus"></i>{L_ADD}</a></li>
							<li><a href="${relative_url(AdminMembersUrlBuilder::configuration())}"><i aria-hidden="true" class="fa fa-fw fa-cogs"></i>{L_CONFIGURATION}</a></li>
							<li><a href="{PATH_TO_ROOT}/user/moderation_panel.php"><i aria-hidden="true" class="fa fa-fw fa-ban"></i>{L_PUNISHEMENT}</a></li>
						</ul>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/admin_groups.php"><i aria-hidden="true" class="fa fa-fw fa-users"></i>{L_GROUP}</a>
						<ul class="level-2">
							<li><a href="{PATH_TO_ROOT}/admin/admin_groups.php"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/admin_groups.php?add=1"><i aria-hidden="true" class="fa fa-fw fa-plus"></i>{L_ADD}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminExtendedFieldsUrlBuilder::fields_list())}"><i aria-hidden="true" class="fa fa-fw fa-list"></i>{L_EXTEND_FIELD}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminExtendedFieldsUrlBuilder::fields_list())}"><i aria-hidden="true" class="fa fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminExtendedFieldsUrlBuilder::add())}"><i aria-hidden="true" class="fa fa-fw fa-plus"></i>{L_ADD}</a></li>
						</ul>
					</li>
					# IF C_ADMIN_LINKS_4 #
						# START admin_links_4 #
							# INCLUDE admin_links_4.MODULE_MENU #
						# END admin_links_4 #
					# ENDIF #
				</ul>
			</div>
		</div>

		<div id="openmodal-{L_CONTENT}" class="modal modal-animation">
			<div class="close-modal" aria-label="${LangLoader::get_message('close_menu', 'admin')}"></div>
			<div class="content-panel">
				<div class="next-menu">
					<a data-modal data-target="openmodal-{L_USER}"><i aria-hidden="true" class="fa fa-arrow-left"></i> {L_USER}</a>
					<a data-modal data-target="openmodal-{L_MODULES}">{L_MODULES} <i aria-hidden="true" class="fa fa-arrow-right"></i></a>
				</div>
				<ul class="modal-menu">
					<li>
						<a href="${relative_url(AdminContentUrlBuilder::content_configuration())}"><i aria-hidden="true" class="far fa-fw fa-square"></i>{L_CONTENT_CONFIG}</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/menus/menus.php"><i aria-hidden="true" class="fa fa-fw fa-list-ul"></i>{L_MENUS}</a>
						<ul class="level-2">
							<li><a href="{PATH_TO_ROOT}/admin/menus/menus.php"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/menus/links.php"><i aria-hidden="true" class="fa fa-fw fa-list-ul"></i>{L_ADD_LINKS_MENU}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/menus/content.php"><i aria-hidden="true" class="far fa-fw fa-file"></i>{L_ADD_CONTENT_MENU}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/menus/feed.php"><i aria-hidden="true" class="fa fa-fw fa-rss"></i>{L_ADD_FEED_MENU}</a></li>
						</ul>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/admin_files.php"><i aria-hidden="true" class="fa fa-fw fa-file-alt"></i>{L_FILES}</a>
						<ul class="level-2">
							<li><a href="{PATH_TO_ROOT}/admin/admin_files.php"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminFilesUrlBuilder::configuration())}"><i aria-hidden="true" class="fa fa-cogs"></i>{L_CONFIGURATION}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(UserUrlBuilder::comments())}"><i aria-hidden="true" class="far fa-fw fa-comment"></i>{L_COMMENTS}</a>
						<ul class="level-2">
							<li><a href="${relative_url(UserUrlBuilder::comments())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/content/?url=/comments/config/"><i aria-hidden="true" class="fa fa-fw fa-cogs"></i>{L_CONFIGURATION}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminSmileysUrlBuilder::management())}"><i aria-hidden="true" class="far fa-fw fa-smile"></i>{L_SMILEY}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminSmileysUrlBuilder::management())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminSmileysUrlBuilder::add())}"><i aria-hidden="true" class="fa fa-fw fa-plus"></i>{L_ADD}</a></li>
						</ul>
					</li>
					# IF C_ADMIN_LINKS_5 #
						# START admin_links_5 #
							# INCLUDE admin_links_5.MODULE_MENU #
						# END admin_links_5 #
					# ENDIF #
				</ul>
			</div>
		</div>

		<div id="openmodal-{L_MODULES}" class="modal modal-animation">
			<div class="close-modal" aria-label="${LangLoader::get_message('close_menu', 'admin')}"></div>
			<div class="content-panel">
				<div class="next-menu">
					<a data-modal data-target="openmodal-{L_CONTENT}"><i aria-hidden="true" class="fa fa-arrow-left"></i> {L_CONTENT}</a>
					<a data-modal data-target="openmodal-{L_ADMINISTRATION}">{L_ADMINISTRATION} <i aria-hidden="true" class="fa fa-arrow-right"></i></a>
				</div>
				<ul class="modal-menu">
					<li>
						<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}"><i aria-hidden="true" class="fa fa-fw fa-cube"></i>{L_MODULES}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminModulesUrlBuilder::add_module())}"><i aria-hidden="true" class="fa fa-fw fa-plus"></i>{L_ADD}</a></li>
							<li><a href="${relative_url(AdminModulesUrlBuilder::update_module())}"><i aria-hidden="true" class="fa fa-fw fa-level-up-alt"></i>{L_UPDATES}</a></li>
						</ul>
					</li>
					# IF C_ADMIN_LINKS_6 #
						# START admin_links_6 #
							# INCLUDE admin_links_6.MODULE_MENU #
						# END admin_links_6 #
					# ENDIF #
				</ul>
			</div>
		</div>
	</div>

</div>
