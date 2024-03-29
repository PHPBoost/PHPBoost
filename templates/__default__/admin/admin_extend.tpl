<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {@menu.quick.links}
	</a>
	<ul>
		<li>
			<a href="${Environment::get_home_page()}" class="quick-link">{@menu.site}</a>
		</li>
		<li>
			<a href="{PATH_TO_ROOT}/admin/admin_index.php" class="quick-link">{@menu.administration}</a>
		</li>
		<li>
			<a href="${relative_url(UserUrlBuilder::disconnect())}" class="quick-link">{@menu.sign.out}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">

	<fieldset>
		<legend>{@menu.administration}</legend>
		<div class="fieldset-inset">
			<nav class="admin-extend-menu">
				<ul>
					<li>
						<a href="${relative_url(AdminConfigUrlBuilder::general_config())}">
							<i class="fa fa-cog fa-2x" aria-hidden="true"></i>
							<p>{@menu.configuration}</p>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/updates">
							<i class="fa fa-download fa-2x" aria-hidden="true"></i>
							<p>{@menu.updates}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminMaintainUrlBuilder::maintain())}">
							<i class="fa fa-clock fa-2x" aria-hidden="true"></i>
							<p>{@menu.maintenance}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}">
							<i class="fa fa-image fa-2x" aria-hidden="true"></i>
							<p>{@menu.themes}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}">
							<i class="fa fa-language fa-2x" aria-hidden="true"></i>
							<p>{@menu.langs}</p>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/admin_alerts.php">
							<i class="fa fa-bell fa-2x" aria-hidden="true"></i>
							<p>{@menu.alerts}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}">
							<i class="fa fa-sync fa-2x" aria-hidden="true"></i>
							<p>{@menu.cache}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}">
							<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i>
							<p>{@menu.errors.archived}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminServerUrlBuilder::system_report())}">
							<i class="fa fa-building fa-2x" aria-hidden="true"></i>
							<p>{@menu.server.system.report}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminMembersUrlBuilder::management())}">
							<i class="fa fa-user fa-2x" aria-hidden="true"></i>
							<p>{@menu.users}</p>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/admin_groups.php">
							<i class="fa fa-users fa-2x" aria-hidden="true"></i>
							<p>{@menu.groups}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminExtendedFieldsUrlBuilder::fields_list())}">
							<i class="fa fa-bars fa-2x" aria-hidden="true"></i>
							<p>{@menu.extended.fields}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminContentUrlBuilder::content_configuration())}">
							<i class="far fa-square fa-2x" aria-hidden="true"></i>
							<p>{@menu.content}</p>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/menus/">
							<i class="fa fa-list-ul fa-2x" aria-hidden="true"></i>
							<p>{@menu.menus}</p>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/admin/admin_files.php">
							<i class="fa fa-file fa-2x" aria-hidden="true"></i>
							<p>{@menu.files}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(UserUrlBuilder::comments())}">
							<i class="fa fa-comment fa-2x" aria-hidden="true"></i>
							<p>{@menu.comments}</p>
						</a>
					</li>
					<li>
						<a href="${relative_url(AdminSmileysUrlBuilder::management())}">
							<i class="far fa-smile fa-2x" aria-hidden="true"></i>
							<p>{@menu.smileys}</p>
						</a>
					</li>
				</ul>
			</nav>
		</div>
	</fieldset>

	<fieldset>
		<legend>{@menu.modules}</legend>
		<div class="fieldset-inset">
			<nav class="admin-extend-menu">
				<ul>
					<li>
						<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}">
							<i class="fa fa-cubes fa-2x" aria-hidden="true"></i>
							<p>{@menu.management}</p>
						</a>
					</li>
					# START modules_extend #
						<li>
							<a href="{modules_extend.U_ADMIN_MODULE}">
								# IF modules_extend.C_IMG #
									<img src="{modules_extend.IMG}" alt="{modules_extend.NAME}" />
								# ELSE #
									# IF modules_extend.C_FA_ICON #
										<i class="{modules_extend.FA_ICON} fa-fw fa-2x"></i>
									# ELSE #
										# IF modules_extend.C_HEXA_ICON #<span class="biggest">{modules_extend.HEXA_ICON}</span># ENDIF #
									# ENDIF #
								# ENDIF #
								<p>{modules_extend.NAME}</p>
							</a>
						</li>
					# END modules_extend #
				</ul>
			</nav>
		</div>
	</fieldset>

</div>
