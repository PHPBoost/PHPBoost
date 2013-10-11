<h1>{@archives.list}</h1>
<table>
	# IF C_ARCHIVES #
	<thead>
		<tr> 
			# IF NOT C_SPECIFIC_STREAM #
			<th>
				<a href="{SORT_STREAM_TOP}"><i class="icon-arrow-up"></i></a>
				{@archives.stream_name}
				<a href="{SORT_STREAM_BOTTOM}"><i class="icon-arrow-down"></i></a>
			</th>
			# ENDIF #
			<th>
				<a href="{SORT_SUBJECT_TOP}"><i class="icon-arrow-up"></i></a>
				{@archives.name} 
				<a href="{SORT_SUBJECT_BOTTOM}"><i class="icon-arrow-down"></i></a>
			</th>
			<th>
				<a href="{SORT_DATE_TOP}"><i class="icon-arrow-up"></i></a>
				{@archives.date} 
				<a href="{SORT_DATE_BOTTOM}"><i class="icon-arrow-down"></i></a>
			</th>
			<th>
				<a href="{SORT_SUBSCRIBERS_TOP}"><i class="icon-arrow-up"></i></a>
				{@archives.nbr_subscribers} 
				<a href="{SORT_SUBSCRIBERS_BOTTOM}"><i class="icon-arrow-down"></i></a>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="{NUMBER_COLUMN}">
				{@newsletter.page} : {PAGINATION}
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
				<span class="text_strong" >{@archives.not_archives}</span>
			</td>
		</tr>
	</tbody>
	# ENDIF #
</table>