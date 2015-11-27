<table id="table">
	<tr>
		<td>
			# INCLUDE SELECT_GROUP #
		</td>
	</tr>
</table>

<table id="table2">
	<caption>
		{GROUP_NAME}
		# IF C_ADMIN #
			<a href="{U_ADMIN_GROUPS}" class="fa fa-edit"></a>
		# ENDIF #
	</caption>
	<thead>
		<tr>
			<th>
				{@avatar}
			</th>
			<th>
				{@display_name}
			</th>
			<th>
				{@level}
			</th>
		</tr>
	</thead>
	<tbody>
		# START members_list #
		<tr>
			<td>
				# IF members_list.C_AVATAR #<img class="valign-middle" src="{members_list.U_AVATAR}" alt="{members_list.PSEUDO}"/># ENDIF #
			</td>
			<td>
				<a href="{members_list.U_PROFILE}" class="{members_list.LEVEL_CLASS}" # IF members_list.C_GROUP_COLOR # style="color:{members_list.GROUP_COLOR}" # ENDIF #>
					{members_list.PSEUDO}
				</a>
			</td>
			<td>
				{members_list.LEVEL}
			</td>
		</tr>
		# END members_list #
		# IF C_NOT_MEMBERS #
		<tr>
			<td colspan="4">
				<span class="text-strong">{@no_member}</span>
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>