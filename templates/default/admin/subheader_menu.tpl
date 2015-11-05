
<script src="{PATH_TO_ROOT}/templates/default/admin/js/menumaker.js"></script>
<nav id="rm-admin" class="rm">
	<ul>
		<li><a class="cssmenu-title"><i class="fa fa-fw fa-cog"></i> {L_ADMINISTRATION}</a>
			<ul>
				<li>
					<a class="cssmenu-title" href="${relative_url(AdminConfigUrlBuilder::general_config())}"><i class="fa fa-fw fa-cog"></i> {L_CONFIGURATION}</a>
					<ul>
						<li><a class="cssmenu-title" href="${relative_url(AdminConfigUrlBuilder::general_config())}"><i class="fa fa-fw fa-cog"></i> {L_CONFIG_GENERAL}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminConfigUrlBuilder::advanced_config())}"><i class="fa fa-fw fa-cogs"></i> {L_CONFIG_ADVANCED}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminConfigUrlBuilder::mail_config())}"><i class="fa fa-fw fa-envelope-o"></i> {L_MAIL_CONFIG}</a></li>
					</ul>
				</li>
				<li>
					<a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/updates/updates.php"><i class="fa fa-fw fa-download"></i> {L_UPDATES}</a>
					<ul>
						<li><a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/updates/updates.php?type=kernel"><i class="fa fa-fw fa-cog"></i> {L_KERNEL}</a></li>
						<li><a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/updates/updates.php?type=module"><i class="fa fa-fw fa-cubes"></i> {L_MODULES}</a></li>
						<li><a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/updates/updates.php?type=template"><i class="fa fa-fw fa-picture-o"></i> {L_THEMES}</a></li>
					</ul>
				</li>
				<li>
					<a class="cssmenu-title" href="${relative_url(AdminMaintainUrlBuilder::maintain())}"><i class="fa fa-fw fa-clock-o"></i> {L_MAINTAIN}</a>
				</li>
				<li>
					<a class="cssmenu-title" href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}"><i class="fa fa-fw fa-picture-o"></i> {L_THEMES}</a>
					<ul>
						<li><a class="cssmenu-title" href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}"><i class="fa fa-fw fa-cog"></i> {L_MANAGEMENT}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminThemeUrlBuilder::add_theme())}"><i class="fa fa-fw fa-plus"></i> {L_ADD}</a></li>
					</ul>
				</li>
				<li>
					<a class="cssmenu-title" href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}"><i class="fa fa-fw fa-language"></i> {L_LANGS}</a>
					<ul>
						<li><a class="cssmenu-title" href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}"><i class="fa fa-fw fa-cog"></i> {L_MANAGEMENT}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminLangsUrlBuilder::install())}"><i class="fa fa-fw fa-plus"></i> {L_ADD}</a></li>
					</ul>
				</li>
				<li>
					<a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/admin_alerts.php"><i class="fa fa-fw fa-bell"></i> {L_ADMINISTRATOR_ALERTS}</a>
				</li>
				# IF C_ADMIN_LINKS_2 #
					# START admin_links_2 #
						# INCLUDE admin_links_2.MODULE_MENU #
					# END admin_links_2 #
				# ENDIF #
			</ul>
		</li>
		<li><a class="cssmenu-title"><i class="fa fa-fw fa-wrench"></i> {L_TOOLS}</a>
			<ul>
				<li>
					<a class="cssmenu-title" href="${relative_url(AdminCacheUrlBuilder::clear_cache())}"><i class="fa fa-fw fa-refresh"></i> {L_CACHE}</a>
					<ul>
						<li><a class="cssmenu-title" href="${relative_url(AdminCacheUrlBuilder::clear_cache())}"><i class="fa fa-fw fa-refresh"></i> {L_CACHE}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminCacheUrlBuilder::clear_syndication_cache())}"><i class="fa fa-fw fa-rss"></i> {L_SYNDICATION_CACHE}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminCacheUrlBuilder::clear_css_cache())}"><i class="fa fa-fw fa-css3"></i> {L_CSS_CACHE_CONFIG}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminCacheUrlBuilder::configuration())}"><i class="fa fa-fw fa-cogs"></i> {L_CONFIGURATION}</a></li>
					</ul>
				</li>
				<li>
					<a class="cssmenu-title" href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}"><i class="fa fa-fw fa-exclamation-triangle"></i> {L_ERRORS}</a>
					<ul>
						<li><a class="cssmenu-title" href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}"><i class="fa fa-fw fa-exclamation-circle"></i> {L_ERRORS}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminErrorsUrlBuilder::list_404_errors())}"><i class="fa fa-fw fa-ban"></i> {L_404_ERRORS}</a></li>
					</ul>
				</li>
				<li>
					<a class="cssmenu-title" href="${relative_url(AdminServerUrlBuilder::system_report())}"><i class="fa fa-fw fa-building"></i> {L_SERVER}</a>
					<ul>
						<li><a class="cssmenu-title" href="${relative_url(AdminServerUrlBuilder::phpinfo())}"><i class="fa fa-fw fa-info"></i> {L_PHPINFO}</a></a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminServerUrlBuilder::system_report())}"><i class="fa fa-fw fa-info-circle"></i> {L_SYSTEM_REPORT}</a></li>
					</ul>
				</li>
				# IF C_ADMIN_LINKS_3 #
					# START admin_links_3 #
						# INCLUDE admin_links_3.MODULE_MENU #
					# END admin_links_3 #
				# ENDIF #
			</ul>
		</li>
		<li><a class="cssmenu-title"><i class="fa fa-fw fa-user"></i> {L_USER}</a>
			<ul>
				<li>
					<a class="cssmenu-title" href="${relative_url(AdminMembersUrlBuilder::management())}"><i class="fa fa-fw fa-user"></i> {L_USER}</a>
					<ul>
						<li><a class="cssmenu-title" href="${relative_url(AdminMembersUrlBuilder::management())}"><i class="fa fa-fw fa-cog"></i> {L_MANAGEMENT}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminMembersUrlBuilder::add())}"><i class="fa fa-fw fa-plus"></i> {L_ADD}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminMembersUrlBuilder::configuration())}"><i class="fa fa-fw fa-cogs"></i> {L_CONFIGURATION}</a></li>
						<li><a class="cssmenu-title" href="{PATH_TO_ROOT}/user/moderation_panel.php"><i class="fa fa-fw fa-ban"></i> {L_PUNISHEMENT}</a></li>
					</ul>
				</li>
				<li>
					<a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/admin_groups.php"><i class="fa fa-fw fa-users"></i> {L_GROUP}</a>
					<ul>
						<li><a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/admin_groups.php"><i class="fa fa-fw fa-cog"></i> {L_MANAGEMENT}</a></li>
						<li><a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/admin_groups.php?add=1"><i class="fa fa-fw fa-plus"></i> {L_ADD}</a></li>
					</ul>
				</li>
				<li>
					<a class="cssmenu-title" href="${relative_url(AdminExtendedFieldsUrlBuilder::fields_list())}"><i class="fa fa-fw fa-reorder"></i> {L_EXTEND_FIELD}</a>
					<ul>
						<li><a class="cssmenu-title" href="${relative_url(AdminExtendedFieldsUrlBuilder::fields_list())}"><i class="fa fa-cog"></i> {L_MANAGEMENT}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminExtendedFieldsUrlBuilder::add())}"><i class="fa fa-fw fa-plus"></i> {L_ADD}</a></li>
					</ul>
				</li>
				# IF C_ADMIN_LINKS_4 #
					# START admin_links_4 #
						# INCLUDE admin_links_4.MODULE_MENU #
					# END admin_links_4 #
				# ENDIF #
			</ul>
		</li>
		<li><a class="cssmenu-title"><i class="fa fa-fw fa-square-o"></i> {L_CONTENT}</a>
			<ul>
				<li>
					<a class="cssmenu-title" href="${relative_url(AdminContentUrlBuilder::content_configuration())}"><i class="fa fa-fw fa-square-o"></i> {L_CONTENT_CONFIG}</a>
				</li>
				<li>
					<a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/menus/menus.php"><i class="fa fa-fw fa-list-ul"></i> {L_MENUS}</a>
					<ul>
						<li><a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/menus/menus.php"><i class="fa fa-fw fa-cog"></i> {L_MANAGEMENT}</a></li>
						<li><a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/menus/links.php"><i class="fa fa-fw fa-list-ul"></i> {L_ADD_LINKS_MENU}</a></li>
						<li><a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/menus/content.php"><i class="fa fa-fw fa-file-o"></i> {L_ADD_CONTENT_MENU}</a></li>
						<li><a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/menus/feed.php"><i class="fa fa-fw fa-rss"></i> {L_ADD_FEED_MENU}</a></li>
					</ul>
				</li>
				<li>
					<a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/admin_files.php"><i class="fa fa-fw fa-file-text-o"></i> {L_FILES}</a>
					<ul>
						<li><a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/admin_files.php"><i class="fa fa-fw fa-cog"></i> {L_MANAGEMENT}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminFilesUrlBuilder::configuration())}"><i class="fa fa-cogs"></i> {L_CONFIGURATION}</a></li>
					</ul>
				</li>
				<li>
					<a class="cssmenu-title" href="${relative_url(UserUrlBuilder::comments())}"><i class="fa fa-fw fa-comment-o"></i> {L_COMMENTS}</a>
					<ul>
						<li><a class="cssmenu-title" href="${relative_url(UserUrlBuilder::comments())}"><i class="fa fa-fw fa-cog"></i> {L_MANAGEMENT}</a></li>
						<li><a class="cssmenu-title" href="{PATH_TO_ROOT}/admin/content/?url=/comments/config/"><i class="fa fa-fw fa-cogs"></i> {L_CONFIGURATION}</a></li>
					</ul>
				</li>
				<li>
					<a class="cssmenu-title" href="${relative_url(AdminSmileysUrlBuilder::management())}"><i class="fa fa-fw fa-smile-o"></i> {L_SMILEY}</a>
					<ul>
						<li><a class="cssmenu-title" href="${relative_url(AdminSmileysUrlBuilder::management())}"><i class="fa fa-fw fa-cog"></i> {L_MANAGEMENT}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminSmileysUrlBuilder::add())}"><i class="fa fa-fw fa-plus"></i> {L_ADD}</a></li>
					</ul>
				</li>
				# IF C_ADMIN_LINKS_5 #
					# START admin_links_5 #
						# INCLUDE admin_links_5.MODULE_MENU #
					# END admin_links_5 #
				# ENDIF #
			</ul>
		</li>
		<li><a class="cssmenu-title"><i class="fa fa-fw fa-cubes"></i> {L_MODULES}</a>
			<ul>
				<li>
					<a class="cssmenu-title" href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}"><i class="fa fa-fw fa-cubes"></i> {L_MODULES}</a>
					<ul> 
						<li><a class="cssmenu-title" href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}"><i class="fa fa-fw fa-cog"></i> {L_MANAGEMENT}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminModulesUrlBuilder::add_module())}"><i class="fa fa-fw fa-plus"></i> {L_ADD}</a></li>
						<li><a class="cssmenu-title" href="${relative_url(AdminModulesUrlBuilder::update_module())}"><i class="fa fa-fw fa-level-up"></i> {L_UPDATES}</a></li>
					</ul>
				</li>
				# IF C_ADMIN_LINKS_6 #
					# START admin_links_6 #
						# INCLUDE admin_links_6.MODULE_MENU #
					# END admin_links_6 #
				# ENDIF #
			</ul>
		</li>
	</ul>
</nav>
<script>
	jQuery("#rm-admin").menumaker({
		title: "{HEADER_LOGO} ${Langloader::get_message('admin.main_menu', 'main')}",
		format: "multitoggle",
		breakpoint: 9000
	});
</script>