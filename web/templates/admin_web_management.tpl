		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_WEB_MANAGEMENT}</li>
				<li>
					<a href="admin_web_cat.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_cat.php" class="quick-link">{L_WEB_CAT}</a>
				</li>
				<li>
					<a href="admin_web_cat.php#add_cat"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_cat.php#add_cat" class="quick-link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_web.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web.php" class="quick-link">{L_WEB_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_web_add.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_add.php" class="quick-link">{L_WEB_ADD}</a>
				</li>
				<li>
					<a href="admin_web_config.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_config.php" class="quick-link">{L_WEB_CONFIG}</a>
				</li>
			</ul>
		</div> 
		
		<div id="admin-contents">
			<table>
				<caption>{L_LISTE}</caption>
				<thead>
					<tr> 
						<th>{L_NAME}</th>
						<th>{L_CATEGORY}</th>
						<th>{L_VIEW}</th>
						<th>{L_DATE}</th>
						<th>{L_APROB}</th>
						<th>{L_UPDATE}</th>
						<th>{L_DELETE}</th>
					</tr>
				</thead>
				# IF C_PAGINATION #
				<tfoot>
					<tr>
						<th colspan="7">
							# INCLUDE PAGINATION #
						</th>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START web #
					<tr>
						<td>
							<a href="{PATH_TO_ROOT}/web/web.php?cat={web.IDCAT}&amp;id={web.IDWEB}">{web.NAME}</a>
						</td>
						<td>
							{web.CAT}
						</td>
						<td>
							{web.COMPT}
						</td>
						<td>
							{web.DATE}
						</td>
						<td>
							{web.APROBATION}
						</td>
						<td>
							<a href="admin_web.php?id={web.IDWEB}" title="{L_UPDATE}" class="fa fa-edit"></a>
						</td>
						<td>
							<a href="admin_web.php?delete=true&amp;id={web.IDWEB}&amp;token={TOKEN}" class="fa fa-delete" data-confirmation="delete-element"></a>
						</td>
					</tr>
					# END web #
				</tbody>
			</table>
		</div>
		