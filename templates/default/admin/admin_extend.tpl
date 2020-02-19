<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {@quick_links}
	</a>
	<ul>
		<li>
			<a href="${Environment::get_home_page()}" class="quick-link">${LangLoader::get_message('index.site', 'main')}</a>
		</li>
		<li>
			<a href="{PATH_TO_ROOT}/admin/admin_index.php" class="quick-link">{@administration}</a>
		</li>
		<li>
			<a href="${relative_url(UserUrlBuilder::disconnect())}" class="quick-link">${LangLoader::get_message('index.disconnect', 'main')}</a>
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
							<i class="fa fa-cog fa-2x" aria-hidden="true"></i>
							<p>{@configuration}</p>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/updates">
							<i class="fa fa-download fa-2x" aria-hidden="true"></i>
							<p>{@updates}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminMaintainUrlBuilder::maintain())}">
							<i class="fa fa-clock fa-2x" aria-hidden="true"></i>
							<p>{@tools.maintain}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}">
							<i class="fa fa-image fa-2x" aria-hidden="true"></i>
							<p>{@administration.themes}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}">
							<i class="fa fa-language fa-2x" aria-hidden="true"></i>
							<p>{@administration.langs}</p>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/admin_alerts.php">
							<i class="fa fa-bell fa-2x" aria-hidden="true"></i>
							<p>{@administration.alerts}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}">
							<i class="fa fa-sync fa-2x" aria-hidden="true"></i>
							<p>{@tools.cache}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}">
							<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i>
							<p>${LangLoader::get_message('tools.errors-archived', 'admin-links-common')}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminServerUrlBuilder::system_report())}">
							<i class="fa fa-building fa-2x" aria-hidden="true"></i>
							<p>${LangLoader::get_message('tools.server.system-report', 'admin-links-common')}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminMembersUrlBuilder::management())}">
							<i class="fa fa-user fa-2x" aria-hidden="true"></i>
							<p>{@users}</p>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/admin_groups.php">
							<i class="fa fa-users fa-2x" aria-hidden="true"></i>
							<p>{@users.groups}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminExtendedFieldsUrlBuilder::fields_list())}">
							<i class="fa fa-bars fa-2x" aria-hidden="true"></i>
							<p>${LangLoader::get_message('users.extended-fields', 'admin-links-common')}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminContentUrlBuilder::content_configuration())}">
							<i class="far fa-square fa-2x" aria-hidden="true"></i>
							<p>{@content}</p>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/menus/">
							<i class="fa fa-list-ul fa-2x" aria-hidden="true"></i>
							<p>{@content.menus}</p>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/admin_files.php">
							<i class="fa fa-file fa-2x" aria-hidden="true"></i>
							<p>{@content.files}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(UserUrlBuilder::comments())}">
							<i class="fa fa-comment fa-2x" aria-hidden="true"></i>
							<p>{@content.comments}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminSmileysUrlBuilder::management())}">
							<i class="far fa-smile fa-2x" aria-hidden="true"></i>
							<p>{@administration.smileys}</p>
						</a>
					</li>
				</ul>
			</nav>
		</div>
	</fieldset>

	<fieldset>
		<legend>{@modules}</legend>
		<div class="fieldset-inset">
			<nav class="admin-extend-menu">
				<ul>
					<li>
						<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}">
							<i class="fa fa-cubes fa-2x" aria-hidden="true"></i>
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
