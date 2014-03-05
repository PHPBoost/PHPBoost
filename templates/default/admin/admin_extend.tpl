<div id="admin-quick-menu">
	<ul>
		<li class="title-menu">{L_QUICK_LINKS}</li>
		<li>
			<a href="{U_INDEX_SITE}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/admin.png" alt="" /></a>
			<br />
			<a href="{U_INDEX_SITE}" class="quick-link">{L_SITE}</a>
		</li>
		<li>
			<a href="{PATH_TO_ROOT}/admin/admin_index.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/ranks.png" alt="" /></a>
			<br />
			<a href="{PATH_TO_ROOT}/admin/admin_index.php" class="quick-link">{L_INDEX_ADMIN}</a>
		</li>
		<li>
			<a href="{PATH_TO_ROOT}/admin/admin_index.php?disconnect=true&amp;token={TOKEN}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/home.png" alt="" /></a>
			<br />
			<a href="{PATH_TO_ROOT}/admin/admin_index.php?disconnect=true&amp;token={TOKEN}" class="quick-link">{L_DISCONNECT}</a>
		</li>
	</ul>
</div>

<div id="admin-contents">
	<table>
		<thead>
			<tr>
				<th colspan="5">
					{L_SITE}
				</th>
			</tr>
		</thead>
		<tbody>
			<tr class="center">
				<td class="no-separator" style="width:20%;">
					<a href="${relative_url(AdminConfigUrlBuilder::general_config())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/configuration.png" alt="" /></a>
					<br />
					<a href="${relative_url(AdminConfigUrlBuilder::general_config())}">{L_CONFIGURATION}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/themes.png" alt="" /></a>
					<br />
					<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}">{L_THEME}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/languages.png" alt="" /></a>
					<br />
					<a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}">{L_LANG}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="{PATH_TO_ROOT}/admin/admin_smileys.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/smileys.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/admin_smileys.php">{L_SMILEY}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="{PATH_TO_ROOT}/admin/admin_alerts.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/administrator_alert.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/admin_alerts.php">{L_ADMINISTRATOR_ALERTS}</a>
				</td>
			</tr>
			<tr class="center">
				<td class="no-separator" style="width:20%;">
					<a href="{PATH_TO_ROOT}/admin/updates"><img src="{PATH_TO_ROOT}/templates/default/images/admin/updater.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/updates">{L_WEBSITE_UPDATES}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="{PATH_TO_ROOT}/admin/admin_maintain.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/maintain.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/admin_maintain.php">{L_MAINTAIN}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/cache.png" alt="" /></a>
					<br />
					<a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}">{L_CACHE}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="{PATH_TO_ROOT}/admin/admin_errors.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/errors.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/admin_errors.php">{L_ERRORS}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="{PATH_TO_ROOT}/admin/admin_system_report.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/server.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/admin_system_report.php">{L_SERVER}</a>
				</td>
			</tr>
			<tr class="center">
				<td class="no-separator" style="width:20%;">
					<a href="{PATH_TO_ROOT}/admin/member/index.php?url=/management/"><img src="{PATH_TO_ROOT}/templates/default/images/admin/members.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/member/index.php?url=/management/">{L_USER}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="{PATH_TO_ROOT}/admin/admin_groups.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/admin_groups.php">{L_GROUP}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="{PATH_TO_ROOT}/admin/member/index.php?url=/extended-fields/list/"><img src="{PATH_TO_ROOT}/templates/default/images/admin/extendfield.png" alt=""/></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/member/index.php?url=/extended-fields/list/">{L_EXTEND_FIELD}</a>
				</td>
				<td class="no-separator" style="width:20%;">&nbsp;</td>
				<td class="no-separator" style="width:20%;">&nbsp;</td>
			</tr>
			<tr class="center">
				<td class="no-separator" style="width:20%;">
					<a href="${relative_url(AdminContentUrlBuilder::content_configuration())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/content.png" alt="" /></a>
					<br />
					<a href="${relative_url(AdminContentUrlBuilder::content_configuration())}">{L_CONTENT_CONFIG}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="{PATH_TO_ROOT}/admin/menus/"><img src="{PATH_TO_ROOT}/templates/default/images/admin/menus.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/menus/">{L_SITE_MENU}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="{PATH_TO_ROOT}/admin/admin_files.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/files.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}/admin/admin_files.php">{L_FILES}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="${relative_url(UserUrlBuilder::comments())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/com.png" alt="" /></a>
					<br />
					<a href="${relative_url(UserUrlBuilder::comments())}">{L_COM}</a>
				</td>
				<td class="no-separator" style="width:20%;">
					<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}">{L_MODULES}</a>
				</td>
			</tr>
		</tbody>
	</table>

	<table>
		<thead>
			<tr>
				<th colspan="5">
					{L_MODULES}
				</th>
			</tr>
		</thead>
		<tbody>
			# START modules_extend #
			{modules_extend.START_TR}
				<td class="no-separator" style="width:20%;">
					<a href="{modules_extend.U_ADMIN_MODULE}"><img src="{modules_extend.IMG}" alt="" /></a>
					<br />
					<a href="{modules_extend.U_ADMIN_MODULE}">{modules_extend.NAME}</a>
				</td>
				# START modules_extend.td #
				{modules_extend.td.TD}
				# END modules_extend.td #
			{modules_extend.END_TR}
			# END modules_extend #
		</tbody>
	</table>
	<br />
</div>