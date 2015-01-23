		# INCLUDE FORM #
				
		<table>
			<caption>{L_USERS_MANAGEMENT}</caption>
			<thead>
				<tr>
					<th>
					</th>
					<th>
						<a href="{SORT_LOGIN_TOP}" class="fa fa-table-sort-up"></a>
						{L_LOGIN} 
						<a href="{SORT_LOGIN_BOTTOM}" class="fa fa-table-sort-down"></a>
					</th>
					<th>
						<a href="{SORT_LEVEL_TOP}" class="fa fa-table-sort-up"></a>
						{L_LEVEL}
						<a href="{SORT_LEVEL_BOTTOM}" class="fa fa-table-sort-down"></a>
					</th>
					<th>
						{L_MAIL}
					</th>
					<th>
						<a href="{SORT_REGISTERED_TOP}" class="fa fa-table-sort-up"></a>
						{L_REGISTERED}
						<a href="{SORT_REGISTERED_BOTTOM}" class="fa fa-table-sort-down"></a>
					</th>
					<th>
						<a href="{SORT_LAST_CONNECT_TOP}" class="fa fa-table-sort-up"></a>
						{L_LAST_CONNECT}
						<a href="{SORT_LAST_CONNECT_BOTTOM}" class="fa fa-table-sort-down"></a>
					</th>
					<th>
						<a href="{SORT_APPROBATION_TOP}" class="fa fa-table-sort-up"></a>
						{L_APPROBATION}
						<a href="{SORT_APPROBATION_BOTTOM}" class="fa fa-table-sort-down"></a>
					</th>
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
				# START member_list #
				<tr> 
					<td> 
						<a href="{member_list.EDIT_LINK}" title="{L_UPDATE}" class="fa fa-edit"></a>
						<a # IF member_list.C_DELETABLE #href="{member_list.DELETE_LINK}"# ELSE #href="" onclick="return false;" style="opacity: 0.3; cursor: default;"# ENDIF # title="{L_DELETE}" class="fa fa-delete"# IF member_list.C_DELETABLE # data-confirmation="delete-element"# ENDIF #></a>
					</td>
					<td>
						<a href="{member_list.U_PROFILE}" class="{member_list.LEVEL_CLASS}" # IF member_list.C_GROUP_COLOR # style="color:{member_list.GROUP_COLOR}" # ENDIF #>{member_list.LOGIN}</a>
					</td>
					<td> 
						{member_list.LEVEL}
					</td>
					<td>
						<a href="mailto:{member_list.MAIL}" class="basic-button smaller">Mail</a>
					</td>
					<td>
						{member_list.REGISTERED}
					</td>
					<td>
						{member_list.LAST_CONNECT}
					</td>
					<td>
						{member_list.APPROBATION}
					</td>
				</tr>
				# END member_list #
			</tbody>
		</table>
