		<div id="online_table">
		<table class="module_table">
			<tr>
				<th colspan="3">
					{L_ONLINE}
				</th>
			</tr>
			<tr>
				<td class="row2" style="width:33%">
					{L_LOGIN}
				</td>
				<td class="row2" style="width:34%">
					{L_LOCATION}
				</td>
				<td class="row2" style="width:33%">
					{L_LAST_UPDATE}
				</td>
			</tr>	
			
			# START users # 
			<tr>
				<td class="row3">
					{users.USER}
					<span id="l{users.ID}"></span>
					<a href="" onclick="ajax_display_desc({users.ID}); return false;">
						<img src="{PATH_TO_ROOT}/online/plus.gif" alt="" class="valign_middle" />
					</a>
				</td>
				<td class="row3">
					{users.LOCATION}
				</td>
				<td class="row3">
					{users.LAST_UPDATE}
				</td>
			</tr>
			<tr id="td_{users.ID}" style="display:{users.DISPLAY_DESC}">
				<td colspan="3" width="100%">
				&gt;&gt;&nbsp;{users.DESC}
				</td>
			</tr>
			# END users #
			<tr>
				<td class="row1" style="text-align:center" colspan="3">
					{PAGINATION}&nbsp;
				</td>
			</tr>
		</table>
		</div>