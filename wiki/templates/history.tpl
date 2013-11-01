		# START article #
		{article.L_TITLE}
		<table>
			<thead>
				<tr>
					<th>
						{L_VERSIONS}
					</th>
					<th>
						{L_DATE}
					</th>
					<th>
						{L_AUTHOR}
					</th>
					<th>
						{L_ACTIONS}
					</th>
				</tr>
			</thead>
			<tbody>
				# START article.list #
				<tr>
					<td>
						<a href="{article.list.U_ARTICLE}">{article.list.TITLE}</a> {article.list.CURRENT_RELEASE}
					</td>
					<td>
						{article.list.DATE}
					</td>
					<td>
						{article.list.AUTHOR}
					</td>
					<td>
						{article.list.ACTIONS}
					</td>
				</tr>
				# END article.list #
			</tbody>
		</table>
		# END article #

		# START index #
		<table>
			<thead>
				<tr>
					<th>
						<a href="{index.TOP_TITLE}" class="icon-table-sort-up"></a>
						{index.L_TITLE}
						<a href="{index.BOTTOM_TITLE}" class="icon-table-sort-down"></a>
					</th>
					<th>
						<a href="{index.TOP_DATE}" class="icon-table-sort-up"></a>
						{index.L_DATE}
						<a href="{index.BOTTOM_DATE}" class="icon-table-sort-down"></a>
					</th>
					<th>
						{index.L_AUTHOR}
					</th>
				</tr>
			</thead>
			# IF index.PAGINATION #
			<tfoot>
				<tr>
					<th colspan="3">
						{index.PAGINATION}
					</th>
				</tr>
			</tfoot>
			# ENDIF #
			<tbody>
				# START index.list #
				<tr>
					<td>
						<a href="{index.list.U_ARTICLE}">{index.list.TITLE}</a>
					</td>
					<td>
						{index.list.DATE}
					</td>
					<td>
						{index.list.AUTHOR}
					</td>
				</tr>
				# END index.list #
			</tbody>
		</table>
		# END index #
		