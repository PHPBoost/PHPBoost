<table class="module_table" style="width:70%;">	
	<tr>
		<td style="vertical-align:top;" class="row2">
			# INCLUDE SELECT #
		</td>
	</tr>
</table>

<div class="spacer">&nbsp;</div>

<table class="module_table" style="width: 70%;text-align:center;">
	<tr>
		<th colspan="3">
			{GROUP_NAME}&nbsp;&nbsp;{ADMIN_GROUPS}
		</th>
	</tr>
	<tr>
		<td class="row3" style="font-weight: bold;width: 120px;">
			{@avatar}
		</td>
		<td class="row3" style="font-weight: bold;">
			{@pseudo}
		</td>
		<td class="row3" style="font-weight: bold;">
			{@status}
		</td>
	</tr>
	
	# START members_list #
	<tr>
		<td class="row1">
			{members_list.AVATAR}
		</td>
		<td class="row1">
			{members_list.PSEUDO}
		</td>
		<td class="row1">
			{members_list.STATUS}
		</td>
	</tr>	
	# END members_list #
</table>