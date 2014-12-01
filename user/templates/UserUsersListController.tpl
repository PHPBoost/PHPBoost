<section>
	<header><h1>{@users}</h1></header>
	# IF C_ARE_GROUPS #
	<table>
		<tr>
			<td style="vertical-align:top;">
				# INCLUDE SELECT_GROUP #
			</td>
		</tr>
	</table>	
	<br /><br />
	# ENDIF #
	<table>	
		<thead>
			<tr>
				<th>
					<a href="{SORT_LOGIN_TOP}" class="fa fa-table-sort-up"></a>
					{@display_name} 
					<a href="{SORT_LOGIN_BOTTOM}" class="fa fa-table-sort-down"></a>
				</th>
				<th>
					{@email}
				</th>
				<th>
					<a href="{SORT_REGISTERED_TOP}" class="fa fa-table-sort-up"></a>
					{@registration_date}
					<a href="{SORT_REGISTERED_BOTTOM}" class="fa fa-table-sort-down"></a>
				</th>
				<th>
					<a href="{SORT_MSG_TOP}" class="fa fa-table-sort-up"></a>
					{@messages}
					<a href="{SORT_MSG_BOTTOM}" class="fa fa-table-sort-down"></a>	
				</th>
				<th>
					<a href="{SORT_LAST_CONNECT_TOP}" class="fa fa-table-sort-up"></a>
					{@last_connection}
					<a href="{SORT_LAST_CONNECT_BOTTOM}" class="fa fa-table-sort-down"></a>
				</th>
				<th>
					{@private_message}
				</th>
			</tr>
		</thead>
		# IF C_PAGINATION #
		<tfoot>
			<tr>
				<th colspan="6">
					# INCLUDE PAGINATION #
				</th>
			</tr>
		</tfoot>
		# ENDIF #
		<tbody>
			# START member_list #
			<tr> 
				<td>
					<a href="{member_list.U_USER_ID}" class="{member_list.LEVEL_CLASS}" # IF member_list.C_GROUP_COLOR # style="color:{member_list.GROUP_COLOR}" # ENDIF #>{member_list.PSEUDO}</a>
				</td>
				<td> 
					# IF member_list.C_MAIL #
						<a href="mailto:{member_list.MAIL}" class="basic-button smaller">Mail</a>
					# ENDIF #
				</td>
				<td> 
					{member_list.DATE}
				</td>
				<td> 
					{member_list.MSG}
				</td>
				<td> 
					{member_list.LAST_CONNECT}
				</td>
				<td> 
					<a href="{member_list.U_USER_PM}" class="basic-button smaller">MP</a>
				</td>
			</tr>
			# END member_list #
		</tbody>
	</table>
	<footer></footer>
</section>