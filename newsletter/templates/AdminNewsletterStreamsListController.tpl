<table>
	<thead>
		<tr>
			<th>
			</th>
			<th>
				<a href="{SORT_NAME_TOP}" class="pbt-icon-table-sort-up"></a>
				{@streams.name} 
				<a href="{SORT_NAME_BOTTOM}" class="pbt-icon-table-sort-down"></a>
			</th>
			<th>
				{@streams.description}
			</th>
			<th>
				<a href="{SORT_STATUS_TOP}" class="pbt-icon-table-sort-up"></a>
				{@streams.visible}
				<a href="{SORT_STATUS_BOTTOM}" class="pbt-icon-table-sort-down"></a>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="2">
				<a href="{C_ADD_STREAM}">{@streams.add}</a>
			</th>
			<th colspan="2">
				<span style="float:right;">{PAGINATION}</span>
			</th>
		</tr>
	</tfoot>
	<tbody>
	# IF C_STREAMS_EXIST #
		# START streams_list #
		<tr style="text-align:center;">
			<td> 
				<a href="{streams_list.EDIT_LINK}" class="pbt-icon-edit"></a>
				<a href="{streams_list.DELETE_LINK}" class="pbt-icon-delete" data-confirmation="delete-element"></a>
			</td>
			<td>
				{streams_list.NAME}
			</td>
			<td>
				{streams_list.DESCRIPTION}
			</td>
			<td>
				{streams_list.STATUS}
			</td>
		</tr>
		# END streams_list #
	# ENDIF #
	</tbody>
</table>