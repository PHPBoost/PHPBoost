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
					<a href="{SORT_LOGIN_TOP}" class="sort-up"></a>
					{@pseudo} 
					<a href="{SORT_LOGIN_BOTTOM}" class="sort-down"></a>
				</th>
				<th>
					{@email}
				</th>
				<th>
					<a href="{SORT_REGISTERED_TOP}" class="sort-up"></a>
					{@registration_date}
					<a href="{SORT_REGISTERED_BOTTOM}" class="sort-down"></a>
				</th>
				<th>
					<a href="{SORT_MSG_TOP}" class="sort-up"></a>
					{@messages}
					<a href="{SORT_MSG_BOTTOM}" class="sort-down"></a>	
				</th>
				<th>
					<a href="{SORT_LAST_CONNECT_TOP}" class="sort-up"></a>
					{@last_connection}
					<a href="{SORT_LAST_CONNECT_BOTTOM}" class="sort-down"></a>
				</th>
				<th>
					{@private_message}
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="6">
					<span class="inline">{L_PAGE} : </span>{PAGINATION}
				</th>
			</tr>
		</tfoot>
		<tbody>
			# START member_list #
			<tr> 
				<td>
					<a href="{member_list.U_USER_ID}" class="{member_list.LEVEL_CLASS}" # IF member_list.C_GROUP_COLOR # style="color:{member_list.GROUP_COLOR}" # ENDIF #>{member_list.PSEUDO}</a>
				</td>
				<td> 
					# IF member_list.C_MAIL #
						<a href="mailto:{member_list.MAIL}" class="small-button">Mail</a>
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
					<a href="{member_list.U_USER_PM}" class="small-button">MP</a>
				</td>
			</tr>
			# END member_list #
		</tbody>
	</table>
	<footer></footer>
</section>