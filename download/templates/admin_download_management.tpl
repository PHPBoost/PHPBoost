		# INCLUDE admin_download_menu #
		
		<div id="admin_contents">
			<table>
				<thead>
					<tr>
						<th>{L_TITLE}</th>
						<th>{L_SIZE}</th>
						<th>{L_CATEGORY}</th>
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
					# START list #
					<tr> 
						<td> 
							<a href="{list.U_FILE}">{list.TITLE}</a>
						</td>
						<td> 
							{list.SIZE}
						</td>
						<td> 
							{list.CAT}
						</td>
						<td>
							{list.DATE}
						</td>
						<td>
							{list.APROBATION}
						</td>
						<td> 
							<a href="{list.U_EDIT_FILE}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
						</td>
						<td>
							<a href="{list.U_DEL_FILE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
						</td>
					</tr>
					# END list #
				</tbody>
			</table>
		</div>
		