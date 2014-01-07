<div id="admin_quick_menu">
	<ul>
		<li class="title_menu">{L_WEBSITE_UPDATES}</li>
		<li>
			<a href="updates.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/updater.png" alt="" /></a>
			<br />
			<a href="updates.php" class="quick_link">{L_WEBSITE_UPDATES}</a>
		</li>
		<li>
			<a href="updates.php?type=kernel"><img src="{PATH_TO_ROOT}/templates/default/images/admin/configuration.png" alt="" /></a>
			<br />
			<a href="updates.php?type=kernel" class="quick_link">{L_KERNEL}</a>
		</li>
		<li>
			<a href="updates.php?type=module"><img src="{PATH_TO_ROOT}/templates/default/images/admin/modules.png" alt="" /></a>
			<br />
			<a href="updates.php?type=module" class="quick_link">{L_MODULES}</a>
		</li>
		<li>
			<a href="updates.php?type=template"><img src="{PATH_TO_ROOT}/templates/default/images/admin/themes.png" alt="" /></a>
			<br />
			<a href="updates.php?type=template" class="quick_link">{L_THEMES}</a>
		</li>
	</ul>
</div>

<div id="admin_contents">
	# IF C_INCOMPATIBLE_PHP_VERSION #
		<div class="message-helper warning message-helper-small">
			<i class="fa fa-warning"></i>
			<div class="message-helper-content">{L_INCOMPATIBLE_PHP_VERSION}</div>
		</div>
	# ELSE #		
		<table>
			<caption>{L_WEBSITE_UPDATES}</caption>
		# IF C_UPDATES #
 			<div class="message-helper warning message-helper-small">
				<i class="fa fa-warning"></i>
				<div class="message-helper-content">{L_UPDATES_ARE_AVAILABLE}</div>
			</div>
			<thead>
				<tr>
					# IF C_ALL #
					<th style="width:50px;">{L_TYPE}</td>
					# ENDIF #
					<th>{L_DESCRIPTION}</td>
					<th style="width:75px;">{L_PRIORITY}</td>
					<th style="width:75px;">{L_UPDATE_DOWNLOAD}</td>
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
						<div style="padding:5px;padding-top:10px;text-align:justify;">{apps.short_description}</div>
						<p style="text-align:right;"><a href="detail.php?identifier={apps.identifier}" title="{L_MORE_DETAILS}" class="small">{L_DETAILS}</a></p>
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
		</table>
			<div class="message-helper success message-helper-small">
				<i class="fa fa-success"></i>
				<div class="message-helper-content">{L_NO_AVAILABLES_UPDATES}</div>
			</div>
		# ENDIF #
		<p class="center" style="margin-top:100px;">
			<a href="{U_CHECK}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/updater.png" alt="" /></a>
			<br />
			<a href="{U_CHECK}">{L_CHECK_FOR_UPDATES_NOW}</a>
		</p>
	# ENDIF #
</div>