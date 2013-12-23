		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_ADD_SMILEY}</li>
				<li>
					<a href="admin_smileys.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/smileys.png" alt="" /></a>
					<br />
					<a href="admin_smileys.php" class="quick_link">{L_SMILEY_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_smileys_add.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/smileys.png" alt="" /></a>
					<br />
					<a href="admin_smileys_add.php" class="quick_link">{L_ADD_SMILEY}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<table>
				<thead>
					<tr> 
						<th></th>
						<th>
							{L_SMILEY}
						</th>
						<th>
							{L_CODE}
						</th>
					</tr>
				</thead>
				<tbody>
					# START list #
					<tr>
						<td> 
							<a href="admin_smileys.php?id={list.IDSMILEY}&amp;edit=1" title="{L_EDIT}" class="fa fa-edit"></a>
							<a href="admin_smileys.php?id={list.IDSMILEY}&amp;del=1&amp;token={TOKEN}" title="{L_DELETE}" class="fa fa-delete" data-confirmation="delete-element"></a>
						</td>
						<td>
							<img src="{PATH_TO_ROOT}/images/smileys/{list.URL_SMILEY}" alt="" />
						</td>
						<td>
							{list.CODE_SMILEY}
						</td>
					</tr>
					# END list #
				</tbody>
			</table>
		</div>