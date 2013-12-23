<table>
	# IF C_ARCHIVES #
	<thead>
		<tr> 
			# IF NOT C_SPECIFIC_STREAM #
			<th>
				<a href="{SORT_STREAM_TOP}" class="fa fa-table-sort-up"></a>
				{@archives.stream_name}
				<a href="{SORT_STREAM_BOTTOM}" class="fa fa-table-sort-down"></a>
			</th>
			# ENDIF #
			<th>
				<a href="{SORT_SUBJECT_TOP}" class="fa fa-table-sort-up"></a>
				{@archives.name} 
				<a href="{SORT_SUBJECT_BOTTOM}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{SORT_DATE_TOP}" class="fa fa-table-sort-up"></a>
				{@archives.date} 
				<a href="{SORT_DATE_BOTTOM}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{SORT_SUBSCRIBERS_TOP}" class="fa fa-table-sort-up"></a>
				{@archives.nbr_subscribers} 
				<a href="{SORT_SUBSCRIBERS_BOTTOM}" class="fa fa-table-sort-down"></a>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="{NUMBER_COLUMN}">
				{PAGINATION}
			</th>
		</tr>
	</tfoot>
	<tbody>
		# START archives_list #
			<tr>
				# IF NOT C_SPECIFIC_STREAM #
				<td>
					<a href="{archives_list.VIEW_STREAM}">{archives_list.STREAM_NAME}</a>
				</td>
				# ENDIF #
				<td>
					<a href="{archives_list.VIEW_ARCHIVE}">{archives_list.SUBJECT}</a>
				</td>
				<td>
					{archives_list.DATE}
				</td>
				<td>
					{archives_list.NBR_SUBSCRIBERS}
				</td>
			</tr>
		# END archives_list #
	</tbody>
	# ELSE #
	<tbody>
		<tr>
			<td colspan="4">
				<span class="text-strong" >{@archives.not_archives}</span>
			</td>
		</tr>
	</tbody>
	# ENDIF #
</table>