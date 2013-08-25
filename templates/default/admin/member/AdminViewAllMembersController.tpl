		<script type="text/javascript">
		<!--
		function Confirm(level) {
			ok = confirm("{L_CONFIRM_DEL_USER}");
			if (ok && (level == 2)) {
				return confirm("{L_CONFIRM_DEL_ADMIN}");
			}
			return ok;
		}
		-->
		</script>
		
		# INCLUDE FORM #
		
		<table  class="module_table">
			<tr> 
				<th colspan="9">
					{L_USERS_MANAGEMENT}
				</th>
			</tr>
			<tr style="text-align:center;">
				<td class="row1">
				</td>
				<td class="row1">
					<a href="{SORT_LOGIN_TOP}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" /></a>
					{L_LOGIN} 
					<a href="{SORT_LOGIN_BOTTOM}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" /></a>
				</td>
				<td class="row1">
					<a href="{SORT_LEVEL_TOP}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" /></a>
					{L_LEVEL}
					<a href="{SORT_LEVEL_BOTTOM}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" /></a>
				</td>
				<td class="row1">
					{L_MAIL}
				</td>
				<td class="row1">
					<a href="{SORT_REGISTERED_TOP}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" /></a>
					{L_REGISTERED}
					<a href="{SORT_REGISTERED_BOTTOM}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" /></a>
				</td>
				<td class="row1">
					<a href="{SORT_LAST_CONNECT_TOP}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" /></a>
					{L_LAST_CONNECT}
					<a href="{SORT_LAST_CONNECT_BOTTOM}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" /></a>
				</td>
				<td class="row1">
					<a href="{SORT_APPROBATION_TOP}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" /></a>
					{L_APPROBATION}
					<a href="{SORT_APPROBATION_BOTTOM}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" /></a>
				</td>
			</tr>
			
			# START member_list #
			<tr style="text-align:center;"> 
				<td class="row2"> 
					<a href="{member_list.EDIT_LINK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
					<a href="{member_list.DELETE_LINK}" onclick="javascript:return Confirm({member_list.LEVEL});"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
				</td>
				<td class="row2">
					<a href="{member_list.U_PROFILE}" class="{member_list.LEVEL_CLASS}" # IF member_list.C_GROUP_COLOR # style="color:{member_list.GROUP_COLOR}" # ENDIF #>{member_list.LOGIN}</a>
				</td>
				<td class="row2"> 
					{member_list.LEVEL}
				</td>
				<td class="row2">
					<a href="mailto:{member_list.MAIL}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/email.png" alt="{member_list.MAIL}" title="{member_list.MAIL}" /></a>
				</td>
				<td class="row2">
					{member_list.REGISTERED}
				</td>
				<td class="row2">
					{member_list.LAST_CONNECT}
				</td>
				<td class="row2">
					{member_list.APPROBATION}
				</td>
			</tr>
			# END member_list #
		
		</table>
		
		# IF C_PAGINATION #<p style="text-align: center;">{PAGINATION}</p># ENDIF #
