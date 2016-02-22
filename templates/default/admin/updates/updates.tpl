<nav id="admin-quick-menu">
	<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" title="{L_WEBSITE_UPDATES}">
		<i class="fa fa-bars"></i> {L_WEBSITE_UPDATES}
	</a>
	<ul>
		<li>
			<a href="updates.php" class="quick-link">{L_WEBSITE_UPDATES}</a>
		</li>
		<li>
			<a href="updates.php?type=kernel" class="quick-link">{L_KERNEL}</a>
		</li>
		<li>
			<a href="updates.php?type=module" class="quick-link">{L_MODULES}</a>
		</li>
		<li>
			<a href="updates.php?type=template" class="quick-link">{L_THEMES}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	<fieldset>
		<legend>{L_WEBSITE_UPDATES}</legend>
		<div class="fieldset-inset">
		# IF C_INCOMPATIBLE_PHP_VERSION #
			<div class="warning message-helper-small">{L_INCOMPATIBLE_PHP_VERSION}</div>
		# ELSE #
			# IF C_UPDATES #
			<table id="table">
				<caption>{L_WEBSITE_UPDATES}</caption>
				<div class="warning message-helper-small">{L_UPDATES_ARE_AVAILABLE}</div>
				<thead>
					<tr>
						# IF C_ALL #
						<th class="td100">{L_TYPE}</td>
						# ENDIF #
						<th>{L_DESCRIPTION}</td>
						<th class="td75">{L_PRIORITY}</td>
						<th class="td150">{L_UPDATE_DOWNLOAD}</td>
					</tr>
				</thead>
				<tbody>
					# START apps #
					<tr> 
						# IF C_ALL #
						<td class="center">{apps.type}</td>
						# ENDIF #
						<td>
							{L_NAME} : <strong>{apps.name}</strong> - {L_VERSION} : <strong>{apps.version}</strong>
							<div class="update-desc">{apps.short_description}</div>
							<p><a href="{PATH_TO_ROOT}/admin/updates/detail.php?identifier={apps.identifier}" title="{L_MORE_DETAILS}" class="small">{L_DETAILS}</a></p>
						</td>
						<td>{apps.L_PRIORITY}</td>
						<td class="center">
							<a href="{apps.download_url}" title="{L_DOWNLOAD_THE_COMPLETE_PACK}">{L_DOWNLOAD_PACK}</a><br />
							# IF apps.update_url #
							/<br />
							<a href="{apps.update_url}" title="{L_DOWNLOAD_THE_UPDATE_PACK}">{L_UPDATE_PACK}</a>
							# ENDIF #
						</td>
					</tr>
					# END apps #
				</tbody>
			</table>
			# ELSE #
				<div class="success message-helper-small">{L_NO_AVAILABLES_UPDATES}</div>
			# ENDIF #
			<p class="center">
				<a href="{U_CHECK}"><i class="fa fa-download"></i></a> <a href="{U_CHECK}">{L_CHECK_FOR_UPDATES_NOW}</a>
			</p>
		# ENDIF #
		</div>
	</fieldset>
</div>