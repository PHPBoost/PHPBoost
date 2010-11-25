		# START article #
		<table class="module_table">
			<tr>
				<th colspan="4">
					{article.L_TITLE}
				</th>
			</tr>
			<tr>
				<td class="row3" style="text-align:center;">
					{L_VERSIONS}
				</td>
				<td class="row3" style="text-align:center;">
					{L_DATE}
				</td>
				<td class="row3" style="text-align:center;">
					{L_AUTHOR}
				</td>
				<td class="row3" style="text-align:center;">
					{L_ACTIONS}
				</td>
			</tr>						
			# START article.list #
			<tr>
				<td class="row2" style="text-align:center;">
					<a href="{article.list.U_ARTICLE}">{article.list.TITLE}</a> {article.list.CURRENT_RELEASE}
				</td>
				<td class="row2" style="text-align:center;">
					{article.list.DATE}
				</td>
				<td class="row2" style="text-align:center;">
					{article.list.AUTHOR}
				</td>
				<td class="row2" style="text-align:center;">
					{article.list.ACTIONS}
				</td>
			</tr>
			# END article.list #
		</table>
		# END article #

		# START index #
		<table class="module_table">
			<tr>
				<th colspan="4">
					{index.L_HISTORY}
				</th>
			</tr>
			<tr>
				<td class="row1" style="text-align:center;">
					{index.ARROW_TOP_TITLE}
					{index.L_TITLE}
					{index.ARROW_BOTTOM_TITLE}
				</td>
				<td class="row1" style="text-align:center;">
					{index.ARROW_TOP_DATE}
					{index.L_DATE}
					{index.ARROW_BOTTOM_DATE}
				</td>
				<td class="row1" style="text-align:center;">
					{index.L_AUTHOR}
				</td>
			</tr>						
			# START index.list #
			<tr>
				<td class="row2" style="text-align:center;">
					<a href="{index.list.U_ARTICLE}">{index.list.TITLE}</a>
				</td>
				<td class="row2" style="text-align:center;">
					{index.list.DATE}
				</td>
				<td class="row2" style="text-align:center;">
					{index.list.AUTHOR}
				</td>
			</tr>
			# END index.list #
			<tr>
				<td class="row2" style="text-align:center;" colspan="3">
					&nbsp;{index.PAGINATION}
				</td>
			</tr>
		</table>
		# END index #
		