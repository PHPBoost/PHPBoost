<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" aria-label="{L_GROUPS_MANAGEMENT}">
		<i class="fa fa-bars" aria-hidden="true"></i> {@user.groups.management}
	</a>
	<ul>
		<li>
			<a href="admin_groups.php" class="quick-link">{@user.groups.management}</a>
		</li>
		<li>
			<a href="admin_groups.php?add=1" class="quick-link">{@user.add.group}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	<fieldset class="fieldset-content">
		<legend>{@user.groups.management}</legend>
		<div class="fieldset-inset">
			<table class="table">
				<thead>
					<tr>
						<th>
							{@common.name}
						</th>
						<th>
							{@common.image}
						</th>
						<th>
							{@common.edit}
						</th>
						<th>
							{@common.delete}
						</th>
					</tr>
				</thead>
				<tbody>
					# START group #
						<tr>
							<td>
								<a href="{group.U_GROUP}"# IF group.C_GROUP_COLOR # style="color:{group.GROUP_COLOR}"# ENDIF #>{group.NAME}</a>
							</td>
							<td>
								# IF group.C_THUMBNAIL #<img src="{group.U_THUMBNAIL}" alt="{group.NAME}"># ENDIF #
							</td>
							<td>
								<a href="admin_groups.php?id={group.ID}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
							</td>
							<td>
								<a href="admin_groups.php?del=1&amp;id={group.ID}&amp;token={TOKEN}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
							</td>
						</tr>
					# END group #
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4">
							<a class="button link-color" href="admin_groups.php?add=1">{@user.add.group}</a>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</fieldset>
</div>
