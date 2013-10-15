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
		
		{L_USERS_MANAGEMENT}
		<table>
			<thead>
				<tr>
					<th>
					</th>
					<th>
						<a href="{SORT_LOGIN_TOP}" class="sort-up"></a>
						{L_LOGIN} 
						<a href="{SORT_LOGIN_BOTTOM}" class="sort-down"></a>
					</th>
					<th>
						<a href="{SORT_LEVEL_TOP}" class="sort-up"></a>
						{L_LEVEL}
						<a href="{SORT_LEVEL_BOTTOM}" class="sort-down"></a>
					</th>
					<th>
						{L_MAIL}
					</th>
					<th>
						<a href="{SORT_REGISTERED_TOP}" class="sort-up"></a>
						{L_REGISTERED}
						<a href="{SORT_REGISTERED_BOTTOM}" class="sort-down"></a>
					</th>
					<th>
						<a href="{SORT_LAST_CONNECT_TOP}" class="sort-up"></a>
						{L_LAST_CONNECT}
						<a href="{SORT_LAST_CONNECT_BOTTOM}" class="sort-down"></a>
					</th>
					<th>
						<a href="{SORT_APPROBATION_TOP}" class="sort-up"></a>
						{L_APPROBATION}
						<a href="{SORT_APPROBATION_BOTTOM}" class="sort-down"></a>
					</th>
				</tr>
			</thead>
			# IF C_PAGINATION #
			<tfoot>
				<tr>
					<th colspan="7">
						{PAGINATION}
					</th>
				</tr>
			</tfoot>
			# ENDIF #
			<tbody>
				# START member_list #
				<tr> 
					<td> 
						<a href="{member_list.EDIT_LINK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
						<a href="{member_list.DELETE_LINK}" onclick="javascript:return Confirm({member_list.LEVEL});"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
					<td>
						<a href="{member_list.U_PROFILE}" class="{member_list.LEVEL_CLASS}" # IF member_list.C_GROUP_COLOR # style="color:{member_list.GROUP_COLOR}" # ENDIF #>{member_list.LOGIN}</a>
					</td>
					<td> 
						{member_list.LEVEL}
					</td>
					<td>
						<a href="mailto:{member_list.MAIL}" class="small-button">Mail</a>
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
