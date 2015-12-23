<nav class="cssmenu-admin">
	<ul>
		<li class="admin-li">
			<a href="#openmodal-{L_ADMINISTRATION}" title="{L_ADMINISTRATION}"><i class="fa fa-fw fa-cog"></i><span>{L_ADMINISTRATION}</span></a>
			<div id="openmodal-{L_ADMINISTRATION}" class="cssmenu-modal">
				<a href="#closemodal" title="${LangLoader::get_message('close_menu', 'admin')}" class="close"><span>x</span></a>
				<div class="next-menu">
					<a class="float-left" href="#openmodule-{L_MODULES}" title="{L_MODULES}">
						<i class="fa fa-arrow-left"></i>
						{L_MODULES}
					</a>
					<a class="float-right" href="#openmodal-{L_TOOLS}" title="{L_TOOLS}">
						{L_TOOLS}
						<i class="fa fa-arrow-right"></i>
					</a>
				</div>
				<ul class="submenu">
					<li>
						<a href="${relative_url(AdminConfigUrlBuilder::general_config())}" title="{L_CONFIGURATION}"><i class="fa fa-fw fa-cog"></i>{L_CONFIGURATION}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminConfigUrlBuilder::general_config())}" title="{L_CONFIG_GENERAL}"><i class="fa fa-fw fa-cog"></i>{L_CONFIG_GENERAL}</a></li>
							<li><a href="${relative_url(AdminConfigUrlBuilder::advanced_config())}" title="{L_CONFIG_ADVANCED}"><i class="fa fa-fw fa-cogs"></i>{L_CONFIG_ADVANCED}</a></li>
							<li><a href="${relative_url(AdminConfigUrlBuilder::mail_config())}" title="{L_MAIL_CONFIG}"><i class="fa fa-fw fa-envelope-o"></i>{L_MAIL_CONFIG}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}" title="{L_THEMES}"><i class="fa fa-fw fa-picture-o"></i>{L_THEMES}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}" title="{L_MANAGEMENT}"><i class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminThemeUrlBuilder::add_theme())}" title="{L_ADD}"><i class="fa fa-fw fa-plus"></i>{L_ADD}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}" title="{L_LANGS}"><i class="fa fa-fw fa-language"></i>{L_LANGS}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}" title="{L_MANAGEMENT}"><i class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminLangsUrlBuilder::install())}" title="{L_ADD}"><i class="fa fa-fw fa-plus"></i> {L_ADD}</a></li>
						</ul>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/updates/updates.php" title="{L_UPDATES}"><i class="fa fa-fw fa-download"></i>{L_UPDATES}</a>
						<ul class="level-2">
							<li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=kernel" title="{L_KERNEL}"><i class="fa fa-fw fa-cog"></i>{L_KERNEL}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=module" title="{L_MODULES}"><i class="fa fa-fw fa-cubes"></i>{L_MODULES}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=template" title="{L_THEMES}"><i class="fa fa-fw fa-picture-o"></i>{L_THEMES}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminMaintainUrlBuilder::maintain())}" title="{L_MAINTAIN}"><i class="fa fa-fw fa-clock-o"></i>{L_MAINTAIN}</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/admin_alerts.php" title="{L_ADMINISTRATOR_ALERTS}"><i class="fa fa-fw fa-bell"></i> {L_ADMINISTRATOR_ALERTS}</a>
					</li>
					# IF C_ADMIN_LINKS_2 #
						# START admin_links_2 #
							# INCLUDE admin_links_2.MODULE_MENU #
						# END admin_links_2 #
					# ENDIF #
				</ul>
			</div>
			
		</li>
		<li class="admin-li">
			<a href="#openmodal-{L_TOOLS}" title="{L_TOOLS}"><i class="fa fa-fw fa-wrench"></i><span>{L_TOOLS}</span></a>
			<div id="openmodal-{L_TOOLS}" class="cssmenu-modal">
				<a href="#closemodal" title="${LangLoader::get_message('close_menu', 'admin')}" class="close"><span>x</span></a>
				<div class="next-menu">
					<a class="float-left" href="#openmodal-{L_ADMINISTRATION}" title="{L_ADMINISTRATION}">
						<i class="fa fa-arrow-left"></i>
						{L_ADMINISTRATION}
					</a>
					<a class="float-right" href="#openmodal-{L_USER}" title="{L_USER}">
						{L_USER}
						<i class="fa fa-arrow-right"></i>
					</a>
				</div>
				<ul class="submenu">
					<li>
						<a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}" title="{L_CACHE}"><i class="fa fa-fw fa-refresh"></i>{L_CACHE}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}" title="{L_CACHE}"><i class="fa fa-fw fa-refresh"></i>{L_CACHE}</a></li>
							<li><a href="${relative_url(AdminCacheUrlBuilder::clear_syndication_cache())}" title="{L_SYNDICATION_CACHE}"><i class="fa fa-fw fa-rss"></i>{L_SYNDICATION_CACHE}</a></li>
							<li><a href="${relative_url(AdminCacheUrlBuilder::clear_css_cache())}" title="{L_CSS_CACHE_CONFIG}"><i class="fa fa-fw fa-css3"></i>{L_CSS_CACHE_CONFIG}</a></li>
							<li><a href="${relative_url(AdminCacheUrlBuilder::configuration())}" title="{L_CONFIGURATION}"><i class="fa fa-fw fa-cogs"></i>{L_CONFIGURATION}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}" title="{L_ERRORS}"><i class="fa fa-fw fa-exclamation-triangle"></i>{L_ERRORS}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}" title="{L_LOGGED_ERRORS}"><i class="fa fa-fw fa-exclamation-circle"></i>{L_LOGGED_ERRORS}</a></li>
							<li><a href="${relative_url(AdminErrorsUrlBuilder::list_404_errors())}" title="{L_404_ERRORS}"><i class="fa fa-fw fa-ban"></i>{L_404_ERRORS}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminServerUrlBuilder::system_report())}" title="{L_SERVER}"><i class="fa fa-fw fa-building"></i>{L_SERVER}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminServerUrlBuilder::phpinfo())}" title="{L_PHPINFO}"><i class="fa fa-fw fa-info"></i>{L_PHPINFO}</a></a></li>
							<li><a href="${relative_url(AdminServerUrlBuilder::system_report())}" title="{L_SYSTEM_REPORT}"><i class="fa fa-fw fa-info-circle"></i>{L_SYSTEM_REPORT}</a></li>
						</ul>
					</li>
					# IF C_ADMIN_LINKS_3 #
						# START admin_links_3 #
							# INCLUDE admin_links_3.MODULE_MENU #
						# END admin_links_3 #
					# ENDIF #
				</ul>
			</div>
		</li>
		<li class="admin-li">
			<a href="#openmodal-{L_USER}" title="{L_USER}"><i class="fa fa-fw fa-user"></i><span>{L_USER}</span></a>
			<div id="openmodal-{L_USER}" class="cssmenu-modal">
				<a href="#closemodal" title="${LangLoader::get_message('close_menu', 'admin')}" class="close"><span>x</span></a>
				<div class="next-menu">
					<a class="float-left" href="#openmodal-{L_TOOLS}" title="{L_TOOLS}">
						<i class="fa fa-arrow-left"></i>
						{L_TOOLS}
					</a>
					<a class="float-right" href="#openmodal-{L_CONTENT}" title="{L_CONTENT}">
						{L_CONTENT}
						<i class="fa fa-arrow-right"></i>
					</a>
				</div>
				<ul class="submenu">
					<li>
						<a href="${relative_url(AdminMembersUrlBuilder::management())}" title="{L_USER}"><i class="fa fa-fw fa-user"></i>{L_USER}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminMembersUrlBuilder::management())}" title="{L_MANAGEMENT}"><i class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminMembersUrlBuilder::add())}" title="{L_ADD}"><i class="fa fa-fw fa-plus"></i>{L_ADD}</a></li>
							<li><a href="${relative_url(AdminMembersUrlBuilder::configuration())}" title="{L_CONFIGURATION}"><i class="fa fa-fw fa-cogs"></i>{L_CONFIGURATION}</a></li>
							<li><a href="{PATH_TO_ROOT}/user/moderation_panel.php" title="{L_PUNISHEMENT}"><i class="fa fa-fw fa-ban"></i>{L_PUNISHEMENT}</a></li>
						</ul>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/admin_groups.php" title="{L_GROUP}"><i class="fa fa-fw fa-users"></i>{L_GROUP}</a>
						<ul class="level-2">
							<li><a href="{PATH_TO_ROOT}/admin/admin_groups.php" title="{L_MANAGEMENT}"><i class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/admin_groups.php?add=1" title="{L_ADD}"><i class="fa fa-fw fa-plus"></i>{L_ADD}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminExtendedFieldsUrlBuilder::fields_list())}" title="{L_EXTEND_FIELD}"><i class="fa fa-fw fa-reorder"></i>{L_EXTEND_FIELD}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminExtendedFieldsUrlBuilder::fields_list())}" title="{L_MANAGEMENT}"><i class="fa fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminExtendedFieldsUrlBuilder::add())}" title="{L_ADD}"><i class="fa fa-fw fa-plus"></i>{L_ADD}</a></li>
						</ul>
					</li>
					# IF C_ADMIN_LINKS_4 #
						# START admin_links_4 #
							# INCLUDE admin_links_4.MODULE_MENU #
						# END admin_links_4 #
					# ENDIF #
				</ul>
			</div>
		</li>
		<li class="admin-li">
			<a href="#openmodal-{L_CONTENT}" title="{L_CONTENT}"><i class="fa fa-fw fa-square-o"></i><span>{L_CONTENT}</span></a>
			<div id="openmodal-{L_CONTENT}" class="cssmenu-modal">
				<a href="#closemodal" title="${LangLoader::get_message('close_menu', 'admin')}" class="close"><span>x</span></a>
				<div class="next-menu">
					<a class="float-left" href="#openmodal-{L_USER}" title="{L_USER}">
						<i class="fa fa-arrow-left"></i>
						{L_USER}
					</a>
					<a class="float-right" href="#openmodule-{L_MODULES}" title="{L_MODULES}">
						{L_MODULES}
						<i class="fa fa-arrow-right"></i>
					</a>
				</div>
				<ul class="submenu">
					<li>
						<a href="${relative_url(AdminContentUrlBuilder::content_configuration())}" title="{L_CONTENT_CONFIG}"><i class="fa fa-fw fa-square-o"></i>{L_CONTENT_CONFIG}</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/menus/menus.php" title="{L_MENUS}"><i class="fa fa-fw fa-list-ul"></i>{L_MENUS}</a>
						<ul class="level-2">
							<li><a href="{PATH_TO_ROOT}/admin/menus/menus.php" title="{L_MANAGEMENT}"><i class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/menus/links.php" title="{L_ADD_LINKS_MENU}"><i class="fa fa-fw fa-list-ul"></i>{L_ADD_LINKS_MENU}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/menus/content.php" title="{L_ADD_CONTENT_MENU}"><i class="fa fa-fw fa-file-o"></i>{L_ADD_CONTENT_MENU}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/menus/feed.php" title="{L_ADD_FEED_MENU}"><i class="fa fa-fw fa-rss"></i>{L_ADD_FEED_MENU}</a></li>
						</ul>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/admin_files.php" title="{L_FILES}"><i class="fa fa-fw fa-file-text-o"></i>{L_FILES}</a>
						<ul class="level-2">
							<li><a href="{PATH_TO_ROOT}/admin/admin_files.php" title="{L_MANAGEMENT}"><i class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminFilesUrlBuilder::configuration())}" title="{L_CONFIGURATION}"><i class="fa fa-cogs"></i>{L_CONFIGURATION}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(UserUrlBuilder::comments())}" title="{L_COMMENTS}"><i class="fa fa-fw fa-comment-o"></i>{L_COMMENTS}</a>
						<ul class="level-2">
							<li><a href="${relative_url(UserUrlBuilder::comments())}" title="{L_MANAGEMENT}"><i class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/content/?url=/comments/config/" title="{L_CONFIGURATION}"><i class="fa fa-fw fa-cogs"></i>{L_CONFIGURATION}</a></li>
						</ul>
					</li>
					<li>
						<a href="${relative_url(AdminSmileysUrlBuilder::management())}" title="{L_SMILEY}"><i class="fa fa-fw fa-smile-o"></i>{L_SMILEY}</a>
						<ul class="level-2">
							<li><a href="${relative_url(AdminSmileysUrlBuilder::management())}" title="{L_MANAGEMENT}"><i class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminSmileysUrlBuilder::add())}" title="{L_ADD}"><i class="fa fa-fw fa-plus"></i>{L_ADD}</a></li>
						</ul>
					</li>
					# IF C_ADMIN_LINKS_5 #
						# START admin_links_5 #
							# INCLUDE admin_links_5.MODULE_MENU #
						# END admin_links_5 #
					# ENDIF #
				</ul>
			</div>
		</li>
		<li class="admin-li">
			<a href="#openmodule-{L_MODULES}" title="{L_MODULES}"><i class="fa fa-fw fa-cube"></i><span>{L_MODULES}</span></a>
			<div id="openmodule-{L_MODULES}" class="cssmenu-modal">
				<a href="#closemodal" title="${LangLoader::get_message('close_menu', 'admin')}" class="close"><span>x</span></a>
				<div class="next-menu">
					<a class="float-left" href="#openmodal-{L_CONTENT}" title="{L_CONTENT}">
						<i class="fa fa-arrow-left"></i>
						{L_CONTENT}
					</a>
					<a class="float-right" href="#openmodal-{L_ADMINISTRATION}" title="{L_ADMINISTRATION}">
						{L_ADMINISTRATION}
						<i class="fa fa-arrow-right"></i>
					</a>
				</div>
				<ul class="submenu">
					<li>
						<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}" title="{L_MODULES}"><i class="fa fa-fw fa-cube"></i>{L_MODULES}</a>
						<ul class="level-2"> 
							<li><a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}" title="{L_MANAGEMENT}"><i class="fa fa-fw fa-cog"></i>{L_MANAGEMENT}</a></li>
							<li><a href="${relative_url(AdminModulesUrlBuilder::add_module())}" title="{L_ADD}"><i class="fa fa-fw fa-plus"></i>{L_ADD}</a></li>
							<li><a href="${relative_url(AdminModulesUrlBuilder::update_module())}" title="{L_UPDATES}"><i class="fa fa-fw fa-level-up"></i>{L_UPDATES}</a></li>
						</ul>
					</li>
					# IF C_ADMIN_LINKS_6 #
						# START admin_links_6 #
							# INCLUDE admin_links_6.MODULE_MENU #
						# END admin_links_6 #
					# ENDIF #
				</ul>
			</div>
		</li>
	</ul>
</nav>