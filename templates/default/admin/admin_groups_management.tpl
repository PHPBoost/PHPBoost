		<nav id="admin-quick-menu">
			<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" aria-label="{L_GROUPS_MANAGEMENT}">
				<i class="fa fa-bars" aria-hidden="true"></i> {L_GROUPS_MANAGEMENT}
			</a>
			<ul>
				<li>
					<a href="admin_groups.php" class="quick-link">{L_GROUPS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_groups.php?add=1" class="quick-link">{L_ADD_GROUPS}</a>
				</li>
			</ul>
		</nav>

		<div id="admin-contents">
			<fieldset class="fieldset-content">
				<legend>{L_GROUPS_MANAGEMENT}</legend>
				<div class="fieldset-inset">
					<table class="table">
						<thead>
							<tr>
								<th>
									{L_NAME}
								</th>
								<th>
									{L_IMAGE}
								</th>
								<th>
									{L_UPDATE}
								</th>
								<th>
									{L_DELETE}
								</th>
							</tr>
						</thead>
						<tbody>
							# START group #
							<tr>
								<td>
									<a href="{group.U_USER_GROUP}"# IF group.C_GROUP_COLOR # style="color:{group.GROUP_COLOR}"# ENDIF #>{group.NAME}</a>
								</td>
								<td>
									{group.IMAGE}
								</td>
								<td>
									<a href="admin_groups.php?id={group.ID}" aria-label="{L_UPDATE}"><i class="fa fa-edit" aria-hidden="true" aria-label="{L_UPDATE}"></i></a>
								</td>
								<td>
									<a href="admin_groups.php?del=1&amp;id={group.ID}&amp;token={TOKEN}" data-confirmation="delete-element" aria-label="{L_DELETE}"><i class="fa fa-trash-alt" aria-hidden="true" aria-label="{L_DELETE}"></i></a>
								</td>
							</tr>
							# END group #
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4">
									<span><a href="admin_groups.php?add=1" aria-label="{L_ADD_GROUPS}">{L_ADD_GROUPS}</a></span>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</fieldset>
		</div>
