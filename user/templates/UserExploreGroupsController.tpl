<div class="center" style="width: 70%;">
	<table class="module_table">	
		<tr>
			<td style="vertical-align:top;" class="row2">
				# INCLUDE SELECT_GROUP #
			</td>
		</tr>
	</table>

	<div class="spacer">&nbsp;</div>

	{GROUP_NAME}
	# IF C_ADMIN #
		<a href="{U_ADMIN_GROUPS}" class="pbt-icon-edit"></a>
	# ENDIF #
	<table>
		<thead>
			<tr>
				<th>
					{@avatar}
				</th>
				<th>
					{@pseudo}
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
					<img class="valign_middle" src="{members_list.U_AVATAR}" alt="{members_list.PSEUDO}"/>
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
					<span class="text_strong">{@no_member}</span>
				</td>
			</tr>
			# ENDIF #
		</tbody>
	</table>
</div>