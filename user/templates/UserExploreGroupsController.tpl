<table class="module_table" style="width:70%;">	
	<tr>
		<td style="vertical-align:top;" class="row2">
			# INCLUDE SELECT_GROUP #
		</td>
	</tr>
</table>

<div class="spacer">&nbsp;</div>

<table class="module_table" style="width: 70%;text-align:center;">
	<tr>
		<th colspan="3">
			{GROUP_NAME}
			# IF C_ADMIN #
				&nbsp;&nbsp;
				<a href="{U_ADMIN_GROUPS}" >
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" />
				</a>
			# ENDIF #
		</th>
	</tr>
	<tr>
		<td class="row3" style="font-weight: bold;width: auto;">
			{@avatar}
		</td>
		<td class="row3" style="font-weight: bold;">
			{@pseudo}
		</td>
		<td class="row3" style="font-weight: bold;">
			{@level}
		</td>
	</tr>
	
	# START members_list #
	<tr>
		<td class="row1">
			<img class="valign_middle" src="{members_list.U_AVATAR}" alt=""	/>
		</td>
		<td class="row1">
			<a href="{members_list.PROFILE_LINK}">
				{members_list.PSEUDO}
			</a>
		</td>
		<td class="row1">
			{members_list.STATUS}
		</td>
	</tr>	
	# END members_list #
</table>