<ul>
	<li>
		<h5 class="links"><img src="{PATH_TO_ROOT}/templates/default/images/admin/admin_mini.png" alt="" /> {L_INDEX}</h5>
		<ul>
			<li><a href="{PATH_TO_ROOT}/index.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/admin_mini.png"/> {L_INDEX_SITE}</a></li>
			<li><a href="{PATH_TO_ROOT}/admin/admin_index.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/ranks_mini.png"/> {L_ADMINISTRATION}</a></li>
			<li class="separator"></li>
			<li><a href="{PATH_TO_ROOT}/admin/admin_index.php?disconnect=true&amp;token={TOKEN}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/home_mini.png"/> {L_DISCONNECT}</a></li>
			# IF C_ADMIN_LINKS_1 #
			<li class="separator"></li>
				# START admin_links_1 #
					# INCLUDE admin_links_1.MODULE_MENU #
				# END admin_links_1 #
			# ENDIF #
		</ul>
	</li>
	<li>
		<h5 class="links"><img src="{PATH_TO_ROOT}/templates/default/images/admin/ranks_mini.png" alt="" /> {L_ADMINISTRATION}</h5>
		<ul>
			<li class="extend">
				<a href="${relative_url(AdminConfigUrlBuilder::general_config())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/config_mini.png"/> {L_CONFIGURATION}</a>
				<ul>
					<li><a href="${relative_url(AdminConfigUrlBuilder::general_config())}">{L_CONFIG_GENERAL}</a></li>
					<li><a href="${relative_url(AdminConfigUrlBuilder::advanced_config())}">{L_CONFIG_ADVANCED}</a></li>
					<li><a href="${relative_url(AdminConfigUrlBuilder::mail_config())}">{L_MAIL_CONFIG}</a></li>
				</ul>
			</li>
			 <li class="extend">
				<a href="{PATH_TO_ROOT}/admin/updates/updates.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/updater_mini.png"/> {L_UPDATES}</a>
				<ul>
					<li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=kernel">{L_KERNEL}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=module">{L_MODULES}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=template">{L_THEMES}</a></li>
				</ul>
			</li>
			<li><a href="{PATH_TO_ROOT}/admin/admin_maintain.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/maintain_mini.png"/> {L_MAINTAIN}</a></li>
			<li class="extend">
				<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/themes_mini.png"/> {L_THEMES}</a>
				<ul>
					<li><a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}">{L_MANAGEMENT}</a></li>
					<li><a href="${relative_url(AdminThemeUrlBuilder::add_theme())}">{L_ADD}</a></li>
				</ul>
			</li>
			<li class="extend">
				<a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/languages_mini.png"/> {L_LANGS}</a>
				<ul>
					<li><a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}">{L_MANAGEMENT}</a></li>
					<li><a href="${relative_url(AdminLangsUrlBuilder::install())}">{L_ADD}</a></li>
				</ul>
			</li>
			<li><a href="{PATH_TO_ROOT}/admin/admin_alerts.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/administrator_alert_mini.png"/> {L_ADMINISTRATOR_ALERTS}</a></li>
			# IF C_ADMIN_LINKS_2 #
			<li class="separator"></li>
				# START admin_links_2 #
					# INCLUDE admin_links_2.MODULE_MENU #
				# END admin_links_2 #
			# ENDIF #
		</ul>
	</li>
	<li>
		<h5 class="links"><img src="{PATH_TO_ROOT}/templates/default/images/admin/tools_mini.png" class="valign_middle" alt="" /> {L_TOOLS}</h5>
		<ul>
			<li class="extend">
				<a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/cache_mini.png"/> {L_CACHE}</a>
				<ul>
					<li><a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}">{L_CACHE}</a></li>
					<li><a href="${relative_url(AdminCacheUrlBuilder::clear_syndication_cache())}">{L_SYNDICATION_CACHE}</a></li>
					<li><a href="${relative_url(AdminCacheUrlBuilder::clear_css_cache())}">{L_CSS_CACHE_CONFIG}</a></li>
					<li><a href="${relative_url(AdminCacheUrlBuilder::configuration())}">{L_CONFIGURATION}</a></li>
				</ul>
			</li>
			<li class="extend">
				<a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/errors_mini.png"/> {L_ERRORS}</a>
				<ul>
					<li><a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}">{L_ERRORS}</a></li>
					<li><a href="${relative_url(AdminErrorsUrlBuilder::list_404_errors())}">{L_404_ERRORS}</a></li>
				</ul>
			</li><li class="extend">
				<a href="{PATH_TO_ROOT}/admin/admin_system_report.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/server_mini.png"/> {L_SERVER}</a>
				<ul>
					<li><a href="{PATH_TO_ROOT}/admin/admin_phpinfo.php">{L_PHPINFO}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/admin_system_report.php">{L_SYSTEM_REPORT}</a></li>
				</ul>
			</li>
			# IF C_ADMIN_LINKS_3 #
			<li class="separator"></li>
				# START admin_links_3 #
					# INCLUDE admin_links_3.MODULE_MENU #
				# END admin_links_3 #
			# ENDIF #
		</ul>
	</li>
	<li>
		<h5 class="links"><img src="{PATH_TO_ROOT}/templates/default/images/admin/groups_mini.png" class="valign_middle" alt="" /> {L_USER}</h5>
		<ul>
			<li class="extend">
				<a href="${relative_url(AdminMembersUrlBuilder::management())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/members_mini.png"/> {L_USER}</a>
				<ul>
					<li><a href="${relative_url(AdminMembersUrlBuilder::management())}">{L_MANAGEMENT}</a></li>
					<li><a href="${relative_url(AdminMembersUrlBuilder::add())}">{L_ADD}</a></li>
					<li><a href="${relative_url(AdminMembersUrlBuilder::configuration())}">{L_CONFIGURATION}</a></li>
					<li><a href="{PATH_TO_ROOT}/user/moderation_panel.php">{L_PUNISHEMENT}</a></li>
				</ul>
			</li>
			<li class="extend">
				<a href="{PATH_TO_ROOT}/admin/admin_groups.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/groups_mini.png"/> {L_GROUP}</a>
				<ul>
					<li><a href="{PATH_TO_ROOT}/admin/admin_groups.php">{L_MANAGEMENT}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/admin_groups.php?add=1">{L_ADD}</a></li>
				</ul>
			</li>
			<li class="extend">
				<a href="{PATH_TO_ROOT}/admin/member/index.php?url=/extended-fields/list/"><img src="{PATH_TO_ROOT}/templates/default/images/admin/extendfield_mini.png"/> {L_EXTEND_FIELD}</a>
				<ul>
					<li><a href="{PATH_TO_ROOT}/admin/member/?url=/extended-fields/list/">{L_MANAGEMENT}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/member/?url=/extended-fields/add/">{L_ADD}</a></li>
				</ul>
			</li>
			# IF C_ADMIN_LINKS_4 #
			<li class="separator"></li>
				# START admin_links_4 #
					# INCLUDE admin_links_4.MODULE_MENU #
				# END admin_links_4 #
			# ENDIF #
		</ul>
	</li>
	<li>
		<h5 class="links"><img src="{PATH_TO_ROOT}/templates/default/images/admin/contents_mini.png" class="valign_middle" alt="" /> {L_CONTENT}</h5>
		<ul>
			<li><a href="${relative_url(AdminContentUrlBuilder::content_configuration())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/content_mini.png"/> {L_CONTENT_CONFIG}</a></li>
			<li class="extend">
				<a href="{PATH_TO_ROOT}/admin/menus/menus.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/menus_mini.png"/> {L_MENUS}</a>
				<ul>
					<li><a href="{PATH_TO_ROOT}/admin/menus/menus.php">{L_MANAGEMENT}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/menus/links.php">{L_ADD_LINKS_MENU}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/menus/content.php">{L_ADD_CONTENT_MENU}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/menus/feed.php">{L_ADD_FEED_MENU}</a></li>
				</ul>
			</li>
			<li class="extend">
				<a href="{PATH_TO_ROOT}/admin/admin_files.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/files_mini.png"/> {L_FILES}</a>
				<ul>
					<li><a href="{PATH_TO_ROOT}/admin/admin_files.php">{L_MANAGEMENT}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/admin_files_config.php">{L_CONFIGURATION}</a></li>
				</ul>
			</li>
			<li class="extend">
				<a href="${relative_url(UserUrlBuilder::comments())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/com_mini.png"/> {L_COMMENTS}</a>
				<ul>
					<li><a href="${relative_url(UserUrlBuilder::comments())}">{L_MANAGEMENT}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/content/?url=/comments/config/">{L_CONFIGURATION}</a></li>
				</ul>
			</li>
			<li class="extend">
				<a href="{PATH_TO_ROOT}/admin/admin_smileys.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/smileys_mini.png"/> {L_SMILEY}</a>
				<ul>
					<li><a href="{PATH_TO_ROOT}/admin/admin_smileys.php">{L_MANAGEMENT}</a></li>
					<li><a href="{PATH_TO_ROOT}/admin/admin_smileys_add.php">{L_ADD}</a></li>
				</ul>
			</li>
			# IF C_ADMIN_LINKS_5 #
			<li class="separator"></li>
				# START admin_links_5 #
					# INCLUDE admin_links_5.MODULE_MENU #
				# END admin_links_5 #
			# ENDIF #
		</ul>
	</li>
	<li>
		<h5 class="links"><img src="{PATH_TO_ROOT}/templates/default/images/admin/modules_mini.png" class="valign_middle" alt="" /> {L_MODULES}</h5>
		<ul>
			<li class="extend">
				<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/modules_mini.png"/> {L_MODULES}</a>
				<ul> 
					<li><a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}">{L_MANAGEMENT}</a></li>
					<li><a href="${relative_url(AdminModulesUrlBuilder::add_module())}">{L_ADD}</a></li>
					<li><a href="${relative_url(AdminModulesUrlBuilder::update_module())}">{L_UPDATES}</a></li>
				</ul>
			</li>
			# IF C_ADMIN_LINKS_6 #
			<li class="separator"></li>
				# START admin_links_6 #
					# INCLUDE admin_links_6.MODULE_MENU #
				# END admin_links_6 #
			# ENDIF #
		</ul>
	</li>
</ul>
