<table class="module_table" style="width: 98%;">	
			<tr>
				<th colspan="8">
					{@profile}
				</th>
			</tr>
			<tr>
				<td colspan="8" class="row1">
					{PAGINATION}&nbsp;
				</td>
			</tr>	
			<tr style="font-weight:bold;text-align: center;">
				<td class="row3">
					<a href="{SORT_LOGIN_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
					{@pseudo} 
					<a href="{SORT_LOGIN_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
				</td>
				<td class="row3">
					{@mail}
				</td>
				<td class="row3">
					<a href="{SORT_REGISTERED_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
					{@registered_on}
					<a href="{SORT_REGISTERED_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
				</td>
				<td class="row3">
					<a href="{SORT_MSG_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
					{@message}
					<a href="{SORT_MSG_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>	
				</td>
				<td class="row3">
					<a href="{SORT_LAST_CONNECT_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
					{@last_connect}
					<a href="{SORT_LAST_CONNECT_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
				</td>
				<td class="row3">
					{@private_message}
				</td>
			</tr>
			# START member_list #
			<tr> 
				<td class="row2" style="text-align:center;">
					<a href="{member_list.U_USER_ID}">{member_list.PSEUDO}</a>
				</td>
				<td class="row2" style="text-align:center;"> 
					{member_list.MAIL}
				</td>
				<td class="row2" style="text-align:center;"> 
					{member_list.DATE}
				</td>
				<td class="row2" style="text-align:center;"> 
					{member_list.MSG}
				</td>
				<td class="row2" style="text-align:center;"> 
					{member_list.LAST_CONNECT}
				</td>
				<td class="row2" style="text-align:center;"> 
					<a href="{member_list.U_USER_PM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/pm.png" alt="{L_PRIVATE_MESSAGE}" /></a>
				</td>
			</tr>
			# END member_list #
			<tr>
				<td colspan="8" class="row1">
					<span style="float:left;">{PAGINATION}</span>
				</td>
			</tr>
		</table>