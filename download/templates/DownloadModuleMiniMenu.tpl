<div class="cell-table">
	<table id="table-mini-download" class="table">
		<thead>
			<tr>
				<th aria-label="# IF C_SORT_BY_DATE #{@download.sort.date}# ELSE #{@download.sort.trophy}# ENDIF #">
					<i class="fa# IF C_SORT_BY_DATE #r fa-calendar-alt# ELSE # fa-trophy# ENDIF # hidden-small-screens" aria-hidden="true"></i>
					<span class="hidden-large-screens"># IF C_SORT_BY_DATE #{@download.sort.date}# ELSE #{@download.sort.trophy}# ENDIF #</span>
				</th>
				<th>{@common.name}</th>
				# IF NOT C_SORT_BY_DATE #
					<th aria-label="# IF C_SORT_BY_DOWNLOADS_NUMBER #{@download.sort.downloads.number}# ELSE #{@download.sort.notes}# ENDIF #">
						<i class="fa# IF C_SORT_BY_DOWNLOADS_NUMBER # fa-download# ELSE #r fa-star# ENDIF # hidden-small-screens" aria-hidden="true"></i>
						<span class="hidden-large-screens"># IF C_SORT_BY_DOWNLOADS_NUMBER #{@download.sort.downloads.number}# ELSE #{@download.sort.notes}# ENDIF #</span>
					</th>
				# ENDIF #
			</tr>
		</thead>
		<tbody>
			# IF C_ITEMS #
				# START items #
					<tr class="category-{items.CATEGORY_ID}">
						<td># IF C_SORT_BY_DATE #<time datetime="{items.DATE_ISO8601}">{items.DATE_DAY_MONTH}</time># ELSE #{items.DISPLAYED_POSITION}# ENDIF #</td>
						<td# IF C_SORT_BY_NOTATION # class="mini-download-table-name"# ENDIF #>
							<a href="{items.U_ITEM}">
								{items.TITLE}
							</a>
							<p class="align-right small">
								<a href="{items.U_CATEGORY}">
									<i class="far fa-folder"></i> {items.CATEGORY_NAME}
								</a>
							</p>
						</td>
						# IF NOT C_SORT_BY_DATE #
							<td># IF C_SORT_BY_DOWNLOADS_NUMBER #{items.DOWNLOADS_NUMBER}# ELSE #{items.STATIC_NOTATION}# ENDIF #</td>
						# ENDIF #
					</tr>
				# END items #
			# ELSE #
				<tr>
					<td colspan="# IF C_SORT_BY_DATE #2# ELSE #3# ENDIF #">{@common.no.item.now}</td>
				</tr>
			# ENDIF #
		</tbody>
	</table>
</div>
