	# START contributions_list #
		<table class="module_table">
			<tr>
				<th colspan="5">
					{L_CONRIBUTION_PANEL}
				</th>
			</tr>
			<tr>
				<td class="row3" style="text-align:center;">
					{contributions_list.TITLE_ASC} {L_TITLE} {contributions_list.TITLE_DESC}
				</td>
				<td class="row3" style="text-align:center;">
					{contributions_list.CONTRIBUTOR_ASC} {L_CONTRIBUTOR} {contributions_list.CONTRIBUTOR_DESC}
				</td>
				<td class="row3" style="text-align:center;">
					{contributions_list.STATUS_ASC} {L_STATUS} {contributions_list.STATUS_DESC}
				</td>
				<td class="row3" style="text-align:center;">
					{contributions_list.TIMESTAMP_ASC} {L_DATE} {contributions_list.TIMESTAMP_DESC}
				</td>
				<td class="row3" style="text-align:center;">
					{L_COMS}
				</td>
			</tr>
			# START item #
			<tr>
				<td class="row1">
				<a href="{contributions_list.item.U_ITEM}">{contributions_list.item.TITLE}</a>
				</td>
				<td class="row1" style="text-align:center;">
					<a href="{contributions_list.item.U_MEMBER}">{contributions_list.item.MEMBER}</a>
				</td>
				<td class="row1" style="text-align:center;">
					{contributions_list.item.STATUS}
				</td>
				<td class="row1" style="text-align:center;">
					{contributions_list.item.TIME}
				</td>
				<td class="row1" style="text-align:center;">
					{contributions_list.item.COMS_NUMBER}
				</td>
			</tr>	
			# END item #
			<tr>
				<td class="row2" colspan="5" style="text-align:center;">
					&nbsp;{contributions_list.PAGINATION}
				</td>
			</tr>
		</table>
	# END contributions_list #

	# START contribution #
		<table class="module_table">
			<tr>
				<th colspan="2">
					{L_CONTRIBUTION}
				</th>
			</tr>
			# START error_handler #
			<tr>
				<td colspan="2">
					<span id="errorh"></span>
					<div class="{contribution.error_handler.CLASS}">
						<img src="../templates/{THEME}/images/{contribution.error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {contribution.error_handler.L_ERROR}
					</div>
				</td>
			</tr>
			# END error_handler #
			# START properties #
			<tr>
				<td class="row3">
					{L_TITLE}
				</td>
				<td class="row1">
					{contribution.properties.TITLE}
				</td>
			</tr>
			<tr>
				<td class="row3">
					{L_MODULE}
				</td>
				<td class="row1">
					{contribution.properties.MODULE} - <a href="{contribution.properties.URL}">{L_VIEW_CONTRIBUTION}</a>
				</td>
			</tr>
			<tr>
				<td class="row3">
					{L_NOTIFICATIONS}
				</td>
				<td class="row1">
					{contribution.properties.NOTIFICATIONS}
				</td>
			</tr>
			<tr>
				<td class="row3">
					{L_STATUS}
				</td>
				<td class="row1">
					{contribution.properties.STATUS}
					<br />
					{contribution.properties.TREAT}
					<br />{L_EXPLAIN_TREAT}
				</td>
			</tr>
			# END properties #
			<tr>
				<td class="row2" colspan="2">
					&nbsp;
				</td>
			</tr>
		</table>
		
		<br />
		<br />
		<br />
	# END contribution #