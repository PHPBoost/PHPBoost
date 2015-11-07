<nav id="admin-quick-menu">
	<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" title="{L_PROFIL}">
		<i class="fa fa-bars"></i> {L_QUICK_LINKS}
	</a>
	<ul>
		<li>
			<a href="{U_INDEX_SITE}" class="quick-link">{L_SITE}</a>
		</li>
		<li>
			<a href="{PATH_TO_ROOT}/admin/admin_index.php" class="quick-link">{L_INDEX_ADMIN}</a>
		</li>
		<li>
			<a href="${relative_url(UserUrlBuilder::disconnect())}" class="quick-link">{L_DISCONNECT}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	
	<fieldset>
		<legend>${LangLoader::get_message('administration', 'admin-links-common')}</legend>
		<div class="fieldset-inset">
			<nav class="admin-extend-menu">
				<ul>
					<li>
							<a href="${relative_url(AdminConfigUrlBuilder::general_config())}">
								<i class="fa fa-cog fa-2x"></i>
								<p>{L_CONFIGURATION}</p>
							</a>
					</li>
					<li>
							<a href="{PATH_TO_ROOT}/admin/updates">
								<i class="fa fa-download fa-2x"></i>
								<p>{L_WEBSITE_UPDATES}</p>
							</a>
					</li>
					<li>
							<a href="${relative_url(AdminMaintainUrlBuilder::maintain())}">
								<i class="fa fa-clock-o fa-2x"></i>
								<p>{L_MAINTAIN}</p>
							</a>
					</li>
					<li>
							<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}">
								<i class="fa fa-picture-o fa-2x"></i>
								<p>{L_THEME}</p>
							</a>
					</li>
					<li>
							<a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}">
								<i class="fa fa-language fa-2x"></i>
								<p>{L_LANG}</p>
							</a>
					</li>
					<li>
							<a href="{PATH_TO_ROOT}/admin/admin_alerts.php">
								<i class="fa fa-bell fa-2x"></i>
								<p>{L_ADMINISTRATOR_ALERTS}</p>
							</a>
					</li>
					<li>
							<a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}">
								<i class="fa fa-refresh fa-2x"></i>
								<p>{L_CACHE}</p>
							</a>
					</li>
					<li>
							<a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}">
								<i class="fa fa-exclamation-triangle fa-2x"></i>
								<p>{L_ERRORS}</p>
							</a>
					</li>
					<li>
							<a href="${relative_url(AdminServerUrlBuilder::system_report())}">
								<i class="fa fa-building fa-2x"></i>
								<p>{L_SERVER}</p>
							</a>
					</li>
					<li>
							<a href="${relative_url(AdminMembersUrlBuilder::management())}">
								<i class="fa fa-user fa-2x"></i>
								<p>{L_USER}</p>
							</a>
					</li>
					<li>
							<a href="{PATH_TO_ROOT}/admin/admin_groups.php">
								<i class="fa fa-users fa-2x"></i>
								<p>{L_GROUP}</p>
							</a>
					</li>
					<li>
							<a href="${relative_url(AdminExtendedFieldsUrlBuilder::fields_list())}">
								<i class="fa fa-bars fa-2x"></i>
								<p>{L_EXTEND_FIELD}</p>
							</a>
					</li>
					<li>
							<a href="${relative_url(AdminContentUrlBuilder::content_configuration())}">
								<i class="fa fa-square-o fa-2x"></i>
								<p>{L_CONTENT_CONFIG}</p>
							</a>
					</li>
					<li>
							<a href="{PATH_TO_ROOT}/admin/menus/">
								<i class="fa fa-list-ul fa-2x"></i>
								<p>{L_SITE_MENU}</p>
							</a>
					</li>
					<li>
							<a href="{PATH_TO_ROOT}/admin/admin_files.php">
								<i class="fa fa-file-o fa-2x"></i>
								<p>{L_FILES}</p>
							</a>
					</li>
					<li>
							<a href="${relative_url(UserUrlBuilder::comments())}">
								<i class="fa fa-comment-o fa-2x"></i>
								<p>{L_COM}</p>
							</a>
					</li>
					<li>
							<a href="${relative_url(AdminSmileysUrlBuilder::management())}">
								<i class="fa fa-smile-o fa-2x"></i>
								<p>{L_SMILEY}</p>
							</a>
					</li>
				</ul>
			</nav>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>{L_MODULES}</legend>
		<div class="fieldset-inset">
			<nav class="admin-extend-menu">
				<ul>
					<li>
						<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}">
							<i class="fa fa-cubes fa-2x"></i>
							<p>${LangLoader::get_message('management', 'admin-links-common')}</p>
						</a>
					</li>
					# START modules_extend #
					<li>
						<a href="{modules_extend.U_ADMIN_MODULE}">
							<img src="{modules_extend.IMG}" alt="{modules_extend.NAME}" />
							<p>{modules_extend.NAME}</p>
						</a>
					</li>
					# END modules_extend #
				</ul>
			</nav>
		</div>
	</fieldset>
	
</div>