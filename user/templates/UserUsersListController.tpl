# IF C_ARE_GROUPS #
<table>	
	<tr>
		<td style="vertical-align:top;" class="row2">
			# INCLUDE SELECT_GROUP #
		</td>
	</tr>
</table>	
<br /><br />
# ENDIF #
<h1>{@users}</h1>
<table>	
	<thead>
		<tr>
			<th>
				<a href="{SORT_LOGIN_TOP}"><i class="icon-arrow-up"></i></a>
				{@pseudo} 
				<a href="{SORT_LOGIN_BOTTOM}"><i class="icon-arrow-down"></i></a>
			</th>
			<th>
				{@email}
			</th>
			<th>
				<a href="{SORT_REGISTERED_TOP}"><i class="icon-arrow-up"></i></a>
				{@registration_date}
				<a href="{SORT_REGISTERED_BOTTOM}"><i class="icon-arrow-down"></i></a>
			</th>
			<th>
				<a href="{SORT_MSG_TOP}"><i class="icon-arrow-up"></i></a>
				{@messages}
				<a href="{SORT_MSG_BOTTOM}"><i class="icon-arrow-down"></i></a>	
			</th>
			<th>
				<a href="{SORT_LAST_CONNECT_TOP}"><i class="icon-arrow-up"></i></a>
				{@last_connection}
				<a href="{SORT_LAST_CONNECT_BOTTOM}"><i class="icon-arrow-down"></i></a>
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
					<a href="mailto:{member_list.MAIL}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/email.png" alt="{member_list.MAIL}" /></a>
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
				<a href="{member_list.U_USER_PM}"><span class="label">MP</span></a>
			</td>
		</tr>
		# END member_list #
	</tbody>
</table>
